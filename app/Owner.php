<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

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

	public static function importOwner() {
		set_time_limit(0);

		$file = Input::file( 'xl' );

		//move the file to storage/uploads folder with its original file name
		$file->move(storage_path() . '/uploads', $file->getClientOriginalName());

		//Load the sheet and convert it into array
		$sheet = Excel::load( storage_path() . '/uploads/' . $file->getClientOriginalName())->toArray();

		foreach ($sheet as $row) {
			$new_owner = new Owner();
			$new_owner->firstName = $row['firstname'];
			$new_owner->lastName = $row['lastname'];
			$new_owner->location = $row['campaign'];
			$new_owner->save();
		}

		return redirect()->back()->with('success_msg', 'Files has been successfully imported.');
	}

}
