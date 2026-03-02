<x-master>
    <div class="row">
        <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
            <h3 class="font-weight-bold">Dompet Saya</h3>
            <h6 class="font-weight-normal mb-0">Kelola semua sumber dana kamu di sini. <span class="text-primary">Mulai catat dengan rapi!</span></h6>
            </div>
            <div class="col-12 col-xl-4">
             <div class="justify-content-end d-flex">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addWalletModal">
                + Tambah Dompet Baru
              </button>
             </div>
            </div>
        </div>
        </div>
    </div>



    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Daftar Dompet (Wallets)</h4>
                    <p class="card-description">Daftar semua kas keuangan yang kamu kelola.</p>
                    <div class="table-responsive">
                        <table class="table table-striped text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Dompet</th>
                                    <th>Saldo Aktif</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($wallets as $index => $wallet)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="font-weight-bold">{{ $wallet->name }}</td>
                                    <td>Rp {{ number_format($wallet->balance, 0, ',', '.') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#editWalletModal{{ $wallet->id }}">Edit</button>
                                        <form id="delete-form-{{ $wallet->id }}" action="{{ route('wallets.destroy', $wallet->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('{{ $wallet->id }}', '{{ $wallet->name }}')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Modal (Inside Foreach Loop) -->
                                <div class="modal fade" id="editWalletModal{{ $wallet->id }}" tabindex="-1" role="dialog" aria-labelledby="editWalletModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content text-left">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editWalletModalLabel">Edit Dompet</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('wallets.update', $wallet->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Nama Dompet</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $wallet->name }}" required placeholder="Contoh: BCA, OVO, Tunai">
                                                </div>
                                                <div class="form-group">
                                                    <label>Saldo Awal (Rp)</label>
                                                    <input type="number" name="balance" class="form-control" value="{{ $wallet->balance }}" required>
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
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Kamu belum memiliki dompet. Silakan Tambahkan Dompet Baru.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Wallet -->
    <div class="modal fade" id="addWalletModal" tabindex="-1" role="dialog" aria-labelledby="addWalletModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addWalletModalLabel">Tambah Dompet Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('wallets.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Dompet</label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: BCA, OVO, Tunai">
                    </div>
                    <div class="form-group">
                        <label>Saldo Awal (Rp)</label>
                        <input type="number" name="balance" class="form-control" value="0" required min="0">
                        <small class="form-text text-muted">Isi saldo awal jika dompet ini sudah memiliki uang di dalamnya.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- Modal Add Wallet -->
    <div class="modal fade" id="addWalletModal" tabindex="-1" role="dialog" aria-labelledby="addWalletModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addWalletModalLabel">Tambah Dompet Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('wallets.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Dompet</label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: BCA, OVO, Tunai">
                    </div>
                    <div class="form-group">
                        <label>Saldo Awal (Rp)</label>
                        <input type="number" name="balance" class="form-control" value="0" required min="0">
                        <small class="form-text text-muted">Isi saldo awal jika dompet ini sudah memiliki uang di dalamnya.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- SweetAlert Script -->
    <script>
        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Hapus Dompet?',
                text: "Kamu akan menghapus dompet: " + name + ". Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }

        // Show success message if session has 'success'
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false
            });
            @endif
        });
    </script>
</x-master>
