<?php

class TagPost extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tag_posts';
	public $timestamps = false;

	protected $fillable = array(
				'post_id',
				'tag_id',
				);

	public function tag()
	{
		return $this->hasMany('Tag', 'id', 'tag_id');
	}

	public function post()
	{
		return $this->hasOne('Post', 'id', 'post_id');
	}
}
