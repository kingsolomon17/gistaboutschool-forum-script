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
$description = functions::cleaninput($_POST["description"]);
$url = functions::cleaninput($_POST["url"]);
$title = functions::cleaninput($_POST["title"]);
$img = functions::cleaninput($_POST["img"]);
$expiredate = (int)$_POST["time"];
$expiredate = $expiredate * 86400;
$expiredate = time() + $expiredate;
$date = time();
if(strlen($description) < 5)
{
$pagetitle = "Your Description Must Be More Than 5";
require('../incfiles/head.php');
functions::display_error('Your Description Must Be More Than 5');
require('../incfiles/end.php');
die();
}

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

$urcheck = mysql_num_rows(mysql_query("SELECT `url` FROM `sponsored` WHERE `url`='$url'"));

$tcheck = mysql_num_rows(mysql_query("SELECT `title` FROM `sponsored` WHERE `title`='$title'"));

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

$add = mysql_query("INSERT INTO `sponsored` SET `img`='$img', `description`='$description', `boardid`='$on', `expiredate`='$expiredate', `title`='$title', `url`='$url', `date`='$date'");
if($add)
{
$info = mysql_fetch_array(mysql_query("SELECT `id` FROM `sponsored` WHERE `title`='$title'"));
$id = $info["id"];
functions::go("?action=view&id=$id");
} else {
$pagetitle = "Error Adding Sponsored";
require('../incfiles/head.php');
functions::display_error('Error Adding Sponsored');
require('../incfiles/end.php');
die();
}
} else {
$pagetitle = "Add Sponsored";
require('../incfiles/head.php');
echo "<p><h2>Add Sponsored</h2><p><div class='display'><form action='#' method='POST'>
<b>Title:</b><p><input type='text' name='title'><br>
<b>Url:</b><p><input type='text' name='url'><br>
<b>ImageUrl:</b><p><input type='text' name='img'><br>
<b>Expiring Time: (in days)</b><p><input type='text' name='time' value='1' size='4'><br>

<b>Select Where To Show:</b><p><select name='on'><option value='0'>On Full Site</option>";
$qquery=mysql_query("SELECT * FROM `boards`");
while($row = mysql_fetch_assoc($qquery))
{
$cname = $row["name"];
$id = $row["id"];
echo "<option value='$id'>$cname</option>";
}
echo "</select><br>
<b>Description:</b><p><textarea rows='9' cols='23' name='description'></textarea><br><br><input type='submit' name='submit' class='button' value='add'></form></div>";

require('../incfiles/end.php');  
die();
}
break;

case 'edit':
$id = (int)$_GET["id"];
$query = mysql_query("SELECT * FROM `sponsored` WHERE `id`=$id");

if(mysql_num_rows($query) < 1)
{
$pagetitle = "Sponsored Post Not Found";
require('../incfiles/head.php');
functions::display_error('Sponsored Post Not found');
require('../incfiles/end.php');
die();
}
$ainfo = mysql_fetch_array($query);
$adescription = functions::cleaninput($ainfo["description"]);
$aurl = functions::cleaninput($ainfo["url"]);
$atitle = functions::cleaninput($ainfo["title"]);
$aimg = functions::cleaninput($ainfo["img"]);

