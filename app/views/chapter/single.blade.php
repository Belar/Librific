@extends('allround.master')

@section('op_metas')
	<meta property="og:title" content="{{{ $chapter->title }}}"/>
	<meta property="og:url" content="http://website-url/chapter/{{{ $chapter->slug }}}"/>
	<meta property="og:site_name" content="{{{ $username = User::where('id', '=' , $chapter->author_id )->first()->nick }}} on Libirfic"/>
	<meta property="og:description" content="{{{ Str::limit($chapter->text, 600) }}}">
	<meta property="og:image" content="http://website-url/l.jpg">
	<meta property="og:image:type" content="image/jpg">
	<meta property="og:image:width" content="250">
	<meta property="og:image:height" content="250">
@stop

@section('top')
		<div id="chapter-nav" class="row col-md-12">
				<div class="navbar navbar-default">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						</button>
					</div>
					<div class="navbar-collapse collapse">
						<ul class="nav navbar-nav navbar-right">
							@if ( Auth::Check() && DB::table('favourite_ch')->whereRaw('favourite_id = ? AND user_id = ?',array($chapter->id, Auth::User()->id))->first())
								<li class="hidden-xs">
									<a href="/chapter/unfavourite/{{{ $chapter->id }}}"><i class="fa fa-heart redish" data-toggle="tooltip" data-placement="bottom" title="Don't!"></i></a>
								</li>
								<li class="visible-xs">
									<a href="/chapter/unfavourite/{{{ $chapter->id }}}"><i class="fa fa-heart redish"></i> Favourite</a>
								</li>
							@else
								<li class="hidden-xs">
									<a href="/chapter/favourite/{{{ $chapter->id }}}"><i class="fa fa-heart-o" data-toggle="tooltip" data-placement="bottom" title="Thanks!"></i></a>
								</li>
								<li class="visible-xs">
									<a href="/chapter/favourite/{{{ $chapter->id }}}"><i class="fa fa-heart-o"></i> Favourite</a>
								</li>
							@endif

							@if (!empty($chapter->book_id) && Book::where('id', '=' , $chapter->book_id)->first()->public_state == true)
								<li class="toc-toggle hidden-xs">
									<a href="#" onclick="return false"><i class="fa fa-list" data-toggle="tooltip" data-placement="bottom" title="ToC"></i></a>
								</li>
								<li class="visible-xs toc-toggle">
									<a href="#toc"><i class="fa fa-list"></i> Go to ToC</a>
								</li>
							@endif
								<li><a href="{{{ URL::to('/') }}}">Go to Librific</a></li>
						</ul>
					</div><!--/.nav-collapse -->
				</div>
		</div>
@overwrite

