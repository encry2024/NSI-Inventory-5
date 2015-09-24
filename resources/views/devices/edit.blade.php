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
                    <li><a role="button" class=" col-lg-12 text-left size-13" href="#"  data-toggle='modal' data-target='#noteStore' ><span class="glyphicon glyphicon-pencil"></span> Make a Note</a></li>
                    @if ($device->owner_id != 0)
                        <li><a role="button" class=" col-lg-12 text-left size-13" data-toggle='modal' data-target='#disassociate_device' href="#"><span class="glyphicon glyphicon-remove"></span> Disassociate</a></li>
                    @else
                        <li><a role="button" id="grp" class=" col-lg-12 text-left size-13 cli" data-toggle='modal' data-target='#associate_device' href="#"><span class="glyphicon glyphicon-tag"></span> Associate</a></li>
                    @endif
                    <li><a role="button" class=" col-lg-12 text-left size-13" href="#" data-toggle='modal' data-target='#change_status'><span class="glyphicon glyphicon-repeat"></span> Change Status</a></li>
                    <li><a role="button" class=" col-lg-12 text-left size-13" href="#" data-toggle="modal" data-target="#deleteDevice"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
                </ul>
            </div>
            <li role="presentation" class="active"><a href="#">Basic Details</a></li>
            <li role="presentation"><a href="{{ route('dInfo', $device->slug) }}">Information</a></li>
            <li role="presentation"><a href=" {{ route('dn', $device->slug) }} ">Notes</a></li>
            <li role="presentation"><a href=" {{ route('ds', $device->slug) }}">Status</a></li>
            <li role="presentation"><a href=" {{ route('dadh', $device->slug) }}">Ownership</a></li>
        </ul>
        <br/>

        <div class="col-lg-12">
            <form class="form-horizontal">
                <div class="form-group">
                    <label class="col-lg-2 control-label">Device:</label>
                    <div class="col-lg-10">
                        <label class="control-label" style="font-weight: normal;">{{ $device->name }}</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Category:</label>
                    <div class="col-lg-10">
                        <label class="control-label" style="font-weight: normal;">{{ $device->category->name }}</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Owner:</label>
                    <div class="col-lg-10">
                        @if ($device->owner_id != 0)
                            <label class="control-label" style="font-weight: normal;">{{ $device->owner->fullName() }}</label>
                        @else
                            <label class="control-label" style="font-weight: normal;">{{ $device->availability }}</label>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Status:</label>
                    <div class="col-lg-10">
                    @if($device->status_id != '' || $device->status_id != 0)
                        <label class="control-label" style="font-weight: normal;">{{ $device->status->status }}</label>
                    @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Note:</label>
                    <div class="col-lg-10">
                    @if (count($note) > 0)
                        <textarea type="text" class="form-control" id="device_note" name="device_note" placeholder="Note" value="{{ $note->note }}" readOnly>{{ $note->note }}</textarea>
                    @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- EDIT INFORMATION MODAL --}}
 <div class="modal fade" id="editInfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    {!! Form::open(['route'=>['information/update']]) !!}
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit {{ $device->name }} <span id="category_label"></span> </h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 center col-lg-offset-1">
                            <div class="form-group">
                                <label class="col-md-4 control-label" id="catgry_label"></label>
                                <div class="col-md-6">
                                    <input type="string" class="form-control" id="info_value" name="value" value="{{ old('value') }}">
                                </div>
                            </div>
                            <br/><br/>
                        </div>
                    </div>
                </div>
            </div>
            <br/>
            <div class="modal-footer">
                {!! Form::hidden('inf_id', '', ['id'=>'info_id']) !!}
                {!! Form::hidden('device_id', $device->id) !!}
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>

