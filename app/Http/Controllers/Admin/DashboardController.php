<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Goal;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'accounts' => Account::count(),
            'transactions' => Transaction::count(),
            'goals' => Goal::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
