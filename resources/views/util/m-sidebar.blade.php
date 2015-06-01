<div class="col-lg-3">
	<div class="btn-group-vertical col-lg-12" style="width: 100%;" role="group">
		<a href="{{ route('category.create')  }}" class="btn btn-default col-lg-12 text-left" role="button"><span class="glyphicon glyphicon-plus"></span> Create Categories</a>
		<a href="" class="btn btn-default text-left col-lg-12" role="button" data-popover="true" tabindex="1" data-html="true" data-trigger="focus" data-content="
			<a href='{{ route('category_excel') }}'><span class='glyphicon glyphicon-list-alt'></span> Categories</a>
			<a href='{{ route('device_excel') }}'><span class='glyphicon glyphicon-hdd'></span> Devices</a>
			<br>
			<a href='{{ route("import_information") }}'><span class='glyphicon glyphicon-info-sign'></span> Information</a>
			<a href='{{ route("import_field") }}'><span class='glyphicon glyphicon-tag'></span> Fields</a>
		"><span class="glyphicon glyphicon-share-alt"></span> Import Excel</a>
		<a href="{{ route('field_excel') }}" class="btn btn-default text-left col-lg-12" role="button"><span class="glyphicon glyphicon-search"></span> Search Information <span class="badge right">{{ count($information)  }}</span></a>
		<a href="" class="btn btn-default text-left col-lg-12" role="button"><span class="glyphicon glyphicon-user"></span> Manage Users <span class="badge right">{{ count($users)  }}</span></a>
		<a href="{{ route('owner.index') }}" class="btn btn-default text-left col-lg-12" role="button"><span class="glyphicon glyphicon-map-marker"></span> Create Owners <span class="badge right">{{ count($owners)  }}</span></a>
		<a href="{{ route('status.index') }}" class="btn btn-default text-left col-lg-12" role="button"><span class="glyphicon glyphicon-exclamation-sign"></span> Statuses <span class="badge right">{{ count($status) }}</span></a>
		<a href="{{ route('all_assoc') }}" class="btn btn-default text-left col-lg-12" role="button"><span class="glyphicon glyphicon-flag"></span> Checked Out Devices <span class="badge right">{{ count($assoc) }}</span></a>
		<a href="#" class="btn btn-default text-left col-lg-12" role="button"><span class="glyphicon glyphicon-ban-circle"></span> Defective Devices <span class="badge right">{{ count($defective_devices) }}</span></a>
		<a href="" class="btn btn-default text-left col-lg-12" role="button"><span class="glyphicon glyphicon-trash"></span> Deleted Categories <span class="badge right">{{ count($deleted_categories) }}</span></a>
		<a href="" class="btn btn-default text-left col-lg-12" role="button"><span class="glyphicon glyphicon-ok"></span> Available Devices <span class="badge right">{{ count($available_devices) }}</span></a>
		<a href="" class="btn btn-default text-left col-lg-12" role="button"><span class="glyphicon glyphicon-calendar"></span> 1-mnth Licenses <span class="badge right">42</span></a>
		</a>
	</div>
</div>
