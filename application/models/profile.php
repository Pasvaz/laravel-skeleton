<?php

class Profile extends Eloquent 
{
	/**
	 * Belongs to `user` table.
	 *
	 * @access public
	 * @return User
	 */
	public function user()
	{
		return $this->belong_to('User');
	}
}