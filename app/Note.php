<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Note extends Eloquent {

	//
	protected $table = 'notes';

	public function user() {
		return $this->belongsTo('App\User');
	}

	public function device()
	{
		return $this->hasMany('App\Device');
	}

}
