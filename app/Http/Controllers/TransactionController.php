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


public function store(Request $request, CurrencyService $currencyService)
{
    $validated = $request->validate([
        'account_id' => 'required|exists:accounts,id',
        'category_id' => 'required|exists:categories,id',
        'type' => 'required|in:income,expense',
        'amount' => 'required|numeric|min:0.01',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'transaction_date' => 'required|date',
    ]);

    // الحساب لازم يكون للمستخدم الحالي
    $account = Account::where('id', $validated['account_id'])
        ->where('user_id', auth()->id())
        ->firstOrFail();

    // التصنيف
    $category = Category::where('id', $validated['category_id'])
        ->where(function ($query) {
            $query->where('user_id', auth()->id())
                ->orWhereNull('user_id');
        })
        ->firstOrFail();

    // نتأكد أن نوع التصنيف يطابق نوع العملية
    if ($category->type !== $validated['type']) {
        return back()
            ->withErrors([
                'category_id' => __('messages.category_type_mismatch'),
            ])
            ->withInput();
    }

    /*
     * عملة العملية هي عملة الحساب نفسه.
     * مثال: الحساب ILS → العملية ILS
     */
    $currency = $account->currency;

    /*
     * العملة الأساسية للتقارير.
     * إذا عندك currency للمستخدم استخدمها،
     * وإلا نأخذ العملة الافتراضية من Settings.
     */
    $baseCurrency =
        auth()->user()->currency
        ?? Setting::where('key', 'default_currency')->value('value')
        ?? 'USD';

    // إذا نفس العملة ما في داعي API
    if ($currency === $baseCurrency) {

        $exchangeRate = 1;
        $baseAmount = $validated['amount'];

    } else {

        $exchangeRate = $currencyService->getRate(
            $currency,
            $baseCurrency
        );

        $baseAmount = $validated['amount'] * $exchangeRate;
    }

    DB::transaction(function () use (
        $validated,
        $account,
        $currency,
        $baseCurrency,
        $exchangeRate,
        $baseAmount
    ) {

        Transaction::create([
            'user_id' => auth()->id(),

            'account_id' => $validated['account_id'],
            'category_id' => $validated['category_id'],

            'type' => $validated['type'],
            'amount' => $validated['amount'],

            'currency' => $currency,
            'exchange_rate' => $exchangeRate,
            'base_currency' => $baseCurrency,
            'base_amount' => $baseAmount,

            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'transaction_date' => $validated['transaction_date'],
        ]);

        // تحديث رصيد الحساب
        if ($validated['type'] === 'income') {

            $account->increment(
                'balance',
                $validated['amount']
            );

        } else {

            $account->decrement(
                'balance',
                $validated['amount']
            );
        }
    });

    flash()->success(__('messages.transaction_created'));

    return redirect()->route('transactions.index');
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
    $this->authorizeTransaction($transaction);

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

        'title' => 'required|string|max:255',

        'description' => 'nullable|string',

        'transaction_date' => 'required|date',
    ]);

    $category = Category::findOrFail($validated['category_id']);

    if ($category->type !== $validated['type']) {
        return back()
            ->withErrors([
                'category_id' => __('messages.category_type_mismatch')
            ])
            ->withInput();
    }

    // الحساب الجديد المختار
    $newAccount = Account::where('id', $validated['account_id'])
        ->where('user_id', auth()->id())
        ->firstOrFail();

    // عملة العملية = عملة الحساب
    $currency = $newAccount->currency;

    // العملة الأساسية للتقارير
    $baseCurrency =
        auth()->user()->currency
        ?? Setting::where('key', 'default_currency')->value('value')
        ?? 'USD';

    if ($currency === $baseCurrency) {
        $exchangeRate = 1;
        $baseAmount = $validated['amount'];
    } else {
        $exchangeRate = $currencyService->getRate(
            $currency,
            $baseCurrency
        );

        $baseAmount = $validated['amount'] * $exchangeRate;
    }

    DB::transaction(function () use (
        $transaction,
        $validated,
        $newAccount,
        $currency,
        $exchangeRate,
        $baseCurrency,
        $baseAmount
    ) {
        /*
         * 1. نرجع تأثير العملية القديمة
         */
        $oldAccount = $transaction->account;

        if ($transaction->type === 'income') {
            // الدخل القديم كان زاد الرصيد، فنرجعه بالنقص
            $oldAccount->decrement(
                'balance',
                $transaction->amount
            );
        } else {
            // المصروف القديم كان نقص الرصيد، فنرجعه بالزيادة
            $oldAccount->increment(
                'balance',
                $transaction->amount
            );
        }


        /*
         * 2. نحدّث العملية
         */
        $transaction->update([
            'account_id' => $validated['account_id'],
            'category_id' => $validated['category_id'],

            'type' => $validated['type'],
            'amount' => $validated['amount'],

            'currency' => $currency,
            'exchange_rate' => $exchangeRate,
            'base_currency' => $baseCurrency,
            'base_amount' => $baseAmount,

            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'transaction_date' => $validated['transaction_date'],
        ]);


        /*
         * 3. نطبق تأثير العملية الجديدة
         */
        if ($validated['type'] === 'income') {
            $newAccount->increment(
                'balance',
                $validated['amount']
            );
        } else {
            $newAccount->decrement(
                'balance',
                $validated['amount']
            );
        }
    });

    flash()->info(__('messages.transaction_updated'));

    return redirect()->route('transactions.index');
}


  public function destroy(Transaction $transaction)
{
    $this->authorizeTransaction($transaction);

    $account = $transaction->account;

    DB::transaction(function () use ($transaction, $account) {

        if ($transaction->type === 'income') {
            $account->decrement('balance', $transaction->amount);
        } else {
            $account->increment('balance', $transaction->amount);
        }

        $transaction->delete();
    });

    flash()->warning(__('messages.transaction_deleted'));

    return redirect()->route('transactions.index');
}




    private function authorizeTransaction(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
