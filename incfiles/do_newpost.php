<?php
require('incfiles/init.php');

if(!functions::isloggedin())
{
functions::go("/login?redirect=$self");
die();
}



if($_POST["submit"])
{

$id = (int)$_POST["topic"];
$session = $_POST["session"];

if($session != $sessionkey)
{
$pagetitle = "Error Try Again Later";
require('incfiles/head.php');
functions::display_error('Error Try Again Later');
require('incfiles/end.php');
die();
}


$num=mysql_num_rows(mysql_query("SELECT * FROM topics WHERE id=$id"));

if($id < 1 || $num < 1)
{
$pagetitle = "forum topic not found";
require('incfiles/head.php');
functions::display_error('forum topic not found');
require('incfiles/end.php');
die();
}



$info = mysql_fetch_array(mysql_query("SELECT * FROM topics WHERE id=$id"));
$locked = $info["locked"];
$bid = $info["boardid"];   


$modcheck = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND boardid = '$bid'"));

$admincheck = mysql_num_rows(mysql_query("SELECT * FROM admins WHERE username = '$user'"));

$modcheck2 = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND type = 'super'"));

if($locked > 0 && $admincheck < 1 && $modcheck < 1 && $modcheck2 < 1)
{
$pagetitle = "This topic As Been Locked From Creating New Post Please Try Again Later";
require('incfiles/head.php');
functions::display_error('This topic As Been Locked From Creating New Post Please Try Again Later');
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
$ext = end(explode(".",strtolower($ffilename)));
$valid_exts = $config->validExtension;
$size = $_FILES["attachment"]["size"][$i];
$path = "attachment/".$_FILES['attachment']['name'][$i];

if($size<10)
{
$errors[]="File must be larger than 10Bytes!";
}

if($size>50000000)
{
$errors[]="size too large!";
}


$img_exts = $config->imgExtension;

if(in_array($ext,$img_exts))
{
if($size > 10000000)
{
$errors[]="size too large!";
}
}


if(!in_array($ext,$valid_exts))
{
$errors[] ="invalid file extension!";
}

}
}
}




$message = functions::cleaninput($_POST["body"]);

if(empty($message) || strlen($message)<4 || strlen($message)>10000)
{
$errors[]="Your content is too short or more than 10000";
}

$ctime = time() - 20;
$pcheck = mysql_num_rows(mysql_query("SELECT * FROM posts WHERE topicid=$id AND date>'$ctime' AND poster='$user' AMD message='$message'"));

if($pcheck > 0)
{
$errors[]="You can't post same post in less than 20sec";
}

if(count($errors) > 0)
{
$string = "";
foreach($errors as $error)
{
$string .= "$error<br/>";
}
$pagetitle = "Error";
require('incfiles/head.php');
functions::display_error($string);
require('incfiles/end.php');
die();
}   

$numrows3 = mysql_num_rows(mysql_query("SELECT * FROM posts WHERE topicid=$id"));

$position = $numrows3 + 1;

$date = time();
$query = mysql_query("INSERT INTO posts SET poster='$user', date='$date', topicid='$id', message='$message', type='reply', position='$position'");
if(!$query)
{
$pagetitle = "An error occured";
require('incfiles/head.php');
functions::display_error('An error occured');
require('incfiles/end.php');
die();
}


$fabc = mysql_fetch_array(mysql_query("SELECT * FROM posts WHERE date='$date' AND message='$message' AND topicid='$id'"));
$ffid=$fabc["id"];


if(isset($_FILES["attachment"]))
{
for ($i = 0; $i <= 3; $i++)
{
$ffilename = $_FILES['attachment']['name'][$i];
if($ffilename)
{
$frand=rand(0000, 9999);
$filename = preg_replace('/[^a-zA-Z0-9-_\.]/i','',$_FILES['attachment']['name'][$i]);

$path = "attachment/" . $frand . preg_replace('/[^a-zA-Z0-9-_\.]/i','',$_FILES['attachment']['name'][$i]);
$size = $_FILES["attachment"]["size"][$i];
$ext = end(explode(".",strtolower($ffilename)));
copy($_FILES['attachment']['tmp_name'][$i],
$path);
if(strlen($filename) > 3)
{
mysql_query("INSERT INTO attachment SET `name`='$filename', `by`='$user', `url`='$path', size='$size', extension='$ext', `date`='$date', `postid`='$ffid'") or mysql_error();
}

}



}

}
mysql_query("UPDATE topics SET lastposter='$user', lastpostdate='$date' WHERE id=$id");



mysql_query("UPDATE usersfollow SET hasread=hasread+1 WHERE following='$user'");

mysql_query("UPDATE follow SET hasread=hasread+1 WHERE type='topic' AND itemid=$id");

if(isset($_POST["follow"]) && $_POST["follow"] =='on')
{
mysql_query("INSERT INTO follow SET follower='$user', date='$date', itemid='$id', type='topic'"); 
}  

$rowsperpage2 = $config->postsperpage;
$numrows2 = mysql_num_rows(mysql_query("SELECT * FROM posts WHERE topicid=$id"));
$lpage = ceil($numrows2/$rowsperpage2);
   



header("location: ".urls::topic($title, $id, $lpage)."#".$ffid."");
exit();

} else {
$pagetitle = "Error";
require('incfiles/head.php');
functions::display_error('Error');
require('incfiles/end.php');
die();
}
?>