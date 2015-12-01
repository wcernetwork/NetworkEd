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

		<h3>Hi [[ $firstname ]],</h3>
 
		<p>Your user account on NetworkEd has not yet been confirmed. An admin has resent your confirmation code so you can verify your account.</p>

		<p>Please follow this link to confirm your NetworkEd account: <a href="[[ URL::to('auth/verify/' . $confirmation_code) ]]">[[ URL::to('auth/verify/' . $confirmation_code) ]]</a></p>

		<p>Thank you!<br />
		The NetworkEd Team</p>
	</body>
</html>
