<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Field extends Eloquent {

	//
    use SoftDeletes;
    protected $softDelete = true;
    protected $dates = ['deleted_at'];


    protected $fillable = ['category_id', 'category_label'];

}
