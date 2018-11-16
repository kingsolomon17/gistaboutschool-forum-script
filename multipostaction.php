<?php
require('incfiles/init.php');
require('panel/access.php');
$redirect =$_POST["redirect"];

if($_POST["delete"])
{

$tid = (int)$_POST["topicid"];
If(isset($tid))
{
$check = mysql_num_rows(mysql_query("SELECT * FROM topics WHERE id=$tid"));
if($check != 1)
{
$pagetitle = "Topic Not Found";
require('incfiles/head.php');
functions::display_error('Topic Not Found');
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

If(isset($tid))
{
$info = mysql_fetch_array(mysql_query("SELECT * FROM topics WHERE id=$tid"));
$bid = $info["boardid"];   
$title = functions::cleanoutput($info["subject"]);


$modcheck = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND boardid = '$bid'"));

}

$admincheck = mysql_num_rows(mysql_query("SELECT * FROM admins WHERE username = '$user'"));

$modcheck2 = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND type = 'super'"));


if($admincheck < 1 && $modcheck2 < 1 && $modcheck < 1)
{
functions::display_error('Insufficient Permission');
require('incfiles/end.php');
die();
}
$checkbox = $_REQUEST["multi"];
foreach($checkbox as $check)
{
$del = $check;
$pinfo = mysql_fetch_array(mysql_query("SELECT * FROM posts WHERE id=$del"));
$position = $pinfo["position"];
$ptid = $pinfo["topicid"];
mysql_query("DELETE FROM posts WHERE id=$del");

mysql_query("UPDATE posts SET position=position-1 WHERE topicid='$ptid' AND id>'$del'");

$atquery = mysql_query("SELECT `url` FROM `attachment` WHERE `postid`=$del");
while($atinfo = mysql_fetch_array($atquery))
{
$aturl = $atinfo["url"];
if(file_exists($config->attachmentFolder.$aturl))
{
unlink($config->attachmentFolder.$aturl);
}
}
mysql_query("DELETE FROM `attachment` WHERE `postid`=$del");

}
if(empty($redirect))
{
header("location: ".urls::topic($title, $tid)."");
exit();
} else {
header("location: http://$redirect");
exit();
}
}

elseif($_POST["hide"])
{
$tid = (int)$_POST["topicid"];
If(isset($tid))
{
$check = mysql_num_rows(mysql_query("SELECT * FROM topics WHERE id=$tid"));
if($check != 1)
{
$pagetitle = "Topic Not Found";
require('incfiles/head.php');
functions::display_error('Topic Not Found');
require('incfiles/end.php');
die();
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
If(isset($tid))
{
$info = mysql_fetch_array(mysql_query("SELECT * FROM topics WHERE id=$tid"));
$bid = $info["boardid"];   
$title = functions::cleanoutput($info["subject"]);


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
$hide = $check;
$r = mysql_query("UPDATE posts SET hide='1'  WHERE id ='$hide'");
}

if(empty($redirect))
{
header("location: ".urls::topic($title, $tid)."");
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