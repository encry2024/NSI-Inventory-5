@extends('app')

@section('header')
	@include('util.m-topbar')
	<div class="container">
		<div class="col-lg-12">
			<ol class="breadcrumb" style=" margin-left: 1.5rem; ">
				<li><label>Inventory</label>
				<li><a href="{{ route('home') }}" class="active">Home</a></li>
				<li><label>Deleted Categories</label>
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
				<h3>Deleted Categories</h3>
			</div>
			<br>
			<table id="deleted_category"></table>
			<br><br>
			<br>
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

$('body').popover({ selector: '[data-popover]', trigger: 'hover', placement: 'left', delay: {show: 50, hide: 50}});


$.getJSON("{{ route('d_c') }}", function(data) {
	$('#deleted_category').dataTable({
		"aaData": data,
		"lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
		"oLanguage": {
			"sLengthMenu": "# of Delete Category _MENU_",
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
			{"sTitle": "Category", "mDataProp": "category_name"},
			{"sTitle": "Date Deleted", "mDataProp": "deleted_at"}
		],
		"aoColumnDefs":
		[
			// REDIRECT TO HEADSET PROFILE
			{
				"aTargets": [ 0 ], // Column to target
				"mRender": function ( data, type, full ) {
					var icon = "";
					return "<a href='#' class='size-14 text-left' data-popover='true' data-html='true' data-trigger='hover' data-content='<a title=&apos;Restore " + data + "&apos;><span class=&quot;glyphicon glyphicon-import&quot;></span></a>'>" + data + "</a>";
				}
			},
			{
				"aTargets": [ 1 ], // Column to target
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