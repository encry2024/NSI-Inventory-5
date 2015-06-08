<?php namespace App\Http\Controllers;

use App\Owner;
use App\User;
use App\Information;
use App\Status;
use App\Device;
use App\Category;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
        $owners = Owner::all();
        $users = User::where('type', 'user')->get();
        $information = Information::all();
		$status = Status::all();
		$assoc = Device::where('owner_id', '!=', '')->get();
		$available_devices = Device::where('owner_id', 0)->get();
		$deleted_categories = Category::onlyTrashed()->get();
		$defective_devices = Device::where('status_id', '!=', 1)->get();
		$uncategorized_devices = Device::where('category_id', 0)->withTrashed()->get();

		return view('home', compact('owners', 'users', 'information', 'status', 'assoc', 'available_devices', 'deleted_categories', 'defective_devices', 'uncategorized_devices'));
	}

}
