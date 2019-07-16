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
    <h6 class="m-0 font-weight-bold text-primary">Edit {{ $fulfilment_module_name ?? "Fulfilment" }}</h6>

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
      <input type="hidden" 
              name="fulfilment[fulfilment_id]" 
              value="{{ $fulfilment->id }}">
      <div class="row">
        <div class="col">
          <div class="form-group">
            <label class="control-label">Sales Order Number</label>
            <input type="text" 
                    name="fulfilment[so_id]"
                    class="form-control form-control-sm"
                    disabled="" 
                    value="{{ $fulfilment->Order->sales_order_no }}" />
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
                    value="{{ $fulfilment->Order->order_date }}" 
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
                    value="{{ $fulfilment->Order->CustomerName() }}" 
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
                    value="{{ $fulfilment->Order->ref_no }}" 
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
                    value="{{ $fulfilment->fulfilment_no }}" 
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
                    value="{{ $fulfilment->fulfilment_date }}" />
          </div>
        </div>
        <div class="col"> </div>
        <div class="col"> </div>
      </div>  
      <div class="row">
        <div class="col-md-8">  
            @include('fulfilment.item_detail', [ 'items' => $fulfilment->FullItemDetails() ])
        </div>

        <div class="col-md-4">
          <textarea name="order[memo]" 
                id="order_memo" 
                objName="order" 
                data-objProp="memo" 
                class="form-control" 
                placeholder="Memo" disabled="">{{ $fulfilment->Order->memo }}</textarea> 
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
    return `@include('fulfilment.item_row', 
                ["item" => 
                  [
                    'so_item_id' => '${item.id}', 
                    'item_name' => '${item.item_name}',  
                    'item_rate' => '${item.item_rate}', 
                    'item_qty' => '${item.item_qty}', 
                    'balance_qty' => '${item.balance_qty}'
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
        fulfil_qty= parseInt($(thisInput).val());
    if(bal_qty < fulfil_qty) {
      $(thisInput).addClass('is-invalid');
      showDangerMsg("Fulfilment Quantity can't be greater than Balance Quantity");
    } else {
      $(thisInput).removeClass('is-invalid');
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