@extends('allround.master')

@section('content')

	<div id="reg-login-forms" class="row">
		<div class="col-md-6 col-md-offset-3">
			<h4>Login</h4>
			
			<p>Login form allows you to sign in to your account and gain access to areas of Librific which are  reserved only for registered users. If you don't have FREE account on Librific, please head to it's short <a href="{{{ URL::to('/register') }}}">registration form.</a></p>
			
			<div class="form-body">
				@if(Sentry::check())
					<p>You are already logged in. Enjoy it!</p>
				@else				
					{{ Form::open( array('url' => 'login', 'role' => 'form') ) }}
									
					@if(Session::has('login_error'))
						<div id="flash_notice" class="text-danger"><p>{{ Session::get('login_error') }}</p></div>
					@endif
					
					<div class="form-group">
						{{ Form::label('email', 'E-mail') }}
						{{ Form::email('email', '', array('class'=>'form-control')) }}
					</div>
					<div class="form-group">
						{{ Form::label('password', 'Password') }}
						{{ Form::password('password', array('class'=>'form-control')) }}
					</div>
								
					<div class="checkbox">
						{{ Form::checkbox('name', 'Remember me', '', array('class' => 'push-left')); }} Remember me
					</div>
								
					<p>Forgot your password? <a href="/request">Restore it</a></p>
								
						{{ Form::submit('Sign In', array('class' => 'btn btn-primary')) }}
					{{ Form::close() }}
				@endif			
			</div>
		</div>
	</div>

@stop