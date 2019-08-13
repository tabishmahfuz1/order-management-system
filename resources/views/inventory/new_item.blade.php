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
    <h6 class="m-0 font-weight-bold text-primary">New Item</h6>

    <!-- <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div> -->
  </div>
  <!-- /.box-header -->
  <div class="card-body">

    <form action="{{ route('save_item') }}" 
          method="post" 
          enctype="multipart/form-data" 
          onsubmit="return validateForm();"
          autocomplete="off">
      {{csrf_field()}}
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Item Name</label>
            <input class="form-control form-control-sm" name="item_name" placeholder="Item Name"></input>
          </div>
          <div class="form-group">
            <label class="control-label">Purhcasing Cost</label>
            <input class="form-control form-control-sm" name="item_cost" type="number" step=".01" placeholder="Purhcasing Cost"></input>
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
            <input class="form-control form-control-sm" type="number" step=".01" name="item_price" placeholder="Selling Price" ></input>
          </div>
          
          <div class="form-group">
            <label class="control-label">Quantity on Hand</label>
            <input type="number" class="form-control form-control-sm" name="qty_on_hand" placeholder="0"></input>
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
  function validateForm() {
    let flag = true;
    if($('input[name=item_name]').val().trim() == ''){
      $('input[name=item_name]').addClass('is-invalid');
      flag = false;
    } else {
      $('input[name=item_name]').removeClass('is-invalid');
    }

    if(!$('input[name=item_cost]').val().trim()){
      $('input[name=item_cost]').addClass('is-invalid');
      flag = false;
    } else {
      $('input[name=item_cost]').removeClass('is-invalid');
    }

    if(!$('input[name=item_price]').val().trim()){
      $('input[name=item_price]').addClass('is-invalid');
      flag = false;
    } else {
      $('input[name=item_price]').removeClass('is-invalid');
    }
    return flag;
  }
  var menu_id = "new_item";
</script>
<!-- /.container-fluid -->
@endsection