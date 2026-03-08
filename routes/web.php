<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->group(function () {
        Route::get('/home', function () {
            if (Gate::allows('access-dashboard')) {
                return redirect()->route('dashboard');
            }

            return redirect()->route('not-authorized');
        })->name('home.redirect');
    });


Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::post('/users/{user}', [\App\Http\Controllers\UserController::class, 'toggleActive'])->name('users.enable_disable');



});

Route::view('/not-authorized', 'not-authorized')->name('not-authorized');

Route::get('/', function () {
    return view('welcome');
})->name('home');
