<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Goal;
use App\Models\Saving;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SavingController extends Controller
{
    public function store(
        Request $request,
        Goal $goal,
        CurrencyService $currencyService
    ) {
        $this->authorizeGoal($goal);


        if ($goal->status === 'completed') {
            return back()->withErrors([
                'amount' =>
                    __('messages.goal_already_completed'),
            ])->withInput();
        }


        if ($goal->status === 'cancelled') {
            return back()->withErrors([
                'amount' =>
                    __('messages.goal_cancelled_cannot_save'),
            ])->withInput();
        }


        $validated = $request->validate([
            'account_id' => [
                'required',

                Rule::exists('accounts', 'id')
                    ->where(function ($query) {
                        $query->where(
                            'user_id',
                            Auth::id()
                        );
                    }),
            ],

            /*
             * هذا المبلغ بعملة الهدف
             *
             * Goal USD:
             * amount = 100
             *
             * يعني المستخدم يريد إضافة 100 USD للهدف.
             */
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            'note' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | الحساب الذي سيتم الخصم منه
        |--------------------------------------------------------------------------
        */

        $account = Account::where(
            'user_id',
            Auth::id()
        )->findOrFail(
            $validated['account_id']
        );


        /*
        |--------------------------------------------------------------------------
        | التحويل من عملة الهدف إلى عملة الحساب
        |--------------------------------------------------------------------------
        |
        | مثال:
        |
        | Goal = USD
        | Account = ILS
        | Saving = 100 USD
        |
        | نريد معرفة كم ILS يجب خصمه.
        |
        */

        if (
            $goal->currency
            ===
            $account->currency
        ) {
            $exchangeRate = 1;

            $sourceAmount =
                (float) $validated['amount'];
        } else {
         try {

    $exchangeRate =
        $currencyService->getRate(
            $goal->currency,
            $account->currency
        );

} catch (\RuntimeException $e) {

    return back()
        ->withErrors([
            'account_id' =>
                __('messages.currency_service_unavailable'),
        ])
        ->withInput();
}

            $sourceAmount = round(
                (float) $validated['amount']
                * $exchangeRate,
                2
            );
        }


        DB::transaction(function () use (
            $validated,
            $goal,
            $account,
            $sourceAmount,
            $exchangeRate
        ) {

            /*
            |--------------------------------------------------------------------------
            | Lock الحساب والهدف
            |--------------------------------------------------------------------------
            |
            | هذا مهم لو صار طلبين بنفس اللحظة.
            |
            */

            $lockedAccount =
                Account::where(
                    'id',
                    $account->id
                )
                ->where(
                    'user_id',
                    Auth::id()
                )
                ->lockForUpdate()
                ->firstOrFail();


            $lockedGoal =
                Goal::where(
                    'id',
                    $goal->id
                )
                ->where(
                    'user_id',
                    Auth::id()
                )
                ->lockForUpdate()
                ->firstOrFail();


            /*
            |--------------------------------------------------------------------------
            | التأكد مرة ثانية أن الهدف قابل للادخار
            |--------------------------------------------------------------------------
            */

            if (
                $lockedGoal->status
                === 'completed'
            ) {
                throw ValidationException::withMessages([
                    'amount' =>
                        __('messages.goal_already_completed'),
                ]);
            }


            if (
                $lockedGoal->status
                === 'cancelled'
            ) {
                throw ValidationException::withMessages([
                    'amount' =>
                        __('messages.goal_cancelled_cannot_save'),
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | حساب المبلغ المدخر والمتبقي
            |--------------------------------------------------------------------------
            */

            $currentSaved =
                (float) $lockedGoal
                    ->savings()
                    ->sum('amount');


            $remaining =
                (float) $lockedGoal->target_amount
                - $currentSaved;


            /*
            |--------------------------------------------------------------------------
            | منع تجاوز الهدف
            |--------------------------------------------------------------------------
            */

            if (
                (float) $validated['amount']
                > $remaining
            ) {
                throw ValidationException::withMessages([
                    'amount' =>
                        __('messages.saving_exceeds_goal_remaining'),
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | التأكد من الرصيد
            |--------------------------------------------------------------------------
            */

            if (
                (float) $lockedAccount->balance
                < $sourceAmount
            ) {
                throw ValidationException::withMessages([
                    'account_id' =>
                        __('messages.insufficient_account_balance'),
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | خصم المبلغ من الحساب
            |--------------------------------------------------------------------------
            */

            $lockedAccount->decrement(
                'balance',
                $sourceAmount
            );


            /*
            |--------------------------------------------------------------------------
            | إنشاء Saving
            |--------------------------------------------------------------------------
            */

            Saving::create([
                'goal_id' =>
                    $lockedGoal->id,

                'account_id' =>
                    $lockedAccount->id,

                /*
                 * ما وصل إلى الهدف
                 */
                'amount' =>
                    $validated['amount'],

                'currency' =>
                    $lockedGoal->currency,

                /*
                 * ما خرج من الحساب
                 */
                'source_amount' =>
                    $sourceAmount,

                'source_currency' =>
                    $lockedAccount->currency,

                'exchange_rate' =>
                    $exchangeRate,

                'date' =>
                    $validated['date'],

                'note' =>
                    $validated['note'] ?? null,
            ]);


            /*
            |--------------------------------------------------------------------------
            | تحديث المبلغ الحالي
            |--------------------------------------------------------------------------
            */

            $newSavedAmount =
                $currentSaved
                + (float) $validated['amount'];


            $newStatus =
                $newSavedAmount
                    >= (float) $lockedGoal->target_amount
                    ? 'completed'
                    : 'active';


            $lockedGoal->update([
                'current_amount' =>
                    $newSavedAmount,

                'status' =>
                    $newStatus,
            ]);
        });


        flash()->success(
            __('messages.saving_created')
        );


        return redirect()
            ->route(
                'goals.show',
                $goal
            );
    }


    public function destroy(
        Request $request,
        Saving $saving
    ) {
        $saving->load('goal');


        $goal =
            $saving->goal;


        $this->authorizeGoal(
            $goal
        );


        /*
        |--------------------------------------------------------------------------
        | عند حذف Saving
        |--------------------------------------------------------------------------
        |
        | لازم نرجع قيمته إلى حساب بنفس عملة الهدف.
        |
        | Goal USD
        | Saving 100 USD
        |
        | يرجع 100 USD إلى حساب USD.
        |
        */

        $validated = $request->validate([
            'return_account_id' => [
                'required',

                Rule::exists('accounts', 'id')
                    ->where(function ($query) use ($goal) {

                        $query
                            ->where(
                                'user_id',
                                Auth::id()
                            )
                            ->where(
                                'currency',
                                $goal->currency
                            );
                    }),
            ],
        ]);


        DB::transaction(function () use (
            $validated,
            $saving,
            $goal
        ) {

            $returnAccount =
                Account::where(
                    'id',
                    $validated['return_account_id']
                )
                ->where(
                    'user_id',
                    Auth::id()
                )
                ->where(
                    'currency',
                    $goal->currency
                )
                ->lockForUpdate()
                ->firstOrFail();


            $lockedGoal =
                Goal::where(
                    'id',
                    $goal->id
                )
                ->where(
                    'user_id',
                    Auth::id()
                )
                ->lockForUpdate()
                ->firstOrFail();


            /*
             * نرجع قيمة الادخار نفسها
             * بعملة الهدف.
             */
            $returnAccount->increment(
                'balance',
                $saving->amount
            );


            /*
             * نحذف Saving
             */
            $saving->delete();


            /*
             * نعيد حساب مجموع الادخار.
             */
            $newSavedAmount =
                (float) $lockedGoal
                    ->savings()
                    ->sum('amount');


            /*
             * إذا كان الهدف مكتمل
             * وبعد الحذف صار أقل من الهدف
             * يرجع Active.
             *
             * لو كان Cancelled
             * نخليه Cancelled.
             */

            if (
                $lockedGoal->status
                === 'cancelled'
            ) {
                $newStatus =
                    'cancelled';
            } else {
                $newStatus =
                    $newSavedAmount
                        >= (float) $lockedGoal->target_amount
                        ? 'completed'
                        : 'active';
            }


            $lockedGoal->update([
                'current_amount' =>
                    $newSavedAmount,

                'status' =>
                    $newStatus,
            ]);
        });


        flash()->warning(
            __('messages.saving_deleted')
        );


        return redirect()
            ->route(
                'goals.show',
                $goal
            );
    }


    private function authorizeGoal(
        Goal $goal
    ) {
        if (
            $goal->user_id
            !== Auth::id()
        ) {
            abort(403);
        }
    }
}