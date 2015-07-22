<?php

use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder {

	public function run()
	{
		DB::table('statuses')->insert(
			array(
				array(
					'status'		=> 'NORMAL',
					'slug'			=> 'normal',
					'description'	=> 'Description for devices with no defects.',
					'created_at'	=> date('Y-m-d H:i:s'),
					'updated_at'	=> date('Y-m-d H:i:s')
				),
				array(
					'status'		=> 'DEFECTIVE',
					'slug'			=> 'defective',
					'description'	=> 'Description for devices with defects.',
					'created_at'	=> date('Y-m-d H:i:s'),
					'updated_at'	=> date('Y-m-d H:i:s')
				),
				array(
					'status'		=> 'RETIRED',
					'slug'			=> 'retired',
					'description'	=> 'Description for retired devices.',
					'created_at'	=> date('Y-m-d H:i:s'),
					'updated_at'	=> date('Y-m-d H:i:s')
				),
				array(
					'status'		=> 'NOT SPECIFIED',
					'slug'			=> 'not-specified',
					'description'	=> 'Devices that was returned with no status provided.',
					'created_at'	=> date('Y-m-d H:i:s'),
					'updated_at'	=> date('Y-m-d H:i:s')
				),
				array(
					'status'		=> 'INACTIVE',
					'slug'			=> 'inactive',
					'description'	=> 'Devices that is no longer active.',
					'created_at'	=> date('Y-m-d H:i:s'),
					'updated_at'	=> date('Y-m-d H:i:s')
				),
			)
		);
	}

}