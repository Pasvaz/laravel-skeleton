<?php

class Profile_Controller extends Authenticated_Controller 
{
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

		$users=User::get();

		return $this->layout->nest('content', 'user.profile', array('User' => $withUser, 'Friends'=>$users));
	}

	public function get_livedit()
	{
		$data=new DateTime();
		$locale='it-it';
		$fmt = new IntlDateFormatter($locale, IntlDateFormatter::SHORT, IntlDateFormatter::NONE );
		echo $fmt->format($data).'<br>';
		echo 'pattern is '. $fmt->getPattern ().'<br>';

		$locale='it_CH';
		$fmt = new IntlDateFormatter($locale, IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);
		echo $fmt->format($data).'<br>';
		echo 'pattern '. $fmt->getPattern ().'<br>';
		
		$locale='en-US';
		$fmt = new IntlDateFormatter($locale, IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);
		echo $fmt->format($data).'<br>';
		echo 'pattern '. $fmt->getPattern ().'<br>';
		die('NO');
	}
}