@extends('app')

@section('header')
    @include('util.m-topbar')
	<div class="container">
		<div class="col-lg-12">
			<ol class="breadcrumb" style=" margin-left: 1.5rem; ">
				<li><label>Inventory</label>
				<li><a href="{{ route('home') }}" class="active">Home</a></li>
				<li><a href="{{ route('category.show', [$device->category->slug])  }}" class="active">{{ $device->category->name }}</a></li>
				<li><label>{{ $device->name }}</label>
			</ol>
			@if (Session::has('success_msg'))
				<div class="alert {{ Session::get('message_label') }}" role="alert" style=" margin-left: 1.5rem; ">
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
   			<a role="button" class="btn btn-default col-lg-12 text-left" href="#"  data-toggle='modal' data-target='#noteStore' ><span class="glyphicon glyphicon-pencil"></span> Make a Note</a>
			@if ($device->owner_id != 0)
				<a role="button" class="btn btn-default col-lg-12 text-left" data-toggle='modal' data-target='#disassociate_device' href="#"><span class="glyphicon glyphicon-remove"></span> Disassociate</a>
			@else
				<a role="button" id="grp" class="btn btn-default col-lg-12 text-left cli" data-toggle='modal' data-target='#associate_device' href="#"><span class="glyphicon glyphicon-tag"></span> Associate</a>
			@endif
			<a role="button" class="btn btn-default col-lg-12 text-left" href="#" data-toggle='modal' data-target='#change_status'><span class="glyphicon glyphicon-repeat"></span> Change Status</a>
			<a role="button" class="btn btn-default col-lg-12 text-left" href="#" data-toggle="modal" data-target="#deleteDevice"><span class="glyphicon glyphicon-trash"></span> Delete {{ $device->name }}</a>
			<a role="button" class="btn btn-default col-lg-12 text-left" href="{{ route('category.show', [$device->category->slug])  }}"><span class="glyphicon glyphicon-chevron-left"></span> Return to {{ $device->category->name }}</a>
		</div>

    	<div class="btn-group-vertical col-lg-12" role="group" style="top: 1rem;">
			<a role="button" id="grp" class="btn btn-default col-lg-12 text-left" href="{{ route('status.index') }}">Status: <span class="right">{{ $device->status->status }}</span></a>
			@if ($device->owner_id != 0)
				<a role="button" id="grp" class="btn btn-default col-lg-12 text-left" data-toggle='modal' title="{{ $device->owner->fullName() }}" href="{{ route('owner.show', [$device->owner->slug]) }}">Owner: <span class="right">{{ str_limit($device->owner->fullName(), $limit = 16, $end = '...') }}</span></a>
			@else
				<a role="button" id="grp" class="btn btn-default col-lg-12 text-left" data-toggle='modal' data-target='#associate_device' href="#">Owner: <span class="right">{{ $device->availability }}</span></a>
			@endif
		</div>

		<div class="btn-group-vertical col-lg-12" role="group" style="top: 2rem;">
		<a role="button" id="grp" class="btn btn-default col-lg-12 text-center rm-hvr" href="#">Click here to edit all</a></a>
			@foreach ($device->information as $key=>$device_field)
				<a tabindex="{{ ++$key }}" role="button" id="grp" class="btn btn-default col-lg-12 text-left" data-popover="true" data-html="true" data-trigger="focus"
				data-content="<a class='glyphicon glyphicon-pencil' onclick='editInfo({{ $device_field->id}}, &quot;{{ $device_field->value }}&quot;, &quot;{{ $device_field->field->category_label }}&quot;)' title=&quot;Edit {{ $device->name }} {{ $device_field->field->category_label }}&quot;  data-toggle='modal' data-target='#editInfo'></a><a class='glyphicon glyphicon-trash' title=&quot;Delete {{ $device->name }} {{ $device_field->field->category_label }}&quot; onclick='deleteInfo({{ $device_field->id}}, &quot;{{ $device_field->value }}&quot;, &quot;{{ $device_field->field->category_label }}&quot;)' data-toggle='modal' data-target='#deleteInfo'></a>"
				> {{ $device_field->field->category_label }}:<span class="right">{{ $device_field->value }}</span></a>
			@endforeach
		</div>
	</div>

    <div class="col-lg-9 col-md-offset-center-2" >
		<h3><i>{{ $device->name  }}</i><span class="right"><label for="" class="size-15">Category: {{ $device->category->name }}</label></span></h3>
		<hr/>

		@if($device->status_id != '' || $device->status_id != 0)
		<div class="panel panel-default col-lg-12" style="border-color: #ccc;">
			<h3>Status: <span><a>{{ $device->status->status }}</a></span></h3>
			<hr>
			Status Description:
			<div class="well well-sm" name="note"><i>{{ $device->status->description }}</i></div>
		</div>
		@endif

		<div class="panel panel-default col-lg-12" style="border-color: #ccc;">
			<h3>Associate/Dissociate History</h3>
			<hr>
			<br/>
			<table id="assoc_history"></table>
			<br/>
		</div>

		<div class="panel panel-default col-lg-12" style="border-color: #ccc;">
			<h3>Status History</h3>
			<hr>
			<br/>
			<table id="device_status"></table>
			<br/>
		</div>

        <div class="panel panel-default col-lg-12" style="border-color: #ccc;">
			<h3>Notes</h3>
			<hr>
			<label for="">Recent Note:</label>
			@if (count($note) > 0)
			<div class="well well-sm" name="note"><i>{{ str_replace("&apos;", "'", $note->note) }}</i></div>
			@else
			<div class="well well-sm" name="note"><i>There is no note to display...</i></div>
			@endif
			<hr>
			<table id="note_history"></table>
			<br/>
		</div>
    </div>
 </div>

