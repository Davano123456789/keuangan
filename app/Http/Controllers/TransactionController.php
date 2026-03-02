<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\User;

use App\Models\Category;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = 1;

        $transactions = Transaction::with(['category', 'fromWallet', 'toWallet'])
            ->where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->get();

        $incomes = $transactions->where('type', 'IN');
        $expenses = $transactions->where('type', 'OUT');
        $transfers = $transactions->where('type', 'TRANS');

        $wallets = Wallet::where('user_id', $userId)->get();
        $categories = Category::where('user_id', $userId)->get();

        return view('transactions.index', compact('transactions', 'incomes', 'expenses', 'transfers', 'wallets', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:IN,OUT,TRANS',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'category_id' => 'nullable|required_if:type,IN,OUT|exists:categories,id',
            'from_wallet_id' => 'nullable|required_if:type,OUT,TRANS|exists:wallets,id',
            'to_wallet_id' => 'nullable|required_if:type,IN,TRANS|exists:wallets,id',
            'note' => 'nullable|string|max:500',
        ]);

        $userId = 1;

        DB::beginTransaction();

        try {
            // Append current time to the date if it's only a date
            $date = $request->date . ' ' . date('H:i:s');

            $transaction = Transaction::create([
                'user_id' => $userId,
                'category_id' => $request->category_id,
                'type' => $request->type,
                'amount' => $request->amount,
                'note' => $request->note,
                'from_wallet_id' => $request->from_wallet_id,
                'to_wallet_id' => $request->to_wallet_id,
                'date' => $date,
            ]);

            // Update Wallet Balances with check
            if ($request->type === 'IN') {
                Wallet::find($request->to_wallet_id)->increment('balance', $request->amount);
            } elseif ($request->type === 'OUT') {
                $wallet = Wallet::find($request->from_wallet_id);
                if ($wallet->balance < $request->amount) {
                    throw new \Exception("Saldo di dompet '{$wallet->name}' tidak mencukupi.");
                }
                $wallet->decrement('balance', $request->amount);
            } elseif ($request->type === 'TRANS') {
                $fromWallet = Wallet::find($request->from_wallet_id);
                if ($fromWallet->balance < $request->amount) {
                    throw new \Exception("Saldo di dompet '{$fromWallet->name}' tidak mencukupi untuk transfer.");
                }
                $fromWallet->decrement('balance', $request->amount);
                Wallet::find($request->to_wallet_id)->increment('balance', $request->amount);
            }

            DB::commit();
            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dicatat!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal mencatat transaksi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'type' => 'required|in:IN,OUT,TRANS',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'category_id' => 'nullable|required_if:type,IN,OUT|exists:categories,id',
            'from_wallet_id' => 'nullable|required_if:type,OUT,TRANS|exists:wallets,id',
            'to_wallet_id' => 'nullable|required_if:type,IN,TRANS|exists:wallets,id',
            'note' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            // 1. Reverse old balance changes (with null safety)
            if ($transaction->type === 'IN') {
                $wallet = Wallet::find($transaction->to_wallet_id);
                if ($wallet) $wallet->decrement('balance', $transaction->amount);
            } elseif ($transaction->type === 'OUT') {
                $wallet = Wallet::find($transaction->from_wallet_id);
                if ($wallet) $wallet->increment('balance', $transaction->amount);
            } elseif ($transaction->type === 'TRANS') {
                $fromWallet = Wallet::find($transaction->from_wallet_id);
                $toWallet = Wallet::find($transaction->to_wallet_id);
                if ($fromWallet) $fromWallet->increment('balance', $transaction->amount);
                if ($toWallet) $toWallet->decrement('balance', $transaction->amount);
            }

            // Append current time to the date if it's only a date
            $date = $request->date . ' ' . date('H:i:s');

            // 2. Update transaction data
            $transaction->update([
                'category_id' => $request->category_id,
                'type' => $request->type,
                'amount' => $request->amount,
                'note' => $request->note,
                'from_wallet_id' => $request->from_wallet_id,
                'to_wallet_id' => $request->to_wallet_id,
                'date' => $date,
            ]);

            // 3. Apply new balance changes with check
            if ($request->type === 'IN') {
                Wallet::find($request->to_wallet_id)->increment('balance', $request->amount);
            } elseif ($request->type === 'OUT') {
                $wallet = Wallet::find($request->from_wallet_id);
                if ($wallet->balance < $request->amount) {
                    throw new \Exception("Saldo di dompet '{$wallet->name}' tidak mencukupi.");
                }
                $wallet->decrement('balance', $request->amount);
            } elseif ($request->type === 'TRANS') {
                $fromWallet = Wallet::find($request->from_wallet_id);
                if ($fromWallet->balance < $request->amount) {
                    throw new \Exception("Saldo di dompet '{$fromWallet->name}' tidak mencukupi untuk transfer.");
                }
                $fromWallet->decrement('balance', $request->amount);
                Wallet::find($request->to_wallet_id)->increment('balance', $request->amount);
            }

            DB::commit();
            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal memperbarui transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        DB::beginTransaction();

        try {
            // Reverse balance changes before deleting (with null safety)
            if ($transaction->type === 'IN') {
                $wallet = Wallet::find($transaction->to_wallet_id);
                if ($wallet) $wallet->decrement('balance', $transaction->amount);
            } elseif ($transaction->type === 'OUT') {
                $wallet = Wallet::find($transaction->from_wallet_id);
                if ($wallet) $wallet->increment('balance', $transaction->amount);
            } elseif ($transaction->type === 'TRANS') {
                $fromWallet = Wallet::find($transaction->from_wallet_id);
                $toWallet = Wallet::find($transaction->to_wallet_id);
                if ($fromWallet) $fromWallet->increment('balance', $transaction->amount);
                if ($toWallet) $toWallet->decrement('balance', $transaction->amount);
            }

            $transaction->delete();

            DB::commit();
            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    public function export()
    {
        $userId = 1; // Hardcoded User ID for now
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\TransactionsExport($userId), 'transaksi-keuanganku-' . date('Y-m-d') . '.xlsx');
    }
}
