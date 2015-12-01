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

		<h3>Hello [[ $firstname ]],</h3>

		<div>
			You are invited to attend the following NetworkEd event:<br /><br />

			<strong>What:</strong> <a href="[[ URL::to('/') ]]/#!/post/[[ $event_id ]]">[[ $event->title ]]</a><br /><br />

			<strong>When:</strong> [[ $event->expiration_date ]]<br /><br />

			<strong>Where:</strong> [[ $event->location ]]<br />
			[[ $event->address ]]<br />
			[[ $event->city ]], [[ $event->city ]] [[ $event->zip ]]<br /><br />

			<strong>Contact:</strong> <a href="mailto:[[ $event->contact_email ]]">[[ $event->contact_name ]]</a><br /><br />

			We hope that you will attend the event. Please contact the Network (<a href="mailto:info@wcernetwork.org">info@wcernetwork.org</a>) if you have any questions about this event.<br /><br />

			Sincerely,<br />
			The NetworkEd Team<br /><br />
			You are receiving this email because you are subscribed to the NetworkEd events list. <a href="">Please click here to unsubscribe</a>.
		</div>
	</body>
</html>
