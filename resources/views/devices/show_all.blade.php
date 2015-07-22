@extends('app')

@section('header')
	@include('util.m-topbar')
	<div class="container">
		<div class="col-lg-12">
			<ol class="breadcrumb" style=" margin-left: 1.5rem; ">
				<li><label>Inventory</label>
				<li><a href="{{ route('home') }}" class="active">Home</a></li>
				<li><label>Available Devices</label>
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
			<h3>Available Devices</h3>
			<hr/>
			<br>
			<table id="all_avail_dev"></table>
			<br>
		</div>
	</div>
</div>
@stop

@section('script')
<script type="text/javascript">
$.getJSON("{{ route('r_a_a_d') }}", function(data) {
	$('#all_avail_dev').dataTable({
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
			{"sTitle": "#", 'width':'5%',"mDataProp": "id"},
			{"sTitle": "Category", 'width':'25%',"mDataProp": "category_name"},
			{"sTitle": "Device", 'width':'25%',"mDataProp": "device_name"},
			{"sTitle": "Recent Updated", 'width':'25%',"mDataProp": "updated_at"}
		],
		"aoColumnDefs":
		[
			// REDIRECT TO HEADSET PROFILE
			{ "bSortable": false, "aTargets": [ 0 ] },
			{
				"aTargets": [ 0 ], // Column to target
				"mRender": function ( data, type, full ) {
					return "<label class='size-14 text-left'>" + data + "</label>";
				}
			},
			{
				"aTargets": [ 1 ], // Column to target
				"mRender": function ( data, type, full ) {
				    var url = '{{ route('category.edit', ":slug") }}';
				    url = url.replace(':slug', full["category_slug"]);
					// 'full' is the row's data object, and 'data' is this column's data
					// e.g. 'full[0]' is the comic id, and 'data' is the comic title
					return "<a href='" + url + "' class='size-14 text-left'>" + data + "</a>";
				}
			},
			{
				"aTargets": [ 1 ], // Column to target
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
					// 'full' is the row's data object, and 'data' is this column's data
					// e.g. 'full[0]' is the comic id, and 'data' is the comic title
					return "<label class='size-14 text-left'>" + data + "</label>";
				}
			}
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
	$('div.dataTables_filter input').attr('placeholder', 'Filter Devices');
});
</script>
@stop