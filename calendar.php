<?php
/*
index.php
index.php?by=email+email.com&title=Events+by+%0AYour+Name
index.php?show=San+Francisco,CA;Alameda,CA&hide=Santa+Clara,CA
index.php?eventid=44
mycal.php
*/

if (!isset($mycal)) $mycal = false;
if (!isset($by)) $by = false;
if (!isset($show)) $show = false;
if (!isset($eventid)) $eventid = null;
if (!isset($rawtitle)) $rawtitle = '';
if (!isset($date1)) $date1 = '';
if (!isset($hacker)) $hacker = '';
if (!isset($nologin)) $nologin = '';
if (!isset($notabs)) $notabs = '';
if (!isset($tab)) $tab = '';
if (!isset($useremail)) $useremail = '';
if (!isset($title)) $title = '';
if (!isset($virtual)) $virtual = '0';

if (!isset($showweeks)) $showweeks = 4;
if (!isset($areashtml)) $areashtml = '';
if (!isset($areacodes)) $areacodes = array();

logit("calendar");
logvar($showweeks);

// Log user visit
if (!empty($userid)) {
  if (!empty($lastvisit)) {
if($userid && $lastvisit!=date('Y-m-d')) sql("update Users set lastvisit=curdate() where userid=$userid",'Log user visit');
  }
}

// Weeks
if(request('weeks')) $showweeks=request('weeks');  // use specifed weeks if any
elseif(request('showweeks')) $showweeks=request('showweeks');
elseif(!isset($showweeks)) $showweeks=4;  // else if no prefs on file default 4 weeks

// Preselected event
if($eventid1=request('eventid')) {
  $date1=request('date');
  if(!$date1) $date1=getsql("select min(date) from Events where eventid=$eventid1 && date>=curdate()",'Find first date of event');  // if date not specified, use soonest
  $e1='false';  // match calendar item later
}

// Modes:
// regular: area checkboxes
// by: show events by host (any areas)
// mycal: show favorites (any areas)
$regular=(bool)(!$by && !$mycal);
$tab=($mycal)?'mycal':'events';

$areas=array();

/*
show:              use areas from ?show=               weeks=4 or request
regular from self: use areas from screen, save prefs   weeks=request, save
regular from cold: use areas from database             weeks=database or request
mycal   from self: use areas from database             weeks=request, save
mycal   from cold: use areas from database             weeks=database or request
by:                no areas needed                     weeks=database or request

*/

// Preselected Areas
if($show) {
  $countryid=request('country');
  if(!$countryid) $countryid='US';
  foreach(explode(' ',request('show')) as $as) {
    list($area,$stateid)=explode(',',str_replace('_',' ',$as));
    $areas[]=array($countryid,$stateid,$area,1);
  }
  if(request('hide')) {
    foreach(explode(' ',request('hide')) as $as) {
      list($area,$stateid)=explode(',',str_replace('_',' ',$as));
      $areas[]=array($countryid,$stateid,$area,0);
    }
  }
	$milonga=$dropin=$series=$practica=$livemusic=$performance=$other=$virtual=1;
}

// Load Prefs from Page
elseif($regular && form('pagecomplete') === 'events') {  // save prefs if changing weeks
  $from='index.php';
  require('lib/saveprefs.php');  // sets $areas, $showeeks, $milonga, etc. from page
}

// Load Prefs from Database
elseif($userid && !$by) {
  $qid=sql("select countryid,stateid,area,selected from UserAreas where userid=$userid order by countryid,stateid,area",'User Areas');
	while($q=mysqli_fetch_row($qid))
    $areas[]=$q;
	mysqli_free_result($qid);
}

// Save showweeks
if( (isset($userid) && ($mycal || $by) && form('pagecomplete')=='events'))
  sql("update Users set showweeks=$showweeks where userid=$userid",'Save showweeks');  /// missing $userid?

