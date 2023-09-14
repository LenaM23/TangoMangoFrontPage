<?php
require_once("lib/functions.php");
loaduser('useremail');
?>
<html>
<head>
  <title>Donations to TangoMango</title>
  <link href="media/stylesheet.css?2" rel=stylesheet type=text/css>
  <link href="media/<?= platform() ?>.css?1" rel=stylesheet type=text/css>
</head>
<body>
<?php include_once("analyticstracking.php") ?>
<table width=100% height=100% border=0 cellpadding=0 cellspacing=0>
  <tr>
    <td valign=top colspan=3>
      <?php require("header.php"); ?>
    </td>
  </tr>
  <tr valign=top>
    <td style="padding:0px 20px"><input class="button spacer" type=button value="Back"></td>
    <td height=100% align=center>

      <table style="width:24em" border=0 cellpadding=0 cellspacing=0> <tr> <td style="text-align:justify"> <br> <span
              class=styled>TangoMango</span> is a free community calendar service of the <span class=styled>Bay Area Argentine
	Tango Association</span> dancers to find information about milongas, classes, and related events.
            </br></br>

            To   support  <span  class=styled>TangoMango</span>   you  can   donate  via   PayPal  to   the  <span
              class=styled>Bay Area Argentine Tango Association</span>, a 501(c)3 non-profit.
            </br></br>

            <div style="text-align: center;">
              <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="L863T3YBMKM7Q">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
              </form>
            </div>

            <hr/>

          </td> </tr> </table>

    </td>
    <td align=right style="padding:0px 20px">
      <input class=button type=button value="Back" onClick="location='index.php'">
    </td>
  </tr>
  <tr>
    <td colspan=3 valign=bottom>
      <br>
      <br>
      <?php require('footer.php'); ?>
    </td>
  </tr>
</table>
</body>
</html>


<?php postcode(); ?>
