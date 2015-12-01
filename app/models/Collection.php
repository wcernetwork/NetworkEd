<?php

class Collection extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'collections';

	protected $fillable = array(
				'slug',
				'name',
				'order',
				'thumbnail',
				'active',
				);

	public function posts()
	{
		return $this->belongsToMany('Post', 'collection_posts');
	}

}
