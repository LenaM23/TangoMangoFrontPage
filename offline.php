<html>
<head>
<title>Temporarily Offline</title>
<link href="media/stylesheet.css?2" rel=stylesheet type=text/css>
<link href="media/<?=(strpos($_SERVER['HTTP_USER_AGENT'],'Mac')!==false)?'mac':'win'?>.css?1" rel=stylesheet type=text/css>
</head>
<body>
<?php include_once("analyticstracking.php") ?>
<table width=100% height=100% border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td valign=top>
<?php
$notabs=$nologin=true;
require("header.php");
?>
		</td>
	</tr>
	<tr>
		<td height=70% valign=center>

<table class=dark style="width:24em" align=center border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td style="padding:12px 20px">
Sorry, We are in the middle of upgrading TangoMango right now. Please check back in 10 minutes.
		</td>
	</tr>
</table>

		</td>
	</tr>
	<tr>
		<td height=30% valign=bottom>
<?php require("footer.php"); ?>
		</td>
	</tr>
</table>
</body>
</html>
