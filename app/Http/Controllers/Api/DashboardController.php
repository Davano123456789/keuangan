<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isAdmin = $user->role === 'admin';

        if ($isAdmin) {
            $wallets = Wallet::all();
            $transactionQuery = Transaction::query();
        } else {
            $wallets = $user->wallets;
            $walletIds = $wallets->pluck('id');
            $transactionQuery = Transaction::where(function($q) use ($walletIds) {
                $q->whereIn('from_wallet_id', $walletIds)
                  ->orWhereIn('to_wallet_id', $walletIds);
            });
        }

        $totalBalance = $wallets->sum('balance');
        $now = Carbon::now();

        // Income this month
        $incomeThisMonth = (clone $transactionQuery)->whereHas('category', function($q) {
                $q->where('type', 'IN');
            })
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->sum('amount');

        // Expense this month
        $expenseThisMonth = (clone $transactionQuery)->whereHas('category', function($q) {
                $q->where('type', 'OUT');
            })
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->sum('amount');

        // Expense by category
        $expenseByCategory = (clone $transactionQuery)->whereHas('category', function($q) {
                $q->where('type', 'OUT');
            })
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->selectRaw('category_id, sum(amount) as total')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        // Income by category
        $incomeByCategory = (clone $transactionQuery)->whereHas('category', function($q) {
                $q->where('type', 'IN');
            })
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->selectRaw('category_id, sum(amount) as total')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        // Recent transactions
        $recentTransactions = (clone $transactionQuery)->with(['category', 'fromWallet', 'toWallet'])
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_balance' => $totalBalance,
                'income_this_month' => $incomeThisMonth,
                'expense_this_month' => $expenseThisMonth,
                'expense_by_category' => $expenseByCategory,
                'income_by_category' => $incomeByCategory,
                'recent_transactions' => $recentTransactions,
                'wallets' => $wallets,
                'user' => $user
            ]
        ]);
    }
}
