<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;


class DeviceStatus extends Eloquent {

	//
	protected $table = 'device_statuses';

	public function status() {
		return $this->belongsTo('App\status');
	}

	public function device() {
		return $this->belongsTo('App\Device');
	}

	public function user() {
		return $this->belongsTo('App\User');
	}
}
