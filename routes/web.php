<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;

Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('auth');;
Route::resource('wallets', WalletController::class);

Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
Route::resource('transactions', TransactionController::class);

Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', function () {
    return view('auth.register');
})->name('register');


Route::get('/logout', function () {
    return redirect()->route('login');
})->name('logout');

Route::post('/register', [AuthController::class, 'register']);

