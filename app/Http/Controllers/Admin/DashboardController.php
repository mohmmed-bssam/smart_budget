<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Goal;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Current Date
        |--------------------------------------------------------------------------
        */

        $year = now()->year;
        $month = now()->month;


        /*
        |--------------------------------------------------------------------------
        | Dashboard Statistics
        |--------------------------------------------------------------------------
        |
        | - إجمالي المستخدمين: جميع المستخدمين العاديين
        | - المستخدمون الجدد: المسجلون خلال الشهر الحالي
        | - العمليات: عمليات الشهر الحالي فقط
        | - الأهداف: الأهداف المنشأة خلال الشهر الحالي فقط
        |
        */

        $stats = [

            'users' => User::where('role', 'user')
                ->count(),


            'new_users_this_month' => User::where('role', 'user')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count(),


            'transactions_this_month' => Transaction::whereHas(
                'user',
                function ($query) {
                    $query->where('role', 'user');
                }
            )
                ->whereYear('transaction_date', $year)
                ->whereMonth('transaction_date', $month)
                ->count(),


            'goals_this_month' => Goal::whereHas(
                'user',
                function ($query) {
                    $query->where('role', 'user');
                }
            )
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count(),
        ];


        /*
        |--------------------------------------------------------------------------
        | Monthly Income
        |--------------------------------------------------------------------------
        |
        | مهم:
        | نستخدم base_amount وليس amount
        | لأن العمليات ممكن تكون USD / ILS / EUR ...
        |
        */

        $incomeByMonth = Transaction::whereHas(
            'user',
            function ($query) {
                $query->where('role', 'user');
            }
        )
            ->where('type', 'income')
            ->whereYear('transaction_date', $year)
            ->selectRaw(
                'MONTH(transaction_date) as month,
                 SUM(base_amount) as total'
            )
            ->groupBy('month')
            ->pluck('total', 'month');


        /*
        |--------------------------------------------------------------------------
        | Monthly Expenses
        |--------------------------------------------------------------------------
        */

        $expensesByMonth = Transaction::whereHas(
            'user',
            function ($query) {
                $query->where('role', 'user');
            }
        )
            ->where('type', 'expense')
            ->whereYear('transaction_date', $year)
            ->selectRaw(
                'MONTH(transaction_date) as month,
                 SUM(base_amount) as total'
            )
            ->groupBy('month')
            ->pluck('total', 'month');


        /*
        |--------------------------------------------------------------------------
        | Prepare Chart Data
        |--------------------------------------------------------------------------
        */

        $monthlyIncome = [];
        $monthlyExpenses = [];


        for ($currentMonth = 1; $currentMonth <= 12; $currentMonth++) {

            $monthlyIncome[] =
                (float) (
                    $incomeByMonth[$currentMonth]
                    ?? 0
                );


            $monthlyExpenses[] =
                (float) (
                    $expensesByMonth[$currentMonth]
                    ?? 0
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Recent Transactions This Month
        |--------------------------------------------------------------------------
        */

        $recentTransactions = Transaction::with('user')
            ->whereHas(
                'user',
                function ($query) {
                    $query->where('role', 'user');
                }
            )
            ->whereYear(
                'transaction_date',
                $year
            )
            ->whereMonth(
                'transaction_date',
                $month
            )
            ->latest('transaction_date')
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Base Currency
        |--------------------------------------------------------------------------
        |
        | لأن الرسم يستخدم base_amount.
        | نفترض USD كعملة أساسية إذا لم نجد قيمة.
        |
        */

        $baseCurrency = Transaction::whereNotNull('base_currency')
            ->value('base_currency')
            ?? 'USD';


        return view(
            'admin.dashboard',
            compact(
                'stats',
                'monthlyIncome',
                'monthlyExpenses',
                'year',
                'recentTransactions',
                'baseCurrency'
            )
        );
    }
}
