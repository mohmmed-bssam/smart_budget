<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\Category;
use Illuminate\Http\Request;

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



    public function store(Request $request)
    {

        $validated = $request->validate([

            'account_id' => 'required|exists:accounts,id',

            'category_id' => 'required|exists:categories,id',

            'type' => 'required|in:income,expense',

            'amount' => 'required|numeric|min:0',

            'title' => 'required|string|max:255',

            'description' => 'nullable|string',

            'transaction_date' => 'required|date',

        ]);



        $validated['user_id'] = auth()->id();



        Transaction::create($validated);



        flash()->success(__('messages.transaction_created'));



        return redirect()
            ->route('transactions.index');
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




    public function update(Request $request, Transaction $transaction)
    {

        $this->authorizeTransaction($transaction);



        $validated = $request->validate([

            'account_id' => 'required|exists:accounts,id',

            'category_id' => 'required|exists:categories,id',

            'type' => 'required|in:income,expense',

            'amount' => 'required|numeric|min:0',

            'title' => 'required|string|max:255',

            'description' => 'nullable|string',

            'transaction_date' => 'required|date',

        ]);



        $transaction->update($validated);



        flash()->success(__('messages.transaction_updated'));



        return redirect()
            ->route('transactions.index');
    }




    public function destroy(Transaction $transaction)
    {

        $this->authorizeTransaction($transaction);


        $transaction->delete();



        flash()->warning(__('messages.transaction_deleted'));



        return redirect()
            ->route('transactions.index');
    }





    private function authorizeTransaction(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
