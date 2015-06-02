@extends('app')

@section('header')
    @include('util.m-topbar')
    <div class="container">
		<div class="col-lg-12">
			<ol class="breadcrumb" style=" margin-left: 1.5rem; ">
				<li><label>Inventory</label>
				<li><a href="{{ route('home') }}" class="active">Home</a></li>
				<li><label>{{ $category->name }}</label>
			</ol>
		</div>
	</div>
@stop

@section('content')
<div class="container">
    <div class="col-lg-3">
		<div class="btn-group-vertical col-lg-12" role="group">
			<a role="button" class="btn btn-default col-lg-12 text-left" href="{{ route('create_device', [$category->slug])  }}"><span class="glyphicon glyphicon-plus"></span> Create {{ str_limit($category->name, $limit='10', $end='...') }}</a>
			<a href="{{ route('device_excel', [$category->slug]) }}" class="btn btn-default text-left col-lg-12" role="button"><span class="glyphicon glyphicon-share-alt"></span> Import {{ str_limit($category->name, $limit='10', $end='...') }} Devices</a>
			<a role="button" class="btn btn-default col-lg-12 text-left" href="{{ route('category.show', [$category->slug])  }}"><span class="glyphicon glyphicon-info-sign"></span> {{str_limit($category->name, $limit='10', $end='...')}} Profile</a>
			<a role="button" class="btn btn-default col-lg-12 text-left" href="#"><span class="glyphicon glyphicon-book"></span> {{str_limit($category->name, $limit='10', $end='...')}} History <span class="badge right">0</span></a>
			<a role="button" class="btn btn-default col-lg-12 text-left" href="#"><span class="glyphicon glyphicon-trash"></span> Deleted {{str_limit($category->name, $limit='10', $end='...')}}s <span class="badge right">0</span></a>
			<a role="button" class="btn btn-default col-lg-12 text-left" href="#" data-toggle="modal" data-target="#deleteCategory"><span class="glyphicon glyphicon-remove"></span> Delete {{ str_limit($category->name, $limit='10', $end='...') }}</a>
			<a role="button" class="btn btn-default col-lg-12 text-left" href="{{ route('home')  }}"><span class="glyphicon glyphicon-chevron-left"></span> Return to home</a>
		</div>
	</div>

     <div class="col-lg-9 col-md-offset-center-2">
        <div class="panel panel-default col-lg-12" style="border-color: #ccc;">
           <div class="page-header">
                <h3>{{ $category->name  }} Category</h3>
           </div>
           <br>
           <table id="devices" class="table">

           </table>
           <br/><br/>
        </div>
    </div>
 </div>


<!-- Delete Contact Modal -->
<div class="modal fade" name="deleteCategory" id="deleteCategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabels" aria-hidden="true">
	{!! Form::open(['method'=>'DELETE', 'route'=>['category.destroy', $category->slug]]) !!}
	<div class="modal-dialog">
		<div class="modal-content ">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabels">Delete {{ $category->name }}</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12 center col-lg-offset-1">
							<div class="form-group">
								<label class="col-md-12 control-label">Are you sure you want to delete [ Category :: {{ $category->name }} ]</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<br/>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-danger">Delete</button>
			</div>
		</div>
	</div>
	{!! Form::close() !!}
</div>
@stop

@section('script')
<script type="text/javascript">
	$.getJSON("{{ route('fetch_devices', [$category->id]) }}", function(data) {
		$('#devices').dataTable({
			"aaData": data,
			"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
			"aaSorting": [[ 5, 'desc' ]],
			"oLanguage": {
				"sLengthMenu": "No. of Devices _MENU_",
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
				{"sTitle": "Recent Update", "width":"20%" ,"mDataProp": "updated_at"}

			],
			"aoColumnDefs":
			[
				//FORMAT THE VALUES THAT IS DISPLAYED ON mDataProp
				//ID

				{ "bSortable": false, "aTargets": [ 0 ] },
				{
					"aTargets": [ 0 ], // Column to target
					"mRender": function ( data, type, full ) {
					var url = '{{ route('owner.show', ":slug") }}';
						url = url.replace(':slug', full["owner_slug"]);
						// 'full' is the row's data object, and 'data' is this column's data
						// e.g. 'full[0]' is the comic id, and 'data' is the comic title
						return "<a href='"+url+"' class='size-14 text-left'>" + data + "</a>";
					}
				},
				//CATEGORY SLUG
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
				{
					"aTargets": [ 5 ], // Column to target
					"mRender": function ( data, type, full ) {
					// 'full' is the row's data object, and 'data' is this column's data
					// e.g. 'full[0]' is the comic id, and 'data' is the comic title
					return '<label class="text-center size-14"> <!--' + full["updated_at_2"]  + '-->' + data + ' </label>';
					}
				}
			]
		});
	$('div.dataTables_filter input').attr('placeholder', 'Filter Devices');
});
</script>
@stop