@extends('layouts.app')

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<!-- <h4 class="h3 mb-2 text-gray-800">{{ $customer_module_name ?? "Distributors" }}</h4> -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Manage {{ $customer_module_name ?? "Distributors" }}</h6>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <form method="GET" action="{{ route('view_customers') }}" autocomplete="off">
        <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Name</th>
              <th>Address</th>
              <th>City</th>
              <th>State</th>
              <th width="15%">Discount (%)</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <td>
                <input type="text" 
                        name="name" 
                        placeholder="Name" 
                        class="form-control form-control-sm" />
              </td>
              <td>
                <input type="text" 
                        name="address" 
                        placeholder="Address" 
                        class="form-control form-control-sm" />
              </td>
              <td>
                <input type="text" 
                        name="city" 
                        placeholder="City" 
                        class="form-control form-control-sm" />
              </td>
              <td>
                <input type="text" 
                        name="state" 
                        placeholder="State" 
                        class="form-control form-control-sm" />
              </td>
              <td>
                @include('components.comparison_filter_input', 
                            [ 'name' => 'discount', 
                              'placeholder' => 'Discount',
                              'input_class' => 'text-right',
                              'type' => 'number',
                              'step' => '.01'])
              </td>
              <td>
                <select name="status"
                        class="form-control-sm form-control">
                  <option value="">Select Status</option>
                  <option value="1">Active</option>
                  <option value="0">Disabled</option>
                </select>
              </td>
              <td>
                <button class="btn btn-sm btn-primary"><i class="fa fa-search fa-sm"></i></button>
              </td>
            </tr>
          </tfoot>
          <tbody>
            @if(!empty($customers))
              @foreach($customers as $customer)
                <tr>
                  <td>{{ $customer->name }}</td>
                  <td>{{ $customer->address }}</td>
                  <td>{{ $customer->city }}</td>
                  <td>{{ $customer->state }}</td>
                  <td class="text-right">{{ $customer->discount }}</td>
                  <td class="text-center">{{ $customer->status == 1 ? "Active" : "Disabled" }}</td>
                  <td class="text-center"><a href="{{ route('edit_customer', [$customer->id]) }}"><i class="fa fa-edit"></i></a></td>
                </tr>
              @endforeach
            @endif
          </tbody>
        </table>
      </form>
    </div>
  </div>
</div>

</div>
<!-- /.container-fluid -->
@include('plugins.datatables')
<!-- Page level custom scripts -->
<script src="{{ asset('js/demo/datatables-demo.js') }}"></script>

<script type="text/javascript">
  var menu_id = "view_customers";
  let getVars = {!! json_encode($_GET) !!};
  if(getVars) {
    for (let i in getVars) {
      $('[name=' + i + ']').val(getVars[i]);
    }
  }
</script>
@endsection