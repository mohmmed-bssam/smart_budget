<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Setting;
use App\Services\CurrencyService;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{

    public function index()
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->with(['account', 'category'])
            ->latest()
            ->paginate(10);


        return view('transactions.index', compact('transactions'));
    }



    public function create()
    {
        $accounts = Account::where('user_id', auth()->id())
            ->get();


        $categories = Category::where('user_id', auth()->id())
            ->orWhereNull('user_id')
            ->get();


        return view('transactions.create', compact(
            'accounts',
            'categories'
        ));
    }


public function store(
    Request $request,
    CurrencyService $currencyService
) {
    $validated = $request->validate([
        'account_id' => [
            'required',
            Rule::exists('accounts', 'id')
                ->where('user_id', auth()->id()),
        ],

        'category_id' => [
            'required',
            Rule::exists('categories', 'id')
                ->where(function ($query) {
                    $query->where('user_id', auth()->id())
                        ->orWhereNull('user_id');
                }),
        ],

        'type' => 'required|in:income,expense',

        'amount' => 'required|numeric|min:0.01',

        'currency' => 'required|in:USD,EUR,ILS,JOD,GBP',

        'title' => 'required|string|max:255',

        'description' => 'nullable|string',

        'transaction_date' => 'required|date',
    ]);


    $account = Account::where(
        'id',
        $validated['account_id']
    )
        ->where(
            'user_id',
            auth()->id()
        )
        ->firstOrFail();


    $category = Category::where(
        'id',
        $validated['category_id']
    )
        ->where(function ($query) {
            $query->where(
                'user_id',
                auth()->id()
            )
                ->orWhereNull(
                    'user_id'
                );
        })
        ->firstOrFail();


    if (
        $category->type
        !==
        $validated['type']
    ) {
        return back()
            ->withErrors([
                'category_id' =>
                    __('messages.category_type_mismatch'),
            ])
            ->withInput();
    }


    /*
    |--------------------------------------------------------------------------
    | Account Amount
    |--------------------------------------------------------------------------
    |
    | amount = المبلغ الذي أدخله المستخدم
    | account_amount = المبلغ الذي يدخل/يخرج من الحساب
    |
    */

    try {

        if (
            $validated['currency']
            ===
            $account->currency
        ) {
            $accountAmount =
                (float) $validated['amount'];

        } else {

            $accountRate =
                $currencyService->getRate(
                    $validated['currency'],
                    $account->currency
                );

            $accountAmount = round(
                (float) $validated['amount']
                * $accountRate,
                2
            );
        }

    } catch (\Throwable $e) {

        return back()
            ->withErrors([
                'currency' =>
                    __('messages.currency_service_unavailable'),
            ])
            ->withInput();
    }


    /*
    |--------------------------------------------------------------------------
    | Base Currency
    |--------------------------------------------------------------------------
    */

    $baseCurrency =
        auth()->user()->currency
        ?? Setting::where(
            'key',
            'default_currency'
        )->value('value')
        ?? 'USD';


    /*
    |--------------------------------------------------------------------------
    | Base Amount
    |--------------------------------------------------------------------------
    */

    try {

        if (
            $validated['currency']
            ===
            $baseCurrency
        ) {
            $exchangeRate = 1;

            $baseAmount =
                (float) $validated['amount'];

        } elseif (
            $account->currency
            ===
            $baseCurrency
        ) {

            $baseAmount =
                $accountAmount;

            $exchangeRate =
                $baseAmount
                / (float) $validated['amount'];

        } else {

            $exchangeRate =
                $currencyService->getRate(
                    $validated['currency'],
                    $baseCurrency
                );

            $baseAmount = round(
                (float) $validated['amount']
                * $exchangeRate,
                2
            );
        }

    } catch (\Throwable $e) {

        return back()
            ->withErrors([
                'currency' =>
                    __('messages.currency_service_unavailable'),
            ])
            ->withInput();
    }


    DB::transaction(function () use (
        $validated,
        $account,
        $accountAmount,
        $exchangeRate,
        $baseCurrency,
        $baseAmount
    ) {

        $lockedAccount =
            Account::where(
                'id',
                $account->id
            )
                ->where(
                    'user_id',
                    auth()->id()
                )
                ->lockForUpdate()
                ->firstOrFail();


        if (
            $validated['type'] === 'expense'
            &&
            (float) $lockedAccount->balance
            < $accountAmount
        ) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'amount' =>
                    __('messages.insufficient_account_balance'),
            ]);
        }


        Transaction::create([
            'user_id' =>
                auth()->id(),

            'account_id' =>
                $lockedAccount->id,

            'category_id' =>
                $validated['category_id'],

            'type' =>
                $validated['type'],

            /*
             * المبلغ كما أدخله المستخدم
             */
            'amount' =>
                $validated['amount'],

            'currency' =>
                $validated['currency'],

            /*
             * المبلغ الفعلي على الحساب
             */
            'account_amount' =>
                $accountAmount,

            'account_currency' =>
                $lockedAccount->currency,

            /*
             * التقارير
             */
            'exchange_rate' =>
                $exchangeRate,

            'base_currency' =>
                $baseCurrency,

            'base_amount' =>
                $baseAmount,

            'title' =>
                $validated['title'],

            'description' =>
                $validated['description']
                ?? null,

            'transaction_date' =>
                $validated['transaction_date'],
        ]);


        if (
            $validated['type']
            === 'income'
        ) {
            $lockedAccount->increment(
                'balance',
                $accountAmount
            );

        } else {

            $lockedAccount->decrement(
                'balance',
                $accountAmount
            );
        }
    });


    flash()->success(
        __('messages.transaction_created')
    );


    return redirect()
        ->route(
            'transactions.index'
        );
}



    public function show(Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);


        return view('transactions.show', compact('transaction'));
    }




    public function edit(Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);


        $accounts = Account::where('user_id', auth()->id())
            ->get();


        $categories = Category::where('user_id', auth()->id())
            ->orWhereNull('user_id')
            ->get();



        return view('transactions.edit', compact(
            'transaction',
            'accounts',
            'categories'
        ));
    }




