@extends('layouts.card-body-base')

@section('card-heading') Item Types @endsection

@section('card-content')
    <item-type-list></item-type-list>
@endsection

@section('scripts')
<script type="text/javascript">
	var menu_id = 'view_item_types';
</script>
@endsection
