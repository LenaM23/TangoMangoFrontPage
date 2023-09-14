<?php
require_once("lib/functions.php");
$eventid=request('eventid');
$date=request('date');
$do=request('do');  // empty=new, editall, editone
if(!$do && $eventid) $do='editall';
$dates=array();
$today=date('Y-m-d');

if (!isset($venueid)) $venueid=null;
$useremail=request('user');  // link from email sent to owners
if($useremail) {  // login using given email
	varsql("select userid,lastvisit,countryid,stateid,area from Users where useremail=\"$useremail\"",'login');  // may not match if user hasn't logged in before
	if(isset($userid)) setid();
	if($userid && (!isset($lastvisit) || $lastvisit!=$today)) sql("update Users set lastvisit='$today' where userid=$userid");
	}
else loaduser('useremail,countryid,stateid,area');
if(!$useremail) {
	$prompt='post';
	return require('login.php');
	}

if($eventid) {
	$qdate=($date)?"&& date='$date'":'order by date';
	varsql("select * from Events where eventid=$eventid $qdate",'load event');
	if(!isset($title) || ($title===null)) {  // date or event deleted
		if($date) varsql("select * from Events where eventid=$eventid order by date",'retry different date');  // if date deleted, try first available
		if($title===null) abort("Oops, that listing has already been deleted.");
		}
	varsql("select * from Venues where venueid=$venueid",'load venue');
	$qid=sql("select date,status from Events where eventid=$eventid");
	while($q=mysqli_fetch_row($qid)) {
		list($d,$stat)=$q;
		$dates[$d]=($stat=='request')?'hilite':$stat;  // reg/hilite/denied/null
		}
	mysqli_free_result($qid);
	}
else {  // new event
	$milonga=$dropin=$series=$practica=$livemusic=$performance=$other=$virtual=0;  // default no categories
	if(!$countryid) $countryid='US';
	if(!$stateid) $stateid='CA';
	$eventemail=$useremail;
	$time=$cost=$description='';
	$do='new';
	$dates=array();
	$status='';
	}

$disabledelete=($eventemail && !isany($useremail,$eventemail) && !$admin)?'disabled':'';

