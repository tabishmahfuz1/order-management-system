@extends('layouts.app')

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<!-- <h4 class="h3 mb-2 text-gray-800">Invoices</h4> -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Manage Invoices</h6>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th>Invoice Number</th>
            <th>Invoice Date</th>
            <th>Sales Order Number</th>
            <th>Order Date</th>
            <th>{{ $customer_module_name ?? "Distributor" }}</th>
            <th>Invoice Total</th>
            <th>Invoice Balance</th>
            <th>Action</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th>Invoice Number</th>
            <th>Invoice Date</th>
            <th>Sales Order Number</th>
            <th>Order Date</th>
            <th>{{ $customer_module_name ?? "Distributor" }}</th>
            <th>Invoice Total</th>
            <th>Invoice Balance</th>
            <th>Action</th>
          </tr>
        </tfoot>
        <tbody>
          @if(!empty($invoices))
            @foreach($invoices as $invoice)
              <tr>
                <td>{{ $invoice->invoice_no }}</td>
                <td> @date($invoice->invoice_date) </td>
                <td>{{ $invoice->sales_order_no }}</td>
                <td>{{ $invoice->order_date }}</td>
                <td>{{ $invoice->customer_name }}</td>
                <td class="text-right">{{ $invoice->getInvoiceTotal() }}</td>
                <td class="text-right">{{ $invoice->getBalanceAmount() }}</td>
                <td class="text-center"><a href="{{ route('edit_invoice', [ $invoice->id ]) }}"><i class="fa fa-edit"></i></a></td>
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
  var menu_id = "view_invoices";
</script>
@endsection