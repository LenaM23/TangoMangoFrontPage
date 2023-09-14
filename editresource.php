<?php
require_once("lib/functions.php");
global $useremail; global $countryid; global $stateid;
loaduser('useremail,countryid,stateid');

if (!isset($stateid)) $stateid = null;
if (!isset($address)) $address=null;
if (!isset($album)) $album=null;
if (!isset($area)) $area=null;
if (!isset($artist)) $artist=null;
if (!isset($city)) $city=null;
if (!isset($description)) $description=null;
if (!isset($directions)) $directions=null;
if (!isset($email)) $email=null;
if (!isset($filters)) $filters=null;
if (!isset($name)) $name=null;
if (!isset($phone)) $phone=null;
if (!isset($song)) $song=null;
if (!isset($type)) $type=null;
if (!isset($web)) $web=null;
if (!isset($worldwide)) $worldwide=null;

// edit res
if($resid=request('resid')) {
	$list=request('list');
	$new=$dir=$info=$music=false;
	if($list=='music') {
		varsql("select * from Music where resid=$resid");
		$music=true;
		foreach(decom(enjoin(',',$dance,$flavor)) as $x)
      ${$x}=$x;
		}
	elseif(isany($list,'info,infoevent,travel,howto,movie')) {
		varsql("select * from Info where resid=$resid");
		$info=true;
    $type = null;
    foreach(decom($type) as $x)
      ${$x}=$x;
		}
	else {
	  // TODO fix the varsql global setting.
		varsql("select * from Directory where resid=$resid");
    if(!$countryid) $countryid='US';
		$dir=true;
		}
	}

// new
else {  // default to settings from Resources page
	$teacher=form('teacher');
	$dj=form('dj');
	$musician=form('musician');
	$performer=form('performer');
	$org=form('org');
	$retail=form('retail');
	$infodance=form('infodance');
	$infomusic=form('infomusic');
	$infoevent=form('infoevent');
	$travel=form('travel');
	$howto=form('howto');
	$movie=form('movie');
	$tango=form('tango');
	$vals=form('vals');
	$milonga=form('milonga');
	$traditional=form('traditional');
	$contemporary=form('contemporary');
	$alternative=form('alternative');
	$new=true;
	$info=($infodance || $infomusic || $infoevent || $travel || $howto || $movie);
	$music=($tango || $vals || $milonga || $traditional || $contemporary || $alternative);
	$dir=(!$info && !$music);  // default if blank
	}

?>
<html>
<head>
<title>Edit Resources</title>
<script src="lib/script.js?2"></script>
<link href="media/stylesheet.css?2" rel=stylesheet type=text/css>
<link href="media/<?= platform() ?>.css?1" rel=stylesheet type=text/css>
</head>
<body>
<?php include_once("analyticstracking.php") ?>
<!-- page layout -->
<table width=100% height=100% border=0 cellpadding=0 cellspacing=0>
	<tr class=noprint>
		<td valign=top>

<form name=form1 action="lib/saveresource.php" method=post onSubmit="return false">
<input type=hidden name=resid value=<?=$resid?>>
<input type=hidden name=list value=<?php if($dir) echo 'dir'; elseif($info) echo 'info'; elseif($music) echo 'music'; ?>>
<?php if($dir && $resid): ?>
<input type=hidden name=restype value=<?=$type?>>
<?php endif; ?>
<?php require("header.php"); ?>

		</td>
	</tr>
	<tr valign=top>
		<td height=100% style="padding-left:20px">

<div class=big>
<?php
if($new) echo "Add a New Resource";
elseif($dir) echo "Edit Directory";
elseif($info) echo "Edit Website/Book/Video";
elseif($music) echo "Edit Recommended Music";
?>
</div>
<br>

<table border=0 cellpadding=0 cellspacing=0>
<!-- TYPES -->
<?php if($new): ?>
	<tr>
		<td><b>New</b> &nbsp;</td>
		<td nowrap>
