@extends('allround.master')

@section('content')

	<div class="row">
		<div class="col-md-8">

							<div class="single_book">
							<h4>
							@if(!empty($recent->book_id))
							<a href="/book/{{{ Book::find($recent->book_id)->slug  }}}">{{{ Book::find($recent->book_id)->title }}}</a> ::
							@endif
							<a href="chapter/{{{ $recent->slug }}}">{{{ $recent->title }}}</a></h4>


							{{ Purifier::clean($recent_text) }}

							</div>
			<br>
			<h4>More from Librific</h4>
			<p>Chapter above is the most recent one and changes very often. If you would like to read previous information shared, just browse headlines visible below. The list contains all Librific's public books and chapters ever published, have a nice reading and if you have any questions just follow info from the sidebar.</p>
			@foreach ($chapters as $chapter)

				<div class="single_book">
				<h4>
				@if(!empty($chapter->book_id))
					<a href="/book/{{{ Book::find($chapter->book_id)->slug  }}}">{{{ Book::find($chapter->book_id)->title }}}</a> ::
				@endif
				<a href="chapter/{{{ $chapter->slug }}}">{{{ $chapter->title }}}</a><h4>
				</div>

			@endforeach

			<div class="container">
				{{ $chapters->links() }}
			</div>

		</div>

		<div class="col-md-4">

			<h4>Libri-book</h4>
			<p>It would be really bad example if you couldn't read a book about Librific, right? That's why it's here. The main Librific book where you can read about what's going on, what's being planned and what kind of new staff will be pushed to you in the nearest future. Stay tuned!</p>

			<br>

			<h4>Librific</h4>
			<div id="about">
				<p>Librific is text sharing platform which allows you to present your writing to fans and friends in fast, easy and comfortable way for users of all platforms. You are able to create books and split them into chapters with your own unique touch thanks to simple, yet powerful formatting tools. It doesn't matter if you want to create next big novel, share notes or a private diary, Librific is a great place to start, right now.</p>
			</div>
					</div>
	</div>
@stop
