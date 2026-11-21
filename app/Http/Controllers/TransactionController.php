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
use App\Models\Budget;
use App\Models\User;
use App\Notifications\BudgetThresholdNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Notifications\BudgetExceededNotification;

class TransactionController extends Controller
{

 public function index()
{
    $itemsPerPage = (int) Setting::where('key', 'items_per_page')
        ->value('value');

    $itemsPerPage = $itemsPerPage ?: 10;

    $transactions = Transaction::where('user_id', Auth::id())
        ->with([
            'account',
            'category',
        ])
        ->latest()
        ->paginate($itemsPerPage);

    return view(
        'transactions.index',
        compact('transactions')
    );
}



    public function create()
    {
        $accounts = Account::where('user_id', Auth::id())
            ->get();


        $categories = Category::where('user_id', Auth::id())
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
                ->where('user_id', Auth::id()),
        ],

        'category_id' => [
            'required',
            Rule::exists('categories', 'id')
                ->where(function ($query) {
                    $query->where('user_id', Auth::id())
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
            Auth::id()
        )
        ->firstOrFail();


    $category = Category::where(
        'id',
        $validated['category_id']
    )
        ->where(function ($query) {
            $query->where(
                'user_id',
                Auth::id()
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
        Auth::user()->currency
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


    /*
    |--------------------------------------------------------------------------
    | Save Transaction
    |--------------------------------------------------------------------------
    */

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
                    Auth::id()
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
                Auth::id(),

            'account_id' =>
                $lockedAccount->id,

            'category_id' =>
                $validated['category_id'],

            'type' =>
                $validated['type'],

            'amount' =>
                $validated['amount'],

            'currency' =>
                $validated['currency'],

            'account_amount' =>
                $accountAmount,

            'account_currency' =>
                $lockedAccount->currency,

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


    /*
    |--------------------------------------------------------------------------
    | Budget Threshold Notification
    |--------------------------------------------------------------------------
    */

    if (
        $validated['type']
        === 'expense'
    ) {
        $this->checkBudgetThreshold(
            Auth::id(),
            $validated['category_id'],
            $validated['transaction_date']
        );
    }


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


        $accounts = Account::where('user_id', Auth::id())
            ->get();


        $categories = Category::where('user_id', Auth::id())
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
                Auth::id()
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
                        Auth::id()
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


    /*
    |--------------------------------------------------------------------------
    | Category
    |--------------------------------------------------------------------------
    */

    $category = Category::where(
        'id',
        $validated['category_id']
    )
        ->where(function ($query) {

            $query
                ->where(
                    'user_id',
                    Auth::id()
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
    | New Account
    |--------------------------------------------------------------------------
    */

    $newAccount = Account::where(
        'id',
        $validated['account_id']
    )
        ->where(
            'user_id',
            Auth::id()
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
        Auth::user()->currency
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


    /*
    |--------------------------------------------------------------------------
    | Update Transaction
    |--------------------------------------------------------------------------
    */

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
                    Auth::id()
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
                        Auth::id()
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

            'amount' =>
                $validated['amount'],

            'currency' =>
                $validated['currency'],

            'account_amount' =>
                $accountAmount,

            'account_currency' =>
                $lockedNewAccount->currency,

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


    /*
    |--------------------------------------------------------------------------
    | Budget Threshold Notification
    |--------------------------------------------------------------------------
    |
    | نفحص بعد حفظ التعديل.
    |
    | لو العملية بعد التعديل أصبحت مصروف،
    | نفحص ميزانية التصنيف والشهر الجديدين.
    |
    */

    if (
        $validated['type']
        === 'expense'
    ) {
        $this->checkBudgetThreshold(
            Auth::id(),
            $validated['category_id'],
            $validated['transaction_date']
        );
    }


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
                    Auth::id()
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
                Auth::id()
            ),
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
private function checkBudgetThreshold(
    int $userId,
    int $categoryId,
    string $transactionDate
): void {
    $date = Carbon::parse($transactionDate);

    $budget = Budget::with('category')
        ->where('user_id', $userId)
        ->where('category_id', $categoryId)
        ->where('year', $date->year)
        ->where('month', $date->month)
        ->first();

    if (! $budget) {
        return;
    }

    if ((float) $budget->base_amount <= 0) {
        return;
    }

    $spent = Transaction::where('user_id', $userId)
        ->where('category_id', $categoryId)
        ->where('type', 'expense')
        ->whereYear('transaction_date', $date->year)
        ->whereMonth('transaction_date', $date->month)
        ->sum('base_amount');

    $percentage = round(
        ((float) $spent / (float) $budget->base_amount) * 100,
        2
    );

    $user = User::findOrFail($userId);


    /*
    |--------------------------------------------------------------------------
    | Budget Exceeded
    |--------------------------------------------------------------------------
    */

    if ($percentage >= 100) {

        $alreadyExceededNotified = $user
            ->notifications()
            ->where(
                'data->type',
                'budget_exceeded'
            )
            ->where(
                'data->budget_id',
                $budget->id
            )
            ->exists();


        if (! $alreadyExceededNotified) {

            $exceededAmount = round(
                (float) $spent
                -
                (float) $budget->base_amount,
                2
            );

            $user->notify(
                new BudgetExceededNotification(
                    $budget,
                    $percentage,
                    $exceededAmount
                )
            );
        }

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Budget 80% Warning
    |--------------------------------------------------------------------------
    */

    if ($percentage < 80) {
        return;
    }


    $alreadyThresholdNotified = $user
        ->notifications()
        ->where(
            'data->type',
            'budget_threshold'
        )
        ->where(
            'data->budget_id',
            $budget->id
        )
        ->exists();


    if ($alreadyThresholdNotified) {
        return;
    }


    $user->notify(
        new BudgetThresholdNotification(
            $budget,
            $percentage
        )
    );
}
    private function authorizeTransaction(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
