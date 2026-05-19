<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = $request->query('type');

        $query = Transaction::with(['category', 'fromWallet', 'toWallet'])
            ->orderBy('date', 'desc');

        if (auth()->user()->role !== 'admin') {
            $assignedWalletIds = auth()->user()->wallets->pluck('id');
            $query->where(function($q) use ($assignedWalletIds) {
                $q->whereIn('from_wallet_id', $assignedWalletIds)
                  ->orWhereIn('to_wallet_id', $assignedWalletIds);
            });
        }

        if ($type && in_array($type, ['IN', 'OUT', 'TRANS'])) {
            $query->where('type', $type);
        }

        $transactions = $query->paginate(10)->withQueryString();

        if (auth()->user()->role === 'admin') {
            $wallets = Wallet::all();
        } else {
            $wallets = auth()->user()->wallets;
        }
        $categories = Category::all();

        return view('transactions.index', compact('transactions', 'wallets', 'categories', 'type'));
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

        // $userId = auth()->id();

        DB::beginTransaction();

        try {
            $date = Carbon::parse($request->date);
            
            // If it's just a date (length 10 like YYYY-MM-DD), append current time
            if (strlen($request->date) <= 10) {
                $date->setTimeFrom(now());
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
            ]);
              if ($request->filled('category_id')) {
        $category = Category::find($request->category_id);
        if ($category && $category->status !== 'active') {
            $category->update(['status' => 'active']);
        }      
        }

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
            'category_id' => 'nullable|required_if:type,IN,OUT|exists:categories,id',
            'note' => 'nullable|string|max:500',
        ]);
        DB::beginTransaction();

        try {
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

            $date = Carbon::parse($request->date);
            

            if (strlen($request->date) <= 10) {
                $date->setTimeFrom(now());
            }


            $transaction->update([
                'category_id' => $request->category_id,
                'user_id' => auth()->id(),
                'date' => $date,
            ]);

            

            DB::commit();
            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal memperbarui transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Detail the specified resource in storage.
     */
    public function detail(Transaction $transaction)
    {
        return view('transactions.detail', compact('transaction'));
    }
     
    public function export()
    {
        // $userId = auth()->id();
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\TransactionsExport(), 'transaksi-keuanganku-' . date('Y-m-d') . '.xlsx');
    }
}
