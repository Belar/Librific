<?php

class SentryGroupSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		
		DB::table('groups')->delete();


		Sentry::getGroupProvider()->create(array(
	        'name'        => 'Admins',
	        'permissions' => array(
	            'admin' => 1,
	            'mod' => 1,
	        )));
		
		Sentry::getGroupProvider()->create(array(
	        'name'        => 'Mods',
	        'permissions' => array(
	            'admin' => 0,
	            'mod' => 1,
	        )));
	}

}