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

	/**
	 * Getter for birth_date attributes.
	 * 
	 * @return void
	 */
	public function get_birth_date()
	{
		$date = DateTime::createFromFormat(Base_Controller::STORED_DATE_FORMAT, $this->get_attribute('birth_date'));
		return $date->format(Base_Controller::PRESENTED_DATE_FORMAT);
	}
}