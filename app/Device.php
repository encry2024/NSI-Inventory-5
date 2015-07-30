<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use App\Audit;
use Illuminate\Support\Facades\Auth;

class Device extends Eloquent implements SluggableInterface {

	//
	use SoftDeletes, SluggableTrait, RecordsActivity;

	protected $softDelete = true;
	protected $dates = ['deleted_at'];

	protected $fillable = ['name', 'slug', 'status_id', 'category_id', 'availability', 'owner_id'];
	protected $touches = ['category', 'owner'];

	protected $sluggable = array(
		'build_from' => 'name',
	);

	public function getFullnameAttribute()
	{
		return $this->name;
	}

    public function category() {
        return $this->belongsTo('App\Category')->withTrashed();
    }

	public function information() {
		return $this->hasMany('App\Information');
	}

	public function status() {
		return $this->belongsTo('App\Status');
	}

	public function owner() {
		return $this->belongsTo('App\Owner');
	}

	public function devicelog() {
		return $this->hasManyThrough('DeviceLog', 'Device', 'item_id', 'device_id');
	}

	# FUNCTIONS

	public static function store_device($device, $inputs) {
		$device_name = $device['name'];
		$category_id = $inputs['category_id'];

		//return $device;
		$update_category = Category::find($category_id);
		$update_category->touch();

		$new_device = new Device();
		$new_device->name = $device_name;
		$new_device->category_id = $category_id;
		$new_device->availability = 'AVAILABLE';
		$new_device->status_id = 1;

		if ($new_device->save()) {
			foreach ($inputs as $key => $value) {
				//return $devices;
				if (strpos($key, 'field') !== false) {
					$field = explode('-', $key);
					$field_id = $field[1];

					$information = new Information();
					$information->device_id = $new_device->id;
					$information->field_id = $field_id;
					$information->value = $value;
					$information->save();
				}
			}

			$audit = new Audit();
			$audit->auditUserEvent(Auth::user()->id, 'create', $update_category->name, $new_device->name);
		}

		return redirect()->back()->with('success_msg', 'Device :: '.$new_device->name.' was successfully saved.');
	}

	# CHANGE DEVICE STATUS
	public static function change_status($input, $id) {
		$status_id = $input->get('status_id');
		$device_id = $id;

		$device = Device::find($device_id);

		Device::find($device_id)->update(['status_id' => $status_id]);
		$status = Status::find($status_id);

		$deviceStatus = new DeviceStatus();
		$deviceStatus->status_id = $status_id;
		$deviceStatus->device_id = $device_id;
		$deviceStatus->user_id = \Auth::user()->id;
		$deviceStatus->save();

		$audit = new Audit();
		$audit->auditUserEvent(Auth::user()->id, 'changed status', $device->name, $status->status);


		$category = Category::find($device->category_id);
		$category->touch();

		return redirect()->back();
	}

	# FETCH STATUS HISTORY
	public static function fetch_Status( $id ) {
		$json = array();

		$device_statuses = DeviceStatus::where('device_id', $id)->get();

		foreach($device_statuses as $device_status) {
			$json[] = [
				'id' => $device_status,
				'user_name' => $device_status->user->name,
				'status_id' => $device_status->status->id,
				'device_id' => $device_status->device->id,
				'status' => $device_status->status->status,
				'description' => $device_status->status->description,
				'created_at' => date('m/d/Y h:i A', strtotime($device_status->created_at))
			];
		}

		return json_encode($json);
	}

	# FETCH ASSOCIATION HISTORY
	public static function fetch_assocHistory( $id ) {
		$json = array();
		$device_log = DeviceLog::where('device_id', $id)->get();

		foreach ($device_log as $dLog) {
			$json[] = [
				'name' => str_limit($dLog->owner->fullName(), $limit=14, $end='...'),
				'fullname' => $dLog->owner->fullName(),
				'slug' => $dLog->owner->slug,
				'user_type' => $dLog->user->type,
				'campaign' => $dLog->owner->location,
				'user_id' => $dLog->user->id,
				'user_name' => $dLog->user->name,
				'action' => $dLog->action,
				'created_at' => date('m/d/Y h:i A', strtotime($dLog->created_at))
			];
		}

		return json_encode($json);
	}

	# FETCH ALL ASSOCIATION AND DISSOCIATION HISTORY
	public static function fetchAllAssoc() {
		$json = array();
		$device_logs = DeviceLog::with(['deviceOwner', 'user', 'device', 'owner'])->get();

		foreach ($device_logs as $device_log) {
			$json[] = [
				'name' => $device_log->owner->fullName(),
				'fullname' => $device_log->owner->fullName(),
				'owner_slug' => $device_log->owner->slug,
				'device_slug' => $device_log->device->slug,
				'user_slug' => $device_log->user->slug,
				'campaign' => $device_log->owner->location,
				'category_name' => $device_log->device->category->name,
				'user_id' => $device_log->user->id,
				'device_name' => $device_log->device->name,
				'user_name' => $device_log->user->name,
				'action' => $device_log->action,
				'created_at' => date('m/d/Y h:i A', strtotime($device_log->created_at))
			];

		}
		return json_encode($json);
	}