// Load Areas
if($regular || $mycal) {
  $areacodes=$qareas=$csa=array();
  $venueids=$areashtml='';
  $i=-1;
  foreach($areas as $k=>$csax) {
    list($countryid,$stateid,$area,$checked)=$csax;
		$areacodes[]=$a="$countryid:$stateid:$area";
		$countryid=clean($countryid);
		$stateid=clean($stateid);
		$area=clean($area);
    if($regular || $show) {
			list($ok,$aka)=listsql("select 1,aka from Areas where countryid='$countryid' && stateid='$stateid' && area='$area'");
      if(!$ok) {  // remove misspelled areas from bad links
        unset($areas[$k]);
        continue;
      }
    }
    $qareas[]="countryid='$countryid' && stateid='$stateid' && area=\"$area\"";
    if($mycal) continue;  // checkboxes not needed for mycal
    $checked=($checked)?'checked':'';
    if($aka) $area=$aka;
    $i++;
    $areashtml.="<input class=checkbox type=checkbox name=area$i value=1 onclick=\"update()\" $checked><span onclick=\"clickcheck('area$i')\"> $area</span><br>\n";
  }
  if(!$qareas && !$eventid) return require('home.php');  // login if no valid areas
  if($qareas) {
    $qareas=implode(' || ',$qareas);
    $qid=sql("select venueid,countryid,stateid,area from Venues where $qareas",'Venues in User Areas');
		while($q=mysqli_fetch_row($qid)) {
      list($venueid,$c,$s,$a)=$q;
      $venueids.="$venueid,";
      $csa[$venueid]="$c:$s:$a";
    }
		mysqli_free_result($qid);
    $venueids=substr($venueids,0,-1);
  }
}

if($regular)  // regular calendar
  $query=($venueids)?"venueid in($venueids)":false;
elseif($mycal) {  // my calendar
  $ids='';
  $qid=sql("select eventid from Favorites where userid=$userid",'Favorites');
	while($q=mysqli_fetch_row($qid))
    $ids.="$q[0],";
	mysqli_free_result($qid);
  $query=($ids)?'eventid in('.substr($ids,0,-1).')':false;
}
else  // host calendar
  $query="find_in_set(\"$by\",eventemail)";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
  <title>Tango Mango</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <script src="lib/script.js?2"></script>
  <link href="media/stylesheet.css?2" rel=stylesheet type=text/css>
  <link href="media/<?=platform()?>.css?1" rel=stylesheet type=text/css>
  <style>
    .printmargins {padding:0px 20px}
    .rel {position:relative}
  </style>
  <link href="media/print.css?1" rel=stylesheet type=text/css media=print>

