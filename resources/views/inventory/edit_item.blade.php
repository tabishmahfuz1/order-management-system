@extends('layouts.app')
@section('content')
@include('plugins.datepicker')
<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="card shadow-sm">
  <div class="card-header with-border">
    <h6 class="m-0 font-weight-bold text-primary">Edit Item - {{ $item->item_name }}</h6>
    <div class="text-center info_msg"></div>
  </div>
  <!-- /.box-header -->
  <div class="card-body p-1">
    <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a role="tab" 
                class="nav-link active" 
                aria-controls="fulfilments_tab" 
                data-toggle="tab" 
                href="#general_details">General Details</a>
          </li>
          <li class="nav-item">
            <a role="tab" 
                class="nav-link" 
                data-toggle="tab" 
                href="#stock_details">Stock Details</a>
          </li>
          <li class="nav-item">
            <a role="tab" 
                class="nav-link" 
                data-toggle="tab" 
                href="#movement_details">Movement</a>
          </li>
        </ul>
        <div class="container-fluid border">
          <div class="tab-content mt-1" style="min-height: 60vh;"> 
            <div id="general_details" 
                  class="tab-pane fade show active" 
                  role="tabpanel">
                    <form action="{{ route('save_item') }}" method="post" enctype="multipart/form-data" onsubmit="return validateForm();">
                      {{csrf_field()}}
                      <input type="hidden" name="item_id" value="{{ $item->id }}">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="control-label">Item Name</label>
                            <input class="form-control form-control-sm" 
                                    name="item_name" 
                                    placeholder="Item Name" 
                                    value="{{ $item->item_name }}" />
                          </div>

                          <div class="form-group">
                            <label class="control-label">Purchasing Cost</label>
                            <input class="form-control form-control-sm" 
                                    name="item_cost" 
                                    placeholder="Purchasing Cost" 
                                    value="{{ $item->item_cost }}" />
                          </div>

                          <div class="form-group">
                            <label class="control-label">Item Type</label>
                            <input class="form-control form-control-sm" 
                                    name="item_type" 
                                    placeholder="Item Type" />
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="control-label">Item Status</label>
                            <select name="status" class="form-control form-control-sm">
                              <option value="1">Active</option>
                              <option value="0">Disabled</option>
                            </select>
                          </div>
                          
                          <div class="form-group">
                            <label class="control-label">Selling Price</label>
                            <input class="form-control form-control-sm" 
                                    type="number" 
                                    name="item_price" 
                                    placeholder="Selling Price" 
                                    value="{{ $item->item_price }}" 
                                    step=".01" />
                          </div>
                          
                          <div class="form-group">
                            <label class="control-label">Quantity on Hand</label>
                            <input type="number" 
                                    class="form-control form-control-sm" 
                                    name="qty_on_hand" 
                                    placeholder="0" 
                                    value="{{ $item->qty_on_hand }}" />
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="text-center">
                            <img id="item_img" src="http://images1-1.gamewise.co/pots-102166-full.jpeg"></img>
                          </div>
                          <div class="text-center">
                            <button type="button" class="btn-success btn-sm">Upload image</button>
                          </div>
                        </div>
                      </div>
                        
                        <div class="text-center">
                          <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                      </form>
            </div>
            <div id="stock_details" 
                  class="tab-pane" 
                  role="tabpanel">
              <table class="m-auto table table-bordered table-striped table-sm w-75">
                <thead>
                  <tr>
                    <th style="width: 20%;">Type</th>
                    <th style="width: 20%;">Date</th>
                    <th class="text-center">Quantity</th>
                    <th style="width: 40%;">Remarks</th>
                    <th style="width: 10%;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <select class="select form-control-sm form-control"
                              name="stock_entry_type"
                              id="stock_entry_type"
                              required>
                        <option value="">Select a type</option>
                        <option value="{{ \App\ItemStockDetail::RECEIVING }}">Receiving</option>
                        <option value="{{ \App\ItemStockDetail::ADJUSTMENT }}">Adjustment</option>
                      </select>
                    </td>
                    <td>
                      <input type="text" 
                              name="entry_date" 
                              id="entry_date"
                              class="form-control form-control-sm datepicker" />
                    </td>
                    <td>
                      <input type="number" 
                              name="entry_quantity"
                              id="entry_quantity"
                              class="form-control form-control-sm" />
                    </td>
                    <td>
                      <input type="text" 
                              name="entry_remarks"
                              id="entry_remarks"
                              class="form-control form-control-sm" />
                    </td>
                    <td class="text-center">
                      <button type="button" 
                              class="btn btn-sm btn-success"
                              onclick="AddStockDetail(this)">
                        <i class="fa fa-plus fa-sm"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
                <tbody id="stock_details_body">
                  
                </tbody>
              </table>
            </div>
            <div id="movement_details" class="tab-pane" role="tabpanel">
              
            </div>
          </div>
</div>
</div>
</div>
<script type="text/javascript">
  var menu_id = "view_items",
      Item    = {!! json_encode($item) !!};

  $(function(){
    $('.datepicker').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true
    });
  });

  $('select[name=status]').val('{{ $item->status }}');
  function validateForm() {
    let flag = true;
    if($('input[name=item_name]').val().trim() == ''){
      $('input[name=item_name]').addClass('is-invalid');
      flag = false;
    }

    if(!$('input[name=item_cost]').val().trim()){
      $('input[name=item_cost]').addClass('is-invalid');
      flag = false;
    }

    if(!$('input[name=item_price]').val().trim()){
      $('input[name=item_price]').addClass('is-invalid');
      flag = false;
    }
    return flag;

  }
  @if(Session::has('success'))
    $('.info_msg').prev('h6').hide();
    $('.info_msg').parent('div').addClass('bg-gradient-success text-white');
    $('.info_msg').html('<b>{{ Session::get('success') }}</b>');
    setTimeout(function(){
      // $callout.remove();
      $('.info_msg').fadeTo(500, 0.01, function(){ 
          $('.info_msg').prev('h6').slideDown(150);
          $(this).slideUp(150, function() {
              $(this).parent('div').removeClass('bg-gradient-success text-white');

              $(this).remove(); 
          }); 
      });
    }, 2000);
  @endif

  async function AddStockDetail(addBtn) {
    let $thisTr = $(addBtn).closest('tr'),
        type    = $thisTr.find('#stock_entry_type').val(),
        date    = $thisTr.find('#entry_date').datepicker('getDate'),
        quantity= $thisTr.find('#entry_quantity').val(),
        remarks = $thisTr.find('#entry_remarks').val()
        item_id = Item.id;
        if(!type || !date || !quantity) {
          showDangerMsg("One or more of the required fields are mmissing");
          return false;
        } 

        date = date.toISOString();

        let data = {
          type,
          date,
          quantity,
          remarks,
          item_id,
        };
        data._token = '{{ Session::token() }}';
        try{
          let response = await $.post('{{ route("add_stock_detail") }}', data);
        } catch(err) {
          console.error("Error while adding new Stock detail", err);
        }
  }
</script>
<!-- /.container-fluid -->
@endsection