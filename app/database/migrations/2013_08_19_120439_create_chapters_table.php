<?php

use Illuminate\Database\Migrations\Migration;

class CreateChaptersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('chapters', function($table)
			{
				$table->increments('id');
				$table->string('slug', 255);
				$table->string('title');
				$table->integer('book_id');
				$table->integer('author_id');
				$table->integer('chapter_n')->default(0);
				$table->text('text');
				$table->string('chapter_cover');
				
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
		Schema::drop('chapters');
	}

}