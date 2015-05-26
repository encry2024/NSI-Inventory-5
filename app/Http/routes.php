<?php

# ROUTE BIND
Route::bind('category', 	function( $slug ) 	{ return App\Category::whereSlug($slug)->first(); });
Route::bind('device', 		function( $slug ) 	{ return App\Device::whereSlug($slug)->first(); });
Route::bind('status', 		function( $slug )	{ return App\Status::whereSlug($slug)->first(); });
Route::bind('owner', 		function( $slug )	{ return App\Owner::whereSlug($slug)->first(); });
Route::bind('user', 		function( $id )		{ return App\User::find($id); });
Route::bind('note', 		function( $id )		{ return App\Note::find($id); });
Route::bind('information', 	function( $id )  	{ return App\Information::find($id); });

# ROUTE RESOURCE
Route::resource('category', 'CategoryController');

# DEVICE RESOURCE
get('fetch/devices/{category_id}', ['as' => 'fetch_devices', 'uses' => 'DeviceController@fetch']);
get('category/{category_slug}/create', ['as' => 'create_device', 'uses' => 'DeviceController@create']);
get('device/status/{device_id}', ['as' => 'fetch_device_statuses', 'uses' => 'DeviceController@fetchStatus']);
get('getDeviceHistory/{device_id}', ['as' => 'device_assoc_history' ,'uses' => 'DeviceController@assocHistory']);
post('device/associate/{device_id}', ['as' => 'device_associate', 'uses' => 'DeviceController@associateDevice']);
post('device/disassociate/{device_id}', ['as' => 'device_disassociate', 'uses' => 'DeviceController@disassociateDevice']);
post('change/status/{device_id}', ['as' => 'change_status', 'uses' => 'DeviceController@changeStatus']);
Route::resource('device', 'DeviceController');

# STATUS RESOURCE
post('status/delete', ['as' => 'delete_status', 'uses' => 'StatusController@deleteStatus']);
get('fetch/status', ['as' => 'fetch_all_status', 'uses' => 'StatusController@fetchStatus']);
get('fetch/all_defectives', ['as' => 'get_defectives', 'uses' => 'StatusController@fetchDefectives']);
get('fetch/all_available', ['as' => 'get_available', 'uses' => 'StatusController@fetchAvailable']);
Route::resource('status', 'StatusController');

# OWNER RESOURCE
get('owner/fetch', ['as'=>'fetch_owners', 'uses' => 'OwnerController@fetchOwners']);
get('dispatchedDevices/{owner_id}', ['as' => 'fetchDispatchDevices', 'uses' => 'OwnerController@fetchDispatches']);
get('fetchAvailableOwners/{device_id}', ['as' => 'fetchAvailableOwners', 'uses' => 'OwnerController@fetchAvailableOwners']);
Route::resource('owner', 'OwnerController');

# USER RESOURCE
Route::resource('user', 'UserController', ['only'=>['edit', 'index', 'show']]);

# NOTE RESOURCE
get('note/history/{device_id}', ['as' => 'note_history', 'uses' => 'NoteController@fetchNotes']);
Route::resource('note', 'NoteController', ['only'=>['store', 'show', 'fetchNotes']]);

# INFORMATION RESOURCE
post('information/update', ['as' => 'information/update', 'uses' => 'InformationController@updateInfo']);
Route::resource('information', 'InformationController');

# AUTH CONTROLLERS
Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

# GET
get('/', ['as' => 'home' , 'uses' => 'HomeController@index']);
get('associates', ['as' => 'assoc', 'uses' => 'DeviceController@allAssoc']);
get('associates/all', ['as' => 'all_assoc', 'uses' => 'DeviceController@viewAssoc']);
get('import_excel', ['as' => 'category_excel', 'uses' => 'CategoryController@excelIndex']);
get('import_device/{category_slug}', ['as' => 'device_excel', 'uses' => 'DeviceController@deviceIndex']);
post('open_excel', ['as' => 'openFile', 'uses' => 'CategoryController@openExcel']);
post('import_devices/{category_id}', ['as' => 'importDevice', 'uses' => 'DeviceController@openExcel']);
get('import_owner', ['as' => 'owner_excel', 'uses' => 'OwnerController@ownerIndex']);
post('open_owner', ['as' => 'importOwner', 'uses' => 'OwnerController@openExcel']);