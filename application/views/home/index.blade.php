@layout('layouts.main')
<? /*
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Laravel: A Framework For Web Artisans</title>
	<meta name="viewport" content="width=device-width">
	{{ HTML::style('laravel/css/style.css') }}
	{{ Asset::container('bootstrapper')->styles();}}
	{{ Asset::container('bootstrap per')->scripts();}}
	{{ Bootstrapper\Helpers::inject_activate_js(array('popover','tooltip')) }}
</head>
<body>*/?>
@section('content')
<? 	$logged_name = Session::get('logged_name');
	if (isset($logged_name)) echo Alert::success("Welcome back $logged_name")->block();
/*	$modal = Bootstrapper\Modal::create('myModal')
		->with_header('This is the Header!')
		->add_headers(array('This is one more Header!'))
		->with_body('Hello World')
		->add_body(array(HTML::image('http://bootstrapper.aws.af.cm/img/bootstrap-mdo-sfmoma-01.jpg')));
*/
	$modal_remote = Bootstrapper\Modal::create('myModalRemote')
		->with_header('Login')
		->with_data_remote('/login');
		$modal_remote->autofooter = false;

	?>
	{{ $modal_remote->get_launch_button('<i class="icon-user"></i> Login popup') }}
	{{ $modal_remote }}
	<br>	<br>
		<?
		    echo Bootstrapper\Carousel::create(array(
				array(
					'image'=>'http://bootstrapper.aws.af.cm/img/bootstrap-mdo-sfmoma-01.jpg',
					'label'=>'First Thumbnail label', 
					'caption'=>'Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida 
					at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.'
				),
				array(
					'image'=>'http://bootstrapper.aws.af.cm/img/bootstrap-mdo-sfmoma-02.jpg', 
					'label'=>'Second Thumbnail label', 
					'caption'=>'Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta 
					gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.'
				),
				array(
					'image'=>'http://bootstrapper.aws.af.cm/img/bootstrap-mdo-sfmoma-03.jpg', 
					'label'=>'Third Thumbnail label', 
					'caption'=>'Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta 
					gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.'
				),
			));
		?>
		
		<header>
			<h1>Laravel</h1>
			<h2>A Framework For Web Artisan</h2>

			<p class="intro-text" style="margin-top: 45px;">
			</p>
		</header>
		<div role="main" class="main">
			<div class="home">
				<h2>Learn the terrain.</h2>
				<a href="#" rel="tooltip" title="first tooltip">hover over me</a>
				<p>
					You've landed yourself on our default home page. The route that
					is generating this page lives at: <a id="example" href="#" data-trigger="hover" rel="popover" data-title='A Title' data-placement="right" data-original-title="Example">terrain</a>
				</p>

				<pre>{{ path('app') }}routes.php</pre>

				<p>And the view sitting before you can be found at:</p>

				<pre>{{ path('app') }}views/home/index.blade.php</pre>

				<h2>Grow in knowledge.</h2>

				<p>
					Learning to use Laravel is amazingly simple thanks to
					its {{ HTML::link('docs', 'wonderful documentation') }}.
				</p>

				<h2>Create something beautiful.</h2>

				<p>
					Now that you're up and running, it's time to start creating!
					Here are some links to help you get started:
				</p>

				<ul class="out-links">
					<li><a href="http://laravel.com">Official Website</a></li>
					<li><a href="http://forums.laravel.com">Laravel Forums</a></li>
					<li><a href="http://github.com/laravel/laravel">GitHub Repository</a></li>
				</ul>
			</div>
		</div>
@endsection
@section('dynamicscripts')
{{Bootstrapper\Javascripter::write_javascript()}}
@endsection