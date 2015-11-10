<?php

use Illuminate\Database\Migrations\Migration;

class UsersAddSocialSoftdeleteQuicknoteBookmark extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function($table)
			{
				$table->string('goodreads')->nullable();
				$table->string('youtube')->nullable();
				$table->text('quick_note');
				$table->string('bookmark')->nullable();
				
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
		Schema::table('users', function($table)
			{
				$table->dropColumn('goodreads', 'youtube', 'quick_note', 'bookmark');
			});
	}

}