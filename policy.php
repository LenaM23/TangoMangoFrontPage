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
<p style="color: yellow">
  As the facilitators of TangoMango.org, the Bay Area Argentine Tango Association (BAATA) strongly encourages posters
  to specify in their listings what their COVID protocols are. Event sponsors are legally required to follow their
  own community guidelines. BAATA has no responsibility as to whether or not posters are meeting those requirements.
</p>
<table width=100% border=0 cellpadding=0 cellspacing=0>
	<tr valign=top>
		<td style="text-align:justify">
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
