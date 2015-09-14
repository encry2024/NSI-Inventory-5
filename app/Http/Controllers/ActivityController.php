<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ActivityController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index(Request $request)
	{
		$activities = Activity::with(['user', 'subject'])->latest();
		$activities = $activities->where('old_value', 'LIKE', '%'.$request->get('filter').'%');
		$activities = $activities->orwhere('new_value', 'LIKE', '%'.$request->get('filter').'%');
		$activities = $activities->orWhere('name', 'LIKE', '%'.$request->get('filter').'%')->paginate(25);


		$activities->setPath('activity');
		return view('activity.index', compact('activities'));
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
	public function destroy($id) {
		//
	}

}
