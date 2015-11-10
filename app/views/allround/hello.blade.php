@extends('allround.master')

@section('content')

      <!-- Main component for a primary marketing message or call to action -->
		<div class="jumbotron">

			@if(Sentry::check())
				<div class="row">
					<div class="col-md-4">
						<h4>Welcome, {{{ Sentry::getUser()->nick }}}!</h4>
						<p><strong>Thank you for registering</strong> on Librific. Before you dive into text sharing &amp; reading you should get some tips about the place. Let's take a look together.</p>

						<p>In the top menu you can see <a href="{{{ URL::to('/libribook') }}}">Libri-book</a>, the book about Librific,<strong> with information about changes, updates and plans.</strong> It will keep you up-to-date with everything happening on the website.</p>

						<p>Great place to start reading (unless you already follow your favourite authors) is to visit <strong>Library</strong> where you can dive straight into <strong>recently published</strong> <a href="{{{ URL::to('/books') }}}">books</a> and <a href="{{{ URL::to('/chapters') }}}">chapters</a>.</p>

						<p>Last but not least is Profile menu which gives you fast and easy access to password change, public information and dashboard. <a href="{{{ URL::to('/dashboard') }}}">Dashboard</a> is the <strong>heart of your activity</strong>, it allows you to manage your content and reading activities. Dashboard lets you check on, followed by you, users or delete, add and edit your own content. Variety of options available in dashboard will grow soon what will make it more appealing to readers.</p>

					</div>

					<div class="col-md-4">
						<h4>Quick edit</h4>
						<p>Edit one of your recently created chapters:</p>
						<ul class="recent-chapters">
						@foreach (Chapter::where('author_id', '=' , Sentry::getUser()->id )->orderBy('created_at', 'desc')->take(5)->get() as $chapter)
									<li>
										<a href="/chapter/{{ $chapter->slug }}">{{{ $chapter->title }}}</a>
										<a href="/chapter/edit/{{ $chapter->id }}"><span class="icon fa fa-edit darkish"></span></a>
									</li>
						@endforeach
						</ul>
						<p>...or add new one right now:</p>
						<div id="add_chapter_hello">
							{{ Form::open(array( 'url' => 'add_chapter', 'role' => 'form')) }}
								<div class="form-group">
								{{ Form::label('chapter_title', 'Chapter title') }}
								{{ Form::text('chapter_title', '', array('class'=>'form-control')) }}
								</div>
								<div class="checkbox">
									{{ Form::checkbox('public', '1', false) }} <p>Public - <small>You can edit this option later in Chapter edit view</small></p>
								</div>
								{{ Form::submit('Add', array('class' => 'btn btn-primary pull-right')) }}
							{{ Form::close() }}
						</div>
					</div>
					<div class="col-md-4">
						<h4>Never slow down</h4>
						<p>Librific is about connecting, finding new writers, getting to true readers or even just talking to other people interested in sharing written word.</p>
						<p>Every writer should stay motivated and persistent. Librific is more than excited to keep you inspired, let's stay in touch.</p>
						<p>
							<a href="#" class="btn btn-fb" target="_blank"><i class="fa fa-facebook"></i> | Like on Facebook</a>
							<a href="#" class="btn btn-twr" target="_blank"><i class="fa fa-twitter"></i> | Follow on Twitter</a>
						</p>
					</div>
				</div>
			@else
				<div class="row">
					<div class="info-box col-md-8 col-md-offset-2">

						<h2 class="text-center">Text sharing made clean and simple</h2>
						<blockquote>
							<p>1. Sign in</p>
							<p>2. Write down</p>
							<p>3. Share pure and clean text </p>
						</blockquote>

						<p class="text-center">
							<!-- Button trigger modal -->
							<a class="btn btn-lg btn-primary" data-toggle="modal" href="#registerForm">Start writing now</a>
						</p>
					</div>
				</div>
            <div class="why">
                <div class="row">
                    <div class="col-md-4">
                        <p class="text-center">
                            <span class="why_icon fa fa fa-circle"></span>
                        </p>
                        <p>Process is simple and comfortable. It takes maximum of 3 easy steps to publish your text which can stay private or become a public resource.</p>
                        <p>Dashboard available for every users allows content management and makes interacting with the website more friendly.</p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-center">
                            <span class="why_icon fa fa-text-height"></span>
                        </p>
                        <p>Librific provides powerful editing system based on Markdown mark-up language. Headings, lists, block-quotes, code blocks, image and video embeds are just a few of available features which can be used in every text.</p>
                    </div>
                     <div class="col-md-4">
                        <p class="text-center">
                            <span class="why_icon fa fa-align-left"></span>
                        </p>
                        <p>Each instance of text (a.k.a. chapter) has a clean and minimalistic design which lets reader to focus on the most important thing, content.</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <p class="text-center">
                            <span class="why_icon fa fa-thumbs-up"></span>
                        </p>
                        <p>Librific is free. You don't have to pay for posting text on Librific and you will never have to. Simple as that, there is nothing to explain.</p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-center">
                            <span class="why_icon fa fa-ban"></span>
                        </p>
                        <p>"No-ads" policy. It means your text won't be polluted with pop-ups, AdWords banners or actually any kind of colourful banners except those you place in it yourself.</p>
                        <p>Librific supports clean and "well served" text.</p>
                    </div>
                     <div class="col-md-4">
                        <p class="text-center">
                            <span class="why_icon fa fa-repeat"></span>
                        </p>
                        <p>Unlimited space for content. There are no limits when comes to amount of books, chapters, paragraphs, sentences, words or characters you can post on Librific. Feel free to create new books every day.</p>
                    </div>
                </div>
            </div>
			@endif
		</div>

	  <!-- Modal -->
		<div class="modal fade" id="registerForm" tabindex="-1" role="dialog" aria-labelledby="registrationModal" aria-hidden="true">
			<div class="modal-dialog">
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title">Account registration</h4>
				  <p>Use the short form below to create your future account.</p>
				</div>
				<div class="modal-body">
					@if(Sentry::check())
						<p>You are already logged in. If you still would like to register a new account, you need to <a href="/logout">log out.</a></p>
					@else
						{{ Form::open(array('url' => 'register', 'role' => 'form')) }}

							<div class="form-group">
							{{ Form::label('nick', 'Nick') }}
							{{ Form::text('nick', '', array('class'=>'form-control')) }}
							</div>
							<div class="form-group">
							{{ Form::label('email', 'Email address') }}
							{{ Form::email('email', '', array('class'=>'form-control')) }}
							</div>
							<div class="form-group">
							{{ Form::label('password', 'Password') }}
							{{ Form::password('password', array('class'=>'form-control')) }}
							</div>
							<div class="form-group">
							{{ Form::label('password_confirmation', 'Password confirmation') }}
							{{ Form::password('password_confirmation', array('class'=>'form-control')) }}
							</div>
							<div class="checkbox">
									{{ Form::checkbox('tos', 'yes') }} I agree to the <a href="/tos">terms of service.</a>
							</div>

							<p>Already have an account? Just <a href="#loginForm" data-toggle="modal" data-dismiss="modal">sign in.</a></p>

							<div class="modal-footer">
							  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							  {{ Form::submit('Register', array('class' => 'btn btn-primary')) }}
							</div>

						{{ Form::close() }}
					@endif
				</div>
			  </div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

	</div>{{-- End of Container from master.blade --}}

	<div class="container">{{-- Start of Container from master.blade --}}

	<div id="foot" class="row">

		<div class="col-md-6">
			<a href="/"><img src="{{ URL::asset('assets/images/logo.png') }}" /></a>
			<p><small>© 2013 Librific. All rights reserved.</small></p>
			<p><small><a href="/tos">Terms of Use</a></small></p>
		</div>
		<div class="col-md-2">
			<h5>Librific</h5>
			<ul>
				<li><a href="/about">About Librific</a></li>
				<li><a href="/libribook">Libri-book</a></li>
				<li><a href="/faq">FAQ</a></li>
			</ul>
		</div>
		<div class="col-md-2">
			<h5>User</h5>
				<ul>
					<li><a href="/register">Join</a></li>
					<li><a href="/login">Sign In</a></li>
					<li><a href="/dashboard">Dashboard</a></li>
				</ul>
		</div>
	</div>
@stop
