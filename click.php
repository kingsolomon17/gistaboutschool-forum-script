<?php
require('incfiles/init.php');

$id = (int)$_GET["id"];
$query = mysql_query("SELECT `url`,`expiredate` FROM `ads` WHERE `id`='$id'");
if(mysql_num_rows($query) > 0)
{
$info = mysql_fetch_array($query);
$url = $info["url"];
$expiredate = $info["expiredate"];
$time = time();

if($expiredate > $time)
{
if(!isset($_COOKIE['ads'.$id]));
{
setcookie("sessionkey", 'ads'.$id.'', "$expiredate");
mysql_query("UPDATE `ads` SET `clicks`=`clicks`+1 WHERE `id`=$id");
}
functions::go($url);
} else {
$pagetitle = "This Ads Has Expired";
require('incfiles/head.php');
functions::display_error('This Ads Has Expired');
require('incfiles/end.php');
die();
}

} else {
$pagetitle = "Ads Not Found";
require('incfiles/head.php');
functions::display_error('Ads Not Found');
require('incfiles/end.php');
die();
}
?>