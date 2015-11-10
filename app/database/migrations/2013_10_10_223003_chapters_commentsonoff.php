<?php

use Illuminate\Database\Migrations\Migration;

class ChaptersCommentsonoff extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('chapters', function($table)
			{
				$table->boolean('comments_onoff')->default(true);

			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('chapters', function($table)
			{
				$table->dropColumn('comments_onoff');
			});
	}

}