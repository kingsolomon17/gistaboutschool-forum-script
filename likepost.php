<?php
require('incfiles/init.php');

if(!functions::isloggedin())
{
require('incfiles/head.php');
functions::display_error('You Must Be LoggedIn To Like Post');
require('incfiles/end.php');
die();
}

$sessionkey = $_COOKIE["sessionkey"];
if(isset($_GET["post"]) && isset($_GET["session"]) && $_GET["session"] == $sessionkey && isset($_GET["action"]))
{
$pid = $_GET["post"];
$action = $_GET["action"];
$check = mysql_num_rows(mysql_query("SELECT * FROM posts WHERE id=$pid"));
if($check != 1)
{
$pagetitle = "Post Not Found";
require('incfiles/head.php');
functions::display_error('Post Not Found');
require('incfiles/end.php');
die();
}
else
{
require('incfiles/head.php');
$pinfo = mysql_fetch_array(mysql_query("SELECT * FROM posts WHERE id=$pid"));
$tid = $pinfo["topicid"];
$info = mysql_fetch_array(mysql_query("SELECT * FROM topics WHERE id=$tid"));
$bid = $info["boardid"];   
$title = functions::cleanoutput($info["subject"]);


if($action == 'unlike')
{

$query2 = mysql_num_rows(mysql_query("SELECT * FROM likepost WHERE liker='$user' AND postid=$pid"));

if($query2 < 1)
{
functions::display_error('You Cant Unlike Post You Have Not Liked');
require('incfiles/end.php');
die();
}

$query = mysql_query("DELETE FROM likepost WHERE liker='$user' AND postid=$pid");


} else {

$query2 = mysql_num_rows(mysql_query("SELECT * FROM likepost WHERE liker='$user' AND postid=$pid"));

if($query2 > 0)
{
functions::display_error('You Cant Like Post Twice');
require('incfiles/end.php');
die();
}

$user_name = $_SESSION['user'] ;
$sql = mysql_query("UPDATE users SET points=points+0.50 where username='{$user_name}'");
if(!sql) 
{
$pagetitle = "An error occured";
require('incfiles/head.php');
functions::display_error("An error occured");
require('incfiles/end.php');
die();
}

$query = mysql_query("INSERT INTO likepost SET liker='$user', date='$date', postid='$pid'");

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
header("location: ".urls::topic($title, $tid)."");
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