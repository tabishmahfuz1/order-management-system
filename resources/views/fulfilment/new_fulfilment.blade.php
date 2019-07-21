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

    <form action="{{ route('save_fulfilment') }}" method="post" enctype="multipart/form-data" onsubmit="return validateForm();">
      {{csrf_field()}}
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
                    name="fulfilment[so_id]" 
                    id="sales_order_select"
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
      <div class="row"> 
        <div class="col">
          <div class="form-group">
            <label class="control-label">Fulfilment Number</label>
            <input type="text" 
                    name="fulfilment_no" 
                    class="form-control-sm form-control" 
                    value="(Auto Generated)" 
                    disabled />
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label class="control-label">Fulfilment Date</label>
            <input type="text" 
                    name="fulfilment[fulfilment_date]" 
                    id="fulfilment_date" 
                    class="form-control-sm form-control datepicker" 
                    value="" />
          </div>
        </div>
        <div class="col"> </div>
        <div class="col"> </div>
      </div>  
      <div class="row">
        <div class="col-md-8">  
            @include('fulfilment.item_detail')
        </div>

        <div class="col-md-4">
          <textarea name="order[memo]" 
                id="order_memo" 
                objName="order" 
                data-objProp="memo" 
                class="form-control" 
                placeholder="Memo" disabled=""></textarea> 
        </div>
      </div>

      <div class="row">
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
    $('#fulfilment_date').datepicker({
      format: 'yyyy-mm-dd',
    }).datepicker('setDate', 'today');
    @if(!empty($order_id))
      $('#sales_order_select').val('{{ $order_id }}');
      $('#sales_order_select').change();
    @endif
  });

  async function getOrderDetails(thisSelect) {
    let orderId = $(thisSelect).val();
    $.get('{{ route("get_order_detail_for_fulfilment") }}/' + orderId, (data) => {
      // console.log(data);
      updateOrder(data);
      renderItems(data.Items);
    });
  }

  function renderItems(items) {
    let itemsHtml = items.reduce((acc, item) => {
      return acc += createItemRow(item);
    },``);

    $('#item-list').html(itemsHtml);
  }

  function createItemRow(item) {
    item.recommended_fulfilment_qty = Math.min(item.qty_on_hand, item.balance_qty);
    return `@include('fulfilment.item_row', 
                ["item" => 
                  [
                    'so_item_id' => '${item.id}', 
                    'item_name' => '${item.item_name}',  
                    'item_rate' => '${item.item_rate}', 
                    'item_qty' => '${item.item_qty}', 
                    'qty_on_hand' => '${item.qty_on_hand}', 
                    'balance_qty' => '${item.balance_qty}',
                    'fulfilment_qty' => '${item.recommended_fulfilment_qty}'
                  ]
                ])`;
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
  
  function validateFulfilQty(thisInput) {
    let $thisTr = $(thisInput).closest('tr'),
        bal_qty = parseInt($thisTr.find('.balance_qty_input').val()),
        order_qty = parseInt($thisTr.find('.item_qty_input').val()),
        in_stock = parseInt($thisTr.find('.qty_on_hand_input').val()),
        fulfil_qty= parseInt($(thisInput).val());
    if(bal_qty < fulfil_qty) {
      // console.log(bal_qty, fulfil_qty);
      $(thisInput).addClass('is-invalid');
      showDangerMsg("Fulfilment Quantity can't be greater than Balance Quantity");
      return false;
    } else if(in_stock < fulfil_qty){
      // console.log(in_stock, fulfil_qty);
      $(thisInput).addClass('is-invalid');
      showDangerMsg("Insufficien Quantity in Stock");
      return false;
    }else {
      $(thisInput).removeClass('is-invalid');
      return true;
    }
  }

  function validateForm() {
    if(!$('input#fulfilment_date').val().trim()){
      $('input#fulfilment_date').addClass('is-invalid');
      return false;
    }
    let validQtys = true;
    $('.fulfil_qty_input').each(function(){
      if(!validateFulfilQty(this)){
        validQtys = false;
        return false;
      }
    });
    return validQtys;
  }

  function SubmitForm() {
    let so_id = updateOrder.Order.id,
        data = {
          so_id
        }; 
    data['_token'] = '{{ Session::token() }}';
    data['items']  = [];
    $('.fulfil_qty_input').each(function(){ data['items'][$(this).data('so_item_id')] = $(this).val(); });
    console.table(data)
  }
</script>
<!-- /.container-fluid -->
@endsection