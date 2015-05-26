<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;

class Category extends Eloquent implements SluggableInterface {

	//
    use SoftDeletes;
    use SluggableTrait;

    protected $softDelete = true;
    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'slug'];

    protected $sluggable = array( 'build_from' => 'name' );

    public function getFullnameAttribute()
    {
        return $this->name;
    }

    public function fields() {
        return $this->hasMany('App\Field');
    }

    public function devices() {
        return $this->hasMany('Device');
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

	public static function fetchCategories() {
		$json = array();
		$categories = Category::all();
		foreach ($categories as $category) {
			$json[] = array(
				'id' 				=> $category->id,
				'slug'              => $category->slug,
				'name' 				=> $category->name,
				'updated_at' 		=> date('F d, Y [ h:i A D ]', strtotime($category->updated_at)),
			);
		}
		return json_encode($json);
	}
}
