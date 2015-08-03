@extends('...app')

@section('header')
	@include('util.m-topbar')
	<div class="container">
		<div class="col-lg-12">
			<ol class="breadcrumb" style=" margin-left: 1.5rem; ">
				<li><label>Inventory</label>
				<li><a href="{{ route('home') }}" class="active">Home</a></li>
				<li><label>Current Associates</label>
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
            <h3>Current Associates</h3>
			<hr/>
			@if (Request::has('filter'))
                    <div class="alert alert-success" role="alert">Entered Query: "{{ Request::get('filter') }}" Filter Result: {{ $devices->firstItem() }} to {{ $devices->lastItem() }} out of {{$devices->total()}} {{ Request::get('categoryLabel') }}</div>
                @endif
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="left" for="" style="margin-top: 0.5rem; margin-left: 1.5rem;">Filter By: </label>
                        <div class="col-lg-4">
                            <input type="search" class="form-control" id="filter" name="filter" placeholder="Enter your query">
                        </div>
                        <button type="submit" class="btn btn-default">Filter</button>
                        <a role="button" class="btn btn-default" href="{{ route('all_assoc') }}">Clear filter</a>
                    </div>
                </form>
                <hr/>
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <td>Category</td>
                            <td>Device</td>
                            <td>Owner</td>
                            <td>Released By</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($devices as $device)
                        <tr>
                            <td>
                                <a href="{{ route('category.show', $device->category_slug) }}">{{ $device->category_name }}</a>
                            </td>
                            <td>
                                <a href="{{ route('device.edit', $device->device_slug) }}">{{ $device->device_name }}</a>
                            </td>
                            <td>
                                <a href="{{ route('owner.show', $device->owner_slug) }}">{{ $device->owner_fName }} {{ $device->owner_lName }}</a>
                            </td>
                            <td>
                                {!! $device->user_id != 0 ? '<a href="'. route('user.show', $device->user_id).'">'. $device->user_name .'</a>' : 'Not provided' !!}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if (Request::has('filter'))
                <form class="form-inline">
                    <div class="form-group left" style=" margin-top: 2.55rem; ">
                        <label class="" for="">Showing {{ count($devices) == 0 ? count($devices) . ' to '.  $devices->lastItem() . ' out of ' . $devices->total() : $devices->firstItem() . ' to ' . $devices->lastItem() . ' out of ' . $devices->total() . ' Associates'}}</label>
                    </div>
                    <div class="form-group right">
                        <span class="right">{!! $devices->appends(['filter' => Request::get('filter')])->render() !!}</span>
                    </div>
                </form>
                @else
                    <form class="form-inline">
                        <div class="form-group left" style=" margin-top: 2.55rem; ">
                            <label class="" for="">Showing {!! $devices->firstItem() !!} to {!! $devices->lastItem() !!} out of {!! $devices->total() !!} Associates</label>
                        </div>
                        <div class="form-group right">
                            <span class="right">{!! $devices->appends(['filter' => Request::get('filter')])->render() !!}</span>
                        </div>
                    </form>
                @endif
			<br/>
		</div>
	</div>
</div>
@stop

@section('style')
@stop