<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
    <a class="navbar-brand brand-logo mr-5" href="{{ url('/') }}"><img src="{{asset('img/FinanKu.png')}}" class="mr-2" style="width: 160px; height: 110px;" alt="logo"/></a>
    <a class="navbar-brand brand-logo-mini" href="{{ url('/') }}"><img src="{{ asset('img/pwa/FinanKu.png') }}" alt="logo"/></a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
    <button class="navbar-toggler navbar-toggler align-self-center d-none d-lg-block" type="button" data-toggle="minimize">
        <span class="icon-menu"></span>
    </button>
    <ul class="navbar-nav mr-lg-2">
    </ul>
    <ul class="navbar-nav navbar-nav-right">
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
        <span class="icon-menu"></span>
    </button>
    </div>
</nav>