<select class=menu name=restype onChange="changetype()">
	<option value=teacher   <?php if($teacher)   echo 'selected' ?>>Teacher/Choreographer
	<option value=dj        <?php if($dj)        echo 'selected' ?>>DJ
	<option value=musician  <?php if($musician)  echo 'selected' ?>>Musician
	<option value=performer <?php if($performer) echo 'selected' ?>>Performer Dancer
	<option value=org       <?php if($org)       echo 'selected' ?>>Organization
	<option value=retail    <?php if($retail)    echo 'selected' ?>>Store/Retailer
	<option value=info      <?php if($info)      echo 'selected' ?>>Website/Book/Video
	<option value=music     <?php if($music)     echo 'selected' ?>>Recommended Music
</select>
		</td>
	</tr>
<?php endif; ?>

	<tr id=showinfotypes <?php if(!$info) echo 'class=hide' ?>>
		<td></td>
		<td nowrap style="padding-bottom:0.6em">
<input class=checkbox type=checkbox name=infodance value=infodance <?php if($infodance) echo 'checked' ?> onClick="updateinfo('info')"><span onClick="clickcheck('infodance')"> About Tango Dance</span><br>
<input class=checkbox type=checkbox name=infomusic value=infomusic <?php if($infomusic) echo 'checked' ?> onClick="updateinfo('info')"><span onClick="clickcheck('infomusic')"> About Tango Music</span><br>
<input class=checkbox type=checkbox name=infoevent value=infoevent <?php if($infoevent) echo 'checked' ?> onClick="updateinfo('infoevent')"><span onClick="clickcheck('infoevent')"> About Tango Events</span><br>
<input class=checkbox type=checkbox name=travel value=travel <?php if($travel) echo 'checked' ?> onClick="updateinfo('travel')"><span onClick="clickcheck('travel')"> Travel, Culture</span><br>
<input class=checkbox type=checkbox name=howto value=howto <?php if($howto) echo 'checked' ?> onClick="updateinfo('howto')"><span onClick="clickcheck('howto')"> Instructional Video</span><br>
<input class=checkbox type=checkbox name=movie value=movie <?php if($movie) echo 'checked' ?> onClick="updateinfo('movie')"><span onClick="clickcheck('movie')"> Movie featuring Tango</span>
		</td>
	</tr>

	<tr id=showmusictypes <?php if(!$music) echo 'class=hide' ?>>
		<td></td>
		<td nowrap>
			<table border=0 cellpadding=0 cellspacing=0>
				<tr valign=top>
					<td nowrap>
<input class=checkbox type=checkbox name=tango value=tango <?php if($tango) echo 'checked' ?>><span onClick="clickcheck('tango')"> Tango</span><br>
<input class=checkbox type=checkbox name=vals value=vals <?php if($vals) echo 'checked' ?>><span onClick="clickcheck('vals')"> Vals</span><br>
<input class=checkbox type=checkbox name=milonga value=milonga <?php if($milonga) echo 'checked' ?>><span onClick="clickcheck('milonga')"> Milonga</span>
					</td>
					<td nowrap style="padding-left:20px">
<input class=checkbox type=checkbox name=traditional value=traditional <?php if($traditional) echo 'checked' ?>><span onClick="clickcheck('traditional')"> Traditional</span><br>
<input class=checkbox type=checkbox name=contemporary value=contemporary <?php if($contemporary) echo 'checked' ?>><span onClick="clickcheck('contemporary')"> Contemporary</span><br>
<input class=checkbox type=checkbox name=alternative value=alternative <?php if($alternative) echo 'checked' ?>><span onClick="clickcheck('alternative')"> Alternative</span>
					</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr id=showtip <?php if($info) echo 'class=hide' ?>>
		<td></td>
		<td nowrap class=small style="padding-top:0.6em">Leave anything blank that does not apply or is unknown:</td>
	</tr>

<!-- DATA -->
	<tr id=showname <?php if($music) echo 'class=hide' ?>>
		<td nowrap><b id=namelabel><?php
