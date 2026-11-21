<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController as ControllersDashboardController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SavingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransferController;
use App\Services\CurrencyService;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


Route::get('/test-currency', function (CurrencyService $currencyService) {
    $amount = 100;

    $rate = $currencyService->getRate('USD', 'EUR');
    $converted = $currencyService->convert($amount, 'USD', 'EUR');

    return [
        'amount' => $amount,
        'from' => 'USD',
        'to' => 'EUR',
        'rate' => $rate,
        'converted' => $converted,
    ];
});
Route::prefix(LaravelLocalization::setLocale())
    ->group(function () {


        /*
        |--------------------------------------------------------------------------
        | Public Website Routes
        |--------------------------------------------------------------------------
        */

        Route::get('/', function () {
            return view('welcome');
        })->name('home');



        /*
        |--------------------------------------------------------------------------
        | Authentication Routes
        |--------------------------------------------------------------------------
        */

        require __DIR__ . '/auth.php';


        /*
        |--------------------------------------------------------------------------
        | User Dashboard
        |--------------------------------------------------------------------------
        */
        Route::middleware(['auth', 'verified'])
            ->group(function () {

                Route::get('/dashboard', [ControllersDashboardController::class, 'index'])->name('dashboard');
                Route::resource('accounts', AccountController::class);
                Route::post(
    '/categories/quick-store',
    [CategoryController::class, 'quickStore']
)->name('categories.quick-store');
                Route::resource('categories', CategoryController::class);
                Route::get(
    '/transactions/exchange-preview',
    [TransactionController::class, 'exchangePreview']
)->name('transactions.exchange-preview');
                Route::resource('transactions', TransactionController::class);
                Route::resource('budgets', BudgetController::class);
                Route::resource('goals', GoalController::class);
                Route::resource('transfers', TransferController::class)
                     ->only(['index','create','store','show','destroy', ]);
                     Route::get(
    '/goals/{goal}/savings/exchange-preview',
    [SavingController::class, 'exchangePreview']
)->name('goals.savings.exchange-preview');
                     Route::post(
    '/goals/{goal}/savings',
    [SavingController::class, 'store']
)->name('goals.savings.store');


Route::delete(
    '/savings/{saving}',
    [SavingController::class, 'destroy']
)->name('savings.destroy');
Route::get(
    '/accounts/{account}/statement',
    [AccountController::class, 'statement']
)->name('accounts.statement');
Route::post(
    '/notifications/{notification}/read',
    [NotificationController::class, 'read']
)->name('notifications.read');


Route::post(
    '/notifications/read-all',
    [NotificationController::class, 'markAllAsRead']
)->name('notifications.read-all');
                /*
                |--------------------------------------------------------------------------
                | Profile
                |--------------------------------------------------------------------------
                */
                Route::get('/profile', [ProfileController::class, 'edit'])
                    ->name('profile.edit');

                Route::patch('/profile', [ProfileController::class, 'update'])
                    ->name('profile.update');

                Route::delete('/profile', [ProfileController::class, 'destroy'])
                    ->name('profile.destroy');
            });



        /*
        |--------------------------------------------------------------------------
        | Admin Dashboard
        |--------------------------------------------------------------------------
        */

        Route::middleware(['auth', 'verified', 'admin'])
            ->prefix('admin')
            ->name('admin.')
            ->group(function () {
                Route::get('/dashboard', [
                    DashboardController::class,
                    'index'
                ])->name('dashboard');
Route::get('/settings', [SettingController::class, 'index'])
    ->name('settings.index');

Route::put('/settings', [SettingController::class, 'update'])
    ->name('settings.update');                Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])
                    ->name('users.update-role');

                Route::patch('/users/{user}/status', [UserController::class, 'toggleStatus'])
                    ->name('users.toggle-status');

                Route::resource('users', UserController::class);
                Route::get('/reports', [ReportController::class, 'index'])
                    ->name('reports.index');
            });
    });