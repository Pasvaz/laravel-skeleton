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

	public function get_newuser($action = null)
	{
		return View::make('user.register');
	}

	public function post_newuser($action = null)
	{
		$date = DateTime::createFromFormat(Base_Controller::PRESENTED_DATE_FORMAT, Input::get('birthdate'));
		//dd($date->format(Base_Controller::STORED_DATE_FORMAT));
		Input::merge(array('birthdate'=>$date->format(Base_Controller::STORED_DATE_FORMAT)));
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
			Input::merge(array('birthdate'=>$date->format(Base_Controller::PRESENTED_DATE_FORMAT)));
	    	Input::flash();
	        return Redirect::to('login/newuser')->with_errors($validation)->with_input();
	    }

		$user = new User();
		$user->email = Input::get('email');
		$user->password = Input::get('password');
		$user->name = Input::get('first_name').' '.Input::get('last_name');
		$user->language = Config::get('application.language');
		$user->save();

		$profile = array(
			'first_name' => Input::get('first_name'), 
			'last_name' => Input::get('last_name'),
			'birth_date' => $date->format(Base_Controller::STORED_DATE_FORMAT),
			);
		$user->profile()->insert($profile);
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

		if (Auth::attempt(array('username' => Input::get('email'), 'password' => Input::get('password')))){
			Auth::user()->last_login=new \DateTime;
			Auth::user()->save();
			if (Auth::user()->language) Session::put('language', Auth::user()->language);
			$logged_name = Auth::user()->profile->first_name;
			return Redirect::home()->with('logged_name', $logged_name);
		}
		else
			return "Ma cu si?";
	}

}