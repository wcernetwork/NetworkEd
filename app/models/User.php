<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait, SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * Append additional attributes to define user role
	 */
	protected $appends = array('is_moderator', 'is_admin');

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	protected $fillable = array(
				'email',
				'password',
				'role_id',
				'first_name',
				'last_name',
				'city',
				'state',
				'zip',
				'organization',
				'bio',
				'role_title',
				'role_title_other',
				'photo',
				'background_image',
				'confirmation_code',
				'moderator_requested',
				'share_email',
				'social_link_1',
				'social_link_2'
				);

	public function role()
	{
		return $this->belongsTo('Role');
	}

	public function posts()
	{
		return $this->hasMany('Post');
	}

	public function favorites()
	{
		return $this->hasMany('Favorite');
	}

	/*role 1 is admin, role 2 is moderator. This determines if the user can approve/reject posts*/
	public function getIsModeratorAttribute()
    {
        return $this->role_id == 1 || $this->role_id == 2;
    }

    public function getIsAdminAttribute()
    {
        return $this->role_id == 1;
    }

}
