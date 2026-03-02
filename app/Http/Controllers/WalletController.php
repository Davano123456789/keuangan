<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Models\User;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = 1;

        $wallets = Wallet::where('user_id', $userId)->get();
        return view('wallets.index', compact('wallets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'balance' => 'required|numeric|min:0',
        ]);

        $userId = 1;

        Wallet::create([
            'user_id' => $userId,
            'name' => $request->name,
            'balance' => $request->balance
        ]);

        return redirect()->route('wallets.index')->with('success', 'Dompet berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Wallet $wallet)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'balance' => 'required|numeric|min:0',
        ]);

        $wallet->update([
            'name' => $request->name,
            'balance' => $request->balance
        ]);

        return redirect()->route('wallets.index')->with('success', 'Data dompet berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wallet $wallet)
    {
        // Check if wallet has transactions
        $hasTransactions = \App\Models\Transaction::where('from_wallet_id', $wallet->id)
            ->orWhere('to_wallet_id', $wallet->id)
            ->exists();

        if ($hasTransactions) {
            return back()->with('error', 'Tidak bisa menghapus dompet! Silakan hapus semua transaksi di dompet ini terlebih dahulu.');
        }

        $wallet->delete();

        return redirect()->route('wallets.index')->with('success', 'Dompet berhasil dihapus!');
    }
}