{{-- EDIT INFORMATION MODAL --}}
 <div class="modal fade" id="editInfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	{!! Form::open(['route'=>['information/update']]) !!}
	<div class="modal-dialog">
		<div class="modal-content ">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Edit {{ $device->name }} <span id="category_label"></span> </h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12 center col-lg-offset-1">
							<div class="form-group">
								<label class="col-md-4 control-label" id="catgry_label"></label>
								<div class="col-md-6">
									<input type="string" class="form-control" id="info_value" name="value" value="{{ old('value') }}">
								</div>
							</div>
							<br/><br/>
						</div>
					</div>
				</div>
			</div>
			<br/>
			<div class="modal-footer">
				{!! Form::hidden('inf_id', '', ['id'=>'info_id']) !!}
				{!! Form::hidden('device_id', $device->id) !!}
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
	{!! Form::close() !!}
</div>

{{-- CREATE NOTE MODAL --}}
 <div class="modal fade" id="noteStore" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	{!! Form::open(['route'=>['note.store']]) !!}
	<div class="modal-dialog">
		<div class="modal-content ">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><span><a class="glyphicon glyphicon-pencil" href=""></a></span> Create a Note <span id="category_label"></span> </h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12 center col-lg-offset-1">
							<div class="form-group">
								<label class="col-md-4 control-label"><i>Your Note*</i></label>
								<div class="col-md-6">
									<input type="string" class="form-control" id="" name="note" value="{{ old('value') }}">
								</div>
							</div>
							<br/><br/>
						</div>
					</div>
				</div>
			</div>
			<br/>
			<div class="modal-footer">
				{!! Form::hidden('user_id', Auth::user()->id) !!}
				{!! Form::hidden('device_id', $device->id) !!}
				@if (count($note) > 0)
				{!! Form::hidden('note_id', $note->id) !!}
				@endif
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
	{!! Form::close() !!}
</div>

{{-- ASSOCIATE DEVICE MODAL --}}
 <div class="modal fade" id="associate_device" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	{!! Form::open(['route' => ['device_associate', $device->id]]) !!}
	<div class="modal-dialog">
		<div class="modal-content ">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Associate {{ $device->name }} </h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12 center col-lg-offset-1">
							<div class="form-group">
								<label class="col-md-4 control-label">Owners</label>
								<div class="col-md-6">
									<div id="groupList" name="owner_id"></div>
								</div>
							</div>
							<br/><br/>
						</div>
					</div>
				</div>
			</div>
			<br/>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Associate Device</button>
			</div>
		</div>
	</div>
	{!! Form::close() !!}
</div>

{{-- DISASSOCIATE DEVICE MODAL --}}
 <div class="modal fade" id="disassociate_device" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	{!! Form::open(['route' => ['device_disassociate', $device->id]]) !!}
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Disassociate {{ $device->name }} </h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12 center col-lg-offset-1">
							<div class="form-group">
								<label class="col-md-4 control-label">Owner</label>
								<div class="col-md-6">
									@if ($device->owner_id != 0)
									<label for="">{{ $device->owner->fullName() }}</label>
									@endif
								</div>
							</div>
							<br/><br/>
						</div>
					</div>
				</div>
			</div>
			<br/>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Disassociate Device</button>
			</div>
		</div>
	</div>
	{!! Form::close() !!}
