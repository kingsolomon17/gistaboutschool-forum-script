<?php
require('../incfiles/init.php');
require('access.php');

$action = $_GET["action"];
if(isset($action))
{
switch($action)
{

case 'add':
if($access != admin)
	if($access != supermod)
{
$pagetitle = "Insufficient Permission";
require('../incfiles/head.php');
functions::display_error('Insufficient Permission');
require('../incfiles/end.php');
die();
}

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
$tag = mysql_query("INSERT INTO `updates` SET `tagger`='$user', `title`='$title', `url`='$url', `date`='$date'");

if($tag)
{
$pagetitle = "Update Successfully Added";
require('../incfiles/head.php');
$query = mysql_query("SELECT `url`,`title`,`id` FROM `updates` WHERE `url`='$url' AND `title`='$title'"); 
$info = mysql_fetch_array($query);  
$title = functions::cleanoutput($info['title']);
$url = functions::cleanoutput($info["url"]);
$id = $info["id"];
echo '<p><h2>Update Successfully Added</h2><p><div class="display"><a
href="'.$url.'"><b>'.$title.'</b></a><p><a href="?action=edit&id='.$id.'">edit</a> - <a href="?action=delete&id='.$id.'">del</a></div>';

require('../incfiles/end.php');
die();
} else {
$pagetitle = "Error Adding Update";
require('../incfiles/head.php');
functions::display_error('Error Adding Update');
require('../incfiles/end.php');
die();
}
} else {
$pagetitle = "Add Update";
require('../incfiles/head.php');
echo "<p><h2>Add Update</h2><p><div class='display'><form action='#' method='POST'>
<b>Title:</b><p><input type='text' name='title'><br>
<b>Url:</b><p><input type='text' name='url'><br><br><input type='submit' name='submit' class='button' value='add'></form></div>";

require('../incfiles/end.php');  
die();
}
break;


case 'edit':
$id = (int)$_GET["id"];
$query = mysql_query("SELECT `url`,`tagger`,`title`,`topicid` FROM `updates` WHERE `id`=$id");

if(mysql_num_rows($query) < 1)
{
$pagetitle = "Update Not Found";
require('../incfiles/head.php');
functions::display_error('Update Not found');
require('../incfiles/end.php');
die();
}
$info = mysql_fetch_array($query);
$title = functions::cleanoutput($info['title']);
$url = functions::cleanoutput($info["url"]);
$by = $info["tagger"];
$tid = $info["topicid"];
$tinfo = mysql_fetch_array(mysql_query("SELECT * FROM `topics` WHERE id=$tid"));
$bid = $tinfo["boardid"];
$modcheck = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND boardid = '$bid'"));

$check = mysql_num_rows(mysql_query("SELECT `username` FROM `admins` WHERE `username`='$by'"));

if($access != 'admin' && $check > 0)
{
$pagetitle = "Insufficient Permission";
require('../incfiles/head.php');
functions::display_error('Insufficient Permission');
require('../incfiles/end.php');
die();
}

if($access == 'mod')
{
if($modcheck < 1)
{
$pagetitle = "Insufficient Permission";
require('../incfiles/head.php');
functions::display_error('Insufficient Permission');
require('../incfiles/end.php');
die();
}
}

if($access == 'supermod')
{
if($tid < 1)
{
$pagetitle = "Insufficient Permission";
require('../incfiles/head.php');
functions::display_error('Insufficient Permission');
require('../incfiles/end.php');
die();
}
}

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
$edit = mysql_query("UPDATE `updates` SET `title`='$title', `url`='$url' WHERE `id`='$id'");

if($edit)
{
$pagetitle = "Update Successfully Edited";
require('../incfiles/head.php');
$query = mysql_query("SELECT `url`,`title`,`id` FROM `updates` WHERE `id`='$id'"); 
$info = mysql_fetch_array($query);  
$title = functions::cleanoutput($info['title']);
$url = functions::cleanoutput($info["url"]);
$id = $info["id"];
echo '<p><h2>Update Successfully Edited</h2><p><div class="display"><a
href="'.$url.'"><b>'.$title.'</b></a><p><a href="?action=edit&id='.$id.'">edit</a> - <a href="?action=delete&id='.$id.'">del</a></div>';

require('../incfiles/end.php');
die();
} else {
$pagetitle = "Error Editing Update";
require('../incfiles/head.php');
functions::display_error('Error Editing Update');
require('../incfiles/end.php');
die();
}
} else {
$pagetitle = "Edit Update";
require('../incfiles/head.php');
echo "<p><h2>Edit Update</h2><p><div class='display'><form action='#' method='POST'>
<b>Title:</b><p><input type='text' name='title' value='$title'><br>
<b>Url:</b><p><input type='text' name='url' value='$url'><br><br><input type='submit' name='submit' class='button' value='edit'></form></div>";

require('../incfiles/end.php');  
die();
}
break;

case 'delete':
$id = (int)$_GET["id"];
$query = mysql_query("SELECT `topicid`,`tagger` FROM `updates` WHERE `id`=$id");

if(mysql_num_rows($query) < 1)
{
$pagetitle = "Update Not Found";
require('../incfiles/head.php');
functions::display_error('Update Not found');
require('../incfiles/end.php');
die();
}
$info = mysql_fetch_array($query);
$tid = $info["topicid"];
$by = $info["tagger"];
$tinfo = mysql_fetch_array(mysql_query("SELECT * FROM `topics` WHERE id=$tid"));
$bid = $tinfo["boardid"];
$modcheck = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND boardid = '$bid'"));