public function update(
    Request $request,
    Transaction $transaction,
    CurrencyService $currencyService
) {
    $this->authorizeTransaction(
        $transaction
    );


    $validated = $request->validate([
        'account_id' => [
            'required',

            Rule::exists(
                'accounts',
                'id'
            )->where(
                'user_id',
                auth()->id()
            ),
        ],

        'category_id' => [
            'required',

            Rule::exists(
                'categories',
                'id'
            )->where(function ($query) {

                $query
                    ->where(
                        'user_id',
                        auth()->id()
                    )
                    ->orWhereNull(
                        'user_id'
                    );
            }),
        ],

        'type' =>
            'required|in:income,expense',

        'amount' =>
            'required|numeric|min:0.01',

        'currency' =>
            'required|in:USD,EUR,ILS,JOD,GBP',

        'title' =>
            'required|string|max:255',

        'description' =>
            'nullable|string',

        'transaction_date' =>
            'required|date',
    ]);


    $category = Category::where(
        'id',
        $validated['category_id']
    )
        ->where(function ($query) {

            $query
                ->where(
                    'user_id',
                    auth()->id()
                )
                ->orWhereNull(
                    'user_id'
                );
        })
        ->firstOrFail();


    if (
        $category->type
        !==
        $validated['type']
    ) {
        return back()
            ->withErrors([
                'category_id' =>
                    __('messages.category_type_mismatch'),
            ])
            ->withInput();
    }


    $newAccount = Account::where(
        'id',
        $validated['account_id']
    )
        ->where(
            'user_id',
            auth()->id()
        )
        ->firstOrFail();


    /*
    |--------------------------------------------------------------------------
    | Account Amount
    |--------------------------------------------------------------------------
    */

    try {

        if (
            $validated['currency']
            ===
            $newAccount->currency
        ) {
            $accountAmount =
                (float) $validated['amount'];

        } else {

            $accountRate =
                $currencyService->getRate(
                    $validated['currency'],
                    $newAccount->currency
                );

            $accountAmount = round(
                (float) $validated['amount']
                * $accountRate,
                2
            );
        }

    } catch (\Throwable $e) {

        return back()
            ->withErrors([
                'currency' =>
                    __('messages.currency_service_unavailable'),
            ])
            ->withInput();
    }


    /*
    |--------------------------------------------------------------------------
    | Base Currency
    |--------------------------------------------------------------------------
    */

    $baseCurrency =
        auth()->user()->currency
        ?? Setting::where(
            'key',
            'default_currency'
        )->value('value')
        ?? 'USD';


    /*
    |--------------------------------------------------------------------------
    | Base Amount
    |--------------------------------------------------------------------------
    */

    try {

        if (
            $validated['currency']
            ===
            $baseCurrency
        ) {
            $exchangeRate = 1;

            $baseAmount =
                (float) $validated['amount'];

        } elseif (
            $newAccount->currency
            ===
            $baseCurrency
        ) {

            $baseAmount =
                $accountAmount;

            $exchangeRate =
                $baseAmount
                / (float) $validated['amount'];

        } else {

            $exchangeRate =
                $currencyService->getRate(
                    $validated['currency'],
                    $baseCurrency
                );

            $baseAmount = round(
                (float) $validated['amount']
                * $exchangeRate,
                2
            );
        }

    } catch (\Throwable $e) {

        return back()
            ->withErrors([
                'currency' =>
                    __('messages.currency_service_unavailable'),
            ])
            ->withInput();
    }


    DB::transaction(function () use (
        $transaction,
        $validated,
        $newAccount,
        $accountAmount,
        $exchangeRate,
        $baseCurrency,
        $baseAmount
    ) {

        /*
        |--------------------------------------------------------------------------
        | Old Account
        |--------------------------------------------------------------------------
        */

        $oldAccount =
            Account::where(
                'id',
                $transaction->account_id
            )
                ->where(
                    'user_id',
                    auth()->id()
                )
                ->lockForUpdate()
                ->firstOrFail();


        /*
         * العمليات القديمة ممكن account_amount يكون NULL.
         */
        $oldAccountAmount =
            $transaction->account_amount
            ?? $transaction->amount;


        /*
        |--------------------------------------------------------------------------
        | 1. Undo Old Transaction
        |--------------------------------------------------------------------------
        */

        if (
            $transaction->type
            === 'income'
        ) {
            $oldAccount->decrement(
                'balance',
                $oldAccountAmount
            );

        } else {

            $oldAccount->increment(
                'balance',
                $oldAccountAmount
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 2. Lock New Account
        |--------------------------------------------------------------------------
        */

        if (
            $newAccount->id
            ===
            $oldAccount->id
        ) {
            $lockedNewAccount =
                $oldAccount;

        } else {

            $lockedNewAccount =
                Account::where(
                    'id',
                    $newAccount->id
                )
                    ->where(
                        'user_id',
                        auth()->id()
                    )
                    ->lockForUpdate()
                    ->firstOrFail();
        }


        /*
        |--------------------------------------------------------------------------
        | 3. Check Balance
        |--------------------------------------------------------------------------
        */

        if (
            $validated['type']
            === 'expense'
            &&
            (float) $lockedNewAccount->balance
            < $accountAmount
        ) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'amount' =>
                    __('messages.insufficient_account_balance'),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 4. Update Transaction
        |--------------------------------------------------------------------------
        */

        $transaction->update([
            'account_id' =>
                $lockedNewAccount->id,

            'category_id' =>
                $validated['category_id'],

            'type' =>
                $validated['type'],

            /*
             * العملية الأصلية
             */
            'amount' =>
                $validated['amount'],

            'currency' =>
                $validated['currency'],

            /*
             * ما تأثر به الحساب
             */
            'account_amount' =>
                $accountAmount,

            'account_currency' =>
                $lockedNewAccount->currency,

            /*
             * التقارير
             */
            'exchange_rate' =>
                $exchangeRate,

            'base_currency' =>
                $baseCurrency,

            'base_amount' =>
                $baseAmount,

            'title' =>
                $validated['title'],

            'description' =>
                $validated['description']
                ?? null,

            'transaction_date' =>
                $validated['transaction_date'],
        ]);


        /*
        |--------------------------------------------------------------------------
        | 5. Apply New Transaction
        |--------------------------------------------------------------------------
        */

        if (
            $validated['type']
            === 'income'
        ) {
            $lockedNewAccount->increment(
                'balance',
                $accountAmount
            );

        } else {

            $lockedNewAccount->decrement(
                'balance',
                $accountAmount
            );
        }
    });


    flash()->info(
        __('messages.transaction_updated')
    );


    return redirect()
        ->route(
            'transactions.index'
        );
}


 public function destroy(
    Transaction $transaction
) {
    $this->authorizeTransaction(
        $transaction
    );


    DB::transaction(function () use (
        $transaction
    ) {

        $account =
            Account::where(
                'id',
                $transaction->account_id
            )
                ->where(
                    'user_id',
                    auth()->id()
                )
                ->lockForUpdate()
                ->firstOrFail();


        $accountAmount =
            $transaction->account_amount
            ?? $transaction->amount;


        if (
            $transaction->type
            === 'income'
        ) {
            $account->decrement(
                'balance',
                $accountAmount
            );

        } else {

            $account->increment(
                'balance',
                $accountAmount
            );
        }


        $transaction->delete();
    });


    flash()->warning(
        __('messages.transaction_deleted')
    );


    return redirect()
        ->route(
            'transactions.index'
        );
}



public function exchangePreview(
    Request $request,
    CurrencyService $currencyService
) {
    $validated = $request->validate([
        'amount' => [
            'required',
            'numeric',
            'min:0.01',
        ],

        'currency' => [
            'required',
            'in:USD,EUR,ILS,JOD,GBP',
        ],

        'account_id' => [
            'required',

            Rule::exists(
                'accounts',
                'id'
            )->where(
                'user_id',
                auth()->id()
            ),
        ],
    ]);


    $account = Account::where(
        'id',
        $validated['account_id']
    )
        ->where(
            'user_id',
            auth()->id()
        )
        ->firstOrFail();


    $fromCurrency =
        $validated['currency'];

    $toCurrency =
        $account->currency;

    $amount =
        (float) $validated['amount'];


    /*
     * نفس العملة
     */
    if ($fromCurrency === $toCurrency) {

        return response()->json([
            'same_currency' => true,

            'from_currency' =>
                $fromCurrency,

            'to_currency' =>
                $toCurrency,

            'amount' =>
                $amount,

            'converted_amount' =>
                round($amount, 2),

            'rate' =>
                1,

            'inverse_rate' =>
                1,
        ]);
    }


    try {

        /*
         * مثال:
         * ILS → USD
         */
        $rate =
            $currencyService->getRate(
                $fromCurrency,
                $toCurrency
            );


        $convertedAmount = round(
            $amount * $rate,
            2
        );


        /*
         * مثال:
         *
         * إذا:
         * 1 ILS = 0.314 USD
         *
         * العكسي:
         * 1 USD = 3.18 ILS
         */
        $inverseRate =
            $rate > 0
                ? 1 / $rate
                : null;


        return response()->json([
            'same_currency' => false,

            'from_currency' =>
                $fromCurrency,

            'to_currency' =>
                $toCurrency,

            'amount' =>
                $amount,

            'converted_amount' =>
                $convertedAmount,

            'rate' =>
                round($rate, 6),

            'inverse_rate' =>
                $inverseRate
                    ? round($inverseRate, 6)
                    : null,
        ]);


    } catch (\Throwable $e) {

        return response()->json([
            'message' =>
                __('messages.currency_service_unavailable'),
        ], 503);
    }
}
    private function authorizeTransaction(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
