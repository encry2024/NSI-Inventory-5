@extends('...app')

@section('header')
	@include('util.m-topbar')
	<div class="container">
		<div class="col-lg-12">
			<ol class="breadcrumb" style=" margin-left: 1.5rem; ">
				<li><label>Inventory</label>
				<li><a href="{{ route('home') }}" class="active">Home</a></li>
				<li><label>Defective Devices</label>
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
				<h3>Defective Devices</h3>
			</div>
			<br/>
			<table id="current_assoc"></table>
			<br/>
		</div>
	</div>
</div>
@stop

@section('script')
<script>
$.getJSON("{{ route('get_defectives') }}", function(data) {
	$('#current_assoc').dataTable({
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
			{"sTitle": "#", "mDataProp": "id", "sClass": "size-14"},
			{"sTitle": "Device", "mDataProp": "device_name", "sClass": "size-14"},
			{"sTitle": "Status", "mDataProp": "status"},
			{"sTitle": "Assigned By", "mDataProp": "user_name"},
			{"sTitle": "Date Assigned", "width":"20%", "mDataProp": "created_at"}

		],
		"aoColumnDefs":
		[
			//FORMAT THE VALUES THAT IS DISPLAYED ON mDataProp
			//ID
			{ "bSortable": false, "aTargets": [ 0 ] },
			{
				"aTargets": [ 0 ], // Column to target
				"mRender": function ( data, type, full ) {
				// 'full' is the row's data object, and 'data' is this column's data
				// e.g. 'full[0]' is the comic id, and 'data' is the comic title
				return '<label class="text-center size-14">' + data + '</label>';
				}
			},
			{
				"aTargets": [ 1 ], // Column to target
				"mRender": function ( data, type, full ) {
				var url = '{{ route('device.edit', ":slug") }}';
				url = url.replace(':slug', full["device_slug"]);
				// 'full' is the row's data object, and 'data' is this column's data
				// e.g. 'full[0]' is the comic id, and 'data' is the comic title
				return "<a href='"+url+"' class='size-14 text-left'  data-popover='true' data-html='true' data-trigger='hover' data-content='Name: <a>" + full['fullname'] + "</a> <br/> Campaign: <a>" + full['campaign'] + "</a>'>" + data + "</a>";
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
					return "<a href='"+url+"' class='size-14 text-left'  >" + data + "</a>";
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
			},
			{
				"aTargets": [ 4 ], // Column to target
				"mRender": function ( data, type, full ) {
				// 'full' is the row's data object, and 'data' is this column's data
				// e.g. 'full[0]' is the comic id, and 'data' is the comic title
				return '<label class="text-center size-14"> ' + data + ' </label>';
				}
			},
		],

		"fnDrawCallback": function( oSettings ) {
			/* Need to redo the counters if filtered or sorted */
			if ( oSettings.bSorted || oSettings.bFiltered )
			{
				for ( var i=0, iLen=oSettings.aiDisplay.length ; i<iLen ; i++ )
				{
					$('td:eq(0)', oSettings.aoData[ oSettings.aiDisplay[i] ].nTr ).html( "<label>" + (i+1) + "</label>" );
				}
			}
		}
	});
$('div.dataTables_filter input').attr('placeholder', 'Filter Associate/Dissociate');
});
</script>
@stop

@section('style')
@stop