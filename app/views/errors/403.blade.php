@extends('allround.master')

@section('content')

	<div class="row">
		<div id="http_error" class="col-md-8 col-md-offset-4">
			<h2>Error 403: Forbidden</h2>
			<h4>Server couldn't reach a content you asked for, all it found is this strange 403 book.</h4>
			<h4>Just leave it here please and let this link take you <a href="{{{ URL::to('/') }}}">home.</a></h4>
			
		</div>
	</div>
	
@stop