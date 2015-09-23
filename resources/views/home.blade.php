@extends('app')

@section('header')
    @include('util.m-topbar')
    <div class="container">
		<div class="col-lg-12">
			<ol class="breadcrumb" style=" margin-left: 1.5rem; ">
				<li><label>Inventory</label>
				<li>Home</li>
			</ol>
		</div>
	</div>
@stop

@section('content')
<div class="container">
	@include('util.m-sidebar')
    <div class="col-lg-9 col-md-offset-center-2">
        <div class="panel panel-default col-lg-12" style="border-color: #ccc;">
        <br/>
			@if (Request::has('filter'))
				<div class="alert alert-success" role="alert">Entered Query: "{{ Request::get('filter') }}" Filter Result: {{ $categories->firstItem() }} to {{ $categories->lastItem() }} out of {{$categories->total()}} Categories</div>
			@endif
			<form class="form-horizontal">
				<div class="form-group">
					<div class="col-lg-4">
						<input type="search" class="form-control" id="filter" name="filter" placeholder="Enter your query" style="margin-left: 38.5rem;" autocomplete="off">
					</div>
					<button type="submit" class="btn btn-default" style="margin-left: 38rem;">Filter</button>
					<a role="button" class="btn btn-default" href="{{ route('home') }}" style="margin-left: 0rem !important;">Clear filter</a>
				</div>
			</form>
			<hr/>
			<table class="table table-condensed">
				<thead>
					<tr>
						<td>Category</td>
						<td>No. of Device</td>
						<td>No. of Associates</td>
						<td>No. of Defectives</td>
						<td>No. of Available</td>
						<td>Recent Updates</td>
					</tr>
				</thead>
				<tbody>
					@foreach ($categories as $category)
					<tr>

						<td>
							<a class="control-label" href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a>
						</td>
						<td>
							<label class="control-label">{{ count($category->devices) }}</label>
						</td>
						<td>
							<a class="control-label" href="{{ route('assoc_dev', $category->slug) }}">{{ count($category->associated_devices()) }}</a>
						</td>
						<td>
							<a class="control-label" href="{{ route('defect_device', $category->slug) }}">{{ count($category->def_device()) }}</a>
						</td>
						<td>
							<a class="control-label" href="{{ route('avail_device', $category->slug) }}">{{ count($category->av_device()) }}</a>
						</td>
						<td>
							<label class="control-label" title="{{ date('F d, Y h:i A', strtotime($category->updated_at)) }}">{{ date('M d, Y h:i A', strtotime($category->updated_at)) }}</label>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>

			@if (Request::has('filter'))
			<form class="form-inline">
				<div class="form-group left" style=" margin-top: 2.55rem; ">
					<label class="" for="">Showing {{ count($categories) == 0 ? count($categories) . ' to '.  $categories->lastItem() . ' out of ' . $categories->total() : $categories->firstItem() . ' to ' . $categories->lastItem() . ' out of ' . $categories->total() . ' ' . Request::get('categoryLabel')  }}</label>
				</div>
				<div class="form-group right">
					<span class="right">{!! $categories->appends(['filter' => Request::get('filter')])->render() !!}</span>
				</div>
			</form>
			@else
				<form class="form-inline">
					<div class="form-group left" style=" margin-top: 2.55rem; ">
						<label class="" for="">Showing {!! $categories->firstItem() !!} to {!! $categories->lastItem() !!} out of {!! $categories->total() !!} Categories</label>
					</div>
					<div class="form-group right">
						<span class="right">{!! $categories->appends(['filter' => Request::get('filter')])->render() !!}</span>
					</div>
				</form>
			@endif
			<br/><br/><br/>
        </div>
    </div>
 </div>
@stop
