<?php

use Illuminate\Database\Migrations\Migration;

class ChaptersAddTags extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('chapters', function($table)
			{
				$table->text('tags');

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
				$table->dropColumn('tags');
			});
	}

}