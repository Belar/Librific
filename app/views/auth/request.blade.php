@extends('allround.master')

@section('content')

	<div id="reg-login-forms" class="row">
		<div class="col-md-6 col-md-offset-3">
			<h4>Password change request</h4>
			<p>In order to reset your password you need to provide e-mail address associated with your Librific account. Password change link will be delivered to that e-mail address address.</p>
			<div class="form-body">
				@if (Session::has('error'))
					{{ trans(Session::get('reason')) }}
				@elseif (Session::has('success'))
					An e-mail with the password reset has been sent.
				@endif
				
				{{ Form::open(array('url' => 'request', 'role' => 'form')) }}

				<div class="form-group">
							{{ Form::label('email', 'Email address') }}
							{{ Form::email('email', Input::old('email'), array('class'=>'form-control')) }}
						</div>
				{{ Form::submit('Request', array('class' => 'btn btn-primary')) }}					
				{{ Form::close() }}	
				
			</div>
		</div>
	</div>

@stop