@section('content')
	<div class="row">
		<div  id="chapter" class="col-md-12">
			<h3 class="text-center">{{{ $chapter->title }}}</h3>
			@if ( trim($chapter_text) != '' )
			<div id="chapter-body">
					{{ Purifier::clean($chapter_text) }}
			</div>

			<hr>
			@else

			<p class="text-center empty"><em>"{{{ $chapter->title }}}"</em> does not contain any content. It's author didn't publish text for this chapter yet, please check later. In the mean time you can check out Librific's <a href="{{{ URL::to('/books') }}}">book index</a> to find something new to read.</p>

			<hr>
			@endif

		</div>

		<div class="col-md-4" >
			@if( !empty($chapter->book_id) && Auth::Check() == true && Auth::User()->id === $chapter->author_id )
				<div id="toc" class="text-center" data-spy="affix">
					<h4><a href="{{{ URL::to('/') }}}/book/{{{ Book::where('id', '=' , $chapter->book_id)->first()->slug }}}">{{{ Book::where('id', '=' , $chapter->book_id)->first()->title }}}</a></h4>
					<h5>Table of contents</h5>
					<ol class="text-left">
						@foreach (Chapter::where('book_id', '=', $chapter->book_id)->get() as $chapter_toc)
							@if($chapter_toc->id == $chapter->id)
							<li>
								<a class="current_toc" href="/chapter/{{ $chapter_toc->slug }}">{{{ $chapter_toc->title }}}</a>
							</li>
							@else
							<li>
								<a href="/chapter/{{ $chapter_toc->slug }}">{{{ $chapter_toc->title }}}</a>
							</li>
							@endif
						@endforeach
					</ol>
				</div>
			@elseif (!empty($chapter->book_id) && Book::where('id', '=' , $chapter->book_id)->first()->public_state == true)
				<div id="toc" class="text-center" data-spy="affix">
					<h4><a href="{{{ URL::to('/') }}}/book/{{{ Book::where('id', '=' , $chapter->book_id)->first()->slug }}}">{{{ Book::where('id', '=' , $chapter->book_id)->first()->title }}}</a></h4>
					<h5>Table of contents</h5>
					<ol class="text-left">
						@foreach (Chapter::whereRaw('book_id = ? AND public_state = ?',array($chapter->book_id, true))->get() as $chapter_toc)
							@if($chapter_toc->id == $chapter->id)
							<li>
								<a class="current_toc" href="/chapter/{{ $chapter_toc->slug }}">{{{ $chapter_toc->title }}}</a>
							</li>
							@else
							<li>
								<a href="/chapter/{{ $chapter_toc->slug }}">{{{ $chapter_toc->title }}}</a>
							</li>
							@endif
						@endforeach
					</ol>
				</div>
			@endif

		</div>
	</div>

		<div class="row">
			<div class="col-md-4">
					@if( Auth::Check() && Auth::User()->id === $chapter->author_id )
						<div id="author_options">
							<div id="author-panel-icons">
								<a href="/chapter/edit/{{ $chapter->id }}"><i class="fa fa-edit "></i></a>

								@if ($chapter->comments_onoff == true)
									<a href="/chapter/comments/{{ $chapter->id }}"><i class="fa fa-comment"></i></a>
								@else
									<a href="/chapter/comments/{{ $chapter->id }}"><i class="fa fa-comment redish"></i></a>
								@endif
							</div>

							@if($chapter->public_state == false)
								<div class="input-group input-group-sm secret_link">
								  <span class="input-group-addon"><i class="fa fa-key"></i></span>
								  <input class="form-control secret_link_select" value="{{{ URL::to('/').'/chapter/private/'.$chapter->secret_link }}}">
								</div>
							@endif
						</div>
					@endif

				@if(!empty($chapter->where))
					<p>Where: <span class="text-muted">{{{ $chapter->where }}}</span></p>
				@endif

				@if(!empty($chapter->when))
					<p>When: <span class="text-muted">{{{ $chapter->when }}}</span></p>
				@endif

				@if(!empty($chapter->why))
					<p>Why: <span class="text-muted">{{{ $chapter->why }}}</span></p>
				@endif

				@if(!empty($chapter->tags))

				<p>Posted by: </i><a href="/profile/{{{ User::where('id', '=', $chapter->author_id)->first()->nick }}}">{{{ User::where('id', '=', $chapter->author_id)->first()->nick }}}</a></p>

				<p>Tags:
					@foreach (explode(",", $chapter->tags) as $tag)
						<a href="/chapter_tag/{{{ trim($tag) }}}">{{{ trim($tag) }}}</a>
					@endforeach
				</p>
				@endif
			</div>


			<div class="col-md-8">

				@if ( trim($chapter_text) != '' && $chapter->comments_onoff == true )

					<!-- Disqus powered comments. -->

				@else
					<p class="text-center empty">Author of the <em>"{{{ $chapter->title }}}"</em> disabled comments for this chapter.</p>
				@endif
			</div>
		</div>
@stop

@section('footer')
	<script>
	$('.toc-toggle').click(function() {
		$.fx.off = true;
		$('#toc').toggle( function() {
			$('#chapter').toggleClass("col-md-12 col-md-8");
        //$(this).removeClass("gridView").addClass("plainView");
		}, function() {
			$('#chapter').toggleClass("col-md-8 col-md-12");
			//$(this).removeClass("plainView").addClass("gridView");
	   });

	}).fx.off = true;
	</script>
	<script>
		$(".secret_link_select").on("click", function () {
		   $(".secret_link_select").select();
		});
	</script>
	<script type="text/javascript">
    $(function () {
        $("[data-toggle='tooltip']").tooltip();
    });
	</script>
@stop
