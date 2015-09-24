<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Device extends Eloquent implements SluggableInterface
{
    use SoftDeletes, SluggableTrait, RecordsActivity;

    protected $softDelete = true;
    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'slug', 'status_id', 'category_id', 'availability', 'owner_id'];
    protected $touches = ['category'];

    protected $sluggable = array(
        'build_from' => 'name',
    );

    public function getFullnameAttribute()
    {
        return $this->name;
    }

    public function category()
    {
        return $this->belongsTo('App\Category')->withTrashed();
    }

    public function information()
    {
        return $this->hasMany('App\Information');
    }

    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    public function owner()
    {
        return $this->belongsTo('App\Owner');
    }

    public function devicelog()
    {
        return $this->hasManyThrough('DeviceLog', 'Device', 'item_id', 'device_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    # FUNCTIONS

    public static function store_device($device, $inputs)
    {
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
        }

        return redirect()->back()->with('success_msg', 'Device :: '.$new_device->name.' was successfully saved.');
    }

    # CHANGE DEVICE STATUS
    public static function change_status($input, $id)
    {
        $status_id = $input->get('status_id');
        $device_id = $id;

        $device = Device::find($device_id);

        Device::find($device_id)->update(['status_id' => $status_id]);

        $deviceStatus = new DeviceStatus();
        $deviceStatus->status_id = $status_id;
        $deviceStatus->device_id = $device_id;
        $deviceStatus->user_id = \Auth::user()->id;
        $deviceStatus->save();

        $category = Category::find($device->category_id);
        $category->touch();

        return redirect()->back();
    }

    # FETCH STATUS HISTORY
    public static function show_device_status($device_slug)
    {
        $ctr = 0;
        $device = Device::whereSlug($device_slug)->first();
        $statuses = DeviceStatus::with(['user', 'status'])->where('device_id', $device->id);
        $statuses = $statuses->latest('device_statuses.created_at')->paginate(25);
        $statuses->setPath('status');

        return view('devices.device_tab.status', compact('statuses', 'ctr', 'device'));
    }

    public static function show_device_ownership($device_slug)
    {
        $ctr = 0;
        $device = Device::whereSlug($device_slug)->first();
        $ownerships = DeviceLog::with(['user', 'device', 'owner'])->where('device_id', $device->id);
        $ownerships = $ownerships->latest('device_logs.created_at')->paginate(25);
        $ownerships->setPath('ownerships');

        return view('devices.device_tab.ownership', compact('ownerships', 'ctr', 'device'));
    }

    public static function importDevice($request)
    {
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

    public static function assoc_device($category_slug)
    {
        $json = [];
        $category = Category::whereSlug($category_slug)->first();
        $devices = Device::where('category_id', $category->id)->where('owner_id', '!=', '0')->get();

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

    public static function avail_device($category_slug)
    {
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

    public static function defect_device($category_slug)
    {
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

    public static function show_AllAvailableDevices()
    {
        $json = [];
        $devices = Device::with('category')->where('status_id', 1)->where('owner_id', 0)->get();

        foreach ($devices as $device) {
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

    public static function retrieveAvailableDevices($category_slug, $request)
    {
        $count = 0;
        $category = Category::whereSlug($category_slug)->first();
        $devices = Device::with(['category', 'information'])->where('category_id', $category->id)->where('owner_id', 0);
        $devices = $devices->where('name', 'LIKE', '%'.$request->get('filter').'%')->paginate(25);
        $devices->setPath('available_devices');

        return view('devices.available_devices', compact('category', 'devices', 'count'));
    }

    public static function viewAssociation($request)
    {
        $devices = DB::table('devices')
            ->select('owners.*',
                DB::raw('inv_owners.firstName as "owner_fName"'),
                DB::raw('inv_owners.lastName as "owner_lName"'),
                DB::raw('inv_owners.slug as "owner_slug"'),
                'devices.*', DB::raw('inv_devices.name as "device_name"'), DB::raw('inv_devices.slug as "device_slug"'),
                'categories.*', DB::raw('inv_categories.name as "category_name"'), DB::raw('inv_categories.slug as "category_slug"'), 'users.*',
                DB::raw('inv_users.name as "user_name"'),
                DB::raw('inv_users.id as "user_id"'))
            ->join('owners', function ($join) use ($request) {
                $join->on('devices.owner_id', '=', 'owners.id');
            })
            ->leftJoin('categories', function ($join) {
                $join->on('devices.category_id', '=', 'categories.id');
            })
            ->leftJoin('users', function ($join) {
                $join->on('devices.user_id', '=', 'users.id');
            });

        if ($request->has('filter')) {
            $devices = $devices->where('owners.firstName', 'LIKE', '%'.$request->get('filter').'%')
                               ->orWhere('owners.lastName', 'LIKE', '%'.$request->get('filter').'%')
                               ->orWhere('devices.name', 'LIKE', '%'.$request->get('filter').'%');
        }

        $devices = $devices->latest('devices.created_at')->paginate(25);

        $devices->setPath('all');
        return view('associates.index', compact('devices'));
    }

    public static function deleteAll($request)
    {
        $totalRequest = count($request);
        foreach ($request as $deleteRequest) {
            $device = Device::whereSlug($deleteRequest);
            $device->delete();
        }

        return redirect()->back()->with('message', $totalRequest.' devices was successfully deleted');
    }

    public static function cLog($owner_id, $id)
    {
        $success_msg = "";
        $message_label = "";

        if ($owner_id != '') {
            $device = Device::find($id);
            $device->owner_id = $owner_id;
            $device->user_id = Auth::user()->id;
            $device->save();

            $owner = Owner::find($owner_id);

            $device_log = new DeviceLog();
            $device_log->owner_id = $owner_id;
            $device_log->device_id = $id;
            $device_log->user_id = \Auth::user()->id;
            $device_log->action = "ASSOCIATE";
            $device_log->save();

            $success_msg = $device->name .' was ASSOCIATED with ' . $owner->fullName();
            $message_label = "alert-success";
        } else {
            $success_msg = 'Owner doesn\'t exist or Owner was not provided';
            $message_label = "alert-danger";
        }

        return redirect()->back()->with('success_msg', $success_msg)->with('message_label', $message_label);
    }

    public static function disassocLog($id)
    {
        $device = Device::find($id);

        $device_log = new DeviceLog();
        $device_log->owner_id = $device->owner_id;
        $device_log->device_id = $id;
        $device_log->user_id = \Auth::user()->id;
        $device_log->action = "DISASSOCIATE";
        $device_log->save();

        $owner_id = $device_log->owner_id;

        $owner = Owner::find($owner_id);

        $device->owner_id = 0;
        $device->save();

        return redirect()->back()->with('success_msg', $device->name . ' was DISASSOCIATED to '.$owner->fullName())->with('message_label', 'alert-success');
    }

    public static function show_device_note($device_slug, $request)
    {
        $ctr = 0;
        $device = Device::whereSlug($device_slug)->first();
        $device_id = $device->id;

        $notes = Note::with(['user'])->where('device_id', $device_id);
        $notes = $notes->where('note', 'LIKE', '%'.$request->get('filter').'%');
        $notes = $notes->latest('notes.created_at')->paginate(25);
        $notes->setPath('notes');

        return view('devices.device_tab.note', compact('device', 'notes', 'ctr'));
    }

    /**
     * Device getcounts
     *
     **/
    

    public static function getCountOfAvailableDevices()
    {
        return count(Device::where('status_id', 1)->where('owner_id', 0)->get());
    }

    public static function getCountOfDefectiveDevices()
    {
        return count(Device::where('status_id', '!=', 1)->get());
    }
}
