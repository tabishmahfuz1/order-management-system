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
    <h6 class="m-0 font-weight-bold text-primary">New {{ $module_name ?? "Sales Order" }}</h6>

    <!-- <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div> -->
  </div>
  <!-- /.box-header -->
  <div class="card-body">

    <form action="{{ route('save_sales_order') }}" method="post" enctype="multipart/form-data" onsubmit="return validateForm();">
      {{csrf_field()}}
      <div class="row">
        <div class="col">
          <div class="form-group">
            <label class="control-label">Sales Order Number</label>
            <input type="text" name="order[sales_order_no]" class="form-control form-control-sm" placeholder="(Auto Generated)" disabled />
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label class="control-label">Order Date</label>
            <input type="text" required name="order[order_date]" class="datepicker form-control form-control-sm" value="{{ date('Y-m-d') }}" />
          </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label class="control-label">{{ $customer_module_alias ?? "Distributor" }}</label>
            <select name="order[customer_id]" required class="select2 form-control form-control-sm">
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
            <input type="text" name="order[ref_no]" class="form-control form-control-sm" />
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
          <tbody>
              <tr id="add_item_tr">
                <td>
                  <div class="form-group">
                    <select class="select2 form-control form-control-sm" name="item_id" id="new_item">
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
                    <input type="number" name="new_item_cost" step=".01" id="new_item_cost" readonly class="form-control"/>
                    <span class="form-text"></span>
                  </div>
                </td>
                <td>
                  <div class="form-group">
                    <input type="number" name="new_item_price" step=".01" id="new_item_price" readonly class="form-control calculation_input"/>
                    <span class="form-text"></span>
                  </div>
                </td>
                <td><input type="number" name="new_item_disc_per" step=".01" id="new_item_discount" class="form-control calculation_input"/></td>
                <td><input type="number" name="new_item_disc_amt" step=".01" id="new_item_discount_amount" readonly class="form-control calculation_input"/></td>
                <td><input type="number" name="new_item_rate" step=".01" id="new_item_rate" class="form-control calculation_input"/></td>
                <td><input type="number" name="new_item_tax_rate" step=".01" id="new_item_tax_rate" class="form-control calculation_input"/></td>
                <td><input type="number" name="new_qty_on_hand" id="new_item_qty_on_hand" readonly class="form-control"/></td>
                <td><input type="number" name="new_item_qty" id="new_item_quantity" class="form-control calculation_input"/></td>
                <td><input type="number" name="new_item_total" step=".01" id="new_item_total" readonly class="form-control"/></td>
                <td><button type="button" class="btn btn-sm btn-primary" onclick="AddItem()">Add</button></td>
              </tr>
          </tbody>
          <tbody id="item-list">
            
          </tbody>
          <tfoot>
            <tr>
              <td colspan="8" rowspan="6">
                <textarea name="order[memo]" class="form-control" placeholder="Memo"></textarea> 
              </td>
              <th>Sub Total</th>
              <td><input type="text" name="order[sub_total]" id="sub_total" readonly class="form-control"/></td>
              <td></td>
            </tr>
            <tr>
              <th>Discount <input type="text" name="order[discount_percent]" id="discount_percent" class="form-control form-control-sm" onchange="calculateTotals()"/></th>
              <td><input type="text" name="order[discount_amount]" id="discount_amount" readonly class="form-control" onchange="calculateTotals()"/></td>
              <td></td>
            </tr>
            <tr>
              <th>Freight</th>
              <td>
                <input type="number" step="0.01" name="order[freight]" id="freight" class="form-control" onchange="calculateTotals()" />
              </td>
            </tr>
            <tr>
              <th>Other Cost</th>
              <td>
                <input type="number" step="0.01" name="order[other_costs]" id="other_costs" class="form-control" onchange="calculateTotals()"/>
              </td>
            </tr>
            <tr>
              <th>Tax Amount</th>
              <td>
                <input type="number" step="0.01" name="order[tax_amount]" id="tax_total" class="form-control" onchange="calculateTotals()"/>
              </td>
            </tr>
            <tr>
              <th>Order Total</th>
              <td>
                <input type="number" step="0.01" name="order[order_total]" id="order_total" class="form-control" readonly />
              </td>
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

<script src="{{ asset('custom-libraries/select2/dist/js/select2.full.min.js') }}"></script>

<script type="text/javascript">
  var menu_id = "new_sales_order";
  $(function(){
    $('.select2').select2();
    $('.datepicker').datepicker({
      format: 'yyyy-mm-dd'
    });

    $('#new_item').change(async function(){
      let item_id     = $(this).val();
      if(!item_id)
        return false;
      let item        = await getItemById(item_id);
      if(!item)
        return false;
      $('#new_item_cost').val(item.item_cost);
      $('#new_item_price').val(item.item_price);
      $('#new_item_rate').val(item.item_price);
      $('#new_item_qty_on_hand').val(item.qty_on_hand);
      $('#new_item_quantity').val(0);
      $('#new_item_discount').val(0);
      $('#new_item_discount_amount').val(0);
      
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
    let row = `@include('order.so_item_row', [
                  'item' => [
                    'id'        => '${row_num}',
                    'item_name' => '${item.item_name}',
                    'item_id' => '${item.item_id}',
                    'item_cost' => '${item.item_cost}',
                    'item_price' => '${item.item_price}',
                    'item_disc_per' => '${item.item_disc_per}',
                    'item_disc_amt' => '${item.item_disc_amt}',
                    'item_rate' => '${item.item_rate}',
                    'tax_rate' => '${item.item_tax_rate}',
                    'item_qty_on_hand' => '${item.qty_on_hand}',
                    'item_qty' => '${item.item_qty}',
                    'item_total' => '${item.item_total}',
                  ]
                ])`;
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
    // body...
  }

  function calculateNewItemTotal(input) {
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

  }

  function calculateTotals(){
    let $sub_total = $('#sub_total'), sub_total = 0, taxTotal = 0,
        $taxTotal = $('#tax_total')
    $('.item_total_input').each(function(){
      let thisTotal = parseFloat($(this).val());
      sub_total += thisTotal;
      let taxRate = parseFloat($(this).closest('tr').find('.tax_rate_input').val() || 0);
      taxTotal += (thisTotal * taxRate * 0.01);
    });
    $taxTotal.val(taxTotal);
    $sub_total.val(sub_total);
    let freight     = parseFloat(($('#freight').val() || 0));
    let other_costs  = parseFloat(($('#other_costs').val() || 0));

    $('#order_total').val(freight + other_costs + sub_total + taxTotal);
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
    if($('#item-list tr').length == 0) {
      showDangerMsg("Please add atleast one Item");
      return false;
    }
  }
</script>
<!-- /.container-fluid -->
@endsection