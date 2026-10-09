<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller {

    public function index() {
        $accounts = Account::where( 'user_id', auth()->id() )
        ->latest()
        ->paginate( 10 );

        return view( 'accounts.index', compact( 'accounts' ) );
    }

    public function create() {
        return view( 'accounts.create' );
    }

public function store(Request $request)
{
    $request->validate([
        'name_en' => 'required|string|max:255',
        'name_ar' => 'required|string|max:255',
        'type' => 'required|in:cash,bank,wallet,savings',
        'balance' => 'required|numeric|min:0',
        'currency' => 'required|string|size:3',
    ]);

    Account::create([
        'user_id' => auth()->id(),

        'name' => [
            'en' => $request->name_en,
            'ar' => $request->name_ar,
        ],

        'type' => $request->type,
        'balance' => $request->balance,
        'currency' => $request->currency,
    ]);

    flash()->success(__('messages.account_created'));

    return redirect()->route('accounts.index');
}

    public function show( Account $account ) {
        $this->authorizeAccount( $account );

        return view( 'accounts.show', compact( 'account' ) );
    }

    public function edit( Account $account ) {
        $this->authorizeAccount( $account );

        return view( 'accounts.edit', compact( 'account' ) );
    }

   public function update(Request $request, Account $account)
{
    $this->authorizeAccount($account);

    $request->validate([
        'name_en' => 'required|string|max:255',
        'name_ar' => 'required|string|max:255',
        'type' => 'required|in:cash,bank,wallet,savings',
        'balance' => 'required|numeric|min:0',
        'currency' => 'required|string|size:3',
    ]);

    $account->update([
        'name' => [
            'en' => $request->name_en,
            'ar' => $request->name_ar,
        ],

        'type' => $request->type,
        'balance' => $request->balance,
        'currency' => $request->currency,
    ]);

    flash()->success(__('messages.account_updated'));

    return redirect()->route('accounts.index');
}

    public function destroy( Account $account ) {
        $this->authorizeAccount( $account );

        $account->delete();

        flash()->warning( __( 'messages.account_deleted' ) );

        return redirect()
        ->route( 'accounts.index' );
    }

    private function authorizeAccount( Account $account ) {
        if ( $account->user_id !== auth()->id() ) {
            abort( 403 );
        }
    }
}
