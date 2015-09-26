 <!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta id="_token" name="_token" content="{{ csrf_token() }}"/>
	<title>NSI :: Inventory `5</title>

	<link rel="stylesheet" href="{!! URL::to('/') !!}/packages/css/btn.css"/>
	<link rel="stylesheet" href="{!! URL::to('/') !!}/packages/css/links.css"/>
	<link rel="stylesheet" href="{!! URL::to('/') !!}/packages/css/fonts.css"/>
	<link rel="stylesheet" href="{!! URL::to('/') !!}/packages/css/input.css"/>
	<link rel="stylesheet" href="{!! URL::to('/') !!}/packages/css/location.css"/>
	<link rel="stylesheet" href="{!! URL::to('/') !!}/packages/css/panel.css"/>
	<link rel="stylesheet" href="{!! URL::to('/') !!}/packages/css/separators.css"/>
	<link rel="stylesheet" href="{!! URL::to('/') !!}/packages/jquery-dropdown/source/css/multilist.css"/>
	<link rel="stylesheet" type="text/css" href="{!! URL::to('/') !!}/bootstrap/css/bootstrap-datepicker.min.css">
	<link href="{{ asset('packages/css/app.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="{{ URL::to('/') }}/bootstrap/bootstrap-3.3.4/css/bootstrap.css"/>
	<link rel="stylesheet" href="{{ URL::to('/') }}/packages/DataTables-1.10.4/media/css/jquery.dataTables.min.css"/>
	<link rel="stylesheet" href="{{ URL::to('/') }}/packages/autocomplete/css/jquery.tagit.css"/>
	<link rel="stylesheet" href="{{ URL::to('/') }}/packages/autocomplete/css/tagit.ui-zendesk.css"/>
	@yield('header')
</head>
<body>
	<script src="{{ URL::to('/') }}/bootstrap/bootstrap-3.3.4/js/jquery-1.11.2.min.js"></script>
	<script src="{{ URL::to('/') }}/jquery-ui-1.11.4/jquery-ui.min.js"></script>
	<script src="{{ URL::to('/') }}/packages/ohsnap-notification/ohsnap.js"></script>
	<script>
		$(document).off('.data-api');
	</script>
	<script src="{{ URL::to('/') }}/bootstrap/js/bootstrap-datepicker.min.js"></script>
	<script src="{{ URL::to('/') }}/packages/DataTables-1.10.4/media/js/jquery.dataTables.min.js"></script>
	<script src="{{ URL::to('/') }}/bootstrap/bootstrap-3.3.4/js/bootstrap.min.js"></script>
	<script src="{{ URL::to('/') }}/packages/jquery-dropdown/demo/jquery.tmpl.min.js"></script>
	<script src="{{ URL::to('/') }}/packages/jquery-dropdown/source/js/multilist.js"></script>
	<script src="{{ URL::to('/') }}/packages/autocomplete/js/tag-it.min.js"></script>
	<script src="{{ URL::to('/') }}/jquery-ui-1.11.4/papaparse.js"></script>


	@yield('content')
	@yield('script')
	@yield('style')
	@if (Request::path() != "auth/login")
	<div class="container">
		<div class="col-lg-12">
			<hr/>
			<label class="size-12 app-info-label" for=""><span class=""><kbd>© 2015 Northstar Solutions, Inc.</kbd></span></label>
			<label class="right size-12 app-info-label" for=""><kbd>Inventory `5 &mdash; Version: 1.2.1.1</kbd></label>
		</div>
	</div>
	@endif
	<style>
		body {
			/*background-color: #ddd;*/
			/*background: no-repeat center center fixed;
			background: linear-gradient(0deg, #5d2203, #de661d) no-repeat center center fixed;*/
			/*background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#c6651f), to(#9b1f03)) no-repeat;*/
			-webkit-font-smoothing: antialiased;
			/*font-family:Trebuchet MS, Arial, 'Segoe UI', sans-serif;*/
			font-size: 13px;
			font-weight: bold;
		}
	</style>
</body>
</html>
