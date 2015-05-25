<?php namespace App\Http\Controllers;

use App\Owner;
use App\User;
use App\Field;
use App\Status;
use App\Device;

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
        $fields = Field::all();
		$status = Status::all();
		$assoc = Device::where('owner_id', '!=', '')->get();
		$available_devices = Device::where('owner_id', 0)->get();

		return view('home', compact('owners', 'users', 'fields', 'status', 'assoc', 'available_devices'));
	}

}
