@extends('app')

@section('header')
    @include('util.m-topbar')
    <div class="container">
		<div class="col-lg-12">
			<ol class="breadcrumb" style=" margin-left: 1.5rem; ">
				<li><label>Inventory</label>
				<li><a href="{{ route('home') }}" class="active">Home</a></li>
				<li><label>Import Categories</label>
			</ol>
		</div>
	</div>
@stop

@section('content')
<div class="container">
    <div class="col-lg-3">
		<div class="btn-group-vertical col-lg-12" role="group">
			<a role="button" class="btn btn-default col-lg-12 text-left" href="{{ route('home')  }}"><span class="glyphicon glyphicon-chevron-left"></span> Return to home</a>
		</div>
	</div>

	<div class="col-lg-9 col-md-offset-center-2">
		<div class="panel panel-default col-lg-12" style="border-color: #ccc;">
			<div class="page-header">
				<h3>Import Categories</h3>
			</div>
			{!! Form::open(['route' => ['openFile'], 'files' => true]) !!}
			<input type="file" name="xl" id="xl">
			<br/>
			<input class="btn btn-primary" type="submit" value="Upload XLS""></input>
			{!! Form::close() !!}
			<br/><br/>
		</div>
	</div>
 </div>
@stop

@section('script')
@stop