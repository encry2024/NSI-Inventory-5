<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Auth;

class DeviceLog extends Eloquent {

	//
	use RecordsActivity;
	protected $table = 'device_logs';

	public function owner() {
		# code...
		return $this->belongsTo('App\Owner');
	}

	public function device() {
		# code...
		return $this->belongsTo('App\Device');
	}

	public function deviceOwner() {
		return $this->hasManyThrough('App\Device', 'App\Owner', 'id', 'owner_id');
	}

	public function user() {
		return $this->belongsTo('App\User');
	}

	public static function createLog($owner_id, $id) {

		$device = Device::find($id);
		$device->owner_id = $owner_id;
		$device->user_id = Auth::user()->id;
		$device->save();

		$owner = Owner::find($owner_id);

		$device_log = new DeviceLog();
		$device_log->owner_id = $owner_id;
		$device_log->device_id = $id;
		$device_log->user_id = \Auth::user()->id;
		$device_log->action = "ASSOCIATE";
		$device_log->save();

		return redirect()->back()->with('success_msg', $device->name .' was ASSOCIATED with ' . $owner->fullName());
	}

	public static function disassocLog($id) {
		$device = Device::find($id);

		$device_log = new DeviceLog();
		$device_log->owner_id = $device->owner_id;
		$device_log->device_id = $id;
		$device_log->user_id = \Auth::user()->id;
		$device_log->action = "DISASSOCIATE";
		$device_log->save();

		$owner_id = $device_log->owner_id;

		$owner = Owner::find($owner_id);

		$device->owner_id = 0;
		$device->save();

		return redirect()->back()->with('success_msg', $device->name . ' was DISASSOCIATED to '.$owner->fullName());
	}

	public static function getCountDeviceLog() {
		return count(DeviceLog::all());
	}
}
