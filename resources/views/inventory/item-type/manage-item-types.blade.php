@extends('layouts.card-body-base')

@section('card-heading') Item Types @endsection

@section('card-content')
@verbatim
<div id="itemListComponent">
	<div class="form-group input-group col-md-4 mx-auto" ref="newTypeGroup">
		<input v-model="newTypeName"
				placeholder="New Type" 
				class="form-control-sm form-control input-group-append" />
		<button class="btn btn-sm btn-success input-group-append" v-on:click="addItemType">
			<i class="fa fa-plus fa-sm"></i>
		</button>
	</div>
	<table class="table table-striped table-bordered table-sm">
		<thead>
			<tr>
				<th></th>
				<th>Type Name</th>
				<th>Status</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody v-if="itemTypes.length > 0">
			<tr v-for="(itemType, i) in itemTypes" 
				v-bind:typeId="itemType.id">
				<td class="text-center">{{ i+1 }}</td>
				<td>{{ itemType.name }}</td>
				<td>{{ itemType.status? "Active" : "Disabled" }}</td>
				<td class="text-center">
					<button type="button" class="btn btn-sm btn-primary"
							v-on:click="editItemType(itemType, i)">
						<i class="fa fa-edit"></i>
					</button>
				</td>
			</tr>
		</tbody>
		<tbody v-else>
			<tr>
				<td colspan="4">No Item Types Added</td>
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
	    itemTypes: {!! $itemTypes->toJson() !!},
	    newTypeName: '',
	    editType: null
	  },
	  created: function() {
	    console.log('Vue instance was created');
	  },
	  methods: {
	  	editItemType: function(thisItemType, index){
	  		thisItemType.editing = true;
	  		console.log(thisItemType);
	  		this.editType = {
	  			originalTypeObject: thisItemType,
	  			index
	  		};
	  		this.newTypeName = thisItemType.name;
	  		this.goto('newTypeGroup');
	  	},
	  	addItemType: function () {
	  		if(!this.newTypeName) return false;

	  		if(this.editType) {
	  			this.editType.originalTypeObject.editing = false;
	  			Vue.set(
	  				this.itemTypes, 
	  				this.editType.index, 
	  				Object.assign(this.editType.originalTypeObject, 
	  					{name: this.newTypeName})
	  				);
	  			this.editType = null;
	  		} else {
	  			console.log("To Add", this.newTypeName);
		  		this.itemTypes.push({name: this.newTypeName, status: true});
	  		}

		  	this.newTypeName = "";
	  	},
	  	goto(refName) {
	        var element = this.$refs[refName];
	        console.log(element);
	        var top = element.offsetTop;
	        document.getElementById('content-wrapper').scrollTo(0, top);
	    }
	  }
	});
	
</script>
@endsection

