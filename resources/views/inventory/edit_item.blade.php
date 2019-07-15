@extends('layouts.app')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <!-- <h1 class="h3 mb-0 text-gray-800">New Item</h1> -->
    <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
  </div>
  <div class="card shadow-sm">
  <div class="card-header with-border">
    <h6 class="m-0 font-weight-bold text-primary">Manage Item</h6>
    <div class="text-center info_msg"></div>
  </div>
  <!-- /.box-header -->
  <div class="card-body">

    <form action="{{ route('save_item') }}" method="post" enctype="multipart/form-data" onsubmit="return validateForm();">
      {{csrf_field()}}
      <input type="hidden" name="item_id" value="{{ $item->id }}">
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Item Name</label>
            <input class="form-control form-control-sm" name="item_name" placeholder="Item Name" value="{{ $item->item_name }}"></input>
          </div>

          <div class="form-group">
            <label class="control-label">Purchasing Cost</label>
            <input class="form-control form-control-sm" name="item_cost" placeholder="Purchasing Cost" value="{{ $item->item_cost }}"></input>
          </div>

          <div class="form-group">
            <label class="control-label">Item Type</label>
            <input class="form-control form-control-sm" name="item_type" placeholder="Item Type"></input>
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
            <input class="form-control form-control-sm" type="number" name="item_price" placeholder="Selling Price" value="{{ $item->item_price }}" step=".01"></input>
          </div>
          
          <div class="form-group">
            <label class="control-label">Quantity on Hand</label>
            <input type="number" class="form-control form-control-sm" name="qty_on_hand" placeholder="0" value="{{ $item->qty_on_hand }}"></input>
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
</div>
</div>
<script type="text/javascript">
  var menu_id = "view_items";
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
</script>
<!-- /.container-fluid -->
@endsection