@extends('app')

@section('header')
	@include('util.m-topbar')
	<div class="container">
		<div class="col-lg-12">
			<ol class="breadcrumb">
				<li><label>Inventory</label>
				<li><label>Home</label>
			</ol>
		</div>
	</div>
@stop

@section('content')
<div class="container">
	@include('util.m-sidebar')
	<div class="col-lg-9 col-md-offset-center-2">
		<div class="panel panel-default col-lg-12" style="border-color: #ccc;">
			<h3>Categories</h3>
			<hr/>
			<br><br>
			<table id="ctgy" class="table"></table>
			<br/><br/>
		</div>
	</div>
</div>
@stop

@section('script')
<script type="text/javascript">
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

	$('body').popover({ selector: '[data-popover]', trigger: 'hover', placement: 'right', delay: {show: 50, hide: 50}});

	$.getJSON("category", function(data) {
		$('#ctgy').dataTable({
			"aaData": data,
			"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
			"aaSorting": [[ 5, 'desc' ]],
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
				{"sTitle": "Category", "mDataProp": "name"},
				{"sTitle": "# Devices", "mDataProp": "total_devices"},
				{"sTitle": "# Associated", "mDataProp": "assoc_device"},
				{"sTitle": "# Available", "mDataProp": "av_device"},
				{"sTitle": "# Defective", "mDataProp": "def_device"},
				{"sTitle": "Recent Update","mDataProp": "updated_at", "sType": "string"}
			],
			"aoColumnDefs":
			[
				// REDIRECT TO HEADSET PROFILE
				{
					"aTargets": [ 0 ], // Column to target
					"mRender": function ( data, type, full ) {
						var url = '{{ route('category.show', ":slug") }}';
						url = url.replace(':slug', full["slug"]);
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
				},
				{
					"aTargets": [ 2 ], // Column to target
					"mRender": function ( data, type, full ) {
						var url = '{{ route('assoc_dev', ":slug") }}';
						url = url.replace(':slug', full['slug']);
						// 'full' is the row's data object, and 'data' is this column's data
						// e.g. 'full[0]' is the comic id, and 'data' is the comic title
						return "<a href='" + url + "' class='size-14 text-left'>" + data + "</a>";
					}
				},
				{
					"aTargets": [ 3 ], // Column to target
					"mRender": function ( data, type, full ) {
						var url = '{{ route('avail_device', ":slug") }}';
						url = url.replace(':slug', full['slug']);
						// 'full' is the row's data object, and 'data' is this column's data
						// e.g. 'full[0]' is the comic id, and 'data' is the comic title
						return "<a href='" + url + "' class='size-14 text-left'>" + data + "</a>";
					}
				},
				{
					"aTargets": [ 4 ], // Column to target
					"mRender": function ( data, type, full ) {
						var url = '{{ route('defect_device', ":slug") }}';
						url = url.replace(':slug', full['slug']);
						// 'full' is the row's data object, and 'data' is this column's data
						// e.g. 'full[0]' is the comic id, and 'data' is the comic title
						return "<a href='" + url + "' class='size-14 text-left'>" + data + "</a>";
					}
				},
				
				//CATEGORY RECENT UPDATE
				{
					"aTargets": [ 5 ], // Column to target
					"mRender": function ( data, type, full ) {
					// 'full' is the row's data object, and 'data' is this column's data
					// e.g. 'full[0]' is the comic id, and 'data' is the comic title
					return '<label class="text-center size-14"> ' + data + '</br>' + full['time_updated'] + ' </label>';
					}
				},
			]
		});
	$('div.dataTables_filter input').attr('placeholder', 'Filter Categories');
});
</script>
@stop