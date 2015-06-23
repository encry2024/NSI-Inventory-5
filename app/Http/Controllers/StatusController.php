<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Status;
use App\Http\Requests\CreateStatusRequest;

class StatusController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	protected $status;

	public function __construct( Status $status ) {
		$this->status = $status;
		$this->middleware('auth');
	}



	public function index()
	{
		//
		$statuses = Status::all();
		$deletedStatus = Status::onlyTrashed()->get();
		return view('status.index', compact('statuses', 'deletedStatus'));
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
	public function store( CreateStatusRequest $status_request, Status $status )
	{
		//
		$status_req = $status->create( $status_request->all() );
		return $status_req;
		return redirect()->back();
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

	public function deleteStatus(Request $request) {
		$status_id = $request->get('status_id');
		$status = Status::find($status_id);
		$status->delete();

		return redirect()->back()->with('success_msg', 'Status was successfully deleted.');
	}

	public function fetchStatus() {
		$json = array();
		$statuses = Status::all();

		foreach ($statuses as $status) {
			$json[] = [
				'id' => $status->id,
				'status' =>$status->status
			];
 		}

		return json_encode($json);
	}

	public function fetchDefectives() {
		$defectives = Status::fetch_defectives();

		return $defectives;
	}

	public function fetchAvailable() {
		$available = Status::fetch_available();

		return $available;
	}

}
