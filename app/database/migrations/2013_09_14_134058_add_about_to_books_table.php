<?php

use Illuminate\Database\Migrations\Migration;

class AddAboutToBooksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('books', function($table)
			{
				$table->text('about');
			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('books', function($table)
			{
				$table->dropColumn('about');
			});
	}

}