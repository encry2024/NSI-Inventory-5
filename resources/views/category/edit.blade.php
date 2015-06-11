@extends('app')

@section('header')
	@include('util.m-topbar')
	<div class="container">
		<div class="col-lg-12">
			<ol class="breadcrumb" style=" margin-left: 1.5rem; ">
				<li><label>Inventory</label>
				<li><a href="{{ route('home') }}" class="active col-lg-p">Home</a></li>
				<li><a href="{{ route('category.show', [$category->slug]) }}" class="active">{{ $category->name }}</a></li>
				<li><label>Profile</label></li>
			</ol>
		</div>
	</div>
@stop

@section('content')
<div class="container">
	<div class="col-lg-3">
    	<div class="btn-group-vertical col-lg-12" style="width: 100%;" role="group">
    		<a href="{{ route('category.show', [$category->slug]) }}" class="text-left btn btn-default col-lg-12">Back</a>
    	</div>
    </div>

	<div class="col-lg-9">
		<div class="panel panel-default col-lg-12" style="border-color: #ccc;">
			<div class="panel-header" id="ctg_name">
				<h3><span id="ctgyname">{{ $category->name }}</span> <a href="" class="size-14" data-placement="right" id="chnge_name" data-toggle="popover" data-container='body' data-title="<label style=' margin-bottom: 0rem; ' >Change <span id='ctg_ch'>{{ $category->name }}</span> name</label>" data-html="true" data-trigger="click"
				data-content="
					<form id='frm_update' class='updte'>
						<input type='hidden' name='_method' value='PATCH'>

						<label for='info_value' style=' margin-top: 1rem; '>Category:</label><input type='string' style='width: 25rem;margin-left: 8rem;margin-top: -3rem; margin-bottom: 2.3rem;' class='form-control' id='category_name' name='category_name' value='{{ $category->name }}'>
						<div class='sep-1'></div>
						<button type='button' class='btn btn-default right' data-dismiss='popover'>Close</button>
						<button id='submit' class='right btn btn-primary update' style='margin-right:0.3rem;' type='submit'>Update</button>

						<br><br>
					</form>
				"
				><span class="glyphicon glyphicon-edit"{{-- style=" top: -1rem; "--}}></span></a> </h3>
			</div>
		</div>

		<div class="panel panel-default col-lg-12">

		</div>

		<div class="panel panel-default col-lg-12" style="border-color: #ccc;">
			<div class="page-header">
				<h3><span class="glyphicon glyphicon-tag"></span> Fields</h3>
			</div>
			<form class="form-inline">

			@foreach ($category->fields as $key=>$category_field)
			<div class="form-group" style="margin-left: -1.5rem;">
				<div class="col-lg-12">
					<div class="alert alert-success col-lg-12" style="width: 105%; height: 5rem;" role="alert">
						<button tabindex="{{ ++$key }}" id="dlte_field" type="button" class="close" data-popover="true" data-container='body' data-title="<label style=' margin-bottom: 0rem; '>Delete {{ $category_field->category_label }}</label>" data-html="true" data-trigger="click" data-content="

						<form style='width:100%;' method='POST' action=&quot;{{ route('field.destroy', [$category_field->id]) }}&quot;>
							<input type='hidden' name='_method' value='delete'>
							<input name='_token' type='hidden' value='{{ csrf_token() }}'>

							<input type='hidden' name='field_id' value='{{ $category_field->id }}'>
							<label>Are you sure you want to delete <span style='color: #c9302c;'>Field :: {{ $category_field->category_label }}</span>?</label>
							<div class='sep-1'></div>
							<button type='button' class='btn btn-default right' data-dismiss='popover'>Close</button>
							<button type='submit' style='margin-right:0.3rem;' class='btn btn-danger right'>Delete</button>

							<br><br>
						</form>
						"> <span class="glyphicon glyphicon-trash size-12" aria-hidden="true" style="margin-left: 0.5rem;"> </span></button>
						<button tabindex="{{ ++$key }}" id="update_field" type="button" class="close" data-container="body" data-popover="true" data-title="<label style=' margin-bottom: 0rem; '>Update Field</label>" data-html="true" data-trigger="click" data-content="
						<body>
						<form method='POST' action=&quot;{{ route('field.update', [$category_field->id]) }}&quot;>
						<input type='hidden' name='_method' value='PATCH'>
						<input name='_token' type='hidden' value='{{ csrf_token() }}'>

						<label for='info_value' style=' margin-top: 1rem; '>Field:</label><input type='string' style='width: 25rem;margin-left: 5rem;margin-top: -3rem; margin-bottom: 2.3rem;' class='form-control' id='info_value' name='field' value='{{ $category_field->category_label }}'>
						<div class='sep-1'></div>
						<button type='button' class='btn btn-default right' data-dismiss='popover'>Close</button>
						<button class='right btn btn-success' style='margin-right:0.3rem;' type='submit'><span class='glyphicon glyphicon-ok'></span> Update</button>

						<br><br>
						</form>
						</body>
						"
						> <span class="glyphicon glyphicon-pencil size-12" aria-hidden="true" style="margin-left: 0.5rem;"> </span></button>
						<label style=" margin-top: 0.2rem; ">{{ $category_field->category_label }}</label>
					</div>
				</div>
			</div>
			@endforeach
			</form>
		</div>
	</div>
