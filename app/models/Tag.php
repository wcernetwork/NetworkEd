<?php

class Tag extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tags';
	public $timestamps = false;
	protected $softDelete = false;

	protected $fillable = array(
				'slug',
				'name',
				'category_id',
				'pending',
				);

	public function tag_category()
	{
		return $this->hasOne('TagCategory', 'id', 'category_id');
	}

}
