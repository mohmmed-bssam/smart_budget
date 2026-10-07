<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Goal;

class DashboardController extends Controller {
    public function index() {
        $stats = [
            'users' => User::where( 'role', 'user' )
            ->where( 'status', 1 )
            ->count(),
            'accounts' => Account::count(),
            'transactions' => Transaction::count(),
            'goals' => Goal::count(),
        ];

        $year = now()->year;

        $incomeByMonth = Transaction::where( 'type', 'income' )
        ->whereYear( 'transaction_date', $year )
        ->selectRaw( 'MONTH(transaction_date) as month, SUM(amount) as total' )
        ->groupBy( 'month' )
        ->pluck( 'total', 'month' );

        $expensesByMonth = Transaction::where( 'type', 'expense' )
        ->whereYear( 'transaction_date', $year )
        ->selectRaw( 'MONTH(transaction_date) as month, SUM(amount) as total' )
        ->groupBy( 'month' )
        ->pluck( 'total', 'month' );

        $monthlyIncome = [];
        $monthlyExpenses = [];

        for ( $month = 1; $month <= 12; $month++ ) {
            $monthlyIncome[] = ( float ) ( $incomeByMonth[ $month ] ?? 0 );
            $monthlyExpenses[] = ( float ) ( $expensesByMonth[ $month ] ?? 0 );
        }
        $recentTransactions = Transaction::with( 'user' )
        ->latest( 'transaction_date' )
        ->take( 5 )
        ->get();

        return view( 'admin.dashboard', compact(
            'stats',
            'monthlyIncome',
            'monthlyExpenses',
            'year',
            'recentTransactions'
        ) );
    }
}