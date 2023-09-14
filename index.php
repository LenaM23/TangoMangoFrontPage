<?php
/*
show=San+Francisco,CA;Alameda,CA&hide=Santa+Clara,CA&country=US
by=email+email.com&title=
weeks=4
*/
$userid = ''; $rawtitle='';
require_once("lib/functions.php");
loaduser('*');
$by=request('by');  // show all by host
if($by) {
	$by=str_replace(' ','@',$by);
	$rawtitle=request('title');
	$title=str_replace("\n",'<br>',$rawtitle);
	if(!$title) $title="Events by<br>$by";
	}
$show=request('show');
$eventid=request('eventid');
// menu of cities to choose
if(!$userid && !$show && !$by && !$eventid && form('pagecomplete')!='events') return require('home.php');

logit("calendar");
require('calendar.php');
?>




