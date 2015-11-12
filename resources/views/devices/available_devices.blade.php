@extends('app')

@section('header')
    @include('util.m-topbar')
    <div class="container">
        <div class="col-lg-12">
            <ol class="breadcrumb" style=" margin-left: 1.5rem; ">
                <li><label>Inventory</label>
                <li><a href="{{ route('home') }}" class="active">Home</a></li>
                <li><a href="{{ route('category.show', [$category->slug])  }}" class="active">{{ $category->name }}</a></li>
                <li><label>Available Devices</label>
            </ol>
            @if (Session::has('message'))
                <div class="alert alert-success" role="alert" style=" margin-left: 1.5rem; ">
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
            <a role="button" class="btn btn-default col-lg-12 text-left" href="{{ route('home')  }}"><span class="glyphicon glyphicon-chevron-left"></span> Return to Home</a>
        </div>
    </div>

    <div class="col-lg-9 col-md-offset-center-2" >
        <div class="panel panel-default col-lg-12" style="border-color: #ccc;">
           <div class="page-header">
                <h3>Available {{ $category->name }}</h3>
           </div>
            @if (Request::has('filter'))
                <div class="alert alert-success" role="alert">Entered Query: "{{ Request::get('filter') }}" Filter Result: {{ $devices->firstItem() }} to {{ $devices->lastItem() }} out of {{$devices->total()}} Available Devices</div>
            @endif
            <form class="form-horizontal">
                <div class="form-group">
                    <div class="col-lg-4">
                        <input type="search" class="form-control" id="filter" name="filter" placeholder="Enter your query" >
                    </div>
                    <button type="submit" class="btn btn-default">Filter</button>
                    <a role="button" class="btn btn-default" href="{{ route('avail_device', $category->slug) }}" style="margin-left: 0rem !important;">Clear filter</a>
                </div>
                <a class="right btn btn-danger btn-xs disabled" onclick="getDevices()" id="delBtn" data-toggle="modal" data-target="#deleteModal"><span class="glyphicon glyphicon-trash"></span> Delete</a>
                <br/><br/>
                <table class="table table-condensed ">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td>Devices</td>
                            <td>Brand</td>
                            <td>NSI Tag</td>
                            <td>Recent Update</td>
                            <td>
                            <input type="checkbox" id="select_all" name="selector" value="Select All">
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($devices as $index => $device)
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
                                <label>{{ $count++ + $devices->firstItem() }}</label>
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
                                <label>{{ date('M d, Y h:i A', strtotime($device->updated_at)) }}</label>
                            </td>
                            <td>
                                <input type="checkbox" class="sel_dev" name="dvcs[]" value="{{ $device->name.'/'.$device->slug }}">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
            @if (Request::has('filter'))
            <form class="form-inline">
                <div class="form-group left" style=" margin-top: 2.55rem; ">
                    <label class="" for="">Showing {{ count($devices) == 0 ? count($devices) . ' to '.  $devices->lastItem() . ' out of ' . $devices->total() : $devices->firstItem() . ' to ' . $devices->lastItem() . ' out of ' . $devices->total() . ' ' . Request::get('categoryLabel')  }}</label>
                </div>
                <div class="form-group right">
                    <span class="right">{!! $devices->appends(['filter' => Request::get('filter')])->render() !!}</span>
                </div>
            </form>

            @else
                <form class="form-inline">
                    <div class="form-group left" style=" margin-top: 2.55rem; ">
                        <label class="" for="">Showing {!! $devices->firstItem() !!} to {!! $devices->lastItem() !!} out of {!! $devices->total() !!} devices</label>
                    </div>
                    <div class="form-group right">
                        <span class="right">{!! $devices->appends(['filter' => Request::get('filter')])->render() !!}</span>
                    </div>
                </form>
            @endif
            <br/><br/><br/>
           <br>
        </div>
    </div>
</div>


<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <form action="{{ route('da') }}" method="POST">
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="totalDevices"></h4>
                </div>
                <div class="modal-body">
                    <label>Are you sure you want to delete the following devices?</label>
                    <ul class="list-group" id="deviceList">
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </form>
</div>

@stop

@section('script')
<script type="text/javascript">

//Controls the behaviour of delete button & checkboxes
$(document).ready(function() { 
    var count = 0;
    var checkCount = 0;
    var singularCount = 0;

    $("#select_all").change(function() {
        $(".sel_dev").prop('checked', $(this).prop("checked"));
        count = document.querySelectorAll('.sel_dev:checked').length;
        en_dis_delBtn(count);

        
    });

    $(".sel_dev").change(function() {
        singularCount = document.querySelectorAll('.sel_dev:checked').length;

        en_dis_delBtn(singularCount);
    });

    // enable_disable_deleteButton
    function en_dis_delBtn (checkCount) {
        if (checkCount == 0) {
            $("#delBtn").addClass('disabled');
        } else {
            $("#delBtn").removeClass('disabled');
        }
    }
});

// display all selected devices
function getDevices() {
    e = $(".sel_dev:checked");
    var dev;

    $("#deviceList").empty();
    for (i = 0; i < e.length; i++) {
        dev = e[i].value.split("/");
        $("#deviceList").append("<input type='hidden' name='selectedDevices[]' value='"+dev[1]+"' /><li class='list-group-item'><input type='text' class='form-control' value='"+dev[0]+"' readOnly/></li>");
        document.getElementById("totalDevices").innerHTML = "<span class='glyphicon glyphicon-trash'></span> "+ (i+1) + " Selected Devices to be Deleted";
    }
}

</script>
@stop