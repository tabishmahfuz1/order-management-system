@extends('layouts.app')

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<h4 class="h3 mb-2 text-gray-800">{{ $customer_module_name ?? "Distributors" }}</h4>
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Manage {{ $customer_module_name ?? "Distributors" }}</h6>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th>Name</th>
            <th>Address</th>
            <th>City</th>
            <th>State</th>
            <th>Discount (%)</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th>Name</th>
            <th>Address</th>
            <th>City</th>
            <th>State</th>
            <th>Discount (%)</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </tfoot>
        <tbody>
          @if(!empty($customers))
            @foreach($customers as $customer)
              <tr>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->address }}</td>
                <td>{{ $customer->city }}</td>
                <td>{{ $customer->state }}</td>
                <td class="text-right">{{ $customer->discount }}</td>
                <td class="text-center">{{ $customer->status == 1 ? "Active" : "Disabled" }}</td>
                <td class="text-center"><a href="{{ route('edit_customer', [$customer->id]) }}"><i class="fa fa-edit"></i></a></td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>
  </div>
</div>

</div>
<!-- /.container-fluid -->
@include('plugins.datatables')
<!-- Page level custom scripts -->
<script src="{{ asset('js/demo/datatables-demo.js') }}"></script>

<script type="text/javascript">
  var menu_id = "view_customers";
</script>
@endsection