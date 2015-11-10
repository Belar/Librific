<?php

class SentryUserGroupSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('users_groups')->delete();

		$adminUser = Sentry::getUserProvider()->findByLogin(''); //initial user e-mail address

		$adminGroup = Sentry::getGroupProvider()->findByName('Admins');
		$modGroup = Sentry::getGroupProvider()->findByName('Mods');

	    // Assign the groups to the users
	    $adminUser->addGroup($modGroup);
	    $adminUser->addGroup($adminGroup);
	}

}
