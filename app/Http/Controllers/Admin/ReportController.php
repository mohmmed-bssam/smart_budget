<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Goal;

class ReportController extends Controller
{
    public function index()
    {
        $reports = [
            'users_count' => User::where('role', 'user')->count(),

            'active_users_count' => User::where('role', 'user')
                ->where('status', 1)
                ->count(),

            'inactive_users_count' => User::where('role', 'user')
                ->where('status', 0)
                ->count(),

            'accounts_count' => Account::count(),

            'transactions_count' => Transaction::count(),

            'income_transactions_count' => Transaction::where('type', 'income')
                ->count(),

            'expense_transactions_count' => Transaction::where('type', 'expense')
                ->count(),

            'goals_count' => Goal::count(),
        ];

        $recentTransactions = Transaction::with('user')
            ->latest('transaction_date')
            ->take(10)
            ->get();

        return view('admin.reports.index', compact(
            'reports',
            'recentTransactions'
        ));
    }
}
