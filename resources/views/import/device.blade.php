@extends('app')

@section('header')
    @include('util.m-topbar')
    <div class="container">
		<div class="col-lg-12">
			<ol class="breadcrumb" style=" margin-left: 1.5rem; ">
				<li><label>Inventory</label>
				<li><a href="{{ route('home') }}" class="active">Home</a></li>
				<li><label>Import Devices</label>
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
			<h3>Import Devices</h3>
			<hr/>
			<form id='frm_update' class='updte' enctype='multipart/form-data'>
				<input type='hidden' name='_method' value='POST'>
				<input type="file" name="xl" id="xl">
				<br/>
				<input id="submit" class="btn btn-primary" type="submit" value="Upload XLS">
			</form>
			<br/><br/>
			<div class="progress">
				<div class="progress-bar"  id="progress" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
					<label id="totalData" for="">0%</label>
				</div>
			</div>
		</div>
	</div>
 </div>
@stop

@section('script')
<script>
	$.ajaxSetup({
	   headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
	})

	$(document).on('submit', 'form', function() {
		var size = $("#xl")[0].files[0].size;
		var progress = 0;
		var newPercent = 0;
		var data_failed_import = 0;

		Papa.parse($("#xl")[0].files[0], {
			header: true,
			dynamicTyping: true,
			skipEmptyLines: true,

			step: function(row) {
				var csvData = row.data[0];
				$.ajax({
					type: 'POST',
					url: "{{ route('import_devices') }}",
					data: csvData,
					success: function() {
						progress = row.meta.cursor;
						newPercent = Math.round(progress / size * 100);

						document.getElementById("progress").style.width = newPercent + "%";
						document.getElementById("totalData").innerHTML = newPercent + "%";
					},
					error: function() {
						data_failed_import++;
					}
				});
			},
			complete: function() {
				document.getElementById("totalData").innerHTML = "Data importing is finished";
			}
		});
		return false;
	});
</script>
@stop