<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Activity extends Eloquent {

	//

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function subject()
	{
		return $this->morphTo()->withTrashed();
	}

}
