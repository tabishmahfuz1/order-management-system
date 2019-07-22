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
    <h6 class="m-0 font-weight-bold text-primary">New {{ $fulfilment_module_name ?? "Invoice" }}</h6>

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
              objName="fulfilment" 
              data-objProp="id" 
              name="fulfilment[id]" 
              value="">
      <div class="row">
        <div class="col">
          <div class="form-group">
            <label class="control-label">Sales Order Number</label>
            <select class="select2 form-control-sm form-control" 
                    name="invoice[so_id]" 
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
      <div class="row mb-2">
        <textarea name="order[memo]" 
                id="order_memo" 
                objName="order" 
                data-objProp="memo" 
                class="form-control" 
                placeholder="Memo" disabled=""></textarea> 
      </div>
      <div class="row border border-secondar rounded-top">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a role="tab" class="nav-link active" aria-controls="fulfilments_tab" data-toggle="tab" href="#fulfilments_tab">Fulfilments</a>
          </li>
          <li class="nav-item">
            <a role="tab" class="nav-link" data-toggle="tab" href="#other_cost_tab">Freight &amp; Other Costs</a>
          </li>
          <li class="nav-item">
            <a role="tab" class="nav-link" data-toggle="tab" href="#preview_invoice_tab">Preview Invoice</a>
          </li>
        </ul>
        <div class="container-fluid border">
          <div class="tab-content mt-1"> 
            <div id="fulfilments_tab" class="tab-pane fade show active" role="tabpanel">
              @include('invoice.fulfilment_lines')
            </div>
            <div id="other_cost_tab" class="tab-pane" role="tabpanel">
              <table class="table table-sm">
                <tr>
                  <th></th>
                  <th>Sales Order Total</th>
                  <th>Already Charged</th>
                  <th>Charge in this Invoice</th>
                </tr>
                <tr>
                  <td>Freight</td>
                  <td>
                    <input type="number" 
                            step=".01" 
                            objName="order" 
                            data-objProp="freight" 
                            class="form-control-sm form-control" 
                            name="order[freight]" 
                            readonly="" />
                  </td>
                  <td>
                    <input type="number" 
                            step=".01" 
                            class="form-control-sm form-control" 
                            objName="order"
                            data-objProp="claimed_freight"
                            name="order[claimed_freight]" 
                            readonly="" />
                  </td>
                  <td>
                    <input type="number" 
                            step=".01" 
                            class="form-control-sm form-control" 
                            name="invoice[freight]" 
                            onchange="validateClaimedOtherCosts()" />
                  </td>
                </tr>
                <tr>
                  <td>Other Costs</td>
                  <td>
                    <input type="number" 
                            step=".01" 
                            class="form-control-sm form-control"
                            objName="order"
                            data-objProp="other_costs"
                            name="order[other_costs]" 
                            readonly="">
                  </td>
                  <td>
                    <input type="number" 
                            step=".01" 
                            class="form-control-sm form-control"
                            objName="order"
                            data-objProp="claimed_other_costs"
                            name="order[claimed_other_costs]" 
                            readonly="">
                  </td>
                  <td>
                    <input type="number" 
                            step=".01" 
                            class="form-control-sm form-control"
                            name="invoice[other_costs]" 
                            onchange="validateClaimedOtherCosts()" />
                  </td>
                </tr>
              </table>
            </div>
            <div id="preview_invoice_tab" class="tab-pane" role="tabpanel">
              
            </div>
          </div>
        </div>
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
  var menu_id = "new_invoice";
  $(function(){
    $('.select2').select2();
    $('#fulfilment_date').datepicker({
      format: 'yyyy-mm-dd',
    }).datepicker('setDate', 'today');
    @isset($order_id)
      $('#sales_order_select').val('{{ $order_id }}');
      $('#sales_order_select').change();
    @endisset
  });

  async function getOrderDetails(thisSelect) {
    let orderId = $(thisSelect).val();
    $.get('{{ route("get_order_detail_for_invoice") }}/' + orderId, (data) => {
      // console.log(data);
      updateOrder(data);
      renderFulfilments(data.Fulfilments);
    });
  }

  function renderFulfilments(fulfilments) {
    let fulfilmentsHtml = fulfilments.reduce((acc, fulfilment) => {
      return acc += createFulfilmentRow(fulfilment);
    },``);

    $('#fulfilments-list').html(fulfilmentsHtml);
  }

  function createFulfilmentRow(fulfilment) {
    return `@include('invoice.fulfilment_row', 
                ["fulfilment" => 
                  [
                    'id' => '${fulfilment.id}', 
                    'fulfilment_no' => '${fulfilment.fulfilment_no}', 
                    'fulfilment_date' => '${fulfilment.fulfilment_date}',  
                    'fulfilment_amt' => '${fulfilment.fulfilment_amt}'
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

  async function getItems(thisBtn) {
    let $thisTr = $(thisBtn).closest('tr'),
        thisFulfilmentId = $thisTr.data('fulfilment_id');
    if($(thisBtn).find('i').hasClass('fa-minus')) {
      $(thisBtn).find('i').toggleClass('fa-plus fa-minus');
    }
    $('#fulfilments-list tr[items_for]').hide();
    if($(thisBtn).find('i').hasClass('fa-minus')) {
      $(thisBtn).find('i').toggleClass('fa-plus fa-minus');
    }
    if($('#fulfilments-list tr[items_for='+ thisFulfilmentId +']').length > 0){
      $('#fulfilments-list tr[items_for='+ thisFulfilmentId +']').show();
      $(thisBtn).find('i').toggleClass('fa-plus fa-minus');
      return true;
    }
    try{
      let items = await $.get('{{ route("get_fulfilment_items") }}/' + thisFulfilmentId);
      $thisTr.after(`<tr items_for="${thisFulfilmentId}"><td colspan="4">${reduceItemsToTable(items)}</td></tr>`);
      $('#fulfilments-list tr[items_for='+ thisFulfilmentId +']').show();
      $(thisBtn).find('i').toggleClass('fa-plus fa-minus');
    } catch(err) {
      console.error('Couldn\'t fetch items', err);
    }
    
  }

  function reduceItemsToTable(items) {
    // console.log(items);
    let header= `<tr><th>Item Name</th><th>Selling Price</th><th>Discount</th>
                    <th>Item Rate</th><th>Fulfilment Qty.</th><th>Item Total</th></tr>`,
        itemsHtml = items.reduce((acc, item) => {
                      return acc += createFulfilledItemRow(item);
                    },``);
    return `<table class="col-md-10 m-auto">${header}${itemsHtml}</table>`;
  }

  function createFulfilledItemRow(item) {
    return `<tr>
      <td>${item.item_name}</td>
      <td class="text-right">${item.item_price}</td>
      <td class="text-right">${item.item_disc_amt}</td>
      <td class="text-right">${item.item_rate}</td>
      <td class="text-center">${item.fulfilment_qty}</td>
      <td class="text-right">${(parseFloat(item.item_rate) * parseInt(item.fulfilment_qty)).toFixed(2)}</td>
    </tr>`;
  }

  function validateClaimedOtherCosts() {
    return true;
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