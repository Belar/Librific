@extends('allround.master')

@section('content')
		<div id="search" class="row col-md-12">
			<h3>Search</h3>
			<p>Use form below to specify date range and genre you are interested in. Hit "Submit" and list of chapters will be adjusted immediately.</p>
			
			{{ Form::open(array('url' => 'chapters_conditions', 'class' => 'form-inline', 'role' => 'form' )) }}
	
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
			{{ Form::submit('Submit', array('class' => 'btn btn-default')) }}			
			{{ Form::close() }}
		</div>
		
		@foreach (array_chunk($chapters->getItems(), 3) as $chaptersRow)
		<div class="row">
			@foreach ($chaptersRow as $chapter)
				<div class="col-md-4">
						<div class="single-chapter">								
							<h4>{{{ $chapter->title }}}</h4>
								@if($chapter->book_id)
									<p><i class="fa fa-book"></i> <a href="/book/{{{ Book::find($chapter->book_id)->slug  }}}">{{{ Book::find($chapter->book_id)->title }}}</a></p>
								@endif
									<p>{{{ Str::limit($chapter->text, 600) }}}</p>
									<p class="text-right"><a href="/chapter/{{{ $chapter->slug }}}"><em>Go to the chapter...</em></a></p>
						</div>
				</div>
			@endforeach
		</div>
		@endforeach
		
		<div class="container">
			{{ $chapters->links() }}
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