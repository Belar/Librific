@extends('allround.master')

@section('content')
	<div id="dashboard" class="row">
		<div class="col-md-8">
			<h3>Admin Panel</h3>
			<div class="table-responsive">
				<table class="table table-condensed">
					<tr>
					<th>#</th>
					<th>Nick</th>
					<th>Last login</th>
					<th>Options</th>				
					</tr>

					@foreach($users as $user)
						<tr>
						<td>{{{ $user->id }}}</td>
						<td>{{{ $user->nick }}}</td>
						<td>{{{ $user->last_login }}}</td>
						<td>

							@if(Sentry::findThrottlerByUserId($user->id)->isSuspended())
								<a href="user/unsuspend/{{{ $user->id }}}"><span class="glyphicon glyphicon-volume-up redish"></a>
							@else
								<a href="user/suspend/{{{ $user->id }}}"><span class="glyphicon glyphicon-volume-off"></a>
							@endif
								
							@if(Sentry::findThrottlerByUserId($user->id)->isBanned())
								<a href="user/unban/{{{ $user->id }}}"><span class="glyphicon glyphicon-thumbs-up redish"></a>
							@else
								<a href="user/ban/{{{ $user->id }}}"><span class="glyphicon glyphicon-thumbs-down"></a>
							@endif
							
							
						</td>
						</tr>
					@endforeach
				</table>
			</div>

		</div>
		
		<div class="col-md-4">
		<h3>Stats</h4>
		<p>{{{ $users->count() }}} users wrote {{{ $chapter_count }}} chapters in {{{ $book_count }}} books.</p>	
		</div>
	</div>

@stop