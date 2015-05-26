@extends('app')

@section('header')
    @include('util.m-topbar')
    <div class="container">
		<div class="col-lg-12">
			<ol class="breadcrumb" style=" margin-left: 1.5rem; ">
				<li><label>Inventory</label>
				<li><a href="{{ route('home') }}" class="active">Home</a></li>
				<li><label>Import Excel's</label>
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
                <h3>Import {{ $category->name }}s</h3>
           </div>
           {!! Form::open(['route' => ['importDevice', $category->id], 'files' => true]) !!}
           {!! Form::file('xl', null) !!}
           <br/>
           {!! Form::submit('Import to Database', ['class'=>'Form-control btn btn-primary']) !!}
           {!! Form::close() !!}
           <table id="devices" class="table"></table>
           <br/><br/>
        </div>
    </div>
 </div>
@stop
