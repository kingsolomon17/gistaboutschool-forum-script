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
case 'delete':

$bid = (int)$_GET["bid"];
$query = mysql_query("SELECT `name` FROM `boards` WHERE `id`=$bid");

if(mysql_num_rows($query) < 1)
{
$pagetitle = "Board Not Found";
require('../incfiles/head.php');
functions::display_error('Board Not found');
require('../incfiles/end.php');
die();
}

$check = mysql_num_rows(mysql_query("SELECT `name` FROM `boards` WHERE `typeid`=$bid"));

if($check > 0)
{
$pagetitle = "You Can't Delete Board That Have Child Boards 1st Delete Its Child Boards And Try Again";
require('../incfiles/head.php');
functions::display_error("You Can't Delete Board That Have Child Boards 1st Delete Its Child Boards And Try Again");
require('../incfiles/end.php');
die();
}


$binfo = mysql_fetch_array($query);
$bname = $binfo["name"];

if(isset($_POST["submit"]))
{
$password = $_POST["password"];
if($password == $config->sitePassword)
{
$del = mysql_query("DELETE FROM `boards` WHERE `id`='$bid'");
if($del)
{
$tquery = mysql_query("SELECT `id` FROM `topics` WHERE `boardid`=$bid");
while($tinfo = mysql_fetch_array($tquery))
{
$tid = $tinfo["id"];


$atquery = mysql_query("SELECT `url` FROM `attachment` WHERE `topicid`=$tid");
while($atinfo = mysql_fetch_array($atquery))
{
$aturl = $atinfo["url"];
if(file_exists($config->attachmentFolder.$aturl))
{
unlink($config->attachmentFolder.$aturl);
}
}
mysql_query("DELETE FROM `attachment` WHERE `topicid`=$tid");

$pquery = mysql_query("SELECT `id` FROM posts WHERE `topicid`=$tid");
while($pinfo = mysql_fetch_array($pquery))
{

$pid = $pinfo["id"];
mysql_query("DELETE FROM `reportposts` WHERE `postid`=$pid");

mysql_query("DELETE FROM `likepost` WHERE `postid`=$pid");

}
mysql_query("DELETE FROM `posts` WHERE `topicid`=$tid");

mysql_query("DELETE FROM `follow` WHERE `type`='topic' AND `itemid`=$tid");

}

mysql_query("DELETE FROM `topics` WHERE `boardid`=$bid");

mysql_query("DELETE FROM `follow` WHERE `type`='board' AND `itemid`=$bid");

mysql_query("DELETE FROM `moderators` WHERE `boardid`=$bid");

mysql_query("DELETE FROM `bannedusers` WHERE `boardid`=$bid");

$pagetitle = "Board Successfully Deleted";
require('../incfiles/head.php');
functions::display_error('Board Successfully Deleted<p><a href="/panel/boards">GO BACK</a>');
require('../incfiles/end.php');
die();
} else {
$pagetitle = "Error Deleting Board";
require('../incfiles/head.php');
functions::display_error('Error Deleting Board');
require('../incfiles/end.php');
die();
}


} else {
$pagetitle = "Incorrect Password";
require('../incfiles/head.php');
functions::display_error('Incorrect Password');
require('../incfiles/end.php');
}
} else {
$pagetitle = "Insert Site Password To Delete $bname";
require('../incfiles/head.php');
echo "<p><h2>Insert Site Password To Delete $bname</h2><p><div class='display'><form action='#' method='POST'>
<input type='text' name='password' value=''><br><br><input type='submit' name='submit' class='button' value='submit'></form></div>";

require('../incfiles/end.php');  
die();
}
break;

case 'addsub':

