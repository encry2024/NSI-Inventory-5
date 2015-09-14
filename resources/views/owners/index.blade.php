@extends('app')

@section('header')
	@include('util.m-topbar')
	<div class="container">
    	<div class="col-lg-12">
    		<ol class="breadcrumb" style=" margin-left: 1.5rem; ">
    			<li><label>Inventory</label>
    			<li><a href="{{ route('home') }}" class="active">Home</a></li>
    			<li><label>Owners</label>
    		</ol>
    		@if (Session::has('success_msg'))
				<div class="alert alert-success" role="alert" style=" margin-left: 1.5rem; ">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					{{ Session::get('success_msg')  }}
				</div>
			@endif
    	</div>
    </div>
@stop

@section('content')
<div class="container">
	<div class="col-lg-3">
   		<div class="btn-group-vertical col-lg-12" role="group">
			<a role="button" class="btn btn-default col-lg-12 text-left" href="#" data-toggle='modal' data-target='#addOwner'><span class="glyphicon glyphicon-plus"></span> Create Owners</a>
			<a role="button" class="btn btn-default col-lg-12 text-left" href="#" data-toggle='modal' data-target='#addStatus'><span class="glyphicon glyphicon-trash"></span> Deleted Owners <span class="badge right">{{ count($deletedOwners) }}</span></a>
			<a role="button" class="btn btn-default col-lg-12 text-left" href="{{ route('home')  }}"><span class="glyphicon glyphicon-chevron-left"></span> Return to Home</a>
		</div>
	</div>
	<div class="col-lg-9 col-md-offset-center-2" >
		<div class="panel panel-default col-lg-12" style="border-color: #ccc;">
			<div class="page-header">
				<h3>Owners</h3>
			</div>
			<br/>
			<table id="owners"></table>
			<br/>
		</div>
	</div>
</div>

{{-- EDIT OWNER MODAL --}}
<div class="modal fade" id="addOwner" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	{!! Form::open(['route'=>['owner.store']]) !!}
	<div class="modal-dialog">
		<div class="modal-content ">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Owners</h4>
			</div>
			<div class="modal-body">
				<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12 center col-lg-offset-1">
							<div class="form-group">
								<label class="col-md-4 control-label">Firstname</label>
								<div class="col-md-6">
									<input type="string" class="form-control" name="firstname" value="{{ old('firstname') }}">
								</div>
							</div>
							<br/><br/>
							<div class="form-group">
								<label class="col-md-4 control-label">Lastname</label>
								<div class="col-md-6">
									<input type="string" class="form-control" name="lastname" value="{{ old('lastname') }}">
								</div>
							</div>
							<br/><br/>
							<div class="form-group" style=" margin-top: -1.5rem;">
								<label class="col-md-4 control-label">Campaign</label>
								<div class="col-md-6">
									<input type="string" class="form-control" name="location" value="{{ old('location') }}">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			</div>
			<br/>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-primary">Save Changes</button>
		</div>
	</div>
</div>
{!! Form::close() !!}
</div>
@stop

@section('script')
<script>
$.getJSON("{{ URL::to('/') }}/owner/fetch", function(data) {
	$('#owners').dataTable({
		"aaData": data,
		"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
		"oLanguage": {
			"sEmptyTable": "No Owners to be shown...",
			"sLengthMenu": "No. of Owners _MENU_",
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
			{"sTitle": "#", "mDataProp": "id", "sWidth": "20px","sClass": "size-14"},
			{"sTitle": "Name", "mDataProp": "name"},
			{"sTitle": "Campaign", "mDataProp": "campaign"},
			{"sTitle": "Recent Update", "mDataProp": "updated_at"}

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
			//CATEGORY SLUG
			{
				"aTargets": [ 1 ], // Column to target
				"mRender": function ( data, type, full ) {
					var url = '{{ route('owner.show', ":slug") }}';
					url = url.replace(':slug', full["slug"]);
					// 'full' is the row's data object, and 'data' is this column's data
					// e.g. 'full[0]' is the comic id, and 'data' is the comic title
					return "<a href='"+url+"' class='size-14 text-left'>" + data + "</a>";
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
			{
				"aTargets": [ 3 ], // Column to target
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
$('div.dataTables_filter input').attr('placeholder', 'Filter Devices');
});
</script>
@stop

@section('style')
@stop