<?php

class Profile_Controller extends Controller 
{

	public $layout = 'layouts.main';
	public $restful = true;
	
	/**
	 * Create a new Controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->filter('before', 'auth');//->only(array('index', 'list'));
	}

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

	public function get_view($who = null, $action = 'page')
	{
		$withUser = Auth::user();
		if (!is_null($who) and 
			is_numeric($who) and 
			!($withUser = User::find($who))) 
		{
				return Response::error('404');
		}

		if ($action == 'popup')	{
			$this->layout = 'layouts.simple';
			$this->layout = $this->layout();
		}

		return $this->layout->nest('content', 'user.profile', array('User' => $withUser));
	}

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
			$logged_name = Auth::user()->profile->first_name;
			return Redirect::home()->with('logged_name', $logged_name);
		}
		else
			return "Ma cu si?";
	}

}