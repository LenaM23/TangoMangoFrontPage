<!-- Header -->
<?php
if (!isset($tab)) $tab='';
if (!isset($lit)) {
  $lit = array();
  $default=      'nav';
  $lit['events']='nav_events';
  $lit['mycal']= 'nav_mycal';
  $lit['res']=   'nav_res';
}
if (!isset($default)) $default = '';
if (!isset($urlroot)) $urlroot = '.';
if (!isset($notabs)) $notabs = '';
if (!isset($events)) $events = '';
if (!isset($nologin)) $nologin = '';
if (!isset($useremail)) $useremail = '';

if($tab=='events') {
	$default=      'nav_events';
	$lit['mycal']= 'nav_events_mycal';
	$lit['res']=   'nav_events_res';
	}
elseif($tab=='mycal') {
	$default=      'nav_mycal';
	$lit['events']='nav_events_mycal';
	$lit['res']=   'nav_mycal_res';
	}
elseif($tab=='res') {
	$default=      'nav_res';
	$lit['events']='nav_events_res';
	$lit['mycal']= 'nav_mycal_res';
	}
else {
	$default=      'nav';
	$lit['events']='nav_events';
	$lit['mycal']= 'nav_mycal';
	$lit['res']=   'nav_res';
	}
?>
<!-- <center><p>NOTICE:  CDT</p></center> -->
<!--<h1><a href="--><?//=$urlroot?><!--/donations.php" >Donate to keep TangoMango running.</a></h1>-->
<div id="horz_ad_bar">
  <a id="single_image1" href="#"><img src="<?=$urlroot?>/media/batango_logo_90x90.png"></a>
  <a id="single_horz_ad1" href="#"><img src="https://dummyimage.com/364x90/444/fff" alt=""/></a>
  <a id="single_image2" href="https://tangousachampionship.com/home/" ><img src="<?=$urlroot?>/media/xtr/ATUSA2023_tangomango2023.jpg"></a>
  <a id="single_horz_ad3" href="#"><img src="https://dummyimage.com/364x90/444/fff" alt=""/></a>  <span class="stretch"></span>
</div>
<!--<div style="flex-direction:row; align-content: space-between; " xmlns="http://www.w3.org/1999/html"><img src="--><?//=$urlroot?><!--/media/batango_logo_90x90.png"><a   href="https://tangousachampionship.com/home/"><img src="--><?//=$urlroot?><!--/media/ads/ATUSA2023_tangomango2023.jpg"></a><a id="single_horz_ad" href="#"><img src="https://dummyimage.com/364x90/444/fff" alt=""/></a>-->
</div>
<table width=100% style="background-image:url(<?=$urlroot?>/media/bigbar.gif); background-repeat:repeat-x; margin-bottom:8px" border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td height=75>
			<img src="<?=$urlroot?>/media/tango.gif" width=285 height=75 <?php if(!$notabs) echo "onclick=\"location='$urlroot/index.php'\"" ?>>
		</td>
<?php if(!$notabs): ?>
		<td align=right valign=top>
			<table id=navtabs height=66 width=392 style="background-image:url(<?=$urlroot?>/media/<?=$default?>.gif); background-repeat:no-repeat;" border=0 cellpadding=0 cellspacing=0>
				<tr>
					<td align=right valign=top colspan=7 height=42 style="padding:4px 20px 0px 0px">
<?php if(!$useremail && !$nologin): ?>
						<a class=link href="<?=$urlroot?>/login.php">Login</a>
<?php elseif(!$nologin): ?>
						<?=munge($useremail)?> &nbsp;
						<a class=link href="<?=$urlroot?>/login.php">Logout</a>
<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td width=48 height=24></td>
					<td width=65 <?php if($tab!=='events'): ?>onmouseover="overtab('events')" onmouseout="outtab()" onclick="location='<?=$urlroot?>/index.php'"<?php endif; ?>>&nbsp;</td>
					<td width=21></td>
					<td width=124 <?php if($tab!=='mycal'): ?>onmouseover="overtab('mycal')" onmouseout="outtab()" onclick="location='<?=$urlroot?>/mycal.php'"<?php endif; ?>>&nbsp;</td>
					<td width=17></td>
					<td width=97 <?php if($tab!=='res'): ?>onmouseover="overtab('res')" onmouseout="outtab()" onclick="location='<?=$urlroot?>/resources.php'"<?php endif; ?>>&nbsp;</td>
					<td width=18></td>
				</tr>
			</table>
		</td>
<?php endif; ?>
	</tr>
</table>

<script>

function overtab(tab) {
	if    (tab=='events') document.getElementById('navtabs').style.backgroundImage='url(<?=$urlroot?>/media/<?=$lit['events']?>.gif)';
	else if(tab=='mycal') document.getElementById('navtabs').style.backgroundImage='url(<?=$urlroot?>/media/<?=$lit['mycal']?>.gif)';
	else if(tab=='res')   document.getElementById('navtabs').style.backgroundImage='url(<?=$urlroot?>/media/<?=$lit['res']?>.gif)';
	}

function outtab() {
	document.getElementById('navtabs').style.backgroundImage='url(<?=$urlroot?>/media/<?=$default?>.gif)';
	}

</script>
