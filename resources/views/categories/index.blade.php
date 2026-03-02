<x-master>
    <div class="row">
        <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
            <h3 class="font-weight-bold">Pengaturan Kategori</h3>
            <h6 class="font-weight-normal mb-0">Kelola daftar label untuk Pemasukan dan Pengeluaran kamu.</h6>
            </div>
            <div class="col-12 col-xl-4">
             <div class="justify-content-end d-flex">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCategoryModal">
                + Tambah Kategori
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
                    <h4 class="card-title">Daftar Kategori</h4>
                    <p class="card-description">Label yang digunakan untuk mencatat setiap transaksi.</p>
                    <div class="table-responsive">
                        <table class="table table-striped text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kategori</th>
                                    <th>Tipe</th>
                                     <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $index => $category)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="font-weight-bold">
                                        {{ $category->icon }} {{ $category->name }}
                                    </td>
                                    <td>
                                        @if($category->type == 'IN')
                                            <label class="badge badge-success">Pemasukan</label>
                                        @else
                                            <label class="badge badge-danger">Pengeluaran</label>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#editCategoryModal{{ $category->id }}">Edit</button>
                                        <form id="delete-form-{{ $category->id }}" action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('{{ $category->id }}', '{{ $category->name }}')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                <!-- Edit Modal (Inside Foreach Loop) -->
                                <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content text-left">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editCategoryModalLabel">Edit Kategori</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('categories.update', $category->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="form-group">
                        <label>Nama Kategori</label>
                        <input type="text" name="name" class="form-control" value="{{ $category->name }}" required placeholder="Contoh: Makanan, Transportasi, Gaji">
                    </div>
                                                <div class="form-group">
                        <label>Tipe Kategori</label>
                        <select name="type" class="form-control" required>
                            <option value="">Pilih Tipe Kategori</option>
                            <option value="IN" {{ $category->type == 'IN' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="OUT" {{ $category->type == 'OUT' ? 'selected' : '' }}>Pengeluaran</option>
                        </select>
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
                                    <td colspan="3" class="text-center text-muted py-4">Kamu belum memiliki kategori khusus. Gunakan tombol 'Tambah' untuk membuat yang baru.</td>
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
    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addWalletModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Tambah Kategori Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: Makanan, Transportasi, Gaji">
                    </div>
                    <div class="form-group">
                        <label>Tipe Kategori</label>
                        <select name="type" class="form-control" required>
                            <option value="">Pilih Tipe Kategori</option>
                            <option value="IN">Pemasukan</option>
                            <option value="OUT">Pengeluaran</option>
                        </select>
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
                title: 'Hapus Kategori?',
                text: "Kamu akan menghapus kategori: " + name + ". Data yang dihapus tidak bisa dikembalikan!",
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
