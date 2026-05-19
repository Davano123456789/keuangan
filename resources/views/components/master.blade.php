<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>{{ config('app.name', 'KeuanganKu') }}</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('dashboard-admin/vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('dashboard-admin/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('dashboard-admin/vendors/css/vendor.bundle.base.css') }}">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="{{ asset('dashboard-admin/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
  <link rel="stylesheet" href="{{ asset('dashboard-admin/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('dashboard-admin/js/select.dataTables.min.css') }}">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('dashboard-admin/css/vertical-layout-light/style.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('dashboard-admin/images/favicon.png') }}" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- PWA Meta Tags -->
  <meta name="theme-color" content="#4B49AC">
  <link rel="apple-touch-icon" href="{{ asset('img/pwa/FinanKu.png') }}">
  <link rel="manifest" href="{{ asset('manifest.json') }}">

  <style>
    /* Override sidebar-icon-only agar berfungsi baik dengan sidebar-fixed */
    @media (min-width: 992px) {
      .sidebar-fixed .sidebar {
        transition: width 0.25s ease;
      }
      .sidebar-fixed .main-panel {
        transition: width 0.25s ease, margin-left 0.25s ease;
      }
      /* Ketika icon-only: sidebar menyempit ke 70px */
      .sidebar-fixed.sidebar-icon-only .sidebar {
        width: 70px !important;
      }
      /* Main panel melebar mengisi sisa ruang */
      .sidebar-fixed.sidebar-icon-only .main-panel {
        margin-left: 70px !important;
        width: calc(100% - 70px) !important;
      }
      /* Sembunyikan teks menu, tampilkan hanya icon */
      .sidebar-fixed.sidebar-icon-only .sidebar .nav .nav-item .nav-link .menu-title,
      .sidebar-fixed.sidebar-icon-only .sidebar .nav .nav-item .nav-link .badge {
        display: none !important;
      }
      /* Besarkan icon sedikit saat icon-only */
      .sidebar-fixed.sidebar-icon-only .sidebar .nav .nav-item .nav-link i.menu-icon {
        margin-right: 0;
        font-size: 1.25rem;
      }
      /* Pusatkan icon */
      .sidebar-fixed.sidebar-icon-only .sidebar .nav .nav-item .nav-link {
        display: flex;
        justify-content: center;
        padding: 1rem 0.5rem;
      }
      /* Nonaktifkan hover expand saat sidebar icon-only */
      .sidebar-fixed.sidebar-icon-only .sidebar .nav .nav-item.hover-open .nav-link .menu-title {
        display: none !important;
      }
      .sidebar-fixed.sidebar-icon-only .sidebar .nav .nav-item.hover-open .collapse,
      .sidebar-fixed.sidebar-icon-only .sidebar .nav .nav-item.hover-open .collapsing {
        display: none !important;
      }
      .sidebar-fixed.sidebar-icon-only .sidebar:hover {
        width: 70px !important;
      }
      /* Hapus garis vertikal di antara sidebar dan konten */
      .sidebar-fixed .sidebar {
        border-right: none !important;
        box-shadow: none !important;
      }
      .navbar .navbar-brand-wrapper {
        border-right: none !important;
      }
      /* Hapus garis dari pagination Laravel */
      nav[role="navigation"] {
        border: none !important;
      }
      nav[role="navigation"] > div:first-child {
        display: none !important;
      }
      .pagination {
        border: none !important;
        margin-bottom: 0 !important;
      }
      .pagination .page-item .page-link {
        border: 1px solid #dee2e6 !important;
      }
      .pagination .page-item:first-child .page-link {
        border-left: 1px solid #dee2e6 !important;
      }
    }
  </style>
</head>
<body class="sidebar-fixed">
  <div class="container-scroller">
    <x-navbar></x-navbar>
    
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      <div class="theme-setting-wrapper">
        <div id="settings-trigger"><i class="ti-settings"></i></div>
        <div id="theme-settings" class="settings-panel">
          <i class="settings-close ti-close"></i>
          <p class="settings-heading">SIDEBAR SKINS</p>
          <div class="sidebar-bg-options selected" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border mr-3"></div>Light</div>
          <div class="sidebar-bg-options" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark</div>
          <p class="settings-heading mt-2">HEADER SKINS</p>
          <div class="color-tiles mx-0 px-4">
            <div class="tiles success"></div>
            <div class="tiles warning"></div>
            <div class="tiles danger"></div>
            <div class="tiles info"></div>
            <div class="tiles dark"></div>
            <div class="tiles default"></div>
          </div>
        </div>
      </div>
      
      <x-sidebar></x-sidebar>

      <div class="main-panel">
        <div class="content-wrapper">
            {{ $slot }}
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2026. KeuanganKu Apps. All rights reserved.</span>
          </div>
        </footer> 
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>   
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="{{ asset('dashboard-admin/vendors/js/vendor.bundle.base.js') }}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="{{ asset('dashboard-admin/vendors/chart.js/Chart.min.js') }}"></script>
  <script src="{{ asset('dashboard-admin/vendors/datatables.net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('dashboard-admin/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
  <script src="{{ asset('dashboard-admin/js/dataTables.select.min.js') }}"></script>

  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="{{ asset('dashboard-admin/js/off-canvas.js') }}"></script>
  <script src="{{ asset('dashboard-admin/js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('dashboard-admin/js/template.js') }}"></script>
  <script src="{{ asset('dashboard-admin/js/settings.js') }}"></script>
  <script src="{{ asset('dashboard-admin/js/todolist.js') }}"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="{{ asset('dashboard-admin/js/dashboard.js') }}"></script>
  <script src="{{ asset('dashboard-admin/js/Chart.roundedBarCharts.js') }}"></script>
  <!-- End custom js for this page-->

  <!-- PWA Service Worker Registration (Disabled for Debugging)
  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
          .then(reg => console.log('Service Worker: Registered'))
          .catch(err => console.log(`Service Worker: Error: ${err}`));
      });
    }
  </script>
  -->
  <!-- SweetAlert Session Handling -->
  <script>
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
    });
  </script>


  <!-- Cegah scroll mengubah nilai input number -->
  <script>
    document.addEventListener('wheel', function (e) {
      if (document.activeElement.type === 'number') {
        document.activeElement.blur();
      }
    }, { passive: true });
  </script>
</body>

</html>
