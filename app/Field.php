<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class Field extends Eloquent {

	//
    use SoftDeletes;
    protected $softDelete = true;
    protected $dates = ['deleted_at'];


    protected $fillable = ['category_id', 'category_label'];

	public static function importField() {
		set_time_limit(0);

		$file = Input::file( 'xl' );

		//move the file to storage/uploads folder with its original file name
		$file->move(storage_path() . '/uploads', $file->getClientOriginalName());

		//Load the sheet and convert it into array
		$sheet = Excel::load( storage_path() . '/uploads/' . $file->getClientOriginalName())->toArray();

		foreach ($sheet as $row) {
			$new_field = new Field();
			$new_field->category_id = $row['category_id'];
			$new_field->category_label = $row['category_label'];
			$new_field->save();
		}

		return redirect()->back()->with('success_msg', 'Files has been successfully imported.');
	}
}
