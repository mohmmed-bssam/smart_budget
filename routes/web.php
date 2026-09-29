<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


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

                Route::get('/dashboard', function () {
                    return view('dashboard');
                })->name('dashboard');


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


                Route::resource('users', UserController::class);


                Route::resource('settings', SettingController::class);
            });
    });
