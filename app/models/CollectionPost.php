<?php

class CollectionPost extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'collection_posts';

	protected $fillable = array(
				'post_id',
				'collection_id',
				'order',
				);

}
