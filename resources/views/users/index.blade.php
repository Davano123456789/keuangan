<x-master>
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row align-items-center">
                <div class="col-12 col-md-8 mb-3 mb-md-0">
                    <h3 class="font-weight-bold">Manajemen Pegawai</h3>
                    <h6 class="font-weight-normal mb-0">Kelola akses akun untuk karyawan perusahaan di sini.</h6>
                </div>
                <div class="col-12 col-md-4 text-md-right">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">+ Tambah Pegawai</button>
                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Pegawai</th>
                                    <th>Email Akses</th>
                                    <th>Bergabung Sejak</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="font-weight-bold">{{ $user->name }}
                                        @if(auth()->id() === $user->id) 
                                            <span class="badge badge-success px-2 py-1 ml-2">Anda</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        @if(auth()->id() !== $user->id)
                                        <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ $user->id }}', '{{ $user->name }}')">Cabut Akses</button>
                                        </form>
                                        @else
                                        <button class="btn btn-sm btn-outline-secondary disabled" disabled>Cabut Akses</button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-5 text-center text-muted">Belum ada akun pegawai terdaftar.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content text-left">
                <div class="modal-header">
                    <h5 class="modal-title">Buat Akun Pegawai Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Budi Santoso" value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Alamat Email</label>
                            <input type="email" name="email" class="form-control" placeholder="pegawai@perusahaan.com" value="{{ old('email') }}" required>
                            <small class="text-muted">Email ini akan digunakan pegawai untuk login ke sistem.</small>
                        </div>
                        <div class="form-group">
                            <label>Password Akun</label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required minlength="8">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan dan Beri Akses</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

     <!-- SweetAlert Script -->
     <script>
        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Cabut Akses Pegawai?',
                text: "Kamu akan menghapus akses untuk " + name + ". Pengguna ini tidak akan bisa login lagi ke dalam sistem ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Cabut Akses!',
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

            @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
            });
            @endif

            @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan Input',
                html: '<ul>' +
                    @foreach($errors->all() as $error)
                        '<li>{{ $error }}</li>' +
                    @endforeach
                '</ul>',
            });
            @endif
        });
    </script>
</x-master>
