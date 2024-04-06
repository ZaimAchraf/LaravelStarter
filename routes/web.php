<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->group(function () {
        Route::get('/home', function () {
            if (Gate::allows('access-dashboard', auth()->user())) {
                return redirect()->route('dashboard');
            } elseif (Gate::allows('access-forum', auth()->user())) {
                return redirect()->route('forum.index');
            }
        })->name('home');
    });


Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::post('/users/{user}', [\App\Http\Controllers\UserController::class, 'enable_disable'])->name('users.enable_disable');

    Route::get('/employees', [\App\Http\Controllers\EmployeeController::class, 'index'])->name('employees.index');

    Route::resource('devis', \App\Http\Controllers\QuotationController::class);
});