if($info) echo "Title";
elseif(!$resid && $dir) echo "Name";
elseif($type=='teacher') echo "Teacher/Choreographer";
elseif($type=='dj') echo "DJ";
elseif($type=='musician') echo "Musician/Group";
elseif($type=='performer') echo "Performer";
elseif($type=='org') echo "Organization";
elseif($type=='retail') echo "Store";
			?></b> &nbsp;</td>
		<td nowrap><input class=textbox name=name style="width:30em" maxlength=250 value="<?=$name?>" onChange="captext(this,6)"></td>
	</tr>
	<tr id=showsong <?php if(!$music) echo 'class=hide' ?>>
		<td nowrap><b>Song</b> &nbsp;</td>
		<td nowrap><input class=textbox name=song style="width:30em" maxlength=250 value="<?=$song?>" onChange="captext(this,6)"></td>
	</tr>
	<tr id=showartist <?php if(!$music) echo 'class=hide' ?>>
		<td nowrap><b>Artist</b> &nbsp;</td>
		<td nowrap><input class=textbox name=artist style="width:30em" maxlength=250 value="<?=$artist?>" onChange="captext(this,6)"></td>
	</tr>
	<tr id=showalbum <?php if(!$music) echo 'class=hide' ?>>
		<td nowrap><b>Album</b> &nbsp;</td>
		<td nowrap><input class=textbox name=album style="width:30em" maxlength=250 value="<?=$album?>" onChange="captext(this,6)"></td>
	</tr>
	<tr>
		<td nowrap><b id=weblabel><?= ($info)?'URL':'Website' ?></b> &nbsp;</td>
		<td nowrap><input class=textbox name=web style="width:30em" maxlength=250 value="<?=$web?>" onChange="changelink(this)"></td>
	</tr>
	<tr id=showemail <?php if(!$dir) echo 'class=hide' ?>>
		<td nowrap><b>Email</b> &nbsp;</td>
		<td nowrap><input class=textbox name=email style="width:30em" maxlength=250 value="<?=$email?>" onChange="changeemail(this)"></td>
	</tr>
	<tr id=showphone valign=top <?php if(!$dir) echo 'class=hide' ?>>
		<td nowrap style="padding-top:4px"><b>Phone</b> &nbsp;</td>
		<td nowrap><textarea class=textbox name=phone style="width:30em" rows=2 onChange="maxlength(this,250)"><?=unhtml($phone)?></textarea></td>
	</tr>

	<tr id=showgap <?php if(!$dir || $type=='retail' || $retail) echo 'class=hide' ?>>
		<td>&nbsp;</td>
	</tr>

	<tr id=showworldwide <?php if(!(($resid && ($type=='retail')) || (!$resid && $retail))) echo 'class=hide' ?>>
		<td></td>
		<td nowrap style="padding-top:0.6em">
<input class=checkbox type=checkbox name=worldwide value=1 <?php if($worldwide) echo 'checked' ?> onClick="updateretail('worldwide')"><span onClick="clickcheck('worldwide')"> Web/mail order</span><br>
<input class=checkbox type=checkbox name=local value=1 <?php if($city) echo 'checked' ?> onClick="updateretail('local')"><span onClick="clickcheck('local')"> Local store:</span>
		</td>
	</tr>

<!-- ADDRESS, CITY -->
	<tr id=showaddress <?php if(!$dir) echo 'class=hide' ?>>
		<td nowrap><b>Address</b> &nbsp;</td>
		<td><input class=textbox name=address style="width:30em" maxlength=250 value="<?=$address?>" onChange="captext(this,3)"></td>
	</tr>
	<tr id=showdirections <?php if(!$dir) echo 'class=hide' ?>>
		<td></td>
		<td><textarea class=textbox name=directions rows=2 style="width:30em" onChange="captext(this,3,true);maxlength(this,250)"><?=unhtml($directions)?></textarea></td>
	</tr>
	<tr id=showdirections2 <?php if(!$dir) echo 'class=hide' ?>>
		<td></td>
		<td class=small>
			Use the "Address" line for the exact address to be found by Google<br>Maps.
			Use the second box for cross-streets, landmarks, parking, etc.
		</td>
	</tr>
	<tr id=showcity <?php if(!$dir) echo 'class=hide' ?>>
		<td nowrap><b>City</b> &nbsp;</td>
		<td><input class=textbox name=city style="width:30em" maxlength=250 value="<?=$city?>" onChange="captext(this,4)"></td>
	</tr>

