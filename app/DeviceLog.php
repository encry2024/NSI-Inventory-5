<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Auth;
use App\Device;

class DeviceLog extends Eloquent
{
    //
    use RecordsActivity;
    protected $table = 'device_logs';

    public function owner()
    {
        return $this->belongsTo('App\Owner');
    }

    public function device()
    {
        return $this->belongsTo('App\Device')->withTrashed();
    }

    public function deviceOwner()
    {
        return $this->hasManyThrough('App\Device', 'App\Owner', 'id', 'owner_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function getCountDeviceLog()
    {
        return count(DeviceLog::all());
    }
}
