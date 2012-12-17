@layout('layouts.main')
@section('content')
<?$bs=Former::Framework('bootstrap');?>
<? echo Former::horizontal_open()
  ->id('MyForm')
  ->secure()
  ->rules(array( 'name' => 'required' ))
  ->method('POST');?>
{{Former::token();}}
{{Former::text("email", "Email")}}
{{Former::text('foo')->state('error')->help('bar')}}
{{Former::submit("login")}}
<? echo \Former::actions (
    Former::primary_submit('Login'),
    Former::reset('Reset')
  );?>
{{Former::close()}}
@endsection