</div>

{{-- CHANGE STATUS MODAL --}}
 <div class="modal fade" id="change_status" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	{!! Form::open(['route' => ['change_status', $device->id]]) !!}
	<div class="modal-dialog">
		<div class="modal-content ">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Change {{ $device->name }} Status</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12 center col-lg-offset-1">
							<div class="form-group">
								<label class="col-md-4 control-label">Owners</label>
								<div class="col-md-6">
									<div id="statusList" name="status_id"></div>
								</div>
							</div>
							<br/><br/>
						</div>
					</div>
				</div>
			</div>
			<br/>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Change Status</button>
			</div>
		</div>
	</div>
	{!! Form::close() !!}
</div>

<!-- DELETE DEVICE MODAL -->
<div class="modal fade" name="deleteDevice" id="deleteDevice" tabindex="-1" role="dialog" aria-labelledby="myModalLabels" aria-hidden="true">
	{!! Form::open(['method'=>'DELETE', 'route'=>['device.destroy', $device->slug]]) !!}
	<div class="modal-dialog">
		<div class="modal-content ">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabels">Delete {{ $device->name }}</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12 center col-lg-offset-1">
							<div class="form-group">
								<label class="col-md-12 control-label">Are you sure you want to delete [ Device :: {{ $device->name }} ]</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<br/>
			<div class="modal-footer">
				{!! Form::hidden('category_slug', $device->category->slug) !!}
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-danger">Delete</button>
			</div>
		</div>
	</div>
	{!! Form::close() !!}
</div>
@stop

