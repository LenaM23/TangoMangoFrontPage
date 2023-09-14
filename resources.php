<?php
require_once("lib/functions.php");
$tab='res';
loaduser('useremail');

// Use settings if from edit resource, else default blank
if($list=request('list')) {
	foreach(decom($list) as $x)
    ${$x}=$x;
	}

?>
<html>
<head>
<title>Resources</title>
<script src="lib/script.js?2"></script>
<link href="media/stylesheet.css?2" rel=stylesheet type=text/css>
<link href="media/<?= platform() ?>.css?1" rel=stylesheet type=text/css>

<script>

function overres(resid) {
	setstyle('link'+resid,'show');
	}

function outres(resid) {
	setstyle('link'+resid,'spacer');
	}

function editres(resid) {
	location='editresource.php?resid='+resid+'&list='+list;
	}

function getres(list) {
	window.dataframe.location='lib/getresources.php?list='+list;
	setspan('listings','&nbsp;'); ///<div class=styled>Loading...</div>
	}

function resloaded() {  // uses innerHTML because form values change &lt; to < etc.
	setspan('listings',window.dataframe.document.getElementById('listings').innerHTML);
	filters=window.dataframe.document.getElementById('filters').innerHTML;
	if(filters) filters=filters.split(',');
	if(list=='info') updateinfo();
	else if(list=='music') updatemusic();
	}

</script>
</head>
<body>
<?php include_once("analyticstracking.php") ?>
<!-- two column layout -->
<table width=100% height=100% border=0 cellpadding=0 cellspacing=0>
	<tr class=noprint>
		<td colspan=2 valign=top>

<form name=form1 action="editresource.php" method=post onSubmit="return false">
<?php require("header.php"); ?>

		</td>
	</tr>
	<tr valign=top>
		<td nowrap width=1% height=100% class=noprint style="padding: 0px 0px 0px 20px">

<!-- DIRECTORY -->
<div class=styled>Directory<input class="checkbox spacer" type=checkbox></div>
<div class=styled2>
<input class=checkbox type=checkbox name=teacher   value=1 onClick="update(this.name,1)" <?php if(isset($teacher)) echo 'checked' ?>
><span onClick="clickcheck('teacher')">&nbsp;Teachers</span><br>
<input class=checkbox type=checkbox name=dj        value=1 onClick="update(this.name,1)" <?php if(isset($dj)) echo 'checked' ?>
><span onClick="clickcheck('dj')">&nbsp;DJ's</span><br>
<input class=checkbox type=checkbox name=musician  value=1 onClick="update(this.name,1)" <?php if(isset($musician)) echo 'checked' ?>
><span onClick="clickcheck('musician')">&nbsp;Musicians</span><br>
<input class=checkbox type=checkbox name=performer value=1 onClick="update(this.name,1)" <?php if(isset($performer)) echo 'checked' ?>
><span onClick="clickcheck('performer')">&nbsp;Performers</span><br>
<input class=checkbox type=checkbox name=org       value=1 onClick="update(this.name,1)" <?php if(isset($org)) echo 'checked' ?>
><span onClick="clickcheck('org')">&nbsp;Organizations</span><br>
<input class=checkbox type=checkbox name=retail    value=1 onClick="update(this.name,1)" <?php if(isset($retail)) echo 'checked' ?>
><span onClick="clickcheck('retail')">&nbsp;Shoes, Apparel, CD's...</span><br>
<br>
</div>

<!-- INFORMATION -->
<div class=styled>Links, Books, Videos<input class="checkbox spacer" type=checkbox></div>
<div class=styled2>
<input class=checkbox type=checkbox name=infodance value=1 onClick="update('info')" <?php if(isset($infodance)) echo 'checked' ?>
><span onClick="clickcheck('infodance')">&nbsp;About Tango Dance</span><br>
<input class=checkbox type=checkbox name=infomusic value=1 onClick="update('info')" <?php if(isset($infomusic)) echo 'checked' ?>
><span onClick="clickcheck('infomusic')">&nbsp;About Tango Music</span><br>
<input class=checkbox type=checkbox name=infoevent value=1 onClick="update(this.name)" <?php if(isset($infoevent)) echo 'checked' ?>
><span onClick="clickcheck('infoevent')">&nbsp;About Tango Events</span><br>
<input class=checkbox type=checkbox name=travel value=1 onClick="update(this.name)" <?php if(isset($travel)) echo 'checked' ?>
><span onClick="clickcheck('travel')">&nbsp;Travel, Culture</span><br>
<input class=checkbox type=checkbox name=howto value=1 onClick="update(this.name)" <?php if(isset($howto)) echo 'checked' ?>
><span onClick="clickcheck('howto')">&nbsp;Instructional Videos</span><br>
<input class=checkbox type=checkbox name=movie value=1 onClick="update(this.name)" <?php if(isset($movie)) echo 'checked' ?>
><span onClick="clickcheck('movie')">&nbsp;Movies featuring Tango</span><br>
<br>
</div>