<!-- COUNTRY -->
	<tr id=showcountry class=hide>
		<td nowrap><b>Country</b> &nbsp;</td>
		<td>
<select class=menu name=countryinfo onChange="changecountry()">
<option value="US:State" selected>United States
</select>
		</td>
	</tr>

<!-- STATE -->
	<tr id=showstate <?php if(!$dir) echo 'class=hide' ?>>
		<td nowrap><b id=statelabel>State</b> &nbsp;</td>
		<td>
<select class=menu name=stateinfo onChange="changestate()">
<?php
$arealabel='';
if(!$countryid) $countryid='US';
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
	<tr id=showarea <?php if(!$dir || $qrows<=1) echo 'class=hide' ?>>
		<td nowrap><b id=arealabel><?=$arealabel?></b> &nbsp;</td>
		<td>
<select class=menu name=area>
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

<!-- MAP -->
	<tr id=showmap <?php if(!$dir) echo 'class=hide' ?>>
		<td></td>
		<td nowrap style="padding-bottom:1em">
			<input class=button type=button value="Test Map" onClick="testmap()">
		</td>
	</tr>

<!-- DESCRIPTION -->
	<tr valign=top>
		<td nowrap style="padding-top:4px"><b>Description</b> &nbsp;</td>
		<td nowrap><textarea class=textbox name=description rows=10 style="width:30em" onChange="maxlength(this,1500)"><?=unhtml($description)?></textarea><br>
			<span class=small>No HTML, but email and web addresses are hyperlinked automatically.<br>Limit 1500 characters.</span>
		</td>
	</tr>
	<tr id=showbuttons>
		<td></td>
		<td nowrap>
<br>
<input class=button type=button value="Cancel" onClick="location='resources.php'">&nbsp;
<?php if(!$new): ?>
<input class=button type=button value="Delete" onClick="openprompt()">&nbsp;
<?php endif; ?>
<input class=button type=button value="&nbsp;Save&nbsp;" onClick="checkform()">
		</td>
	</tr>

<!-- CONFIRM DELETE -->
	<tr id=showprompt class=hide>
		<td></td>
		<td>
			<br>
			<div class=dark style="padding:12px 20px">
				Are you sure you want to delete this listing?
				<div align=right style="padding-top:12px">
<input class=button type=button value="Cancel" onClick="cancelprompt()">&nbsp;
<input class=button type=button value="Delete" onClick="okprompt()"><br>
				</div>
			</div>
		</td>
	</tr>
</table>

		</td>
	</tr>
	<tr class=noprint>
		<td nowrap valign=bottom>
<input type=hidden name=pagecomplete value=editresource>
</form>
<iframe name=dataframe width=0 height=0 frameborder=0></iframe>
			<br>
			<br>
			<?php require('footer.php'); ?>
		</td>
	</tr>
</table>
</body>
</html>

<script>

<?php if($new): ?>
function changetype() {
	type=menu('restype');
	if(type!='info' && type!='music') list='dir';
	else list=type;
	setspan('namelabel',(list=='info')?'Title':'Name');
	setspan('weblabel',(list=='info')?'URL':'Website');
	setstyle('showinfotypes',(list=='info')?'show':'hide');
	setstyle('showmusictypes',(list=='music')?'show':'hide');
	setstyle('showtip',(list!='info')?'show':'hide');
	setstyle('showname',(list!='music')?'show':'hide');
	setstyle('showemail',(list=='dir')?'show':'hide');
	setstyle('showphone',(list=='dir')?'show':'hide');
	setstyle('showworldwide',(type=='retail')?'show':'hide');
	setstyle('showgap',(list=='dir' && type!='retail')?'show':'hide');
	setstyle('showaddress',(list=='dir')?'show':'hide');
	setstyle('showdirections',(list=='dir')?'show':'hide');
	setstyle('showdirections2',(list=='dir')?'show':'hide');
	setstyle('showcity',(list=='dir')?'show':'hide');
	setstyle('showstate',(list=='dir')?'show':'hide');
	setstyle('showarea', (list=='dir')?'show':'hide');
	setstyle('showmap',(list=='dir')?'show':'hide');
	setstyle('showsong',(list=='music')?'show':'hide');
	setstyle('showalbum',(list=='music')?'show':'hide');
	setstyle('showartist',(list=='music')?'show':'hide');
	settext('list',list);
	}
