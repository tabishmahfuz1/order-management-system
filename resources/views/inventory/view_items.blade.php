@extends('layouts.app')

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<!-- <h4 class="h3 mb-2 text-gray-800">Items</h4> -->
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">Manage Items</h6>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<form method="GET" action="{{ route('view_items') }}" autocomplete="off">
				<table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
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
							<td>
								<input type="text" 
												name="item_name" 
												class="form-control form-control-sm"
												placeholder="Name" />
							</td>
							<td>
								
							</td>
							<td>
								<div class="input-group"> 
									<div class="input-group-append">
										<select class="rounded-left form-control-sm form-control"
														name="item_cost_comparison"> 
											<option value="lt">&lt;</option>
											<option value="le">&lt;=</option>
											<option value="ge">&gt;=</option>
											<option value="gt">&gt;</option>
											<option value="eq">=</option>
										</select>
									</div>
									<input type="number" 
													step=".01" 
													name="item_cost" 
													placeholder="Item Cost" 
													class="form-control form-control-sm">
								</div>
							</td>
							<td>
								<div class="input-group"> 
									<div class="input-group-append">
										<select class="rounded-left form-control-sm form-control"
														name="item_price_comparison"> 
											<option value="lt">&lt;</option>
											<option value="le">&lt;=</option>
											<option value="ge">&gt;=</option>
											<option value="gt">&gt;</option>
											<option value="eq">=</option>
										</select>
									</div>
									<input type="number" 
													step=".01" 
													name="item_price" 
													placeholder="Item Price" 
													class="form-control form-control-sm">
								</div>
							</td>
							<td>
								<div class="input-group"> 
									<div class="input-group-append">
										<select class="rounded-left form-control-sm form-control"
														name="qty_on_hand_comparison"> 
											<option value="lt">&lt;</option>
											<option value="le">&lt;=</option>
											<option value="ge">&gt;=</option>
											<option value="gt">&gt;</option>
											<option value="eq">=</option>
										</select>
									</div>
									<input type="number" 
													name="qty_on_hand" 
													placeholder="Stock" 
													class="form-control form-control-sm">
								</div>
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
	var menu_id = "view_items";
	let getVars = {!! json_encode($_GET) !!};
  if(getVars) {
    for (let i in getVars) {
      $('[name=' + i + ']').val(getVars[i]);
    }
  }
</script>
@endsection