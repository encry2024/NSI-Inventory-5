<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Note extends Eloquent {

	//
	protected $table = 'notes';
	protected $touches = ['user'];

	public function user() {
		return $this->belongsTo('App\User');
	}

}
