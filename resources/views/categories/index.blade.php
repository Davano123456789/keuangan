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
              <button type="button" class="btn btn-primary" onclick="alert('Fitur tambah kategori belum diaktifkan saat ini.')">
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
                                </tr>
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
</x-master>
