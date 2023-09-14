<?php
require_once("lib/functions.php");
loaduser('useremail,stateid,countryid');

if(request('stateid')) {  // use selected state if any, otherwise use User's state
	$stateid=request('stateid');
	$countryid='US';
	}
if(isset($stateid)) varsql("select arealabel from States where countryid='$countryid' && stateid='$stateid'");

$userareas=$otherareas=array();
if(isset($userid)) {
	$qid=sql("select countryid,stateid,area from UserAreas where userid=$userid");  // get all chosen areas
	while($q=mysqli_fetch_row($qid)) {
		list($c,$s,$a)=$q;
		$userareas["$c:$s:$a"]=true;
		if($c!='$countryid' && $s!=$stateid) {
			$aka=getsql("select aka from Areas where countryid='$c' && stateid='$s' && area=\"$a\"");
			$otherareas[]=array($c,$s,$a,$aka);  // chosen areas in other states
			}
		}
	mysqli_free_result($qid);
	}

?>
<html>
<head>
<title>Choose Areas</title>
<script src="lib/script.js?2"></script>
<link href="media/stylesheet.css?2" rel=stylesheet type=text/css>
<link href="media/<?= platform() ?>.css?1" rel=stylesheet type=text/css>
<script>
checkcookies();

function checkform(to) {
	areastring='';
	for(k in areas)
		if(areas[k]) areastring+=k+"\t";
	settext('areas',areastring);
	settext('to',to);
	document.form1.submit();
	}

areas=[];
<?php
foreach($userareas as $area=>$x)
	echo "areas['".addslashes($area)."']=true;\n";
?>

function clickarea(a,code) {
	if(areas[code]) {  // de-select
		areas[code]=false;
		a.className='aoff';
		}
	else {  // select
		areas[code]=true;
		a.className='aon';
		}
	}

function overarea(a) {
	a.className='aover';
	}

function outarea(a,code) {
	a.className=(areas[code])?'aon':'aoff';
	}

</script>
</head>
<body>
<?php include_once("analyticstracking.php") ?>
<table width=100% height=100% border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td valign=top>
<form name=form1 action="lib/savearea.php" method=post onSubmit="return false">
<input type=hidden name=to>
<input type=hidden name=areas>
<?php require("header.php"); ?>
		</td>
	</tr>
	<tr>
		<td valign=top height=100%>

<div style="padding-left:20px">

<!-- STATE -->

<?php if(!isset($stateid)):
$default='';
$countryid='US';
?>
Select your state:<br>
<?php endif; ?>
<select name=stateid onChange="checkform('<?=$urlroot?>/choosearea.php?countryid=US&stateid='+menu('stateid'))">
<?php require("lib/statemenu.php"); ?>
</select>
</div><!-- left:20px -->

<!-- AREA -->

<?php if(isset($arealabel)):
$arealabel=str_replace('County','counties',$arealabel);
$arealabel=str_replace('Borough','boroughs',$arealabel);
$arealabel=str_replace('City','cities',$arealabel);
$arealabel=str_replace('Parish','parishes',$arealabel);
$arealabel=str_replace('Island','islands',$arealabel);

$stateareas=array();
$qid=sql("select area,aka from Areas where countryid='$countryid' && stateid='$stateid'");
while($q=mysqli_fetch_row($qid))
	$stateareas[]=$q;
mysqli_free_result($qid);

$datecount=array();
$qid=sql("select area,count(*) from Events inner join Venues using(venueid) where countryid='$countryid' && stateid='$stateid' group by area,stateid,countryid");
while($q=mysqli_fetch_row($qid))
	$datecount["$countryid:$stateid:$q[0]"]=$q[1];
mysqli_free_result($qid);

$statecount=count($stateareas);
$statecols=ceil($statecount/24);
$staterows=ceil($statecount/$statecols);

if($othercount=count($otherareas)) {
	$othercols=ceil($othercount/24);
	$otherrows=ceil($othercount/$othercols);
} else {
    $otherrows=0;
}

$rows=max($staterows,$otherrows);
?>
<br>
<div style="padding-left:20px">
Click one or more <?=$arealabel?> to list on the calendar page:
</div>
<br>
<table border=0 cellpadding=0 cellspacing=0>
	<tr valign=top>
		<td nowrap style="padding-left:20px">
<?php
// All areas in this state
foreach($stateareas as $i=>$it) {
	list($area,$aka)=$it;
	if($i%$rows==0 && $i) echo "
		</td>
		<td nowrap style=\"padding-left:20px\">
";
	$key="$countryid:$stateid:$area";
	$style=array_key_exists($key, $userareas)?'aon':'aoff';
	$code=addslashes($key);
	if($aka) $aka=" ($aka)";
	$count=array_key_exists($key, $datecount)?' ('.$datecount["$key"].')':'';
	echo "<span class=$style onmouseover=\"overarea(this)\" onmouseout=\"outarea(this,'$code')\" onclick=\"clickarea(this,'$code')\">$area$aka$count</span><br>\n";
	}

// All other areas chosen outside this state
foreach($otherareas as $i=>$array) {
	list($c,$s,$a,$aka)=$array;
	if($i%$rows==0) echo "
		</td>
		<td nowrap style=\"padding-left:20px\">
";
	$code=addslashes("$c:$s:$a");
	if($aka) $a.=" ($aka)";
	echo "<span class=aon onmouseover=\"overarea(this)\" onmouseout=\"outarea(this,'$code')\" onclick=\"clickarea(this,'$code')\">$s:$a</span><br>\n";
	}
?>
		</td>
	</tr>
</table>
<?php endif; // if areas
if($stateid): ?>
<br>

<!-- OK -->
<div style="padding-left:20px">
<input class=button type=button value="Cancel" onClick="location='index.php'">&nbsp;
<input class=button type=button value="&nbsp; OK &nbsp;" onClick="checkform('<?=$urlroot?>/index.php')"><br>
<br>
<div class=small>Numbers in parentheses show how many event dates are currently posted in that area.<br>
Please let me know if some of these areas should be combined or split, or if there is a<br>better way to divide your state into "clearly distinct regions of reasonable driving distance."</div>
</div>
<?php endif; ?>

		</td>
	</tr>
	<tr>
		<td valign=bottom>
<input type=hidden name=pagecomplete value=choosearea>
</form>
<br>
<br>
<?php require("footer.php"); ?>
		</td>
	</tr>
</table>
</body>
</html>

<?php postcode(); ?>
