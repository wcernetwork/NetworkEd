<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('posts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->text('description');

			$table->string('thumbnail');
			$table->string('thumbnail_sm');
			$table->string('primary_media');
			$table->string('primary_media_type');
			$table->string('media_2')->nullable();
			$table->string('media_2_type')->nullable();
			$table->string('media_3')->nullable();
			$table->string('media_3_type')->nullable();

			$table->string('video_id')->nullable();
			$table->string('video_host')->nullable();

			$table->integer('post_type_id')->unsigned();
			$table->foreign('post_type_id')->references('id')->on('post_types');

			$table->string('location');
			$table->string('address');
			$table->string('city');
			$table->string('state');
			$table->string('zip');

			$table->decimal('latitude', 10, 7);
			$table->decimal('longitude', 10, 7);

			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');

			$table->integer('likes')->unsigned();
			$table->dateTime('expiration_date')->nullable();

			$table->integer('approved')->unsigned();

			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('posts');
	}

}
