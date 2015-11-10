@extends('allround.master')

@section('content')

	<div id="reg-login-forms" class="row">
		<div class="col-md-6 col-md-offset-3">
			<h4>Password change form</h4>
			<p>This is the last step of password reset. Just fill your e-mail address and choose your new password.</p>
			<div class="form-body">
			
				{{ Form::open(array('url' => URL::to('/reset'), 'role' => 'form' )) }}
				
					{{ Form::hidden('pass_code', $pass_code ) }}

					<ul class="errors">
						@foreach($errors->all() as $message)
							<li class="text-danger">{{ $message }}</li>
						@endforeach
					</ul>
					
					<div class="form-group">
					{{ Form::label('email', 'Email address') }}
					{{ Form::email('email', Input::old('email'), array('class'=>'form-control')) }}
					</div>
					<div class="form-group">
					{{-- Password field. ------------------------}}
					{{ Form::label('password', 'New password') }}
					{{ Form::password('password', array('class'=>'form-control')) }}
					</div>
					<div class="form-group">
					{{-- Password confirmation field. -----------}}
					{{ Form::label('password_confirmation', 'New password confirmation') }}
					{{ Form::password('password_confirmation', array('class'=>'form-control')) }}
					</div>

					{{ Form::submit('Set', array('class' => 'btn btn-primary')) }}
					</form>
			
			</div>
		</div>
	</div>

@stop