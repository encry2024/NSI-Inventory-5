<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateDeviceRequest extends Request {

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
			'name'  =>  'required|unique:devices,name'
		];
	}

	public function messages()
	{
		return [
			'name.required' => "Device Description is Required.",
			'name.unique' => "The device you are trying to add is already in the database"
		];
	}

}
