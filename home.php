<?php require_once("lib/functions.php"); ?>
<html>
<head>
<title>TangoMango - Argentine Tango Calendar</title>
<script src="lib/script.js?2"></script>
<link href="media/stylesheet.css?2" rel=stylesheet type=text/css>
<link href="media/<?=platform()?>.css?1" rel=stylesheet type=text/css>
</head>
<body>
<?php include_once("analyticstracking.php") ?>
<table width=100% height=100% border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td valign=top>
<?php $nologin=$notabs=true; require("header.php"); ?>
		</td>
	</tr>
	<tr>
		<td height=80% valign=center align=center>
<form name=form1 onSubmit="checkcookies()" action="lib/loginuser.php" method=post>
<input type=hidden name=from value=home>
<h6 class=hide>Argentine Tango Dancing in San Francisco Bay Area, San Jose, Berkeley, San Diego, Los Angeles, Orange County, Southern California, San Luis Obispo, Santa Barbara, Central Coast, Miami, Palm Beach, South Florida, Chicago, and other cities.</h6>

		<!--/*Blank Block */ -->

<table border=0 cellpadding=0 cellspacing=0>
	<tr valign=top>
		<td width=260 align=center>
			<a class=styled href="index.php?show=San_Francisco,CA+Alameda,CA+San_Mateo,CA+Santa_Clara,CA+Marin,CA+Contra_Costa,CA+Sacramento,CA+Santa_Cruz,CA+Monterey,CA+Sonoma,CA+Mendocino,CA+Stanislaus,CA">
				<img width=200 height=150 src="media/sanfrancisco.jpg" border=0 style="padding-bottom:8px"><br>San Francisco &nbsp;&<br>No. California</a>
		</td>
		<td width=260 align=center>
			<a class=styled href="index.php?show=Miami-Dade,FL+Broward,FL+Palm_Beach,FL">
				<img width=200 height=150 src="media/miami.jpg" border=0 style="padding-bottom:8px"><br>Miami &nbsp;&<br>So. Florida</a>
		</td>
		<td width=260 align=center>
			<a class=styled href="index.php?show=Cook,IL+Lake,IL+Du_Page,IL">
				<img width=200 height=150 src="media/chicago.jpg" border=0 style="padding-bottom:8px"><br>Chicago Area</a>
		</td>
		<td width=260 align=center>
			<a class=styled href="index.php?show=Philadelphia,PA+Bucks,PA+Delaware,PA
			+Chester, PA+Montgomery, PA">
			<img width=200 height=150 src="media/Philadelphia.jpg" border=0 style="padding-bottom:8px"><br>Greater Philadelphia Area</a>
		</td>
	</tr>
	<tr><td height=40 colspan=3>&nbsp;</td></tr>
	<tr valign=top>
		<td width=260 align=center>
			<a class=styled href="index.php?show=Los_Angeles,CA+San_Diego,CA+Santa_Barbara,CA+Orange,CA+Ventura,CA+Riverside,CA+San_Luis_Obispo,CA+Fresno,CA+Yolo,CA">
				<img width=200 height=150 src="media/sandiego.jpg" border=0 style="padding-bottom:8px"><br>Southern & Central<br>California</a>
		</td>
		<td width=260 align=center>
		<!-- /*Blank Block*/ -->
      <p class="warning">
        As the facilitators of TangoMango.org, the Bay Area Argentine Tango Association (BAATA)
        strongly encourages posters to explain their COVID protocols. BAATA has no responsibility
        as to whether or not the organizer meets those requirements.
      </p>

		</td>
		<td width=260 align=center>
			<a class=styled href="choosearea.php">
				<img width=200 height=150 src="media/newyork.jpg" border=0 style="padding-bottom:8px"><br>Other Cities...</a>
		</td>
	</tr>
</table>

<br>
<br>
<br>
<?php if(!isset($useremail)):  ?>
TangoMango is a free listing of Argentine tango events. Click a city above,<br>
or login below to load your previous settings.<br>
<?php else: ?>
<table border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td nowrap class=dark style="padding:12px 20px">
Could not find any previous settings for <?php echo $useremail; ?>.<br>
Click a city above, or try a different email address.
		</td>
	</tr>
</table>
<?php endif; ?>
<br>
<table style="margin-top:8px" border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td nowrap>Email &nbsp;</td>
		<td nowrap><input class=textbox name=useremail style="width:20em" maxlength=100 onChange="changeemail(this,1)" value="<?= $useremail ?>"></td>
		<td nowrap>&nbsp; <input class=button type=submit value="Login"></td>
	</tr>
</table>

</form>
		</td>
	</tr>
	<tr>
		<td height=30% valign=bottom>
<div class=small align=center style="padding-bottom:4px">TangoMango.org will not send you spam or share your email address...ever!</div>
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

