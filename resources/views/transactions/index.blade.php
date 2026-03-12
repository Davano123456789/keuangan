<x-master>
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Riwayat Transaksi</h3>
                    <h6 class="font-weight-normal mb-0">Pantau semua arus kas kamu di sini.</h6>
                </div>
                <div class="col-12 col-xl-4 text-right">
                    <a href="{{ route('transactions.export') }}" class="btn btn-success mr-2 text-white">
                        <i class="ti-download"></i> Export Excel
                    </a>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addTransactionModal">+ Tambah Transaksi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="row mb-4">
        <div class="col-md-12">
            <!-- Mobile Dropdown -->
            <div class="dropdown d-md-none">
                <button class="btn btn-outline-primary dropdown-toggle w-100 text-left d-flex justify-content-between align-items-center" type="button" id="mobileTabDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 15px; padding: 12px 20px;">
                    <span id="selectedTabLabel">Filter: Semua</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right w-100" aria-labelledby="mobileTabDropdown" style="border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <a class="dropdown-item py-3 transaction-filter {{ !$type ? 'bg-primary text-white' : '' }}" href="{{ route('transactions.index') }}" data-type="">Semua</a>
                    <a class="dropdown-item py-3 transaction-filter {{ $type == 'IN' ? 'bg-primary text-white' : '' }}" href="{{ route('transactions.index', ['type' => 'IN']) }}" data-type="IN">Pemasukan</a>
                    <a class="dropdown-item py-3 transaction-filter {{ $type == 'OUT' ? 'bg-primary text-white' : '' }}" href="{{ route('transactions.index', ['type' => 'OUT']) }}" data-type="OUT">Pengeluaran</a>
                    <a class="dropdown-item py-3 transaction-filter {{ $type == 'TRANS' ? 'bg-primary text-white' : '' }}" href="{{ route('transactions.index', ['type' => 'TRANS']) }}" data-type="TRANS">Pindah Saldo</a>

                </div>
            </div>

            <!-- Desktop Pills -->
            <ul class="nav nav-pills nav-pills-custom d-none d-md-flex" id="transactionTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link transaction-filter {{ !$type ? 'active' : '' }}" href="{{ route('transactions.index') }}" data-type="">Semua</a>

                </li>
                <li class="nav-item">
                    <a class="nav-link transaction-filter {{ $type == 'IN' ? 'active' : '' }}" href="{{ route('transactions.index', ['type' => 'IN']) }}" data-type="IN">Pemasukan</a>

                </li>
                <li class="nav-item">
                    <a class="nav-link transaction-filter {{ $type == 'OUT' ? 'active' : '' }}" href="{{ route('transactions.index', ['type' => 'OUT']) }}" data-type="OUT">Pengeluaran</a>
                </li>
                <li class="nav-item">
                     <a class="nav-link transaction-filter {{ $type == 'TRANS' ? 'active' : '' }}" href="{{ route('transactions.index', ['type' => 'TRANS']) }}" data-type="TRANS">Pindah Saldo</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="tab-content" id="transactionTabsContent">
        <div class="tab-pane fade show active" role="tabpanel">
            @include('transactions.partials.table', ['data' => $transactions])
        </div>
    </div>

    <!-- Modal Add Transaction -->
    <div class="modal fade" id="addTransactionModal" tabindex="-1" role="dialog" aria-labelledby="addTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTransactionModalLabel">Tambah Transaksi Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('transactions.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tipe Transaksi</label>
                            <select name="type" id="typeSelect" class="form-control" required>
                                <option value="OUT">Pengeluaran</option>
                                <option value="IN">Pemasukan</option>
                                <option value="TRANS">Pindah Saldo</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Nominal (Rp)</label>
                            <input type="number" name="amount" class="form-control" placeholder="0" required min="1">
                        </div>

                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="datetime-local" name="date" class="form-control" value="{{ date('Y-m-d\TH:i') }}" required>
                        </div>

                        <!-- Category Row (For IN/OUT only) -->
                        <div class="form-group" id="categoryGroup">
                            <label>Kategori</label>
                            <select name="category_id" class="form-control" id="categorySelect">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" data-type="{{ $cat->type }}">{{ $cat->icon }} {{ $cat->name }} ({{ $cat->type == 'IN' ? 'Masuk' : 'Keluar' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Wallet Selectors -->
                        <div class="row">
                            <div class="col-md-12 form-group" id="fromWalletGroup">
                                <label id="fromWalletLabel">Dari Dompet (Sumber)</label>
                                 <select name="from_wallet_id" id="fromWalletSelect" class="form-control">
                                     <option value="">-- Pilih Dompet --</option>
                                     @foreach($wallets as $wallet)
                                         <option value="{{ $wallet->id }}">{{ $wallet->name }} (Rp {{ number_format($wallet->balance, 0, ',', '.') }})</option>
                                     @endforeach
                                 </select>
                             </div>
                             <div class="col-md-12 form-group" id="toWalletGroup" style="display:none;">
                                 <label id="toWalletLabel">Ke Dompet (Tujuan)</label>
                                 <select name="to_wallet_id" id="toWalletSelect" class="form-control">
                                     <option value="">-- Pilih Dompet --</option>
                                     @foreach($wallets as $wallet)
                                         <option value="{{ $wallet->id }}">{{ $wallet->name }} (Rp {{ number_format($wallet->balance, 0, ',', '.') }})</option>
                                     @endforeach
                                 </select>
                             </div>
                        </div>

                        <div class="form-group">
                            <label>Catatan (Opsional)</label>
                            <textarea name="note" class="form-control" rows="3" placeholder="Contoh: Beli makan siang, Gaji bulan ini..."></textarea>
                        </div>

                        <div class="form-group">
                            <label>Input Oleh (Nama)</label>
                            <input type="text" name="created_by" class="form-control" placeholder="Contoh: Davano, Admin, Sekretaris" value="Admin">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="saveButton">Simpan Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Custom CSS for Pills -->
    <style>
        .nav-pills-custom .nav-link {
            border-radius: 20px;
            padding: 8px 25px;
            margin-right: 10px;
            background: #f8f9fa;
            color: #4B49AC;
            font-weight: 500;
            border: 1px solid #ddd;
        }
        .nav-pills-custom .nav-link.active {
            background: #4B49AC;
            color: white;
            border-color: #4B49AC;
        }
    </style>

    <script>
        function updateTabLabel(text) {
            document.getElementById('selectedTabLabel').innerText = 'Filter: ' + text;
        }

        document.addEventListener('DOMContentLoaded', function() {
            function loadTransactions(url) {
                // Show loading indicator
                $('#transactionTabsContent').html('<div class="card"><div class="card-body text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-3">Memuat transaksi...</p></div></div>');

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        var newContent = $(response).find('#transactionTabsContent').html();
                        $('#transactionTabsContent').html(newContent);

                        let type = new URL(url).searchParams.get('type') || '';

                        // Update active states for both desktop and mobile
                        $('.transaction-filter').removeClass('active bg-primary text-white');
                        $('.nav-link.transaction-filter[data-type="' + type + '"]').addClass('active');
                        $('.dropdown-item.transaction-filter[data-type="' + type + '"]').addClass('bg-primary text-white');

                        // Update mobile dropdown label
                        let labelText = $('.transaction-filter[data-type="' + type + '"]').first().text();
                        $('#selectedTabLabel').text('Filter: ' + labelText);
                    },
                    error: function() {
                        $('#transactionTabsContent').html('<div class="card"><div class="card-body text-center py-5"><p class="text-danger">Gagal memuat data. Silakan muat ulang halaman.</p></div></div>');
                    }
                });
            }

            // Set initial state for browser history to make back/forward work seamlessly
            history.replaceState({path: window.location.href}, '', window.location.href);

            $(document).on('click', '.transaction-filter', function(e) {
                e.preventDefault();
                let url = $(this).attr('href');

                // Prevent reloading if the filter is already active
                if (new URL(url).href === new URL(location.href).href) {
                    return;
                }

                loadTransactions(url);
                history.pushState({path: url}, '', url);
            });

            window.addEventListener('popstate', function(event) {
                // When the user uses browser back/forward, load the content for that state
                if (event.state) {
                    loadTransactions(event.state.path);
                }
            });
            const typeSelect = document.getElementById('typeSelect');
            const categoryGroup = document.getElementById('categoryGroup');
            const fromWalletGroup = document.getElementById('fromWalletGroup');
            const toWalletGroup = document.getElementById('toWalletGroup');
            const categorySelect = document.getElementById('categorySelect');
            const saveButton = document.getElementById('saveButton');

            // Wallet requirement alert
            @if($wallets->isEmpty())
                saveButton.disabled = true;
                saveButton.innerText = 'Buat Dompet Dulu';
            @endif

             const fromWalletSelect = document.getElementById('fromWalletSelect');
             const toWalletSelect = document.getElementById('toWalletSelect');

             function updateWalletOptions() {
                 const selectedFrom = fromWalletSelect.value;
                 const selectedTo = toWalletSelect.value;

                 // Filter To Wallet based on From Wallet
                 Array.from(toWalletSelect.options).forEach(option => {
                     if (option.value === "") return;
                     option.style.display = (option.value === selectedFrom) ? 'none' : 'block';
                 });

                 // Filter From Wallet based on To Wallet
                 Array.from(fromWalletSelect.options).forEach(option => {
                     if (option.value === "") return;
                     option.style.display = (option.value === selectedTo) ? 'none' : 'block';
                 });
             }

             fromWalletSelect.addEventListener('change', updateWalletOptions);
             toWalletSelect.addEventListener('change', updateWalletOptions);

             function updateForm() {
                 const type = typeSelect.value;
                 
                 // Reset Visibility and Required
                 categoryGroup.style.display = 'block';
                 fromWalletGroup.style.display = 'none';
                 toWalletGroup.style.display = 'none';
                 
                 categorySelect.required = false;
                 fromWalletSelect.required = false;
                 toWalletSelect.required = false;

                 if (type === 'OUT') {
                     fromWalletGroup.style.display = 'block';
                     document.getElementById('fromWalletLabel').innerText = 'Dari Dompet (Sumber)';
                     categorySelect.required = true;
                     fromWalletSelect.required = true;
                 } else if (type === 'IN') {
                     toWalletGroup.style.display = 'block';
                     document.getElementById('toWalletLabel').innerText = 'Masuk ke Dompet';
                     categorySelect.required = true;
                     toWalletSelect.required = true;
                 } else if (type === 'TRANS') {
                     categoryGroup.style.display = 'none';
                     fromWalletGroup.style.display = 'block';
                     toWalletGroup.style.display = 'block';
                     document.getElementById('fromWalletLabel').innerText = 'Dari Dompet (Asal)';
                     document.getElementById('toWalletLabel').innerText = 'Ke Dompet (Tujuan)';
                     fromWalletSelect.required = true;
                     toWalletSelect.required = true;
                 }

                 // Filter Categories based on type
                 Array.from(categorySelect.options).forEach(option => {
                     if (option.value === "") return;
                     const catType = option.getAttribute('data-type');
                     option.style.display = (catType === type) ? 'block' : 'none';
                 });
                 categorySelect.value = "";
                 
                 // Reset wallet filters when type changes
                 updateWalletOptions();
             }

             typeSelect.addEventListener('change', updateForm);
             updateForm(); // Init

             // Global Filter for Edit Modals (using delegation)
             document.addEventListener('change', function(e) {
                 if (e.target.classList.contains('edit-from-wallet') || e.target.classList.contains('edit-to-wallet')) {
                     const modal = e.target.closest('.modal');
                     const fromSelect = modal.querySelector('.edit-from-wallet');
                     const toSelect = modal.querySelector('.edit-to-wallet');
                     
                     if (fromSelect && toSelect) {
                         const fromVal = fromSelect.value;
                         const toVal = toSelect.value;

                         Array.from(toSelect.options).forEach(opt => {
                             if (opt.value === "") return;
                             opt.style.display = (opt.value === fromVal) ? 'none' : 'block';
                         });

                         Array.from(fromSelect.options).forEach(opt => {
                             if (opt.value === "") return;
                             opt.style.display = (opt.value === toVal) ? 'none' : 'block';
                         });
                     }
                 }
             });

            // Delete Confirmation
            window.confirmDeleteTransaction = function(id) {
                Swal.fire({
                    title: 'Hapus Transaksi?',
                    text: "Menghapus transaksi ini akan mengembalikan saldo dompet kamu ke kondisi sebelumnya. Lanjutkan?",
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

            // SweetAlert Handlers
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: {!! json_encode(session('success')) !!},
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Waduh!',
                    text: {!! json_encode(session('error')) !!},
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Input Tidak Valid!',
                    html: `{!! "<ul>" . implode('', array_map(fn($e) => "<li>$e</li>", $errors->all())) . "</ul>" !!}`,
                });
            @endif
        });
    </script>
</x-master>
