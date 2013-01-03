<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
    </style>
    {{ Asset::container('bootstrapper')->styles() }}
    {{ HTML::style('css/main.css') }}
    {{ HTML::script('js/modernizr-2.6.1-respond-1.1.0.min.js') }}
    
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ asset('img/apple-touch-icon-144-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ asset('img/apple-touch-icon-114-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ asset('img/apple-touch-icon-72-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('img/apple-touch-icon-57-precomposed.png') }}">
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar">
    <!--[if lt IE 7]>
        <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
    <![endif]-->

    <?
    $user_name = $array_usermenu = $array_register_menu = $array_login_menu = $array_login_attr = null;

    if (Auth::guest()) {
      $modal_remote = Bootstrapper\Modal::create('ModalLogin')
        ->with_header('Login')
        ->with_data_remote('/login/index/popup');

      $modal_remote->autofooter = false;
      $array_register_menu = array(__('interface.signup'), '/login/newuser', false, false, null, 'aw_user');
      $array_login_menu = array(Navigation::link('Login', '#', false, false, null, 'aw_key icon-white'));
      $array_login_attr = $modal_remote->get_launcher_attributes()+array('class'=>'pull-right');

      echo $modal_remote;
    }
    else {
      $user_name = Auth::user()->name;
      $array_usermenu = array($user_name, '#', false, false,
                          array(
                            array(__('interface.profile'), '/profile'),
                            array(Navigation::DIVIDER),
                            //array(Navigation::HEADER, 'Logout'),
                            array(__('interface.logout'), '/logout'),
                          )
                        );
    }

    // Navigation Bar
    echo Bootstrapper\Navbar::inverse(null, Navbar::FIX_TOP)
    ->with_brand(Config::get('application.site-name'), '#')

    ->with_menus(
      array(Navigation::link(Config::get('application.language', 'en'), '#', false, false, 
            array(
              array('label'=>'English', 'url'=>'/language/1'),
              array('label'=>'Italiano', 'url'=>'/language/0'),
            ), 'flag icon-white')),
      array('class' => 'pull-right')
    )

    // Login
    ->with_menus($array_login_menu, $array_login_attr)

    // Home
    ->with_menus(
      array(Navigation::link('Home', '/', false, false, null, 'home icon-white')),
      array('class' => 'pull-left')
    )

    // Home, Register, User Menu
    ->with_menus(
        Navigation::links(
        array(
          $array_register_menu,
          $array_usermenu,
        )
      ),array('class' => 'pull-right')
    )

    ->collapsible();
    ?> 
    
    <div class="wrapper">
        <div class="container">
                
                @yield('content')
                
        </div> <!-- /container -->
    </div> <!-- /wrapper -->
        
        
    <div class="push"><!-- / / --></div> <!-- /push -->
        
    <footer>
        <p>&copy; Medialeader</p>
    </footer> <!-- /footer -->
    
    
    
    <!-- begin javascript -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../../public/js/jquery-1.8.1.min.js"><\/script>')</script>
    
    {{ Asset::container('bootstrapper')->scripts() }}
    @yield('dynamicscripts')

    {{ HTML::script('js/plugins.js') }}
    {{ HTML::script('js/main.js') }}
<?
/*
 * Google Analytics
 * 
     <script>
        var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
        (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
        g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
        s.parentNode.insertBefore(g,s)}(document,'script'));
    </script>
*/?>
    <!-- end javascript -->
</body>
</html>