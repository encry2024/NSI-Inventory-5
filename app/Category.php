<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class Category extends Eloquent implements SluggableInterface {

	//
	use SoftDeletes;
	use SluggableTrait;

	protected $softDelete = true;
	protected $dates = ['deleted_at'];

	protected $fillable = ['name', 'slug'];

	protected $sluggable = array( 'build_from' => 'name' );

	public function getFullnameAttribute() {
		return $this->name;
	}

	public function fields() {
		return $this->hasMany('App\Field');
	}

	public function devices() {
		return $this->hasMany('App\Device');
	}

	public function delete() {
		$devices = $this->devices()->withTrashed()->get();
		foreach ($devices as $cat_dev) {
			$cat_dev->category_id = 0;
			$cat_dev->save();
			$cat_dev->touch();
		}

		foreach ($this->fields as $category_fields) {
			$category_fields->category_id = 0;
			$category_fields->save();
		}
		// Delete this model
		return parent::delete();
	}

	public function audit() {
		return $this->hasMany('Audit');
	}

	public function devicelogs() {
		return $this->hasManyThrough('DeviceLog', 'Device', 'item_id', 'device_id');
	}

	public static function storeCategory( $f_requests, $request , $category) {
		$category->name = $request['name'];
		$category->save();

		$ctg_id = $category->id;
		$ctg_name = $category->name;

		foreach ($f_requests->category_label as $ctg_label) {
			$field = new Field;
			$field->category_id = $ctg_id;
			$field->category_label = $ctg_label;
			$field->save();
		}
		return redirect()->route('category.create')->with('success_msg', 'Category :: '.$ctg_name.' is successfully saved.');
	}

	public function importCategory() {
		$file = Input::file( 'xl' );

		$file->move(storage_path() . '/uploads', $file->getClientOriginalName());

		$sheet = Excel::load( storage_path() . '/uploads/' . $file->getClientOriginalName())->toArray();

		foreach ($sheet as $row) {
			$new_information = new Information();
			$new_information->device_id = $row['device_id'];
			$new_information->field_id = $row['field_id'];
			$new_information->value = $row['value'];
			$new_information->save();
		}

		return redirect()->back();
	}

	public static function fetchCategories() {
		$json = array();
		$categories = Category::all();
		foreach ($categories as $category) {
			$json[] = array(
				'id' 				=> $category->id,
				'slug'				=> $category->slug,
				'name' 				=> $category->name,
				'assoc_device'		=> count($category->associated_devices()),
				'av_device'			=> count($category->av_device()),
				'total_devices'		=> count($category->devices),
				'def_device'		=> count($category->def_device()),
				'updated_at' 		=> date('F d, Y', strtotime($category->updated_at)),
				'time_updated'		=> date('[ h:i A D ]', strtotime($category->updated_at))
			);
		}
		return json_encode($json);
	}

	public static function fetch_history( $category_slug ) {
		$json = [];
		$category = Category::whereSlug($category_slug)->first();
		$device_logs = DeviceLog::all();

		foreach($device_logs as $device_log) {
			if ($device_log->device->category_id == $category->id) {
				$json[] = [
					'device_slug' 	=> $device_log->device->slug,
					'device_name' 	=> $device_log->device->name,
					'owner_slug' 	=> $device_log->owner->slug,
					'owner_name' 	=> $device_log->owner->fullName(),
					'user_slug' 	=> $device_log->user->id,
					'assigned_by' 	=> $device_log->user->name,
					'action' 		=> $device_log->action,
					'date_assigned' => date('m/d/Y h:i A', strtotime($device_log->created_at)),
				];
			}
		}

		return json_encode($json);
	}

	public static function fetch_status_history( $category_slug ) {
		$json = [];
		$category = Category::whereSlug($category_slug)->first();
		$device_statuses = DeviceStatus::all();

		foreach ($device_statuses as $device_status) {
			if ($device_status->device->category_id == $category->id) {
				$json[] = [
					'device_slug' 		=> $device_status->device->slug,
					'device_name' 		=> $device_status->device->name,
					'user_slug' 		=> $device_status->user->id,
					'user_name' 		=> $device_status->user->name,
					'status_label' 		=> $device_status->status->status,
					'status_descrip' 	=> $device_status->status->description,
					'created_at' 		=> date('m/d/Y h:i A', strtotime($device_status->created_at))
				];
			}
		}

		return json_encode($json);
	}

	public static function fetch_del_cat() {
		$json = [];
		$categories = Category::onlyTrashed()->get();

		foreach ($categories as $category) {
			$json[] = [
				'category_id' => $category->id,
				'category_slug' => $category->slug,
				'category_name' => $category->name,
				'deleted_at' => date('F d, Y h:i A', strtotime($category->deleted_at))
			];
		}

		return json_encode($json);
	}



	public function associated_devices() {
		return $this->devices()->where('owner_id', '!=', '0')->get();
	}

	public function av_device() {
		return $this->devices()->where('availability', 'Available')->get();
	}

	public function def_device() {
		return $this->devices()->where('status_id', '!=', 1)->get();
	}
}
