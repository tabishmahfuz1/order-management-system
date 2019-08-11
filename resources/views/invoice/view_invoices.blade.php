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
      <form id="search_form" action="{{ route('view_invoices') }}">
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
              <td>  
                  <input class="form-control form-control-sm text-center"
                          placeholder="Invoice Number"
                          name="invoice_no" />
              </td>
              <td>
                @include('components.comparison_filter_input', 
                            [ 'name' => 'invoice_date',
                              'input_class' => 'datepicker text-center',
                              'placeholder' => 'Invoice Date']) 
              </td>
              <td>  
                  <input class="form-control form-control-sm text-center"
                          placeholder="Sales Order Number"
                          name="sales_order_no" />
              </td>
              <td>
                @include('components.comparison_filter_input', 
                            [ 'name' => 'order_date',
                              'input_class' => 'datepicker text-center',
                              'placeholder' => 'Order Date']) 
              </td>
              <td>  
                  <input class="form-control form-control-sm"
                          placeholder="Customer"
                          name="customer_name" />
              </td>
              <td>
                @include('components.comparison_filter_input', 
                            [ 'name' => 'invoice_total',
                              'type' => 'number',
                              'input_class' => 'text-right',
                              'placeholder' => 'Invoice Total']) 
              </td>
              <td>
                @include('components.comparison_filter_input', 
                            [ 'name' => 'balance_amt',
                              'type' => 'number',
                              'input_class' => 'text-right',
                              'placeholder' => 'Invoice Balance']) 
              </td>
              <td>  
                  <button class="btn btn-sm btn-primary">  
                      <i class="fa fa-sm fa-search"> </i>
                  </button>
              </td>
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
      </form>
    </div>
  </div>
</div>

</div>
<!-- /.container-fluid -->
<!-- Page level custom scripts -->
<script async src="{{ asset('js/demo/datatables-demo.js') }}"></script>

<script type="text/javascript">
  var menu_id = "view_invoices";
  var getVars = {!! json_encode($_GET) !!};
  if(getVars) {
    for (let i in getVars) {
      $('[name=' + i + ']').val(getVars[i]);
    }
  }
  $('form#search_form').submit( async function(e){
    e.preventDefault();
    $.get(this.action, $(this).serialize(), function(content) {
      $('#content-wrapper #content').html(content);
      // window.history.pushState({urlPath:this.url, content: content},"",this.url);
    });
  });
  /*window.addEventListener('popstate', (event) => {
    console.log(event, "location: " + document.location + ", state: " + JSON.stringify(event.state));
    if(event.state && event.state.content)
      $('#content-wrapper #content').html(event.state.content);
    else
      window.location.href = window.location.href;
  });*/
</script>
@endsection
@section('scripts')
@include('plugins.datatables')
@endsection