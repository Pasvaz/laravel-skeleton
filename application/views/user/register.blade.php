@layout('layouts.main')

@section('content')
<? 
$rules = array(
	'first_name' => 'required',
	'last_name' => 'required',
	'email' => 'required|email|unique:users',
	'birthdate' => 'required',
	'password' => 'required',
);
echo Former::horizontal_open()
  ->id('MyForm')
  ->secure()
  ->rules($rules)
  ->method('POST');
  ?>
{{Former::token();}}
{{Former::text("first_name", __('user.first_name'))->appendIcon('aw_user')}}
{{Former::text("last_name", __('user.last_name'))->appendIcon('aw_group')}}
{{Former::append("email", "Email")->with_labeled_icon('email', 'icon-envelope')}}

<? $dpicker_attr=Bootstrapper\Datepicker::create('birthdate')->with_language('it')->with_options("startView:2, autoclose:1")->with_format(Base_Controller::PRESENTED_FORMAT)->get_attributes();
echo Former::append("birthdate", __('user.birth_date'), $dpicker_attr['data-date'], $dpicker_attr)->with_labeled_icon('birthdate', 'icon-calendar')?>
{{Former::password("password", 'Password')->appendIcon('aw_key')}}

<? echo \Former::actions (
    Former::primary_submit('Signup'),
    Former::reset('Reset')
  );?>
{{Former::close()}}

testing a tooltip {{Bootstrapper\Tooltip::create('HERE', 'This is a nice Tooltip')->with_html(false)->with_placement(Bootstrapper\Tooltip::ON_RIGHT)->get_as_anchor()}}
<br>
testing a tooltip {{Bootstrapper\Tooltip::create('HERE', 'Tooltip Title', 'This is a nice <a>Popover</a>')->with_html(true)->get_as_anchor()}}
@endsection

@section('dynamicscripts')
{{ Asset::container('bootstrapper-datepicker')->scripts() }}
{{ Asset::container('bootstrapper-datepicker')->styles() }}
{{Bootstrapper\Javascripter::write_javascript()}}
@endsection
