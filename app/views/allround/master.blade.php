<!DOCTYPE html>
<html lang="en">
<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- Bootstrap core CSS -->
		<link href="{{ URL::asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="{{ URL::asset('assets/css/main.css') }}" rel="stylesheet">
		<!-- Markdown css -->
		<link href="{{ URL::asset('assets/css/bootstrap-markdown.min.css') }}" rel="stylesheet">

		<!-- Social media buttons -->
		<link href="{{ URL::asset('assets/css/social-buttons-3.css') }}" rel="stylesheet">
		<!--<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css" rel="stylesheet">-->
		<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.1/css/font-awesome.css" rel="stylesheet">

		<link rel="icon" type="image/ico" href="{{ URL::asset('assets/images/favicon.ico') }}"/>

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		  <script src="../../assets/js/html5shiv.js"></script>
		  <script src="../../assets/js/respond.min.js"></script>
		<![endif]-->

		<title>Librific :: {{ isset($pageTitle) ? $pageTitle : '' }}</title>

		@yield('op_metas')
</head>
<body>

	<div class="container">

		@section('top')		<!-- Static navbar -->
		<div class="row">
			<div class="col-md-2">
				<a href="/"><img src="{{ URL::asset('assets/images/logo.png') }}" class="pull-left"/></a>
			</div>
			<div class="col-md-10">
				<div class="navbar navbar-default">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						</button>
					</div>
					<div class="navbar-collapse collapse">
						<ul class="nav navbar-nav navbar-right">
							<li><a href="/about">About</a></li>
							<li><a href="/libribook">Libri-book</a></li>

							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Library <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="/faq">FAQ</a></li>
									<li class="divider"></li>
									<li class="dropdown-header">Recently published</li>
									<li><a href="/books">Books</a></li>
									<li><a href="/chapters">Chapters</a></li>
								</ul>
							</li>

							@if(Sentry::check())
							<li class="dropdown">
								<a href="#" class="dropdown-toggle redish" data-toggle="dropdown">Profile <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li class="dropdown-header">Welcome {{{ Sentry::getUser()->nick }}}!</li>
									<li class="divider"></li>
									<li><a href="/dashboard">Dashboard</a></li>
									<li><a href="/profile/{{{ Sentry::getUser()->nick }}}">Profile preview</a></li>
									<li><a href="/profile_edit">Update your profile</a></li>
									<li><a href="/pass_change">Password change</a></li>
									<li><a href="/logout">Logout</a></li>

									@if(Sentry::getUser()->hasAccess('admin'))
									<li class="divider"></li>
									<li><a href="/wonderland">Admin</a></li>
									@endif
								</ul>
							</li>
							{{-- <li><a href="" class="btn nav-new-ch">New</a></li> --}}
							@else
							   <li><a href="#loginForm" data-toggle="modal" class="redish">Sign in</a></li>
							@endif
					  </ul>
					</div><!--/.nav-collapse -->
				</div>
			</div>
		</div>
		@show


		<div class="modal fade" id="loginForm" tabindex="-1" role="dialog" aria-labelledby="loginForm" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Login</h4>
					</div>
					<div class="modal-body">
						@if(Sentry::check())
							<p>You are already logged in. Enjoy it!</p>
						@else
							{{ Form::open( array('url' => 'login', 'role' => 'form') ) }}

							<div class="form-group">
							{{ Form::label('email', 'E-mail') }}
							{{ Form::email('email', '', array('class'=>'form-control')) }}
							</div>
							<div class="form-group">
							{{ Form::label('password', 'Password') }}
							{{ Form::password('password', array('class'=>'form-control')) }}
							</div>

							<div class="checkbox">
									{{ Form::checkbox('remember_me', 'Remember me', '', array('class' => 'push-left')); }} Remember me
							</div>

							<p>Forgot your password? <a href="{{ URL::to('/request') }}">Restore it</a></p>

							<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}
							</div>

							{{ Form::close() }}
						@endif
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
    </div> <!-- /container -->

	<div class="container">

			@if(Session::has('global_error'))
			<div class="alert alert-warning">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<div id="flash_notice">{{ Session::get('global_error') }}</div>
			</div>
			@endif

			@if(Session::has('global_success'))
			<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<div id="flash_notice">{{ Session::get('global_success') }}</div>
			</div>
			@endif
	</div>

	<div class="container">
		@yield('content')
    </div> <!-- /container -->


		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="{{ URL::asset('assets/js/jquery-2.0.3.min.js') }}"></script>
		<script src="{{ URL::asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>

		<script src="{{ URL::asset('assets/js/bootstrap-markdown.js') }}"></script>
		<script src="{{ URL::asset('assets/js/markdown.js') }}"></script>
		<script src="{{ URL::asset('assets/js/to-markdown.js') }}"></script>

		<script>
			$('.alert').delay(5000).fadeOut(1000);
		</script>

		@yield('footer')

</body>
</html>