<script type="text/javascript">
    litlink=false;
    ei=false;
    date='';

    function overcal(e,i) {
      if(e.className.substr(0,6)=='callit') return;
      if(favs[i]) e.className='cal favo';
      else if(especials[i]) e.className='cal specialo';
      else e.className='cal normo';
    }
    function outcal(e,i) {
      if(e.className.substr(0,6)=='callit') return;
      if(favs[i]) e.className='cal fav';
      else if(especials[i]) e.className='cal special';
      else e.className='cal norm';
    }

    function closeevent() {
      setstyle('eventpopup','hide');
      date='';
    }

    function loadevent(e,date1,eventid1,mouse) {
      if(autosave) {
        clearTimeout(autosave);  // don't let autosave interrupt event loading
        autosave=false;
        resave=true;  // flag to save after event loaded
      }
      if(e) ei=e.id.substr(1,99);  // needed for update
      if(date) setstyle('eventpopup','hide');
      date=date1;
      eventid=eventid1;
      <?php if($mycal): ?>
      if(litlink && favs[liti]) litlink.className='cal fav';
      <?php else: ?>
      if(litlink) {
        if(favs[liti]) litlink.className='cal fav';
        else if(especials[liti]) litlink.className='cal special';
        else litlink.className='cal norm';
      }
      <?php endif; ?>
      if(e && e.className!='hide') {  // hilite on calendar (unless hidden from pre-select)
        if(favs[ei]) e.className='callit fav';
        else if(especials[ei]) e.className='callit special';
        else e.className='callit norm';
      }
      litlink=e;
      liti=ei;
      window.dataframe.location='lib/loadevent.php?date='+date+'&eventid='+eventid;
      setspan('info','Loading...');
      if(mouse) {
        var it=document.getElementById('eventpopup');
        var s=(navigator.userAgent.indexOf('WebKit')!=-1)? document.body.scrollTop : document.documentElement.scrollTop;
        var w=document.documentElement.clientWidth;
        <?php if(isin('MSIE',$_SERVER['HTTP_USER_AGENT'])): ?>
        var x=mouse.x;
        <?php else: ?>
        var x=mouse.pageX;
        <?php endif; ?>
        it.style.top=(Math.max(120,s+20))+'px';
        if(x>w-300) {  // close to right edge
          it.style.right=(Math.max(20,w-x-160))+'px';
          it.style.left='';
        }
        else {
          it.style.left=(Math.max(20,x-160))+'px';
          it.style.right='';
        }
      }
      setstyle('eventpopup','');
    }

    function eventloaded() {  // uses innerHTML because form values change &lt; to < etc.
      setspan('info',window.dataframe.document.getElementById('info').innerHTML);
      shortdate=window.dataframe.document.getElementById('shortdate').innerHTML;
      datecount=window.dataframe.document.getElementById('datecount').innerHTML;
      editable= window.dataframe.document.getElementById('editable' ).innerHTML-0;
      setstyle('subtext','hide');
      if(resave) saveprefs();  // this was postponed for event to load
    }

    function changeweeks() {
      document.form1.target='_self';
      <?php if($regular): ?>
      document.form1.action='index.php';
      <?php elseif($mycal): ?>
      document.form1.action='mycal.php';
      <?php elseif($by): ?>
      document.form1.action=rep('+','@','index.php?by=<?=str_replace('@','+',$by)?>')+'&title=<?=urlencode($rawtitle)?>';
      <?php endif; ?>
      document.form1.submit();
    }

  </script>

</head>
<body>
<?php include_once("analyticstracking.php") ?>
<form name=form1 action="index.php" method=post onSubmit="return false">

  <!-- POP UP -->
  <div id=eventpopup class=hide style="position:absolute; top:120px; left:400px;">
    <table class="dark border" width=320 cellpadding=0 cellspacing=0>
      <tr>
        <td style="padding:12px 12px 12px 20px" width=320>
          <div id=info>Loading...</div>
          <div id=prompt></div>
        </td>
      </tr>
    </table>
  </div>

  <!-- two column layout -->
  <table width=100% height=100% border=0 cellpadding=0 cellspacing=0>
    <tr class=noprint>
      <td colspan=2 valign=top>

        <?php require("header.php"); ?>

      </td>
    </tr>
<!-- HORIZONTAL BANNER -->
<!--  <tr class=noprint>-->
<!--	    <td colspan="4">-->
<!--        <div style="text-align:center"><img src="http://via.placeholder.com/364x90?text=364x90+Horizontal+Banner 1"><img src="http://via.placeholder.com/364x90?text=364x90+Horizontal+Banner 2"></div>-->
<!--	    </td>-->
<!--	</tr>-->
<!-- /HORIZONTAL BANNER -->
    <tr valign=top>
      <td <?=($by)?'':'nowrap'?> width=1% height=100% class=noprint style="padding: 0px 0px 0px 20px">

        <!-- WEEKS -->
<?PHP
$selected = array();

function echoSelected($idx) {
  if (isset($selected[$idx]))
    return $selected[$idx];
  else
    return "";
}

error_log(print_r($selected,true));
logvar( [$showweeks=>'selected'] );
?>

        <select name=showweeks onChange="changeweeks()">