<?php endif; ?>

function updateinfo(w) {
	if(w!='info') {
		uncheck('infodance');
		uncheck('infomusic');
		}
	if(w!='infoevent') uncheck('infoevent');
	if(w!='travel') uncheck('travel');
	if(w!='howto') uncheck('howto');
	if(w!='movie') uncheck('movie');
	}

function updateretail(w) {
	if(checked('worldwide') || checked('local')) return;
	if(w=='worldwide') check('local');
	else check('worldwide');
	}

function checkform() {
<?php if($new): ?>
	list=menu('restype');
	if(list=='music') {
		if(!checked('tango') && !checked('vals') && !checked('milonga')) return alert('Must select Tango, Vals, and/or Milonga');
		if(!checked('traditional') && !checked('contemporary') && !checked('alternative'))
			return alert('Must select Traditional, Contemporary, and/or Alternative');
		if(!text('artist') && !text('album') && !text('song')) return alert('Artist, Album, or Song are required');
		}
	else if(list=='info') {
		if(!checked('infodance') && !checked('infomusic') && !checked('infoevent') && !checked('travel') && !checked('howto') && !checked('movie'))
			return alert('At least one checkbox must be checked');
		if(!text('name')) return alert('Title is required');
		}
	else {
		if(!text('name')) return alert('Name is required');
		if(list=='retail' && !checked('worldwide') && !checked('local')) return alert('Must select Web/mail order or Local store');
		if(list!='retail' || checked('local')) {
			if(!menu('stateinfo')) return alert('State is required');
			if(!menu('area')) return alert(document.getElementById('arealabel').innerHTML+' is required');
			}
		}
<?php elseif($dir): ?>
	if(!text('name')) return alert(document.getElementById('namelabel').innerHTML+' is required');
	if(<?=($type=='retail')?"checked('local')":'true'?>) {
		if(!menu('stateinfo')) return alert('State is required');
		if(!menu('area')) return alert(document.getElementById('arealabel').innerHTML+' is required');
		}
<?php elseif($info): ?>
	if(!checked('infodance') && !checked('infomusic') && !checked('infoevent') && !checked('travel') && !checked('howto') && !checked('movie'))
		return alert('At least one checkbox must be checked');
	if(!text('name')) return alert('Title is required');
<?php elseif($music): ?>
		if(!checked('tango') && !checked('vals') && !checked('milonga')) return alert('Must select Tango, Vals, and/or Milonga');
		if(!checked('traditional') && !checked('contemporary') && !checked('alternative'))
			return alert('Must select Traditional, Contemporary, and/or Alternative');
	if(!text('artist') && !text('album') && !text('song')) return alert('Artist, Album, or Song are required');
<?php endif; ?>
	gopost('lib/saveresource.php');
	}


<!-- PROMPT -->

function openprompt() {
	setstyle('showbuttons','hide');
	setstyle('showprompt','show');
	}

function cancelprompt() {
	setstyle('showbuttons','show');
	setstyle('showprompt','hide');
	}

function okprompt() {
	gopost('lib/deleteresource.php');
	}


<!-- STATE & AREA -->

function changestate() {
	s=menu('stateinfo');
	s=s.split(':');
	stateid=s[0];
	label=s[1];
	c='US:State';
	c=c.split(':');
	countryid=c[0];
	total=document.form1.area.options.length;
	for(x=0;x<total;x++)  // delete all menu items
		document.form1.area.options[0]=null;
	document.form1.area.options[0]=new Option('Loading...','');
	setspan('arealabel',label);
	setstyle('showarea','show');
	window.dataframe.location='lib/getareas.php?countryid='+countryid+'&stateid='+stateid;
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
		setstyle('showarea','hide');
		}
	}

function testmap() {
	x=menu('stateinfo');
	x=x.split(':');
	stateid=x[0];
	dest=escape(text('address')+', '+text('city')+', '+stateid);
	url='https://maps.google.com/maps?daddr='+dest;
	window.open(url,'mapwin');
	}

</script>


<?php postcode(); ?>
