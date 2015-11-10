@extends('allround.master')

@section('content')
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<h4>Password change</h4>
			<p>Form below allows you to change your password. For security reason it's required that you provide your old password.</p>
			
				{{ Form::open(array('url' => 'pass_change', 'method' => 'PUT', 'role' => 'form')) }}
				
					{{ Form::hidden('id', $user->id) }}
					
					@if(Session::has('old_pass_error'))
						<div id="flash_notice" class="text-danger"><p>{{ Session::get('old_pass_error') }}</p></div>
					@endif
					
					<ul class="errors">
						@foreach($errors->all() as $message)
							<li class="text-danger">{{ $message }}</li>
						@endforeach
					</ul>
					
					<div class="form-group">
					{{-- Password field. ------------------------}}
					{{ Form::label('old_password', 'Old password') }}
					{{ Form::password('old_password', array('class'=>'form-control')) }}
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
					
					{{-- Form submit button. --------------------}}
					{{ Form::submit('Change' , array('class' => 'btn btn-primary')) }}
			
				{{ Form::close() }}
		</div>
	</div>
@stop
