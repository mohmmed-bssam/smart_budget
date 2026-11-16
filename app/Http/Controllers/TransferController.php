<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Setting;
use App\Models\Transfer;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TransferController extends Controller
{
   public function index()
{
    $itemsPerPage = (int) Setting::where('key', 'items_per_page')
        ->value('value');

    $itemsPerPage = $itemsPerPage ?: 10;

    $transfers = Transfer::where('user_id', Auth::id())
        ->with([
            'fromAccount',
            'toAccount',
        ])
        ->latest('transfer_date')
        ->paginate($itemsPerPage);

    return view(
        'transfers.index',
        compact('transfers')
    );
}


    public function create()
    {
        $accounts = Account::where(
            'user_id',
            Auth::id()
        )
            ->orderBy('created_at')
            ->get();

        return view(
            'transfers.create',
            compact('accounts')
        );
    }


    public function store(
        Request $request,
        CurrencyService $currencyService
    ) {
        $validated = $request->validate([
            'from_account_id' => [
                'required',

                Rule::exists('accounts', 'id')
                    ->where(function ($query) {
                        $query->where(
                            'user_id',
                            Auth::id()
                        );
                    }),
            ],

            'to_account_id' => [
                'required',
                'different:from_account_id',

                Rule::exists('accounts', 'id')
                    ->where(function ($query) {
                        $query->where(
                            'user_id',
                            Auth::id()
                        );
                    }),
            ],

            'from_amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'transfer_date' => [
                'required',
                'date',
            ],

            'note' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]
        ,[
            'to_account_id.different' =>
            __('messages.transfer_accounts_must_be_different'),
        ]);


        $fromAccount = Account::where(
            'user_id',
            Auth::id()
        )->findOrFail(
            $validated['from_account_id']
        );


        $toAccount = Account::where(
            'user_id',
            Auth::id()
        )->findOrFail(
            $validated['to_account_id']
        );




        if ($fromAccount->balance < $validated['from_amount']) {
            return back()
                ->withErrors([
                    'from_amount' =>
                        __('messages.insufficient_account_balance'),
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | حساب قيمة التحويل
        |--------------------------------------------------------------------------
        */

        if (
            $fromAccount->currency
            ===
            $toAccount->currency
        ) {
            $exchangeRate = 1;

            $toAmount =
                $validated['from_amount'];
        } else {
            $exchangeRate =
                $currencyService->getRate(
                    $fromAccount->currency,
                    $toAccount->currency
                );

            $toAmount = round(
                $validated['from_amount']
                * $exchangeRate,
                2
            );
        }


        /*
        |--------------------------------------------------------------------------
        | تنفيذ التحويل
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $fromAccount,
            $toAccount,
            $exchangeRate,
            $toAmount
        ) {

            /*
            | خصم من الحساب الأول
            */
            $fromAccount->decrement(
                'balance',
                $validated['from_amount']
            );


            /*
            | إضافة للحساب الثاني
            */
            $toAccount->increment(
                'balance',
                $toAmount
            );


            /*
            | حفظ سجل التحويل
            */
            Transfer::create([
                'user_id' =>
                    Auth::id(),

                'from_account_id' =>
                    $fromAccount->id,

                'to_account_id' =>
                    $toAccount->id,

                'from_amount' =>
                    $validated['from_amount'],

                'from_currency' =>
                    $fromAccount->currency,

                'to_amount' =>
                    $toAmount,

                'to_currency' =>
                    $toAccount->currency,

                'exchange_rate' =>
                    $exchangeRate,

                'transfer_date' =>
                    $validated['transfer_date'],

                'note' =>
                    $validated['note'] ?? null,
            ]);
        });


        flash()->success(
            __('messages.transfer_created')
        );


        return redirect()
            ->route('transfers.index');
    }


    public function show(
        Transfer $transfer
    ) {
        $this->authorizeTransfer(
            $transfer
        );


        $transfer->load([
            'fromAccount',
            'toAccount',
        ]);


        return view(
            'transfers.show',
            compact('transfer')
        );
    }


    public function destroy(
        Transfer $transfer
    ) {
        $this->authorizeTransfer(
            $transfer
        );


        $transfer->load([
            'fromAccount',
            'toAccount',
        ]);


        $fromAccount =
            $transfer->fromAccount;

        $toAccount =
            $transfer->toAccount;


        /*
        |--------------------------------------------------------------------------
        | هل الحساب المستلم ما زال عنده المبلغ؟
        |--------------------------------------------------------------------------
        |
        | لأن حذف التحويل يعني عكس العملية.
        |
        | لازم نرجع:
        | to_account → from_account
        |
        */

        if (
            $toAccount->balance
            < $transfer->to_amount
        ) {
            return back()
                ->withErrors([
                    'transfer' =>
                        __('messages.cannot_reverse_transfer'),
                ]);
        }


        DB::transaction(function () use (
            $transfer,
            $fromAccount,
            $toAccount
        ) {

            /*
            | نسحب نفس القيمة التي وصلت
            | من الحساب المستلم
            */
            $toAccount->decrement(
                'balance',
                $transfer->to_amount
            );


            /*
            | نرجع نفس القيمة الأصلية
            | للحساب المرسل
            */
            $fromAccount->increment(
                'balance',
                $transfer->from_amount
            );


            /*
            | حذف سجل التحويل
            */
            $transfer->delete();
        });


        flash()->warning(
            __('messages.transfer_deleted')
        );


        return redirect()
            ->route('transfers.index');
    }


    private function authorizeTransfer(
        Transfer $transfer
    ) {
        if (
            $transfer->user_id
            !==
            Auth::id()
        ) {
            abort(403);
        }
    }
}