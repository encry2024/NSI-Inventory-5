<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Eloquent implements SluggableInterface {

	//
	use SoftDeletes;
	use SluggableTrait;

	protected $softDelete = true;
	protected $dates = ['deleted_at'];

	protected $fillable = ['status', 'slug', 'description'];

	protected $sluggable = array(
		'build_from' => 'status',
	);

	public function getFullnameAttribute() {
		return $this->status;
	}

	public function device() {
		return $this->hasOne('App\Device', 'status_id');
	}

	public function device_status() {
		return $this->hasMany('App\DeviceStatus');
	}

	public static function fetch_defectives() {
		$json = array();
		$defectiveStatuses = Device::where('status_id', '!=', 1)->get();

		foreach ($defectiveStatuses as $defectiveStatus) {
			$json[] = [
				'device_id' => $defectiveStatus->id,
				'device_name' => $defectiveStatus->name,
				'status' => $defectiveStatus->status->status,
				'status_id' => $defectiveStatus->status->id,
				'category' => $defectiveStatus->category->name,
				'category_id' => $defectiveStatus->category->id
			];
		}

		return json_encode($json);
	}

	public static function fetch_available() {
		$json = array();
		$defectiveStatuses = Device::where('status_id', 0)->get();

		foreach ($defectiveStatuses as $defectiveStatus) {
			$json[] = [
				'device_id' => $defectiveStatus->id,
				'device_name' => $defectiveStatus->name,
				'category' => $defectiveStatus->category->name,
				'category_id' => $defectiveStatus->category->id
			];
		}

		return json_encode($json);
	}

	public static function getStatusCount() {
		return count(Status::all());
	}

}
