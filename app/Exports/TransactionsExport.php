<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    // protected $userId;

    // public function __construct($userId)
    // {
    //     $this->userId = $userId;
    // }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Transaction::with(['category', 'fromWallet', 'toWallet'])
            ->orderBy('date', 'desc');

        if (auth()->user()->role !== 'admin') {
            $assignedWalletIds = auth()->user()->wallets->pluck('id');
            $query->where(function($q) use ($assignedWalletIds) {
                $q->whereIn('from_wallet_id', $assignedWalletIds)
                  ->orWhereIn('to_wallet_id', $assignedWalletIds);
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Tipe',
            'Kategori',
            'Nominal',
            'Dari Dompet',
            'Ke Dompet',
            'Catatan'
        ];
    }

    public function map($transaction): array
    {
        $type = '';
        if ($transaction->type == 'IN') $type = 'Pemasukan';
        elseif ($transaction->type == 'OUT') $type = 'Pengeluaran';
        elseif ($transaction->type == 'TRANS') $type = 'Pindah Saldo';

        return [
            $transaction->date->format('d/m/Y H:i'),
            $type,
            $transaction->category->name ?? '-',
            $transaction->amount,
            $transaction->fromWallet->name ?? '-',
            $transaction->toWallet->name ?? '-',
            $transaction->note ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
