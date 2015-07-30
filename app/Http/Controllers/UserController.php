<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\User;
class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index(Request $request) {
		$users = User::where('name', 'LIKE', '%'.$request->get('filter').'%')->latest();
		$users = $users->where('email', 'LIKE', '%'.$request->get('filter').'%');
		$users = $users->whereType('user')->paginate(25);


		$users->setPath('/user');
		return view('user.index', compact('users'));
	}

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

	public function viewChangePassword()
	{
		return view('auth.change_password');
	}

	public function changePass( Request $request )
	{
		User::find(Auth::user()->id)->update(['password' => bcrypt($request->get('password'))]);

		Auth::logout();

		return redirect('auth/login')->with('success_msg', 'Your password was successfully changed');
	}

}
