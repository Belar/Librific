<?php

use Illuminate\Database\Migrations\Migration;

class AddPublicStateSecretLinkChaptersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('chapters', function($table)
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
		Schema::table('chapters', function($table)
			{
				$table->dropColumn('public_state', 'secret_link');
			});
	}

}