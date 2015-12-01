<?php

class TagCategory extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tag_categories';
	public $timestamps = false;

	protected $fillable = array(
				'slug',
				'name',
				'order',
				);

}
