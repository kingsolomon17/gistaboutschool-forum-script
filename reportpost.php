<?php
require('incfiles/init.php');
$pagetitle = 'reportpost';
require('incfiles/head.php');

$pid = $_GET["post"];

if(!functions::isloggedin())
{
functions::display_error('You Must Be LoggedIn To Report This Post');
require('incfiles/end.php');
die();
}

$check=mysql_num_rows(mysql_query("SELECT * FROM posts WHERE id=$pid"));
if($check != 1)
{
functions::display_error('Post Not Found');
require('incfiles/end.php');
die();
}

$einfo=mysql_fetch_array(mysql_query("SELECT * FROM posts WHERE id=$pid"));
$id = $einfo["topicid"];
$ephide = $einfo["hide"];
   
$info = mysql_fetch_array(mysql_query("SELECT * FROM topics WHERE id=$id"));
$title=functions::cleanoutput($info["subject"]);
   

if($ephide > 0)
{
functions::display_error('This topic As Been Hidden Already');
require('incfiles/end.php');
die();
}


if($_POST["submit"])
{
   
$message = functions::cleaninput($_POST["reason"]);

if(empty($message) || strlen($message)<4 || strlen($message)>150)
{
$errors[]="Your content is too short or more than 150";
}
   
if(count($errors) > 0)
{
$string = "";
foreach($errors as $error)
{
$string .= "$error<br/>";
}
functions::display_error($string);
require('incfiles/end.php');
die();
}
   
$date = time();
$query = mysql_query("INSERT reportposts SET reporter='$user', date='$date', postid='$pid', reason='$message'");
if(!$query)
{
$msg="An error occured";
functions::display_error($msg);
require('incfiles/end.php');
die();
}
   
if(isset($_GET["redirect"]))
{
$rdr = $_GET['redirect'];
header("location: http://$rdr");
exit();
} else {
header("location: ".urls::topic($title, $id)."");
exit();
}

} else {
echo '<h2>Report to Moderator</h2><p><table><tbody><tr><td>
<form method="POST" action="#">
<p>Inform the moderators and administrators of an abusive or wrongly posted message.
<p>Enter comment:
<input type="text" name="reason">
<input type="submit" name="submit" value="Submit">
</form></tbody></table>';
require('incfiles/end.php');
}


?>