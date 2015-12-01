<!doctype html>
<html lang="en">
<head>
	<title>NetworkEd</title>
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

		@if($errors->has())
		<?= $errors->first() ?><br /><br />Return to the <a href="[[ URL::to('home') ]]">NetworkEd site</a>.
		@else
		Your account is now verified!<br /><br />Continue on to log in to the <a href="[[ URL::to('home') ]]">NetworkEd</a> site.
		@endif
	</div>
</body>
</html>
