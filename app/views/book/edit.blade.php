@extends('allround.master')

@section('content')
	<div class="row">
	
		<div class="col-md-4">
			<h4>Welcome to book edit view</h4>
			<p>Edit view allows you to modify your book's information like adding book description or changing title.</p>
			<p>You can't edit "about's" formatting for styling reasons and partially to avoid additional security issues assosiated with free formatting. However, more editing options are about to come soon which will expand your overall book styling possibilities.</p>
		</div>
		<div class="col-md-8">
			<h3 class="text-center">{{{ $book->title }}}</h3>

				<ul class="errors">
					@foreach($errors->all() as $message)
						<li class="text-danger">{{ $message }}</li>
					@endforeach
				</ul>

			{{ Form::open(array('url' => 'book_edit', 'method' => 'PUT')) }}
			
				{{ Form::hidden('id', $book->id) }}
			
				<div class="form-group">
				{{ Form::label('title', 'Title') }} <p><small>Changing title will regenerate slug-link to this chapter.</small></p>
				{{ Form::text('title', $book->title, array('class'=>'form-control') ) }}
				</div>
				
				<div class="form-group">
				{{ Form::label('about', 'About') }}
				{{ Form::textarea('about', $book->about, array('class'=>'form-control') ) }}
				</div>
				
				<div class="form-group">
					{{ Form::label('genre', 'Genre') }}		
					{{ Form::select('genre', array(
						'doodles' => 'Doodles',
						'letters' => 'Letters',
						'magazines' => 'Magazines',
						'notes' => 'Notes',
						'novels' => 'Novels',
						'poetry' => 'Poetry',
						'records' => 'Records',						
						'thoughts' => 'Thoughts',	
						'rulebooks' => 'Rulebooks',						
						'other' => 'Other'
					), $book->genre , array('class'=>'form-control')); }}
				</div>
					
				<div class="form-group">
				{{ Form::label('tags', 'Tags') }} <p><small>Use commas to separate tags from each other.</small></p>
				{{ Form::text('tags', $book->tags, array('class'=>'form-control') ) }}
				</div>
				<div class="checkbox">
						{{ Form::checkbox('book_public_state', '1', $book->public_state ) }} <p>Public - <small>If set to private (unmarked), you will still be able to share your content with a secret link, but it won't be visible for wide audience and listed in public resources.</small></p>
				</div>
				
				{{-- Form submit button. --------------------}}
				{{ Form::submit('Update', array('class' => 'btn btn-primary')) }}
			
			{{ Form::close() }}
		</div>
		
	</div>
@stop
