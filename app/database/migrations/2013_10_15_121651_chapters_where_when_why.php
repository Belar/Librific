<?php

use Illuminate\Database\Migrations\Migration;

class ChaptersWhereWhenWhy extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('chapters', function($table)
			{
				$table->string('where')->nullable();
				$table->string('when')->nullable();
				$table->text('why')->nullable();
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
				$table->dropColumn('where', 'when', 'why');
			});
	}

}