<?php

class Like extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'likes';

	protected $fillable = array(
				'user_id',
				'post_id',
				);
}
