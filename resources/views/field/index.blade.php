@extends('app')

@section('header')
    @include('util.m-topbar')
    <div class="container">
		<div class="col-lg-12">
			<ol class="breadcrumb" style=" margin-left: 1.5rem; ">
				<li><label>Inventory</label>
				<li><a href="{{ route('home') }}" class="active">Home</a></li>
				<li><label>Device Information</label>
			</ol>
		</div>
	</div>
@stop

@section('content')
<div class="container">
    <div class="col-lg-3">
		<div class="btn-group-vertical col-lg-12" role="group">
			<a role="button" class="btn btn-default col-lg-12 text-left" href="{{ route('home')  }}"><span class="glyphicon glyphicon-chevron-left"></span> Return to home</a>
		</div>
	</div>

    <div class="col-lg-9 col-md-offset-center-2">
        <div class="panel panel-default col-lg-12" style="border-color: #ccc;">
        <br/>
			@if (Request::has('filter') || Request::has('categoryLabel'))
				<div class="alert alert-success" role="alert">Entered Query: "{{ Request::get('filter') }}" Filter Result: {{ $information->firstItem() }} to {{ $information->lastItem() }} out of {{$information->total()}} {{ Request::get('categoryLabel') }}</div>
			@endif
			<form class="form-horizontal">
				<div class="form-group">
					<label class="left" for="" style="margin-top: 0.5rem; margin-left: 1.5rem;">Filter By: </label>
					<select name="categoryLabel" class="btn btn-default left" style="margin-left: 1.5rem;">
						@foreach($fields as $field)
							<option value="{{ $field->category_label }}">{{ $field->category_label }}</option>
						@endforeach
					</select>
					<div class="col-lg-4">
						<input type="search" class="form-control" id="filter" name="filter" placeholder="Enter your query">
					</div>
					<button type="submit" class="btn btn-default">Filter</button>
					<a role="button" class="btn btn-default" href="{{ route('information.index') }}">Clear filter</a>
				</div>
			</form>
			<hr/>
			@if (Request::has('filter') || Request::has('categoryLabel'))
				<table class="table table-hover">
					<thead>
						<tr>
							<td>Category</td>
							<td>Device</td>
							<td>Device Information</td>
							<td>Category Label</td>
						</tr>
					</thead>
					<tbody>
						@foreach ($information as $info)
						<tr>
							<td>
								<a href="{{ route('category.show', $info->category_slug) }}">{{ $info->category_name }}</a>
							</td>
							<td>
								<a href="{{ route('device.edit', $info->device_slug) }}">{{ $info->device_name }}</a>
							</td>
							<td>
								{!! $info->value == '' ? "<code>information not provided</code>" : "<kbd>".$info->value."</kbd>" !!}
							</td>
							<td>
								<kbd>{{ $info->category_label }}</kbd>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>

				@if (Request::has('filter') || Request::has('categoryLabel'))
				<form class="form-inline">
					<div class="form-group left" style=" margin-top: 2.55rem; ">
						<label class="" for="">Showing {{ count($information) == 0 ? count($information) . ' to '.  $information->lastItem() . ' out of ' . $information->total() : $information->firstItem() . ' to ' . $information->lastItem() . ' out of ' . $information->total() . ' ' . Request::get('categoryLabel')  }}</label>
					</div>
					<div class="form-group right">
						<span class="right">{!! $information->appends(['filter' => Request::get('filter'), 'categoryLabel' => Request::get('categoryLabel')])->render() !!}</span>
					</div>
				</form>
				@else
					<form class="form-inline">
						<div class="form-group left" style=" margin-top: 2.55rem; ">
							<label class="" for="">Showing {!! $information->firstItem() !!} to {!! $information->lastItem() !!} out of {!! $information->total() !!} Information</label>
						</div>
						<div class="form-group right">
							<span class="right">{!! $information->appends(['filter' => Request::get('filter'), 'categoryLabel' => Request::get('categoryLabel')])->render() !!}</span>
						</div>
					</form>
				@endif
			@endif
        </div>
    </div>
 </div>
@stop