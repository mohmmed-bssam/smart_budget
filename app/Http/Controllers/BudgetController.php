<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Setting;
use App\Models\Transaction;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BudgetController extends Controller
{
public function index()
{
    $itemsPerPage = (int) Setting::where('key', 'items_per_page')
        ->value('value');

    $itemsPerPage = $itemsPerPage ?: 10;

    $budgets = Budget::where('user_id', Auth::id())
        ->with('category')
        ->latest()
        ->paginate($itemsPerPage);

    foreach ($budgets as $budget) {

        $spent = Transaction::where('user_id', Auth::id())
            ->where('category_id', $budget->category_id)
            ->where('type', 'expense')
            ->whereYear('transaction_date', $budget->year)
            ->whereMonth('transaction_date', $budget->month)
            ->sum('base_amount');

        $budget->spent = $spent;

        $budget->remaining =
            $budget->base_amount - $spent;

        $budget->percentage =
            $budget->base_amount > 0
                ? round(
                    ($spent / $budget->base_amount) * 100,
                    2
                )
                : 0;
    }

    return view('budgets.index', compact('budgets'));
}

    public function create()
    {
        $categories = Category::where('type', 'expense')
            ->where(function ($query) {
                $query->where('user_id', Auth::id())
                    ->orWhereNull('user_id');
            })
            ->get();

        return view('budgets.create', compact('categories'));
    }


  public function store(
    Request $request,
    CurrencyService $currencyService
) {
    $validated = $request->validate([
        'category_id' => [
            'required',
            Rule::exists('categories', 'id')
                ->where(function ($query) {
                    $query->where('user_id', Auth::id())
                        ->orWhereNull('user_id');
                }),
        ],

        'amount' => 'required|numeric|min:0.01',

        'currency' => 'required|in:USD,EUR,ILS,JOD,GBP',

        'month' => 'required|integer|min:1|max:12',

        'year' => 'required|integer|min:' . now()->year,
    ]);

    $category = Category::findOrFail(
        $validated['category_id']
    );

    if ($category->type !== 'expense') {
        return back()
            ->withErrors([
                'category_id' =>
                    __('messages.budget_expense_category_only'),
            ])
            ->withInput();
    }

    $exists = Budget::where('user_id', Auth::id())
        ->where('category_id', $validated['category_id'])
        ->where('month', $validated['month'])
        ->where('year', $validated['year'])
        ->exists();

    if ($exists) {
        return back()
            ->withErrors([
                'category_id' =>
                    __('messages.budget_already_exists'),
            ])
            ->withInput();
    }

    $baseCurrency =
        Auth::user()->currency
        ?? Setting::where('key', 'default_currency')->value('value')
        ?? 'USD';

    if ($validated['currency'] === $baseCurrency) {

        $exchangeRate = 1;

        $baseAmount = $validated['amount'];

    } else {

        $exchangeRate = $currencyService->getRate(
            $validated['currency'],
            $baseCurrency
        );

        $baseAmount =
            $validated['amount'] * $exchangeRate;
    }

    Budget::create([
        'user_id' => Auth::id(),

        'category_id' => $validated['category_id'],

        'amount' => $validated['amount'],

        'currency' => $validated['currency'],

        'exchange_rate' => $exchangeRate,

        'base_currency' => $baseCurrency,

        'base_amount' => $baseAmount,

        'month' => $validated['month'],

        'year' => $validated['year'],
    ]);

    flash()->success(
        __('messages.budget_created')
    );

    return redirect()
        ->route('budgets.index');
}


  public function show(Budget $budget)
{
    $this->authorizeBudget($budget);

    $spent = Transaction::where('user_id', Auth::id())
        ->where('category_id', $budget->category_id)
        ->where('type', 'expense')
        ->whereYear('transaction_date', $budget->year)
        ->whereMonth('transaction_date', $budget->month)
        ->sum('base_amount');

    $remaining =
        $budget->base_amount - $spent;

    $percentage =
        $budget->base_amount > 0
            ? round(
                ($spent / $budget->base_amount) * 100,
                2
            )
            : 0;

    return view(
        'budgets.show',
        compact(
            'budget',
            'spent',
            'remaining',
            'percentage'
        )
    );
}


    public function edit(Budget $budget)
    {
        $this->authorizeBudget($budget);


        $categories = Category::where(
                'type',
                'expense'
            )
            ->where(function ($query) {
                $query->where(
                    'user_id',
                    Auth::id()
                )
                ->orWhereNull('user_id');
            })
            ->get();


        return view(
            'budgets.edit',
            compact(
                'budget',
                'categories'
            )
        );
    }


   public function update(
    Request $request,
    Budget $budget,
    CurrencyService $currencyService
) {
    $this->authorizeBudget($budget);

    $validated = $request->validate([
        'category_id' => [
            'required',

            Rule::exists('categories', 'id')
                ->where(function ($query) {
                    $query->where('user_id', Auth::id())
                        ->orWhereNull('user_id');
                }),
        ],

        'amount' => 'required|numeric|min:0.01',

        'currency' => 'required|in:USD,EUR,ILS,JOD,GBP',

        'month' => 'required|integer|min:1|max:12',

        'year' => 'required|integer',
    ]);


    $category = Category::findOrFail(
        $validated['category_id']
    );


    if ($category->type !== 'expense') {

        return back()
            ->withErrors([
                'category_id' =>
                    __('messages.budget_expense_category_only'),
            ])
            ->withInput();
    }


    $exists = Budget::where('user_id', Auth::id())
        ->where('category_id', $validated['category_id'])
        ->where('month', $validated['month'])
        ->where('year', $validated['year'])
        ->where('id', '!=', $budget->id)
        ->exists();


    if ($exists) {

        return back()
            ->withErrors([
                'category_id' =>
                    __('messages.budget_already_exists'),
            ])
            ->withInput();
    }


    $baseCurrency =
        Auth::user()->currency
        ?? Setting::where(
            'key',
            'default_currency'
        )->value('value')
        ?? 'USD';


    if ($validated['currency'] === $baseCurrency) {

        $exchangeRate = 1;

        $baseAmount =
            $validated['amount'];

    } else {

        $exchangeRate =
            $currencyService->getRate(
                $validated['currency'],
                $baseCurrency
            );

        $baseAmount =
            $validated['amount']
            * $exchangeRate;
    }


    $budget->update([
        'category_id' =>
            $validated['category_id'],

        'amount' =>
            $validated['amount'],

        'currency' =>
            $validated['currency'],

        'exchange_rate' =>
            $exchangeRate,

        'base_currency' =>
            $baseCurrency,

        'base_amount' =>
            $baseAmount,

        'month' =>
            $validated['month'],

        'year' =>
            $validated['year'],
    ]);


    flash()->success(
        __('messages.budget_updated')
    );


    return redirect()
        ->route('budgets.index');
}


    public function destroy(Budget $budget)
    {
        $this->authorizeBudget($budget);


        $budget->delete();


        flash()->warning(
            __('messages.budget_deleted')
        );


        return redirect()
            ->route('budgets.index');
    }


    private function authorizeBudget(
        Budget $budget
    ) {
        if (
            $budget->user_id !== Auth::id()
        ) {
            abort(403);
        }
    }
}
