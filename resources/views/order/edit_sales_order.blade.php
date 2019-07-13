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
    <h6 class="m-0 font-weight-bold text-primary">Edit {{ $module_name ?? "Sales Order" }}</h6>

    <!-- <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div> -->
  </div>
  <!-- /.box-header -->
  <div class="card-body">

    <form action="{{ route('save_sales_order') }}" method="post" enctype="multipart/form-data" onsubmit="return validateForm();">
      {{csrf_field()}}
      <input type="hidden" name="order_id" value="{{ $order->id }}">
      <input type="hidden" name="order[id]" value="{{ $order->id }}">
      <div class="row">
        <div class="col">
          <div class="form-group">
            <label class="control-label">Sales Order Number</label>
            <input type="text" name="order[sales_order_no]" class="form-control form-control-sm" value="{{ $order->sales_order_no }}" disabled />
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label class="control-label">Order Date</label>
            <input type="text" name="order[order_date]" class="form-control form-control-sm" value="{{ $order->order_date }}" id="order_date" disabled />
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label class="control-label">{{ $customer_module_alias ?? "Distributor" }}</label>
            <select name="order[customer_id]" id="customer-select" class="select2 form-control form-control-sm">
              <option value="">Select {{ $customer_module_alias ?? "Distributor" }}</option>
              @foreach($customers as $customer)
                <option value="{{ $customer->id }}" data-discount="{{ $customer->discount }}" >{{ $customer->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label class="control-label">Reference Number</label>
            <input type="text" name="order[ref_no]" value="{{ $order->ref_no }}" class="form-control form-control-sm" />
          </div>
        </div>
        <!-- <div class="col-md-4">
          
        </div> -->
      </div>
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
              <th>Item Cost</th>
              <th>Item Price</th>
              <th>Discount</th>
              <th>Discount Amount</th>
              <th>Item Rate</th>
              <th>Qty. on Hand</th>
              <th>Quantity</th>
              <th>Item Total</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
              <tr id="add_item_tr">
                <td>
                  <div class="form-group">
                    <select class="select2 form-control form-control-sm item_select" name="item_id" id="new_item">
                      <option value="">Select Item</option>
                      @if(!empty($items))
                        @foreach($items as $item)
                          <option value="{{ $item->id }}">{{ $item->item_name }}</option>
                        @endforeach
                      @endif
                    </select>
                    <span class="form-text"></span>
                  </div>
                </td>
                <td>
                  <div class="form-group">
                    <input type="number" name="new_item_cost" step=".01" id="new_item_cost" readonly class="form-control item_cost_input"/>
                    <span class="form-text"></span>
                  </div>
                </td>
                <td>
                  <div class="form-group">
                    <input type="number" name="new_item_price" step=".01" id="new_item_price" readonly class="form-control calculation_input item_price_input"/>
                    <span class="form-text"></span>
                  </div>
                </td>
                <td><input type="number" name="new_item_disc_per" step=".01" id="new_item_discount" class="form-control calculation_input item_disc_per_input"/></td>
                <td><input type="number" name="new_item_disc_amt" step=".01" id="new_item_discount_amount" readonly class="form-control calculation_input item_disc_amt_input"/></td>
                <td><input type="number" name="new_item_rate" step=".01" id="new_item_rate" class="form-control calculation_input item_rate_input"/></td>
                <td><input type="number" name="new_qty_on_hand" id="new_item_qty_on_hand" readonly class="form-control item_qty_on_hand_input"/></td>
                <td><input type="number" name="new_item_qty" id="new_item_quantity" class="form-control calculation_input item_qty_input"/></td>
                <td><input type="number" name="new_item_total" step=".01" id="new_item_total" readonly class="form-control item_total_input"/></td>
                <td><button type="button" class="btn btn-sm btn-primary" onclick="AddItem()">Add</button></td>
              </tr>
          </tbody>
          <tbody id="item-list">
            @each('order.so_item_row', $so_items, 'item')
          </tbody>
          <tfoot>
            <tr>
              <td colspan="7" rowspan="5">
                <textarea name="order[memo]" class="form-control" placeholder="Memo">{{ $order->memo }}</textarea> 
              </td>
              <th>Sub Total</th>
              <td><input type="text" name="order[sub_total]" id="sub_total" readonly value="{{ $order->sub_total }}" class="form-control"/></td>
              <td></td>
            </tr>
            <tr>
              <th>Discount <input type="text" name="order[discount_percent]" id="discount_percent" class="form-control form-control-sm" value="{{ $order->discount_percent }}" /></th>
              <td><input type="text" name="order[discount_amount]" id="discount_amount" value="{{ $order->discount_amount }}" readonly class="form-control"/></td>
              <td></td>
            </tr>
            <tr>
              <th>Freight</th>
              <td>
                <input type="number" step="0.01" name="order[freight]" id="freight" value="{{ $order->freight }}" class="form-control" onchange="calculateTotals()" />
              </td>
              <td></td>
            </tr>
            <tr>
              <th>Other Cost</th>
              <td>
                <input type="number" step="0.01" name="order[other_costs]" id="other_costs"value="{{ $order->other_costs }}" class="form-control" onchange="calculateTotals()" />
              </td>
              <td></td>
            </tr>
            <tr>
              <th>Order Total</th>
              <td>
                <input type="number" step="0.01" name="order[order_total]" id="order_total" class="form-control" value="{{ $order->order_total }}" readonly />
              </td>
              <td></td>
            </tr>
          </tfoot>
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
  var menu_id = "new_sales_order";
  $('#customer-select').val('{{ $order->customer_id }}');
  $(function(){
    $('.select2').select2();
    $('.datepicker').datepicker({
      format: 'yyyy-mm-dd'
    });

    $(document).on('change', '.item_select', async function(){
      let item_id     = $(this).val(),
          $thisTr     = $(this).closest('tr');
      // console.log(this, item_id)
      if(!item_id)
        return false;
      let item        = await getItemById(item_id);
      if(!item)
        return false;
      $thisTr.find('.item_cost_input').val(item.item_cost);
      $thisTr.find('.item_price_input').val(item.item_price);
      $thisTr.find('.item_rate_input').val(item.item_price);
      $thisTr.find('.item_qty_on_hand_input').val(item.qty_on_hand);
      $thisTr.find('.item_qty_input').val(0);
      $thisTr.find('.item_disc_per_input').val(0);
      $thisTr.find('.item_disc_amt_input').val(0);
      
    });

    $(document).on('change', '.calculation_input', calculateNewItemTotal)

  });


  function AddItem() {
    let inputs = document.querySelectorAll('#add_item_tr input'), new_item_data = [];
    // console.log(new_item_data);
    for(var i = 0; i < inputs.length; i++) {
      new_item_data[inputs[i].name.replace('new_', '')] = inputs[i].value;
    }
    new_item_data['item_name'] = $('#new_item option:selected').text();
    new_item_data['item_id'] = $('#new_item').val();

    if(!new_item_data['item_id']) {
      let $form_control = $('#new_item');
      $form_control.addClass('is-invalid');
      $form_control.change(function(){
        if($form_control.val())
          $form_control.removeClass('is-invalid');
      });
      return false;
    }
    // console.log(new_item_data);return false;
    if(!new_item_data['item_qty'] || new_item_data['item_qty'] == 0) {
      let $form_control = $('#new_item_quantity');
      $form_control.addClass('is-invalid');
      $form_control.change(function(){
        if($form_control.val())
          $form_control.removeClass('is-invalid');
      });
      return false;
    }

    AddItemRow(new_item_data);
    ClearNewItemRow(inputs);
  }

  function AddItemRow(item, row_num) {
    let toAdd = true;
    if(!row_num){
      row_num = ($('#item-list tr').length || 0);
    }
    else{
      toAdd = false;
    }
    let row = `<tr class="item_row" data-row-num="${row_num}">
        <td>
          <input type="text" readonly name="order[items][${row_num}][item_name]" value="${item.item_name}" readonly class="form-control">
          <input type="hidden" name="order[items][${row_num}][item_id]" value="${item.item_id}">
        </td>
        <td>
          <input type="number" name="order[items][${row_num}][item_cost]" value="${item.item_cost}" step=".01" readonly class="form-control"/>
        </td>
        <td>
          <input type="number" name="order[items][${row_num}][item_price]" value="${item.item_price}" step=".01" readonly class="form-control"/>
        </td>
        <td>
          <input type="number" name="order[items][${row_num}][item_disc_per]" value="${item.item_disc_per}" step=".01" readonly class="form-control"/>
        </td>
        <td>
          <input type="number" name="order[items][${row_num}][item_disc_amt]" value="${item.item_disc_amt}"  step=".01" readonly class="form-control"/>
        </td>
        <td>
          <input type="number" name="order[items][${row_num}][item_rate]" value="${item.item_rate}" step=".01" readonly class="form-control"/>
        </td>
        <td>
          <input type="number" name="order[items][${row_num}][qty_on_hand]" value="${item.qty_on_hand}" readonly class="form-control"/>
        </td>
        <td>
          <input type="number" name="order[items][${row_num}][item_qty]" value="${item.item_qty}" readonly class="form-control"/>
        </td>
        <td>
          <input type="number" name="order[items][${row_num}][item_total]" value="${item.item_total}" step=".01" readonly class="form-control item_total_input"/>
        </td>
        <td>
          <button type="button" class="btn btn-sm btn-primary" onclick="EditItem(this)"><i class="fa fa-edit"></i></button>
        </td>
      </tr>`;
      if(toAdd)
        $('#item-list').append(row);
      else
        $('#item-list tr[data-row-num=' + row_num + ']').replaceWith(row);
      calculateTotals();
  }

  function ClearNewItemRow(inputs) {
    for(let i = 0; i < inputs.length; i++) {
      $(inputs[i]).val('');
    }
    $('#new_item').val('')
  }

  function EditItem(thisBtn) {
    let $thisTr = $(thisBtn).closest('tr');
    $thisTr.find('.editable_input').prop('readonly', false);
    let so_item_id = $thisTr.data('so-item-id');
    let $item_select = $('#new_item').clone()
                                      .attr('id', 'item_id_' + so_item_id)
                                      .attr('class', 'item_select')
                                      .css('width', '100%');
    $thisTr.find('.item_name_input').replaceWith($item_select);
    $item_select.val($thisTr.find('.item_id_input').val())
    $('#item_id_' + so_item_id).select2();
    $(thisBtn).replaceWith(`<button onclick="SaveItem(this)" type="button" class="btn btn-success btn-sm" >Save</button>`);
    $('#item_id_' + so_item_id).select2();
  }

  async function SaveItem(thisBtn) {
    let $thisTr = $(thisBtn).closest('tr'),
        so_item_id = $thisTr.data('so-item-id'),
        item_id = $thisTr.find('#item_id_' + so_item_id).val(),
        // so_item_id= $thisTr.find('so_item_id_input').val(),
        item_cost = $thisTr.find('.item_cost_input').val(),
        item_price= $thisTr.find('.item_price_input').val(),
        item_disc_per= $thisTr.find('.item_disc_per_input').val()
        item_disc_amt= $thisTr.find('.item_disc_amt_input').val(),
        item_rate= $thisTr.find('.item_rate_input').val(),
        item_qty_on_hand= $thisTr.find('.qty_on_hand_input').val(),
        item_qty= $thisTr.find('.item_qty_input').val(),
        item_total= $thisTr.find('.item_total_input').val();

    if(!item_id) {
      let $form_control = $thisTr.find('#temp_item_id');
      $form_control.addClass('is-invalid');
      $form_control.change(function(){
        if($form_control.val())
          $form_control.removeClass('is-invalid');
      });
      return false;
    }

    if(!item_qty) {
      let $form_control = $thisTr.find('.item_qty_input');
      $form_control.addClass('is-invalid');
      $form_control.change(function(){
        if($form_control.val())
          $form_control.removeClass('is-invalid');
      });
      return false;
    }

    let item = {
      item_id,
      so_item_id,
      item_cost,
      item_price,
      item_disc_per,
      item_disc_amt,
      item_rate,
      item_qty_on_hand,
      item_qty,
      item_total
    };
    item['_token'] = '{{ Session::token() }}';
    item['so_id']  = '{{ $order->id }}';

    try{
      let response = await $.post('{{ route("add_so_item") }}', item);  
    } catch(e) {
      console.error(e);
      return false;
    }
    if(response.success)
        $thisTr.replaceWith(response.row);
    else{
      console.log(response);
    }
  }

  function calculateNewItemTotal(input) {
      console.log(input)
      let $thisTr = $(input.target).closest('tr');
      let $new_item_price       = $thisTr.find('.item_price_input'),
          $new_item_quantity    = $thisTr.find('.item_qty_input'),
          $new_item_discount    = $thisTr.find('.item_disc_per_input');

      let new_item_price = parseFloat($new_item_price.val() || 0), 
          new_item_quantity = parseFloat($new_item_quantity.val() || 0),
          new_item_discount = parseFloat($new_item_discount.val() || 0);

      let discount_amt    = new_item_price * new_item_discount * 0.01, 
          new_item_rate   = (new_item_price - discount_amt);
      let item_total =  new_item_rate * new_item_quantity;

      $thisTr.find('.item_rate_input').val(new_item_rate);
      $thisTr.find('.item_total_input').val(item_total);
      $thisTr.find('.item_disc_amt_input').val(discount_amt);

  }

  /*function calculateItemTotal(input) {
      console.log(input)
      let $thisTr = $(input.target).closest('tr');
      let $new_item_price       = $('#new_item_price'),
          $new_item_quantity    = $('#new_item_quantity'),
          $new_item_discount    = $('#new_item_discount');

      let new_item_price = parseFloat($new_item_price.val() || 0), 
          new_item_quantity = parseFloat($new_item_quantity.val() || 0),
          new_item_discount = parseFloat($new_item_discount.val() || 0);

      let discount_amt    = new_item_price * new_item_discount * 0.01, 
          new_item_rate   = (new_item_price - discount_amt);
      let item_total =  new_item_rate * new_item_quantity;

      $('#new_item_rate').val(new_item_rate);
      $('#new_item_total').val(item_total);
      $('#new_item_discount_amount').val(discount_amt);

  }*/

  function calculateTotals(){
    let $sub_total = $('#sub_total'), sub_total = 0;
    
    $('.item_total_input').each(function(){
      sub_total += parseFloat($(this).val());
    });

    $sub_total.val(sub_total);
    let freight     = parseFloat(($('#freight').val() || 0));
    let other_costs  = parseFloat(($('#other_costs').val() || 0));

    $('#order_total').val(freight + other_costs + sub_total);
  }

  async function getItemById(item_id) {
    if(!item_id)
        return false;
    try{
        let item_data   = await $.get('{{ route('get_item') }}/' + item_id);
        return item_data;
    } catch(e) {
        showDangerMsg("Error fetching Item Details. Please try again.");
        return false;
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