$check = mysql_num_rows(mysql_query("SELECT `username` FROM `admins` WHERE `username`='$by'"));

if($access != 'admin' && $check > 0)
{
$pagetitle = "Insufficient Permission";
require('../incfiles/head.php');
functions::display_error('Insufficient Permission');
require('../incfiles/end.php');
die();
}

if($access == 'mod')
{
if($modcheck < 1)
{
$pagetitle = "Insufficient Permission";
require('../incfiles/head.php');
functions::display_error('Insufficient Permission');
require('../incfiles/end.php');
die();
}
}

if($access == 'supermod')
{
if($tid < 1)
{
$pagetitle = "Insufficient Permission";
require('../incfiles/head.php');
functions::display_error('Insufficient Permission');
require('../incfiles/end.php');
die();
}
}
if(isset($_POST["submit"]))
{
$verify = $_POST["verify"];
if($verify == yes)
{
$del = mysql_query("DELETE FROM `updates` WHERE `id`='$id'");
if($del)
{   
$pagetitle = "Update Successfully Deleted";
require('../incfiles/head.php');
functions::display_error('Update Successfully Deleted');
require('../incfiles/end.php');
die();
} else {
$pagetitle = "Error Deleting Update";
require('../incfiles/head.php');
functions::display_error('Error Deleting Update');
require('../incfiles/end.php');
die();
}

} else {
functions::go('/panel/updates');
}
} else {
$pagetitle = "Are You Sure You Want To Delete This Update?";
require('../incfiles/head.php');
echo "<p><h2>Are You Sure You Want To Delete This Update</h2><p><div class='display'><form action='#' method='POST'>
<input type='text' name='verify' value='yes'><br><br><input type='submit' name='submit' class='button' value='Delete'></form></div>";

require('../incfiles/end.php');  
die();
}
break;
default:
$pagetitle = "Error: Invalid Action";
require('../incfiles/head.php');
functions::display_error('Error: Invalid Action');
require('../incfiles/end.php');
die();
break;
}
} else {

$pagetitle = 'Manage Updates';
require('../incfiles/head.php');

$rowsperpage = 65;
if(isset($_GET["page"]) && is_numeric($_GET["page"]))
{
$page = (int)$_GET["page"];
}
else
{
$page = 1;
}

$numrows = mysql_num_rows(mysql_query("SELECT * FROM `updates`"));

$totalpages = ceil($numrows/$rowsperpage);
if($page > $totalpages)
{
$page = $totalpages;
}

$offset = ($page-1)*$rowsperpage;

$query = mysql_query("SELECT * FROM `updates` ORDER BY `id` DESC LIMIT $offset, $rowsperpage");

$pagination= new pagination(65, $page, '/panel/boards?page=(page)', $numrows);

echo '<p><h2>Manage Updates</h2><p><a href="/">'.$config->title.' Forum</a> / <a href="/panel/">Panel</a> / <a href="#">Manage Updates</a><p>';
   
if($numrows < 1)
{
echo '<div class="display">No Updates Yet<p><a href="?action=add">Creat Update</a></div>';

} else {
echo $pagination->display() .  '<p><table>';
$i = 0;
while($uinfo=mysql_fetch_array($query))
{
$url = functions::cleanoutput($uinfo["url"]);
$tagger = $uinfo["tagger"];
$by = functions::user_link(functions::cleanoutput($uinfo["tagger"]));
$date = functions::display_date(functions::cleanoutput($uinfo["date"]));
$title = functions::cleanoutput($uinfo["title"]);
$id = $uinfo["id"];
$tid = $uinfo["topicid"];
$tinfo = mysql_fetch_array(mysql_query("SELECT * FROM `topics` WHERE id=$tid"));
$bid = $tinfo["boardid"];

$check = mysql_num_rows(mysql_query("SELECT `username` FROM `admins` WHERE `username`='$tager'"));

$check2 = mysql_num_rows(mysql_query("SELECT `username` FROM `moderators` WHERE `username`='$tagger'"));

$modcheck = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND boardid = '$bid'"));

if($access == 'mod' && $modcheck > 0 && $check < 1)
{
$ac = '<p><a href="?action=edit&id='.$id.'">edit</a> - <a href="?action=delete&id='.$id.'">del</a>';
}

if($access == 'supermod' && $tid > 0 && $check < 1)
{
$ac = '<p><a href="?action=edit&id='.$id.'">edit</a> - <a href="?action=delete&id='.$id.'">del</a>';
}

if($access == 'admin')
{
$ac = '<p><a href="?action=edit&id='.$id.'">edit</a> - <a href="?action=delete&id='.$id.'">del</a>';
}

if($i%2 == 0)
{
$css = 'w';
} else {
$css = '';
}

echo '<tr><td class="'.$css.'"><a
href="'.$url.'"><b>'.$title.'</b></a><br><span class=s>by <b>'.$by.'</b>. '.$date.'</span>'.$ac.'';
$i++;
}
echo '</table><p> '.$pagination->display().'';
}
mysql_free_result($query);
require('../incfiles/end.php');
}

?>