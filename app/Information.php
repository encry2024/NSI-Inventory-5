<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class Information extends Eloquent {

	//
	use SoftDeletes;
	protected $fillable = ['value'];
	protected $touches = ['field', 'device'];

	protected $softDelete = true;

	public function field() {
		return $this->belongsTo('App\Field');
	}

	public function device() {
		return $this->belongsTo('App\Device');
	}

	public static function update_information($request) {
		$info_val = $request->get('value');
		$info_id = $request->get('inf_id');
		$device_id = $request->get('device_id');

		Information::find($info_id)->update(['value' => $info_val]);
		$device = Device::find($device_id);
		$device->touch();
		return redirect()->back();
	}

	public static function importInformation() {
		set_time_limit(0);

		$file = Input::file( 'xl' );

		//move the file to storage/uploads folder with its original file name
		$file->move(storage_path() . '/uploads', $file->getClientOriginalName());

		//Load the sheet and convert it into array
		$sheet = Excel::load( storage_path() . '/uploads/' . $file->getClientOriginalName())->toArray();

		foreach ($sheet as $row) {
			$new_information = new Information();
			$new_information->device_id = $row['device_id'];
			$new_information->field_id = $row['field_id'];
			$new_information->value = $row['value'];
			$new_information->save();
		}

		return redirect()->back()->with('success_msg', 'Files has been successfully imported.');
	}
}
