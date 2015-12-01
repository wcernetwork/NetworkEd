<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionPostTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('collection_posts', function(Blueprint $table)
		{
			$table->increments('id');
			
			$table->integer('post_id')->unsigned();
			$table->foreign('post_id')->references('id')->on('posts');

			$table->integer('collection_id')->unsigned();
			$table->foreign('collection_id')->references('id')->on('collections');

			$table->integer('order');

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
		Schema::drop('collection_post');
	}

}
