@layout('layouts.main')
@section('content')
<? //echo __('user.user_not_activated');?>
<?if ($errors->has('user'))
{
	if ($errors->has('alert_success')) $alert = Alert::success($errors->first('user'))->block();
	else if ($errors->has('alert_warning')) $alert = Alert::warning($errors->first('user'))->block();
	else $alert = Alert::error($errors->first('user'))->block();
	echo $alert;
}
?>
<? echo Former::horizontal_open()
  ->id('MyForm')
  ->secure()
  ->rules(array('email' => 'required|email', 'password' => 'required'))
  ->method('POST');?>
{{Former::token();}}
{{Former::text("email", "Email")->appendIcon('envelope')}}
{{Former::password("password", "Password")->appendIcon('aw_key')}}
<? echo \Former::actions (
    Former::primary_submit('Login'),
    Former::reset('Reset')
  );?>
{{Former::close()}}
@endsection