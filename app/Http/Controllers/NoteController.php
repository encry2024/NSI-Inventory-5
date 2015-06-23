<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Note;
use Illuminate\Http\Request;

class NoteController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
		//
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
	public function store( Request $request ) {
		//return $request->get('note_id');
		$note = Note::find($request->get('note_id'));
		if (count($note) > 0) {
			$note->past = 1;
			$note->save();
		}
		$stripped_note = str_replace("'", '&apos;', $request->get('note'));

		$new_note = new Note();
		$new_note->user_id = $request->get('user_id');
		$new_note->note = $stripped_note;
		$new_note->device_id = $request->get('device_id');
		$new_note->past = 0;
		$new_note->save();

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

	public function fetchNotes( $id ) {
		$json = array();
		$notes = Note::where('device_id', $id)->orderBy('created_at','desc')->get();

		foreach ($notes as $note) {
			$stripped_note = str_replace("'", '&apos;', $note->note);
			$json[] = [
				'id' => $note->id,
				'user_id' => $note->user->id,
				'note' => str_limit($stripped_note, $limit=30, $end='...'),
				'fullnote' => $stripped_note,
				'user_type' => $note->user->type,
				'name' => $note->user->name,
				'created_at' => date('m/d/Y h:i A', strtotime($note->created_at))
			];
		}

		return json_encode($json);
	}

}
