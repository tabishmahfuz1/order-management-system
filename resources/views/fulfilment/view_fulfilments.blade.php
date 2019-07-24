@extends('layouts.app')

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<!-- <h4 class="h3 mb-2 text-gray-800">Fulfilments</h4> -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Manage Fulfilments</h6>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th>Fulfilment Number</th>
            <th>Fulfilment Date</th>
            <th>Sales Order Number</th>
            <th>Order Date</th>
            <th>{{ $customer_module_name ?? "Distributor" }}</th>
            <th>Reference Number</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th>Fulfilment Number</th>
            <th>Fulfilment Date</th>
            <th>Sales Order Number</th>
            <th>Order Date</th>
            <th>{{ $customer_module_name ?? "Distributor" }}</th>
            <th>Reference Number</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </tfoot>
        <tbody>
          @if(!empty($fulfilments))
            @foreach($fulfilments as $fulfilment)
              <tr>
                <td>{{ $fulfilment->fulfilment_no }}</td>
                <td> @date($fulfilment->fulfilment_date) </td>
                <td>{{ $fulfilment->sales_order_no }}</td>
                <td>{{ $fulfilment->order_date }}</td>
                <td>{{ $fulfilment->customer_name }}</td>
                <td>{{ $fulfilment->ref_no }}</td>
                <td class="text-center">{{ $fulfilment->status == 1 ? "Active" : "N.A." }}</td>
                <td class="text-center"><a href="{{ route('edit_fulfilment', [ $fulfilment->id ]) }}"><i class="fa fa-edit"></i></a></td>
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
  var menu_id = "view_fulfilments";
</script>
@endsection