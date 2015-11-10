@extends('allround.master')

@section('content')
	<div id="dashboard" class="row">
		<div class="col-md-8">
			<h3>Dashboard</h3>
			<p>It's the place when you can maintain your content, but also add new, fresh projects.</p> 
			
			<p>Below you can see resource's tree which contains your content, books and assisiated chapters with simple option like edit and delete. On your right there are two simple forms which allow you to add new books and chapters to your album.</p>
			
			<p>Dashboard is also the place where all important information will pop up. Major information about planned maintenance, meaningful changes and new possible activities.</p>
			
			
			<ul class="nav nav-tabs">
			  <li><a href="#follows" data-toggle="tab">Following</a></li>
			  <li><a href="#favourite" data-toggle="tab"><span class="fa fa-heart"></span></a></li>
			  <li class="active"><a href="#dashboard-tree" data-toggle="tab">Edit corner</a></li>
			</ul>
			<div class="tab-content">				
				<div id="follows" class="tab-pane">	
					@foreach ($user->follow as $following)
						<ul>
						@foreach (Chapter::whereNotIn('book_id', $non_public_books_ids )->whereRaw('author_id = ? AND public_state = ?',array($following->id, true))->take(10)->orderBy('created_at', 'desc')->get() as $follow_chapter)
							<li class="panel panel-default">
								<div class="panel-body">
									<a href="/profile/{{{ $following->nick }}}">{{{ $following->nick }}}</a> created a new chapter: <a href="/chapter/{{{ $follow_chapter->slug }}}">{{{ $follow_chapter->title }}}</a>.
								</div>
							</li>
						@endforeach
						</ul>
					@endforeach	
				</div>
				
				<div id="favourite" class="tab-pane">
					<ul>
					@foreach ($user->favouriteCh()->orderBy('id', 'desc')->get() as $favourite_chapter)
					
						<li class="panel panel-default">
								<div class="panel-body">
									<a href="/chapter/unfavourite/{{{ $favourite_chapter->id }}}"><span class="fa fa-ban"></span></a>
									<a href="/chapter/{{{ $favourite_chapter->slug }}}">{{{ $favourite_chapter->title }}}</a>
								</div>
							</li>
					  
					@endforeach
					</ul>
				 </div>
			
				<div id="dashboard-tree" class="tab-pane active">
					<ul>
						@foreach (Book::where('author_id', '=' , $user->id )->get() as $book)
							<li>
							@if($book->public_state == true)
								<span class="fa fa-circle public_state"></span>
							@else
								<span class="fa fa-circle redish public_state"></span>
							@endif
							<a href="/book/{{ $book->slug }}">{{{ $book->title }}}</a>
							<span class="glyphicon glyphicon-remove darkish"  data-toggle="collapse" data-target="#book-delete-alert-{{ $book->id }}" ></span>
							<a href="/book/edit/{{ $book->id }}"><span class="glyphicon glyphicon-edit darkish"></span></a>
							</li>
							
							<div id="book-delete-alert-{{ $book->id }}" class="collapse alert alert-warning">
								<p><strong>Warning!</strong> You were about to delete one of your books and divest it of chapters. Are you sure about this action?</p>
								 <a href="/book/delete/{{ $book->id }}" class="btn btn-danger">Yes</a> <span class="btn btn-success" data-toggle="collapse" data-target="#book-delete-alert-{{ $book->id }}">No, actually I'm not.</span>
							</div>
							
							<li>
								<ul>
									@foreach (Chapter::where('book_id', '=' , $book->id )->orderBy('chapter_n')->get() as $chapter)
									<li>
										@if ($chapter->public_state == true)
											<span class="fa fa-circle public_state"></span>
										@else
											<span class="fa fa-circle redish public_state"></span>
										@endif
										<a href="/chapter/{{ $chapter->slug }}">{{{ $chapter->title }}}</a> 
										<span class="glyphicon glyphicon-remove darkish"  data-toggle="collapse" data-target="#chapter-delete-alert-{{ $chapter->id }}" ></span>
										<a href="/chapter/edit/{{ $chapter->id }}"><span class="glyphicon glyphicon-edit darkish"></span></a>
										
										@if ($chapter->comments_onoff == true)
											<a href="/chapter/comments/{{ $chapter->id }}"><span class="glyphicon glyphicon-comment"></span></a>
										@else
											<a href="/chapter/comments/{{ $chapter->id }}"><span class="glyphicon glyphicon-comment redish"></span></a>
										@endif
										
										@if($chapter->public_state == false)
											{{-- <span class="label label-default secret_link">{{{ URL::to('/').'/chapter/private/'.$chapter->secret_link }}}</span> --}}
											
											<div class="secret_link">
												<span class="fa fa-lock"></span>
												<input class="secret_link_select" value="{{{ URL::to('/').'/chapter/private/'.$chapter->secret_link }}}"></input>
											</div>
										@endif
									</li>
									
									<div id="chapter-delete-alert-{{ $chapter->id }}" class="collapse alert alert-warning">
									  <p><strong>Warning!</strong> You were about to delete one of your chapters. Are you sure about this action?</p>
									  <a href="/chapter/delete/{{ $chapter->id }}" class="btn btn-danger">Yes</a> <span class="btn btn-success" data-toggle="collapse" data-target="#chapter-delete-alert-{{ $chapter->id }}">No, actually I'm not.</span>
									</div>
									
									@endforeach
								</ul>
							</li>
						@endforeach
					</ul>	
						<h5>Unassigned chapters</h5>
						<ul>
									@foreach (Chapter::where('book_id', '=' , false )->where('author_id', '=' , $user->id)->orderBy('chapter_n')->get() as $chapter)
									<li>
										@if($chapter->public_state == true)
											<span class="fa fa-circle public_state"></span>
										@else
											<span class="fa fa-circle redish public_state"></span>
										@endif
										<a href="/chapter/{{ $chapter->slug }}">{{{ $chapter->title }}}</a> 
										<span class="glyphicon glyphicon-remove darkish"  data-toggle="collapse" data-target="#chapter-delete-alert-{{ $chapter->id }}" ></span>
										<a href="/chapter/edit/{{ $chapter->id }}"><span class="glyphicon glyphicon-edit darkish"></span></a>
										
										@if ($chapter->comments_onoff == true)
											<a href="/chapter/comments/{{ $chapter->id }}"><span class="glyphicon glyphicon-comment"></span></a>
										@else
											<a href="/chapter/comments/{{ $chapter->id }}"><span class="glyphicon glyphicon-comment redish"></span></a>
										@endif
										
										@if($chapter->public_state == false)
											<div class="secret_link">
												<span class="fa fa-lock"></span>
												<input class="secret_link_select" value="{{{ URL::to('/').'/chapter/private/'.$chapter->secret_link }}}"></input>
											</div>
										@endif
									</li>
									
									<div id="chapter-delete-alert-{{ $chapter->id }}" class="collapse alert alert-warning">
									  <p><strong>Warning!</strong> You were about to delete one of chapters. Are you sure about this action?</p>
									  <a href="/chapter/delete/{{ $chapter->id }}" class="btn btn-danger">Yes</a> <span class="btn btn-success" data-toggle="collapse" data-target="#chapter-delete-alert-{{ $chapter->id }}">No, actually I'm not.</span>
									</div>
									
									@endforeach
								</ul>
					
				</div>
				
			</div>
		</div>
		
		<div class="col-md-4">
		
			<ul class="errors">
				@foreach($errors->all() as $message)
					<li class="text-danger">{{ $message }}</li>
				@endforeach
			</ul>
		
			<h4 data-toggle="collapse" data-target="#add_book" class="collape-toggle">+New book</h4>
			<div id="add_book" class="collapse">
				{{ Form::open(array('url' => 'add_book', 'files'=>true, 'role' => 'form' )) }}
					<div class="form-group">
					{{ Form::label('title', 'Title') }} <p><small>Required, can be changed later</small></p>
					{{ Form::text('title', '', array('class'=>'form-control')) }}
					</div>
					<div class="form-group">
					{{ Form::label('genre', 'Genre') }}	<p><small>Required, can be changed later</small></p>	
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
					), 'other', array('class'=>'form-control')); }}
					</div>
					<div class="form-group">
				{{--	{{ Form::label('book_cover', 'Book cover') }}
					{{ Form::file('book_cover', '', array('class'=>'form-control')) }} --}}
					</div>
					<div class="checkbox">
						{{ Form::checkbox('book_public_state', '1', true) }} <p>Public - <small>You can edit this option later in Book edit view</small></p>
					</div>
					{{ Form::submit('Add', array('class' => 'btn btn-primary pull-right')) }}			
				{{ Form::close() }}
			</div>
			
			<div class="clearfix"></div>

			<h4 data-toggle="collapse" data-target="#add_chapter" class="collape-toggle">+New chapter</h4>
			<div id="add_chapter" class="collapse">
				{{ Form::open(array( 'url' => 'add_chapter', 'role' => 'form')) }}
					<div class="form-group">
					{{ Form::label('chapter_title', 'Chapter title') }} <p><small>Required, can be changed later</small></p>
					{{ Form::text('chapter_title', '', array('class'=>'form-control')) }}
					</div>
					<div class="form-group">
					{{ Form::label('book', 'Book') }} <p><small>Optional, can be assigned later</small></p>		
					{{ Form::select('book', array(''=>'') + Book::where('author_id', '=', $user->id )->orderBy('created_at', 'desc')->lists('title', 'id'), '', array('class'=>'form-control')) }}
					</div>
					<div class="checkbox">
						{{ Form::checkbox('public', '1', false) }} <p>Public - <small>You can edit this option later in Chapter edit view</small></p>
					</div>
					{{ Form::submit('Add', array('class' => 'btn btn-primary pull-right')) }}	
				{{ Form::close() }}
			</div>
			
		</div>
	</div>

@stop

@section('footer')
	<script>
		$(".secret_link_select").on("click", function () {
		   $(this).select();
		});
	</script>

@stop