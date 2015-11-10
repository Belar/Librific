@extends('allround.master')

@section('content')

	<div class="row">
		<div id="http_error" class="col-md-8 col-md-offset-4">
			<h2>Error 500: Internal Ser... Library Error</h2>
			<h4>Unfortunately, little librarians failed to deliver what you asked for and got only "500" book.</h4>
			<h4>Just leave it here please and let this link take you <a href="{{{ URL::to('/') }}}">home.</a></h4>

		</div>
	</div>

@stop
