@extends('app')

@section('header')
	@include('util.m-topbar')
	<div class="container">
    	<div class="col-lg-12">
    		<ol class="breadcrumb" style=" margin-left: 1.5rem; margin-right: -1.5rem;">
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
			<a role="button" data-toggle="modal" data-target="#editInfo" class="btn btn-default col-lg-12 text-left" href="#"><span class="glyphicon glyphicon-pencil"></span> Edit Owner</a>
			<a role="button" data-toggle="modal" data-target="#confirmDelete" class="btn btn-default col-lg-12 text-left" href="#"><span class="glyphicon glyphicon-trash"></span> Delete Owner</a>
			<a role="button" class="btn btn-default col-lg-12 text-left" href="{{ route('owner.index') }}"><span class="glyphicon glyphicon-chevron-left"></span> Back to Owner Page</a>
		</div>
	</div>

	<div class="panel panel-default col-lg-9" style="border-color: #ccc;">
		<h3>{{ $owner->fullName() }}</h3>
		<br><br>
		<label for="">campaign: {{ $owner->location }}</label>
        <br><br>
        <div class="alert alert-info col-lg-12" role="alert">
            <strong>Current Owned Devices</strong>
        </div>
        <br><br><br><br>
        <div class="list-group">
            @foreach ($owner->devices as $owner_device)
                <a class="list-group-item" href="{{ route('device.edit', $owner_device->slug) }}">{{ $owner_device->name }}</a>
            @endforeach
        </div>

        <br><br><br>
        <div class="alert alert-info col-lg-12" role="alert">
            <strong>Ownership History</strong>
        </div>
        <table class="table table-condensed table-hover">
            <thead>
                <tr>
                    <td></td>
                    <td>Device</td>
                    <td>Category</td>
                    <td>Admin</td>
                    <td>Action</td>
                    <td>Date</td>
                </tr>
            </thead>
            <tbody>
                @foreach($device_logs as $device_log)
                <tr>
                    <td style="font-weight: normal; font-size: 12px;">{{ $ctr++ + $device_logs->firstItem() }}</td>

                    <td style="font-weight: normal; font-size: 12px;"><a href="{{ route('device.edit', $device_log->device->slug) }}">{{ $device_log->device->name }}</a></td>

                    <td style="font-weight: normal; font-size: 12px;">{{ $device_log->device->category->name }}</td>

                    <td style="font-weight: normal; font-size: 12px;">{{ $device_log->user->name }}</td>

                    <td style="font-weight: normal; font-size: 12px;">{{ $device_log->action }}</td>

                    <td style="font-weight: normal; font-size: 12px;">{{ date('F d, Y h:i A', strtotime($device_log->created_at)) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <br><br>
    </div>
</div>

{{-- CHANGE STATUS MODAL --}}
<div class="modal fade" id="editInfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
{!! Form::open(['method'=>'PATCH', 'route' => ['owner.update', $owner->slug]]) !!}
<div class="modal-dialog">
	<div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">Edit {{ $owner->fullName() }}</h4>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12 center col-lg-offset-1">
						<div class="form-group">
							<label class="col-md-4 control-label" style=" margin-top: 0.4rem; ">Firstname</label>
							<div class="col-md-6">
								{!! Form::text('firstName', $owner->firstName,['class'=>'form-control']) !!}
							</div>
						</div>
						<br/><br/>
						<div class="form-group">
							<label class="col-md-4 control-label" style=" margin-top: 0.4rem; ">Lastname</label>
							<div class="col-md-6">
								{!! Form::text('lastName', $owner->lastName,['class'=>'form-control']) !!}
							</div>
						</div>
						<br/><br/>
						<div class="form-group" style=" margin-top: -1.5rem; ">
							<label class="col-md-4 control-label" style=" margin-top: 0.6rem; ">Campaign</label>
							<div class="col-md-6">
								{!! Form::text('campaign', $owner->campaign,['class'=>'form-control']) !!}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br/>
		<div class="modal-footer">
			<button type="submit" class="btn btn-primary">Save Changes</button>
		</div>
	</div>
</div>
{!! Form::close() !!}
</div>

{{--confirmDelete--}}
<div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
{!! Form::open(['method'=>'DELETE', 'route' => ['owner.destroy', $owner->slug]]) !!}
<div class="modal-dialog">
	<div class="modal-content ">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">Deleting {{ $owner->fullName() }}</h4>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row">
					<label for="">Are you sure you want to delete <code>{{ $owner->fullName() }}</code>?</label>
				</div>
			</div>
		</div>
		<br/>
		<div class="modal-footer">
			<button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> Delete</button>
		</div>
	</div>
</div>
{!! Form::close() !!}
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
					"aTargets": [ 3 ], // Column to target
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