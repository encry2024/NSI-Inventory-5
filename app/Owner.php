<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class Owner extends Eloquent implements SluggableInterface
{
    //
    use SoftDeletes, RecordsActivity;
    use SluggableTrait;

    protected $table = 'owners';

    protected $softDelete = true;
    protected $dates = ['deleted_at'];
    protected $fillable = ['firstName', 'lastName', 'location', 'slug'];

    protected $sluggable = [ 'build_from' => 'fullname', 'save_to' => 'slug' ];

    public function getFullnameAttribute()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function devices()
    {
        return $this->hasMany('App\Device');
    }

    public function device_logs()
    {
        return $this->hasManyThrough('App\Device', 'App\DeviceLog', 'device_id', 'owner_id');
    }

    public function fullName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public static function importOwner($request)
    {
        if ($request->get('firstname') == "" || $request->get('lastname') == "") {
            $new_owner = new Owner();
            $new_owner->firstName = "-";
            $new_owner->lastName = "-";
            $new_owner->location = "-";
            $new_owner->save();
        } else {
            $new_owner = new Owner();
            $new_owner->firstName = $request->get('firstname');
            $new_owner->lastName = $request->get('lastname');
            $new_owner->location = $request->get('name');
            $new_owner->save();
        }
    }

    public static function editOwner($slug)
    {
        //return $slug;

        Owner::find($slug->id)->update(['firstName' => Input::get('firstName'), 'lastName' => Input::get('lastName'),
                'location' => Input::get('campaign')
            ]);

        return redirect('owner')->with('success_msg', 'Owner has been updated');
    }


    public static function getOwnerCount()
    {
        return count(Owner::all());
    }
}