<div class=styled>Recommended Music<input class="checkbox spacer" type=checkbox></div>
<div class=styled2>
<input class=checkbox type=checkbox name=tango value=tango onClick="update('music')" <?php if(isset($tango)) echo 'checked' ?>
><span onClick="clickcheck('tango')">&nbsp;Tango</span><br>
<input class=checkbox type=checkbox name=vals value=vals onClick="update('music')" <?php if(isset($vals)) echo 'checked' ?>
><span onClick="clickcheck('vals')">&nbsp;Vals</span><br>
<input class=checkbox type=checkbox name=milonga value=milonga onClick="update('music')" <?php if(isset($milonga)) echo 'checked' ?>
><span onClick="clickcheck('milonga')">&nbsp;Milonga</span><br>
<input class=checkbox type=checkbox name=traditional value=traditional onClick="update('music')" <?php if(isset($traditional)) echo 'checked' ?>
><span onClick="clickcheck('traditional')">&nbsp;Traditional</span><br>
<input class=checkbox type=checkbox name=contemporary value=contemporary onClick="update('music')" <?php if(isset($contemporary)) echo 'checked' ?>
><span onClick="clickcheck('contemporary')">&nbsp;Contemporary</span><br>
<input class=checkbox type=checkbox name=alternative value=alternative onClick="update('music')" <?php if(isset($alternative)) echo 'checked' ?>
><span onClick="clickcheck('alternative')">&nbsp;Alternative</span>
</div>

<br>
<span id=showchangeloc class=hide>
<a href="choosearea.php">Change location</a><br>
</span>
<a href="javascript:gopost()">Add a new item</a>

		</td>
		<td width=99% style="padding: 0px 0px 0px 20px">

<!-- LISTINGS -->
<span id=listings>&nbsp;</span>

		</td>
	</tr>
	<tr class=noprint>
		<td nowrap colspan=2 valign=bottom>
<input type=hidden name=pagecomplete value=events>
</form>
<iframe name=dataframe width=0 height=0 frameborder=0></iframe>
			<br>
			<br>
<div class=small align=center>If anything above is incorrect, please correct it. If anything is missing, please <a href=\"javascript:gopost()\">post</a> it.</div>
			<?php require('footer.php'); ?>
		</td>
	</tr>
</table><!-- two column layout -->
</body>
</html>

<script>
list='<?php
if(isset($teacher)) echo 'teacher';
elseif(isset($dj)) echo 'dj';
elseif(isset($musician)) echo 'musician';
elseif(isset($performer)) echo 'performer';
elseif(isset($org)) echo 'org';
elseif(isset($retail)) echo 'retail';
elseif(isset($infodance) || isset($infomusic)) echo 'info';
elseif(isset($infoevent)) echo 'infoevent';
elseif(isset($travel)) echo 'travel';
elseif(isset($howto)) echo 'howto';
elseif(isset($movie)) echo 'movie';
elseif(isset($tango) || isset($vals) || isset($milonga) || isset($traditional) || isset($contemporary) || isset($alternative)) echo 'music';
?>';

function update(l,showloc) {
	if(l!='teacher') uncheck('teacher');
	if(l!='dj') uncheck('dj');
	if(l!='musician') uncheck('musician');
	if(l!='performer') uncheck('performer');
	if(l!='org') uncheck('org');
	if(l!='retail') uncheck('retail');
	if(l!='info') {
		uncheck('infodance');
		uncheck('infomusic');
		}
	if(l!='infoevent') uncheck('infoevent');
	if(l!='travel') uncheck('travel');
	if(l!='howto') uncheck('howto');
	if(l!='movie') uncheck('movie');
	if(l!='music') {
		uncheck('tango');
		uncheck('vals');
		uncheck('milonga');
		uncheck('traditional');
		uncheck('contemporary');
		uncheck('alternative');
		}
	if(l=='music') {
		if(!checked('traditional') && !checked('contemporary') && !checked('alternative')) {
			check('traditional');
			check('contemporary');
			check('alternative');
			}
		if(!checked('tango') && !checked('vals') && !checked('milonga')) {
			check('tango');
			check('vals');
			check('milonga');
			}
		}
	setstyle('showchangeloc',(showloc)?'show':'hide');
	if(l!=list) {
		list=l;
		getres(list);
		}
	else if(list=='info') updateinfo();
	else if(list=='music') updatemusic();
	}


function updateinfo() {
	if(!filters) return;
	dance=checked('infodance');
	music=checked('infomusic');
	icount=filters.length;
	for(i=0;i<icount;i++) {
		if(dance && filters[i].charAt(0)!='-') style='show';
		else if(music && filters[i].charAt(1)!='-') style='show';
		else style='hide';
		setstyle('i'+i,style);
		}
	}

function updatemusic() {
	if(!filters) return;
	dance=[];
	if(checked('tango')) dance[dance.length]=0;
	if(checked('vals')) dance[dance.length]=1;
	if(checked('milonga')) dance[dance.length]=2;
	flavor=[];
	if(checked('traditional')) flavor[flavor.length]=3;
	if(checked('contemporary')) flavor[flavor.length]=4;
	if(checked('alternative')) flavor[flavor.length]=5;
	dcount=dance.length;
	fcount=flavor.length;
	icount=filters.length;
	for(i=0;i<icount;i++) {
		style='hide';
		for(x=0;x<dcount;x++) {
			if(filters[i].charAt(dance[x])=='-') continue;  // keep scanning till first dance match
			for(y=0;y<fcount;y++) {
				if(filters[i].charAt(flavor[y])=='-') continue;  // keep scanning till first flavor match
				style='show';
				break;
				}
			break;
			}
		setstyle('i'+i,style);
		}
	}

if(list) setTimeout('getres(list)',1);

</script>


<?php postcode(); ?>