</div>

{{-- UNUSED FUNCTIONS --}}
{{--onclick="untagRecipient({{$category_field->id }}, '{{ $category_field->category_label }}')"--}}
@stop


@section('script')
<script>
		$(document).on('submit', 'form', function() {

			var url                = "{{ route('category.update', [$category->slug]) }}";
			var submit 			   = $('#submit');
			var $post              = {};
			$post.category_name    = $("input[name=category_name]").val();
			$post.token            = $("input[name=_token]").val();
			var methodType         = $("input[name=_method]").val();
			//e.preventDefault();
			$.ajax({
				type: methodType,
				url: url,
				data: $post,
				cache: false,
				beforeSend: function() {;
					submit.html('Updating....'); // change submit button text
					submit.removeClass('before');
					submit.addClass('disabled');
				},
				success: function(data){
					submit.removeClass('disabled');
					submit.html('Update');
					$("#ctgyname").load("{{ route('f_c_cs', [$category->slug]) }}");
					$("#ctg_ch").load("{{ route('f_c_cs', [$category->slug]) }}")
				}
			});

			return false;
		});
	$("document").ready(function() {
		$.ajaxSetup({
		   headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
		})



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

		$('[data-popover="true"]').popover({trigger: 'click', placement: 'top', delay: {show: 50, hide: 50}});
		$('[data-toggle="popover"]').popover({trigger: 'click', placement: 'right', delay: {show: 50, hide: 50}});

		$.fn.extend({
			popoverClosable: function (options) {
				var defaults = {
					template:
						'	<div class="popover">\
							<div class="arrow" style="left: 61.307692%; !important"></div>\
							<div class="popover-header">\
							<button type="button" class="close col-lg-push-1" data-dismiss="popover" aria-hidden="true">&times;</button>\
							<h3 class="popover-title"></h3>\
							</div>\
							<div class="popover-content"></div>\
							</div>\
						'
				};
				options = $.extend({}, defaults, options);
				var $popover_togglers = this;
				$popover_togglers.popover(options);
				$popover_togglers.on('click', function (e) {
					e.preventDefault();
					$popover_togglers.not(this).popover('hide');
				});
				$('html').on('click', '[data-dismiss="popover"]', function (e) {
					$popover_togglers.popover('hide');
				});
			}
		});

		$(function () {
			$('[data-toggle="popover"]').popoverClosable();
			$('[data-popover="true"]').popoverClosable();
		});
	});
</script>
@stop