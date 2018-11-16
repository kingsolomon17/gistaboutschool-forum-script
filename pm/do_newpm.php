<?php
require('../incfiles/init.php');
if(!functions::isloggedin())
{
functions::go("/login?redirect=$self");
die();
}

if($_POST["submit"])
{

$to = functions::cleaninput($_POST["receiver"]);
$num = mysql_num_rows(mysql_query("SELECT `username` FROM `users` WHERE `username`='$to'"));

if($num != 1)
{
$pagetitle = "User Not Found";
require('../incfiles/head.php');
functions::display_error('User Not found');
require('../incfiles/end.php');
die();
}

if($sessionkey != $_POST["session"])
{
$pagetitle = "Unexpected Error Occured Please Try Again Later";
require('../incfiles/head.php');
functions::display_error('Unexpected Error Occured Please Try Again Later');
require('../incfiles/end.php');
die();

}


$msg = functions::cleaninput($_POST["body"]);
$date = time();
if(strlen($msg) < 5)
{
$pagetitle = "Your Message Must Be More Than 5";
require('../incfiles/head.php');
functions::display_error('Your Message Must Be More Than 5');
require('../incfiles/end.php');
die();
}


$pm = mysql_query("INSERT INTO `pms` SET `to`='$to', `from`='$user', `date`='$date', `message`='$msg'");

if($pm)
{

if(isset($_POST["redirect"]))
{
$rdr = $_POST['redirect'];
header("location: http://$rdr");
exit();
} else {
header("location: /pm/".$to."");
exit();
}

} else {
$pagetitle = "Error Occured Pls Try Again Later";
require('../incfiles/head.php');
functions::display_error('Error Occured Pls Try Again Later');
require('../incfiles/end.php');
die();
}

} else {
$pagetitle = "Error Occured Please Try Again Later";
require('../incfiles/head.php');
functions::display_error('Error Occured Please Try Again Later');
require('../incfiles/end.php');
die();
}

?>