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

            'users_count' => User::count(),

            'accounts_count' => Account::count(),

            'transactions_count' => Transaction::count(),

            'goals_count' => Goal::count(),


            'total_income' => Transaction::where('type', 'income')
                ->sum('amount'),


            'total_expenses' => Transaction::where('type', 'expense')
                ->sum('amount'),

        ];


        return view('admin.reports.index', compact('reports'));
    }
}