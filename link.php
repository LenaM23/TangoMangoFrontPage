<?php
require_once("lib/functions.php");
loaduser('useremail');
if(!$userid) return require('login.php');

$areas=array();
$qid=sql("select area,stateid,selected from UserAreas where userid=$userid");
while($q=mysqli_fetch_row($qid)) {
	list($area,$stateid,$selected)=$q;
	$areas[$selected][]=urlencode(str_replace(' ','_',$area)).",$stateid";
	}
mysqli_free_result($qid);
if(!$areas) return require('login.php');

$domain=($_SERVER['HTTP_HOST']=='tangomango.org')?'tangomango.org':substr($_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'],0,-9);  // remove '/link.php'
?>
<html>
<head>
<title>Linking to Tango Mango</title>
<script src="lib/script.js?2"></script>
<link href="media/stylesheet.css?2" rel=stylesheet type=text/css>
<link href="media/<?= platform() ?>.css?1" rel=stylesheet type=text/css>
</head>
<body>
<?php include_once("analyticstracking.php") ?>
<table width=100% height=100% border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td valign=top>
<form name=form1 onSubmit="return false">
<?php require("header.php"); ?>
		</td>
	</tr>
	<tr valign=top>
		<td nowrap height=100% style="padding-left:20px">

<?php
// EVENT
if($eventid=request('eventid')):
$date=request('date');
varsql("select title from Events where eventid=\"$eventid\" && date=\"$date\"");
$link="http://$domain/index.php?eventid=$eventid&date=$date";
?>
<div class=styled style="padding-bottom:8px">Link to this Event Listing</div>
To show <?=$title?> on <?=date('M. j, Y',strtotime($date))?>, copy this link:<br>
<?="<a href=\"$link\" target=new>$link</a>"?><br>
<br>
To link to this event from another event, put this in the other event's description:<br>
<?="&lt;link $eventid,$date&gt;$title&lt;/link&gt;"?><br>
<br>
<br>

<?php
// HOST
$eventemail=getsql("select eventemail from Events where eventid=\"$eventid\"");
if($eventemail):
$hosts=decom($eventemail);
$link="http://$domain/index.php?by=".str_replace('@','+',$hosts[0]);
?>
<div class=styled style="padding-bottom:8px">Link to events by Host/Teacher/DJ/Musician</div>
To show events for
<select class=menu name=host onChange="changehost()">
<?php foreach($hosts as $host) echo
"<option value=\"$host\">$host\n";
?>
</select>
copy the link below.<br>
Use the box below to customize the title shown on the calendar.<br>
<textarea class=textbox name=hosttitle onKeyUp="updatehostlink()" rows=2 cols=25>Events by
<?=$hosts[0]?></textarea><br>
<div id=hostlink><?="<a href=\"$link\" target=new>$link</a>"?></div>
<br>
<br>
<?php
endif;  // if eventemail
endif;  // if eventid

// AREA
if($areas[1]):
$link="http://$domain/index.php?show=".implode('+',$areas[1]);
if($areas[0]) $link.="&hide=".implode('+',$areas[0]);
?>
<div class=styled style="padding-bottom:8px">Link to these Cities</div>
To link to Tango Mango for the cities you have selected, copy this link:<br>
<?="<a href=\"$link\" target=new>$link</a>"?><br>
<br>
<br>
<?php endif; ?>

		</td>
	</tr>
	<tr>
		<td valign=bottom>
</form>
<br>
<br>
<?php require('footer.php'); ?>
		</td>
	</tr>
</table>
</body>
</html>

<script>

function changehost() {
	settext('hosttitle','Events by\n'+menu('host'));
	updatehostlink();
	}

function updatehostlink() {
	host=menu('host');
	hosttitle=text('hosttitle');
	hosttitle=rep("\r",'',hosttitle);  // use unix carriage returns
	hostlink1='http://<?=$domain?>/index.php?by='+rep('@','+',host);
	if(hosttitle!='Events by\n'+host) {
		hosttitle=escape(hosttitle);
		hosttitle=rep('+','%2B',hosttitle);  // encode + sign
		hosttitle=rep('%20','+',hosttitle);  // use + for space
		hostlink1+='&title='+hosttitle;
		}
	setspan('hostlink','<a href="'+hostlink1+'" target=new>'+hostlink1+'</a>');
	}

</script>


<?php postcode(); ?>
