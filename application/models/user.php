<?php

class User extends Eloquent 
{
	/**
	 * Has Many relationship to `profiles` table.
	 *
	 * @access public
	 * @return Profile
	 */
	public function profile()
	{
		return $this->has_one('Profile');
	}


	/**
	 * Setter for password attributes.
	 * 
	 * @access public 
	 * @param  string   $password
	 * @return void
	 */
	public function set_password($password)
	{
		$this->set_attribute('password', Hash::make($password));
	}
}