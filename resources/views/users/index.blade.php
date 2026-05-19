<x-master>
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row align-items-center">
                <div class="col-12 col-md-8 mb-3 mb-md-0">
                    <h3 class="font-weight-bold">Manajemen Pegawai</h3>
                    <h6 class="font-weight-normal mb-0">Kelola akses akun untuk karyawan perusahaan di sini.</h6>
                </div>
                <div class="col-12 col-md-4 text-md-right">
                    <a href="{{ route('users.create') }}" class="btn btn-primary">+ Tambah Pegawai</a>
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
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Akses Dompet</th>
                                    <th>Bergabung Sejak</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="font-weight-bold">{{ $user->username }}
                                            @if(auth()->id() === $user->id)
                                                <span class="badge badge-success px-2 py-1 ml-2">Anda</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="badge {{ $user->role === 'admin' ? 'badge-primary' : 'badge-info' }} text-capitalize">
                                                {{ $user->role }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($user->role === 'admin')
                                                <span class="text-muted small">Semua Dompet</span>
                                            @else
                                                @forelse($user->wallets as $wallet)
                                                    <span class="badge badge-outline-dark small mb-1">{{ $wallet->name }}</span>
                                                @empty
                                                    <span class="text-danger small">Tidak ada akses</span>
                                                @endforelse
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                                        <td>
                                            @if(auth()->id() !== $user->id)
                                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary mr-1">Edit</a>
                                                <form id="delete-form-{{ $user->id }}"
                                                    action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="confirmDelete('{{ $user->id }}', '{{ $user->username }}')">Cabut
                                                        Akses</button>
                                                </form>
                                            @else
                                                <button class="btn btn-sm btn-outline-secondary disabled" disabled>Cabut
                                                    Akses</button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-5 text-center text-muted">Belum ada akun pegawai
                                            terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
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
        document.addEventListener('DOMContentLoaded', function () {
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