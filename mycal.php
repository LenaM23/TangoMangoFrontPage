<?php /** @noinspection PhpRedundantClosingTagInspection */
require_once("lib/functions.php");
loaduser('*');
if(!$userid) return header("Location: index.php");  // redirect bad link
$mycal=true;
require('calendar.php');
?>
