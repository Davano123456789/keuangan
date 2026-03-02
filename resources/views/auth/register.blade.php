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
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('dashboard-admin/css/vertical-layout-light/style.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('dashboard-admin/images/favicon.png') }}" />
   <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="../../images/logo.svg" alt="logo">
              </div>
              <h4>Pengguna Baru?</h4>
              <h6 class="font-weight-light">Daftar gampang. Cuman sebentar</h6>
              <form class="pt-3" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                  <input type="text" name="name" class="form-control form-control-lg" id="exampleInputUsername1" placeholder="Username" required>
                </div>
                <div class="form-group">
                  <input type="email" name="email" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Email" required>
                </div>
                 <div class="form-group">
    <input type="password" name="password"
        class="form-control form-control-lg"
        id="password"
        placeholder="Password" required>
    <button class="btn" type="button" id="togglePassword">
        <i class="bi bi-eye"></i>
    </button>
</div>  
                <div class="mb-4">
                  <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input">
                      Saya menyetujui peraturan & kebijakan yang berlaku
                    </label>
                  </div>
                </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">DAFTAR</button>
                </div>
                <div class="text-center mt-4 font-weight-light">
                  Sudah punya akun? <a href="{{ route('login') }}" class="text-primary">Masuk</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="{{ asset('dashboard-admin/vendors/js/vendor.bundle.base.js') }}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="{{ asset('dashboard-admin/js/off-canvas.js') }}"></script>
  <script src="{{ asset('dashboard-admin/js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('dashboard-admin/js/template.js') }}"></script>
  <script src="{{ asset('dashboard-admin/js/settings.js') }}"></script>
  <script src="{{ asset('dashboard-admin/js/todolist.js') }}"></script>
  <!-- endinject -->
   <script>
    const togglePassword = document.querySelector('#togglePassword');
const password = document.querySelector('#password');
const icon = togglePassword.querySelector('i');

togglePassword.addEventListener('click', function () {
    const type = password.type === 'password' ? 'text' : 'password';
    password.type = type;

    icon.classList.toggle('bi-eye');
    icon.classList.toggle('bi-eye-slash');
});
  </script>
</body>

</html>
