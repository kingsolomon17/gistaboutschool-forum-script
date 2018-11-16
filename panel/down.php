<?php
require('../incfiles/init.php');
require('access.php');

if($access != admin)
{
$pagetitle = "Insufficient Permission";
require('../incfiles/head.php');
functions::display_error('Insufficient Permission');
require('../incfiles/end.php');
die();
}

$action = $_GET["action"];
if(isset($action))
{
switch($action)
{
case 'add':
if(isset($_POST["submit"]))
{
$on = (int)$_POST["on"];
$url = functions::cleaninput($_POST["url"]);
$title = functions::cleaninput($_POST["title"]);
$img = functions::cleaninput($_POST["img"]);
$date = time();

if(strlen($url) < 3)
{
$pagetitle = "Your Url Must Be More Than 3";
require('../incfiles/head.php');
functions::display_error('Your Url Must Be More Than 3');
require('../incfiles/end.php');
die();
}

if(strlen($title) < 5)
{
$pagetitle = "Your title Must Be More Than 5";
require('../incfiles/head.php');
functions::display_error('Your title Must Be More Than 5');
require('../incfiles/end.php');
die();
}

if(strlen($img) < 5)
{
$pagetitle = "Your ImageUrl Must Be More Than 5";
require('../incfiles/head.php');
functions::display_error('Your ImageUrl Must Be More Than 5');
require('../incfiles/end.php');
die();
}

$urcheck = mysql_num_rows(mysql_query("SELECT `url` FROM `down` WHERE `url`='$url'"));

$tcheck = mysql_num_rows(mysql_query("SELECT `title` FROM `down` WHERE `title`='$title'"));

if($tcheck > 0)
{
$pagetitle = "Title Alread Exists";
require('../incfiles/head.php');
functions::display_error('Title Alread Exists');
require('../incfiles/end.php');
die();
}

if($urcheck >0)
{
$pagetitle = "Url Alread Exists";
require('../incfiles/head.php');
functions::display_error('Url Alread Exists');
require('../incfiles/end.php');
die();
}

$add = mysql_query("INSERT INTO `down` SET `img`='$img', `type`='$on', `title`='$title', `url`='$url', `date`='$date'");
if($add)
{
$info = mysql_fetch_array(mysql_query("SELECT `id` FROM `down` WHERE `title`='$title'"));
$id = $info["id"];
functions::go("?action=view&id=$id");
} else {
$pagetitle = "Error Adding downloads";
require('../incfiles/head.php');
functions::display_error('Error Adding downloads');
require('../incfiles/end.php');
die();
}
} else {
$pagetitle = "Add downloads";
require('../incfiles/head.php');
echo "<p><h2>Add downloads</h2><p><div class='display'><form action='#' method='POST'>
<b>Title:</b><p><input type='text' name='title'><br>
<b>Url:</b><p><input type='text' name='url'><br>
<b>ImageUrl:</b><p><input type='text' name='img'><br>

<b>Select Where To Show:</b><p><select name='on'><option value='0'>All</option>";
$qquery=mysql_query("SELECT * FROM `downs`");
while($row = mysql_fetch_assoc($qquery))
{
$cname = $row["name"];
$id = $row["id"];
echo "<option value='$id'>$cname</option>";
}
echo "</select><br><br><input type='submit' name='submit' class='button' value='add'></form></div>";

require('../incfiles/end.php');  
die();
}
break;

case 'edit':
$id = (int)$_GET["id"];
$query = mysql_query("SELECT * FROM `down` WHERE `id`=$id");

if(mysql_num_rows($query) < 1)
{
$pagetitle = "downloads Not Found";
require('../incfiles/head.php');
functions::display_error('downloads Not found');
require('../incfiles/end.php');
die();
}
$ainfo = mysql_fetch_array($query);
$aurl = functions::cleaninput($ainfo["url"]);
$atitle = functions::cleaninput($ainfo["title"]);
$aimg = functions::cleaninput($ainfo["img"]);

if(isset($_POST["submit"]))
{
$on = (int)$_POST["on"];
$url = functions::cleaninput($_POST["url"]);
$title = functions::cleaninput($_POST["title"]);
$img = functions::cleaninput($_POST["img"]);

if(strlen($url) < 3)
{
$pagetitle = "Your Url Must Be More Than 3";
require('../incfiles/head.php');
functions::display_error('Your Url Must Be More Than 3');
require('../incfiles/end.php');
die();
}

if(strlen($title) < 5)
{
$pagetitle = "Your title Must Be More Than 5";
require('../incfiles/head.php');
functions::display_error('Your title Must Be More Than 5');
require('../incfiles/end.php');
die();
}

if(strlen($img) < 5)
{
$pagetitle = "Your ImageUrl Must Be More Than 5";
require('../incfiles/head.php');
functions::display_error('Your ImageUrl Must Be More Than 5');
require('../incfiles/end.php');
die();
}

$urcheck = mysql_num_rows(mysql_query("SELECT `url` FROM `down` WHERE `url`='$url' AND `id`!='$id'"));

$tcheck = mysql_num_rows(mysql_query("SELECT `title` FROM `down` WHERE `title`='$title' AND `id`!='$id'"));

if($tcheck > 0)
{
$pagetitle = "Title Alread Exists";
require('../incfiles/head.php');
functions::display_error('Title Alread Exists');
require('../incfiles/end.php');
die();
}

if($urcheck >0)
{
$pagetitle = "Url Alread Exists";
require('../incfiles/head.php');
functions::display_error('Url Alread Exists');
require('../incfiles/end.php');
die();
}

$edit = mysql_query("UPDATE `down` SET `img`='$img', `type`='$on', `title`='$title', `url`='$url'  WHERE `id`=$id");

if($edit)
{
functions::go("?action=view&id=$id");
} else {
$pagetitle = "Error Editing downloads";
require('../incfiles/head.php');
functions::display_error('Error Editing downloads');
require('../incfiles/end.php');
die();
}
} else {
$pagetitle = "Edit downloads";
require('../incfiles/head.php');
echo "<p><h2>Edit downloads</h2><p><div class='display'><form action='#' method='POST'>
<b>Title:</b><p><input type='text' name='title' value='$atitle'><br>
<b>Url:</b><p><input type='text' name='url' value='$aurl'><br>
<b>ImageUrl:</b><p><input type='text' name='img' value='$aimg'><br>
<b>Select Where To Show:</b><p><select name='on'><option value='0'>All</option>";
$qquery=mysql_query("SELECT * FROM `downs`");
while($row = mysql_fetch_assoc($qquery))
{
$cname = $row["name"];
$id = $row["id"];
echo "<option value='$id'>$cname</option>";
}
echo "</select><br><br><input type='submit' name='submit' class='button' value='edit'></form></div>";

require('../incfiles/end.php');  
die();
}
break;

case 'delete':
$id = (int)$_GET["id"];
$query = mysql_query("SELECT * FROM `down` WHERE `id`=$id");

if(mysql_num_rows($query) < 1)
{
$pagetitle = "downloads Not Found";
require('../incfiles/head.php');
functions::display_error('downloads Not found');
require('../incfiles/end.php');
die();
}
$ainfo = mysql_fetch_array($query);
$atitle = functions::cleaninput($ainfo["title"]);

if(isset($_POST["submit"]))
{
$verify = $_POST["verify"];
if($verify == yes)
{
$del = mysql_query("DELETE FROM `down` WHERE `id`='$id'");
if($del)
{
$pagetitle = "downloads Successfully Deleted";
require('../incfiles/head.php');
functions::display_error('downloads Successfully Deleted<p><a href="/panel/down">GO BACK</a>');
require('../incfiles/end.php');
die();
} else {
$pagetitle = "Error Deleting downloads";
require('../incfiles/head.php');
functions::display_error('Error Deleting downloads');
require('../incfiles/end.php');
die();
}

} else {
functions::go('/panel/down');
}
} else {
$pagetitle = "Are You Sure You Want To Delete $atitle?";
require('../incfiles/head.php');
echo "<p><h2>Are You Sure You Want To Delete $atitle</h2><p><div class='display'><form action='#' method='POST'>
<input type='text' name='verify' value='yes'><br><br><input type='submit' name='submit' class='button' value='Delete'></form></div>";

require('../incfiles/end.php');  
die();
}

break;

case 'view':
$id = (int)$_GET["id"];
$query = mysql_query("SELECT * FROM `down` WHERE `id`=$id");

if(mysql_num_rows($query) < 1)
{
$pagetitle = "downloads Not Found";
require('../incfiles/head.php');
functions::display_error('downloads Not found');
require('../incfiles/end.php');
die();
}

$date = $info["date"];
$bid = $info["type"];
if($bid == 0)
{
$whereto = 'All';
} else {
$bquery = mysql_query("SELECT `name` FROM `downs` WHERE `id`=$id");
$binfo = mysql_fetch_array($bquery);
$whereto = '<a href="'.urls::downs($binfo["name"], $bid).'">'.$binfo["name"].'</a>';
}
$id = $info["id"];
$img = functions::cleaninput($info["img"]);   
$pagetitle = "View downloads: $title";
require('../incfiles/head.php');
echo '<p><h2>View downloads: '.$title.'</h2><p><div class="display">
<div class="ratatamp"><a href="/clickdown?id='.$id.'" rel="nofollow"><img src="'.$img.'"></a></div>
<p>Title: '.$title.'<p>Url: '.$url.'<p>ImgUrl: '.$img.'<p>Show On: '.$whereto.'<p><a href="?action=edit&id='.$id.'">edit</a> - <a href="?action=delete&id='.$id.'">delete</a></div>';
require('../incfiles/end.php');
die();
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

$pagetitle = 'Manage downloads';
require('../incfiles/head.php');
echo '<p><h2>Manage downloads</h2><p><a href="/">'.$config->title.' Forum</a> / <a href="/panel/">Panel</a> / <a href="#">Manage downloads</a><p>';

$rowsperpage = 10;
if(isset($_GET["page"]) && is_numeric($_GET["page"]))
{
$page = (int)$_GET["page"];
}
else
{
$page = 1;
}

$numrows = mysql_num_rows(mysql_query("SELECT `img`,`id` FROM `down`"));

$totalpages = ceil($numrows/$rowsperpage);
if($page > $totalpages)
{
$page = $totalpages;
}

$offset = ($page-1)*$rowsperpage;

$query = mysql_query("SELECT `img`,`id` FROM `down` LIMIT $offset, $rowsperpage"); 

$pagination= new pagination($config->postsperpage, $page, '/recent/(page)', $numrows);

if($numrows < 1)
{
echo '<div class="display">No downloads Yet<p><a href="?action=add">Create New downloads</a></div>';

} else {
echo $pagination->display() ;
echo '<table summary="">';
$i = 0;
while($info = mysql_fetch_array($query))
{
$img = functions::cleanoutput($info['img']);
$id = $info["id"];

if($i%2 == 0)
{
$css = 'w';
} else {
$css = '';
}

echo '<tr><td class="'.$css.'">
<div class="ratatamp"><a href="/clickdown?id='.$id.'" rel="nofollow"><img src="'.$img.'"></a></div><p><a href="?action=edit&id='.$id.'">edit</a> - <a href="?action=delete&id='.$id.'">delete</a> - <a href="?action=view&id='.$id.'">view</a>';
$i++;
}
echo '</table>';
echo ''.$pagination->display().'';
}
require('../incfiles/end.php');
}
?>