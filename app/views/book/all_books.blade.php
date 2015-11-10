@extends('allround.master')

@section('content')
	
	
	<div id="search" class="row col-md-12">
			<h3>Search</h3>
			<p>Use form below to specify date range and genre you are interested in. Hit "Submit" and list of books will be adjusted immediately.</p>
			
			{{ Form::open(array('url' => 'books_conditions', 'class' => 'form-inline', 'role' => 'form' )) }}
	
			<ul class="errors">
				@foreach($errors->all() as $message)
					<li class="text-danger">{{ $message }}</li>
				@endforeach
			</ul>
	
			<div class="form-group col-lg-2">
				{{ Form::label('from', 'From', array('class'=>'sr-only')) }}
				{{ Form::text('from', ''  , array('class'=>'form-control datepicker', 'data-date-format' => 'yyyy-mm-dd', 'placeholder' => 'From')) }}
			</div>
			<div class="form-group col-lg-2">
				{{ Form::label('to', 'To', array('class'=>'sr-only')) }}
				{{ Form::text('to', '' , array('class'=>'form-control datepicker', 'data-date-format' => 'yyyy-mm-dd', 'placeholder' => 'To')) }}
			</div>
			<div class="form-group col-lg-2">
					{{ Form::label('genre', 'Genre', array('class'=>'sr-only')) }}		
					{{ Form::select('genre', array(
						'' => '',
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

					), isset($genre_old) ? $genre_old : (''), array('class'=>'form-control', 'placeholder' => 'Genre')); }}
			</div>
			{{ Form::submit('Submit', array('class' => 'btn btn-default')) }}			
			{{ Form::close() }}
	</div>
	@foreach (array_chunk($books->getItems(), 3) as $booksRow)	
		<div class="row">
			@foreach ($booksRow as $book)
				<div class="col-md-4">
					<div class="single_book">
					<h4>{{{ $book->title }}}</h4>
					<p>{{{ Str::limit($book->about, 250) }}}</p>
					<p class="text-right"><a href="/book/{{{ $book->slug }}}"><em>Go to the book...</em></a></p>
					</div>
				</div>
			@endforeach			
		</div>
	@endforeach
	
		<div class="container">
			{{ $books->links() }}
		</div>
	
@stop

@section('footer')
		<script src="{{ URL::asset('assets/js/bootstrap-datepicker.js') }}"></script>
		<script>
		$('.datepicker').datepicker({
			autoclose: 'true'
		})
		</script>
@stop
