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

	public function Loggedin()
	{
		$this->last_login=new \DateTime;
		$this->save();
		if ($this->language) Session::put('language', Auth::user()->language);
	}

	static public function Find_Hash($hash64)
	{
		try 
		{
			$hash= base64_decode($hash64);
			return static::where('activation_hash', '=', $hash)->first();
		}
		catch (Exception $ex)
		{
			return null;
		}
	}

	/**
	 * Check the hash and change the password
	 * 
	 * @access public 
	 * @param  string   $hash64
	 * @param  string   $newpassword
	 * @return boolean success or failure
	 */
	static public function Change_Password($hash64, $newpassword)
	{
		try 
		{
			$hash= base64_decode($hash64);
			$user= static::where('activation_hash', '=', $hash)->first();
			if (!$user) return false;
			
			$user->set_attribute('activation_hash', null);
			$user->set_attribute('password', Hash::make($newpassword));
			$user->save();
			Auth::logout();
			return true;
		}
		catch (Exception $ex)
		{
			return false;
		}
	}

	public function Activate()
	{
		$this->set_attribute('activated', true);
		$this->set_attribute('activation_hash', null);
		$this->save();
	}

	public function Validate()
	{
		$activation_url = URL::base().'/login/activate/'.base64_encode($this->activation_hash);
		Bundle::start('swiftmailer');
		$mailer = IoC::resolve('mailer');
		$message = Swift_Message::newInstance('Message From '.Config::get('application.site-name'))
		    ->setFrom(array('register@example.com'=>Config::get('application.site-name')))
		    ->setTo(array($this->email => $this->name))
		    ->addPart( __('user.activate_account_mail', array('link' => $activation_url)), 'text/plain')
		    ->setBody( __('user.activate_account_mail', array('link' => $activation_url)), 'text/html');

		// Send the email
		$mailer->send($message);
	}

	public function Reset_Password()
	{
		$this->set_attribute('activated', true);
		$this->set_attribute('activation_hash', null);
		$this->save();
	}

	public function Forget_Password()
	{
		$this->activation_hash = hash('md4', Str::random(32));
		$this->save();
		$activation_url = URL::base().'/login/reset/'.base64_encode($this->activation_hash);
		try
		{
			Bundle::start('swiftmailer');
			$mailer = IoC::resolve('mailer');
			$message = Swift_Message::newInstance('Message From '.Config::get('application.site-name'))
			    ->setFrom(array('register@example.com'=>Config::get('application.site-name')))
			    ->setTo(array($this->email => $this->name))
			    ->addPart( __('user.reset_password_mail', array('link' => $activation_url)), 'text/plain')
			    ->setBody( __('user.reset_password_mail', array('link' => $activation_url)), 'text/html');

			// Send the email
			$mailer->send($message);
		}
		catch (Exception $ex)
		{
			//Handle exception
			return false;
		}
		return true;
	}
}