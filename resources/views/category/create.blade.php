@extends('app')

@section('header')
    @include('util.m-topbar')
    <div class="container">
		<div class="col-lg-12">
			<ol class="breadcrumb" style=" margin-left: 1.5rem; ">
				<li><label>Inventory</label>
				<li><a href="{{ route('home') }}" class="active">Home</a></li>
				<li><label>Create Category</label>
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
{!! Form::open(['route' => 'category.store']) !!}
<div class="container">
	<div class="col-lg-3">
		<div class="btn-group-vertical col-lg-12" role="group">
			<button class="btn btn-default col-lg-12 text-left"><span class="glyphicon glyphicon-plus"></span> Create Category</button>
			<button class="btn btn-default col-lg-12 text-left add_field_button"><span class="glyphicon glyphicon-tags"></span> Add Field</button>
			<a href="{{ route('home')  }}" class="btn btn-default text-left" role="button"><span class="glyphicon glyphicon-chevron-left"></span> Return to home</a>
		</div>
	</div>

    <div class="col-lg-9 col-md-offset-center-2">
        <div class="panel panel-default col-lg-12">
           <div class="page-header">
                <h3>Create Category</h3>
           </div>
           <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            <label class="col-md-4 control-label">Name</label>
                <div class="col-md-6">
                    <input type="string" class="form-control" name="name" value="{{ old('name') }}">
                    {!! $errors->first('name', '<span class="help-block">:message</span>')  !!}
                </div>
            </div>

            <br/><br/>
            <div></div>
            <br/>

            <div class="form-group">
                <label class="col-md-4 control-label">Information</label>
                <div class="col-md-6 input_fields_wrap">
                    <input type="text" class="form-control" value="Brand" name="category_label[]" style=" margin-bottom: -1rem; " readOnly>
                    <br/>
                    <input type="text" class="form-control" value="Model" name="category_label[]" style=" margin-bottom: -1rem; " readOnly>
                    <br>
                    <input type="text" class="form-control" value="Serial Number" name="category_label[]" style=" margin-bottom: -1rem; " readOnly>
                    <br>
                    <input type="text" class="form-control" value="Product Key" name="category_label[]" style=" margin-bottom: -1rem; " readOnly>
                    <br>
                    <input type="text" class="form-control" value="NSI Tag" name="category_label[]" style=" margin-bottom: -1rem; " readOnly>
                    <br/>
                    <input type="text" class="form-control" value="Date Purchased" name="category_label[]" style=" margin-bottom: -1rem; " readOnly>
                    <br>
                    <input type="text" class="form-control" value="Order Number" name="category_label[]" style=" margin-bottom: -1rem; " readOnly>
                    <br>
                    <input type="text" class="form-control" value="Purchased Cost" name="category_label[]" style=" margin-bottom: -1rem; " readOnly>
                    <br>
                    <input type="text" class="form-control" value="Expiration Date" name="category_label[]" style=" margin-bottom: -1rem; " readOnly>
                    <br/><br/><br/><br/>
                </div>
            </div>

        </div>
    </div>
 </div>
 {!! Form::close() !!}
@stop

@section('script')
<script type="text/javascript">
	$(document).ready(function() {
		var max_fields      = 10; //maximum input boxes allowed
		var wrapper         = $(".input_fields_wrap"); //Fields wrapper
		var add_button      = $(".add_field_button"); //Add button ID

		var x = 1; //initlal text box count
		$(add_button).click(function(e){ //on add input button click
			e.preventDefault();
			if(x < max_fields){ //max input box allowed
				x++; //text box increment
				$(wrapper).append('<div><input type="text" class="form-control" style=" margin-bottom: -1rem; " name="category_label[]"/><a href="#" id="Font" class="tiny remove_field right"><span class="glyphicon glyphicon-remove"></span></a></div>'); //add input box
			}
		});

		$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
			e.preventDefault();
			$(this).parent('div').remove();
			x--;
		});
	});

</script>
@stop