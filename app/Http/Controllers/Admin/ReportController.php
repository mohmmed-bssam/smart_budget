<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Goal;

class ReportController extends Controller {
    public function index() {
        /*
        |--------------------------------------------------------------------------
        | Normal Users IDs
        |--------------------------------------------------------------------------
        |
        | كل الإحصائيات المالية نحسبها فقط للمستخدمين العاديين
        | وليس الأدمن.
        |
        */

        $normalUserIds = User::where( 'role', 'user' )
        ->select( 'id' );

        /*
        |--------------------------------------------------------------------------
        | Current Month
        |--------------------------------------------------------------------------
        */

        $currentYear = now()->year;
        $currentMonth = now()->month;

        /*
        |--------------------------------------------------------------------------
        | Reports
        |--------------------------------------------------------------------------
        */

        $reports = [

            /*
            |--------------------------------------------------------------------------
            | Users - Current System Status
            |--------------------------------------------------------------------------
            */

            'users_count' => User::where( 'role', 'user' )
            ->count(),

            'active_users_count' => User::where( 'role', 'user' )
            ->where( 'status', 1 )
            ->count(),

            'inactive_users_count' => User::where( 'role', 'user' )
            ->where( 'status', 0 )
            ->count(),

            'new_users_this_month' => User::where( 'role', 'user' )
            ->whereYear( 'created_at', $currentYear )
            ->whereMonth( 'created_at', $currentMonth )
            ->count(),

            /*
            |--------------------------------------------------------------------------
            | Transactions This Month
            |--------------------------------------------------------------------------
            */

            'transactions_count' => Transaction::whereIn(
                'user_id',
                $normalUserIds
            )
            ->whereYear(
                'transaction_date',
                $currentYear
            )
            ->whereMonth(
                'transaction_date',
                $currentMonth
            )
            ->count(),

            /*
            |--------------------------------------------------------------------------
            | Income Transactions This Month
            |--------------------------------------------------------------------------
            */

            'income_transactions_count' => Transaction::whereIn(
                'user_id',
                $normalUserIds
            )
            ->where( 'type', 'income' )
            ->whereYear(
                'transaction_date',
                $currentYear
            )
            ->whereMonth(
                'transaction_date',
                $currentMonth
            )
            ->count(),

            /*
            |--------------------------------------------------------------------------
            | Expense Transactions This Month
            |--------------------------------------------------------------------------
            */

            'expense_transactions_count' => Transaction::whereIn(
                'user_id',
                $normalUserIds
            )
            ->where( 'type', 'expense' )
            ->whereYear(
                'transaction_date',
                $currentYear
            )
            ->whereMonth(
                'transaction_date',
                $currentMonth
            )
            ->count(),

            /*
            |--------------------------------------------------------------------------
            | Goals Created This Month
            |--------------------------------------------------------------------------
            */

            'goals_count' => Goal::whereIn(
                'user_id',
                $normalUserIds
            )
            ->whereYear(
                'created_at',
                $currentYear
            )
            ->whereMonth(
                'created_at',
                $currentMonth
            )
            ->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Recent Transactions This Month
        |--------------------------------------------------------------------------
        */

        $recentTransactions = Transaction::with( 'user' )
        ->whereIn(
            'user_id',
            $normalUserIds
        )
        ->whereYear(
            'transaction_date',
            $currentYear
        )
        ->whereMonth(
            'transaction_date',
            $currentMonth
        )
        ->latest( 'transaction_date' )
        ->take( 10 )
        ->get();

        return view(
            'admin.reports.index',
            compact(
                'reports',
                'recentTransactions'
            )
        );
    }
}