if(isset($_POST["submit"]))
{

$bid = (int)$_POST["bid"];
$query = mysql_query("SELECT * FROM `boards` WHERE `id`=$bid");

if(mysql_num_rows($query) < 1)
{
$pagetitle = "Board Not Found";
require('../incfiles/head.php');
functions::display_error('Board Not found');
require('../incfiles/end.php');
die();
}

$binfo = mysql_fetch_array($query);
$bname = $binfo["name"];
$btype = $binfo["type"];
$btypeid = $binfo["typeid"];
$sub = $binfo["sub"];

if($sub == 'last')
{
$pagetitle = "You Cant Create Sub Board On This Board";
require('../incfiles/head.php');
functions::display_error('You Cant Create Sub Board On This Board');
require('../incfiles/end.php');
die();
}

$description = functions::cleaninput($_POST["description"]);
$url = functions::cleaninput(functions::converturl($_POST["url"]));
$name = functions::cleaninput($_POST["name"]);

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

if(strlen($name) < 5)
{
$pagetitle = "Your Name Must Be More Than 5";
require('../incfiles/head.php');
functions::display_error('Your Name Must Be More Than 5');
require('../incfiles/end.php');
die();
}

$urcheck = mysql_num_rows(mysql_query("SELECT `url` FROM `boards` WHERE `url`='$url'"));

$ncheck = mysql_num_rows(mysql_query("SELECT `name` FROM `boards` WHERE `name`='$name'"));

$uscheck = mysql_num_rows(mysql_query("SELECT `username` FROM `users` WHERE `username`='$url'"));

if($ncheck > 0)
{
$pagetitle = "Name Alread Exists";
require('../incfiles/head.php');
functions::display_error('Name Alread Exists');
require('../incfiles/end.php');
die();
}

if($uscheck > 0 || $urcheck >0 || file_exists('../'.$url) || file_exists('../'.$url.'.php'))
{
$pagetitle = "Url Alread Exists";
require('../incfiles/head.php');
functions::display_error('Url Alread Exists');
require('../incfiles/end.php');
die();
}

$add = mysql_query("INSERT INTO `boards` SET `name`='$name', `description`='$description', `url`='$url', `type`='child', `typeid`='$bid', `sub`='".($btype == 'child' ? 'last' : 2)."'");

if($add)
{
$pagetitle = "SubBoard Successfully Added";
require('../incfiles/head.php');
$query = mysql_query("SELECT `name`,`id`,`description` FROM `boards` WHERE `url`='$url'"); 
$info = mysql_fetch_array($query);  
$name = functions::cleanoutput($info['name']);
$des = functions::cleanoutput($info["description"]);
$bid = $info["id"];
$tnum = mysql_num_rows(mysql_query("SELECT `id` FROM topics WHERE `boardid`=$bid"));

echo '<p><h2>SubBoard Successfully Added</h2><p><div class="display"><b><a href="'.urls::board($name, $bid).'">'.$name.'</a></b>: '.$des.' ('.$tnum.' topics)<p><a href="?action=edit&bid='.$bid.'">edit</a> - <a href="?action=delete&bid='.$bid.'">delete</a></div>';

require('../incfiles/end.php');
die();
} else {
$pagetitle = "Error Adding SubBoard";
require('../incfiles/head.php');
functions::display_error('Error Adding SubBoard');
require('../incfiles/end.php');
die();
}

} else {

$pagetitle = "Add SubBoard";
require('../incfiles/head.php');
echo "<p><h2>Add SubBoard</h2><p><div class='display'><form action='#' method='POST'>
<b>Name:</b><p><input type='text' name='name'><br>
<b>Url:</b><p><input type='text' name='url'><br>
<b>Select Parent Board:</b><p><select name='bid'>";

$qquery=mysql_query("SELECT * FROM `boards` WHERE `sub`!='last'");
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

case 'add':


if(isset($_POST["submit"]))
{
$description = functions::cleaninput($_POST["description"]);
$url = functions::cleaninput(functions::converturl($_POST["url"]));
$name = functions::cleaninput($_POST["name"]);

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

if(strlen($name) < 5)
{
$pagetitle = "Your Name Must Be More Than 5";
require('../incfiles/head.php');
functions::display_error('Your Name Must Be More Than 5');
require('../incfiles/end.php');
die();
}

$urcheck = mysql_num_rows(mysql_query("SELECT `url` FROM `boards` WHERE `url`='$url'"));

$ncheck = mysql_num_rows(mysql_query("SELECT `name` FROM `boards` WHERE `name`='$name'"));

$uscheck = mysql_num_rows(mysql_query("SELECT `username` FROM `users` WHERE `username`='$url'"));

if($ncheck > 0)
{
$pagetitle = "Name Alread Exists";
require('../incfiles/head.php');
functions::display_error('Name Alread Exists');
require('../incfiles/end.php');
die();
}

if($uscheck > 0 || $urcheck >0 || file_exists('../'.$url) || file_exists('../'.$url.'.php'))
{
$pagetitle = "Url Alread Exists";
require('../incfiles/head.php');
functions::display_error('Url Alread Exists');
require('../incfiles/end.php');
die();
}

$add = mysql_query("INSERT INTO `boards` SET `name`='$name', `description`='$description', `url`='$url', `type`='parent', `sub`='1'");

if($add)
{
$pagetitle = "Board Successfully Added";
require('../incfiles/head.php');

$query = mysql_query("SELECT `name`,`id`,`description` FROM `boards` WHERE `url`='$url'"); 
$info = mysql_fetch_array($query);  
$name = functions::cleanoutput($info['name']);
$des = functions::cleanoutput($info["description"]);
$bid = $info["id"];
$tnum = mysql_num_rows(mysql_query("SELECT `id` FROM topics WHERE `boardid`=$bid"));

echo '<p><h2>Board Successfully Added</h2><p><div class="display"><b><a href="'.urls::board($name, $bid).'">'.$name.'</a></b>: '.$des.' ('.$tnum.' topics)<p><a href="?action=edit&bid='.$bid.'">edit</a> - <a href="?action=delete&bid='.$bid.'">delete</a></div>';

require('../incfiles/end.php');
die();
} else {
$pagetitle = "Error Adding Board";
require('../incfiles/head.php');
functions::display_error('Error Adding Board');
require('../incfiles/end.php');
die();
}

} else {

$pagetitle = "Add Board";
require('../incfiles/head.php');
echo "<p><h2>Add Board</h2><p><div class='display'><form action='#' method='POST'>
<b>Name:</b><p><input type='text' name='name'><br>
<b>Url:</b><p><input type='text' name='url'><br><b>Description:</b><p><textarea rows='9' cols='23' name='description'></textarea><br><br><input type='submit' name='submit' class='button' value='add'></form></div>";

require('../incfiles/end.php');  
die();
}

break;

case 'edit':

$bid = (int)$_GET["bid"];
$query = mysql_query("SELECT * FROM `boards` WHERE `id`=$bid");

if(mysql_num_rows($query) < 1)
{
$pagetitle = "Board Not Found";
require('../incfiles/head.php');
functions::display_error('Board Not found');
require('../incfiles/end.php');
die();
}

$binfo = mysql_fetch_array($query);
$bname = functions::cleanoutput($binfo["name"]);
$burl = functions::cleanoutput($binfo["url"]);
$bdescription = functions::cleanoutput($binfo["description"]);

if(isset($_POST["submit"]))
{
$description = functions::cleaninput($_POST["description"]);
$url = functions::cleaninput(functions::converturl($_POST["url"]));
$name = functions::cleaninput($_POST["name"]);

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

if(strlen($name) < 5)
{
$pagetitle = "Your Name Must Be More Than 5";
require('../incfiles/head.php');
functions::display_error('Your Name Must Be More Than 5');
require('../incfiles/end.php');
die();
}

$urcheck = mysql_num_rows(mysql_query("SELECT `url` FROM `boards` WHERE `url`='$url' AND `id`!=$bid"));

$ncheck = mysql_num_rows(mysql_query("SELECT `name` FROM `boards` WHERE `name`='$name' AND `id`!=$bid"));

$uscheck = mysql_num_rows(mysql_query("SELECT `username` FROM `users` WHERE `username`='$url'"));

if($ncheck > 0)
{
$pagetitle = "Name Alread Exists";
require('../incfiles/head.php');
functions::display_error('Name Alread Exists');
require('../incfiles/end.php');
die();
}

if($uscheck > 0 || $urcheck >0 || file_exists('../'.$url) || file_exists('../'.$url.'.php'))
{
$pagetitle = "Url Alread Exists";
require('../incfiles/head.php');
functions::display_error('Url Alread Exists');
require('../incfiles/end.php');
die();
}

$edit = mysql_query("UPDATE `boards` SET `name`='$name', `description`='$description', `url`='$url' WHERE `id`=$bid");

if($edit)
{
$pagetitle = "Board Successfully Edited";
require('../incfiles/head.php');

$query = mysql_query("SELECT `name`,`description` FROM `boards` WHERE `id`=$bid"); 
$info = mysql_fetch_array($query);  
$name = functions::cleanoutput($info['name']);
$des = functions::cleanoutput($info["description"]);
$tnum = mysql_num_rows(mysql_query("SELECT `id` FROM topics WHERE `boardid`=$bid"));

echo '<p><h2>Board Successfully Edited</h2><p><div class="display"><b><a href="'.urls::board($name, $bid).'">'.$name.'</a></b>: '.$des.' ('.$tnum.' topics)<p><a href="?action=edit&bid='.$bid.'">edit</a> - <a href="?action=delete&bid='.$bid.'">delete</a></div>';

require('../incfiles/end.php');
die();
} else {
$pagetitle = "Error Editing Board";
require('../incfiles/head.php');
functions::display_error('Error Editing Board');
require('../incfiles/end.php');
die();
}

} else {

$pagetitle = "Edit Board";
require('../incfiles/head.php');
echo "<p><h2>Edit Board $bname</h2><p><div class='display'><form action='#' method='POST'>
<b>Name:</b><p><input type='text' name='name' value='$bname'><br>
<b>Url:</b><p><input type='text' name='url' value='$burl'><br><b>Description:</b><p><textarea rows='9' cols='23' name='description'>$bdescription</textarea><br><br><input type='submit' name='submit' class='button' value='add'></form></div>";

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
$pagetitle = 'Manage Boards';
require('../incfiles/head.php');
echo '<a name="top"></a><p><h2>Manage Boards</h2><p><a href="/">'.$config->title.' Forum</a> / <a href="/panel/">Panel</a> / <a href="#">Manage Boards</a><p>';

$query = mysql_query("SELECT `name`,`id`,`description` FROM `boards`"); 
if(mysql_num_rows($query) < 1)
{
echo '<div class="display">No Boards Yet<p><a href="?action=add">Creat New Board</a></div>';

} else {
echo '<table summary="">';
$i = 0;
while($info = mysql_fetch_array($query))
{
$name = functions::cleanoutput($info['name']);
$des = functions::cleanoutput($info["description"]);
$id = $info['id'];

$tnum = mysql_num_rows(mysql_query("SELECT `id` FROM topics WHERE `boardid`=$id"));

if($i%2 == 0)
{
$css = 'w';
} else {
$css = '';
}

echo '<tr><td class="'.$css.'"><b><a href="'.urls::board($name, $id).'">'.$name.'</a></b>: '.$des.' ('.$tnum.' topics)<p><a href="?action=edit&bid='.$id.'">edit</a> - <a href="?action=delete&bid='.$id.'">delete</a>';
$i++;
}
echo '</table>';
}
require('../incfiles/end.php');
}
?>
