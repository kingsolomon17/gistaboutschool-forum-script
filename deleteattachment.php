<?php
require('incfiles/init.php');
require('panel/access.php');   
$id = (int)$_GET["id"];
$query = mysql_query("SELECT * FROM `attachment` WHERE `id`='$id'");

if(mysql_num_rows($query) != 1)
{
$pagetitle = "Attachment Not Found";
require('incfiles/head.php');
functions::display_error('Attachment Not Found');
require('incfiles/end.php');
die();
}
$info = mysql_fetch_array($query);
$url = $info["url"];
$tid = $info["topicid"];

$tinfo = mysql_fetch_array(mysql_query("SELECT `subject` FROM `topics` WHERE `id`='$tid'"));

$title = functions::cleanoutput($info["subject"]);
$by = $info["by"];

$admincheck = mysql_num_rows(mysql_query("SELECT * FROM `admins` WHERE `username` = '$by'"));

if($admincheck > 0 && $access != 'admin')
{
$pagetitle = "Insufficient Permission";
require('incfiles/head.php');
functions::display_error('Insufficient Permission');
require('incfiles/end.php');
die();
}
if(isset($_POST["submit"]))
{
$verify = $_POST["verify"];
if($verify == yes)
{

$del = mysql_query("DELETE FROM `attachment` WHERE `id`=$id");

if($del)
{
if(file_exists($config->attachmentFolder.$url))
{
unlink($config->attachmentFolder.$url);
}
}
if(isset($_GET["redirect"]))
{
$rdr = $_GET['redirect'];
header("location: http://$rdr");
exit();
} else {
header("location: ".urls::topic($title, $tid)."");
exit();
}

} else {
if(isset($_GET["redirect"]))
{
$rdr = $_GET['redirect'];
header("location: http://$rdr");
exit();
} else {
header("location: ".urls::topic($title, $tid)."");
exit();
}
}

} else {
$pagetitle = "Are You Sure You Want To Delete Attachment?";
require('incfiles/head.php');
echo "<p><h2>Are You Sure You Want To Delete Attachment?</h2><p><div class='display'><form action='#' method='POST'>
<input type='text' name='verify' value='yes'><br><br><input type='submit' name='submit' class='button' value='Delete'></form></div>";

require('incfiles/end.php');  
die();
}
?>