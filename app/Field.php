<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class Field extends Eloquent
{
    //
    use SoftDeletes, RecordsActivity;
    protected $softDelete = true;
    protected $dates = ['deleted_at'];


    protected $fillable = ['category_id', 'category_label'];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public static function importField($request)
    {
        $new_field = new Field;
        $new_field->category_id = $request->get("category_id");
        $new_field->category_label = $request->get("category_label");
        $new_field->save();
    }
}
