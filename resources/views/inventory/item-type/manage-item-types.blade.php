@extends('layouts.card-body-base')

@section('card-heading') Item Types @endsection

@section('card-content')
@verbatim
<div id="itemListComponent">
	<table class="table table-striped table-bordered table-sm">
		<thead>
			<tr>
				<th></th>
				<th>Type Name</th>
				<th>Status</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="(itemType, i) in itemTypes" 
				v-bind:typeId="itemType.id">
				<td class="text-center">{{ i+1 }}</td>
				<td>{{ itemType.name }}</td>
				<td>{{ itemType.status? "Active" : "Disabled" }}</td>
				<td class="text-center">
					<button class="btn btn-sm btn-primary"
							v-on:click="editItemType">
						<i class="fa fa-edit"></i>
					</button>
				</td>
			</tr>
		</tbody>
	</table>
</div>
@endverbatim
@endsection

@section('scripts')
@include('plugins.vue')
<script type="text/javascript">
	var menu_id = 'view_item_types';
	var app = new Vue({
	  el: '#itemListComponent',
	  data: {
	    itemTypes: {!! $itemTypes->toJson() !!}
	  },
	  methods: {
	  	editItemType: function(e){
	  		console.log(e, this)
	  	}
	  }
	})
</script>
@endsection

