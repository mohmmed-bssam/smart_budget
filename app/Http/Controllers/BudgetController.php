<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;

class BudgetController extends Controller
{

    public function index()
    {
        $budgets = Budget::where('user_id', auth()->id())
            ->with('category')
            ->latest()
            ->paginate(10);


        return view('budgets.index', compact('budgets'));
    }



    public function create()
    {
        $categories = Category::where('user_id', auth()->id())
            ->orWhereNull('user_id')
            ->get();


        return view('budgets.create', compact('categories'));
    }



    public function store(Request $request)
    {

        $validated = $request->validate([

            'category_id' => 'required|exists:categories,id',

            'amount' => 'required|numeric|min:0',

            'month' => 'required|integer|min:1|max:12',

            'year' => 'required|integer',

        ]);



        $validated['user_id'] = auth()->id();



        Budget::create($validated);



        flash()->success(__('messages.budget_created'));



        return redirect()
            ->route('budgets.index');
    }





    public function show(Budget $budget)
    {
        $this->authorizeBudget($budget);


        return view('budgets.show', compact('budget'));
    }




    public function edit(Budget $budget)
    {
        $this->authorizeBudget($budget);


        $categories = Category::where('user_id', auth()->id())
            ->orWhereNull('user_id')
            ->get();


        return view('budgets.edit', compact(
            'budget',
            'categories'
        ));
    }




    public function update(Request $request, Budget $budget)
    {

        $this->authorizeBudget($budget);



        $validated = $request->validate([

            'category_id' => 'required|exists:categories,id',

            'amount' => 'required|numeric|min:0',

            'month' => 'required|integer|min:1|max:12',

            'year' => 'required|integer',

        ]);



        $budget->update($validated);



        flash()->success(__('messages.budget_updated'));



        return redirect()
            ->route('budgets.index');
    }




    public function destroy(Budget $budget)
    {

        $this->authorizeBudget($budget);



        $budget->delete();



        flash()->warning(__('messages.budget_deleted'));



        return redirect()
            ->route('budgets.index');
    }





    private function authorizeBudget(Budget $budget)
    {
        if ($budget->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
