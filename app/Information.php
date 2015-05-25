<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Information extends Eloquent {

	//
	protected $fillable = ['value'];
	protected $touches = ['field', 'device'];

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
}
