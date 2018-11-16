<?php
require('incfiles/init.php');

if(!functions::isloggedin())
{
$pagetitle = "You Must Be LoggedIn To Proceed";
require('incfiles/head.php');
functions::display_error('You Must Be LoggedIn To Proceed');
require('incfiles/end.php');
die();
}

$sessionkey = $_COOKIE["sessionkey"];
if(isset($_GET["member"]) && isset($_GET["session"]) && $_GET["session"] == $sessionkey)
{

$id = functions::cleaninput($_GET["member"]);

$check = mysql_num_rows(mysql_query("SELECT * FROM users WHERE userID=$id"));
if($check != 1)
{
$pagetitle = "Member Not Found";
require('incfiles/head.php');
functions::display_error('Member Not Found');
require('incfiles/end.php');
die();
}
require('incfiles/head.php');

$user2 = functions::user_info2($id, 'username'); 

$check2 = mysql_num_rows(mysql_query("SELECT * FROM usersfollow WHERE follower='$user' AND following='$user2'"));


if($check2 < 1)
{

functions::display_error('You Cant Unfollow member You Have Not Follow');
require('incfiles/end.php');
die();
}
$date = time();
$query = mysql_query("DELETE FROM usersfollow WHERE follower='$user' AND following='$user2'");


if(!$query)
{
$msg="An error occured";
functions::display_error($msg);
require('incfiles/end.php');
die();
}

if(isset($_GET["redirect"]))
{
$rdr = $_GET['redirect'];
header("location: http://$rdr");
exit();
} else {
header("location: ".urls::user($user2)."");
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