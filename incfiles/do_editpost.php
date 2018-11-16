<?php
require('incfiles/init.php');
if(!functions::isloggedin())
{
functions::go("/login?redirect=$self");
die();
}


if($_POST["submit"])
{

$epid = (int)$_POST["post"];

$session = $_POST["session"];

if($session != $sessionkey)
{
$pagetitle = "Error Try Again Later";
require('incfiles/head.php');
functions::display_error('Error Try Again Later');
require('incfiles/end.php');
die();
}

$num=mysql_num_rows(mysql_query("SELECT * FROM posts WHERE id=$epid"));

if($epid < 1 || $num < 1)
{
$pagetitle = "Post not found";
require('incfiles/head.php');
functions::display_error('Post not found');
require('incfiles/end.php');
die();
}

$einfo=mysql_fetch_array(mysql_query("SELECT * FROM posts WHERE id=$epid"));
$emessage = $einfo["message"];
$id = $einfo["topicid"];
$ephide = $einfo["hide"];
$etype = $einfo["type"];
$eposter = $einfo["poster"];


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


if($locked > 0 && $admincheck < 1 && $modcheck < 1 && $modcheck2 < 1)
{
$pagetitle = "This topic As Been Locked From Creating New Post Please Try Again Later";
require('incfiles/head.php');
functions::display_error('This topic As Been Locked From Creating New Post Please Try Again Later');
require('incfiles/end.php');
die();
}

if($ephide > 0 && $admincheck < 1 && $modcheck2 < 1)
{
$pagetitle = "This Post As Been Hidden From All Actions";
require('incfiles/head.php');
functions::display_error('This Post As Been Hidden From All Actions');
require('incfiles/end.php');
die();
}


if($user != $eposter && $admincheck < 1 && $modcheck2 < 1)
{
$pagetitle = "Insufficent Permission";
require('incfiles/head.php');
functions::display_error('Insufficent Permission');
require('incfiles/end.php');
die();
}

$admincheck2 = mysql_num_rows(mysql_query("SELECT * FROM admins WHERE username = '$eposter'"));


if($admincheck2 > 0 && $admincheck < 1)
{
$pagetitle = "Insufficent Permission";
require('incfiles/head.php');
functions::display_error('Insufficent Permission');
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

if($size>50000000)
{
$errors[]="size too large!";
}

if(!in_array($ext,$valid_exts))
{
$errors[] ="invalid file extension!";
}


$img_exts = $config->imgExtension;


if(in_array($ext,$img_exts))
{
if($size > 10000000)
{
$errors[]="size too large!";
}
}


}
}
}



$message = functions::cleaninput($_POST["body"]);
if(empty($message) || strlen($message)<4 || strlen($message) > 10000)
{
$errors[]="Your content is too short or more than 10000";
}


if($etype == topic)
{
$title = ucwords(strtolower(functions::cleaninput($_POST["title"])));
if(empty($title) || strlen($title)<4 || strlen($title) > 80)
{
$errors[]="Your title is too short or more than 80";
}
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

$date = time();
$query = mysql_query("UPDATE posts SET lastedituser='$user', lasteditdate='$date', message='$message' WHERE id ='$epid'");
if(!$query)
{
$pagetitle = "An error occured";
require('incfiles/head.php');
functions::display_error("An error occured");
require('incfiles/end.php');
die();
}


if($etype == topic)
{
mysql_query("UPDATE topics SET subject='$title', message='$message' WHERE id ='$id'");
}


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
mysql_query("INSERT INTO attachment SET `name`='$filename', `url`='$path', `by`='$user', size='$size', extension='$ext', `date`='$date', `postid`='$epid'") or mysql_error();
}

}



}

}


if(isset($_POST["follow"]) && $_POST["follow"] =='on')
{
mysql_query("INSERT INTO follow SET follower='$user', date='$date', itemid='$id', type='topic'"); 
}  
if(isset($_GET["redirect"]))
{
$rdr = $_GET['redirect'];
header("location: http://$rdr");
exit();
} else {
header("location: ".urls::topic($title, $id)."#".$epid."");
exit();
}

} else {
$pagetitle = "Error";
require('incfiles/head.php');
functions::display_error('Error');
require('incfiles/end.php');
die();
}
?>