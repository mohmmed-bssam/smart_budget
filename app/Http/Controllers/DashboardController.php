<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Goal;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $stats = [
            'accounts' => Account::where('user_id', $userId)->count(),

            'transactions' => Transaction::where('user_id', $userId)->count(),

            'goals' => Goal::where('user_id', $userId)->count(),

            'income_this_month' => Transaction::where('user_id', $userId)
                ->where('type', 'income')
                ->whereYear('transaction_date', now()->year)
                ->whereMonth('transaction_date', now()->month)
                ->sum('amount'),

            'expenses_this_month' => Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereYear('transaction_date', now()->year)
                ->whereMonth('transaction_date', now()->month)
                ->sum('amount'),
        ];

        $recentTransactions = Transaction::where('user_id', $userId)
            ->latest('transaction_date')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'stats',
            'recentTransactions'
        ));
    }
}
