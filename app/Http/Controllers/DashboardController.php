<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Goal;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();


        /*
        |--------------------------------------------------------------------------
        | Base Currency
        |--------------------------------------------------------------------------
        |
        | العملة الموحدة التي سنعرض بها إحصائيات الدخل والمصروف.
        |
        | مثال:
        | USD
        |
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
        | Dashboard Statistics
        |--------------------------------------------------------------------------
        */

        $stats = [

            'accounts' =>
                Account::where(
                    'user_id',
                    $userId
                )->count(),


            'transactions' =>
                Transaction::where(
                    'user_id',
                    $userId
                )->count(),


            'goals' =>
                Goal::where(
                    'user_id',
                    $userId
                )->count(),


            /*
             * نجمع base_amount وليس amount
             *
             * لأن العمليات ممكن تكون:
             *
             * 100 ILS
             * 50 EUR
             * 200 USD
             *
             * فلا يصح جمعهم مباشرة.
             */
            'income_this_month' =>
                Transaction::where(
                    'user_id',
                    $userId
                )
                    ->where(
                        'type',
                        'income'
                    )
                    ->whereYear(
                        'transaction_date',
                        now()->year
                    )
                    ->whereMonth(
                        'transaction_date',
                        now()->month
                    )
                    ->sum(
                        'base_amount'
                    ),


            'expenses_this_month' =>
                Transaction::where(
                    'user_id',
                    $userId
                )
                    ->where(
                        'type',
                        'expense'
                    )
                    ->whereYear(
                        'transaction_date',
                        now()->year
                    )
                    ->whereMonth(
                        'transaction_date',
                        now()->month
                    )
                    ->sum(
                        'base_amount'
                    ),
        ];


        /*
        |--------------------------------------------------------------------------
        | Recent Transactions
        |--------------------------------------------------------------------------
        |
        | هون ما بنحوّل العرض لـ Base Currency.
        |
        | كل عملية رح تظهر بعملتها الأصلية:
        |
        | 1000 ILS
        | 50 EUR
        | 200 USD
        |
        */

        $recentTransactions =
            Transaction::where(
                'user_id',
                $userId
            )
                ->with([
                    'account',
                    'category',
                ])
                ->latest(
                    'transaction_date'
                )
                ->latest(
                    'id'
                )
                ->take(5)
                ->get();


        return view(
            'dashboard',
            compact(
                'stats',
                'recentTransactions',
                'baseCurrency'
            )
        );
    }
}
