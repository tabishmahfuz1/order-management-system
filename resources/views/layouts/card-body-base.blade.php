@extends('layouts.app')
@section('content')
<div class="container-fluid">

	<!-- Page Heading -->
	<!-- <h4 class="h3 mb-2 text-gray-800">Invoices</h4> -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
		    <h6 class="m-0 font-weight-bold text-primary">@yield('card-heading')</h6>
		</div>
		<div class="card-body">
			@yield('card-content')
		</div>
	</div>

</div>
@endsection

