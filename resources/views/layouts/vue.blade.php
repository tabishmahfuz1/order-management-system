<!DOCTYPE html>
<html>
<head>
	<title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link href="{{ asset('custom-libraries/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

</head>
<body>
	<!-- Bootstrap core JavaScript-->
    <script src="{{ asset('custom-libraries/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('custom-libraries/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('custom-libraries/jquery-easing/jquery.easing.min.js') }}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
	<div id="wrapper">
		@auth
            @include('layouts.left_sidebar')
        @endauth
        <div id="content-wrapper" class="d-flex flex-column" style="height: 100vh; overflow: auto;">
            <!-- Main Content -->
            @auth
                @include('layouts.topbar')
            @endauth
            <div id="app">
                @yield('content')
            </div>
            <!-- End of Main Content -->
            @yield('scripts')
            @include('layouts.footer')
            <!-- End of Content Wrapper -->
        </div>
	</div>
    <script src="{{ asset('/js/app.js') }}"></script>
    <script type="text/javascript">
	    Date.prototype.toDatabaseFormat = function() {
	      var mm = this.getMonth() + 1; // getMonth() is zero-based
	      var dd = this.getDate();

	      return [this.getFullYear(),
	              (mm>9 ? '' : '0') + mm,
	              (dd>9 ? '' : '0') + dd
	             ].join('-');
	    };
	    window.Laravel = {!! json_encode([
	        'csrfToken' => csrf_token()
	    ]) !!};
	</script>
    </body>
</html>