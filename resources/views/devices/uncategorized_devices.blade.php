@extends('app')

@section('header')
	@include('util.m-topbar')
	<div class="container">
		<div class="col-lg-12">
			<ol class="breadcrumb" style=" margin-left: 1.5rem; ">
				<li><label>Inventory</label>
				<li><a href="{{ route('home') }}" class="active">Home</a></li>
				<li><label>Uncategorized Devices</label>
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
				<h3>Uncategorized Devices</h3>
			</div>
			<br>
				<table id="uncategorized_category"></table>
			<br>
		</div>

	</div>
</div>
@stop

@section('script')
<script type="text/javascript">
$.getJSON("{{ route('f_u_d') }}", function(data) {
	$('#uncategorized_category').dataTable({
		"aaData": data,
		"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
		"aaSorting": [[ 1, 'asc' ]],
		"oLanguage": {
			"sLengthMenu": "# of Uncategorized Device _MENU_",
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
			{"sTitle": "Owner", "mDataProp": "owner", "sClass": "size-14"},
			{"sTitle": "Description", "mDataProp": "name"},
			{"sTitle": "Brand", "mDataProp": "brand"},
			{"sTitle": "NSI Tag", "mDataProp": "tag"},
			{"sTitle": "Status", "mDataProp": "status", "sClass": "size-14"},
			{"sTitle": "Category", "width":"20%" ,"mDataProp": "category"}
		],
		"aoColumnDefs":
		[
			// REDIRECT TO HEADSET PROFILE
			{
				"aTargets": [ 0 ], // Column to target
				"mRender": function ( data, type, full ) {
					return "<label class='size-14 text-left'>" + data + "</label>";
				}
			},
			{
				"aTargets": [ 1 ], // Column to target
				"mRender": function ( data, type, full ) {
					return "<label class='size-14 text-left'>" + data + "</label>";
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
					return "<label class='size-14 text-left'>" + data + "</label>";
				}
			},
			{
				"aTargets": [ 4 ], // Column to target
				"mRender": function ( data, type, full ) {
					return "<label class='size-14 text-left'>" + data + "</label>";
				}
			},
			{
				"aTargets": [ 5 ], // Column to target
				"mRender": function ( data, type, full ) {
					return "<label class='size-14 text-left'>" + data + "</label>";
				}
			}
		]
	});
	$('div.dataTables_filter input').attr('placeholder', 'Filter Devices');
});
</script>
@stop