{{-- CREATE NOTE MODAL --}}
 <div class="modal fade" id="noteStore" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    {!! Form::open(['route'=>['note.store']]) !!}
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><span><a class="glyphicon glyphicon-pencil" href=""></a></span> Create a Note <span id="category_label"></span> </h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 center col-lg-offset-1">
                            <div class="form-group">
                                <label class="col-md-4 control-label"><i>Your Note*</i></label>
                                <div class="col-md-6">
                                    <input type="string" class="form-control" id="" name="note" value="{{ old('value') }}">
                                </div>
                            </div>
                            <br/><br/>
                        </div>
                    </div>
                </div>
            </div>
            <br/>
            <div class="modal-footer">
                {!! Form::hidden('user_id', Auth::user()->id) !!}
                {!! Form::hidden('device_id', $device->id) !!}
                @if (count($note) > 0)
                {!! Form::hidden('note_id', $note->id) !!}
                @endif
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>

{{-- ASSOCIATE DEVICE MODAL --}}
 <div class="modal fade" id="associate_device" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    {!! Form::open(['route' => ['device_associate', $device->id]]) !!}
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Associate {{ $device->name }} </h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 center col-lg-offset-1">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Owners</label>
                                <div class="col-md-6">
                                    <div id="groupList" name="owner_id"></div>
                                </div>
                            </div>
                            <br/><br/>
                        </div>
                    </div>
                </div>
            </div>
            <br/>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Associate Device</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>

{{-- DISASSOCIATE DEVICE MODAL --}}
 <div class="modal fade" id="disassociate_device" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    {!! Form::open(['route' => ['device_disassociate', $device->id]]) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Disassociate {{ $device->name }} </h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 center col-lg-offset-1">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Owner</label>
                                <div class="col-md-6">
                                    @if ($device->owner_id != 0)
                                    <label for="">{{ $device->owner->fullName() }}</label>
                                    @endif
                                </div>
                            </div>
                            <br/><br/>
                        </div>
                    </div>
                </div>
            </div>
            <br/>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Disassociate Device</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>

{{-- CHANGE STATUS MODAL --}}
 <div class="modal fade" id="change_status" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    {!! Form::open(['route' => ['change_status', $device->id]]) !!}
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Change {{ $device->name }} Status</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 center col-lg-offset-1">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Owners</label>
                                <div class="col-md-6">
                                    <div id="statusList" name="status_id"></div>
                                </div>
                            </div>
                            <br/><br/>
                        </div>
                    </div>
                </div>
            </div>
            <br/>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Change Status</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>

<!-- DELETE DEVICE MODAL -->
<div class="modal fade" name="deleteDevice" id="deleteDevice" tabindex="-1" role="dialog" aria-labelledby="myModalLabels" aria-hidden="true">
    {!! Form::open(['method'=>'DELETE', 'route'=>['device.destroy', $device->slug]]) !!}
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabels">Delete {{ $device->name }}</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 center col-lg-offset-1">
                            <div class="form-group">
                                <label class="col-md-12 control-label">Are you sure you want to delete [ Device :: {{ $device->name }} ]</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br/>
            <div class="modal-footer">
                {!! Form::hidden('category_slug', $device->category->slug) !!}
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@stop

@section('script')
<script>
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

    function editInfo(inf_id, value, categry_label) {
        document.getElementById("info_id").value = inf_id;
        document.getElementById('info_value').value = value;
        document.getElementById('category_label').innerHTML = categry_label;
        document.getElementById('catgry_label').innerHTML = categry_label;
    }

    $('body').popover({ selector: '[data-popover]', trigger: 'hover', placement: 'right', delay: {show: 50, hide: 50}});

    $.getJSON("{{ route('fetchAvailableOwners', [$device->category->id]) }}", function(data) {
        var datalist = [];
        console.log(data);

        $.each(data, function(key, val) {
            datalist.push({value: val.id, text: val.name});
        });

        $('#groupList').multilist({
            single: true,
            labelText: 'Select Group',
            datalist: datalist,
            enableSearch: true
        });
    });

    $.getJSON("{{ route('fetch_all_status') }}", function(data) {
        var datalist = [];
        console.log(data);

        $.each(data, function(key, val) {
            datalist.push({value: val.id, text: val.status});
        });

        $('#statusList').multilist({
            single: true,
            labelText: 'Select Status',
            datalist: datalist,
            enableSearch: true
        });
    });
</script>
@stop