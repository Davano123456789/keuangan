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
        $wallets = Wallet::all();
        $totalBalance = $wallets->sum('balance');

        $now = Carbon::now();

        // Income this month
        $incomeThisMonth = Transaction::whereHas('category', function($q) {
                $q->where('type', 'IN');
            })
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->sum('amount');

        // Expense this month
        $expenseThisMonth = Transaction::whereHas('category', function($q) {
                $q->where('type', 'OUT');
            })
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->sum('amount');

        // Expense by category
        $expenseByCategory = Transaction::whereHas('category', function($q) {
                $q->where('type', 'OUT');
            })
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->selectRaw('category_id, sum(amount) as total')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        // Income by category
        $incomeByCategory = Transaction::whereHas('category', function($q) {
                $q->where('type', 'IN');
            })
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->selectRaw('category_id, sum(amount) as total')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_balance' => $totalBalance,
                'income_this_month' => $incomeThisMonth,
                'expense_this_month' => $expenseThisMonth,
                'expense_by_category' => $expenseByCategory,
                'income_by_category' => $incomeByCategory,
                'wallets' => $wallets
            ]
        ]);
    }
}
