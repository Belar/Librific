@extends('allround.master')

@section('content')

	<div class="row">
		<div id="http_error" class="col-md-8 col-md-offset-4">
			<h2>Error 404: The missing page</h2>
			<h4>Unfortunately that's the only book we could find under this address. </h4>
			<h4>Just leave it here please and let this link take you <a href="{{{ URL::to('/') }}}">home.</a></h4>
			
		</div>
	</div>
	
@stop