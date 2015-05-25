@extends('app')

@section('header')
	@include('util.m-topbar')
	<div class="container">
    	<div class="col-lg-12">
    		<ol class="breadcrumb" style=" margin-left: 1.5rem; ">
    			<li><label>Inventory</label>
    			<li><a href="{{ route('home') }}" class="active">Home</a></li>
    			<li><a href="{{ route('owner.index') }}" class="active">Owners</a></li>
    			<li><label>{{ $owner->fullName }}</label></li>
    		</ol>
    	</div>
    </div>
@stop

@section('content')
<div class="container">
	<div class="col-lg-3">
		<div class="btn-group-vertical col-lg-12" role="group">
			<a role="button" class="btn btn-default col-lg-12 text-left" href="#"><span class="glyphicon glyphicon-pencil"></span> Edit Owner</a>
			<a role="button" class="btn btn-default col-lg-12 text-left" href="#"><span class="glyphicon glyphicon-trash"></span> Delete Owner</a>
			<a role="button" class="btn btn-default col-lg-12 text-left" href="{{ route('owner.index') }}"><span class="glyphicon glyphicon-chevron-left"></span> Back to Owner Page</a>
		</div>
		<br><br><br><br><br><br>
		<div class="btn-group-vertical col-lg-12" role="group">
			<a role="button" class="btn btn-default col-lg-12 text-left" href="#">Associated Devices</a>
			@foreach ($owner->devices as $owner_device)
				<a role="button" class="btn btn-default col-lg-12 text-left" href="#">{{ $owner_device->name }}</a>
			@endforeach
		</div>
	</div>

	<div class="panel panel-default col-lg-9" style="border-color: #ccc;">
		<div class="page-header">
			<h3>Associate/Dissociate History</h3>
		</div>
		<br/>
		<table id="dispatchDevices"></table>
		<br/>
	</div>
</div>
@stop

@section('script')
<script type="text/javascript">
	$.getJSON("{{ route('fetchDispatchDevices', [$owner->id]) }}", function(data) {
		$('#dispatchDevices').dataTable({
			"aaData": data,
			"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
			"oLanguage": {
				"sEmptyTable": "No Status History to be shown...",
				"sLengthMenu": "No. of Status History _MENU_",
				"oPaginate": {
				"sFirst": "First ", // This is the link to the first
				"sPrevious": "&#8592; Previous", // This is the link to the previous
				"sNext": "Next &#8594;", // This is the link to the next
				"sLast": "Last " // This is the link to the last
				}
			},
			//DISPLAYS THE VALUE
			//sTITLE - HEADER
			//MDATAPROP - TBODY
			"aoColumns":
			[
				{"sTitle": "Device Name", "mDataProp": "device_name"},
				{"sTitle": "Action", "mDataProp": "action"},
				{"sTitle": "Assigned By", "mDataProp": "user"},

				{"sTitle": "Date Associated/Disassociated", "mDataProp": "created_at"}

			],
			"aoColumnDefs":
			[
				//FORMAT THE VALUES THAT IS DISPLAYED ON mDataProp
				//ID
				{ "bSortable": false, "aTargets": [ 0 ] },
				{
					"aTargets": [ 0 ], // Column to target
					"mRender": function ( data, type, full ) {
					var url = '{{ route('device.edit', ":slug") }}';
					url = url.replace(':slug', full["device_slug"]);
					// 'full' is the row's data object, and 'data' is this column's data
					// e.g. 'full[0]' is the comic id, and 'data' is the comic title
					return "<a href='"+url+"' class='size-14 text-left'>" + data + "</a>";
					}
				},
				//USER
				{
					"aTargets": [ 1 ], // Column to target
					"mRender": function ( data, type, full ) {
						var url = '{{ route('user.edit', ":id") }}';
						url = url.replace(':id', full["user_id"]);
						// 'full' is the row's data object, and 'data' is this column's data
						// e.g. 'full[0]' is the comic id, and 'data' is the comic title
						return "<a href='"+url+"' class='size-14 text-left'>" + data + "</a>";
					}
				},
				//ACTION
				{
					"aTargets": [ 2 ], // Column to target
					"mRender": function ( data, type, full ) {
					// 'full' is the row's data object, and 'data' is this column's data
					// e.g. 'full[0]' is the comic id, and 'data' is the comic title
					return '<label class="text-center size-14"> ' + data + ' </label>';
					}
				},
				//CATEGORY RECENT UPDATE
				{
					"aTargets": [ 2 ], // Column to target
					"mRender": function ( data, type, full ) {
					// 'full' is the row's data object, and 'data' is this column's data
					// e.g. 'full[0]' is the comic id, and 'data' is the comic title
					return '<label class="text-center size-14"> ' + data + ' </label>';
					}
				},
			]
		});
	$('div.dataTables_filter input').attr('placeholder', 'Filter Devices');
	});
</script>
@stop

@section('style')
@stop