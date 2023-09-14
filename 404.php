<html>
<head>
<title>Page Not Found</title>
<link href="/media/stylesheet.css?2" rel=stylesheet type=text/css>
<link href="/media/<?=(strpos($_SERVER['HTTP_USER_AGENT'],'Mac')!==false)?'mac':'win'?>.css?1" rel=stylesheet type=text/css>
</head>
<body>
<?php include_once("analyticstracking.php") ?>
<table width=100% height=100% border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td valign=top>
<? require("/home/tmbatango/tangomango/header.php"); ?>
		</td>
	</tr>
	<tr>
		<td height=70% valign=center>

<table class=dark style="width:24em" align=center border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td style="padding:12px 20px">
That web address is wrong.
		</td>
	</tr>
	<tr>
		<td align=right style="padding:0px 20px 12px 0px">
			<input class=button type=button value="Sorry!" onClick="location='/index.php'">
		</td>
	</tr>
</table>

		</td>
	</tr>
	<tr>
		<td height=30% valign=bottom>
<?php require("/home/tmbatango/tangomango/footer.php"); ?>
		</td>
	</tr>
</table>
</body>
</html>
