<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Saving;
use App\Models\Transfer;
use App\Models\Transaction;
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

public function show(Account $account)
{
    if ($account->user_id !== auth()->id()) {
        abort(403);
    }


    /*
    |--------------------------------------------------------------------------
    | Transactions
    |--------------------------------------------------------------------------
    */

    $transactions = Transaction::where(
        'account_id',
        $account->id
    )
        ->where(
            'user_id',
            auth()->id()
        )
        ->with('category')
        ->get()
        ->map(function ($transaction) use ($account) {

            $accountAmount =
                $transaction->account_amount
                ?? $transaction->amount;

            return [
                'id' => $transaction->id,

                'movement_type' => 'transaction',

                'direction' =>
                    $transaction->type === 'income'
                        ? 'in'
                        : 'out',

                'title' =>
                    $transaction->title,

                'subtitle' =>
                    $transaction->category
                        ? (
                            $transaction->category->name[
                                app()->getLocale()
                            ]
                            ?? $transaction->category->name['en']
                            ?? ''
                        )
                        : null,

                'amount' =>
                    $accountAmount,

                'currency' =>
                    $transaction->account_currency
                    ?? $account->currency,

                'original_amount' =>
                    $transaction->amount,

                'original_currency' =>
                    $transaction->currency,

                'date' =>
                    $transaction->transaction_date,

                'created_at' =>
                    $transaction->created_at,

                /*
                 * حقول التحويل فقط
                 */
                'from_account_name' => null,
                'to_account_name' => null,

                'from_amount' => null,
                'from_currency' => null,

                'to_amount' => null,
                'to_currency' => null,

                'exchange_rate' => null,
            ];
        });



    /*
    |--------------------------------------------------------------------------
    | Outgoing Transfers
    |--------------------------------------------------------------------------
    */

    $outgoingTransfers =
        Transfer::where(
            'from_account_id',
            $account->id
        )
            ->where(
                'user_id',
                auth()->id()
            )
            ->with([
                'fromAccount',
                'toAccount',
            ])
            ->get()
            ->map(function ($transfer) {

                $fromAccountName =
                    $transfer->fromAccount
                        ? (
                            $transfer->fromAccount->name[
                                app()->getLocale()
                            ]
                            ?? $transfer->fromAccount->name['en']
                            ?? ''
                        )
                        : '';

                $toAccountName =
                    $transfer->toAccount
                        ? (
                            $transfer->toAccount->name[
                                app()->getLocale()
                            ]
                            ?? $transfer->toAccount->name['en']
                            ?? ''
                        )
                        : '';

                return [
                    'id' =>
                        $transfer->id,

                    'movement_type' =>
                        'transfer_out',

                    'direction' =>
                        'out',

                    'title' =>
                        __('messages.transfer_out_to')
                        . ' '
                        . $toAccountName,

                    'subtitle' =>
                        $toAccountName,

                    /*
                     * على الحساب الحالي:
                     * المبلغ الذي خرج فعليًا منه.
                     */
                    'amount' =>
                        $transfer->from_amount,

                    'currency' =>
                        $transfer->from_currency,

                    'original_amount' =>
                        null,

                    'original_currency' =>
                        null,

                    'date' =>
                        $transfer->transfer_date,

                    'created_at' =>
                        $transfer->created_at,


                    /*
                    |--------------------------------------------------------------------------
                    | Transfer Details
                    |--------------------------------------------------------------------------
                    */

                    'from_account_name' =>
                        $fromAccountName,

                    'to_account_name' =>
                        $toAccountName,

                    'from_amount' =>
                        $transfer->from_amount,

                    'from_currency' =>
                        $transfer->from_currency,

                    'to_amount' =>
                        $transfer->to_amount,

                    'to_currency' =>
                        $transfer->to_currency,

                    'exchange_rate' =>
                        $transfer->exchange_rate,
                ];
            });



    /*
    |--------------------------------------------------------------------------
    | Incoming Transfers
    |--------------------------------------------------------------------------
    */

    $incomingTransfers =
        Transfer::where(
            'to_account_id',
            $account->id
        )
            ->where(
                'user_id',
                auth()->id()
            )
            ->with([
                'fromAccount',
                'toAccount',
            ])
            ->get()
            ->map(function ($transfer) {

                $fromAccountName =
                    $transfer->fromAccount
                        ? (
                            $transfer->fromAccount->name[
                                app()->getLocale()
                            ]
                            ?? $transfer->fromAccount->name['en']
                            ?? ''
                        )
                        : '';

                $toAccountName =
                    $transfer->toAccount
                        ? (
                            $transfer->toAccount->name[
                                app()->getLocale()
                            ]
                            ?? $transfer->toAccount->name['en']
                            ?? ''
                        )
                        : '';

                return [
                    'id' =>
                        $transfer->id,

                    'movement_type' =>
                        'transfer_in',

                    'direction' =>
                        'in',

                    'title' =>
                        __('messages.transfer_in_from')
                        . ' '
                        . $fromAccountName,

                    'subtitle' =>
                        $fromAccountName,

                    /*
                     * على الحساب الحالي:
                     * المبلغ الذي دخل فعليًا إليه.
                     */
                    'amount' =>
                        $transfer->to_amount,

                    'currency' =>
                        $transfer->to_currency,

                    'original_amount' =>
                        null,

                    'original_currency' =>
                        null,

                    'date' =>
                        $transfer->transfer_date,

                    'created_at' =>
                        $transfer->created_at,


                    /*
                    |--------------------------------------------------------------------------
                    | Transfer Details
                    |--------------------------------------------------------------------------
                    */

                    'from_account_name' =>
                        $fromAccountName,

                    'to_account_name' =>
                        $toAccountName,

                    'from_amount' =>
                        $transfer->from_amount,

                    'from_currency' =>
                        $transfer->from_currency,

                    'to_amount' =>
                        $transfer->to_amount,

                    'to_currency' =>
                        $transfer->to_currency,

                    'exchange_rate' =>
                        $transfer->exchange_rate,
                ];
            });



    /*
    |--------------------------------------------------------------------------
    | Savings
    |--------------------------------------------------------------------------
    */

    $savings =
        Saving::where(
            'account_id',
            $account->id
        )
            ->with('goal')
            ->get()
            ->map(function ($saving) use ($account) {

                return [
                    'id' =>
                        $saving->id,

                    'movement_type' =>
                        'saving',

                    'direction' =>
                        'out',

                    'title' =>
                        __('messages.saving_for_goal'),

                    'subtitle' =>
                        $saving->goal?->title,

                    'amount' =>
                        $saving->source_amount
                        ?? $saving->amount,

                    'currency' =>
                        $saving->source_currency
                        ?? $account->currency,

                    'original_amount' =>
                        $saving->amount,

                    'original_currency' =>
                        $saving->currency,

                    'date' =>
                        $saving->date,

                    'created_at' =>
                        $saving->created_at,


                    /*
                     * حقول التحويل فقط
                     */
                    'from_account_name' => null,
                    'to_account_name' => null,

                    'from_amount' => null,
                    'from_currency' => null,

                    'to_amount' => null,
                    'to_currency' => null,

                    'exchange_rate' => null,
                ];
            });



    /*
    |--------------------------------------------------------------------------
    | Merge All Account Movements
    |--------------------------------------------------------------------------
    */

    $movements =
        collect()
            ->concat($transactions)
            ->concat($outgoingTransfers)
            ->concat($incomingTransfers)
            ->concat($savings)
            ->sortByDesc(function ($movement) {

                $date =
                    $movement['date']
                    ?? $movement['created_at'];

                return $date
                    ? \Carbon\Carbon::parse($date)->timestamp
                    : 0;
            })
            ->values();


    return view(
        'accounts.show',
        compact(
            'account',
            'movements'
        )
    );
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
