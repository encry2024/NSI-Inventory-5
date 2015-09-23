<?php

# ROUTE BIND
Route::bind('category',    function ($slug) { return App\Category::whereSlug($slug)->first(); });
Route::bind('device',      function ($slug) { return App\Device::whereSlug($slug)->first(); });
Route::bind('status',      function ($slug) { return App\Status::whereSlug($slug)->first(); });
Route::bind('owner',       function ($slug) { return App\Owner::whereSlug($slug)->first(); });
Route::bind('user',        function ($id)   { return App\User::find($id); });
Route::bind('note',        function ($id)   { return App\Note::find($id); });
Route::bind('information', function ($id)   { return App\Information::find($id); });
Route::bind('field',       function ($id)   { return App\Field::find($id); });
Route::bind('activity',    function ($id)   { return App\Activity::find($id); });
# ROUTE RESOURCE

# CATEGORY RESOURCE
Route::resource('category', 'CategoryController');
Route::get('change_password', [    'as' => 'change_password', 'uses' => 'UserController@viewChangePassword']);
// GET
get('{category_slug}/category_history', ['as' => 'c_h', 'uses' => 'CategoryController@categoryHistory']);
get('{category_slug}/associate-dissociate-history', ['as' => 'ch', 'uses' => 'CategoryController@viewCategoryHistory']);
get('{category_slug}/statuses', ['as' => 'sh', 'uses' => 'CategoryController@viewCategoryStatusesHistory']);
get('{category_slug}/statuses_history', ['as' => 'c_s_h', 'uses' => 'CategoryController@categoryStatusHistory']);
get('fetch/devices/{info_id}/{category_id}', ['as' => 'f_d_i', 'uses' => 'CategoryController@fetch_devices_infoValue']);
get('fetch/category/{category_slug}', ['as' => 'f_c_cs', 'uses' => 'CategoryController@fetchCatName']);

# DEVICE RESOURCE
Route::resource('device', 'DeviceController');
// GET
get('fetch/devices/{category_id}', ['as' => 'fetch_devices', 'uses' => 'DeviceController@fetch']);
get('category/{category_slug}/create', ['as' => 'create_device', 'uses' => 'DeviceController@create']);
get('device/status/{device_id}', ['as' => 'fetch_device_statuses', 'uses' => 'DeviceController@fetchStatus']);
get('getDeviceHistory/{device_id}', ['as' => 'device_assoc_history', 'uses' => 'DeviceController@assocHistory']);
get('device_information', ['as' => 'fetch_devices_information', 'uses' => 'DeviceController@deviceInformation']);
get('category/{category_slug}/associated_devices', ['as' => 'assocdev', 'uses' => 'DeviceController@assocDev']);
get('{category_slug}/associated_devices', ['as' => 'assoc_dev', 'uses' => 'DeviceController@showAssocDev']);
get('category/{category_slug}/available_devices', ['as' => 'availdev', 'uses' => 'DeviceController@availDev']);
get('{category_slug}/available_devices', ['as' => 'avail_device', 'uses' => 'DeviceController@showAvailDev']);
get('category/{category_slug}/defective_devices', ['as' => 'defectdev', 'uses' => 'DeviceController@defectDev']);
get('{category_slug}/defective_devices', ['as' => 'defect_device', 'uses' => 'DeviceController@showDefectDev']);
get('available_devices', ['as' => 'a_a_d', 'uses' => 'DeviceController@viewAllAvailableDevices']);
get('fetch_available_devices', ['as' => 'r_a_a_d', 'uses' => 'DeviceController@showAllAvailableDevice']);
get('device/{device_slug}/notes', ['as' => 'dn', 'uses' => 'DeviceController@showDeviceNote']);
get('device/{device_slug}/status', ['as' => 'ds', 'uses' => 'DeviceController@showDeviceStatus']);
get('device/{device_slug}/ownerships', ['as' => 'dadh', 'uses' => 'DeviceController@showDeviceOwnership']);
get('device/{device_slug}/information', ['as' => 'dInfo', 'uses' => 'DeviceController@showDeviceInformation']);
// POST
post('device/associate/{device_id}', ['as' => 'device_associate', 'uses' => 'DeviceController@associateDevice']);
post('device/disassociate/{device_id}', ['as' => 'device_disassociate', 'uses' => 'DeviceController@disassociateDevice']);
post('change/status/{device_id}', ['as' => 'change_status', 'uses' => 'DeviceController@changeStatus']);
Route::any('deleteall', ['as' => 'da', 'uses' => 'DeviceController@massDelete']);

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
get('import_excel/information', ['as' => 'import_information', 'uses' => 'InformationController@showinformation']);
post('import_field/information', ['as' => 'importInformation', 'uses' => 'InformationController@importInformation']);
Route::resource('information', 'InformationController');

# FIELD RESOURCE
post('field/{field_id}', ['as' => 'f_update', 'uses' => 'FieldController@update']);
Route::resource('field', 'FieldController', ['only'=>['update', 'store', 'destroy']]);
# AUTH CONTROLLERS
Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

# ACTIVITY RESOURCE
Route::resource('activity', 'ActivityController', ['only' => ['index']]);

# GET
get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
get('associates', ['as' => 'assoc', 'uses' => 'DeviceController@allAssoc']);
get('associates/all', ['as' => 'all_assoc', 'uses' => 'DeviceController@viewAssoc']);
get('archived_data', ['as' => 'a_d', 'uses' => 'AuditController@viewOld']);
get('device_logs/old', ['as' => 'o_d_l', 'uses' => 'OldDeviceLogController@oldDeviceLog']);
get('old_device_logs', ['as' => 'odl', 'uses' => 'OldDeviceLogController@index']);


# IMPORT EXCELS
// CATEGORIES
get('import_excel/categories', ['as' => 'category_excel', 'uses' => 'CategoryController@excelIndex']);
post('open_excel', ['as' => 'import_categories', 'uses' => 'CategoryController@openExcel']);
// DEVICES
get('import_excel/devices', ['as' => 'device_excel', 'uses' => 'DeviceController@deviceIndex']);
post('import_devices', ['as' => 'import_devices', 'uses' => 'DeviceController@openExcel']);
// OWNERS
get('import_excel/owner', ['as' => 'owner_excel', 'uses' => 'OwnerController@ownerIndex']);
post('open_owner', ['as' => 'import_owners', 'uses' => 'OwnerController@openExcel']);
// FIELDS
get('import_excel/fields', ['as' => 'import_field', 'uses' => 'FieldController@showImport']);
post('import_fields', ['as' => 'import_fields', 'uses' => 'FieldController@importFields']);


# ROUTE FILTERING
Route::filter('csrf', function () {
    $token = Request::ajax() ? Request::header('X-CSRF-Token') : Input::get('_token');
    if (Session::token() != $token) {
        throw new Illuminate\Session\TokenMismatchException;
    }
});

# FUNCTION TESTER ROUTE
post('function_save', ['as' => 't', 'uses' => 'CategoryController@testImport']);
get('view_tester', ['as' => 'vt', 'uses' => 'CategoryController@viewTester']);
get('get_count', ['as' => 'gc', 'uses' => 'CategoryController@fetchCount']);
post('test', ['as' => 'tst', 'uses' => 'DeviceController@testFun']);

# POST
Route::post('user/changed_password', ['as' => 'auth_cp', 'uses' => 'UserController@changePass']);
