@extends('app')

@section('header')
	@include('util.m-topbar')
	<div class="container">
		<div class="col-lg-12">
			<ol class="breadcrumb" style=" margin-left: 1.5rem; ">
				<li><label>Inventory</label>
				<li><a href="{{ route('home') }}" class="active">Home</a></li>
				<li><a href="{{ route('category.show', [$category->slug])  }}" class="active">{{ $category->name }}</a></li>
				<li><label>Create {{ $category->name }}</label>
			</ol>
			@if (Session::has('success_msg'))
				<div class="alert alert-success" role="alert" style=" margin-left: 1.5rem; ">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					{{ Session::get('success_msg')  }}
				</div>
			@endif
		</div>
	</div>
@stop

@section('content')
<div class="container">
	{!! Form::open(['route' => ['device.store']]) !!}
	<div class="col-lg-3">
		<div class="btn-group-vertical col-lg-12" role="group">
			<button class="btn btn-default col-lg-12 text-left"><span class="glyphicon glyphicon-plus"></span> Create {{ $category->name }}</button>
			<a href="{{ route('category.show', [$category->slug])  }}" class="btn btn-default text-left" role="button"><span class="glyphicon glyphicon-chevron-left"></span> Back to {{ $category->name }}</a>
		</div>
	</div>

    <div class="col-lg-9 col-md-offset-center-2">
        <div class="panel panel-default col-lg-12" style="border-color: #ccc;">
           	<div class="page-header">
                <h3>Create {{ $category->name }} Device</h3>
           	</div>
           	<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            	<label class="col-md-4 control-label">Device Description:</label>
                <div class="col-md-6">
                    <input type="string" class="form-control" name="name" value="{{ old('name') }}">
                    {!! $errors->first('name', '<span class="help-block">:message</span>')  !!}
                </div>
                <br/><br/>
			</div>
			<div></div>
			<br/>

			<div class="form-group">
				@foreach ($category->fields as $category_field)
					<label class="col-md-4 control-label">{{ $category_field->category_label }}:</label>
					<div class="col-md-6">
						<input type="string" class="form-control" name="field-{{ $category_field->id }}" value="{{ old('name') }}">
					</div>
					<br/><br/>
				@endforeach
				<br/>
			</div>
			{!! Form::hidden('category_id', $category->id) !!}
			</div>
			<br/><br/>
		</div>
	</div>
	{!! Form::close() !!}
</div>
@stop

@section('script')
@stop