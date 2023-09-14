
<html>
<head>
<title>Error</title>
<link href="<?=$urlroot?>/media/stylesheet.css?2" rel=stylesheet type=text/css>
<link href="<?=$urlroot?>/media/<?= platform() ?>.css?1" rel=stylesheet type=text/css>
</head>
<body>
<?php include_once("analyticstracking.php") ?>
<table width=100% height=100% border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td valign=top>
<?php require("$fileroot/header.php"); ?>
		</td>
	</tr>
	<tr>
		<td height=70% valign=center>

<table class=dark style="width:24em" align=center border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td style="padding:12px 20px">
<?=$alert?>
		</td>
	</tr>
	<tr>
		<td align=right style="padding:0px 20px 12px 0px">
			<input class=button type=button value="&nbsp; OK &nbsp;" onClick="location='<?=$to?>'">
		</td>
	</tr>
</table>

<?php postcode(); ?>

		</td>
	</tr>
	<tr>
		<td height=30% valign=bottom>
<?php require("$fileroot/footer.php"); ?>
		</td>
	</tr>
</table>
</body>
</html>
