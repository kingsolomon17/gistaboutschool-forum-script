<?php
require('incfiles/init.php');

$id = (int)$_GET["id"];
$query = mysql_query("SELECT `url`,`expiredate` FROM `sponsored` WHERE `id`='$id'");
if(mysql_num_rows($query) > 0)
{
$info = mysql_fetch_array($query);
$url = $info["url"];
$expiredate = $info["expiredate"];
$time = time();

if($expiredate > $time)
{
if(!isset($_COOKIE['sponsored'.$id]));
{
setcookie("sessionkey", 'sponsored'.$id.'', "$expiredate");
mysql_query("UPDATE `sponsored` SET `clicks`=`clicks`+1 WHERE `id`=$id");
}
functions::go($url);
} else {
$pagetitle = "This Sponsored Post Has Expired";
require('incfiles/head.php');
functions::display_error('This Sponsored Post Has Expired');
require('incfiles/end.php');
die();
}

} else {
$pagetitle = "Sponsored Post Not Found";
require('incfiles/head.php');
functions::display_error('Sponsored Post Not Found');
require('incfiles/end.php');
die();
}
?>