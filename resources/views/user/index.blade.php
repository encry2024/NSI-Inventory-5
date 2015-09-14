@extends('app')

@section('header')
	@include('util.m-topbar')
@stop

@section('content')
<div class="container">
  	@include('util.m-sidebar')
  	<div class="panel panel-default col-lg-9" style="border-color: #ccc;">
		<h3>Manage Users</h3>
		<hr/>
		@if (Request::has('filter'))
			<div class="alert alert-success" role="alert">Entered Query: "{{ Request::get('filter') }}" Filter Result: {{ $users->firstItem() }} to {{ $users->lastItem() }} out of {{$users->total()}} Users</div>
		@endif
		<form class="form-horizontal">
			<div class="form-group">
				<div class="col-lg-4">
					<input type="search" class="form-control" id="filter" name="filter" placeholder="Enter your query">
				</div>
				<button type="submit" class="btn btn-default">Filter</button>
				<a role="button" class="btn btn-default" href="{{ route('user.index') }}">Clear filter</a>
			</div>
		</form>
		<form class="form-horizontal">
			@foreach ($users as $user)
			<div class="form-group">
				<div class="col-lg-1" style="width: 9%;">
					{!! $user->deleted_at == '' ? "<label class='label label-success'>ACTIVE</label>" : "<label class='label label-danger'>INACTIVE</label>" !!}
				</div>
				<div class="col-lg-8">
					<a href="{{ route('user.show', $user->id) }}">{{ $user->name }}</a>
				</div>
            </div>
			@endforeach
		</form>
		@if (Request::has('filter'))
		<form class="form-inline">
			<div class="form-group left" style=" margin-top: 2.55rem; ">
				<label class="" for="">Showing {{ count($users) == 0 ? count($users) . ' to '.  $users->lastItem() . ' out of ' . $users->total() : $users->firstItem() . ' to ' . $users->lastItem() . ' out of ' . $users->total() . ' Users'  }}</label>
			</div>
			<div class="form-group right">
				<span class="right">{!! $users->appends(Request::only('filter'))->render() !!}</span>
			</div>
		</form>
		@else
			<form class="form-inline">
				<div class="form-group left" style=" margin-top: 2.55rem; ">
					<label class="" for="">Showing {!! $users->firstItem() !!} to {!! $users->lastItem() !!} out of {!! $users->total() !!} Users</label>
				</div>
				<div class="form-group right">
					<span class="right">{!! $users->appends(Request::only('filter'))->render() !!}</span>
				</div>
			</form>
		@endif
	</div>
</div>
@stop

@section('script')
@stop