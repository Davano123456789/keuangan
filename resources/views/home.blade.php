<x-master>
    <div class="row">
        <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
            <h3 class="font-weight-bold">Welcome Admin</h3>
            <h6 class="font-weight-normal mb-0">Semua sistem berjalan lancar! <span class="text-primary">Aplikasi KeuanganKu siap digunakan.</span></h6>
            </div>
            <div class="col-12 col-xl-4">
            <div class="justify-content-end d-flex">
            <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <i class="mdi mdi-calendar"></i> Hari Ini ({{ \Carbon\Carbon::now()->format('d M Y') }})
                </button>
            </div>
            </div>
            </div>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
        <div class="card tale-bg">
            <div class="card-people mt-auto">
            <img src="{{ asset('dashboard-admin/images/dashboard/people.svg') }}" alt="people">
            <div class="weather-info">
                <div class="d-flex">
                <div>
                    <h2 class="mb-0 font-weight-normal">Rp {{ number_format($totalBalance, 0, ',', '.') }}</h2>
                </div>
                <div class="ml-2">
                    <h4 class="location font-weight-normal">Total Saldo</h4>
                    <h6 class="font-weight-normal">Semua Dompet</h6>
                </div>
                </div>
            </div>
            </div>
        </div>
        </div>
        <div class="col-md-6 grid-margin transparent">
        <div class="row">
            <div class="col-md-6 mb-4 stretch-card transparent">
            <div class="card card-tale">
                <div class="card-body">
                <p class="mb-4">Total Pemasukan Bulan Ini</p>
                <p class="fs-30 mb-2">Rp {{ number_format($incomeThisMonth, 0, ',', '.') }}</p>
                <p>Keseluruhan</p>
                </div>
            </div>
            </div>
            <div class="col-md-6 mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <div class="card-body">
                <p class="mb-4">Total Pengeluaran Bulan Ini</p>
                <p class="fs-30 mb-2">Rp {{ number_format($expenseThisMonth, 0, ',', '.') }}</p>
                <p>Keseluruhan</p>
                </div>
            </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
            <div class="card card-light-blue">
                <div class="card-body">
                <p class="mb-4">Jumlah Dompet Aktif</p>
                <p class="fs-30 mb-2">{{ $walletCount }}</p>
                <p>Dompet Terdaftar</p>
                </div>
            </div>
            </div>
            <div class="col-md-6 stretch-card transparent">
            <div class="card card-light-danger">
                <div class="card-body">
                <p class="mb-4">Transaksi Terakhir</p>
                <p class="fs-30 mb-2">{{ count($recentTransactions) }}</p>
                <p>Riwayat Terbaru</p>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
</x-master>
