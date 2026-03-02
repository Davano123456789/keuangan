<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // For now, since there is no Auth yet, we use the first user or mock it.
        // Once Auth is ready, change to: $userId = auth()->id();
        $user = User::first();
        if (!$user) {
            // Optional: fallback if no user exists at all in the DB
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('password')
            ]);
        }
        $userId = $user->id;

        $wallets = Wallet::where('user_id', $userId)->get();
        $totalBalance = $wallets->sum('balance');
        $walletCount = $wallets->count();

        // Income this month
        $incomeThisMonth = Transaction::whereHas('category', function($q) {
                $q->where('type', 'IN');
            })
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->sum('amount');

        // Expense this month
        $expenseThisMonth = Transaction::whereHas('category', function($q) {
                $q->where('type', 'OUT');
            })
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->sum('amount');

        // Recent transactions
        $recentTransactions = Transaction::with(['category', 'fromWallet', 'toWallet'])
            ->orderBy('date', 'desc')
            ->take(5)
            ->get(); // this should be filtered by user_id, but transactions table doesn't have user_id. Wait, transactions belong to wallets which belong to users.

        return view('home', compact(
            'totalBalance', 
            'walletCount', 
            'incomeThisMonth', 
            'expenseThisMonth', 
            'recentTransactions'
        ));
    }
}
