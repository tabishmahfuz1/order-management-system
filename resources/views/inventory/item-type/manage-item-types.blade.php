@extends('layouts.card-body-base')

@section('card-heading') Item Types @endsection

@section('card-content')
@verbatim
<div id="itemListComponent">
	<ul>
		<li v-for="itemType in itemTypes" v-bind:typeId="itemType.id">
			{{ itemType.name }}
		</li>
	</ul>
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
	  }
	})
</script>
@endsection

