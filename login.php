<?php
require_once('lib/functions.php');
loaduser('useremail');
if (isset($useremail)) {
    if($useremail) {  // logout
        logit("erase cookie SaveMySettings");
        setcookie('SaveMySettings','',0,'/');  // delete cookie if logged in
        $useremail=$userid=null;
        return require('home.php');
        }
}

if (isset($prompt)) {
    if(!$prompt) $prompt=request('prompt');
}
?>
<html>
<head>
<title>TangoMango Login</title>
<script src="lib/script.js?2"></script>
<script>checkcookies();</script>
<link href="<?=$urlroot?>/media/stylesheet.css?2" rel=stylesheet type=text/css>
<link href="<?=$urlroot?>/media/<?= platform() ?>.css?1" rel=stylesheet type=text/css>
</head>
<body>
<?php include_once("analyticstracking.php") ?>
<table width=100% height=100% border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td valign=top>
<?php $nologin=true; require("header.php"); ?>
		</td>
	</tr>
	<tr>
		<td height=70% valign=center>
<form name=form1 onSubmit="checkcookies()" action="lib/loginuser.php" method=post>
<input type=hidden name=from value=login>
<input type=hidden name=to value=<?=($prompt=='post')?'editevent':'index'?>>
<input type=hidden name=show value="<?=request('show')?>">
<input type=hidden name=hide value="<?=request('hide')?>">


<table class=dark align=center border=0 cellpadding=0 cellspacing=0>
<?php if($prompt): ?>
	<tr>
		<td nowrap style="padding:12px 20px 0px 20px" colspan=2>
			You must login before you can <?=$prompt?> events.
	<?php if($prompt=='post'): ?>
			<div style="padding-top:0.8em">
			You don't need a password. Just enter your<br>email address. (Don't worry: you won't get spam.)
			</div>
	<?php endif; ?>
		</td>
	</tr>
<?php endif; ?>
	<tr>
		<td nowrap style="padding:12px 0px 0px 20px">Email &nbsp;</td>
		<td nowrap style="padding:12px 20px 0px 0px"><input class=textbox name=useremail style="width:20em" maxlength=100 onChange="changeemail(this,1)"></td>
	</tr>
	<tr>
		<td nowrap colspan=2 align=right style="padding:12px 20px">
			<input class=button type=button value="Cancel" onClick="location='index.php'">&nbsp;
			<input class=button type=submit value="&nbsp; Login &nbsp;">
		</td>
	</tr>
</table>

		</td>
	</tr>
	<tr>
		<td height=30% valign=bottom>
</form>
<div class=small align=center style="padding-bottom:4px">TangoMango will not send you spam or share your email address...ever!</div>
<?php require('footer.php'); ?>
		</td>
	</tr>
</table>
</body>
</html>

<script>
selectf('useremail');
</script>

<?php postcode(); ?>