@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="{{asset('custom-libraries/select2/dist/css/select2.min.css')}}">
<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="card shadow-sm">
  <div class="card-header with-border">
    <h6 class="m-0 font-weight-bold text-primary">Edit {{ $module_name ?? "Distributor" }} - <span objName="Customer" data-objprop="name" data-updateProp="innerHTML"></span></h6>

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
            <input class="form-control form-control-sm" 
                    objName="Customer" 
                    data-objprop="name" 
                    name="name" 
                    placeholder="Name" 
                    value=""></input>
            <input type="hidden" name="customer_id" objName="Customer" data-objprop="id" />
          </div>
          <div class="form-group">
            <label class="control-label">Address</label>
            <input class="form-control form-control-sm" objName="Customer" data-objprop="address" type="text" name="address" placeholder="Address" value=""></input>
          </div>
          <div class="form-group">
            <label class="control-label">City</label>
            <input class="form-control form-control-sm" objName="Customer" data-objprop="city" name="city" placeholder="City"></input>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Status</label>
            <select name="status" class="form-control form-control-sm" objName="Customer" data-objprop="status" >
              <option value="1">Active</option>
              <option value="0">Disabled</option>
            </select>
          </div>
          <div class="form-group">
            <label class="control-label">Discount (%)</label>
            <input class="form-control form-control-sm" name="discount" objName="Customer" data-objprop="discount" type="number" step=".01" placeholder="Discount (%)" value=""></input>
          </div>
          
          <div class="form-group">
            <label class="control-label">State</label>
            <select name="state" class="select2 form-control form-control-sm" objName="Customer" data-objprop="state_id">
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
  // $('select[name=state]').val('{{ $customer->state_id }}');
  var menu_id = "view_customers";


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

  function Customer(data) {
    Object.assign(Customer.data, data);

    for(let e of document.querySelectorAll('[objName=Customer]')) {
      // console.log(e)
      let obj = e.attributes.objname.value,
          prop= e.dataset.objprop,
          updateProp=e.dataset.updateprop;
      if(Customer.data.hasOwnProperty(e.dataset.objprop)) {
        if(updateProp) {
          // console.log(e, updateProp, Invoice[obj][prop], obj, prop)
          e[updateProp] = Customer.data[prop];
        } else {
          e.value = Customer.data[prop];
        }
      }
    }
  }

  Customer.data = {!! json_encode($customer) !!}
  Customer({})


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