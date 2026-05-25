<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            $wallets = Wallet::all();
        } else {
            $wallets = $user->wallets;
        }
        return response()->json([
            'status' => 'success',
            'data' => $wallets
        ]);
    }

    public function show(Wallet $wallet)
    {
        return response()->json([
            'status' => 'success',
            'data' => $wallet
        ]);
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Hanya admin yang dapat melakukan aksi ini.'
            ], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'balance' => 'required|numeric|min:0',
        ]);

        $wallet = Wallet::create([
            'name' => $request->name,
            'balance' => $request->balance
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Dompet berhasil ditambahkan',
            'data' => $wallet
        ], 201);
    }

    public function update(Request $request, Wallet $wallet)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Hanya admin yang dapat melakukan aksi ini.'
            ], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'balance' => 'required|numeric|min:0',
        ]);

        $wallet->update([
            'name' => $request->name,
            'balance' => $request->balance
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data dompet berhasil diperbarui',
            'data' => $wallet
        ]);
    }

    public function destroy(Wallet $wallet)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Hanya admin yang dapat melakukan aksi ini.'
            ], 403);
        }

        // Check if wallet has transactions
        $hasTransactions = \App\Models\Transaction::where('from_wallet_id', $wallet->id)
            ->orWhere('to_wallet_id', $wallet->id)
            ->exists();

        if ($hasTransactions) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak bisa menghapus dompet! Silakan hapus semua transaksi di dompet ini terlebih dahulu.'
            ], 422);
        }

        $wallet->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Dompet berhasil dihapus'
        ]);
    }
}
