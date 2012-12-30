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

<?
$fmt = new IntlDateFormatter(Locale::getDefault(), IntlDateFormatter::SHORT, IntlDateFormatter::NONE );
$fmt = new IntlDateFormatter('ar', IntlDateFormatter::FULL, IntlDateFormatter::NONE );
//dd($fmt->getPattern());
$jsfmt = getJavascriptPattern($fmt);
$jsfmt = $fmt->getPattern();
$jsfmt = 'yy-m-d';
echo $fmt->getPattern().'<br>';
echo $jsfmt;
//$fmt->setPattern($jsfmt);
$dpicker_attr=Bootstrapper\Datepicker::create('birthdate')->
              with_language(Session::get('language'))->
              with_options("startView:2, autoclose:1")->
              with_format($jsfmt)->
              with_date(date($jsfmt))->
              get_attributes();
echo Former::append("birthdate", __('user.birth_date'), $dpicker_attr['data-date'], $dpicker_attr)->with_labeled_icon('birthdate', 'icon-calendar')?>
<?echo $fmt->getPattern().'<br>';?>
<?echo getJavascriptPattern($fmt);?>
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
<?
/**
* Create pattern Date Javascript
*
* @param IntlDateFormatter $formatter
*
* @return string pattern date of Javascript
*/
    function getJavascriptPattern(\IntlDateFormatter $formatter)
    {
        $pattern = $formatter->getPattern();
        $patterns = preg_split('([\\\/.:_;,\s-\ ]{1})', $pattern);
        $exits = array();

        // Transform pattern for JQuery ui datepicker
        foreach ($patterns as $index => $val) {
            switch ($val) {
                case 'yy':
                    $exits[$val] = 'y';
                    break;
                case 'y':
                case 'yyyy':
                    $exits[$val] = 'yy';
                    break;
                case 'M':
                    $exits[$val] = 'm';
                    break;
                case 'MM':
                case 'L':
                case 'LL':
                    $exits[$val] = 'mm';
                    break;
                case 'MMM':
                case 'LLL':
                    $exits[$val] = 'M';
                    break;
                case 'MMMM':
                case 'LLLL':
                    $exits[$val] = 'MM';
                    break;
                case 'D':
                    $exits[$val] = 'o';
                    break;
                case 'E':
                case 'EE':
                case 'EEE':
                case 'eee':
                    $exits[$val] = 'D';
                    break;
                case 'EEEE':
                case 'eeee':
                    $exits[$val] = 'DD';
                    break;
            }
        }

        return str_replace(array_keys($exits), array_values($exits), $pattern);
    }
?>