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

    public function getFullnameAttribute()
    {
        return $this->name;
    }

    public function fields() {
        return $this->hasMany('App\Field');
    }

    public function devices() {
        return $this->hasMany('App\Device');
    }

	public function delete() {
		// $device_info = $this->devices;
		foreach ($this->devices as $cat_dev) {
			$cat_dev->category_id = 0;
			$cat_dev->save();
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

	public static function importCategory() {
		$file = Input::file( 'xl' );

		//move the file to storage/uploads folder with its original file name
		$file->move(storage_path() . '/uploads', $file->getClientOriginalName());

		//Load the sheet and convert it into array
		$sheet = Excel::load( storage_path() . '/uploads/' . $file->getClientOriginalName())->toArray();

		foreach ($sheet as $row) {
			$new_category = new Category();
			$new_category->name = $row['name'];
			$new_category->save();
		}

		return redirect()->back()->with('success_msg', 'Files has been successfully imported.');
	}

	//total_device
	/*assoc_device
	av_device
	def_device*/
	public static function fetchCategories() {
		$json = array();
		$categories = Category::all();
		foreach ($categories as $category) {
			$json[] = array(
				'id' 				=> $category->id,
				'slug'              => $category->slug,
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
