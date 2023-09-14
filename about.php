<?php
require_once("lib/functions.php");
loaduser('useremail');
?>
<html>
<head>
<title>About TangoMango</title>
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

<table style="width:24em" border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td style="text-align:justify">
<br>
<span class=styled>TangoMango</span> is a free community calendar for Argentine tango dancers to find information about milongas, classes, and related events. Because this is a <i>community</i> site, anyone can post events, and all updates are published instantly!<br>
<br>
David Hundsness launched TangoMango in January 2006 for the San Francisco Bay Area, and since then it has spread to Southern California, Florida, and Chicago, thanks to brave volunteers. David built this for fun and run it for free as a donation to the tango community.  Now Bay Area Argentine Tango Association has taken over TangoMango for the future.
<br>
<br> If you'd like to see TangoMango in your city, all you need to do is start posting events. Be sure to include the event hosts' email where it asks, so they will receive automated reminders twice a month to keep their listings updated. That's critical, so they'll take care of it themselves instead of relying on you to do everything for them. Also <a href="link.php">link to TangoMango</a> from your own website, and be sure to actively tell people about TangoMango. It can take a couple months, so be persistent, but once you get critical mass it becomes very easy. And contact us if you'd like a link to your area on the home page.<br>
<br>
<table width=100% border=0 cellpadding=0 cellspacing=0>
	<tr valign=top>
		<td style="text-align:justify">
About Bay Area Argentine Tango Association: Is the oldest organization supporting Argentine tango in the San Francisco Bay Area.<br>
<br>
<div align=right>&mdash; Bay Area Argentine Tango Association:<br><script>document.write('<a href="mailto:tm@'+'bayareatango.org">tm@'+'bayareatango.org</a>')</script></div>
		</td>
		<td style="padding-left:12px"><img src="media/batango-30x30_icon_no_alpha.png"></td>
	</tr>
</table>
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