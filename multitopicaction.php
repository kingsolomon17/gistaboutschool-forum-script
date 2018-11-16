<?php
require('incfiles/init.php');
require('panel/access.php');
$redirect =$_POST["redirect"];

if($_POST["delete"])
{

$bid = (int)$_POST["boardid"];
If(isset($bid))
{
$check = mysql_num_rows(mysql_query("SELECT * FROM boards WHERE id=$bid"));
if($check != 1)
{
$pagetitle = "Board Not Found";
require('incfiles/head.php');
functions::display_error('board Not Found');
require('incfiles/end.php');
}
}



if(!functions::isloggedin())
{
$pagetitle = "You Must Be LoggedIn To Proceed";
require('incfiles/head.php');
functions::display_error('You Must Be LoggedIn To Proceed');
require('incfiles/end.php');
die();
}



require('incfiles/head.php');

If(isset($bid))
{
$info = mysql_fetch_array(mysql_query("SELECT * FROM boards WHERE id=$bid"));
$bname = functions::cleanoutput($info["name"]);


$modcheck = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND boardid = '$bid'"));
}


$admincheck = mysql_num_rows(mysql_query("SELECT * FROM admins WHERE username = '$user'"));

$modcheck2 = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND type = 'super'"));


if($admincheck < 1 && $modcheck < 1 && $modcheck2 < 1)
{
functions::display_error('Insufficient Permission');
require('incfiles/end.php');
die();
}
$checkbox = $_REQUEST["multi"];
foreach($checkbox as $check)
{
$del = $check;
$dquery = mysql_query("DELETE FROM topics WHERE id=$del");
if($dquery)
{

$atquery = mysql_query("SELECT `url` FROM `attachment` WHERE `topicid`=$del");
while($atinfo = mysql_fetch_array($atquery))
{
$aturl = $atinfo["url"];
if(file_exists($config->attachmentFolder.$aturl))
{
unlink($config->attachmentFolder.$aturl);
}
}
mysql_query("DELETE FROM `attachment` WHERE `topicid`=$del");

mysql_query("DELETE FROM posts WHERE topicid=$del");
mysql_query("DELETE FROM follow WHERE type='topic' AND itemid=$del");
}

}
if(empty($redirect))
{
header("location: ".urls::board($bname, $bid)."");
exit();
} else {
header("location: http://$redirect");
exit();
}
}
   
else {
$pagetitle = "Error";
require('incfiles/head.php');
functions::display_error('Error');
require('incfiles/end.php');
die();

}


?>