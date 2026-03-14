<nav class="sidebar sidebar-offcanvas" id="sidebar">
<ul class="nav">
    <li class="nav-item">
    <a class="nav-link" href="{{ url('/') }}">
        <i class="icon-grid menu-icon"></i>
        <span class="menu-title">Dashboard</span>
    </a>
    </li>
    <li class="nav-item">
    <a class="nav-link" href="{{ route('transactions.index') }}">
        <i class="icon-layout menu-icon"></i>
        <span class="menu-title">Riwayat Transaksi</span>
    </a>
    </li>
    <li class="nav-item">
    <a class="nav-link" href="{{ route('wallets.index') }}">
        <i class="icon-head menu-icon"></i>
        <span class="menu-title">Dompet Saya</span>
    </a>
    </li>
    <li class="nav-item">
    <a class="nav-link" href="{{ route('categories.index') }}">
        <i class="icon-grid-2 menu-icon"></i>
        <span class="menu-title">Kategori</span>
    </a>
    </li>
    <li class="nav-item border-top mt-3">
    <a class="nav-link" href="{{ route('users.index') }}">
        <i class="icon-head menu-icon"></i>
        <span class="menu-title">Manajemen Pegawai</span>
    </a>
    </li>
    <li class="nav-item border-top mt-3">
    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="ti-power-off menu-icon text-danger"></i>
        <span class="menu-title text-danger">Logout</span>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
    </li>
</ul>
</nav>
