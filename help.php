<?php require_once("lib/functions.php");
loaduser('useremail');
?>
<html>
<head>
<title>Tango Mango Site Help</title>
<link href="media/stylesheet.css?2" rel=stylesheet type=text/css>
<link href="media/<?= platform() ?>.css?1" rel=stylesheet type=text/css>
</head>
<body>
<?php include_once("analyticstracking.php") ?>
<table width=100% height=100% border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td valign=top colspan=3>
<?php require("header.php"); ?>
		</td>
	</tr>
	<tr valign=top>
		<td style="padding:0px 20px"><input class="button spacer" type=button value="Back"></td>
		<td height=100% align=center>

<table style="width:36em" border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td style="text-align:justify">

<div class=styled style="padding:0.5em 0em">To Find Events</div>
Click the Events tab. Hopefully the page is self-explanatory, but if not, please <script>document.write('<a href="mailto:yourname@'+'YourDomain.com">contact me</a>')</script>.<br>
<br>
<div class=styled style="padding:0.5em 0em">To Change or Add Cities</div>
Look for the "Other cities" link on the Events page. You can view any region in the United States.<br>
<br>
<div class=styled style="padding:0.5em 0em">To Add or Update Events</div>
Look for the "Add a new event" or "Update this event" links. This is a community calendar, so <i>anyone</i> can post and update events, even you! No passwords are required, and everything you post is published immediately! If you find an event listing is inaccurate or missing, please fix it yourself, especially since some event organizers are not very computer savvy.<br>
<br>
<div class=styled style="padding:0.5em 0em">To Add or Change Dates of an Event</div>
Select the event, click the "Update this event" link, and select "Edit listing". From there you can click the little calendar to add or remove dates.<br>
<br>
<div class=styled style="padding:0.5em 0em">To Delete an Event</div>
Select the event, click the "Update this event" link, and select one of the delete options. This permanently deletes it from the public calendar. To cancel an event on one date, it's better to Edit the listing for that date, and change the title to indicate it is "Canceled", plus you can offer an explanation in the Description.<br>
<br>
<div class=styled style="padding:0.5em 0em">To Receive Email Reminders</div>
Event hosts, teachers, musicians, and DJ's &mdash; You can receive an automatic email twice a month to remind you to update your listings if they appear outdated. To add your email address to an event, select the event, click the "Update this event" link, and add your email address where it says "Email".<br>
<br>
<div class=styled style="padding:0.5em 0em">To Link to Your Events</div>
If you are an event organizer, teacher, DJ, musician, etc., you can link directly to a calendar of your events. Just select one of your events and click the "Link to this event" link. That gives you a link you can send to people or put on your website (you can even embed the calendar into a frameset on your own website). If your email address isn't listed on your events, you must add that first: Select the event, click the "Update this event" link, and add your email address where it says "Email".<br>
<br>

		</td>
	</tr>
</table>

		</td>
		<td align=right style="padding:0px 20px">
<input class=button type=button value="Back" onClick="location='index.php'">
		</td>
	</tr>
	<tr>
		<td colspan=3 valign=bottom>
<br>
<br>
<?php require('footer.php'); ?>
		</td>
	</tr>
</table>
</body>
</html>


<?php postcode(); ?>