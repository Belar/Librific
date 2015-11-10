<?php

use Illuminate\Database\Migrations\Migration;

class BooksAddGenreTags extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('books', function($table)
			{
				$table->string('genre');
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
		Schema::table('books', function($table)
			{
				$table->dropColumn('genre', 'tags');
			});
	}

}