<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Information extends Eloquent
{
    use SoftDeletes, RecordsActivity;
    protected $fillable = ['value'];
    protected $touches = ['field', 'device'];
    
    protected $softDelete = true;

    public function field()
    {
        return $this->belongsTo('App\Field');
    }

    public function device()
    {
        return $this->belongsTo('App\Device', 'device_id', 'id');
    }

    public static function update_information($request)
    {
        $info_val = $request->get('value');
        $info_id = $request->get('inf_id');
        $device_id = $request->get('device_id');

        Information::find($info_id)->update(['value' => $info_val]);
        $device = Device::find($device_id);
        $device->touch();
        return redirect()->back();
    }

    public static function importInformation($request)
    {
        $new_information = new Information();
        $new_information->device_id = $request->get('device_id');
        $new_information->field_id = $request->get('field_id');
        $new_information->value = $request->get('value');
        $new_information->save();
    }

    public static function getInformationCount()
    {
        return count(Information::all());
    }

    public static function viewInformation($request)
    {
        $fields = Field::groupBy('category_label')->get();

        $information = DB::table('fields')
            ->join('information', function ($join) use ($request) {
                $join->on('fields.id', '=', 'information.field_id')
                    ->where('fields.category_label', '=', $request->get('categoryLabel'))
                    ->where('information.value', 'LIKE', '%'.$request->get('filter').'%');
            })
            ->join('devices', function ($join) {
                $join->on('devices.id', '=', 'information.device_id');
            })
            ->join('categories', function ($join) {
                $join->on('categories.id', '=', 'devices.category_id');
            })
            ->select('information.*', 'fields.*', 'devices.*', DB::raw('inv_devices.name as "device_name"'), DB::raw('inv_devices.slug as "device_slug"'), 'categories.*', DB::raw('inv_categories.name as "category_name"'), DB::raw('inv_categories.slug as "category_slug"'))
            ->paginate(25);


        $information->setPath('information');
        return view('field.index', compact('fields', 'information'));
    }
}
