@extends('app')

@section('header')
	@include('util.m-topbar')
@stop

@section('content')
	
	<div class="container">
		@include('util.m-sidebar')
		<div class="col-lg-9 col-md-offset-center-2">
			<br/>
			<div class="panel panel-default col-lg-12">
				<h3><span class="glyphicon glyphicon-refresh"></span> Change Password</h3>
				<hr/>
				<form action="{{ route('auth_cp') }}" method="POST" class="form-horizontal">
					<input type="hidden" value="{{ csrf_token() }}" name="_token">
					<div class="form-group">
						<label for="inputPassword3" class="col-sm-3 control-label">Password</label>
						<div class="col-sm-8">
							<input type="password" class="form-control" id="password" name="password" placeholder="Password">
						</div>
					</div>
					<div class="form-group">
						<label for="confirmPassword" class="col-sm-3 control-label">Confirm Password</label>
						<div class="col-sm-8">
							<input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Confirm Password">
						</div>
					</div>
					<hr/>
					<div class="form-group">
						<div class="col-lg-12">
							<label>Password Check: <span class="" id="passLabel"><label class="size-12" id="passStatus"></label></span></label>
							<button type="submit" id="submit_btn" class="btn btn-default right disabled ">Change Password</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@stop

@section('script')
<script type="text/javascript">
$(document).ready(function() {
	$("#password").keyup(validate);
	$("#password_confirmation").keyup(validate);
});


function validate() {
	var password1 = $("#password").val();
	var password2 = $("#password_confirmation").val();

	if(password1 == password2) {
		$("#passStatus").text("Password Match!");
		$("#passLabel").removeClass("label label-danger");
		$("#passLabel").addClass("label label-success");
		$("#submit_btn").removeClass("disabled");
	} else {
		$("#passStatus").text("Password Do Not Match....");
		$("#passLabel").removeClass("label label-success");
		$("#passLabel").addClass("label label-danger");
		$("#submit_btn").addClass("disabled");
	}

	if(password1 == '' ) {
		$("#passStatus").text("Password field is empty...");
		$("#passLabel").removeClass("label label-success");
		$("#passLabel").addClass("label label-danger");
		$("#submit_btn").addClass("disabled");
	} else if(password2 == '' ) {
		$("#passStatus").text("Confirm Password field is empty...");
		$("#passLabel").removeClass("label label-success");
		$("#passLabel").addClass("label label-danger");
		$("#submit_btn").addClass("disabled");
	} 
	if (password1 == '' && password2 == '') {
		$("#passStatus").text("Confirm Password & Password field is empty...");
		$("#passLabel").removeClass("label label-success");
		$("#passLabel").addClass("label label-danger");
		$("#submit_btn").addClass("disabled");
	}
}
</script>
@stop