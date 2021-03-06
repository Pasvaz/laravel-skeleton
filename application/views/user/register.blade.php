@layout('layouts.main')

@section('content')
<? 
$rules = array(
	'first_name' => 'required',
	'last_name' => 'required',
	'email' => 'required|email|unique:users',
	'birthdate' => 'required',
	'password' => 'required|between:8,30',
);
echo Former::horizontal_open()
  ->id('MyForm')
  ->secure()
  ->rules($rules)
  ->method('POST');
?>
{{Former::token();}}
{{Former::text("first_name", 'user.first_name')->appendIcon('aw_user')}}
{{Former::text("last_name", 'user.last_name')->appendIcon('aw_group')}}
{{Former::text("email", "Email")->appendIcon('envelope')}}

<?
$fmt = new LocalizedDate(new DateTime(), null, IntlDateFormatter::SHORT, IntlDateFormatter::NONE );
//dd($fmt->getPattern());
$jsfmt = $fmt->getDatepickerPattern();
echo $fmt->datepicker_format.'<br>';
//$fmt->setPattern($jsfmt);
$dpicker_attr=Bootstrapper\Datepicker::create('birthdate')->
              with_language(Session::get('language'))->
              with_options("startView:2, autoclose:1")->
              with_format($jsfmt)->
              with_date($fmt->getDatepickerDate())->
              get_attributes();
echo Former::append("birthdate", 'user.birth_date', $dpicker_attr['data-date'], $dpicker_attr)->with_labeled_icon('birthdate', 'icon-calendar')?>
{{Former::hidden("birthdate_format", $fmt->getDatePattern())}}
{{Former::password("password", 'Password')->appendIcon('aw_key')}}
<? echo \Former::actions (
    Former::primary_submit('Signup'),
    Former::reset('Reset')
  );?>
{{Former::close()}}
@endsection

@section('addstyles')
{{ Asset::container('bootstrapper-datepicker')->styles() }}
@endsection
@section('dynamicscripts')
{{ Asset::container('bootstrapper-datepicker')->scripts() }}
{{Bootstrapper\Javascripter::write_javascript()}}
@endsection