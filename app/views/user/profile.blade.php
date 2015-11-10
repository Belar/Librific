@extends('allround.master')

@section('content')
	<div class="row">
		<div class="col-md-8">
			<div id="dashboard-tree">
			<h3>Books</h3>
				<ul>
					@foreach (Book::where('author_id', '=' , $profile->id)->where('public_state', '=', true)->get() as $book)
						<li>
						{{{ $book_title = $book->title }}} <a href="{{{ URL::to('/') }}}/book/{{{ $book->slug }}} "><span class="glyphicon glyphicon-arrow-right"></span></a>
						</li>
						<li>
							<ul>
								@foreach (Chapter::whereRaw('book_id = ? AND public_state = ?',array($book->id, true))->get() as $chapter)
								<li><a href="/chapter/{{ $chapter->slug }}">{{{ $chapter->title }}}</a></li>
								@endforeach
							</ul>
						</li>
					@endforeach
				</ul>
				<h5>Unassigned chapters</h5>
				<ul>
						<li>
							<ul>
								@foreach (Chapter::whereRaw('author_id = ? AND book_id = ? AND public_state = ?',array($profile->id, false, true))->get() as $chapter)
								<li><a href="/chapter/{{ $chapter->slug }}">{{{ $chapter->title }}}</a></li>
								@endforeach
							</ul>
						</li>
				</ul>
				
			</div>
		</div>
		
		<div class="col-md-4">
		
			<h4>{{{ $profile->nick }}}</h4>
			
			<div id="about">
				<img id="avatar" class="pull-right" src="http://www.gravatar.com/avatar/{{{ $avatar }}}?s=200&r=pg&d=mm" />
				
				<p>{{{ $profile->about }}}</p>
			</div>
			
			<div id="interaction">
				@if (Auth::check())
					@if (Auth::User()->id == $profile->id)
					
					@else
						@if (DB::table('follows')->whereRaw('followed_id = ? AND follower_id = ?',array($profile->id, Auth::User()->id))->first())
							<a href="/profile/unfollow/{{ $profile->id }}" class="btn btn-primary  pull-right">Unfollow <span class="badge">{{ $profile->followers->count() }}</span></a>
						@else
							<a href="/profile/follow/{{ $profile->id }}" class="btn btn-primary  pull-right">Follow <span class="badge">{{ $profile->followers->count() }}</span></a>
						@endif
					@endif
				@else
					<a href="/login" class="btn btn-primary  pull-right">Follow <span class="badge">{{ $profile->followers->count() }}</span></a>
				@endif
				
				<ul id="social_ico">
					@if ( !empty($profile->website) )
						<li><a href="{{{ $profile->website }}}" target="_blank"><img src="{{ URL::asset('assets/images/website24.png') }}"></a></li>
					@else
					@endif
					
					@if ( !empty($profile->twitter) )
						<li><a href="{{{ $profile->twitter }}}" target="_blank"><img src="{{ URL::asset('assets/images/twitter24.png') }}"></a></li>
					@else
					@endif
					
					@if ( !empty($profile->facebook) )
						<li><a href="{{{ $profile->facebook }}}" target="_blank"><img src="{{ URL::asset('assets/images/facebook24.png') }}"></a></li>
					@else
					@endif
					
					@if ( !empty($profile->goodreads) )
						<li><a href="http://goodreads.com/user/show/{{{ $profile->goodreads }}}" target="_blank"><img src="{{ URL::asset('assets/images/goodreads24.png') }}"></a></li>
					@else
					@endif
					
					@if ( !empty($profile->youtube) )
						<li><a href="{{{ $profile->youtube }}}" target="_blank"><img src="{{ URL::asset('assets/images/youtube24.png') }}"></a></li>
					@else
					@endif
				<ul>
			</div>
			
			@if ( !empty($profile->goodreads) )
			<div id="goodreads-books">
			<h4>{{{$profile->nick}}}'s bookshelf on Goodreads</h4>
					  <script src="http://www.goodreads.com/review/grid_widget/{{{$profile->goodreads}}}?cover_size=small&hide_link=true&hide_title=true&num_books=32&order=d&shelf=read&sort=date_added" type="text/javascript" charset="utf-8"></script>
			</div>
			@else
			@endif
			
		</div>
	</div>
@stop