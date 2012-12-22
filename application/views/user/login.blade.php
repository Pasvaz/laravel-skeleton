@layout('layouts.main')
@section('content')

<?$bs=Former::Framework('bootstrap');?>
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