<!doctype html>
<html lang="en">
<head>
	<title>NetworkEd: You've Been Registered</title>
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

		You've successfully registered as a new user on NetworkEd. You should be receiving an email from us soon to confirm your new account.<br /><br />

		In the meantime, click here to continue browsing the <a href="[[ URL::to('home') ]]">NetworkEd</a> site.
	</div>
</body>
</html>
