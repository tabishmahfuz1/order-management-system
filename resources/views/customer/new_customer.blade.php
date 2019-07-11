@extends('layouts.app')
@section('content')
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
    <h6 class="m-0 font-weight-bold text-primary">New {{ $module_name ?? "Distributor" }}</h6>

    <!-- <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div> -->
  </div>
  <!-- /.box-header -->
  <div class="card-body">

    <form action="{{ route('save_customer') }}" method="post" enctype="multipart/form-data" onsubmit="return validateForm();">
      {{csrf_field()}}
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Name</label>
            <input class="form-control form-control-sm" name="name" placeholder="Name"></input>
          </div>
          <div class="form-group">
            <label class="control-label">Address</label>
            <input class="form-control form-control-sm" type="text" name="address" placeholder="Address" ></input>
          </div>
          <div class="form-group">
            <label class="control-label">City</label>
            <input class="form-control form-control-sm" name="city" placeholder="City"></input>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Status</label>
            <select name="status" class="form-control form-control-sm">
              <option value="1">Active</option>
              <option value="0">Disabled</option>
            </select>
          </div>
          <div class="form-group">
            <label class="control-label">Discount (%)</label>
            <input class="form-control form-control-sm" name="discount" type="number" step=".01" placeholder="Discount (%)"></input>
          </div>
          
          <div class="form-group">
            <label class="control-label">State</label>
            <select name="state" class="select2 form-control form-control-sm">
              <option value="">Select State</option>
              @foreach($states as $state)
                <option value="{{ $state->id }}">{{ $state->name }}</option>
              @endforeach
            </select>
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
<script src="{{asset('custom-libraries/select2/dist/js/select2.full.min.js')}}"></script>

<script type="text/javascript">
  var menu_id = "new_customer";
  $(function(){
    $('.select2').select2();
  });

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