<?php namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;

use Illuminate\Http\Request;

use App\Device;
use App\Category;
use App\DeviceStatus;
use App\Information;
use App\Owner;
use App\DeviceLog;
use App\Note;
use App\Http\Requests\CreateDeviceRequest;

class DeviceController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    public function __construct( Device $device ) {
        $this->device = $device;
    }


	public function index() {
		//code...

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($category_slug) {
		$category = Category::where('slug', $category_slug)->first();
		$ctr = 0;
		if (count($category) > 0) {
			return view('devices.create', compact('category', 'ctr'));
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(CreateDeviceRequest $create_request) {
		//store device
		$store_device = Device::store_device($create_request, Input::all());
		return $store_device;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($device)
	{
		//

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($device)
	{
		//
		$note = Note::where('device_id', $device->id)->where('past', 0)->first();
		return view('devices.edit', compact('device', 'note'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	public function fetch( $category_id ) {
		$all_device = Device::fetchAllDevice( $category_id );

		return $all_device;
	}

	public function fetchStatus( $id ) {
		$fetch_status = Device::fetch_Status( $id );

		return $fetch_status;
	}

	public function associateDevice( Request $request, $id ) {
		$owner_id = $request->get('owner_id');
		$log = DeviceLog::createLog($owner_id, $id);

		return $log;
	}

	public function assocHistory( $id ) {
		$return_assocHistory = Device::fetch_assocHistory($id);

		return $return_assocHistory;
	}

	public function allAssoc() {
		$all_assoc = Device::fetchAllAssoc();

		return $all_assoc;
	}

	public function viewAssoc() {
		return view('associates.index');
	}

	public function disassociateDevice( $id ) {
		//return $id;
		$log = DeviceLog::disassocLog($id);

		return $log;
	}

	public function changeStatus( Request $request, $id ) {
		$return_change_status = Device::change_status($request, $id);

		return $return_change_status;
	}

	public function openExcel() {
		//return $category_id;
		$import_excel = Device::importDevice();

		return $import_excel;
	}

	public function deviceIndex() {
		return view('import.device');
	}

	public function deviceInformation() {
		$device_info = Device::getInformation();

		return $device_info;
	}
}