	# FETCH ALL DEVICE
	public static function fetchAllDevice( $category_id ) {
		/*foreach ($devices as $device) {;
			foreach ($device->information as $dev_info) {
				if ($dev_info->field->category_label == "Brand") {
					$brand 	= $dev_info->value;
				}

				if ($dev_info->field->category_label == "NSI Tag") {
					$tag = $dev_info->value;
				}
			}
			if ($device->owner_id != 0) {
				$json[] = array(
					'id' 				=> $device->id,
					'brand'				=> $brand,
					'owner'				=> str_limit($device->owner->fullName(), $limit='10', $end='...'),
					'status'			=> $device->status->status = 0 ? 4 : 1,
					'tag'				=> str_limit($tag, $limit = '10', $end = '...'),
					'slug'              => $device->slug,
					'owner_slug'		=> $device->owner->slug,
					'name' 				=> $device->name,
					'updated_at' 		=> date('m/d/Y h:i A', strtotime($device->updated_at)),
				);
			} else {
				$json[] = array(
					'id' 				=> $device->id,
					'brand'				=> $brand,
					'owner'				=> 'No Owner',
					'status'			=> $device->status->status = 0 ? 4 : 1,
					'tag'				=> str_limit($tag, $limit = '10', $end = '...'),
					'slug'              => $device->slug,
					'name' 				=> $device->name,
					'updated_at' 		=> date('m/d/Y h:i A', strtotime($device->updated_at)),
				);
			}
		}
		return json_encode($json);*/
	}

	public static function importDevice($request) {
		$new_device = new Device();
		$new_device->id = $request->get('id');
		$new_device->name = $request->get("name");
		$new_device->category_id = $request->get('category_id');
		$new_device->owner_id = $request->get('owner_id');
		$new_device->status_id = $request->get('status_id');
		$new_device->comment = $request->get('comment');
		$new_device->availability = $request->get('availability');
		$new_device->save();
	}

	public static function getInformation() {
		$json = array();
		$ctr = 0;
		$devices = Device::with('information')->get();

		foreach ($devices as $device) {
			foreach ($device->information as $device_information) {
				$json[] = [
					'id'	=> ++$ctr,
					'device_id' => $device->id,
					'device_name' => $device->name,
					'device_slug' => $device->slug,
					'information' => $device_information->value,
					'field' => $device_information->field->category_label
				];
			}
		}

		return json_encode($json);
	}

	public static function assoc_device($category_slug) {
		$json = [];
		$category = Category::whereSlug($category_slug)->first();
		$devices = Device::where('category_id', $category->id)->where('owner_id','!=','0')->get();

		foreach ($devices as $device) {
			$json[] = [
				'device_slug' => $device->slug,
				'device_name' => $device->name,
				'owner_slug' => $device->owner->slug,
				'owner_name' => $device->owner->fullName(),
			];
		}

		return json_encode($json);
	}

	public static function avail_device($category_slug) {
		$json = [];
		$category = Category::whereSlug($category_slug)->first();
		$devices = Device::where('availability', 'Available')->where('category_id', $category->id)->get();

		foreach ($devices as $device) {
			$json[] = [
				'id' => $device->id,
				'device_slug' => $device->slug,
				'device_name' => $device->name,
			];
		}

		return json_encode($json);
	}

	public static function defect_device($category_slug) {
		$json = [];
		$category = Category::whereSlug($category_slug)->first();
		$devices = Device::where('status_id', '!=', 1)->where('category_id', $category->id)->get();

		foreach ($devices as $device) {
			$json[] = [
				'id' => $device->id,
				'device_slug' => $device->slug,
				'device_name' => $device->name,
				'status_value' => $device->status->status,
				'status_desc' => $device->status->description,
				'status_slug' => $device->status->slug
			];
		}

		return json_encode($json);
	}

	public static function show_AllAvailableDevices() {
		$json = [];
		$devices = Device::with('category')->where('status_id', 1)->where('owner_id', 0)->get();

		foreach($devices as $device) {
			$json[] = [
				'category_name' => $device->category->name,
				'category_slug' => $device->category->slug,
				'device_name' => $device->name,
				'device_slug' => $device->slug,
				'updated_at' => date('F d, Y h:i A', strtotime($device->updated_at))
			];
		}

		return json_encode($json);
	}

	public static function getCountOfAvailableDevices() {
		return count(Device::where('status_id', 1)->where('owner_id', 0)->get());
	}

	public static function getCountOfDefectiveDevices() {
		return count(Device::where('status_id', '!=', 1)->get());
	}

}
