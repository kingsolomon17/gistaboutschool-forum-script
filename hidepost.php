<?php
require('incfiles/init.php');
require('panel/access.php');
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


$modcheck = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND boardid = '$bid'"));

$admincheck = mysql_num_rows(mysql_query("SELECT * FROM admins WHERE username = '$user'"));

$modcheck2 = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND type = 'super'"));


if($admincheck < 1 && $modcheck < 1 && $modcheck2 < 1)
{
functions::display_error('Insufficient Permission');
require('incfiles/end.php');
die();
}

if($action == 'unhide')
{
$query = mysql_query("UPDATE posts SET hide='0'  WHERE id ='$pid'");
} else {
$query = mysql_query("UPDATE posts SET hide='1'  WHERE id ='$pid'");
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