<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>

		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		    <tr>
		        <td style="text-align: center;">
		            <a href="https://wcernetwork.org"><img src="https://wcernetwork.org/assets/images/banner.png" style="max-width: 100%; width: 400px; text-align: center; margin: 0 auto;" /></a>
		        </td>
		    </tr>
		</table>

		<h3>Hello,</h3>

		<div>
			We received a request to change your password on the NetworkEd website. If you did not request a password reset, please ignore this email. Otherwise, follow <a href="[[ URL::to('password/reset', array($token)) ]]">this link</a> to change your password.<br/><br />
			This link will expire in [[ Config::get('auth.reminder.expire', 60) ]] minutes.<br /><br />
			Thanks,<br />
			The NetworkEd Team
		</div>
	</body>
</html>
