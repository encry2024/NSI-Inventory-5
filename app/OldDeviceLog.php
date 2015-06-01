<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class OldDeviceLog extends Eloquent {

	//

	protected $table = "old_device_log";

	public function device() {
		return $this->belongsTo('App\Device')->withTrashed();
	}

	public function owner() {
		return $this->belongsTo('App\Owner');
	}

	public function deviceOwner() {
		return $this->hasManyThrough('App\Device', 'App\Owner', 'id', 'owner_id');
	}
}
