<?php

class Favorite extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'favorites';

	protected $fillable = array(
				'user_id',
				'post_id',
				);

	public function post()
	{
		return $this->hasOne('Post', 'id', 'post_id');
	}

}
