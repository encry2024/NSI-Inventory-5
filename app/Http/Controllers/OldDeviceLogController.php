<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\OldDeviceLog;
use App\Device;
use App\Owner;
use App\Category;

use Illuminate\Http\Request;

class OldDeviceLogController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('archived.old_device_log');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
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

	public function oldDeviceLog() {
		$json = array();
		$old_categories = Category::all();

		foreach ($old_categories as $old_category) {
			foreach ($old_category->devices as $do) {
				if ($do->owner_id != 0) {
					$json[] = [
						'category_slug' => $old_category->slug,
						'category_name' => $old_category->name,
						'device_slug' => $do->slug,
						'device_name' => $do->name,
						'owner_slug' => $do->owner->slug,
						'owner_name' => $do->owner->fullName(),
						'created_at' => date('M d, Y h:i A', strtotime($do->created_at))
					];
				}
			}
		}

		return json_encode($json);
	}

}
