<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Saving;
use App\Models\Transfer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
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
public function statement(
    Request $request,
    Account $account
) {
    /*
    |--------------------------------------------------------------------------
    | Authorization
    |--------------------------------------------------------------------------
    */

    if ($account->user_id !== Auth::id()) {
        abort(403);
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Period
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([
        'from' => [
            'required',
            'date',
            'before_or_equal:today',
        ],

        'to' => [
            'required',
            'date',
            'after_or_equal:from',
            'before_or_equal:today',
        ],
    ]);


    $from = $validated['from'];
    $to = $validated['to'];


    /*
    |--------------------------------------------------------------------------
    | Transactions
    |--------------------------------------------------------------------------
    */

    $transactions =
        Transaction::where(
            'account_id',
            $account->id
        )
            ->where(
                'user_id',
                Auth::id()
            )
            ->whereBetween(
                'transaction_date',
                [
                    $from,
                    $to,
                ]
            )
            ->with('category')
            ->get()
            ->map(function ($transaction) use ($account) {

                /*
                 * المبلغ الذي تحرك فعليًا
                 * من هذا الحساب.
                 */
                $accountAmount =
                    $transaction->account_amount
                    ?? $transaction->amount;


                $categoryName = null;

                if ($transaction->category) {

                    $categoryName =
                        $transaction->category->name[
                            app()->getLocale()
                        ]
                        ?? $transaction->category->name['en']
                        ?? null;
                }


                return [
                    'date' =>
                        $transaction->transaction_date,

                    'created_at' =>
                        $transaction->created_at,

                    'direction' =>
                        $transaction->type === 'income'
                            ? 'in'
                            : 'out',

                    'type' =>
                        $transaction->type,

                    'title' =>
                        $transaction->title,

                    'details' =>
                        $categoryName,

                    'amount' =>
                        (float) $accountAmount,

                    'currency' =>
                        $transaction->account_currency
                        ?? $account->currency,

                    'original_amount' =>
                        (float) $transaction->amount,

                    'original_currency' =>
                        $transaction->currency,
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
                Auth::id()
            )
            ->whereBetween(
                'transfer_date',
                [
                    $from,
                    $to,
                ]
            )
            ->with('toAccount')
            ->get()
            ->map(function ($transfer) {

                $toAccountName =
                    $transfer->toAccount?->name[
                        app()->getLocale()
                    ]
                    ?? $transfer->toAccount?->name['en']
                    ?? '-';


                return [
                    'date' =>
                        $transfer->transfer_date,

                    'created_at' =>
                        $transfer->created_at,

                    'direction' =>
                        'out',

                    'type' =>
                        'transfer_out',

                    'title' =>
                        __('messages.transfer_out'),

                    'details' =>
                        __('messages.to')
                        . ': '
                        . $toAccountName,

                    'amount' =>
                        (float) $transfer->from_amount,

                    'currency' =>
                        $transfer->from_currency,

                    'original_amount' =>
                        null,

                    'original_currency' =>
                        null,
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
                Auth::id()
            )
            ->whereBetween(
                'transfer_date',
                [
                    $from,
                    $to,
                ]
            )
            ->with('fromAccount')
            ->get()
            ->map(function ($transfer) {

                $fromAccountName =
                    $transfer->fromAccount?->name[
                        app()->getLocale()
                    ]
                    ?? $transfer->fromAccount?->name['en']
                    ?? '-';


                return [
                    'date' =>
                        $transfer->transfer_date,

                    'created_at' =>
                        $transfer->created_at,

                    'direction' =>
                        'in',

                    'type' =>
                        'transfer_in',

                    'title' =>
                        __('messages.transfer_in'),

                    'details' =>
                        __('messages.from')
                        . ': '
                        . $fromAccountName,

                    'amount' =>
                        (float) $transfer->to_amount,

                    'currency' =>
                        $transfer->to_currency,

                    'original_amount' =>
                        null,

                    'original_currency' =>
                        null,
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
            ->whereBetween(
                'date',
                [
                    $from,
                    $to,
                ]
            )
            ->with('goal')
            ->get()
            ->map(function ($saving) use ($account) {

                return [
                    'date' =>
                        $saving->date,

                    'created_at' =>
                        $saving->created_at,

                    'direction' =>
                        'out',

                    'type' =>
                        'saving',

                    'title' =>
                        __('messages.saving_for_goal'),

                    'details' =>
                        $saving->goal?->title,

                    'amount' =>
                        (float) (
                            $saving->source_amount
                            ?? $saving->amount
                        ),

                    'currency' =>
                        $saving->source_currency
                        ?? $account->currency,

                    'original_amount' =>
                        (float) $saving->amount,

                    'original_currency' =>
                        $saving->currency,
                ];
            });


    /*
    |--------------------------------------------------------------------------
    | Merge All Movements
    |--------------------------------------------------------------------------
    */

    $movements =
        collect()
            ->concat($transactions)
            ->concat($outgoingTransfers)
            ->concat($incomingTransfers)
            ->concat($savings)
            ->sortBy(function ($movement) {

                $date =
                    $movement['date']
                        ? \Carbon\Carbon::parse(
                            $movement['date']
                        )->format('Y-m-d')
                        : '';


                $createdAt =
                    $movement['created_at']
                        ? \Carbon\Carbon::parse(
                            $movement['created_at']
                        )->format('H:i:s')
                        : '00:00:00';


                return $date . ' ' . $createdAt;
            })
            ->values();


    /*
    |--------------------------------------------------------------------------
    | Summary
    |--------------------------------------------------------------------------
    */

    $totalIncoming =
        $movements
            ->where(
                'direction',
                'in'
            )
            ->sum(
                'amount'
            );


    $totalOutgoing =
        $movements
            ->where(
                'direction',
                'out'
            )
            ->sum(
                'amount'
            );


    $netMovement =
        $totalIncoming
        - $totalOutgoing;
/*
|--------------------------------------------------------------------------
| Calculate Balance At End Of Period
|--------------------------------------------------------------------------
*/

$currentBalance =
    (float) $account->balance;


/*
 * Transactions after selected "to" date
 */

$transactionsAfter =
    Transaction::where(
        'account_id',
        $account->id
    )
        ->where(
            'user_id',
            Auth::id()
        )
        ->whereDate(
            'transaction_date',
            '>',
            $to
        )
        ->get()
        ->sum(function ($transaction) {

            $amount =
                (float) (
                    $transaction->account_amount
                    ?? $transaction->amount
                );

            return $transaction->type === 'income'
                ? $amount
                : -$amount;
        });


/*
 * Outgoing transfers after selected period
 */

$outgoingTransfersAfter =
    Transfer::where(
        'from_account_id',
        $account->id
    )
        ->where(
            'user_id',
            Auth::id()
        )
        ->whereDate(
            'transfer_date',
            '>',
            $to
        )
        ->sum('from_amount');


/*
 * Incoming transfers after selected period
 */

$incomingTransfersAfter =
    Transfer::where(
        'to_account_id',
        $account->id
    )
        ->where(
            'user_id',
            Auth::id()
        )
        ->whereDate(
            'transfer_date',
            '>',
            $to
        )
        ->sum('to_amount');


/*
 * Savings after selected period
 */

$savingsAfter =
    Saving::where(
        'account_id',
        $account->id
    )
        ->whereDate(
            'date',
            '>',
            $to
        )
        ->get()
        ->sum(function ($saving) {

            return (float) (
                $saving->source_amount
                ?? $saving->amount
            );
        });


/*
|--------------------------------------------------------------------------
| Net movement after selected period
|--------------------------------------------------------------------------
*/

$netAfterPeriod =
    $transactionsAfter
    + (float) $incomingTransfersAfter
    - (float) $outgoingTransfersAfter
    - (float) $savingsAfter;


/*
|--------------------------------------------------------------------------
| Closing Balance
|--------------------------------------------------------------------------
|
| نرجع من الرصيد الحالي للخلف حتى نهاية الفترة المحددة.
|
*/

$closingBalance =
    $currentBalance
    - $netAfterPeriod;


/*
|--------------------------------------------------------------------------
| Opening Balance
|--------------------------------------------------------------------------
|
| Closing = Opening + Incoming - Outgoing
|
| لذلك:
|
| Opening = Closing - Net Movement
|
*/

$openingBalance =
    $closingBalance
    - $netMovement;

    /*
    |--------------------------------------------------------------------------
    | Render PDF Blade
    |--------------------------------------------------------------------------
    */
$html =
    view(
        'accounts.statement-pdf',
        compact(
            'account',
            'movements',
            'from',
            'to',
            'totalIncoming',
            'totalOutgoing',
            'netMovement',
            'openingBalance',
            'closingBalance'
        )
    )->render();


    /*
    |--------------------------------------------------------------------------
    | Generate PDF
    |--------------------------------------------------------------------------
    */

    $tempDir =
        storage_path(
            'app/mpdf-temp'
        );


    if (!is_dir($tempDir)) {

        mkdir(
            $tempDir,
            0755,
            true
        );
    }


    $mpdf =
        new Mpdf([
            'mode' => 'utf-8',

            'format' => 'A4',

            'tempDir' =>
                $tempDir,

            'margin_top' =>
                12,

            'margin_bottom' =>
                12,

            'margin_left' =>
                10,

            'margin_right' =>
                10,

            'autoScriptToLang' =>
                true,

            'autoLangToFont' =>
                true,
        ]);


    $mpdf->SetDirectionality(
        app()->getLocale() === 'ar'
            ? 'rtl'
            : 'ltr'
    );


    $mpdf->WriteHTML(
        $html
    );


    /*
    |--------------------------------------------------------------------------
    | File Name
    |--------------------------------------------------------------------------
    */

    $fileName =
        'account-statement-'
        . $account->id
        . '-'
        . $from
        . '-to-'
        . $to
        . '.pdf';


    $pdfContent =
        $mpdf->Output(
            '',
            Destination::STRING_RETURN
        );


    /*
    |--------------------------------------------------------------------------
    | Download
    |--------------------------------------------------------------------------
    */

    return response(
        $pdfContent
    )
        ->header(
            'Content-Type',
            'application/pdf'
        )
        ->header(
            'Content-Disposition',
            'attachment; filename="'
            . $fileName
            . '"'
        );
}
    private function authorizeAccount( Account $account ) {
        if ( $account->user_id !== auth()->id() ) {
            abort( 403 );
        }
    }
}
