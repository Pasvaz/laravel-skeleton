<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your application using Laravel's RESTful routing and it
| is perfectly suited for building large applications and simple APIs.
|
| Let's respond to a simple GET request to http://example.com/hello:
|
|		Route::get('hello', function()
|		{
|			return 'Hello World!';
|		});
|
| You can even respond to more than one URI:
|
|		Route::post(array('hello', 'world'), function()
|		{
|			return 'Hello World!';
|		});
|
| It's easy to allow URI wildcards using (:num) or (:any):
|
|		Route::put('hello/(:any)', function($name)
|		{
|			return "Welcome, $name.";
|		});
|
*/

Route::get('/', function()
{
	return View::make('home.index');
});

/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Router::register('GET /', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	if( !Session::get('language') ) {
		//Log::info('Session misses lang');
		$accepted_languages = array('en', 'it');
		$user_language = 'en';
		Session::put('language', $user_language);
	} else {
		$user_language = Session::get('language');
	}

	//Log::info('Config has '.Config::get('application.language'));
	Config::set('application.language', $user_language);
	//Log::success('Ends '.$user_language);
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('login');
});

Route::get('language/(:num)', function($lang)
{
	//if (!Input::get('language') or !is_numeric(Input::get('language'))) Response::error('500');
	//$lang = Input::get('language');
	$langarray = array(0=>'it', 1=>'en');
	$user_language=$langarray[$lang];
	Config::set('application.language', $user_language);
	Session::put('language', $user_language);
	if (Auth::user() and Auth::user()->language) {
		Auth::user()->language=$user_language;
		Auth::user()->save();
	}
	return Redirect::home();
});

Route::any('logout', array('as' => 'logout', function()
{
    Auth::logout();
    //echo URL::home(302);
   	return Redirect::to(URL::home(),302);
}));

Route::any('/profile', 'profile@view');
Route::any('/profile/(:num)', 'profile@view');
Route::any('/profile/index/(:all?)', 'profile@view');

Route::controller(Controller::detect());
