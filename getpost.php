<?php
require('incfiles/init.php');
if(isset($_GET["post"]) && isset($_GET["session"]) && $_GET["session"] == $sessionkey)
{
$pid = $_GET["post"];
$check=mysql_num_rows(mysql_query("SELECT * FROM posts WHERE id=$pid"));
if($check != 1)
{
$pagetitle = "Post Not Found";
require('incfiles/head.php');
functions::display_error('Post Not Found');
require('incfiles/end.php');
die();
}
else
{
$info = mysql_fetch_array(mysql_query("SELECT * FROM posts WHERE id=$pid")) or die(mysql_error());
$msg = $info["message"];
$poster = $info["poster"];
$msg = preg_replace("(\[quote author=(.+?)\](.+?)\[\/quote\])is","",$msg);
$msg = '[quote author='.$poster.']'.$msg.'[/quote]';
echo $msg;
die();
}
} else {
$pagetitle = "Error";
require('incfiles/head.php');
functions::display_error('Error');
require('incfiles/end.php');
die();
}
?>