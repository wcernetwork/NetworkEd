<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Post extends Eloquent {

	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'posts';

	protected $fillable = array(
				'title',
				'description',
				'thumbnail',
				'thumbnail_sm',
				'primary_media',
				'primary_media_type',
				'media_2',
				'media_2_type',
				'media_3',
				'media_3_type',
				'post_type_id',
				'location',
				'address',
				'city',
				'state',
				'zip',
				'user_id',
				'likes',
				'expiration_date',
				'video_id',
				'video_host',
				'latitude',
				'longitude',
				'approved',
				'contact_name',
				'contact_email',
				'contact_phone',
				'contact_website',
				'approved_at',
				'last_viewed_at',
				'num_views',
				'summary'
				);

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function post_type()
	{
		return $this->hasOne('PostType', 'id', 'post_type_id');
	}

	public function likelist()
	{
		return $this->hasMany('Like');
	}

	public function favorited()
	{
		return $this->hasMany('Favorite');
	}

	public function tags()
	{
		return $this->hasMany('TagPost', 'post_id', 'id');
	}
}
