<?php
require('incfiles/init.php');
require('panel/access.php');
if(!isset($_GET["topic"]) || !isset($_GET["action"]))
{
$pagetitle = "Invalid Topic Or Action";
require('incfiles/head.php');
functions::display_error('Invalid Topic Or Action');
require('incfiles/end.php');
die();
}

$tid = (int)$_GET["topic"];
$action = $_GET["action"];
$check = mysql_num_rows(mysql_query("SELECT * FROM topics WHERE id=$tid"));
if($check != 1)
{
$pagetitle = "Topic Not Found";
require('incfiles/head.php');
functions::display_error('Topic Not Found');
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

$info = mysql_fetch_array(mysql_query("SELECT * FROM topics WHERE id=$tid"));
$bid = $info["boardid"];   
$binfo = mysql_fetch_array(mysql_query("SELECT * FROM boards WHERE id=$bid"));
$bname = $binfo["name"];

$title = functions::cleanoutput($info["subject"]);

$modcheck = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND boardid = '$bid'"));

$admincheck = mysql_num_rows(mysql_query("SELECT * FROM admins WHERE username = '$user'"));
$modcheck2 = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND type = 'super'"));


if($admincheck < 1 && $modcheck < 1 && $modcheck2 < 1)
{
$pagetitle = "Insufficient Permission";
require('incfiles/head.php');
functions::display_error('Insufficient Permission');
require('incfiles/end.php');
die();
}



if($action==deletetopic)
{
if(isset($_POST["submit"]))
{
$verify = $_POST["verify"];
if($verify == yes)
{
$del = mysql_query("DELETE FROM topics WHERE id=$tid");
if($del)
{

$atquery = mysql_query("SELECT `url` FROM `attachment` WHERE `topicid`=$tid");
while($atinfo = mysql_fetch_array($atquery))
{
$aturl = $atinfo["url"];
if(file_exists($config->attachmentFolder.$aturl))
{
unlink($config->attachmentFolder.$aturl);
}
}
mysql_query("DELETE FROM `attachment` WHERE `topicid`=$tid");

mysql_query("DELETE FROM posts WHERE topicid=$tid");
mysql_query("DELETE FROM follow WHERE type='topic' AND itemid=$tid");
}
header("location: ".urls::board($bname, $bid)."");
exit();
} else {
header("location: ".urls::topic($title, $tid)."");
exit();
}
} else {
$pagetitle = "Delete Topic";
require('incfiles/head.php');
echo "<p><h2>Are You Sure You Want To Delete This Topic?</h2><p><div class='display'><form action='#' method='POST'>
<input type='text' name='verify' value='yes'><br><input type='submit' name='submit' class='button' value='Delete'></form></div>";

require('incfiles/end.php');
}  
die();
}




if($action == move)
{
if(isset($_POST["submit"]))
{
$id = (int)$_POST["id"];
mysql_query("UPDATE topics SET boardid='$id' WHERE id=$tid") or die(mysql_error());

header("location: ".urls::topic($title, $tid)."");
exit();

} else {

$pagetitle = "Move Topic";
require('incfiles/head.php');
echo "<p><h2>Are You Sure You Want To Move Topic?</h2><p><div class='display'><form action='#' method='POST'>
Move to....<br/><select name='id'>";
$qquery=mysql_query("SELECT * FROM boards");
while($row = mysql_fetch_assoc($qquery))
{
$cname = $row["name"];
$id = $row["id"];
echo "<option value='$id'>$cname</option>";
}
echo "</select><br><input type='submit' name='submit' class='button' value='Move'></form></div>";

require('incfiles/end.php');
}
die();
}





if($action=="lock")
{
if($_GET["session"] == $sessionkey)
{
mysql_query("UPDATE topics SET locked=1 WHERE id=$tid");
}
header("location: ".urls::topic($title, $tid)."");
exit();
}



if($action=="unlock")
{
if($_GET["session"] == $sessionkey)
{
mysql_query("UPDATE topics SET locked=0 WHERE id=$tid");
}
header("location: ".urls::topic($title, $tid)."");
exit();
}



if($action==tag)
{
$query3 = mysql_num_rows(mysql_query("SELECT * FROM updates WHERE topicid=$tid"));
if($query3 < 1)
{
if(isset($_POST["submit"]))
{

$title = functions::cleaninput($_POST["title"]);
$url = functions::cleaninput($_POST["url"]);
if(strlen($title) < 5)
{
$pagetitle = "Title Too Short";
require('../incfiles/head.php');
functions::display_error('Title Too Short');
require('../incfiles/end.php');
die();
}

if(strlen($url) < 3)
{
$pagetitle = "Url Too Short";
require('../incfiles/head.php');
functions::display_error('Url Too Short');
require('../incfiles/end.php');
die();
}

$date = time();
$tag = mysql_query("INSERT INTO `updates` SET `tagger`='$user', `title`='$title', `url`='$url', `date`='$date', topicid='$tid'");

if($tag)
{
header("location: /");
exit();
} else {
header("location: ".urls::topic($title, $tid)."");
exit();
}


} else {
$pagetitle = "Tag Topic To Front Page";
require('incfiles/head.php');
echo "<p><h2>Tag Topic To Front Page</h2><p><div class='display'><form action='#' method='POST'>
<b>Title:</b><p><input type='text' name='title' value='$title'><br>
<b>Url:</b><p><input type='text' name='url' value='".urls::topic($title, $tid)."'><br><br><input type='submit' name='submit' class='button' value='add'></form></div>";

require('incfiles/end.php');
}
}  else {
$pagetitle = "Topic Already Taged";
require('incfiles/head.php');
functions::display_error('Topic Already Taged');
require('incfiles/end.php');
die();
}
die();
}



if($action == untag)
{
$query3 = mysql_num_rows(mysql_query("SELECT * FROM updates WHERE topicid=$tid"));
if($query3 > 0)
{
if(isset($_POST["submit"]))
{
$verify = $_POST["verify"];
if($verify == yes)
{
mysql_query("DELETE FROM updates WHERE topicid=$tid");
header("location: ".urls::topic($title, $tid)."");
exit();
} else {
header("location: ".urls::topic($title, $tid)."");
exit();
}
} else {
$pagetitle = "Are You Sure You Want To Untag This Topic From Main Page?";
require('incfiles/head.php');
echo "<p><h2>Are You Sure You Want To Untag This Topic From Main Page?</h2><p><div class='display'><form action='#' method='POST'>
<input type='text' name='verify' value='yes'><br><input type='submit' name='submit' class='button' value='Untag'></form></div>";
require('incfiles/end.php');
die();
}
} else {
$pagetitle = "You Cant Untag Topic You Have Not Taged";
require('incfiles/head.php');
functions::display_error('You Cant Untag Topic You Have Not Taged');
require('incfiles/end.php');
die();
}
die();
}




if($action==edittag)
{
$query3 = mysql_num_rows(mysql_query("SELECT * FROM updates WHERE topicid=$tid"));
if($query3 > 0)
{
if(isset($_POST["submit"]))
{

$title = functions::cleaninput($_POST["title"]);
$url = functions::cleaninput($_POST["url"]);
if(strlen($title) < 5)
{
$pagetitle = "Title Too Short";
require('../incfiles/head.php');
functions::display_error('Title Too Short');
require('../incfiles/end.php');
die();
}

if(strlen($url) < 3)
{
$pagetitle = "Url Too Short";
require('../incfiles/head.php');
functions::display_error('Url Too Short');
require('../incfiles/end.php');
die();
}

$tag = mysql_query("UPDATE `updates` SET `title`='$title', `url`='$url' WHERE `topicid`='$tid'");

if($tag)
{
header("location: /");
exit();
} else {
header("location: ".urls::topic($title, $tid)."");
exit();
}


} else {
$pagetitle = "Tag Topic To Front Page";
require('incfiles/head.php');
echo "<p><h2>Tag Topic To Front Page</h2><p><div class='display'><form action='#' method='POST'>
<b>Title:</b><p><input type='text' name='title' value='$title'><br>
<b>Url:</b><p><input type='text' name='url' value='".urls::topic($title, $tid)."'><br><br><input type='submit' name='submit' class='button' value='add'></form></div>";

require('incfiles/end.php');
}
}  else {
$pagetitle = "You Cant Edit Tag You Have Not Taged";
require('incfiles/head.php');
functions::display_error('You Cant Untag You Have Not Taged');
require('incfiles/end.php');
die();
}
die();
}


if($action==stick)
{
$query3 = mysql_num_rows(mysql_query("SELECT * FROM topics WHERE id=$tid AND stick=0"));
if($query3 > 0)
{
if(isset($_POST["submit"]))
{
$verify = $_POST["verify"];
if($verify == yes)
{
mysql_query("UPDATE topics SET stick='1' WHERE id=$tid");
header("location: ".urls::topic($title, $tid)."");
exit();
} else {
header("location: ".urls::topic($title, $tid)."");
exit();
}


} else {
$pagetitle = "Are You Sure You Want To Stick This Topic?";
require('incfiles/head.php');
echo "<p><h2>Are You Sure You Want To Stick This Topic?</h2><p><div class='display'><form action='#' method='POST'>
<input type='text' name='verify' value='yes'><br><input type='submit' name='submit' class='button' value='Stick'></form></div>";

require('incfiles/end.php');
}
}  else {
$pagetitle = "Topic Already Sticked";
require('incfiles/head.php');
functions::display_error('Topic Already Sticked');
require('incfiles/end.php');
die();
}
die();
}




if($action == unstick)
{
$query3 = mysql_num_rows(mysql_query("SELECT * FROM topics WHERE id=$tid AND stick=1"));
if($query3 > 0)
{
if(isset($_POST["submit"]))
{
$verify = $_POST["verify"];
if($verify == yes)
{
mysql_query("UPDATE topics SET stick='0' WHERE id=$tid");
header("location: ".urls::topic($title, $tid)."");
exit();
} else {
header("location: ".urls::topic($title, $tid)."");
exit();
}
} else {
$pagetitle = "Are You Sure You Want To Unstick This Topic?";
require('incfiles/head.php');
echo "<p><h2>Are You Sure You Want To Unstick This Topic?</h2><p><div class='display'><form action='#' method='POST'>
<input type='text' name='verify' value='yes'><br><input type='submit' name='submit' class='button' value='Unstick'></form></div>";
require('incfiles/end.php');
die();
}
} else {
$pagetitle = "You Cant Unstick Topic You Have Not Sticked";
require('incfiles/head.php');
functions::display_error('You Cant Unstick Topic You Have Not Sticked');
require('incfiles/end.php');
die();
}
die();
}


?>