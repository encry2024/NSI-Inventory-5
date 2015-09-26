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
            @if (Session::has('message'))
                <div class="alert {{ Session::get('message_label') }}" role="alert" style=" margin-left: 1.5rem; ">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ Session::get('message')  }}
                </div>
            @endif
        </div>
    </div>
@stop

@section('content')
<div class="container">
    <div class="col-lg-3">
        <div class="btn-group-vertical col-lg-12" role="group">
            <a role="button" class="btn btn-default col-lg-12 text-left" href="{{ route('device.edit', [$device->slug])  }}"><span class="glyphicon glyphicon-chevron-left"></span> Back</a>
        </div>
    </div>

    <div class="col-lg-9 col-md-offset-center-2" >
        <div class="col-lg-12">
            <h3 style="margin-top: 0.5rem;">Update {{ $device->name }}</h3>
            <br/>
            <form class="form-horizontal" action="{{ route('update_info', $device->slug) }}" method="POST">
                <input type='hidden' name='_token' value='{{{ csrf_token() }}}'>
                @foreach ($device->information as $key=>$device_field)
                <div class="form-group">
                    <label class="col-lg-2 control-label" style="">{{ $device_field->field->category_label }}:</label>
                    <div class="col-lg-5">
                    @if ($device_field->field->category_label == "Date Purchased")
                    <div class="input-group">
                        <input class="form-control date" name="info-{{ $device_field->id }}" style="text-size: 13px !important; font-weight: normal;" value="{{ $device_field->value }}">
                        <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                    </div>
                    @else
                    <input class="form-control" name="info-{{ $device_field->id }}" style="text-size: 13px !important; font-weight: normal;" value="{{ $device_field->value }}">
                    @endif
                    </div>
                </div>
                @endforeach
                <a class="" href="{{ route('device.edit', [$device->slug])  }}" style="margin-left: 13.5rem;">Cancel</a>
                <button class="btn btn-success" type="submit" style="margin-left: 1rem;"><span class="glyphicon glyphicon-ok"></span> Save</button>
            </form>
        </div>
    </div>
</div>
@stop

@section('script')
<script type="text/javascript">
    $('.date').datepicker({
        format: "dd-mm-yyyy",
        autoclose: true
    });
</script>
@stop