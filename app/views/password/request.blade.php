<!doctype html>
<html lang="en">
<head>
	<title>NetworkEd: Password Reset Request</title>
	<meta charset="UTF-8">

	<link rel="stylesheet" href="<?php echo url(); ?>/assets/css/all.min.css">

	<style>

		.welcome {
			width: 300px;
			height: 200px;
			position: absolute;
			left: 50%;
			top: 40%;
			margin-left: -150px;
			margin-top: -100px;
		}

	</style>
</head>
<body>
	<div class="welcome">

		<a href="[[ URL::to('home') ]]"><img src="https://wcernetwork.org/assets/images/banner.png" style="max-width: 100%; width: 400px; text-align: center; margin: 0 auto;" /></a><br /><br />

		@if (isset($error))
		We could not find a user with that email address.<br /><br />

		Click here to go back to the <a href="[[ URL::to('home') ]]">NetworkEd</a> site.
		@else
		You've requested to have your password reset. You should be receiving an email from us with a link to reset your password soon.<br /><br />

		In the meantime, click here to continue browsing the <a href="[[ URL::to('home') ]]">NetworkEd</a> site.
		@endif
	</div>
</body>
</html>
