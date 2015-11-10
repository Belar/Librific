<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h3>Hello, {{{ $nick }}}</h3>

		<div>
			<p>We received a password reset request for your account. If you ordered this action, please proceed and visit the link below to set a new password. Otherwise, please ignore this message and if this situation repeats, please contact support.</p>

			<p>Password reset link: {{ URL::to('/reset', array( $pass_code )) }}.</p>

			<br>
			Greetings,<br>
			<b>Librific</b>
		</div>
	</body>
</html>
