<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Illuminate\Database\Eloquent\SoftDeletes;
#-----------------------------------------------------------
class Owner extends Eloquent implements SluggableInterface{

	//
	use SoftDeletes;
	use SluggableTrait;

	protected $table = 'owners';

	protected $softDelete = true;
	protected $dates = ['deleted_at'];
	protected $fillable = ['firstName', 'lastName', 'location', 'slug'];

	protected $sluggable = [ 'build_from' => 'fullname', 'save_to' => 'slug' ];

	public function getFullnameAttribute() {
		return $this->firstName . ' ' . $this->lastName;
	}

	public function devices() {
		return $this->hasMany('App\Device');
	}

	public function device_logs() {
		return $this->hasManyThrough('App\Device', 'App\DeviceLog', 'device_id', 'owner_id');
	}

	public function fullName() {
		return $this->firstName . ' ' . $this->lastName;
	}

}
