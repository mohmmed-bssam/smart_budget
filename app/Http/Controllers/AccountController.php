<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{

    public function index()
    {
        $accounts = Account::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('accounts.index', compact('accounts'));
    }


    public function create()
    {
        return view('accounts.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => 'required|string|max:255',

            'type' => 'required|string',

            'balance' => 'required|numeric',

            'currency' => 'required|string|max:10',

        ]);


        $validated['user_id'] = auth()->id();


        Account::create($validated);


        flash()->success(__('messages.account_created'));


        return redirect()
            ->route('accounts.index');
    }



    public function show(Account $account)
    {
        $this->authorizeAccount($account);

        return view('accounts.show', compact('account'));
    }



    public function edit(Account $account)
    {
        $this->authorizeAccount($account);

        return view('accounts.edit', compact('account'));
    }



    public function update(Request $request, Account $account)
    {
        $this->authorizeAccount($account);


        $validated = $request->validate([

            'name' => 'required|string|max:255',

            'type' => 'required|string',

            'balance' => 'required|numeric',

            'currency' => 'required|string|max:10',

        ]);


        $account->update($validated);


        flash()->success(__('messages.account_updated'));


        return redirect()
            ->route('accounts.index');
    }



    public function destroy(Account $account)
    {
        $this->authorizeAccount($account);


        $account->delete();


        flash()->warning(__('messages.account_deleted'));


        return redirect()
            ->route('accounts.index');
    }



    private function authorizeAccount(Account $account)
    {
        if ($account->user_id !== auth()->id()) {
            abort(403);
        }
    }
}