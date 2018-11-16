<?php
require('incfiles/init.php');
if(!functions::isloggedin())
{
functions::go("/login?redirect=$self");
die();
}

if($_POST["submit"])
{

$id = (int)$_POST["board"];
$num=mysql_num_rows(mysql_query("SELECT * FROM boards WHERE id=$id"));

if($id < 1 || $num < 1)
{
$pagetitle = "forum category not found";
require('incfiles/head.php');
functions::display_error('forum category not found');
require('incfiles/end.php');
die();
}

$modcheck2 = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND type = 'super'"));

$modcheck = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND boardid = '$id'"));

$admincheck = mysql_num_rows(mysql_query("SELECT * FROM admins WHERE username = '$user'"));

$info = mysql_fetch_array(mysql_query("SELECT * FROM boards WHERE id=$id"));
$locked = $info["locked"];

if($locked > 0 && $admincheck < 1 && $modcheck < 1 && $modcheck2 < 1)
{
$pagetitle = "This Board As Been Locked From Creating New Topic Please Try Again Later";
require('incfiles/head.php');
functions::display_error('This Board As Been Locked From Creating New Topic Please Try Again Later');
require('incfiles/end.php');
die();
}



$erros=array();

if(isset($_FILES["attachment"]))
{

for ($i = 0; $i <= 3; $i++)
{
$ffilename = $_FILES['attachment']['name'][$i];
if($ffilename)
{
$valid_exts = $config->validExtension;
$ext = end(explode(".",strtolower($ffilename)));
$size = $_FILES["attachment"]["size"][$i];
$path = "attachment/".$_FILES['attachment']['name'][$i];

if($size<10)
{
$errors[]="File must be larger than 10Bytes!";
}

$img_exts = $config->imgExtension;

if(in_array($ext,$img_exts))
{
if($size > 10000000)
{
$errors[]="size too large!";
}
}





if($size>50000000)
{
$errors[]="size too large!";
}

if(!in_array($ext,$valid_exts))
{
$errors[] ="invalid file extension!";
}

}
}
}



$title = ucwords(strtolower(functions::cleaninput($_POST["title"])));
$message = functions::cleaninput($_POST["body"]);

if(empty($title)|| strlen($title)<4 || strlen($title) > 80)
{
$errors[]="Your title is too short or more than 80";
}

if(empty($message) || strlen($message)<4 || strlen($message)>10000)
{
$errors[]="Your content is too short or more than 10000";
}

$ttime = time() - 20;
$tcheck = mysql_num_rows(mysql_query("SELECT * FROM topics WHERE boardid=$id AND message='$message' AND date>'$ctime' AND poster='$user' AND subject='$title'"));

if($tcheck > 0)
{
$errors[]="You create same topic in less than 20sec";
}


if(count($errors) > 0)
{
$string = "";
foreach($errors as $error)
{
$string .= "$error<br/>";
}
$pagetitle = "error";
require('incfiles/head.php');
functions::display_error($string);
require('incfiles/end.php');
die();
}   

$date = time();
$query=mysql_query("INSERT INTO topics SET poster='$user', lastpostdate='$date', lastposter='$user', subject='$title', message='$message', date='$date', boardid=$id");
if(!$query)
{
$pagetitle = "An error occured";
require('incfiles/head.php');
functions::display_error("An error occured");
require('incfiles/end.php');
die();
}

$tt=mysql_fetch_array(mysql_query("SELECT * FROM topics WHERE lastpostdate='$date' AND subject='$title' AND message='$message' AND date='$date'"));
$ti=$tt["id"];
$tit=functions::cleanoutput($tt["subject"]);

mysql_query("INSERT INTO posts SET poster='$user', date='$date', topicid='$ti', message='$message', type='topic', position=1") or die(mysql_error());


$fabc=mysql_fetch_array(mysql_query("SELECT * FROM posts WHERE date='$date' AND message='$message' AND topicid='$ti'"));
$ffid=$fabc["id"];


if(isset($_FILES["attachment"]))
{
for ($i = 0; $i <= 3; $i++)
{
$ffilename = $_FILES['attachment']['name'][$i];
if($ffilename)
{
$frand=rand(0000, 9999);
$filename =  preg_replace('/[^a-zA-Z0-9-_\.]/i','',$_FILES['attachment']['name'][$i]);
$path = "attachment/" . $frand . preg_replace('/[^a-zA-Z0-9-_\.]/i','',$_FILES['attachment']['name'][$i]);
$size = $_FILES["attachment"]["size"][$i];
$ext = end(explode(".",strtolower($ffilename)));
copy($_FILES['attachment']['tmp_name'][$i],
$path);
if(strlen($filename) > 3)
{
mysql_query("INSERT INTO attachment SET `name`='$filename', `url`='$path', `by`='$user', size='$size', extension='$ext', `date`='$date', `postid`='$ffid'") or mysql_error();
}

}



}

}



mysql_query("UPDATE usersfollow SET hasread=hasread+1 WHERE following='$user'");

mysql_query("UPDATE follow SET hasread=hasread+1 WHERE type='board' AND itemid=$id");

mysql_query("INSERT INTO follow SET follower='$user', date='$date', itemid='$ti', type='topic'"); 
  
header("location: ".urls::topic($tit, $ti)."#$ffid");
exit();

} else {
$pagetitle = "Error";
require('incfiles/head.php');
functions::display_error('Error');
require('incfiles/end.php');
die();
}
?>