<?php $selected= [$showweeks=>'selected']; ?>
	<option value=-4 <?=echoSelected(-4) ?>>Past month
	<option value=2  <?=echoSelected(2)  ?>>2 weeks
	<option value=3  <?=echoSelected(3)  ?>>3 weeks
	<option value=4  <?=echoSelected(4)  ?>>4 weeks
	<option value=6  <?=echoSelected(6)  ?>>6 weeks
	<option value=9  <?=echoSelected(9)  ?>>2 months
	<option value=13 <?=echoSelected(13) ?>>3 months
	<option value=26 <?=echoSelected(26) ?>>6 months
        </select><br>

        <?php if($regular): ?>

          <!-- CATEGORIES -->
          <br>
          <div class=styled2>
            <input class=checkbox type=checkbox name=milonga     value=1 onClick="update()" <?php if($milonga) echo 'checked' ?>
            ><span onClick="clickcheck('milonga')">&nbsp;Milongas</span><br>
            <input class=checkbox type=checkbox name=dropin      value=1 onClick="update()" <?php if($dropin) echo 'checked' ?>
            ><span onClick="clickcheck('dropin')">&nbsp;Drop-in Classes</span><br>
            <input class=checkbox type=checkbox name=series      value=1 onClick="update()" <?php if($series) echo 'checked' ?>
            ><span onClick="clickcheck('series')">&nbsp;Series/Workshops</span><br>
            <input class=checkbox type=checkbox name=practica    value=1 onClick="update()" <?php if($practica) echo 'checked' ?>
            ><span onClick="clickcheck('practica')">&nbsp;Practicas</span><br>
            <input class=checkbox type=checkbox name=livemusic   value=1 onClick="update()" <?php if($livemusic) echo 'checked' ?>
            ><span onClick="clickcheck('livemusic')">&nbsp;Live Music</span><br>
            <input class=checkbox type=checkbox name=performance value=1 onClick="update()" <?php if($performance) echo 'checked' ?>
            ><span onClick="clickcheck('performance')">&nbsp;Performances</span><br>
            <input class=checkbox type=checkbox name=other       value=1 onClick="update()" <?php if($other) echo 'checked' ?>
            ><span onClick="clickcheck('other')">&nbsp;Other</span><br>
            <br>

            <!-- AREAS -->
            <?=$areashtml?>
            <input type=hidden name=areas value="<?=implode("\t",$areacodes)?>">
          </div>
          <br>
          <a href="choosearea.php">Other cities</a><br>
          <?php if(!$hacker): ?>
        <a href="editevent.php<?php if(request('show')) echo str_replace(' ','+','?show='.request('show').'&hide='.request('hide')) ?>">Add a new event</a><br><br>
          <?php endif; ?>

          <!-- /VERTICAL BANNER -->
          <div style="text-align: left;"><a href="<?=$urlroot?>/donations.php"><img src="<?=$urlroot?>/media/paypal_btn_donateCC_LG.webp" alt="Donations"/></a></div>
        <?php elseif($by): ?>
<!-- SEARCH BY HOST -->
          <br>
          <div class=styled><?=$title?></div>
          <br>
          <br>
          <input class=button type=button value="Show All Events" onClick="location='index.php'"><br>
        <?php endif; ?>
      </td>
      <td width=99% class=printmargins>

        <!-- CALENDAR -->
        <table width=100% border=0 cellpadding=0 cellspacing=0>
          <tr valign=bottom>
            <td nowrap width=14% height=22 class="colorbar styled">Sun</td>
            <td nowrap width=14% height=22 class="colorbar styled">Mon</td>
            <td nowrap width=14% height=22 class="colorbar styled">Tue</td>
            <td nowrap width=14% height=22 class="colorbar styled">Wed</td>
            <td nowrap width=14% height=22 class="colorbar styled">Thu</td>
            <td nowrap width=14% height=22 class="colorbar styled">Fri</td>
            <td nowrap width=14% height=22 class="colorbar styled">Sat</td>
          </tr>
          <?php
          // Load Events
          $timestamp=strtotime('-3 hours');  // don't switch dates till 3AM Pacific time
          $today=date('Y-m-d',$timestamp);
          $day=date('w',$timestamp);  // 0-6 = Sun-Sat
          if($showweeks<0) {  // show past
            $day-=$showweeks*7;  // start n weeks earlier
            $showweeks=1-$showweeks;  // end this week
          }
          $startstamp=strtotime("-$day days")-3*60*60;  // go back to Sunday this week (bug: off one day between 3-4AM for week after daylight saving in spring)
          $firstdate=date('Y-m-d',$startstamp);
          $lastdate=date('Y-m-d',strtotime('+'.($showweeks*7-1).' days',$startstamp));
