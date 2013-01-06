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

	public function get_activate($hash64 = null)
	{
		if (is_null($hash64)) return Response::error('404');
		if (!($user=User::Find_Hash($hash64)))
			return Response::error('404');

		$user->Activate();

		$messages=new \Laravel\Messages();
		$messages->add('user_alert', __('Thank you for joining us, now you can login.'));
		$messages->add('alert_type', Alert::SUCCESS);

		return Redirect::to('login')->with_errors($messages)->with_input();
	}

	public function get_reset($hash64 = null)
	{
		if (is_null($hash64))
		{
			if (Input::had('hash64')) $hash64 = Input::old('hash64');
			else return Response::error('404');
		}
		if (!($user=User::Find_Hash($hash64)))
			return Response::error('404');

		return View::make('user.reset_password')->with('hash64', $hash64);
	}

	public function post_reset()
	{
		$rules = array(
			'hash64' => 'required',
			'password' => 'required|between:8,30',
			'repeat_password' => 'required|same:password',
		);

	    $validation = Validator::make(Input::all(), $rules);
	    if ($validation->fails())
	    {
	    	Input::flash('only', array('hash64', 'hash64'));
	    	//Input::flash();
	        return Redirect::to_action('login@reset')->with_errors($validation);
	    }

		if (!Input::has('hash64') or is_null( ($hash64=Input::get('hash64')) ) ) return Response::error('404');

		$messages=new \Laravel\Messages();
		$messages->add('user_alert', __('user.password_changed'));
		$messages->add('alert_type', Alert::SUCCESS);

		if (!User::Change_Password($hash64, Input::get('password')))
		{
			$messages->add('user_alert', __('user.password_not_changed'));
			$messages->add('alert_type', Alert::ERROR);
		}

		return Redirect::to('login')->with_errors($messages);
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
		Input::merge(array('birthdate'=>$date->format(Localized_Date::STORED_DATE_FORMAT)));
		$rules = array(
			'first_name' => 'required',
			'last_name' => 'required',
			'email' => 'required|email|unique:users',
			'birthdate' => 'required|before:-10 years|after:01-01-1900',
			'password' => 'required|between:8,30',
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
		$user->activation_hash = hash('md4', Str::random(32));
		if ($user->save()){
			$profile = array(
				'first_name' => Input::get('first_name'), 
				'last_name' => Input::get('last_name'),
				'birth_date' => $date->format(Localized_Date::STORED_DATE_FORMAT),
				);
			$user->profile()->insert($profile);
			$user->Validate();
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
				$messages->add('user_alert', __('user.user_not_activated'));
				$messages->add('alert_type', Alert::WARNING);
				return Redirect::to('login')->with_errors($messages)->with_input();
			}
			Auth::user()->Loggedin();
			return Redirect::home()->with('logged_name', Auth::user()->profile->first_name);
		}
		else 
		{
			$validation->errors->add('user_alert', __('user.user_not_found'));
			$validation->errors->add('alert_type', Alert::ERROR);
	        return Redirect::to('login')->with_errors($validation)->with_input();
	    }
	}

	// Forgotten get
	public function get_forgotten()
	{
		return View::make('user.login')->with('is_forgotten', true);
	}

	// Login post
	public function post_forgotten()
	{
	    $rules = array('email' => 'required|email',	);

	    $validation = Validator::make(Input::all(), $rules);
	    if ($validation->fails())
	    {
	        return Redirect::to_action('login@forgotten')->with_errors($validation);
	    }

		if (!($user=User::where('email', '=', Input::get('email'))->first()))
		{
			$validation->errors->add('user_alert', __('user.user_not_found'));
			$validation->errors->add('alert_type', Alert::ERROR);
			$validation->errors->add('email', __('user.user_not_found'));
	        return Redirect::to_action('login@forgotten')->with_errors($validation);
	    }
	    else
		{
/*			if (!Auth::user()->activated)
			{
				$messages=new \Laravel\Messages();
				$messages->add('user', __('user.user_not_activated'));
				$messages->add('alert_warning', 'success');
				return Redirect::to('login')->with_errors($messages)->with_input();
			}
*/
			$messages=new \Laravel\Messages();
			if ($user->Forget_Password()===true)
			{
				$messages->add('user_alert', __('user.reset_password'));
				$messages->add('alert_type', Alert::SUCCESS);
			}
			else
			{
				$messages->add('user_alert', __('user.user_not_activated'));
				$messages->add('alert_type', Alert::ERROR);
			}
			return Redirect::to('login')->with_errors($messages)->with_input();
		}
	}
}