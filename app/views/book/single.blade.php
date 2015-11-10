@extends('allround.master')

@section('op_metas')
	<meta property="og:title" content="{{{ $book->title }}}"/>
	<meta property="og:url" content="http://url/book/{{{ $book->slug }}}"/>
	<meta property="og:site_name" content="{{{ $username = User::where('id', '=' , $book->author_id )->first()->nick }}} on Libirfic"/>
	<meta property="og:description" content="{{{ $book->about }}}	">
	<meta property="og:image" content="http://url/l.jpg">
	<meta property="og:image:type" content="image/jpg">
	<meta property="og:image:width" content="250">
	<meta property="og:image:height" content="250">
@stop

@section('content')
	<div class="row">
		<div class="col-md-4">
			<h3>{{{ $book->title }}}</h3>
			<p>
			{{{ $book->about }}}
			</p>
			<hr>

			<div class="row">
				<div class="col-md-11">
					<p>Genre: <a href="/book_genre/{{{ trim($book->genre) }}}">{{{ trim($book->genre) }}}</a> <br>
					Tags:
					@foreach ( explode(",",$book->tags) as $tag)
						</span><a href="/book_tag/{{{ trim($tag) }}}">{{{ trim($tag) }}}</a>
					@endforeach</p>
				</div>
				<div class="col-md-1">
					@if( Auth::Check() && Auth::User()->id === $book->author_id )
						<div id="author_option">
							<div id="author-panel-icons">
								<a href="/book/edit/{{ $book->id }}"><span class="icon icon-edit darkish"></span></a>
							</div>
						</div>
					@endif
				</div>
			</div>

			<hr>

			<div id="more_books">
				<h4>Books of <a href="/profile/{{{ User::where('id', '=' , $book->author_id )->first()->nick }}}">{{{ $username }}}</a></h4>
				<ul>
					@foreach (Book::where('author_id', '=' , $book->author_id )->where('public_state', '=', true)->get() as $book)
						<li>
							<a href="/book/{{ $book->slug }}">{{{ $book->title }}}</a>
						</li>
					@endforeach
				</ul>
			</div>
		</div>

		<div class="col-md-8" >
			<h3>Chapters</h3>
				@foreach ($chapters as $chapter)
					@if ( trim($chapter->text) != '' )
						<div class="single-chapter">
							<h4>{{{ $chapter->title }}}</h4>
								<p>{{{ Str::limit($chapter->text, 600) }}}</p>
								<p class="text-right"><a href="/chapter/{{{ $chapter->slug }}}"><em>Go to the chapter...</em></a></p>
						</div>
					@else
					@endif

				@endforeach

			<div class="container">
				{{ $chapters->links() }}
			</div>
		</div>
	</div>
@stop
