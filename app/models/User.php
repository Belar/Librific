<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');
	
	protected $fillable = array('nick', 'email');
	
	protected $guarded = array('id', 'password');

	
	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}
	
	
	// Relationship definisions
	
	public function books()
    {
        return $this->hasMany('Book');
    }
	
	public function chapters()
    {
        return $this->hasMany('Chapter');
    }
	
	public function follow()
	{
	  return $this->belongsToMany('User', 'follows', 'follower_id', 'followed_id');
	}
	
	public function followers()
	{
	  return $this->belongsToMany('User', 'follows', 'followed_id', 'follower_id');
	}
	
	/*This one is tricky, favourite chapter isn't for chapter. It depends on USER*/
	public function favouriteCh()
	{
	  return $this->belongsToMany('Chapter', 'favourite_ch', 'user_id', 'favourite_id');
	}
	
	public function favouriteChs()
	{
	  return $this->belongsToMany('Chapter', 'favourite_ch', 'favourite_id', 'user_id');
	}
}