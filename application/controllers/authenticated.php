<?php

class Authenticated_Controller extends Controller 
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

		$this->filter('before', 'auth');
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
}