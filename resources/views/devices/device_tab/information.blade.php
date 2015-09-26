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
            <div class="btn-group right">
                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('edit_info', $device->slug) }}" class="size-13"><span class="glyphicon glyphicon-pencil"></span>&nbsp;Edit</a></li>
                </ul>
            </div>
            <li role="presentation"><a href="{{ route('device.edit', $device->slug) }}">Basic Details</a></li>
            <li role="presentation" class="active"><a href="#">Information</a></li>
            <li role="presentation"><a href=" {{ route('dn', $device->slug) }} ">Notes</a></li>
            <li role="presentation"><a href=" {{ route('ds', $device->slug) }}">Status</a></li>
            <li role="presentation"><a href=" {{ route('dadh', $device->slug) }}">Ownership</a></li>
        </ul>
        <br/>

        <div class="col-lg-12">
            <form class="form-horizontal">
                @foreach ($device->information as $key=>$device_field)
                <div class="form-group">
                    <label class="col-lg-2 control-label" style="">{{ $device_field->field->category_label }}:</label>
                    <div class="col-lg-10">
                    <label class="control-label" style="font-weight: normal;"><i>{{ $device_field->value }}</i></label>
                    </div>
                </div>
                @endforeach
            </form>
        </div>
    </div>
</div>
@stop

@section('script')
<script>
</script>
@stop