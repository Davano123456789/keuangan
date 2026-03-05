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
        $userId = auth()->id();

        $wallets = Wallet::where('user_id', $userId)->get();
        $totalBalance = $wallets->sum('balance');
        $walletCount = $wallets->count();

        // Income this month
        $incomeThisMonth = Transaction::where('user_id', $userId)
            ->whereHas('category', function($q) {
                $q->where('type', 'IN');
            })
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->sum('amount');

        // Expense this month
        $expenseThisMonth = Transaction::where('user_id', $userId)
            ->whereHas('category', function($q) {
                $q->where('type', 'OUT');
            })
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->sum('amount');

        // Recent transactions
        $recentTransactions = Transaction::with(['category', 'fromWallet', 'toWallet'])
            ->where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        // Expense by category for pie chart
        $expenseByCategory = Transaction::where('user_id', $userId)
            ->whereHas('category', function($q) {
                $q->where('type', 'OUT');
            })
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->selectRaw('category_id, sum(amount) as total')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        return view('home', compact(
            'wallets',
            'totalBalance', 
            'walletCount', 
            'incomeThisMonth', 
            'expenseThisMonth', 
            'recentTransactions',
            'expenseByCategory'
        ));
    }
}