if(isset($_POST["submit"]))
{
$on = (int)$_POST["on"];
$description = functions::cleaninput($_POST["description"]);
$url = functions::cleaninput($_POST["url"]);
$title = functions::cleaninput($_POST["title"]);
$img = functions::cleaninput($_POST["img"]);
if(strlen($description) < 5)
{
$pagetitle = "Your Description Must Be More Than 5";
require('../incfiles/head.php');
functions::display_error('Your Description Must Be More Than 5');
require('../incfiles/end.php');
die();
}

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

$urcheck = mysql_num_rows(mysql_query("SELECT `url` FROM `sponsored` WHERE `url`='$url' AND `id`!='$id'"));

$tcheck = mysql_num_rows(mysql_query("SELECT `title` FROM `sponsored` WHERE `title`='$title' AND `id`!='$id'"));

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

$edit = mysql_query("UPDATE `sponsored` SET `img`='$img', `description`='$description', `boardid`='$on', `title`='$title', `url`='$url'  WHERE `id`=$id");

if($edit)
{
functions::go("?action=view&id=$id");
} else {
$pagetitle = "Error Editing Sponsored";
require('../incfiles/head.php');
functions::display_error('Error Editing Sponsored');
require('../incfiles/end.php');
die();
}
} else {
$pagetitle = "Edit Sponsored";
require('../incfiles/head.php');
echo "<p><h2>Edit Sponsored</h2><p><div class='display'><form action='#' method='POST'>
<b>Title:</b><p><input type='text' name='title' value='$atitle'><br>
<b>Url:</b><p><input type='text' name='url' value='$aurl'><br>
<b>ImageUrl:</b><p><input type='text' name='img' value='$aimg'><br>
<b>Select Where To Show:</b><p><select name='on'><option value='0'>On Full Site</option>";
$qquery=mysql_query("SELECT * FROM `boards`");
while($row = mysql_fetch_assoc($qquery))
{
$cname = $row["name"];
$id = $row["id"];
echo "<option value='$id'>$cname</option>";
}
echo "</select><br>
<b>Description:</b><p><textarea rows='9' cols='23' name='description'>$adescription</textarea><br><br><input type='submit' name='submit' class='button' value='edit'></form></div>";

require('../incfiles/end.php');  
die();
}
break;

case 'renew':
$id = (int)$_GET["id"];
$query = mysql_query("SELECT * FROM `sponsored` WHERE `id`=$id");

if(mysql_num_rows($query) < 1)
{
$pagetitle = "Sponsored Post  Not Found";
require('../incfiles/head.php');
functions::display_error('Sponsored Post Not found');
require('../incfiles/end.php');
die();
}
$ainfo = mysql_fetch_array($query);
$atitle = functions::cleaninput($ainfo["title"]);

if(isset($_POST["submit"]))
{
$expiredate = (int)$_POST["time"];
$expiredate = $expiredate * 86400;
$expiredate = time() + $expiredate;

$renew = mysql_query("UPDATE `sponsored` SET `expiredate`='$expiredate' WHERE `id`=$id");

if($renew)
{
functions::go("?action=view&id=$id");
} else {
$pagetitle = "Error Renewing Sponsored Post";
require('../incfiles/head.php');
functions::display_error('Error Editing Sponsored Post');
require('../incfiles/end.php');
die();
}
} else {
$pagetitle = "Renew Sponsored $atitle";
require('../incfiles/head.php');
echo "<p><h2>Renew Sponsored $atitle</h2><p><div class='display'><form action='#' method='POST'>
<b>Expiring Time: (in days)</b><p><input type='text' name='time' value='1' size='4'><br>
<br><br><input type='submit' name='submit' class='button' value='renew'></form></div>";

require('../incfiles/end.php');  
die();
}
break;

case 'delete':
$id = (int)$_GET["id"];
$query = mysql_query("SELECT * FROM `sponsored` WHERE `id`=$id");

