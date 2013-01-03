<?php

class Profile extends Eloquent 
{
	public static $key = 'user_id';
	public static $timestamps = false;

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
	public function get_long_birth_date()
	{
		$date = DateTime::createFromFormat(Localized_Date::STORED_DATE_FORMAT, $this->get_attribute('birth_date'));
		return new Localized_Date($date, null, IntlDateFormatter::LONG, IntlDateFormatter::NONE);
	}

	/**
	 * Getter for birth_date attributes.
	 * 
	 * @return void
	 */
	public function get_birth_date()
	{
		$date = DateTime::createFromFormat(Localized_Date::STORED_DATE_FORMAT, $this->get_attribute('birth_date'));
		return new Localized_Date($date, null, IntlDateFormatter::SHORT, IntlDateFormatter::NONE);
	}
}