//date<='$lastdate' && date>='$firstdate'
$events = array();
// Generate the date index dates.
$theDay=0;
while ($theDay <= ($showweeks*7-1)) {
  $theDate = date('Y-m-d',strtotime('+'.($theDay).' days',$startstamp));
  $events[$theDate] = '';
  $theDay++;
}

          $etypes=$eareas=$especials=$eventids=$favs='';
          $style=($mycal)?'"cal fav"':'hide';
          $i=1;
$favorites=array();
          if($query) {
            $favorites=array();
            if($userid) {
              $qid=sql("select eventid,date from Favorites where userid=$userid",'Favorites');
		while($q=mysqli_fetch_row($qid)) {
                list($e,$d)=$q;
                $favorites[$e][$d]=1;
              }
		mysqli_free_result($qid);
            }
	$qid=sql("select date,dayofweek(date),status,eventid,venueid,title,milonga,dropin,series,practica,livemusic,performance,other,`virtual` from Events where $query && date<='$lastdate' && date>='$firstdate' ".
              "order by date,-(status='hilite'),-livemusic,-performance,-(milonga && !practica),-milonga,-practica,-series,-dropin,eventid",'Events');
	if (!is_null($qid)) {
	  while($q=mysqli_fetch_row($qid)) {
		list($date,$day,$status,$eventid,$venueid,$title,$mi,$dr,$se,$pr,$li,$pe,$ot,$vi)=$q;
    $hide_paused = true;
    $hide_virtual= !$vi || !$virtual;
		if (  hide_paused($title,$hide_paused) || hide_virtual($title, $hide_virtual)) {
		  continue;
    }
		if($mycal && isset($favorites[$eventid][$date])) continue;
              $title=str_replace('@','<br>@',$title);
              $events[$date].="<div id=e$i class=$style onclick=\"loadevent(this,'$date',$eventid,event)\" onmouseover=\"overcal(this,$i)\" onmouseout=\"outcal(this,$i)\">$title</div>\n";
              $eventids.=','.$eventid;
              $especials.=','.(($status=='hilite')?1:0);
    if (isset($favorites[$eventid][$date])) $favs.=','.((int)$favorites[$eventid][$date]);
              if($regular) {
			$etypes.=",'$mi$dr$se$pr$li$pe$ot$vi'";
                $eareas.=','.array_search($csa[$venueid],$areacodes);  // 0,1,2...
              }
              if($date==$date1 && $eventid==$eventid1) $e1="document.getElementById('e$i')";  // remember pre-selected event
              $i++;
            }
	  mysqli_free_result($qid);
	}
          }
          $totalitems=$i-1;

          // Draw Calendar
          $i=0;
          for($weekrow=1;$weekrow<=$showweeks;$weekrow++) {
            $html='';
            echo "
	<tr valign=top>";
            for($daycol=1;$daycol<=7;$daycol++) {
              $datestamp=strtotime("+$i days",$startstamp);
              $date=date('Y-m-d',$datestamp);
              $m=date('n',$datestamp);
              $d=date('j',$datestamp);
		$m=($i==0 || $d==1 || (($showweeks>6) && ($daycol==1)))?
                "$m/":'';  // show month on first day on calendar or the 1st, plus every Sunday if > 6 weeks
              $style=($date==$today)?'"cal calglow"':'cal';  // hilite today
              echo "
		<td class=$style height=60>
			<div class=\"styled caldate\">$m$d</div>
$events[$date]
		</td>";
              $i++;
            }
            echo "
	</tr>
	<tr>$html</tr>
";
          }
          ?>
        </table>

        <!-- SUB TEXT -->
        <div id=subtext class=<?=($date1)?'hide':'noprint'?> style="padding-top:4px;padding-bottom:4px;text-align:center">
          <?php if($events && $regular && !$hacker): ?>
            Click any event for info. Some event organizers don't post their own events,<br>so if anything is missing, please <a href="editevent.php">post it</a> for them.
          <?php elseif(!$events && $regular && !$hacker): ?>
            Nothing found in your area. Help get your community started<br>by <a href="editevent.php">posting events</a> yourself!
          <?php elseif(!$events && $mycal): ?>
            This is your calendar to save your favorite events. Select an event on the <a href="index.php">Events</a> page, then click the "Highlight" link.
          <?php elseif(!$events && $by && !$hacker): ?>
            Nothing found. Some event organizers don't post their own events,<br>so if anything is missing, please <a href="editevent.php">post it</a> for them.
          <?php endif; ?>
        <div nowrap colspan=2 valign=bottom class=noprint style="text-align:center">
          <input type=hidden name=pagecomplete value=events>
          <iframe name=dataframe width=0 height=0 frameborder=0></iframe>
          <br>
          <br>
          <?php require('footer.php'); ?>
        </div>
        </div>

      </td>
    </tr>
  </table><!-- two column layout -->
