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
if(isset($_GET["itemid"]) && isset($_GET["session"]) && $_GET["session"] == $sessionkey && isset($_GET["action"]) && isset($_GET["type"]))
{




$type = $_GET["type"];
$types = $type . 's';

$tid = $_GET["itemid"];
$action = $_GET["action"];
$check = mysql_num_rows(mysql_query("SELECT * FROM $types WHERE id=$tid"));
if($check != 1)
{
$pagetitle = "$type Not Found";
require('incfiles/head.php');
functions::display_error('Post Not Found');
require('incfiles/end.php');
die();
}
else
{
require('incfiles/head.php');

$types = $type . 's';
$info = mysql_fetch_array(mysql_query("SELECT * FROM $types WHERE id=$tid"));


$bid = $info["boardid"];   
$title = functions::cleanoutput($info["subject"]);


if($action == 'unfollow')
{

$query2 = mysql_num_rows(mysql_query("SELECT * FROM follow WHERE follower='$user' AND itemid=$tid AND type='$type'"));

if($query2 < 1)
{
functions::display_error('You Cant Unfollowed $type You Have Not Followed');
require('incfiles/end.php');
die();
}

$query = mysql_query("DELETE FROM follow WHERE follower='$user' AND itemid=$tid AND type='$type'");


} else {

$query2 = mysql_num_rows(mysql_query("SELECT * FROM follow WHERE follower='$user' AND itemid=$tid AND type='$type'"));

if($query2 > 0)
{
functions::display_error('You Cant Follow $type Twice');
require('incfiles/end.php');
die();
}

$query = mysql_query("INSERT INTO follow SET follower='$user', date='$date', itemid='$tid', type='$type'");

}

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
header("location: ".urls::$type($title, $tid)."");
exit();
}

}



} else {
$pagetitle = "Error";
require('incfiles/head.php');
functions::display_error('Error');
require('incfiles/end.php');
die();
}
?>