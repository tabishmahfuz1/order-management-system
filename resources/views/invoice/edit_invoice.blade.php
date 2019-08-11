@extends('layouts.app')
@section('content')
@include('plugins.datepicker')
<link rel="stylesheet" href="{{asset('custom-libraries/select2/dist/css/select2.min.css')}}">
<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="card shadow-sm">
  <div class="card-header with-border">
    <h6 class="m-0 font-weight-bold text-primary">Edit {{ $fulfilment_module_name ?? "Invoice" }} -
      <span objName="Invoice" data-objProp="invoice_no" data-updateProp="innerHTML"></span></h6>

    <!-- <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div> -->
  </div>
  <!-- /.box-header -->
  <div class="card-body">
    <form action="{{ $invoice->isPaid()? '#' : route('save_invoice') }}" 
          method="post" 
          enctype="multipart/form-data" 
          onsubmit="return validateForm();">
      {{csrf_field()}}
      <input type="hidden" 
              objName="Order" 
              data-objProp="id" 
              name="invoice[so_id]" 
              value="">
      <input type="hidden" 
              objName="Invoice" 
              data-objProp="id" 
              name="invoice[invoice_id]" 
              value="">
      <div class="row">
        <div class="col">
          <div class="form-group">
            <label class="control-label">Sales Order Number</label>
            <input type="text" 
                    name="invoice[so_num]"
                    objName="Order" 
                    data-objProp="sales_order_no" 
                    value="" 
                    class="form-control form-control-sm"
                    disabled="" />
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label class="control-label">Order Date</label>
            <input type="text" 
                    objName="Order" 
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
                    objName="Order" 
                    data-objProp="customer_name" 
                    name="order[customer_name]" 
                    value="" 
                    id="customer_name" 
                    class="form-control-sm form-control" disabled />
            <input type="hidden" 
                    objName="Order" 
                    data-objProp="customer_id" 
                    name="invoice[customer_id]" 
                    value="" 
                    id="customer_id" 
                    class="form-control-sm form-control" />
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label class="control-label">Reference Number</label>
            <input type="text" 
                    objName="Order" 
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
        <div class="col">
          <label class="control-label">Invoice Date</label>
          <input type="text" 
                  name="invoice[invoice_date]" 
                  class="datepicker form-control-sm form-control" 
                  value="@date(date('Y-m-d'))" />
        </div>
        <div class="col"></div>
        <div class="col-md-6">
          <label class="control-label">Remarks</label>
          <textarea name="order[memo]" 
                    id="order_memo" 
                    objName="Order" 
                    data-objProp="memo" 
                    class="form-control" 
                    placeholder="Memo" 
                    disabled=""></textarea> 
        </div>
      </div>
      <div class="row border rounded-top">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a role="tab" 
                class="nav-link active" 
                aria-controls="fulfilments_tab" 
                data-toggle="tab" 
                href="#fulfilments_tab">Fulfilments</a>
          </li>
          <li class="nav-item">
            <a role="tab" 
                class="nav-link" 
                data-toggle="tab" 
                href="#other_cost_tab">Freight &amp; Other Costs</a>
          </li>
          <li class="nav-item">
            <a role="tab" 
                class="nav-link" 
                data-toggle="tab" 
                href="#payments_tab">Payments</a>
          </li>
        </ul>
        <div class="container-fluid border">
          <div class="tab-content mt-1"> 
            <div id="fulfilments_tab" 
                  class="tab-pane fade show active" 
                  role="tabpanel">
              @include('invoice.fulfilment_lines')
            </div>
            <div id="other_cost_tab" 
                  class="tab-pane" 
                  role="tabpanel">
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
                            objName="Order" 
                            data-objProp="freight" 
                            class="form-control-sm form-control" 
                            name="order[freight]" 
                            readonly="" />
                  </td>
                  <td>
                    <input type="number" 
                            step=".01" 
                            class="form-control-sm form-control" 
                            objName="Invoice"
                            data-objProp="claimed_freight"
                            name="order[claimed_freight]" 
                            readonly="" />
                  </td>
                  <td>
                    <input type="number" 
                            step=".01" 
                            id="freight_input"
                            class="form-control-sm form-control" 
                            name="invoice[freight]" 
                            objName="Invoice" 
                            data-objProp="freight" 
                            value="" 
                            onchange="validateClaimedOtherCosts()" />
                  </td>
                </tr>
                <tr>
                  <td>Other Costs</td>
                  <td>
                    <input type="number" 
                            step=".01" 
                            class="form-control-sm form-control"
                            objName="Order"
                            data-objProp="other_costs"
                            name="order[other_costs]" 
                            readonly="">
                  </td>
                  <td>
                    <input type="number" 
                            step=".01" 
                            class="form-control-sm form-control"
                            objName="Invoice"
                            data-objProp="claimed_other_costs"
                            name="order[claimed_other_costs]" 
                            readonly="">
                  </td>
                  <td>
                    <input type="number" 
                            step=".01" 
                            id="other_cost_input"
                            class="form-control-sm form-control"
                            name="invoice[other_costs]" 
                            objName="Invoice" 
                            data-objProp="other_costs" 
                            value="" 
                            onchange="validateClaimedOtherCosts()" />
                  </td>
                </tr>
              </table>
            </div>
            <div id="payments_tab" class="tab-pane" role="tabpanel">
              <div class="col-md-6">
                @include('invoice.payment_lines', compact('invoice'))                
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="text-center">
        @if(!$invoice->isPaid())
            <button type="submit" class="btn btn-primary">Save</button>
        @endif
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
  var menu_id = "view_invoices";
  let fulfilmentSet = new Set({!! json_encode($invoice->getFulfilmentIds()) !!});
  $(function(){
    $('.select2').select2();
    $('.datepicker').datepicker({
      format: 'yyyy-mm-dd',
    });
    getOrderDetails('{{ $invoice->so_id }}');
  });

  async function getOrderDetails(orderId) {
    $.get('{{ route("get_order_detail_for_invoice") }}/' + orderId + '/' + Invoice.Invoice.id, (data) => {
      // console.log(data);
      let obj = 'Order';
      Invoice({obj, data});
      renderFulfilments(data.Fulfilments);
      @if($invoice->isPaid())
          $('#fulfilments_tab input').prop('disabled', true);
      @endif
    });
  }

  function renderFulfilments(fulfilments) {
    let fulfilmentsHtml = fulfilments.reduce((acc, fulfilment) => {
      return acc += createFulfilmentRow(fulfilment);
    },``);

    $('#fulfilments-list').html(fulfilmentsHtml);
    checkFulfilmentsInSet(fulfilmentSet);
  }

  function createFulfilmentRow(fulfilment) {
    return `@include('invoice.fulfilment_row', 
                ["fulfilment" => 
                  [
                    'id' => '${fulfilment.id}', 
                    'fulfilment_no' => '${fulfilment.fulfilment_no}', 
                    'fulfilment_date' => '${fulfilment.fulfilment_date}',  
                    'fulfilment_amt' => '${fulfilment.fulfilment_amt}',
                    'fulfilment_tax' => '${fulfilment.fulfilment_tax}',
                    'fulfilment_total' => '${fulfilment.fulfilment_total}'
                  ]
                ])`;
  }

  function checkFulfilmentsInSet(fulfilment_set) {
    for(let row of document.querySelectorAll('.invoice_fulfilment_row')) {
      // console.log(row, row.dataset.fulfilment_id)
      if(fulfilment_set.has(parseInt(row.dataset.fulfilment_id))) {
        $(row).find('.fulfilment_checkbox').prop('checked', true);
      }
    }
    calculateInvoiceTotals();
  }

  function Invoice(data) {
    if(!data) {

    } else if(!data.obj) {
      console.error("Missing [obj] property in", data);
      return false;
    } else if(!Invoice[data.obj]) {
      console.error("Invalid Object Name", data.obj);
      return false;
    }
    Object.assign(Invoice[data.obj], data.data);

    for(let e of document.querySelectorAll('[objName]')) {
      // console.log(e)
      let obj = e.attributes.objname.value,
          prop= e.dataset.objprop,
          updateProp=e.dataset.updateprop;
      if(!Invoice[obj]) {
        console.warn("Unknown Object", obj);
        continue;
      }
      if(Invoice[obj].hasOwnProperty(e.dataset.objprop)) {
        if(updateProp) {
          // console.log(e, updateProp, Invoice[obj][prop], obj, prop)
          e[updateProp] = Invoice[obj][prop];
        } else {
          e.value = Invoice[obj][prop];
        }
      }
    }
  }

  Invoice.Invoice = {!! json_encode($invoice) !!};
  Invoice.Order   = {};
  Invoice({obj: "Invoice", data: {}});


  async function getItems(thisBtn) {
    let $thisTr = $(thisBtn).closest('tr'),
        thisFulfilmentId = $thisTr.data('fulfilment_id');

    $('#fulfilments-list tr[items_for]').hide();
    $('#fulfilments-list tr').not($thisTr).find('i')
        .removeClass('fa-minus').addClass('fa-plus');
        
    if($(thisBtn).find('i').hasClass('fa-minus')) {
      $(thisBtn).find('i').toggleClass('fa-plus fa-minus');
      return true;
    }

    if($('#fulfilments-list tr[items_for='+ thisFulfilmentId +']').length > 0){
      $('#fulfilments-list tr[items_for='+ thisFulfilmentId +']').show();
      $(thisBtn).find('i').toggleClass('fa-plus fa-minus');
      return true;
    }
    try{
      let items = await $.get('{{ route("get_fulfilment_items") }}/' + thisFulfilmentId);
      $thisTr.after(`<tr items_for="${thisFulfilmentId}"><td colspan="6">${reduceItemsToTable(items)}</td></tr>`);
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
    let $freight = $('#freight_input'), freight = $otherCost.val();
    if(freight === '') {
      if(Invoice.Order.freight > Invoice.Order.claimed_freight) {
        showDangerMsg("Please enter the Freight Cost to charge in this Invoice");
        $freight.addClass('is-invalid');
        return false;
      } 
    } else if((Invoice.Order.freight - Invoice.Order.claimed_freight) < parseFloat(freight)){
      showDangerMsg("Invalid Other Cost entered");
      $freight.addClass('is-invalid').focus();
      return false;
    } else {
      $freight.removeClass('is-invalid');
    }

    let $otherCost = $('#other_cost_input'), otherCost = $otherCost.val();
    if(otherCost === '') {
      if(Invoice.Order.other_costs > Invoice.Order.claimed_other_costs) {
        showDangerMsg("Please enter the Other Cost to charge in this Invoice");
        $otherCost.addClass('is-invalid');
        return false;
      } 
    } else if((Invoice.Order.other_costs - Invoice.Order.claimed_other_costs) < parseFloat(otherCost)){
      showDangerMsg("Invalid Other Cost entered");
      $otherCost.addClass('is-invalid').focus();
      return false;
    }else {
      $otherCost.removeClass('is-invalid');
    }
    return true;
  }

  function validateForm() {
    let validQtys = true;
    if($('.fulfilment_checkbox:checked').length == 0) {
      showDangerMsg("Please select at least one Fulfilment");
      return false;
    }

    return validateClaimedOtherCosts();
  }

  function calculateInvoiceTotals() {
    let subtotal = 0,
        tax_total= 0,
        grandtotal=0;
    for(let row of document.querySelectorAll('.invoice_fulfilment_row')) {
      let $row = $(row);
      if(!$row.find('.fulfilment_checkbox').is(':checked')){
        // console.log("NOT CHECKED", row);
        continue;
      }
      // console.log("CHECKED", row);
      subtotal += parseFloat($row.find('.fulfilment_amt_td').text().replace(/,/g, '') || 0);
      tax_total += parseFloat($row.find('.fulfilment_tax_td').text().replace(/,/g, '') || 0);
      grandtotal += parseFloat($row.find('.fulfilment_total_td').text().replace(/,/g, '') || 0);
      // console.table({subtotal, tax_total, grandtotal});
    }
    $('#inv_sub_total_input').val(subtotal.toFixed(2));
    $('#inv_tax_amt_input').val(tax_total.toFixed(2));
    $('#inv_grandtotal_input').val(grandtotal.toFixed(2));
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