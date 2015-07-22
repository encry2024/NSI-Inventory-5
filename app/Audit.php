<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Audit extends Eloquent {

	//

	public function users() {
		return $this->belongsToMany('App\Users');
	}


	public function auditUserEvent($user, $action, $subject_1, $subject_2) {
		$this->user_id = $user;
		$this->event = $action;
		$this->field = $subject_1;
		$this->object = $subject_2;

		$this->save();
	}
}
