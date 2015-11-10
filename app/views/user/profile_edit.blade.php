@extends('allround.master')

@section('content')

	<h4>Profile edit</h4>
	<p>Form below allows you to expand information submitted during registration process. These information will be visible (Except email address.) on your profile page and help other users to get to know you, find you on other social media pages and get more familiar with your reading taste. </p>
	
		{{ Form::open(array('url' => 'profile_edit', 'method' => 'PUT', 'role' => 'form')) }}
		
			{{ Form::hidden('id', $user->id) }}
		
			<ul class="errors">
				@foreach($errors->all() as $message)
					<li class="text-danger">{{ $message }}</li>
				@endforeach
			</ul>
			
	<div class="row">	
		<div id="pass_change"  class="col-md-6">
			<div class="form-group">
			{{ Form::label('nick', 'Nick') }}
			{{ Form::text('nick', $user->nick , array('class' => 'form-control', 'id' => 'disabledInput', 'disabled')) }}
			</div>
			
			<div class="form-group">
			{{ Form::label('email', 'E-mail address') }} <p><small>Invisible to other users. Keep it real please, it's the only way of restoring your password if you forget it.</small></p>
			{{ Form::text('email', $user->email , array('class' => 'form-control')) }}
			</div>
			
			<div class="form-group">
			{{ Form::label('about', 'About') }} <p><small>Few words about yourself, keep it simple please as no formatting is available for this field.</small></p>
			{{ Form::textarea('about', $user->about , array('class' => 'form-control')) }}
			</div>
		</div>

		<div id="pass_change"  class="col-md-6">
			<div class="form-group">
			{{ Form::label('website', 'Your website address') }} <p><small>Valid URL</small></p>
			{{ Form::text('website', $user->website , array('class' => 'form-control')) }}
			</div>
			
			<div class="form-group">
			{{ Form::label('twitter', 'Twitter') }} <p><small>Your profile URL</small></p>
			{{ Form::text('twitter', $user->twitter , array('class' => 'form-control')) }}
			</div>
			
			<div class="form-group">
			{{ Form::label('facebook', 'Facebook') }} <p><small>Your profile URL</small></p>
			{{ Form::text('facebook', $user->facebook , array('class' => 'form-control')) }}
			</div>
			
			<div class="form-group">
			{{ Form::label('goodreads', 'Goodreads') }} <p><small>Your <span class="text-danger">profile ID</span> and ID only as that's what is accepted by Goodreads widget.</small></p>
			{{ Form::text('goodreads', $user->goodreads , array('class' => 'form-control')) }}
			</div>
			
			<div class="form-group">
			{{ Form::label('youtube', 'Youtube') }} <p><small>Your profile URL</small></p>
			{{ Form::text('youtube', $user->youtube , array('class' => 'form-control')) }}
			</div>			
		</div>
	
	</div>
	
		{{ Form::submit('Update' , array('class' => 'btn btn-primary')) }}	
		
		{{ Form::close() }}
	</div>
</div>
@stop
