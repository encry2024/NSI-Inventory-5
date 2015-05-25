<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInformationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('information', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('device_id')->unsigned();
            $table->integer('field_id')->unsigned();
            $table->string('value')->nullable();
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
		Schema::drop('information');
	}

}
