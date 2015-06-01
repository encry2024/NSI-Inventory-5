@extends('app')

@section('header')
	@include('util.m-topbar')
@stop

@section('content')
<div class="container">
    <div class="col-lg-3">
		<div class="btn-group-vertical col-lg-12" role="group">
			<a role="button" class="btn btn-default col-lg-12 text-left" href="{{ route('home')  }}"><span class="glyphicon glyphicon-chevron-left"></span> Return to home</a>
		</div>
	</div>

	<div class="col-lg-9 col-md-offset-center-2">
		<div class="panel panel-default col-lg-12" style="border-color: #ccc;">
			<div class="page-header">
				<h3>Device Logs (Old)</h3>
			</div>
			<table id="old_device_log" class="table"></table>
		</div>
	</div>
 </div>
@stop

@section('script')
<script>
$.getJSON("{{ route('o_d_l') }}", function(data) {
	$('#old_device_log').dataTable({
		"aaData": data,
		"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
		"oLanguage": {
		"sEmptyTable": "No Associate History to be shown...",
			"sLengthMenu": "No. of Associate _MENU_",
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
			{"sTitle": "Category", "width":"10%","mDataProp": "category_name", "sClass": "size-14"},
			{"sTitle": "Device", "mDataProp": "device_name", "sClass": "size-14"},
			{"sTitle": "Owner", "mDataProp": "owner_name"},
			{"sTitle": "Date Assigned", "mDataProp": "created_at"}

		],
		"aoColumnDefs":
		[
			//FORMAT THE VALUES THAT IS DISPLAYED ON mDataProp
			//ID
			{
				"aTargets": [ 0 ], // Column to target
				"mRender": function ( data, type, full ) {
					var url = '{{ route('category.show', ":slug") }}';
					url = url.replace(':slug', full["category_slug"]);
					// 'full' is the row's data object, and 'data' is this column's data
					// e.g. 'full[0]' is the comic id, and 'data' is the comic title
					return "<a href='"+url+"' class='size-14 text-left'  >" + data + "</a>";
				}
			},
			{
				"aTargets": [ 1 ], // Column to target
				"mRender": function ( data, type, full ) {
				var url = '{{ route('device.edit', ":slug") }}';
				url = url.replace(':slug', full["device_slug"]);
				// 'full' is the row's data object, and 'data' is this column's data
				// e.g. 'full[0]' is the comic id, and 'data' is the comic title
				return "<a href='"+url+"' class='size-14 text-left'  >" + data + "</a>";
				}
			},
			//CATEGORY SLUG
			{
				"aTargets": [ 2 ], // Column to target
				"mRender": function ( data, type, full ) {
					var url = '{{ route('owner.show', ":slug") }}';
					url = url.replace(':slug', full["owner_slug"]);
					// 'full' is the row's data object, and 'data' is this column's data
					// e.g. 'full[0]' is the comic id, and 'data' is the comic title
					return "<a href='"+url+"' class='size-14 text-left'  data-popover='true' data-html='true' data-trigger='hover' data-content='Name: <a>" + full['fullname'] + "</a> <br/> Campaign: <a>" + full['campaign'] + "</a>'>" + data + "</a>";
				}
			},
			//CATEGORY RECENT UPDATE
			{
				"aTargets": [ 3 ], // Column to target
				"mRender": function ( data, type, full ) {
				// 'full' is the row's data object, and 'data' is this column's data
				// e.g. 'full[0]' is the comic id, and 'data' is the comic title
				return '<label class="text-center size-14"> ' + data + ' </label>';
				}
			}
		]
	});
$('div.dataTables_filter input').attr('placeholder', 'Filter Associate/Dissociate');
});
</script>
@stop