<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;

// Guest Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::resource('wallets', WalletController::class);
    Route::resource('categories', CategoryController::class);
    Route::get('transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
    Route::resource('transactions', TransactionController::class);

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
