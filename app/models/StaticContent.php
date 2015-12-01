<?php

class StaticContent extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'static_content';

	public $timestamps = false;

	protected $fillable = array(
				'name',
				'content',
				'backup_content',
				);

}