@section('script')
<script>
	var originalLeave = $.fn.popover.Constructor.prototype.leave;
	$.fn.popover.Constructor.prototype.leave = function(obj){
		var self = obj instanceof this.constructor ?
		obj : $(obj.currentTarget)[this.type](this.getDelegateOptions()).data('bs.' + this.type)
		var container, timeout;

		originalLeave.call(this, obj);

		if(obj.currentTarget) {
			container = $(obj.currentTarget).siblings('.popover')
			timeout = self.timeout;
			container.one('mouseenter', function(){
				//We entered the actual popover – call off the dogs
				clearTimeout(timeout);
				//Let's monitor popover content instead
				container.one('mouseleave', function(){
					$.fn.popover.Constructor.prototype.leave.call(self, self);
				});
			})
		}
	};

	function editInfo(inf_id, value, categry_label) {
		document.getElementById("info_id").value = inf_id;
		document.getElementById('info_value').value = value;
		document.getElementById('category_label').innerHTML = categry_label;
		document.getElementById('catgry_label').innerHTML = categry_label;
	}

    $('body').popover({ selector: '[data-popover]', trigger: 'hover', placement: 'right', delay: {show: 50, hide: 50}});

	$.getJSON("{{ route('fetchAvailableOwners', [$device->category->id]) }}", function(data) {
		var datalist = [];
		console.log(data);

		$.each(data, function(key, val) {
			datalist.push({value: val.id, text: val.name});
		});

		$('#groupList').multilist({
			single: true,
			labelText: 'Select Group',
			datalist: datalist,
			enableSearch: true
		});
	});

	$.getJSON("{{ route('fetch_all_status') }}", function(data) {
		var datalist = [];
		console.log(data);

		$.each(data, function(key, val) {
			datalist.push({value: val.id, text: val.status});
		});

		$('#statusList').multilist({
			single: true,
			labelText: 'Select Status',
			datalist: datalist,
			enableSearch: true
		});
	});

	// DEVICE STATUSES
    $.getJSON("{{ route('fetch_device_statuses', [$device->id]) }}", function(data) {
		$('#device_status').dataTable({
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
				{"sTitle": "Status ID", "width":"15%" ,"mDataProp": "id", "sClass": "size-14"},
				{"sTitle": "Status", "mDataProp": "status"},
				{"sTitle": "Description", "mDataProp": "description"},
				{"sTitle": "Changed By", "mDataProp": "user_name"},
				{"sTitle": "Date Changed", "mDataProp": "created_at"}

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
				//DEVICE SLUG
				{
					"aTargets": [ 1 ], // Column to target
					"mRender": function ( data, type, full ) {
						var url = '{{ route('device.edit', ":slug") }}';
						url = url.replace(':slug', full["slug"]);
						// 'full' is the row's data object, and 'data' is this column's data
						// e.g. 'full[0]' is the comic id, and 'data' is the comic title
						return "<a href='"+url+"' class='size-14 text-left'>" + data + "</a>";
					}
				},
				//DATE CHANGED
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
	$('div.dataTables_filter input').attr('placeholder', 'Filter Status');
	});

	// DEVICE ASSOC HISTORY
	$.getJSON("{{ route('device_assoc_history', [$device->id]) }}", function(data) {
		$('#assoc_history').dataTable({
			"aaData": data,
			"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
			"oLanguage": {
			"sEmptyTable": "No Associate/Dissociate History to be shown...",
				"sLengthMenu": "No. of Associate/Dissociate _MENU_",
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
				{"sTitle": "#", "width":"2%","mDataProp": "id", "sClass": "size-14"},
				{"sTitle": "Owner", "width":"20%","mDataProp": "name"},
				{"sTitle": "Assigned By", "width":"20%","mDataProp": "user_name"},
				{"sTitle": "Action", "width":"20%","mDataProp": "action"},
				{"sTitle": "Date Associated/Dissociated", "width":"38%", "mDataProp": "created_at"}
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
				// OWNER SLUG
				{
					"aTargets": [ 1 ], // Column to target
					"mRender": function ( data, type, full ) {
						var url = '{{ route('owner.show', ":slug") }}';
						url = url.replace(':slug', full["slug"]);

						// 'full' is the row's data object, and 'data' is this column's data
						// e.g. 'full[0]' is the comic id, and 'data' is the comic title
						return "<a href='"+url+"' class='size-14 text-left' data-popover='true' data-html='true' data-trigger='hover' data-content='Name: <a>" + full['fullname'] + "</a> <br/> Campaign: <a>" + full['campaign'] + "</a>'>" + data + "</a>";
					}
				},
				// ASSIGNED BY
				{
					"aTargets": [ 2 ], // Column to target
					"mRender": function ( data, type, full ) {
						var url = '{{ route('user.show', ":id") }}';
						url = url.replace(':id', full["user_id"]);
						// 'full' is the row's data object, and 'data' is this column's data
						// e.g. 'full[0]' is the comic id, and 'data' is the comic title
						return "<a href='"+url+"' class='size-14 text-left'  data-popover='true' data-html='true' data-trigger='hover' data-content='Name: <a>" + full['user_name'] + "</a> <br/> User Type: <a>" + full['user_type'] + "</a>'>" + data + "</a>";
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

	/* NOTE DATA TABLE */
	$.getJSON("{{ route('note_history', [$device->id]) }}", function(data) {
		$('#note_history').dataTable({
			"bSort": false,
			"aaData": data,
			"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
			"oLanguage": {
			"sEmptyTable": "There's no Note to be shown...",
				"sLengthMenu": "No. of Note to be shown _MENU_",
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
				{"sTitle": "#", "width":"2%","mDataProp": "id", "sClass": "size-14"},
				{"sTitle": "Note by", "width":"10%","mDataProp": "name"},
				{"sTitle": "Note", "width":"20%","mDataProp": "note"},
				{"sTitle": "Date Created", "width":"8%", "mDataProp": "created_at"}
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
						var url = '{{ route('user.show', ":id") }}';
						url = url.replace(':id', full["user_id"]);
						// 'full' is the row's data object, and 'data' is this column's data
						// e.g. 'full[0]' is the comic id, and 'data' is the comic title
						return "<a href='"+url+"' class='size-14 text-left' data-popover='true' data-html='true' data-trigger='hover' data-content='Name: <a>" + full['name'] + "</a> <br/> User Type: <a>" + full['user_type'] + "</a>'>" + data + "</a>";
					}
				},
				//CATEGORY RECENT UPDATE
				{
					"aTargets": [ 2 ], // Column to target
					"mRender": function ( data, type, full ) {
						var url = '{{ route('user.show', ":id") }}';
						url = url.replace(':id', full["user_id"]);
						// 'full' is the row's data object, and 'data' is this column's data
						// e.g. 'full[0]' is the comic id, and 'data' is the comic title
						return "<a href='"+url+"' class='size-14 text-left' data-popover='true' data-html='true' data-trigger='hover' data-content='" + full["fullnote"] + "'>" + data + "</a>";
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
	$('div.dataTables_filter input').attr('placeholder', 'Type your Filter');
	});
</script>
@stop