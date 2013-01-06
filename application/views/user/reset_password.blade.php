@layout('layouts.main')
@section('content')
<? //echo __('user.user_not_activated');?>
<?if ($errors->has('user_alert'))
{
	$alert_message = $errors->first('user_alert');
	$alert_type =  ($errors->has('alert_type')) ? $errors->first('alert_type') : Alert::INFO;
	echo Alert::show($alert_type, $alert_message)->block();
}
?>
<? echo Former::horizontal_open()
  ->id('resetPasswordForm')
  ->secure()
  ->rules(array('password' => 'required', 'repeat_password' => 'required|same:password'))
  ->method('POST');?>
{{Former::token();}}
{{Former::hidden("hash64", $hash64)}}
{{Former::password("password", "Password")->appendIcon('aw_key')}}
{{Former::password("repeat_password", "Repeat Password")->appendIcon('aw_key')}}
<? echo \Former::actions (
    Former::primary_submit('Reset Password')
  );
?>
{{Former::close()}}
@endsection