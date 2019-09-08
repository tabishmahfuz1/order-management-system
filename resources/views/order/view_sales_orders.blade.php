@extends('layouts.app')
@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<!-- <h4 class="h3 mb-2 text-gray-800">Orders</h4> -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Manage Orders</h6>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <form method="GET" action="{{ route('view_sales_orders') }}" autocomplete="off" id="search_form">
        <table class="table table-bordered table-striped table-hover display nowrap" 
                id="dataTable" 
                width="100%" 
                cellspacing="0">
          <thead>
            <tr>
              <th width="15%">Sales Order Number</th>
              <th width="15%">Order Date</th>
              <th>{{ $customer_module_name ?? "Distributor" }}</th>
              <th>Reference Number</th>
              <th width="15%">Order Amount</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <td>
                <input class="form-control form-control-sm text-center" 
                        name="sales_order_no"
                        placeholder="Sales Order Number" />
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
                        name="customer" />
              </td>
              <td>
                <input class="form-control form-control-sm" 
                        placeholder="Reference Number" 
                        name="ref_no" />
              </td>
              <td>
                @include('components.comparison_filter_input', 
                            [ 'name' => 'order_total', 
                              'placeholder' => 'Order Total',
                              'input_class' => 'text-right',
                              'type' => 'number',
                              'step' => '.01'])  

              </td>
              <td>
                <select name="order_status" class="form-control form-control-sm">
                  <option value="">Status</option>
                </select>
              </td>
              <td>
                <button class="btn btn-sm btn-primary">
                  <i class="fa fa-search fa-sm"></i>
                </button>
              </td>
            </tr>
          </tfoot>
          <tbody>
            @if(!empty($orders))
              @foreach($orders as $order)
                <tr>
                  <td class="text-center">{{ $order->sales_order_no }}</td>
                  <td class="text-center"> @date($order->order_date) </td>
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
      </form>
    </div>
  </div>
</div>

</div>
<!-- /.container-fluid -->

<script src="{{ asset('js/demo/datatables-demo.js') }}"></script>
<script type="text/javascript">
  $(function(){
    $('.datepicker').datepicker({
      format: 'yyyy-mm-dd',
    });
  });

  var menu_id = "view_sales_orders";
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
@include('plugins.datepicker')
<!-- Page level custom scripts -->
@endsection