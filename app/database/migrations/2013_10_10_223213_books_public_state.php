<?php

use Illuminate\Database\Migrations\Migration;

class BooksPublicState extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('books', function($table)
			{
				$table->boolean('public_state')->default(true);
				$table->string('secret_link');

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
				$table->dropColumn('public_state', 'secret_link');
			});
	}

}