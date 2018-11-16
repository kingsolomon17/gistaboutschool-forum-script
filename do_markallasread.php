<?php
require('incfiles/init.php');

if(!functions::isloggedin())
{
require('incfiles/head.php');
functions::display_error('You Must Be LoggedIn To Proceed');
require('incfiles/end.php');
die();
}

$sessionkey = $_COOKIE["sessionkey"];
if(isset($_GET["session"]) && $_GET["session"] == $sessionkey)
{

mysql_query("UPDATE follow SET hasread='0' WHERE follower='$user' AND type='topic'");

if(isset($_GET["redirect"]))
{
$rdr = $_GET['redirect'];
header("location: http://$rdr");
exit();
} else {
header("location: ".urls::$type($title, $tid)."");
exit();
}

} else {
$pagetitle = "Error";
require('incfiles/head.php');
functions::display_error('Error');
require('incfiles/end.php');
die();
}
?>