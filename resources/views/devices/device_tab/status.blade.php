@extends('app')

@section('header')
    @include('util.m-topbar')
    <div class="container">
        <div class="col-lg-12">
            <ol class="breadcrumb" style=" margin-left: 1.5rem; ">
                <li><label>Inventory</label>
                <li><a href="{{ route('home') }}" class="active">Home</a></li>
                <li><a href="{{ route('category.show', [$device->category->slug])  }}" class="active">{{ $device->category->name }}</a></li>
                <li><label>{{ $device->name }}</label>
            </ol>
            @if (Session::has('success_msg'))
                <div class="alert {{ Session::get('message_label') }}" role="alert" style=" margin-left: 1.5rem; ">
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
        <div class="btn-group-vertical col-lg-12" role="group">
            <a role="button" class="btn btn-default col-lg-12 text-left" href="{{ route('category.show', [$device->category->slug])  }}"><span class="glyphicon glyphicon-chevron-left"></span> Return to {{ $device->category->name }}</a>
        </div>
    </div>

    <div class="col-lg-9 col-md-offset-center-2" >
        <ul class="nav nav-tabs">
            <li role="presentation"><a href="{{ route('device.edit', $device->slug) }}">Basic Details</a></li>
            <li role="presentation"><a href="{{ route('dInfo', $device->slug) }}">Information</a></li>
            <li role="presentation"><a href="{{ route('dn', $device->slug) }}">Note</a></li>
            <li role="presentation" class="active"><a href="#">Status</a></li>
            <li role="presentation"><a href=" {{ route('dadh', $device->slug) }}">Ownership</a></li>
        </ul>
        <br/>
        <div class="col-lg-12">
            @if (Request::has('filter'))
                <div class="alert alert-success" role="alert">Entered Query: "{{ Request::get('filter') }}" Filter Result: {{ $statuses->firstItem() }} to {{ $statuses->lastItem() }} out of {{$statuses->total()}} Status</div>
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
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <td></td>
                        <td>Status</td>
                        <td>Description</td>
                        <td>Changed by</td>
                        <td>Date Changed</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($statuses as $status)
                        <tr>
                            <td>
                                <strong>{{ $ctr++ + $statuses->firstItem() }}</strong>
                            </td>
                            <td>
                                <label class="control-label">{{ $status->status->status }}</label>
                            </td>
                            <td>
                                <label class="control-label">{{ $status->status->description }}</label>
                            </td>
                            <td>
                                <strong>{{ $status->user->name }}</strong>
                            </td>
                            <td>
                                <strong>{{ date('F d, Y h:i A', strtotime($status->created_at)) }}</strong>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if (Request::has('filter'))
            <form class="form-inline">
                <div class="form-group left" style=" margin-top: 2.55rem; ">
                    <label class="" for="">Showing {{ count($statuses) == 0 ? count($statuses) . ' to '.  $statuses->lastItem() . ' out of ' . $statuses->total() : $statuses->firstItem() . ' to ' . $statuses->lastItem() . ' out of ' . $statuses->total() . ' Status'  }}</label>
                </div>
                <div class="form-group right">
                    <span class="right">{!! $statuses->appends(['filter' => Request::get('filter')])->render() !!}</span>
                </div>
            </form>
            @else
                <form class="form-inline">
                    <div class="form-group left" style=" margin-top: 2.55rem; ">
                        <label class="" for="">Showing {{ $statuses->firstItem() }} to {{ $statuses->lastItem() }} out of {{ $statuses->total() }} statuses</label>
                    </div>
                    <div class="form-group right">
                        <span class="right">{!! $statuses->appends(['filter' => Request::get('filter')])->render() !!}</span>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@stop

@section('script')
<script>
</script>
@stop