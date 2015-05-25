<?php

use Illuminate\Database\Seeder;

class UsernameSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->insert(
            array(
                array(
                    'email'			=> env('EMAIL_1'),
                    'name'			=> env('NAME_1'),
                    'type'			=> env('TYPE_1'),
                    'password'		=> Hash::make(env('PASSWORD_1')),
                    'created_at'	=> date('Y-m-d H:i:s'),
                    'updated_at'	=> date('Y-m-d H:i:s')
                ),
				array(
					'email'			=> env('EMAIL_2'),
					'name'			=> env('NAME_2'),
					'type'			=> env('TYPE_2'),
					'password'		=> Hash::make(env('PASSWORD_2')),
					'created_at'	=> date('Y-m-d H:i:s'),
					'updated_at'	=> date('Y-m-d H:i:s')
				),
				array(
					'email'			=> env('EMAIL_3'),
					'name'			=> env('NAME_3'),
					'type'			=> env('TYPE_3'),
					'password'		=> Hash::make(env('PASSWORD_3')),
					'created_at'	=> date('Y-m-d H:i:s'),
					'updated_at'	=> date('Y-m-d H:i:s')
				),
				array(
					'email'			=> env('EMAIL_4'),
					'name'			=> env('NAME_4'),
					'type'			=> env('TYPE_4'),
					'password'		=> Hash::make(env('PASSWORD_4')),
					'created_at'	=> date('Y-m-d H:i:s'),
					'updated_at'	=> date('Y-m-d H:i:s')
				),
				array(
					'email'			=> env('EMAIL_5'),
					'name'			=> env('NAME_5'),
					'type'			=> env('TYPE_5'),
					'password'		=> Hash::make(env('PASSWORD_5')),
					'created_at'	=> date('Y-m-d H:i:s'),
					'updated_at'	=> date('Y-m-d H:i:s')
				),
				array(
					'email'			=> env('EMAIL_6'),
					'name'			=> env('NAME_6'),
					'type'			=> env('TYPE_6'),
					'password'		=> Hash::make(env('PASSWORD_6')),
					'created_at'	=> date('Y-m-d H:i:s'),
					'updated_at'	=> date('Y-m-d H:i:s')
				),
				array(
					'email'			=> env('EMAIL_7'),
					'name'			=> env('NAME_7'),
					'type'			=> env('TYPE_7'),
					'password'		=> Hash::make(env('PASSWORD_7')),
					'created_at'	=> date('Y-m-d H:i:s'),
					'updated_at'	=> date('Y-m-d H:i:s')
				),
				array(
					'email'			=> env('EMAIL_8'),
					'name'			=> env('NAME_8'),
					'type'			=> env('TYPE_8'),
					'password'		=> Hash::make(env('PASSWORD_8')),
					'created_at'	=> date('Y-m-d H:i:s'),
					'updated_at'	=> date('Y-m-d H:i:s')
				),
            )
        );
    }

}