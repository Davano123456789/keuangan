<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover text-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                        <th>Dompet</th>
                        <th>Input Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $transaction)
                    <tr>
                        <td>{{ $loop->iteration + ($data->firstItem() - 1) }}</td>
                        <td>{{ $transaction->date->translatedFormat('d M Y, H:i') }} WIB</td>
                        <td>
                            @if($transaction->type == 'TRANS')
                                <span class="badge badge-warning text-white">Pindah Saldo</span>
                            @else
                                <span class="font-weight-bold">
                                    {{ $transaction->category->icon ?? '' }} {{ $transaction->category->name ?? 'N/A' }}
                                </span>
                            @endif
                        </td>
                        <td class="text-left text-wrap" style="max-width: 200px;">
                            {{ $transaction->note ?? '-' }}
                        </td>
                        <td>
                            @if($transaction->type == 'IN')
                                <span class="text-success font-weight-bold">+ Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                            @elseif($transaction->type == 'OUT')
                                <span class="text-danger font-weight-bold">- Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                            @else
                                <span class="text-dark font-weight-bold">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($transaction->type == 'TRANS')
                                <small class="text-muted">{{ $transaction->fromWallet->name ?? '?' }}</small> 
                                <i class="ti-arrow-right mx-1 text-primary"></i> 
                                <small class="text-muted">{{ $transaction->toWallet->name ?? '?' }}</small>
                            @else
                                {{ $transaction->fromWallet->name ?? ($transaction->toWallet->name ?? 'N/A') }}
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $transaction->user->name ?? 'Sistem' }}</span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#editTransactionModal{{ $transaction->id }}">Edit</button>
                            <button class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#detailTransactionModal{{ $transaction->id }}">Detail</button>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="py-5 text-muted">Belum ada transaksi di kategori ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($data->hasPages())
        <div class="mt-4 d-flex justify-content-end">
            {{ $data->links() }}
        </div>
        @endif
    </div>
</div>

@foreach($data as $transaction)
    <!-- Edit Transaction Modal -->
    <div class="modal fade" id="editTransactionModal{{ $transaction->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content text-left">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Transaksi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tipe Transaksi</label>
                            <select name="type" class="form-control" disabled>
                                <option value="OUT" {{ $transaction->type == 'OUT' ? 'selected' : '' }}>Pengeluaran</option>
                                <option value="IN" {{ $transaction->type == 'IN' ? 'selected' : '' }}>Pemasukan</option>
                                <option value="TRANS" {{ $transaction->type == 'TRANS' ? 'selected' : '' }}>Pindah Saldo</option>
                            </select>
                            <input type="hidden" name="type" value="{{ $transaction->type }}">
                            <small class="text-muted">Tipe transaksi tidak bisa diubah untuk menjaga integritas saldo.</small>
                        </div>

                        <div class="form-group">
                            <label>Nominal (Rp)</label>
                            <input type="number" name="amount" class="form-control" value="{{ intval($transaction->amount) }}" disabled>
                        </div>

                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="datetime-local" name="date" class="form-control" value="{{ $transaction->date->format('Y-m-d\TH:i') }}" disabled>
                        </div>

                        @if($transaction->type != 'TRANS')
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="category_id" class="form-control">
                                @foreach($categories as $cat)
                                    @if($cat->type == $transaction->type)
                                        <option value="{{ $cat->id }}" {{ $transaction->category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->icon }} {{ $cat->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="row">
                            @if($transaction->type != 'IN')
                            <div class="col-md-12 form-group">
                                <label>{{ $transaction->type == 'TRANS' ? 'Dari Dompet (Asal)' : 'Dompet (Sumber)' }}</label>
                                 <select name="from_wallet_id" class="form-control edit-from-wallet" required disabled>
                                    @foreach($wallets as $wallet)
                                        <option value="{{ $wallet->id }}" {{ $transaction->from_wallet_id == $wallet->id ? 'selected' : '' }} disabled>{{ $wallet->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            
                            @if($transaction->type != 'OUT')
                            <div class="col-md-12 form-group">
                                <label>{{ $transaction->type == 'TRANS' ? 'Ke Dompet (Tujuan)' : 'Dompet (Masuk)' }}</label>
                                 <select name="to_wallet_id" class="form-control edit-to-wallet" required disabled>
                                    @foreach($wallets as $wallet)
                                        <option value="{{ $wallet->id }}" {{ $transaction->to_wallet_id == $wallet->id ? 'selected' : '' }} disabled>{{ $wallet->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>Catatan (Opsional)</label>
                            <textarea name="note" class="form-control" rows="3" disabled>{{ $transaction->note }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Detail Transaction Modal -->
    <div class="modal fade" id="detailTransactionModal{{ $transaction->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content text-left">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Transaksi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tipe Transaksi</label>
                        <select class="form-control" disabled>
                            <option value="OUT" {{ $transaction->type == 'OUT' ? 'selected' : '' }}>Pengeluaran</option>
                            <option value="IN" {{ $transaction->type == 'IN' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="TRANS" {{ $transaction->type == 'TRANS' ? 'selected' : '' }}>Pindah Saldo</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Nominal (Rp)</label>
                        <input type="number" class="form-control" value="{{ intval($transaction->amount) }}" disabled>
                    </div>

                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="datetime-local" class="form-control" value="{{ $transaction->date->format('Y-m-d\TH:i') }}" disabled>
                    </div>

                    @if($transaction->type != 'TRANS')
                    <div class="form-group">
                        <label>Kategori</label>
                        <select class="form-control" disabled>
                            @foreach($categories as $cat)
                                @if($cat->type == $transaction->type)
                                    <option {{ $transaction->category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->icon }} {{ $cat->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="row">
                        @if($transaction->type != 'IN')
                        <div class="col-md-12 form-group">
                            <label>{{ $transaction->type == 'TRANS' ? 'Dari Dompet (Asal)' : 'Dompet (Sumber)' }}</label>
                             <select class="form-control" disabled>
                                @foreach($wallets as $wallet)
                                    <option {{ $transaction->from_wallet_id == $wallet->id ? 'selected' : '' }}>{{ $wallet->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        
                        @if($transaction->type != 'OUT')
                        <div class="col-md-12 form-group">
                            <label>{{ $transaction->type == 'TRANS' ? 'Ke Dompet (Tujuan)' : 'Dompet (Masuk)' }}</label>
                             <select class="form-control" disabled>
                                @foreach($wallets as $wallet)
                                    <option {{ $transaction->to_wallet_id == $wallet->id ? 'selected' : '' }}>{{ $wallet->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>Catatan (Opsional)</label>
                        <textarea class="form-control" rows="3" disabled>{{ $transaction->note }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
