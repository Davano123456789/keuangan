<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Transaction;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            $wallets = Wallet::all();
        } else {
            $wallets = $user->wallets;
        }
        
        $totalBalance = $wallets->sum('balance');
        $walletCount = $wallets->count();
        $assignedWalletIds = $wallets->pluck('id');

        // Income this month
        $incomeQuery = Transaction::where('type', 'IN')
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year);
            
        if ($user->role !== 'admin') {
            $incomeQuery->whereIn('to_wallet_id', $assignedWalletIds);
        }
        
        $incomeThisMonth = $incomeQuery->sum('amount');

        // Expense this month
        $expenseQuery = Transaction::where('type', 'OUT')
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year);
            
        if ($user->role !== 'admin') {
            $expenseQuery->whereIn('from_wallet_id', $assignedWalletIds);
        }
        
        $expenseThisMonth = $expenseQuery->sum('amount');

        // Recent transactions
        $recentQuery = Transaction::with(['category', 'fromWallet', 'toWallet'])
            ->orderBy('date', 'desc')
            ->take(5);
            
        if ($user->role !== 'admin') {
            $recentQuery->where(function($q) use ($assignedWalletIds) {
                $q->whereIn('from_wallet_id', $assignedWalletIds)
                  ->orWhereIn('to_wallet_id', $assignedWalletIds);
            });
        }
        
        $recentTransactions = $recentQuery->get();

        // Expense by category for pie chart
        $expenseChartQuery = Transaction::where('type', 'OUT')
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year);
            
        if ($user->role !== 'admin') {
            $expenseChartQuery->whereIn('from_wallet_id', $assignedWalletIds);
        }
        
        $expenseByCategory = $expenseChartQuery->selectRaw('category_id, sum(amount) as total')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        // Income by category for pie chart
        $incomeChartQuery = Transaction::where('type', 'IN')
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year);
            
        if ($user->role !== 'admin') {
            $incomeChartQuery->whereIn('to_wallet_id', $assignedWalletIds);
        }
        
        $incomeByCategory = $incomeChartQuery->selectRaw('category_id, sum(amount) as total')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        return view('home', compact(
            'user',
            'wallets',
            'totalBalance', 
            'walletCount', 
            'incomeThisMonth', 
            'expenseThisMonth', 
            'recentTransactions',
            'expenseByCategory',
            'incomeByCategory'
        ));
    }
}