$mozilla=strpos($_SERVER['HTTP_USER_AGENT'],'Firefox')?1:0;
function autorows($text,$minrows,$maxrows) {
	global $mozilla;
	$rows=(!$text)?$minrows:
		min($maxrows,max($minrows,count(explode('<br>',$text))));
	if($mozilla && $rows>1) $rows--;  // firefox always shows one too many rows
	return $rows;
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Edit Event</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script src="lib/script.js?2"></script>
<link href="media/stylesheet.css?2" rel=stylesheet type=text/css>
<link href="media/<?=platform()?>.css?1" rel=stylesheet type=text/css>
<script>
mozilla=(navigator.userAgent.indexOf('Firefox')!=-1 || navigator.userAgent.indexOf('Netscape')!=-1 || navigator.userAgent.indexOf('SeaMonkey')!=-1);

// Dates
edates=[];
<?php foreach($dates as $d=>$stat) echo "edates['$d']='$stat';\n"; ?>
lock=false;

function mover(td) {
	if(lock==td) return;
	td.className=(edates[td.id])?'mcalhover':'mcalon';
	lock=false;
	}
function mout(td) {
	if(edates[td.id]=='reg' || edates[td.id]=='denied') td.className='mcalon';
	else if(edates[td.id]=='hilite') td.className='mcalhilite';
	else td.className='mcaloff';
	}
function mclick(td,e) {
	var f=(mozilla)?e.target:event.srcElement;
	var i=td.id;
	if((mozilla)?e.altKey:event.altKey) {  // alt key
		if(!edates[i] || edates[i]=='reg') {  // hilite
			edates[i]='hilite';
			td.className='mcalhilite';
			}
		else if(edates[i]=='hilite') {  // unlite
			edates[i]='reg';
			td.className='mcalon';
			}
		}
	else {
		if(edates[i]) {  // remove date
			edates[i]='';
			td.className='mcaloff';
			}
		else {  // add date
			edates[i]='reg';
			td.className='mcalon';
			}
		}
	lock=td;
	}

// Location

function changestate() {
	c=menu('countryinfo');
	c=c.split(':');
	countryid=c[0];
	s=menu('stateinfo');
	s=s.split(':');
	stateid=s[0];
	label=s[1];
	total=document.form1.area.options.length;
	for(x=0;x<total;x++)  // delete all menu items
		document.form1.area.options[0]=null;
	document.form1.area.options[0]=new Option('Loading...','');
	setspan('arealabel',label);
	setstyle('arearow','');
	window.dataframe.location='lib/getareas.php?countryid='+countryid+'&stateid='+stateid;
	}

function changearea() {
	c=menu('countryinfo');
	c=c.split(':');
	countryid=c[0];
	s=menu('stateinfo');
	s=s.split(':');
	stateid=s[0];
	area=menu('area');
	if(document.form1.area.options[0].value=='')
		document.form1.area.options[0]=null;  // remove blank once selected
	total=document.form1.venuemenu.options.length;
	for(x=0;x<total;x++)  // delete all menu items
		document.form1.venuemenu.options[0]=null;
	document.form1.venuemenu.options[0]=new Option('Loading...','');
	window.dataframe.location='lib/getvenues.php?countryid='+countryid+'&stateid='+stateid+'&area='+escape(area);
	}

function areasloaded() {
	document.form1.area.options[0]=new Option('','');  // clear "Loading..."
	areas=window.dataframe.document.getElementById('areas').innerHTML;
	areas=areas.split(';');
	for(x=0;x<areas.length;x++) {
		a=areas[x].split('=');
		areat=(a[1])?a[0]+' ('+a[1]+')':a[0];
		areav=a[0];
		document.form1.area.options[x+1]=new Option(areat,areav);
		}
	if(areas.length==1) {  // hide & preselect if only one menuitem
		document.form1.area.selectedIndex=1;
		setstyle('arearow','hide');
		}
	}

function venuesloaded() {
	document.form1.venuemenu.options[0]=new Option('','');  // clear "Loading..."
	venues=window.dataframe.document.getElementById('venues').innerHTML;
	if(!venues) return document.form1.venuemenu.options[0]=new Option('New...',0);
	venues=venues.split('|');
	for(x=0;x<venues.length;x++) {
		v=unescape(venues[x]);
		v=v.split("\t");
		if(v[0]) txt=rep("\n",' ',v[0]);  // venue
		else if(v[1] && v[3]) txt=v[1]+', '+v[3];  // address, city
		else if(v[3]) txt=v[3];  // city
		else continue;
		document.form1.venuemenu.options[x+1]=new Option(txt,venues[x]);
		}
	}

function changevenuemenu() {
	v=unescape(menu('venuemenu'));
	if(!v) v="\t\t\t";
	v=v.split("\t");
	settext('venue',rep('<br>',"\n",v[0]));
	settext('address',v[1]);
	settext('directions',rep('<br>',"\n",v[2]));
	settext('city',v[3]);
	}

function resetvenuemenu() {
	document.form1.venuemenu.options[0]=new Option('Other...','');
	setmenui('venuemenu',0);
	}

function changecity() {
	x=text('city');
	x=x.split(',');
	settext('city',x[0]);
	}

function testmap() {
	x=menu('stateinfo');
	x=x.split(':');
	stateid=x[0];
	dest=escape(text('address')+', '+text('city')+', '+stateid);
	url='https://maps.google.com/maps?daddr='+dest;
	window.open(url,'mapwin');
	}

// KEYBOARD

function typetext(f,e,maxlength) {
	var k=e.keyCode;  // which key
	if(f.value.length<maxlength) return true;
	if(e.ctrlKey || e.metaKey) return true;
	if(k>=48 || k==32 || k==13) return false;  // allow delete, arrows, tab
	}

function autorows(f,e,minrows,maxrows) {
	var k=e.keyCode;  // which key
	if(k!=13 && k!=3 && k!=8 && k!=46) return;  // return or delete keys only
	var t=f.value.split("\n");
	var rows=Math.min(maxrows,Math.max(minrows,t.length));
	r=(<?=$mozilla?> && rows>1)?rows-1:rows;  // firefox always shows one too many rows, so r = 1 less than visible rows
	if(r==f.rows) return;
	f.rows=r;
	}

</script>
</head>
<body>
<?php include_once("analyticstracking.php") ?>
<form name=form1 action="lib/saveevent.php" method=post onSubmit="return false">
<input type=hidden name=do value=<?=$do?>>
<input type=hidden name=eventid value=<?=$eventid?>>
<input type=hidden name=date value="<?=$date?>">
<input type=hidden name=dates value="<?=$date?>">

<?php require("header.php"); ?>

<table width=100% border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td class=big style="padding: 0px 0px 0px 20px">
<?php
if($do=='new') echo "Add a New Event";  // new
elseif($do=='editall') echo $title;  // event
else echo "$title, ".date('D n/j',strtotime($date));  // date
?>
		</td>
		<td align=right style="padding: 0px 20px 0px 20px">
<input class=button type=button value="Cancel" onClick="location='index.php'">
<?php if($do!='new'): ?>
&nbsp;<input class=button type=button value="Delete" onClick="promptdelete()" <?=$disabledelete?>>
<?php endif; ?>
&nbsp;<input class=button type=button value="Preview" onClick="checkform('preview')">
<?php if($do!='new'): ?>
&nbsp;<input class=button type=button value="Save" onClick="checkform('save')">
<?php endif; ?>
		</td>
	</tr>
</table>
<br>
<?php if($do!='editone'): ?>
<!-- DATES -->

<table width=1 border=0 cellpadding=0 cellspacing=0>
	<tr valign=top>
		<td nowrap style="padding-left:20px"><b>Dates</b> &nbsp;</td>
		<td class=small style="padding-bottom:6px">
<?php if($do=='new'): ?>
  Click all dates that apply. For repeating events, complete this form with the general information, then edit each specific date’s details.
<?php else: ?>
Click the calendar to add or remove dates for this event.<b class=normal>&nbsp;</b>
<?php endif; ?>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>

<?php require('lib/dateselector.php'); ?>

		</td>
	</tr>
	<tr>
		<td nowrap style="padding-left:20px"><!-- spacer to align columns with table below -->
			<b class=spacer>Address</b> &nbsp;
		</td>
	</tr>
</table>
<br>
<?php endif;  // dates for new events ?>


<!-- Table for column page layout -->
<table border=0 cellpadding=0 cellspacing=0>
	<tr valign=top>
		<td style="padding-left:20px">

<!-- Table for left column options -->
<table width=10 border=0 cellpadding=0 cellspacing=0>
<!-- TITLE -->
	<tr>
		<td nowrap><b>Title</b> &nbsp;</td>
		<td><input class=textbox name=title style="width:20em" value="<?=isset($title) ? $title : '' ?>" placeholder="Name of this event" maxlength=50 onChange="noyelling(this)"></td>
	</tr>
	<tr>
		<td></td>
		<td class=small style="padding-bottom:8px">
      Title your event with something short and distinctive like “Gardel’s Class @ Allegro” rather than “Tango Class”.
		</td>
	</tr>
<!-- COUNTRY -->
	<tr class=hide>
		<td nowrap><b>Country</b> &nbsp;</td>
		<td>
<select class=menu name=countryinfo onChange="changecountry()">
<option value="US:State" selected>United States
</select>
		</td>
	</tr>
<!-- STATE -->
	<tr>
		<td nowrap><b id=statelabel>State</b> &nbsp;</td>
		<td>
<select class=menu name=stateinfo onChange="changestate()">
<?php
$arealabel='';
if(!$stateid) echo "<option>\n";
$qid=sql("select stateid,state,arealabel from States where countryid='$countryid' order by state");
while($q=mysqli_fetch_row($qid)) {
	$selected=($q[0]==$stateid)?'selected':'';
	echo "<option value=\"$q[0]:$q[2]\" $selected>$q[1]\n";
	if($selected) $arealabel=$q[2];
	}
mysqli_free_result($qid);
?>
</select>
		</td>
	</tr>
<!-- AREA -->
<?php
$qid=sql("select area,aka from Areas where countryid='$countryid' && stateid='$stateid'");
?>
	<tr id=arearow <?php if($qrows==1) echo 'class=hide' ?>>
		<td nowrap><b id=arealabel><?=$arealabel?></b> &nbsp;</td>
		<td>
<select class=menu name=area onChange="changearea()">
<?php
if(!$area) echo "<option>\n";
while($q=mysqli_fetch_row($qid)) {
	list($a,$aka)=$q;
	$selected=($a==$area || $qrows==1)?'selected':'';
	$aka=($aka)?"$a ($aka)":$a;
	echo "<option value=\"$a\" $selected>$aka\n";
	}
mysqli_free_result($qid);
?>
</select>
		</td>
	</tr>
<!-- VENUE -->
	<tr>
		<td nowrap><b>Venue</b> &nbsp;</td>
		<td>
<select class=menu name=venuemenu onChange="changevenuemenu()">
<?php
if(!isset($venueid)) echo "<option>\n";
$qid=sql("select venueid,venue,address,directions,city from Venues where countryid='$countryid' && stateid='$stateid' && area=\"$area\" order by if(venue='',1,0),venue,city,address");
while($q=mysqli_fetch_row($qid)) {
	list($venueid1,$venue1,$address1,$directions1,$city1)=$q;
	if($venue1) $v=str_replace('<br>',' ',$venue1);
	elseif($address1 && $city1) $v="$address1, $city1";
	elseif($city1) $v=$city1;
	else continue;  // don't list in menu if blank city
	$selected=(isset($venueid) && ($venueid1==$venueid))?'selected':'';
	echo "<option value=\"$venue1\t$address1\t$directions1\t$city1\" $selected>$v\n";
	}
mysqli_free_result($qid);
?>
</select>
		</td>
	</tr>
	<tr>
		<td nowrap valign=top style="padding-top:4px"><b>Venue</b> &nbsp;</td>
		<td><textarea placeholder="Name of the venue or URL for an online event" class=textbox name=venue rows=2 style="width:20em" onChange="captext(this,6);maxlength(this,60);resetvenuemenu()">
                <?= (isset($venue) && $venue != '') ? unhtml($venue) : '' ?>
            </textarea></td>
	</tr>
	<tr>
		<td nowrap><b>Address</b> &nbsp;</td>
		<td><input class=textbox name=address style="width:20em" value="<?= isset($address) ? $address : '' ?>" placeholder="Street address of the venue. Leave it blank for online-only event.' maxlength=28 onChange="captext(this,3)"></td>
	</tr>
	<tr>
		<td nowrap><b>City</b> &nbsp;</td>
		<td><input class=textbox name=city style="width:20em" value="<?= isset($city) ? $city : '' ?>" placeholder="Name of the city/town/village. Enter the organizer's city for an online-only event.' maxlength=19 onChange="captext(this,4);resetvenuemenu();changecity()"></td>
	</tr>

	<tr>
		<td></td>
		<td class=small>
			Use the "Address" line for the actual street address.
			Use the next box for cross-streets, landmarks, parking, etc.
			Click "Test Map" to verify it can be found on Google Maps.
            Leave the "Address" blank for an online-only event.
		</td>
	</tr>
	<tr>
		<td></td>
		<td nowrap>
			<input class=button type=button value="Test Map" onClick="testmap()">
		</td>
	</tr>
    <tr>
        <td nowrap><b>Directions</b> &nbsp;</td>
        <td><textarea placeholder="Direction to the venue. Leave blank for an online-only event." class=textbox name=directions rows=2 style="width:20em" onChange="captext(this,3,true);maxlength(this,250)">
                <?= (isset($directions) && $directions!='') ? unhtml($directions) : ''?>
            </textarea></td>
    </tr>
	<tr><td>&nbsp;</td></tr>
<!-- EMAIL -->
	<tr>
		<td nowrap valign=top style="padding-top:4px"><b>Email</b></td>
		<td><textarea class=textbox name=eventemail rows=2 style="width:20em" onChange="changeemail(this);maxlength(this,250)"><?=str_replace(',',"\n",$eventemail)?></textarea></td>
	</tr>
	<tr>
		<td></td>
		<td class=small>
			List email for the event organizer, teacher, musicians, etc.
			(These are NOT shown on the calendar!)
			Only people listed here may delete this event.
			Reminders are sent to these addresses twice a month.
		</td>
	</tr>
	<tr>
		<td nowrap class=spacer><b>Address</b> &nbsp;</td>
	</tr>
</table>

		</td>
<!-- Start right column -->
		<td style="padding-left:40px">

<div class="dark border small" style="padding:6px 12px 8px 12px; margin-bottom:12px; width:376px; position:relative">
  <p>TangoMango is for everyone in the community to promote their events & products. It depends on accurate posts. You must include your name (or your organization’s) and how to contact you. If we find that your post is misleading, we will delete it. Please contact us if you have questions at <a href="mailto:info@bayareatango.org">info@bayareatango.org</a></p>

<p style="justify-content:right;width:376px">&nbsp;&mdash; Bay Area Tango board</p>
</div>

<!-- CATEGORIES -->
<div style="padding-bottom:4px"><b>Categories</b> &mdash; <span class=small>Check all that apply:</span></div>
<table border=0 cellpadding=0 cellspacing=0>
	<tr valign=top>
		<td nowrap>
<input class=checkbox type=checkbox name=milonga value=1 <?php if($milonga) echo 'checked' ?>
	><span onClick="clickcheck('milonga')"> Milonga / Dance Party</span>&nbsp;<br>
<input class=checkbox type=checkbox name=practica value=1 <?php if($practica) echo 'checked' ?>
	><span onClick="clickcheck('practica')"> Practica &nbsp;<span class=small>(at least 60 min.)</span></span>&nbsp;<br>
<input class=checkbox type=checkbox name=dropin value=1 <?php if($dropin) echo 'checked' ?>
	><span onClick="clickcheck('dropin')"> Drop-in Classes</span>&nbsp;<br>
<input class=checkbox type=checkbox name=series value=1 <?php if($series) echo 'checked' ?>
	><span onClick="clickcheck('series')"> Series Class/Workshop</span>&nbsp;
		</td>
		<td nowrap>
<input class=checkbox type=checkbox name=livemusic value=1 <?php if($livemusic) echo 'checked' ?>
	><span onClick="clickcheck('livemusic')"> Live Music</span><br>
<input class=checkbox type=checkbox name=performance value=1 <?php if($performance) echo 'checked' ?>
	><span onClick="clickcheck('performance')"> Stage Performance
	&nbsp;<span class=small>(at least 30 min.)</span></span><br>
      <input class=checkbox type=checkbox name=other value=1 <?php if($other) echo 'checked' ?><br>
      <span onClick="clickcheck('other')"> Other (Not the place for private lessons!)</span><br>
      <input class=checkbox type=checkbox name=virtual value=1 <?php if($virtual) echo 'checked' ?>
      ><span onClick="clickcheck('virtual')"> Virtual (Online) Event</span>
		</td>
	</tr>
</table>

<!-- TIME, COST, DESCRIPTION -->
<div style="padding:8px 0px 2px 0px"><b>Time</b></div>
<textarea class=textbox0 name=time style="width:400px" onChange="maxlength(this,250)" rows=<?=autorows($time,2,5)?> onKeyDown="return typetext(this,event,250)" onKeyUp="autorows(this,event,2,5)"><?=unhtml($time)?></textarea>
<table border=0 cellpadding=0 cellspacing=0>
	<tr valign=top>
		<td nowrap class=small>Format like this:</td>
		<td class=small style="padding-left:20px">8:00 - 9:00 &nbsp; Beginners Class<br>9:00 - 1:00a &nbsp; Milonga</td>
	</tr>
</table>
<div style="padding:0px 0px 2px 0px"><b>Cost</b></div>
<textarea class=textbox0 name=cost style="width:400px" onChange="maxlength(this,250)" rows=<?=autorows($cost,2,5)?> onKeyDown="return typetext(this,event,250)" onKeyUp="autorows(this,event,1,5)"><?=unhtml($cost)?></textarea>
<table border=0 cellpadding=0 cellspacing=0>
	<tr valign=top>
		<td nowrap class=small>Format like this:</td>
		<td class=small style="padding-left:20px">$15 Class + Milonga<br>$10 Milonga only</td>
	</tr>
</table>
<div style="padding:4px 0px 2px 0px"><b>Description</b></div>
<textarea class=textbox0 name=description rows=6 style="width:400px" onChange="maxlength(this,800)" onKeyDown="return typetext(this,event,800)"><?=unhtml($description)?></textarea>
<table border=0 cellpadding=0 cellspacing=0>
	<tr valign=top>
		<td nowrap class=small>Example:</td>
		<td class=small style="padding-left:20px">Every 3rd Saturday<br>DJ: Spot<br>Hosted by Dick and Jane<br>tango@example.com<br>www.example.com</td>
	</tr>
</table>
<div class=small style="padding-top:8px">
No HTML, but email and web addresses are hyperlinked automatically.<br>
Limit 800 characters.
</div>
<br>

<!-- SPECIAL EVENTS -->
<div style="padding:8px 0px 4px 0px"><b>Special Events</b></div>
<div class=small style="width:400px">
<?php if($do=='editone'): ?>
<input class=checkbox type=checkbox name=hilite value=1 <?php if($status=='hilite' || $status=='request') echo 'checked'; elseif($status=='denied') echo 'disabled' ?>
	><span class=normal onClick="clickcheck('hilite')"> Consider highlighting this as a special event</span>
<?php else: ?>
To request this event to be highlighted in a different color, hold the <?=(platform()=='mac')?'Option':'Alt'?> key and click the special event date(s) on the calendar above so they are orange.
Then click <?=($do=='new')?'Preview and Save':'Save'?>. It may take up to a week for me to review and approve your request.
<?php endif; ?>
<p>
To prevent abuse, please request only events that are new or very infrequent and have a broad appeal, like a holiday/theme night, or a series of workshops from a well-known visiting teacher.<br>
&nbsp;
</div>

		</td>
	</tr>
</table>

<!-- BUTTONS -->
<div style="padding-left:20px">
<?php if($do=='editall' && getsql("select count(*) from Events where eventid=$eventid")>1): ?>
<div style="padding-bottom:12px">
<input class=radio type=radio name=similar value=1 checked><span onClick="clickradio('similar',0)"> Save changes only for dates that have the same description, etc.</span><br>
<input class=radio type=radio name=similar value=0><span onClick="clickradio('similar',1)"> Save changes for all dates for this event</span>
</div>
<?php else: ?>
<br>
<?php endif; ?>
<input class=button type=button value="Cancel" onClick="location='index.php'">
<?php if($do!='new'): ?>
&nbsp;<input class=button type=button value="Delete" onClick="promptdelete()" <?=$disabledelete?>>
<?php endif; ?>
&nbsp;<input class=button type=button value="Preview" onClick="checkform('preview')">
<?php if($do!='new'): ?>
&nbsp;<input class=button type=button value="Save" onClick="checkform('save')">
<?php else: ?>
<div class=small style="padding-top:4px">You must Preview this event before you can save it.</div>
<?php endif; ?>
</div>


<!-- PREVIEW -->
<div id=showpreview class=hide style="padding:20px 0px 0px 20px">
<table class="dark border" width=320 border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td style="padding:12px 12px 12px 20px" width=320>
			<div id=info></div>
		</td>
	</tr>
</table>
<div id=showsave class=hide>
	<br>
	<input class=button type=button value="Cancel" onClick="location='index.php'">&nbsp;
	<input class=button type=button value="&nbsp; Save &nbsp;" onClick="checkform('save')">
</div>
</div>


<input type=hidden name=pagecomplete value=editevent>
</form>
<iframe name=dataframe width=0 height=0 frameborder=0></iframe>
<br>
<?php require('footer.php'); ?>
</body>
</html>

<script>

function noyelling(e) {  // title's cannot have ! * < > or all-caps
	i=e.value;
	i=rep('_',' ',i);
	i=rep('~',' ',i);
	i=rep('--','-',i);
	start='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"(\'';  // ok to start with
	while(i && start.indexOf(i.charAt(0))<0)  // cannot start with * or other punctuation
		i=i.substr(1,999);
	if(i.length>5) i=caps(i,0);  // if entire title is uppercase or lowercase, change all to Title case with no acronymns
	cap='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	i+=' ';  // mark end of last word
	title='';
	capstring='';
	for(k=0; k<i.length; k++) {
		c=i.charAt(k);
		if(c=='!' || c=='<' || c=='>') continue;  // filter out ! < >
		if(cap.indexOf(c)<0) {  // not cap
			if(capstring.length<=5) title+=capstring+c;  // keep everything if no more than 5 caps
			else title+=capstring.charAt(0)+capstring.substr(1,999).toLowerCase()+c;  // else change all-cap word to title case
			capstring='';
			}
		else capstring+=c;  // else accumulate caps
		}
	title=rep('DANCE','Dance',title);
	title=rep('CLASS','Class',title);
	title=rep('MUSIC','Music',title);
	title=rep('PARTY','Party',title);
	title=rep('TANGO','Tango',title);
	title=rep('SALSA','Salsa',title);
	title=rep('SWING','Swing',title);
	title=rep('WALTZ','Waltz',title);
	title=rep('MAMBO','Mambo',title);
	title=rep('NIGHT','Night',title);
	title=rep('LIVE','Live',title);
	title=rep('FREE','Free',title);
	title=rep('TRIO','Trio',title);
	title=rep('BOOT','Boot',title);
	title=rep('CAMP','Camp',title);
	title=rep('ROSA','Rosa',title);
	title=rep('POLO','Polo',title);
	title=rep('BEAT','Beat',title);
	title=rep('OMAR','Omar',title);
	title=rep('VEGA','Vega',title);
	title=rep('NORA','Nora',title);
	title=rep('IGOR','Igor',title);
	title=rep('POLK','Polk',title);
	title=rep('THE','The',title);
	title=rep('NEW','New',title);
	e.value=clean(title);
	}

function promptspecial() {
	setstyle('showspecialprompt',(checked('showspecial'))?'':'hide');
	}

function submitspecial() {
	settext('special',1);
	setstyle('showspecialprompt','hide');
	checkform('save');
	}

// PREVIEW

function eventloaded() {  // uses innerHTML because form values change &lt; to < etc.
	setspan('info',window.dataframe.document.getElementById('info').innerHTML);
	setstyle('showpreview','');
	setstyle('showsave','');
	setTimeout('window.scrollTo(0,2000);',1);
	}

function openprompt() {
	alert('This is just a preview.');
	}
function addfav() {
	alert('This is just a preview.');
	}
function linktoevent() {
	alert('This is just a preview.');
	}
function loadevent() {
	alert('This is just a preview.');
	}
function closeevent() {
	setstyle('showpreview','hide');
	setstyle('showsave','hide');
	}


// SUBMIT

function checkform(what) {
<?php if($do!='editone'): ?>
	var dates='';
	for(i in edates)
		if(edates[i]) dates+=i+"="+edates[i]+" ";
	settext('dates',dates);
	<?php if($do=='new'): ?>
	if(!dates) return alert("You need a date.\n(We mean on the calendar.)");
	<?php endif; ?>
<?php endif; ?>
	if(!text('title')) return alert('You need a Title.');
	if(!menu('stateinfo')) return alert('You need to select the State.');
	if(!menu('area')) return alert('You need to select the '+(document.getElementById('arealabel').innerHTML)+'.');
	if(!checked('milonga') && !checked('dropin') && !checked('series') && !checked('practica') && !checked('livemusic') && !checked('performance') && !checked('virtual') && !checked('other'))
		return alert('You need at least one Category.');
	// Preview
	if(what=='preview') {
		setspan('info','Loading...');
		setstyle('showpreview','');
		setstyle('showsave','hide');
		document.form1.target='dataframe';
		document.form1.action='lib/previewevent.php';
		}
	// Save
	else {
		document.form1.target='';
		document.form1.action='lib/saveevent.php';
		}
	document.form1.submit();
	}

function promptdelete() {
<?php if($do=='editone'): ?>
	if(!confirm('Are you sure you want to delete this event from this date?')) return;
<?php else: ?>
	if(!confirm('Are you absolutely, positively certain beyond a reasonable doubt that you want to permanently delete this event from all dates, or forever hold your peace?')) return;
<?php endif; ?>
	document.form1.target='';
	document.form1.action='lib/deleteevent.php';
	document.form1.submit();
	}

</script>


<?php postcode() ?>
