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
use App\Models\User;
use App\Notifications\GoalThresholdNotification;
use App\Notifications\GoalCompletedNotification;
class SavingController extends Controller
{
    public function store(
        Request $request,
        Goal $goal,
        CurrencyService $currencyService
    ) {
        $this->authorizeGoal($goal);
if (
    $goal->deadline
    &&
    $goal->deadline->lt(today())
) {
    return back()
        ->withErrors([
            'amount' =>
                __('messages.goal_deadline_passed_cannot_save'),
        ])
        ->withInput();
}

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

$remaining = round(
    (float) $lockedGoal->target_amount
    - $currentSaved,
    2
);


            /*
            |--------------------------------------------------------------------------
            | منع تجاوز الهدف
            |--------------------------------------------------------------------------
            */

           if (
    round((float) $validated['amount'], 2)
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

$goal->refresh();

$this->checkGoalThreshold(
    Auth::id(),
    $goal
);
        flash()->success(
            __('messages.saving_created')
        );


        return redirect()
            ->route(
                'goals.show',
                $goal
            );
    }
public function exchangePreview(
    Request $request,
    Goal $goal,
    CurrencyService $currencyService
) {
    $this->authorizeGoal($goal);

    $validated = $request->validate([
        'account_id' => [
            'required',

            Rule::exists('accounts', 'id')
                ->where(
                    'user_id',
                    Auth::id()
                ),
        ],

        'amount' => [
            'required',
            'numeric',
            'min:0.01',
        ],
    ]);


    $account = Account::where(
        'id',
        $validated['account_id']
    )
        ->where(
            'user_id',
            Auth::id()
        )
        ->firstOrFail();


    $amount =
        (float) $validated['amount'];


    /*
    |--------------------------------------------------------------------------
    | Remaining Goal Amount
    |--------------------------------------------------------------------------
    */

    $savedAmount =
        (float) $goal
            ->savings()
            ->sum('amount');


    $remaining = round(
    max(
        (float) $goal->target_amount
        - $savedAmount,
        0
    ),
    2
);


    if ($amount > $remaining) {

        return response()->json([
            'message' =>
                __('messages.saving_exceeds_goal_remaining'),

            'remaining' =>
                round($remaining, 2),

        ], 422);
    }


    /*
    |--------------------------------------------------------------------------
    | Same Currency
    |--------------------------------------------------------------------------
    */

    if (
        $goal->currency
        ===
        $account->currency
    ) {

        return response()->json([
            'same_currency' => true,

            'goal_currency' =>
                $goal->currency,

            'account_currency' =>
                $account->currency,

            'saving_amount' =>
                round($amount, 2),

            'account_amount' =>
                round($amount, 2),

            'rate' => 1,

            'inverse_rate' => 1,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Currency Conversion
    |--------------------------------------------------------------------------
    */

    try {

        $rate =
            $currencyService->getRate(
                $goal->currency,
                $account->currency
            );


        $accountAmount =
            round(
                $amount * $rate,
                2
            );


        $inverseRate =
            $rate > 0
                ? 1 / $rate
                : null;


        return response()->json([
            'same_currency' => false,

            'goal_currency' =>
                $goal->currency,

            'account_currency' =>
                $account->currency,

            'saving_amount' =>
                round($amount, 2),

            'account_amount' =>
                $accountAmount,

            'rate' =>
                round($rate, 8),

            'inverse_rate' =>
                $inverseRate
                    ? round($inverseRate, 8)
                    : null,
        ]);


    } catch (\Throwable $e) {

        return response()->json([
            'message' =>
                __('messages.currency_service_unavailable'),

        ], 503);
    }
}

    public function destroy(
    Saving $saving
) {
    $saving->load([
        'goal',
        'account',
    ]);


    $goal =
        $saving->goal;


    $this->authorizeGoal(
        $goal
    );


    DB::transaction(function () use (
        $saving,
        $goal
    ) {

        /*
        |--------------------------------------------------------------------------
        | Original Account
        |--------------------------------------------------------------------------
        |
        | نرجع المبلغ إلى نفس الحساب
        | الذي تم الخصم منه وقت إنشاء الادخار.
        |
        */

        $account =
            Account::where(
                'id',
                $saving->account_id
            )
                ->where(
                    'user_id',
                    Auth::id()
                )
                ->lockForUpdate()
                ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Lock Goal
        |--------------------------------------------------------------------------
        */

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
        | Return Money
        |--------------------------------------------------------------------------
        |
        | نرجع نفس المبلغ الذي خرج من الحساب.
        |
        | مثال:
        |
        | Saving:
        | 100 USD
        |
        | Account deduction:
        | 318 ILS
        |
        | عند الحذف:
        | +318 ILS لنفس الحساب.
        |
        */

        $amountToReturn =
            $saving->source_amount
            ?? $saving->amount;


        $account->increment(
            'balance',
            $amountToReturn
        );


        /*
        |--------------------------------------------------------------------------
        | Delete Saving
        |--------------------------------------------------------------------------
        */

        $saving->delete();


        /*
        |--------------------------------------------------------------------------
        | Recalculate Goal
        |--------------------------------------------------------------------------
        */

        $newSavedAmount =
            (float) $lockedGoal
                ->savings()
                ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | Goal Status
        |--------------------------------------------------------------------------
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

private function checkGoalThreshold(
    int $userId,
    Goal $goal
): void {
    if ((float) $goal->target_amount <= 0) {
        return;
    }

    $savedAmount = (float) $goal
        ->savings()
        ->sum('amount');

    $percentage = round(
        (
            $savedAmount
            /
            (float) $goal->target_amount
        ) * 100,
        2
    );

    $user = User::findOrFail($userId);


    /*
    |--------------------------------------------------------------------------
    | Goal Completed
    |--------------------------------------------------------------------------
    */

    if ($percentage >= 100) {

        $alreadyCompletedNotified = $user
            ->notifications()
            ->where(
                'data->type',
                'goal_completed'
            )
            ->where(
                'data->goal_id',
                $goal->id
            )
            ->exists();


        if (! $alreadyCompletedNotified) {

            $user->notify(
                new GoalCompletedNotification(
                    $goal
                )
            );
        }

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Goal 80% Warning
    |--------------------------------------------------------------------------
    */

    if ($percentage < 80) {
        return;
    }


    $alreadyThresholdNotified = $user
        ->notifications()
        ->where(
            'data->type',
            'goal_threshold'
        )
        ->where(
            'data->goal_id',
            $goal->id
        )
        ->exists();


    if ($alreadyThresholdNotified) {
        return;
    }


    $user->notify(
        new GoalThresholdNotification(
            $goal,
            $percentage
        )
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
