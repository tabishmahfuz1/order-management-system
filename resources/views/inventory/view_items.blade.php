@extends('layouts.app')

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<h4 class="h3 mb-2 text-gray-800">Items</h4>
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Manage Items</h6>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th>Item Name</th>
            <th>Item Type</th>
            <th>Purchasing Cost</th>
            <th>Selling Price</th>
            <th>Stock Balance</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th>Item Name</th>
            <th>Item Type</th>
            <th>Purchasing Cost</th>
            <th>Selling Price</th>
            <th>Stock Balance</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </tfoot>
        <tbody>
          @if(!empty($items))
            @foreach($items as $item)
              <tr>
                <td>{{ $item->item_name }}</td>
                <td></td>
                <td class="text-right">{{ $item->item_cost }}</td>
                <td class="text-right">{{ $item->item_price }}</td>
              <td class="text-right">{{ $item->qty_on_hand }}</td>
                <td class="text-center">{{ $item->status == 1 ? "Active" : "Disabled" }}</td>
                <td class="text-center"><a href="{{ route('edit_item', [$item->id]) }}"><i class="fa fa-edit"></i></a></td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>
  </div>
</div>

</div>
<!-- /.container-fluid -->
@include('plugins.datatables')
<!-- Page level custom scripts -->
<script src="{{ asset('js/demo/datatables-demo.js') }}"></script>

<script type="text/javascript">
  var menu_id = "view_items";
</script>
@endsection