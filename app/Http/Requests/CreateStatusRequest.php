<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateStatusRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'status'    =>  'required|unique:statuses,status'
		];
	}

	public function message() {
		return [
			'status.required' => "Please provide the Status' name",
			'status.unique' => "Status is already in the Database"
		];
	}
}
