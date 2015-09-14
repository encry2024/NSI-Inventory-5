<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class Category extends Eloquent implements SluggableInterface {

	use SoftDeletes, SluggableTrait, RecordsActivity;

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

	public function audit() {
		return $this->hasMany('Audit');
	}

	public function devicelogs() {
		return $this->hasManyThrough('DeviceLog', 'Device', 'item_id', 'device_id');
	}

	public function informations(){
		return $this->hasManyThrough('App\Information', 'App\Device');
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

	public static function importCategory($request) {
		$new_category = new Category;
		$new_category->name = $request->get("name");
		//$new_category->deleted_at = $request->get('deleted_at');
		$new_category->save();
	}

	public static function viewCategories($request) {
		$categories = Category::with(['devices'])->latest('updated_at');

		$categories = $categories->where('name', 'LIKE', '%'.$request->get('filter').'%')->paginate(25);

		$categories->setPath('/');
		return view('home', compact('categories'));
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

	public static function fetch_devices_info_value( $info_id, $category_id) {
		$json = [];
		$info = "";
		$devices = Device::where('category_id', $category_id)->get();

		foreach ($devices as $device) {
			foreach ($device->information as $dev_info) {
				if ($dev_info->field_id == $info_id) {
					$json[] =[
						'inf_value' => $dev_info->value,
						'device_name' => $device->name
					];
				}
			}
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
