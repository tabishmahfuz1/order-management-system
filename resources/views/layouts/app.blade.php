<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link href="{{ asset('custom-libraries/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <!-- <link href="https://fonts.googleapis.com/     css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet"> -->

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>
<body id="page-top">
    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('custom-libraries/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('custom-libraries/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('custom-libraries/jquery-easing/jquery.easing.min.js') }}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <div id="app">
        <!-- <main class="py-4"> -->
            <div id="wrapper">
                @auth
                    @include('layouts.left_sidebar')
                @endauth

                <div id="content-wrapper" class="d-flex flex-column" style="height: 100vh; overflow: auto;">
                    <!-- Main Content -->
                    <div id="content">
                        @auth
                            @include('layouts.topbar')
                        @endauth
                        @yield('content')
                    </div>
                    <!-- End of Main Content -->

                    @include('layouts.footer')
                    <!-- End of Content Wrapper -->
                </div>
            </div>
        <!-- </main> -->
    </div>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
</body>
<script type="text/javascript">
    Date.prototype.toDatabaseFormat = function() {
      var mm = this.getMonth() + 1; // getMonth() is zero-based
      var dd = this.getDate();

      return [this.getFullYear(),
              (mm>9 ? '' : '0') + mm,
              (dd>9 ? '' : '0') + dd
             ].join('-');
    };
</script>
</html>
