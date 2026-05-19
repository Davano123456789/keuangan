<x-master>
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row align-items-center">
                <div class="col-12 col-md-8 mb-3 mb-md-0">
                    <h3 class="font-weight-bold">Tambah Pegawai Baru</h3>
                    <h6 class="font-weight-normal mb-0">Buat akun akses untuk karyawan dan tentukan hak akses dompetnya.</h6>
                </div>
                <div class="col-12 col-md-4 text-md-right">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Formulir Akun Pegawai</h4>
                    <form action="{{ route('users.store') }}" method="POST" class="forms-sample">
                        @csrf
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control" id="username" placeholder="Contoh: budis" value="{{ old('username') }}" required>
                            <small class="text-muted">Username ini akan digunakan pegawai untuk login ke sistem.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password Akun</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Minimal 8 karakter" required minlength="8">
                        </div>

                        <div class="form-group">
                            <label for="role">Role / Peran</label>
                            <select name="role" class="form-control" id="role" required>
                                <option value="user">User / Pegawai</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>

                        <div class="form-group mt-4" id="walletSelection">
                            <label class="d-block mb-3">Hak Akses Dompet</label>
                            <div class="row px-3">
                                @foreach($wallets as $wallet)
                                <div class="col-md-3 col-sm-6 custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="wallet_{{ $wallet->id }}" name="wallet_ids[]" value="{{ $wallet->id }}">
                                    <label class="custom-control-label" for="wallet_{{ $wallet->id }}">{{ $wallet->name }}</label>
                                </div>
                                @endforeach
                            </div>
                            <small class="text-muted">Pilih dompet mana saja yang boleh diakses/dikelola oleh pegawai ini.</small>
                        </div>

                        <div class="mt-5">
                            <button type="submit" class="btn btn-primary mr-2">Simpan dan Beri Akses</button>
                            <a href="{{ route('users.index') }}" class="btn btn-light">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const walletSelection = document.getElementById('walletSelection');

            function toggleWallets() {
                if (roleSelect.value === 'admin') {
                    walletSelection.style.display = 'none';
                    // Uncheck all wallets if admin
                    walletSelection.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
                } else {
                    walletSelection.style.display = 'block';
                }
            }

            roleSelect.addEventListener('change', toggleWallets);
            
            // Initial check
            toggleWallets();
        });
    </script>
</x-master>
