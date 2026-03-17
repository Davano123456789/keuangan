<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');

        $query = Transaction::with(['category', 'fromWallet', 'toWallet', 'user'])
            ->orderBy('date', 'desc');

        if ($type && in_array($type, ['IN', 'OUT', 'TRANS'])) {
            $query->where('type', $type);
        }

        $transactions = $query->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => $transactions
        ]);
    }

    public function show(Transaction $transaction)
    {
        return response()->json([
            'status' => 'success',
            'data' => $transaction->load(['category', 'fromWallet', 'toWallet', 'user'])
        ]);
    }

    /**
     * Store a newly created transaction in storage.
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

        DB::beginTransaction();

        try {
            $date = Carbon::parse($request->date);

            // If it's just a date, append current time
            if (strlen($request->date) <= 10) {
                $date->setTimeFrom(now());
            }

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('transactions', 'public');
            }

            $transaction = Transaction::create([
                'category_id' => $request->category_id,
                'type' => $request->type,
                'amount' => $request->amount,
                'note' => $request->note,
                'from_wallet_id' => $request->from_wallet_id,
                'to_wallet_id' => $request->to_wallet_id,
                'date' => $date,
                'user_id' => auth()->id(),
                'image' => $imagePath,
            ]);

            // Update Wallet Balances
            if ($request->type === 'IN') {
                Wallet::find($request->to_wallet_id)->increment('balance', $request->amount);
            }
            elseif ($request->type === 'OUT') {
                $wallet = Wallet::find($request->from_wallet_id);
                if ($wallet->balance < $request->amount) {
                    throw new \Exception("Saldo di dompet '{$wallet->name}' tidak mencukupi.");
                }
                $wallet->decrement('balance', $request->amount);
            }
            elseif ($request->type === 'TRANS') {
                $fromWallet = Wallet::find($request->from_wallet_id);
                if ($fromWallet->balance < $request->amount) {
                    throw new \Exception("Saldo di dompet '{$fromWallet->name}' tidak mencukupi untuk transfer.");
                }
                $fromWallet->decrement('balance', $request->amount);
                Wallet::find($request->to_wallet_id)->increment('balance', $request->amount);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil dicatat',
                'data' => $transaction->load(['category', 'fromWallet', 'toWallet', 'user'])
            ], 201);

        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mencatat transaksi: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Update the specified transaction in storage.
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
            // 1. Reverse old balance changes
            if ($transaction->type === 'IN') {
                $wallet = Wallet::find($transaction->to_wallet_id);
                if ($wallet)
                    $wallet->decrement('balance', $transaction->amount);
            }
            elseif ($transaction->type === 'OUT') {
                $wallet = Wallet::find($transaction->from_wallet_id);
                if ($wallet)
                    $wallet->increment('balance', $transaction->amount);
            }
            elseif ($transaction->type === 'TRANS') {
                $fromWallet = Wallet::find($transaction->from_wallet_id);
                $toWallet = Wallet::find($transaction->to_wallet_id);
                if ($fromWallet)
                    $fromWallet->increment('balance', $transaction->amount);
                if ($toWallet)
                    $toWallet->decrement('balance', $transaction->amount);
            }

            $date = Carbon::parse($request->date);
            if (strlen($request->date) <= 10) {
                $date->setTimeFrom(now());
            }

            $imagePath = $transaction->image;
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($imagePath) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $request->file('image')->store('transactions', 'public');
            }

            // 2. Update transaction data
            $transaction->update([
                'category_id' => $request->category_id,
                'type' => $request->type,
                'amount' => $request->amount,
                'note' => $request->note,
                'from_wallet_id' => $request->from_wallet_id,
                'to_wallet_id' => $request->to_wallet_id,
                'date' => $date,
                'user_id' => auth()->id(),
                'image' => $imagePath,
            ]);

            // 3. Apply new balance changes
            if ($request->type === 'IN') {
                Wallet::find($request->to_wallet_id)->increment('balance', $request->amount);
            }
            elseif ($request->type === 'OUT') {
                $wallet = Wallet::find($request->from_wallet_id);
                if ($wallet->balance < $request->amount) {
                    throw new \Exception("Saldo di dompet '{$wallet->name}' tidak mencukupi.");
                }
                $wallet->decrement('balance', $request->amount);
            }
            elseif ($request->type === 'TRANS') {
                $fromWallet = Wallet::find($request->from_wallet_id);
                if ($fromWallet->balance < $request->amount) {
                    throw new \Exception("Saldo di dompet '{$fromWallet->name}' tidak mencukupi untuk transfer.");
                }
                $fromWallet->decrement('balance', $request->amount);
                Wallet::find($request->to_wallet_id)->increment('balance', $request->amount);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil diperbarui',
                'data' => $transaction->load(['category', 'fromWallet', 'toWallet', 'user'])
            ]);

        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui transaksi: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Remove the specified transaction from storage.
     */
    public function destroy(Transaction $transaction)
    {
        DB::beginTransaction();

        try {
            // Reverse balance changes before deleting
            if ($transaction->type === 'IN') {
                $wallet = Wallet::find($transaction->to_wallet_id);
                if ($wallet)
                    $wallet->decrement('balance', $transaction->amount);
            }
            elseif ($transaction->type === 'OUT') {
                $wallet = Wallet::find($transaction->from_wallet_id);
                if ($wallet)
                    $wallet->increment('balance', $transaction->amount);
            }
            elseif ($transaction->type === 'TRANS') {
                $fromWallet = Wallet::find($transaction->from_wallet_id);
                $toWallet = Wallet::find($transaction->to_wallet_id);
                if ($fromWallet)
                    $fromWallet->increment('balance', $transaction->amount);
                if ($toWallet)
                    $toWallet->decrement('balance', $transaction->amount);
            }

            // Delete image if exists
            if ($transaction->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($transaction->image);
            }

            $transaction->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil dihapus'
            ]);

        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus transaksi: ' . $e->getMessage()
            ], 422);
        }
    }
}
