@extends('app')

@section('header')
	@include('util.m-topbar')
	<div class="container">
		<div class="col-lg-12">
			<ol class="breadcrumb" style=" margin-left: 1.5rem; ">
				<li><label>Inventory</label>
				<li><a href="{{ route('home') }}" class="active">Home</a></li>
				<li><a href="{{ route('category.show', [$category->slug])  }}" class="active">{{ $category->name }}</a></li>
				<li><label>History</label>
			</ol>
		</div>
	</div>
@stop

@section('content')
<div class="container">
	<div class="col-lg-3">
   		<div class="btn-group-vertical col-lg-12" role="group">
			<a role="button" class="btn btn-default col-lg-12 text-left" href="{{ route('home')  }}"><span class="glyphicon glyphicon-chevron-left"></span> Return to Home</a>
		</div>
	</div>

	<div class="col-lg-9 col-md-offset-center-2" >
		<div class="panel panel-default col-lg-12" style="border-color: #ccc;">
		   <div class="page-header">
				<h3>{{ $category->name }} History</h3>
		   </div>
		   <br>
		   <table id="category_history"></table>
		   <br>
		</div>
	</div>
</div>
@stop

@section('script')
<script type="text/javascript">
$.getJSON("{{ route('c_h', $category->slug) }}", function(data) {
	$('#category_history').dataTable({
		"aaData": data,
		"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
		"oLanguage": {
			"sLengthMenu": "Display no. of Categories _MENU_",
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
			{"sTitle": "Devices", "mDataProp": "device_name"},
			{"sTitle": "Owner", "mDataProp": "owner_name"},
			{"sTitle": "Action", "mDataProp": "action"},
			{"sTitle": "Assigned By", "mDataProp": "assigned_by"},
			{"sTitle": "Date Assigned", "mDataProp": "date_assigned"}
		],
		"aoColumnDefs":
		[
			// REDIRECT TO HEADSET PROFILE
			{
				"aTargets": [ 0 ], // Column to target
				"mRender": function ( data, type, full ) {
				    var url = '{{ route('device.edit', ":slug") }}';
				    url = url.replace(':slug', full["device_slug"]);
					// 'full' is the row's data object, and 'data' is this column's data
					// e.g. 'full[0]' is the comic id, and 'data' is the comic title
					return "<a href='" + url + "' class='size-14 text-left'>" + data + "</a>";
				}
			},
			{
				"aTargets": [ 1 ], // Column to target
				"mRender": function ( data, type, full ) {
				    var url = '{{ route('owner.edit', ":slug") }}';
				    url = url.replace(':slug', full["owner_slug"]);
					// 'full' is the row's data object, and 'data' is this column's data
					// e.g. 'full[0]' is the comic id, and 'data' is the comic title
					return "<a href='" + url + "' class='size-14 text-left'>" + data + "</a>";
				}
			},
			{
				"aTargets": [ 2 ], // Column to target
				"mRender": function ( data, type, full ) {
					return "<label class='size-14 text-left'>" + data + "</label>";
				}
			},
			{
				"aTargets": [ 3 ], // Column to target
				"mRender": function ( data, type, full ) {
					var url = '{{ route('user.edit', ":slug") }}';
					url = url.replace(':slug', full["slug"]);
					// 'full' is the row's data object, and 'data' is this column's data
					// e.g. 'full[0]' is the comic id, and 'data' is the comic title
					return '<a href="'+url+'" class="size-14 text-left">' + data + '</a>';
				}
			},
			{
				"aTargets": [ 4 ], // Column to target
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