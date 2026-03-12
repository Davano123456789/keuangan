<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
// Routes for the application -- no authentication required

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::resource('wallets', WalletController::class);
Route::resource('categories', CategoryController::class);
Route::get('transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
Route::resource('transactions', TransactionController::class);