<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{(Utility::getValByName('title_text')) ? Utility::getValByName('title_text') : config('app.name', 'AMSLaw')}} - @yield('page-title')</title>
    <link rel="icon" href="{{asset(Storage::url('uploads/logo')).'/favicon.png'}}" type="image" sizes="16x16">

    <!-- Font Awesome 5 -->
  <link rel="stylesheet" href="{{ asset('assets/libs/@fortawesome/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/ams.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
</head>

<body class="application application-offset">
  <!-- Application container -->
  <div class="container-fluid container-application">
    <!-- Sidenav -->
    <!-- Content -->
    <div class="main-content position-relative">
      <!-- Main nav -->
      <!-- Page content -->
      <div class="page-content">

        <div class="min-vh-100 py-5 d-flex align-items-center">
          <div class="w-100">
            <div class="row justify-content-center">
              <div class="col-sm-8 col-lg-4">
                @yield('content')
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Footer -->
    </div>
  </div>
  <script src="{{ asset('assets/js/ams.core.js') }}"></script>
  <script src="{{ asset('assets/js/ams.js') }}"></script>
  <!-- Demo JS - remove it when starting your project -->
  <script src="{{ asset('assets/js/demo.js') }}"></script>
</body>

</html>
