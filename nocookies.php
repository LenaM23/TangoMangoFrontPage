<?php require_once('lib/functions.php'); ?>
<html>
<head>
<title>TangoMango Site</title>
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

<table class=dark align=center border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td nowrap style="padding:12px 20px">
Sorry, you must allow cookies to use this website.<br>
<br>
<?php
$agent=$_SERVER['HTTP_USER_AGENT'];
$mac=(strpos($agent,'Windows')===false && strpos($agent,'Mac')!==false);

if(strpos($agent,'Safari/')): ?>
1. Select "Preferences..." from the Safari menu.<br>
2. Click the "Security" icon.<br>
3. Select "Allow Cookies: Only from sites you navigate to".

<?php elseif(strpos($agent,'Firefox/')):
	if($mac):?>
1. Select "Preferences..." from the Firefox menu.<br>
2. Click the "Privacy" icon.<br>
3. Click the "Cookies" tab.<br>
4. Check "Allow sites to set Cookies".
	<?php else: ?>
1. Select "Options..." from the Tools menu.<br>
2. Click the "Privacy" icon.<br>
3. Click the "Cookies" tab.<br>
4. Check "Allow sites to set Cookies".
	<?php endif; ?>

<?php elseif($x=strpos($agent,'Netscape/')):
	$vers=substr($agent,$x+9,3);
	if($vers>=8): ?>
1. Click the security shield icon in the tab for this web page.<br>
2. Select "I Trust This Site".
	<?php else: ?>
From the Tools menu select "Cookie Manager > Allow Cookies from this Site".
	<?php endif; ?>

<?php elseif(strpos($agent,'Opera')): ?>

<?php elseif(strpos($agent,'WebTV/')): ?>

<?php elseif(strpos($agent,'MSIE ')): ?>
1. Select "Internet Options..." from the Tools menu.<br>
2. Click the "Privacy" tab.<br>
3. Click the "Sites" button.<br>
4. Type "yourdomain.com" and click "Allow".
<?php endif; ?>
</td>
	</tr>
	<tr>
		<td align=right style="padding:0px 20px 12px 0px">
			<input class=button type=button value="&nbsp; OK &nbsp;" onclick="location='index.php'">
		</td>
	</tr>
</table>

<?php postcode(); ?>

		</td>
	</tr>
	<tr>
		<td height=30% valign=bottom>
<?php require('footer.php'); ?>
		</td>
	</tr>
</table>
</body>
</html>
