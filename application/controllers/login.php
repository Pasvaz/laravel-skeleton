<?php

class Login_Controller extends Base_Controller 
{
	//public $layout = 'layouts.main';
	//public $restful = true;
	/**
	 * Catch-all method for requests that can't be matched.
	 *
	 * @param  string    $method
	 * @param  array     $parameters
	 * @return Response
	 */
	public function __call($method, $parameters)
	{
		return Response::error('404');
	}

	public function get_activate($hash64='null')
	{
		$hash= base64_decode($hash64);
		if (!($user=User::where('activation_hash', '=', $hash)->first()))
			return Response::error('404');
		$user->activated=true;
		$user->activation_hash=null;
		$user->save();
		$messages=new \Laravel\Messages();
		$messages->add('user', __('Thank you for joining us, now you can login.'));
		$messages->add('alert_success', 'success');
		return Redirect::to('login')->with_errors($messages)->with_input();
	}

	public function get_newuser($action = null)
	{
		//$fmt = new IntlDateFormatter('az', IntlDateFormatter::FULL, IntlDateFormatter::NONE );
		//return $fmt->getPattern();
		return View::make('user.register');
	}

	public function post_newuser($action = null)
	{
		$date = DateTime::createFromFormat(Input::get('birthdate_format'), Input::get('birthdate'));
		//dd($date->format(Base_Controller::STORED_DATE_FORMAT));
		Input::merge(array('birthdate'=>$date->format(Localized_Date::STORED_DATE_FORMAT)));
		//dd(Input::all());
		$rules = array(
			'first_name' => 'required',
			'last_name' => 'required',
			'email' => 'required|email|unique:users',
			'birthdate' => 'required|before:-10 years|after:01-01-1900',
			'password' => 'required',
		);

	    $validation = Validator::make(Input::all(), $rules);
	    if ($validation->fails())
	    {
			Input::merge(array('birthdate'=>$date->format(Input::get('birthdate_format'))));
	    	Input::flash();
	        return Redirect::to('login/newuser')->with_errors($validation)->with_input();
	    }

		$user = new User();
		$user->email = Input::get('email');
		$user->password = Input::get('password');
		$user->name = Input::get('first_name').' '.Input::get('last_name');
		$user->language = Config::get('application.language');
		//$user->activation_hash = Hash::make(Str::random(24));
		$user->activation_hash = hash('md4', Str::random(32));
		if ($user->save()){
			$profile = array(
				'first_name' => Input::get('first_name'), 
				'last_name' => Input::get('last_name'),
				'birth_date' => $date->format(Localized_Date::STORED_DATE_FORMAT),
				);
			$user->profile()->insert($profile);

			$activation_url = URL::base().'/login/activate/'.base64_encode($user->activation_hash);
			Bundle::start('swiftmailer');
			$mailer = IoC::resolve('mailer');
			$message = Swift_Message::newInstance('Message From '.Config::get('application.site-name'))
			    ->setFrom(array('register@example.com'=>Config::get('application.site-name')))
			    ->setTo(array($user->email=>$user->name))
			    ->addPart('Welcome, follow this link to activate your account: '.$activation_url,'text/plain')
			    ->setBody('Welcome, follow this link to activate your account: '.$activation_url,'text/html');

			// Send the email
			$mailer->send($message);
		}
		return Redirect::to('home');
	}

	// Login get
	public function get_index($action = 'page')
	{
		//echo(var_export($this->layout, true).'<br>');
		//$this->layout = 'layouts.simple';
		//die(var_export($this->layout, true));
		if ($action == 'popup')	{
			$this->layout = 'layouts.empty';
			$this->layout = $this->layout();
		}
		return $this->layout->nest('content', 'user.login');
		//metodi alternativi
		//return View::make($layout)->nest('content', 'user.login');
		//return View::make('user.login');
	}

	// Login post
	public function post_index()
	{
	    $rules = array(
	    'email' => 'required|email',
	    'password' => 'required',
		);

	    $validation = Validator::make(Input::all(), $rules);
	    if ($validation->fails())
	    {
	        return Redirect::to('login')->with_errors($validation);
	    }

		if (Auth::attempt(array('username' => Input::get('email'), 'password' => Input::get('password')))) 
		{
			if (!Auth::user()->activated)
			{
				$messages=new \Laravel\Messages();
				$messages->add('user', __('user.user_not_activated'));
				$messages->add('alert_warning', 'success');
				return Redirect::to('login')->with_errors($messages)->with_input();
			}
			Auth::user()->last_login=new \DateTime;
			Auth::user()->save();
			if (Auth::user()->language) Session::put('language', Auth::user()->language);
			$logged_name = Auth::user()->profile->first_name;
			return Redirect::home()->with('logged_name', $logged_name);
		}
		else 
		{
			$validation->errors->add('user', __('user.user_not_found'));
	        return Redirect::to('login')->with_errors($validation)->with_input();
	    }
	}

}