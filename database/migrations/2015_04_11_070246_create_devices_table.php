<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('devices', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('owner_id')->unsigned();
            $table->integer('status_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('availability');
            $table->string('comment');
			$table->timestamps();
            $table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('devices');
	}

}
