@extends('app')

@section('header')
	@include('util.m-topbar')
	<div class="container">
		<div class="col-lg-12">
			<ol class="breadcrumb" style=" margin-left: 1.5rem; ">
				<li><label>Inventory</label>
				<li><a href="{{ route('home') }}" class="active">Home</a></li>
				<li><label>{{ $category->name }}</label></li>
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
		<div class="btn-group-vertical col-lg-12" role="group" id="btnGrp">
			<a role="button" class="btn btn-default col-lg-12 text-left" href="{{ route('create_device', [$category->slug])  }}"><span class="glyphicon glyphicon-plus"></span> Create {{ str_limit($category->name, $limit='10', $end='...') }}</a>
			<a role="button" class="btn btn-default col-lg-12 text-left" href="{{ route('category.edit', [$category->slug])  }}"><span class="glyphicon glyphicon-info-sign"></span> {{str_limit($category->name, $limit='10', $end='...')}} Profile</a>
			<a role="button" class="btn btn-default col-lg-12 text-left" href="#"><span class="glyphicon glyphicon-trash"></span> Deleted {{str_limit($category->name, $limit='10', $end='...')}} <span class="badge right"> {{ count($deleted_device)}} </span></a>
			<a href="{{ route('ch', [$category->slug]) }}" class="btn btn-default col-lg-12 text-left" role="button"><span class="glyphicon glyphicon-book"></span> Associate & Dissociate Log</a>
			<a href="{{ route('sh', [$category->slug]) }}" class="btn btn-default col-lg-12 text-left" role="button"><span class="glyphicon glyphicon-book"></span> Device Statuses Log</a>
			<a role="button" class="btn btn-default col-lg-12 text-left" href="{{ route('home')  }}"><span class="glyphicon glyphicon-chevron-left"></span> Return to home</a>
		</div>
	</div>

	<div class="col-lg-9 col-md-offset-center-2">
		<div class="panel panel-default col-lg-12" style="border-color: #ccc;">
			<h3>{{ $category->name  }} Category</h3>
			<hr/>
			@if (Request::has('filter'))
				<div class="alert alert-success" role="alert">Entered Query: "{{ Request::get('filter') }}" Filter Result: {{ $devices->firstItem() }} to {{ $devices->lastItem() }} out of {{$devices->total()}} Devices</div>
			@endif
			<form class="form-horizontal">
				<div class="form-group">
					<div class="col-lg-4">
						<input type="search" class="form-control" id="filter" name="filter" placeholder="Search Device" style="margin-left: 38.5rem;">
					</div>
					<button type="submit" class="btn btn-default" style="margin-left: 38rem;">Filter</button>
					<a role="button" class="btn btn-default" href="{{ route('category.show', $category->slug) }}" style="margin-left: 0rem !important;">Clear filter</a>
				</div>
			</form>
			<table class="table table-condensed">
				<thead>
					<tr>
						<td>Owner</td>
						<td>Description</td>
						<td>Brand</td>
						<td>NSI Tag</td>
						<td>Status</td>
						<td>Recent Updates</td>
					</tr>
				</thead>
				<tbody>
					@foreach ($devices as $device)
						@foreach ($device->information as $device_information)
							@if ($device_information->field->category_label == "Brand")
								<?php $brand = $device_information->value ?>
							@endif

							@if ($device_information->field->category_label == "NSI Tag")
								<?php $tag = $device_information->value ?>
							@endif
						@endforeach
						<tr>
							<td>
								{!! $device->owner_id!=0 ? '<a class="label label-danger" href="'. route('owner.show', $device->owner->slug) .'">'. $device->owner->fullName() .'</a>' : '<span class="label label-success">Ready to Deploy</span>' !!}
							</td>
							<td>
								<a href="{{ route('device.edit', $device->slug) }}">{{ $device->name }}</a>
							</td>
							<td>
								{!! $brand != '' ? '<label for="">'. $brand .'</label>' : '<label class="label label-warning">Not Provided</label>' !!}
							</td>
							<td>
								<label for="">{{ $tag }}</label>
							</td>
							<td>
								<label for="">{{ $device->status->status }}</label>
							</td>
							<td>
								<label title="{{ date('F d, Y h:i A', strtotime($category->updated_at)) }}">{{ date('F d, Y h:i A', strtotime($category->updated_at)) }}</label>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<form class="form-inline">
				<div class="form-group left" style=" margin-top: 2.55rem; ">
					<label class="" for="">Showing {!! $devices->firstItem() !!} to {!! $devices->lastItem() !!} out of {!! $devices->total() !!} Categories</label>
				</div>
				<div class="form-group right">
					<span class="right">{!! $devices->appends(['filter' => Request::get('filter')])->render() !!}</span>
				</div>
			</form>
		</div>
	</div>
 </div>


{{-- Delete Contact Modal --}}
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