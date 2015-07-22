<div class="col-lg-3">
	<div class="btn-group-vertical col-lg-12" style="width: 100%;" role="group">
		<a href="{{ route('category.create')  }}" class="btn btn-default col-lg-12 text-left" role="button"><span class="glyphicon glyphicon-plus"></span> Create Categories</a>
		<a href="" class="btn btn-default text-left col-lg-12" role="button"><span class="glyphicon glyphicon-user"></span> Manage Users <span class="badge right">{{ App\User::getUserCount()  }}</span></a>
		<a href="{{ route('owner.index') }}" class="btn btn-default text-left col-lg-12" role="button"><span class="glyphicon glyphicon-map-marker"></span> Owners <span class="badge right">{{ App\Owner::getOwnerCount() }}</span></a>
		<a href="{{ route('status.index') }}" class="btn btn-default text-left col-lg-12" role="button"><span class="glyphicon glyphicon-exclamation-sign"></span> Statuses <span class="badge right">{{ App\Status::getStatusCount() }}</span></a>
		<a href="{{ route('all_assoc') }}" class="btn btn-default text-left col-lg-12" role="button"><span class="glyphicon glyphicon-flag"></span> Device Log<span class="badge right">{{ App\DeviceLog::getCountDeviceLog() }}</span></a>
		<a href="{{ route('information.index') }}" class="btn btn-default text-left col-lg-12" role="button"><span class="glyphicon glyphicon-search"></span> Search Information <span class="badge right">{{ App\Information::getInformationCount()  }}</span></a>
		<a href="#" class="btn btn-default text-left col-lg-12" role="button"><span class="glyphicon glyphicon-ban-circle"></span> Defective Devices <span class="badge right">{{ App\Device::getCountOfDefectiveDevices() }}</span></a>
		<a href="{{ route('a_a_d') }}" class="btn btn-default text-left col-lg-12" role="button"><span class="glyphicon glyphicon-ok"></span> Available Devices <span class="badge right">{{ App\Device::getCountOfAvailableDevices() }}</span></a>
	</div>
</div>

<!-- 
<a href="" class="btn btn-default text-left col-lg-12" role="button" data-popover="true" tabindex="1" data-html="true" data-trigger="focus" data-content="
			<a href='{{ route('category_excel') }}'>Categories</a>
			<a href='{{ route('device_excel') }}'>Devices</a>
			<br>
			<a href='{{ route("import_information") }}'>Information</a>
			<a href='{{ route("import_field") }}'>Fields</a>">
			<span class="glyphicon glyphicon-share-alt"></span> Import Excel
		</a>
		<a class="btn btn-default col-lg-12 text-left" role="button" data-popover="true" tabindex="2" data-html="true" data-trigger="focus" data-content="
			<a href='{{ route('category_excel') }}'>History</a>
			<a href='{{ route('odl') }}'>Device</a>">
			<span class="glyphicon glyphicon-folder-close"></span> Archived Logs
		</a>
 -->