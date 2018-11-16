<?php
require('incfiles/init.php');
$sessionkey = $_COOKIE["sessionkey"];
if(!isset($_GET["board"]) || !isset($_GET["action"]))
{
$pagetitle = "Invalid Board Or Action";
require('incfiles/head.php');
functions::display_error('Invalid Board Or Action');
require('incfiles/end.php');
die();
}

$bid = $_GET["board"];
$action = $_GET["action"];
$check = mysql_num_rows(mysql_query("SELECT * FROM boards WHERE id=$bid"));
if($check != 1)
{
$pagetitle = "Board Not Found";
require('incfiles/head.php');
functions::display_error('Board Not Found');
require('incfiles/end.php');
die();
}


if(!functions::isloggedin())
{
$pagetitle = "You Must Be LoggedIn To Proceed";
require('incfiles/head.php');
functions::display_error('You Must Be LoggedIn To Proceed');
require('incfiles/end.php');
die();
}


$user = $_SESSION["user"];

$binfo = mysql_fetch_array(mysql_query("SELECT * FROM boards WHERE id=$bid"));
$bname = $binfo["name"];


$modcheck = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND boardid = '$bid'"));

$admincheck = mysql_num_rows(mysql_query("SELECT * FROM admins WHERE username = '$user'"));


if($admincheck < 1 && $modcheck < 1)
{
$pagetitle = "Insufficient Permission";
require('incfiles/head.php');
functions::display_error('Insufficient Permission');
require('incfiles/end.php');
die();
}

if($action=="lock")
{
if($_GET["session"] == $sessionkey)
{
mysql_query("UPDATE boards SET locked=1 WHERE id=$bid");
}
header("location: ".urls::board($bname, $bid)."");
exit();
}



if($action=="unlock")
{
if($_GET["session"] == $sessionkey)
{
mysql_query("UPDATE boards SET locked=0 WHERE id=$bid");
}
header("location: ".urls::board($bname, $bid)."");
exit();
}


?>