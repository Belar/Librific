<?php

use Illuminate\Database\Migrations\Migration;

class CreateFavouriteCh extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('favourite_ch', function($table)
			{
				$table->increments('id');
				$table->integer('favourite_id');
				$table->integer('user_id');
				$table->timestamps();
			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('favourite_ch');
	}

}