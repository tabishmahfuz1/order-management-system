@extends('layouts.app')

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<h4 class="h3 mb-2 text-gray-800">Orders</h4>
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Manage Orders</h6>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th>Sales Order Number</th>
            <th>Order Date</th>
            <th>{{ $customer_module_name ?? "Distributor" }}</th>
            <th>Reference Number</th>
            <th>Order Amount</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th>Sales Order Number</th>
            <th>Order Date</th>
            <th>{{ $customer_module_name ?? "Distributor" }}</th>
            <th>Reference Number</th>
            <th>Order Amount</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </tfoot>
        <tbody>
          @if(!empty($orders))
            @foreach($orders as $order)
              <tr>
                <td>{{ $order->sales_order_no }}</td>
                <td> @date($order->order_date) </td>
                <td>{{ $order->customer }}</td>
                <td>{{ $order->ref_no }}</td>
                <td class="text-right">{{ $order->order_total }}</td>
                <td class="text-center">{{ $order->getStatus() }}</td>
                <td class="text-center"><a href="{{ route('edit_sales_order', [ $order->id ]) }}"><i class="fa fa-edit"></i></a></td>
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
  var menu_id = "view_sales_orders";
</script>
@endsection