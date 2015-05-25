@extends('app')

@section('header')
	@include('util.m-topbar')
	<div class="container">
    	<div class="col-lg-12">
    		<ol class="breadcrumb" style=" margin-left: 1.5rem; ">
    			<li><label>Inventory</label>
    			<li><a href="{{ route('home') }}" class="active">Home</a></li>
    			<li><label>Statuses</label></li>
    		</ol>
    	</div>
    </div>
@stop

@section('content')
<div class="container">
    <div class="col-lg-3">
   		<div class="btn-group-vertical col-lg-12" role="group">
			<a role="button" class="btn btn-default col-lg-12 text-left" href="#" data-toggle='modal' data-target='#addStatus'><span class="glyphicon glyphicon-plus"></span> Create Status</a>
			<a role="button" class="btn btn-default col-lg-12 text-left" href="#" data-toggle='modal' data-target='#addStatus'><span class="glyphicon glyphicon-trash"></span> Deleted Status <span class="badge right">{{ count($deletedStatus) }}</span></a>
			<a role="button" class="btn btn-default col-lg-12 text-left" href="{{ route('home')  }}"><span class="glyphicon glyphicon-chevron-left"></span> Return to Home</a>
		</div>
	</div>

	<div class="col-lg-9 col-md-offset-center-2" >
		<div class="panel panel-default col-lg-12" style="border-color: #ccc;">
		   <div class="page-header">
				<h3>Statuses</h3>
		   </div>
		   @foreach ($statuses as $status)
			   <label for="note"><i> [ <span><a class="glyphicon glyphicon-pencil size-12" title="Edit Status"></a>&nbsp;&nbsp;<a class="glyphicon glyphicon-book size-12" title="Status History"></a>&nbsp;&nbsp;<a class="glyphicon glyphicon-trash size-12" onclick="deleteStatus({{ $status->id }}, '{{$status->status}}')" data-toggle='modal' data-target='#deleteStatus' title="Delete Status"></a></span> ]&nbsp;{{ $status->status }}</a> </i></label><span class="right"><label for=""><i>[ Date Created: {{ date('M d, Y h:i A', strtotime($status->created_at)) }} ]</i></label></span>
			   <div class="well well-sm" name="note"><i>{{ $status->description }}</i></div>
		   @endforeach
		</div>
	</div>
 </div>

{{-- ADD STATUS MODAL --}}
<div class="modal fade" id="addStatus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	{!! Form::open(['route'=>['status.store']]) !!}
	<div class="modal-dialog">
		<div class="modal-content ">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Status </h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12 center col-lg-offset-1">
							<div class="form-group">
								<label class="col-md-4 control-label">Status:</label>
								<div class="col-md-6">
									<input type="string" class="form-control" name="status" value="{{ old('status') }}">
								</div>
							<br/><br/>
							<div class="form-group">
								<label class="col-md-4 control-label">Description:</label>
								<div class="col-md-6">
									<textarea rows="5" cols="40" type="string" class="form-control" name="description" value="{{ old('description') }}"></textarea>
								</div>
							</div>
							<br/><br/>
						</div>
					</div>
				</div>
			</div>
			<br/>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-primary">Save changes</button>
		</div>
	</div>
</div>
{!! Form::close() !!}
</div>

@stop

@section('script')
@stop