if(mysql_num_rows($query) < 1)
{
$pagetitle = "Sponsored Post Not Found";
require('../incfiles/head.php');
functions::display_error('Sponsored Post Not found');
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
$del = mysql_query("DELETE FROM `sponsored` WHERE `id`='$id'");
if($del)
{
$pagetitle = "Sponsored Post Successfully Deleted";
require('../incfiles/head.php');
functions::display_error('Sponsored Post Successfully Deleted<p><a href="/panel/sponsored">GO BACK</a>');
require('../incfiles/end.php');
die();
} else {
$pagetitle = "Error Deleting Sponsored Post";
require('../incfiles/head.php');
functions::display_error('Error Deleting Sponsored Post');
require('../incfiles/end.php');
die();
}

} else {
functions::go('/panel/sponsored');
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
$query = mysql_query("SELECT * FROM `sponsored` WHERE `id`=$id");

if(mysql_num_rows($query) < 1)
{
$pagetitle = "Sponsored Post Not Found";
require('../incfiles/head.php');
functions::display_error('Sponsored Post Not found');
require('../incfiles/end.php');
die();
}
$info = mysql_fetch_array($query);
$description = functions::cleaninput($info["description"]);
$url = functions::cleaninput($info["url"]);
$clicks = $info["clicks"];
$title = functions::cleaninput($info["title"]);
$expiredate = $info["expiredate"];
if($expiredate < time())
{
$status = '<font color="red">Expired</font> <a href="?action=renew&id='.$id.'">Click Here To Renew</a>';
} else {
$status = '<font color="green">Active</font>';
}

$date = $info["date"];
$bid = $info["boardid"];
if($bid == 0)
{
$whereto = 'All Site Page';
} else {
$bquery = mysql_query("SELECT `name` FROM `boards` WHERE `id`=$bid");
$binfo = mysql_fetch_array($bquery);
$whereto = '<a href="'.urls::board($binfo["name"], $bid).'">'.$binfo["name"].'</a>';
}
$id = $info["id"];
$img = functions::cleaninput($info["img"]);   
$edate = date('l jS F Y \a\t g:i a', $expiredate);
$date = date('l jS F Y \a\t g:i a', $date);
$pagetitle = "View Sponsored: $title";
require('../incfiles/head.php');
echo '<p><h2>View Sponsored: '.$title.'</h2><p><div class="display">
<div class="ratatamp"><a href="/clicksponsored?id='.$id.'" rel="follow"><img src="'.$img.'"></a></div>
<p>Title: '.$title.'<p>Url: '.$url.'<p>ImgUrl: '.$img.'<p>Added On: '.$date.'<p>Expiring Date: '.$edate.'<p>Show On: '.$whereto.'<p>Clicks: '.$clicks.'<p>
Status: '.$status.'<p><a href="?action=edit&id='.$id.'">edit</a> - <a href="?action=delete&id='.$id.'">delete</a></div>';
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

$pagetitle = 'Manage Sponsored';
require('../incfiles/head.php');
echo '<p><h2>Manage Sponsored</h2><p><a href="/">'.$config->title.' Forum</a> / <a href="/panel/">Panel</a> / <a href="#">Manage Sponsored</a><p>';

$rowsperpage = 10;
if(isset($_GET["page"]) && is_numeric($_GET["page"]))
{
$page = (int)$_GET["page"];
}
else
{
$page = 1;
}

$numrows = mysql_num_rows(mysql_query("SELECT `img`,`id`,`expiredate` FROM `sponsored`"));

$totalpages = ceil($numrows/$rowsperpage);
if($page > $totalpages)
{
$page = $totalpages;
}

$offset = ($page-1)*$rowsperpage;

$query = mysql_query("SELECT `img`,`id`,`expiredate` FROM `sponsored` LIMIT $offset, $rowsperpage"); 

$pagination= new pagination($config->postsperpage, $page, '/recent/(page)', $numrows);

if($numrows < 1)
{
echo '<div class="display">No Sponsored Post Yet<p><a href="?action=add">Creat New Sponsored Post</a></div>';

} else {
echo $pagination->display() ;
echo '<table summary="">';
$i = 0;
while($info = mysql_fetch_array($query))
{
$img = functions::cleanoutput($info['img']);
$id = $info["id"];
$expiredate = $info["expiredate"];
if($expiredate < time())
{
$status = '<font color="red">Expired</font> <a href="?action=renew&id='.$id.'">Click Here To Renew</a>';
} else {
$status = '<font color="green">Active</font>';
}

if($i%2 == 0)
{
$css = 'w';
} else {
$css = '';
}

echo '<tr><td class="'.$css.'">
<div class="ratatamp"><a href="/clicksponsored?id='.$id.'" rel="follow"><img src="'.$img.'"></a></div><p>Status: '.$status.'<p><a href="?action=edit&id='.$id.'">edit</a> - <a href="?action=delete&id='.$id.'">delete</a> - <a href="?action=view&id='.$id.'">view</a>';
$i++;
}
echo '</table>';
echo ''.$pagination->display().'';
}
require('../incfiles/end.php');
}
?>