</form>
</body>
</html>

<script>
  autosave=false;
  resave=false;

  eventids=new Array(''<?=$eventids?>);
  especials=new Array(''<?=$especials?>);
  favs=new Array(''<?=$favs?>);
  totalitems=<?=(int)$totalitems?>;

  <?php if($regular):  // not needed otherwise ?>
  etypes=new Array(''<?=$etypes?>);
  eareas=new Array(''<?=$eareas?>);
  update('init');

  function update(init) {
    // read area selections
    fareas=new Array();
    for(a=0;a<<?=count($areas)?>;a++)
      fareas[a]=checked('area'+a);
    // read category selections
    ftypes=new Array();
    if(checked('milonga'))     ftypes[ftypes.length]=0;
    if(checked('dropin'))      ftypes[ftypes.length]=1;
    if(checked('series'))      ftypes[ftypes.length]=2;
    if(checked('practica'))    ftypes[ftypes.length]=3;
    if(checked('livemusic'))   ftypes[ftypes.length]=4;
    if(checked('performance')) ftypes[ftypes.length]=5;
    if(checked('other'))       ftypes[ftypes.length]=6;
    fmax=ftypes.length-1;
    // each event
    for(i=1;i<=totalitems;i++) {
      style='hide';
      if(fareas[eareas[i]]) {  // skip if area not selected
        for(f=0;f<=fmax;f++) {
          if(etypes[i].charAt(ftypes[f])=='0') continue;  // leave hidden until find match
          style=(i==ei)?'callit ':'cal ';
          if(favs[i]) style+='fav';
          else if(especials[i]) style+='special';
          else style+='norm';
          break;  // only need one match
        }
      }
      setstyle('e'+i,style);
    }
    if(!init) {  // save prefs
      if(autosave) clearTimeout(autosave);
      autosave=setTimeout('saveprefs()',4000);  // auto-save prefs after idle 4 secs
    }
  }

  function saveprefs() {
    document.form1.target='dataframe';
    document.form1.action='lib/saveprefs.php';
    document.form1.submit();
    resave=false;
  }

  <?php elseif($mycal):  // MY CAL ?>

  function update() {
    for(i=1;i<=totalitems;i++) {
      if(!favs[i]) style='hide';
      else style=(i==ei)?'callit fav':'cal fav';
      setstyle('e'+i,style);
    }
  }

  <?php elseif($by):  // BY ?>

  update();
  function update() {
    for(i=1;i<=totalitems;i++) {
      style=(i==ei)?'callit ':'cal ';
      if(favs[i]) style+='fav';
      else if(especials[i]) style+='special';
      else style+='norm';
      setstyle('e'+i,style);
    }
  }

  <?php endif; ?>


  // HILITE FAVORITES

  function addfav(one) {  // one for specific date, empty for all dates
    var x=(favs[ei])?0:1;
    if(one)
      favs[ei]=x;
    else {
      for(i=1;i<=totalitems;i++) {
        if(eventids[i]==eventid) favs[i]=x;
      }
    }
    setspan('favlink1',(x)?'Un-highlight this date':'Highlight this date');
    setspan('favlink2',(x)?'Un-highlight all dates':'Highlight all dates');
    update(true);
    var d=(one)?date:'';
    window.dataframe.location='lib/savefavorites.php?eventid='+eventid+'&date='+d+'&set='+x;
  }


  // LINK TO EVENT

  function linktoevent() {
    location='link.php?eventid='+eventid+'&date='+date;
  }


  // PROMPT

  function openprompt() {
    <?php if(!$useremail): ?>
    location='login.php?prompt=update';
    <?php else: ?>
    if(datecount>1) var html=
      '<a href="editevent.php?eventid='+eventid+'&date='+date+'&do=editall">Edit listing</a><br>'+
      '<a href="editevent.php?eventid='+eventid+'&date='+date+'&do=editone">Edit listing for '+shortdate+' only</a><br>'+
      '<a href="javascript:promptdelete(0)">Delete listing</a><br>'+
      '<a href="javascript:promptdelete(1)">Delete from '+shortdate+' only</a><br>';
    else var html=
      '<a href="editevent.php?eventid='+eventid+'&date='+date+'&do=editall">Edit listing</a><br>'+
      '<a href="javascript:promptdelete(0)">Delete listing</a><br>';
    html+='<br>'+
      '<a href="javascript:cancelprompt()">Cancel</a>';
    setstyle('details','hide');
    setspan('prompt',html);
    <?php endif; ?>
  }

  function promptdelete(one) {
    if(!editable) confirmprompt(
      "Sorry, <?=$useremail?> is not allowed to delete this event. You must be listed as one of the organizers for this event to delete it.",'','');
    else if(one) confirmprompt(
      'Are you sure you want to delete this date from the public calendar?','Delete','okdelete(1)');
    else if(datecount>1) confirmprompt(
      'Are you absolutely, positively certain beyond a reasonable doubt that you want to permanently delete this event from all dates from the public calendar, or forever hold your peace?','Delete All','okdelete(0)');
    else confirmprompt(
        'Are you absolutely, positively certain beyond a reasonable doubt that you want to permanently delete this event from the public calendar, or forever hold your peace?','Delete','okdelete(0)');
  }

  function confirmprompt(html,btn,action) {
    html+='<br><br>'+
      '<input class=button type=button value="Cancel" onClick="cancelprompt()">';
    if(btn) html+='&nbsp; '+
      '<input class=button type=button value="'+btn+'" onClick="'+action+'">';
    setstyle('details','hide');
    setspan('prompt',html);
  }

  function okdelete(one) {
    if(one) location='lib/deleteevent.php?eventid='+eventid+'&date='+date;
    else    location='lib/deleteevent.php?eventid='+eventid;
  }

  function cancelprompt() {
    setstyle('details','');
    setspan('prompt','');
  }

  <?php  // select event after saving
  if($date1) {
    $day=date('w',strtotime($date1))+1;
    echo "setTimeout(\"loadevent($e1,'$date1',$eventid1)\",1);";  // the delay helps, otherwise loadevent fails randomly
  }
  ?>

</script>


<?php postcode(); ?>
