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
		$success_msg = "";
		$message_label = "";

		if ($owner_id != '') {
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

			$success_msg = $device->name .' was ASSOCIATED with ' . $owner->fullName();
			$message_label = "alert-success";
		} else {
			$success_msg = 'Owner doesn\'t exist or Owner was not provided';
			$message_label = "alert-danger";
		}

		return redirect()->back()->with('success_msg', $success_msg)->with('message_label', $message_label);
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

		return redirect()->back()->with('success_msg', $device->name . ' was DISASSOCIATED to '.$owner->fullName())->with('message_label', 'alert-success');
	}

	public static function getCountDeviceLog() {
		return count(DeviceLog::all());
	}
}
