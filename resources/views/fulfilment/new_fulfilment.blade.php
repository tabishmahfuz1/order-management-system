@extends('layouts.app')
@section('content')
@include('plugins.datepicker')
<link rel="stylesheet" href="{{asset('custom-libraries/select2/dist/css/select2.min.css')}}">
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <!-- <h1 class="h3 mb-0 text-gray-800">New Item</h1> -->
    <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
  </div>
  <div class="card shadow-sm">
  <div class="card-header with-border">
    <h6 class="m-0 font-weight-bold text-primary">New {{ $fulfilment_module_name ?? "Fulfilment" }}</h6>

    <!-- <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div> -->
  </div>
  <!-- /.box-header -->
  <div class="card-body">

    <form action="{{ route('save_sales_order') }}" method="post" enctype="multipart/form-data" onsubmit="return validateForm();">
      {{csrf_field()}}
      <input type="hidden" 
              name="order_id" 
              value="">
      <input type="hidden" 
              objName="order" 
              data-objProp="id" 
              name="order[id]" 
              value="">
      <div class="row">
        <div class="col">
          <div class="form-group">
            <label class="control-label">Sales Order Number</label>
            <select class="select2 form-control-sm form-control" 
                    name="so_id" 
                    onchange="getOrderDetails(this)">
              <option value="">Select Sales Order</option>
              @foreach($orders as $order)
                <option value="{{ $order->id }}">{{ $order->sales_order_no }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label class="control-label">Order Date</label>
            <input type="text" 
                    objName="order" 
                    data-objProp="order_date" 
                    name="order[order_date]" 
                    class="form-control form-control-sm datepicker" 
                    value="" 
                    id="order_date" 
                    disabled />
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label class="control-label">{{ $customer_module_alias ?? "Distributor" }}</label>
            <input type="text" 
                    objName="order" 
                    data-objProp="customer_name" 
                    name="order[customer_name]" 
                    value="" 
                    id="customer_name" 
                    class="form-control-sm form-control" disabled />
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label class="control-label">Reference Number</label>
            <input type="text" 
                    objName="order" 
                    data-objProp="ref_no" 
                    name="order[ref_no]" 
                    id="order_ref_no" 
                    value="" 
                    class="form-control form-control-sm" 
                    disabled/>
          </div>
        </div>
      </div>
      <textarea name="order[memo]" 
                  id="order_memo" 
                  objName="order" 
                  data-objProp="memo" 
                  class="form-control" 
                  placeholder="Memo"></textarea> 
      <div class="row">
        <table class="table table-striped table-bordered table-hover table-sm">
          <colgroup>
            <col width="15%">
            <col>
            <col>
            <col>
            <col>
            <col width="15%">
            <col>
          </colgroup>
          <thead>
            <tr>
              <th>Item Name</th>
              <th>Purhcasing Cost</th>
              <th>Selling Price</th>
              <th>Discount (%)</th>
              <th>Discount Amount</th>
              <th>Item Rate</th>
              <th>Tax(%)</th>
              <th>Qty. on Hand</th>
              <th>Quantity</th>
              <th>Item Total</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="item-list">
            
          </tbody>
        </table>
      </div>
      <div class="text-center">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
</div>
</div>
</div>

<style type="text/css">
  #add_item_tr .form-group {
    margin-bottom: 0; 
  }

</style>

<script src="{{asset('custom-libraries/select2/dist/js/select2.full.min.js')}}"></script>

<script type="text/javascript">
  var menu_id = "new_fulfilment";
  $(function(){
    $('.select2').select2();
    $('.datepicker').datepicker({
      format: 'yyyy-mm-dd'
    });
  });

  async function getOrderDetails(thisSelect) {
    let orderId = $(thisSelect).val();
    $.get('{{ route("get_order_detail_for_fulfilment") }}/' + orderId, (data) => {
      // console.log(data);
      updateOrder(data);
      renderItems(data.items);
    });
  }

  function renderItems(items) {
    
  }

  function updateOrder(data) {
    if(!updateOrder.Order) {
      updateOrder.Order = {};
    }
    Object.assign(updateOrder.Order, data);

    for(let e of document.querySelectorAll('[objName=order]')) {
      if(updateOrder.Order.hasOwnProperty(e.dataset.objprop)) {
        e.value = updateOrder.Order[e.dataset.objprop];
      }
    }
  }
  
  function validateForm() {
    if($('input[name=name]').val().trim() !== '')
      return true;
    else {
      $('input[name=name]').addClass('is-invalid');
      return false;
    }

  }
</script>
<!-- /.container-fluid -->
@endsection