<?php
require('../incfiles/init.php');
require('access.php');

$action = $_GET["action"];
if(isset($action))
{
switch($action)
{
case 'delete':
if($access != admin)
{
$pagetitle = "Insufficient Permission";
require('../incfiles/head.php');
functions::display_error('Insufficient Permission');
require('../incfiles/end.php');
die();
}

$uid = (int)$_GET["uid"];
$user2 = functions::user_info2($uid, 'username');
$email = functions::user_info2($uid, 'email');
$query = mysql_query("SELECT username FROM users WHERE userID=$uid");

if(mysql_num_rows($query) < 1)
{
$pagetitle = "User Not Found";
require('../incfiles/head.php');
functions::display_error('User Not found');
require('../incfiles/end.php');
die();
}

if(isset($_POST["submit"]))
{
$password = $_POST["password"];
if($password == $config->sitePassword)
{
$del = mysql_query("DELETE FROM users WHERE userID='$uid'");
if($del)
{
mysql_query("DELETE FROM bannedusers WHERE username='$user2'");

mysql_query("DELETE FROM moderators WHERE username='$user2'");

mysql_query("DELETE FROM admins WHERE username='$user2'");

mysql_query("DELETE FROM bannedusers WHERE username='$user2'");

mysql_query("DELETE FROM follow WHERE follower='$user2'");

mysql_query("DELETE FROM `notifications` WHERE `to`='$user2'");

mysql_query("DELETE FROM usersfollow WHERE follower='$user2' OR following='$user2'");

$from = str_replace('http://', '', $config->url);
$from = str_replace('/', '', $from);
$from = str_replace('www.', '', $from);

$message = "your $config->title account has been Deactivated \n\n\n\n";
$sent = mail($email, ''.$config->title.'', $message, 'From: Admin@'.$from.'');

$pagetitle = "User Successfully Deleted";
require('../incfiles/head.php');
functions::display_error('User Successfully Deleted');
require('../incfiles/end.php');
die();
} else {
$pagetitle = "Error Deleting User";
require('../incfiles/head.php');
functions::display_error('Error Deleting User');
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

$pagetitle = "Insert Site Password To Delete $user2";
require('../incfiles/head.php');
echo "<p><h2>Insert Site Password To Delete $user2</h2><p><div class='display'><form action='#' method='POST'>
<input type='text' name='password' value=''><br><br><input type='submit' name='submit' class='button' value='submit'></form></div>";

require('../incfiles/end.php');  
die();
}
break;

case 'ban':

$uid = (int)$_GET["uid"];
$user2 = functions::user_info2($uid, 'username');
$query = mysql_query("SELECT username FROM users WHERE userID=$uid");


if(mysql_num_rows($query) < 1)
{
$pagetitle = "User Not Found";
require('../incfiles/head.php');
functions::display_error('User Not found');
require('../incfiles/end.php');
die();
}

$check = mysql_num_rows(mysql_query("SELECT username FROM admins WHERE username='$user2'"));

$check2 = mysql_num_rows(mysql_query("SELECT username FROM moderators WHERE username='$user2'"));

if(($check > 0 || $check2 > 0 ? 1 : 0) > 0 && $access != admin)
{
$pagetitle = "You Cant Ban This User";
require('../incfiles/head.php');
functions::display_error('You Cant Ban This User');
require('../incfiles/end.php');
die();
}


if(isset($_POST["submit"]))
{

$on = $_POST["on"];
$unbandate = $_POST["time"];
$reason = $_POST["reason"];

if(strlen($reason) < 3)
{
$pagetitle = "Your Reason Must Be More Than 5";
require('../incfiles/head.php');
functions::display_error('Your Reason Must Be More Than 5');
require('../incfiles/end.php');
die();
}
$banq = mysql_query("SELECT   username FROM bannedusers WHERE username='$user2' AND boardid=$on");
if(mysql_num_rows($banq)>0)
{
$pagetitle = "User Has Been Banned Already";
require('../incfiles/head.php');
functions::display_error('User Has Been Banned Already');
require('../incfiles/end.php');
die();
}

$reason = functions::cleaninput($reason);
$date = time();

$unbandate = $unbandate * 86400;
$unbandate = time() + $unbandate;

$ban = mysql_query("INSERT INTO bannedusers SET `username`='$user2', `reason`='$reason', `boardid`='$on', `date`='$date', `by`='$user', `unbandate`='$unbandate'");
if($ban)
{
$pagetitle = "User Successfully Banned";
require('../incfiles/head.php');
functions::display_error('User Successfully Banned');
require('../incfiles/end.php');
die();
} else {
$pagetitle = "Error Banning User";
require('../incfiles/head.php');
functions::display_error('Error Banning User');
require('../incfiles/end.php');
die();
}
} else {
$pagetitle = "Ban user: $user2";
require('../incfiles/head.php');
echo "<p><h2>Ban user: $user2</h2><p><div class='display'><form action='#' method='POST'>
<b>Reason:</b><p><textarea rows='9' cols='23' name='reason'></textarea><br>
<b>Choose unBan Time:</b><p><select name='time'>";

for($i = 1; $i<= 31; $i++)
{
echo '<option value="'.$i.'">'.$i.' day(s)</option>';
}
echo "<option value='20000'>life</option></select><br><b>Ban On:</b><p><select name='on'>";

if($access == mod)
{
$qquery = mysql_query("SELECT * FROM boards WHERE id=$modboard");
$bout = NULL;
} else {
$bout = "<option value='0'>On Full Site</option>";
$qquery=mysql_query("SELECT * FROM boards");
}
echo $bout;
while($row = mysql_fetch_assoc($qquery))
{
$cname = $row["name"];
$id = $row["id"];
echo "<option value='$id'>$cname</option>";
}
echo '</select><br><br>';
echo "<input type='submit' name='submit' class='button' value='Ban'></form></div>";

require('../incfiles/end.php');  
die();
}
break;

case 'unban':

$uid = (int)$_GET["uid"];
$user2 = functions::user_info2($uid, 'username');
$query = mysql_query("SELECT username FROM users WHERE userID=$uid");
$query2 = mysql_query("SELECT * FROM bannedusers WHERE `by`='$user' AND `username`='$user2'");

if(mysql_num_rows($query) < 1)
{
$pagetitle = "User Not Found";
require('../incfiles/head.php');
functions::display_error('User Not found');
require('../incfiles/end.php');
die();
}

if(mysql_num_rows($query2) < 1 && $access != admin)
{
$pagetitle = "User Cant Unban User";
require('../incfiles/head.php');
functions::display_error('User Cant Unban User');
require('../incfiles/end.php');
die();
}


if(isset($_POST["submit"]))
{
$on = $_POST["on"];

$query3 = mysql_query("SELECT * FROM bannedusers WHERE `by`='$user' AND `username`='$user2' AND `boardid`='$on'");

if(mysql_num_rows($query3) < 1 && $access != admin)
{
$pagetitle = "User Cant Unban User";
require('../incfiles/head.php');
functions::display_error('User Cant Unban User');
require('../incfiles/end.php');
die();
}

$unban = mysql_query("DELETE FROM bannedusers WHERE username='$user2' AND `by`='$user' AND `boardid`='$on'");

if($unban)
{
$pagetitle = "User Successfully UnBan";
require('../incfiles/head.php');
functions::display_error('User Successfully UnBan');
require('../incfiles/end.php');
die();
} else {
$pagetitle = "Error Unbaning User";
require('../incfiles/head.php');
functions::display_error('Error Unbanning User');
require('../incfiles/end.php');
die();
}

}else {
$pagetitle = "UnBan user: $user2";
require('../incfiles/head.php');
echo "<p><h2>UnBan user: $user2</h2><p><div class='display'><form action='#' method='POST'>
<b>UnBan On:</b><p><select name='on'>";
if($access == admin)
{
$qquery = mysql_query("SELECT * FROM bannedusers WHERE `username`='$user2'");
} else {
$qquery = mysql_query("SELECT * FROM bannedusers WHERE `by`='$user' AND `username`='$user2'");
}
while($qinfo = mysql_fetch_array($qquery))
{
$boardid = $qinfo['boardid'];
if($boardid == 0)
{
echo  "<option value='0'>On Full Site</option>";
} else {
$qquery = mysql_fetch_array(mysql_query("SELECT * FROM boards WHERE id='$boardid'"));
$qqname = $qquery['name'];
$qqid = $qquery['id'];
echo '<option value="'.$qqid.'">'.$qqname.'</option>';
}
}
echo '</select><br><br>';
echo "<input type='submit' name='submit' class='button' value='UnBan'></form></div>";

require('../incfiles/end.php');  
die();
}
break;

case 'rank':

$uid = (int)$_GET["uid"];
$user2 = functions::user_info2($uid, 'username');

$query = mysql_query("SELECT username FROM users WHERE userID=$uid");

$check = mysql_num_rows(mysql_query("SELECT username FROM admins WHERE username='$user2'"));

$check2 = mysql_num_rows(mysql_query("SELECT username FROM moderators WHERE username='$user2'"));

if($access != admin)
{
$pagetitle = "Insufficient Permission";
require('../incfiles/head.php');
functions::display_error('Insufficient Permission');
require('../incfiles/end.php');
die();
}

if(mysql_num_rows($query) < 1)
{
$pagetitle = "User Not Found";
require('../incfiles/head.php');
functions::display_error('User Not found');
require('../incfiles/end.php');
die();
}

if($check > 0 || $check2 >0)
{
$pagetitle = "User Already Ranked Unrank User And Try Again";
require('../incfiles/head.php');
functions::display_error('User Already Ranked Unrank User And Try Again');
require('../incfiles/end.php');
die();
}

if(isset($_POST["submit"]))
{
$on = $_POST["on"];
$date = time();
$key = RAND(1000000, 2000000);
$email = functions::user_info($user2, 'email');

switch($on)
{
case 'admin':
$rank = mysql_query("INSERT INTO `admins` SET `username`='$user2', `time`='$date', `passkeys`='$key', `keydate`='$date'");
$msg = 'You have been rank Admin on this site pls check your email to get your accesskey thanks';
break;
case 'super':
$rank = mysql_query("INSERT INTO `moderators` SET `username`='$user2', `time`='$date', `type`='super', `passkeys`='$key', `keydate`='$date'");
$msg = 'You have been rank Super Moderator on this site pls check your email to get your accesskey thanks';
break;
default:
$rank = mysql_query("INSERT INTO `moderators` SET `username`='$user2', `time`='$date', `boardid`='$on', `type`='board', `passkeys`='$key', `keydate`='$date'");

$bquery = mysql_fetch_array(mysql_query("SELECT `name` FROM `boards` WHERE `id`='$on'"));
$bname = $bquery["name"];
$msg = 'You have been rank '.$bname.' Board Moderator  on this site pls check your email to get your accesskey thanks';
break;
}

if($rank)
{

$notify = mysql_query("INSERT INTO `notifications` SET `to`='$user2', `date`='$date', `message`='$msg', `from`='Site Administration'");
$from = str_replace('http://', '', $config->url);
$from = str_replace('/', '', $from);
$from = str_replace('www.', '', $from);

$message = " Use The Key Below To Access Yor $config->title  Panel:\n\n";
$message .= "KEY: $key";
mail($email, 'Access Key', $message, 'From: Admin@'.$from.'');

$pagetitle = "User Successfully Ranked";
require('../incfiles/head.php');
functions::display_error('User Successfully Ranked');
require('../incfiles/end.php');
die();
} else {
$pagetitle = "Error Ranking User";
require('../incfiles/head.php');
functions::display_error('Error Banning User');
require('../incfiles/end.php');
die();
}
} else {
$pagetitle = "Rank user: $user2";
require('../incfiles/head.php');
echo "<p><h2>Rank user: $user2</h2><p><div class='display'><form action='#' method='POST'><b>Choose Rank:</b><p><select name='on'>";
echo "<option value='admin'>Admin</option><option value='super'>Super Moderator</option>";
$qquery=mysql_query("SELECT * FROM boards");
while($row = mysql_fetch_assoc($qquery))
{
$cname = $row["name"];
$id = $row["id"];
echo "<option value='$id'>$cname</option>";
}
echo '</select><br><br>';
echo "<input type='submit' name='submit' class='button' value='Rank'></form></div>";

require('../incfiles/end.php');  
die();
}
break;
case 'unrank':

if($access != admin)
{
$pagetitle = "Insufficient Permission";
require('../incfiles/head.php');
functions::display_error('Insufficient Permission');
require('../incfiles/end.php');
die();
}

$uid = (int)$_GET["uid"];
$user2 = functions::user_info2($uid, 'username');
$query = mysql_query("SELECT username FROM `users` WHERE `userID`=$uid");

if(mysql_num_rows($query) < 1)
{
$pagetitle = "User Not Found";
require('../incfiles/head.php');
functions::display_error('User Not found');
require('../incfiles/end.php');
die();
}

$check = mysql_num_rows(mysql_query("SELECT `username` FROM `admins` WHERE `username`='$user2'"));

$check2 = mysql_num_rows(mysql_query("SELECT `username` FROM `moderators` WHERE username='$user2'"));
   
if($check < 1 && $check2 < 1)
{
$pagetitle = "You Can't Unrank User";
require('../incfiles/head.php');
functions::display_error("You Can't Unrank User");
require('../incfiles/end.php');
die();
}

if(isset($_POST["submit"]))
{
$verify = $_POST["verify"];
if($verify == yes)
{
$del = mysql_query("DELETE FROM `moderators` WHERE `username`='$user2'");
$del2 = mysql_query("DELETE FROM `admins` WHERE `username`='$user2'");

if($del || $del2)
{
$pagetitle = "User Successfully Unranked";
require('../incfiles/head.php');
functions::display_error('User Successfully Unranked');
require('../incfiles/end.php');
die();
} else {
$pagetitle = "Error Unranking User";
require('../incfiles/head.php');
functions::display_error('Error Unranking User');
require('../incfiles/end.php');
die();
}

} else {
functions::go('/users');
}
} else {
$pagetitle = "Are You Sure You Want To Unrank $user2?";
require('../incfiles/head.php');
echo "<p><h2>Are You Sure You Want To Unrank $user2?</h2><p><div class='display'><form action='#' method='POST'>
<input type='text' name='verify' value='yes'><br><br><input type='submit' name='submit' class='button' value='Unrank'></form></div>";

require('../incfiles/end.php');  
die();
}
break;

case 'notify':

if($access != admin)
{
$pagetitle = "Insufficient Permission";
require('../incfiles/head.php');
functions::display_error('Insufficient Permission');
require('../incfiles/end.php');
die();
}

$uid = (int)$_GET["uid"];
$user2 = functions::user_info2($uid, 'username');
$query = mysql_query("SELECT username FROM users WHERE userID=$uid");

if(mysql_num_rows($query) < 1)
{
$pagetitle = "User Not Found";
require('../incfiles/head.php');
functions::display_error('User Not found');
require('../incfiles/end.php');
die();
}

if(isset($_POST["submit"]))
{
$msg = functions::cleaninput($_POST["message"]);
$date = time();
if(strlen($msg) < 5)
{
$pagetitle = "Your Message Must Be More Than 5";
require('../incfiles/head.php');
functions::display_error('Your Message Must Be More Than 5');
require('../incfiles/end.php');
die();
}


$notify = mysql_query("INSERT INTO `notifications` SET `to`='$user2', `date`='$date', `message`='$msg', `from`='Site Administration'");

if($notify)
{
$pagetitle = "User Successfully Notified";
require('../incfiles/head.php');
functions::display_error('User Successfully Notify');
require('../incfiles/end.php');
die();
} else {
$pagetitle = "Error Notifying User";
require('../incfiles/head.php');
functions::display_error('Error Notifying User');
require('../incfiles/end.php');
die();
}

} else {


$pagetitle = "Notify user: $user2";
require('../incfiles/head.php');
echo "<p><h2>Notify user: $user2</h2><p><div class='display'><form action='#' method='POST'>
<b>Message:</b><p><textarea rows='9' cols='23' name='message'></textarea><br><br><input type='submit' name='submit' class='button' value='Notify'></form></div>";

require('../incfiles/end.php');  
die();
}

break;

case 'globalnotification':

if(isset($_POST["submit"]))
{
$msg = functions::cleaninput($_POST["message"]);
$receiver = $_POST["receiver"];
$date = time();
if(strlen($msg) < 5)
{
$pagetitle = "Your Message Must Be More Than 5";
require('../incfiles/head.php');
functions::display_error('Your Message Must Be More Than 5');
require('../incfiles/end.php');
die();
}

switch($receiver)
{
case 'all':

$query = mysql_query("SELECT `username` FROM `users`");
while($info = mysql_fetch_array($query))
{
$username = $info["username"];
mysql_query("INSERT INTO `notifications` SET `to`='$username', `date`='$date', `message`='$msg', `from`='Site Administration'") or die(mysql_error());
}

break;

case 'users+mods':
$query = mysql_query("SELECT `username` FROM `users`");
while($info = mysql_fetch_array($query))
{
$username = $info["username"];

$check = mysql_num_rows(mysql_query("SELECT `username` FROM `admins` WHERE `username`='$username'"));
if($check < 1)
{
mysql_query("INSERT INTO `notifications` SET `to`='$username', `date`='$date', `message`='$msg', `from`='Site Administration'") or die(mysql_error());
}
}

break;

case 'users':
$query = mysql_query("SELECT `username` FROM `users`");
while($info = mysql_fetch_array($query))
{
$username = $info["username"];

$check = mysql_num_rows(mysql_query("SELECT `username` FROM `admins` WHERE `username`='$username'"));


$check2 = mysql_num_rows(mysql_query("SELECT `username` FROM `moderators` WHERE `username`='$username'"));

if($check < 1 && $check2 < 1)
{
mysql_query("INSERT INTO `notifications` SET `to`='$username', `date`='$date', `message`='$msg', `from`='Site Administration'") or die(mysql_error());
}
}
break;

case 'mods':
$query = mysql_query("SELECT `username` FROM `moderators`");
while($info = mysql_fetch_array($query))
{
$username = $info["username"];
mysql_query("INSERT INTO `notifications` SET `to`='$username', `date`='$date', `message`='$msg', `from`='Site Administration'") or die(mysql_error());
}
break;

case 'admins':

$query = mysql_query("SELECT `username` FROM `admins`");
while($info = mysql_fetch_array($query))
{
$username = $info["username"];
mysql_query("INSERT INTO `notifications` SET `to`='$username', `date`='$date', `message`='$msg', `from`='Site Administration'") or die(mysql_error());
}

break;
case 'admins+mods':
$query = mysql_query("SELECT * FROM `admins`, `moderators`");
while($info = mysql_fetch_array($query))
{
$username = $info["username"];
mysql_query("INSERT INTO `notifications` SET `to`='$username', `date`='$date', `message`='$msg', `from`='Site Administration'") or die(mysql_error());
}
break;

default:

$pagetitle = "Error";
require('../incfiles/head.php');
functions::display_error('Error');
require('../incfiles/end.php');
die();
break;
}

$pagetitle = "Global Notification Successfully Sent";
require('../incfiles/head.php');
functions::display_error('Global Notification Successfully Sent');
require('../incfiles/end.php');
die();
} else {


$pagetitle = "Send Global Notification";
require('../incfiles/head.php');
echo "<p><h2>Send Global Notification</h2><p><div class='display'><form action='#' method='POST'>
<b>Message:</b><p><textarea rows='9' cols='23' name='message'></textarea><br>
<b>Select Receiver:</b><p><select name='receiver'>
<option value='users'>Users Only</option>
<option value='users+mods'>Users And Mods</option>
<option value='mods'>Mods Only</option>
<option value='admins'>Admins Only</option>
<option value='admins+mods'>Admins And Mods</option>
<option value='all'>All</option>   
</select><br><br><input type='submit' name='submit' class='button' value='Send'></form></div>";

require('../incfiles/end.php');  
die();

}

break;

case 'email':

if($access != admin)
{
$pagetitle = "Insufficient Permission";
require('../incfiles/head.php');
functions::display_error('Insufficient Permission');
require('../incfiles/end.php');
die();
}

$uid = (int)$_GET["uid"];
$user2 = functions::user_info2($uid, 'username');
$email = functions::user_info2($uid, 'email');

$query = mysql_query("SELECT username FROM users WHERE userID=$uid");

if(mysql_num_rows($query) < 1)
{
$pagetitle = "User Not Found";
require('../incfiles/head.php');
functions::display_error('User Not found');
require('../incfiles/end.php');
die();
}

if(isset($_POST["submit"]))
{
$msg = $_POST["message"];
if(strlen($msg) < 5)
{
$pagetitle = "Your Message Must Be More Than 5";
require('../incfiles/head.php');
functions::display_error('Your Message Must Be More Than 5');
require('../incfiles/end.php');
die();
}
$from = str_replace('http://', '', $config->url);
$from = str_replace('/', '', $from);
$from = str_replace('www.', '', $from);

$headers = 'MIME-Version: 1.0'."\r\n";
$headers .= 'Content-type:text/html;charset=iso-8859-1'."\r\n";
$headers .= "From: Admin@$from"."\r\n";
$sent = mail($email, 'Administration Msg', $msg, $headers);

if($sent)
{
$pagetitle = "User Successfully E-mailed";
require('../incfiles/head.php');
functions::display_error('User Successfully E-mailed');
require('../incfiles/end.php');
die();
} else {
$pagetitle = "Error Emailing User";
require('../incfiles/head.php');
functions::display_error('Error Emailing User');
require('../incfiles/end.php');
die();
}

} else {

$pagetitle = "E-mail user: $user2";
require('../incfiles/head.php');
echo "<p><h2>E-mail user: $user2</h2><p><div class='display'><form action='#' method='POST'>
<b>Message:</b><p><textarea rows='9' cols='23' name='message'></textarea><br><br><input type='submit' name='submit' class='button' value='send'></form></div>";

require('../incfiles/end.php');  
die();
}

break;

case 'globalemail':

if(isset($_POST["submit"]))
{
$msg = $_POST["message"];
$receiver = $_POST["receiver"];
$from = str_replace('http://', '', $config->url);
$from = str_replace('/', '', $from);
$from = str_replace('www.', '', $from);

$headers = 'MIME-Version: 1.0'."\r\n";
$headers .= 'Content-type:text/html;charset=iso-8859-1'."\r\n";
$headers .= "From: Admin@$from"."\r\n";
if(strlen($msg) < 5)
{
$pagetitle = "Your Message Must Be More Than 5";
require('../incfiles/head.php');
functions::display_error('Your Message Must Be More Than 5');
require('../incfiles/end.php');
die();
}

switch($receiver)
{
case 'all':

$query = mysql_query("SELECT `email` FROM `users`");
while($info = mysql_fetch_array($query))
{
$email = $info["email"];

mail($email, 'Administration Msg', $msg, $headers);
}

break;

case 'users+mods':
$query = mysql_query("SELECT `username`,`email` FROM `users`");
while($info = mysql_fetch_array($query))
{
$username = $info["username"];

$check = mysql_num_rows(mysql_query("SELECT `username` FROM `admins` WHERE `username`='$username'"));
if($check < 1)
{
$email = $info["email"];

mail($email, 'Administration Msg', $msg, $headers);
}
}

break;

case 'users':
$query = mysql_query("SELECT `username`,`email` FROM `users`");
while($info = mysql_fetch_array($query))
{
$username = $info["username"];

$check = mysql_num_rows(mysql_query("SELECT `username` FROM `admins` WHERE `username`='$username'"));


$check2 = mysql_num_rows(mysql_query("SELECT `username` FROM `moderators` WHERE `username`='$username'"));

if($check < 1 && $check2 < 1)
{
$email = $info["email"];

mail($email, 'Administration Msg', $msg, $headers);
}
}
break;

case 'mods':
$query = mysql_query("SELECT `username` FROM `moderators`");
while($info = mysql_fetch_array($query))
{
$username = $info["username"];
$email = functions::user_info($username, 'email');

mail($email, 'Administration Msg', $msg, $headers);
}
break;

case 'admins':

$query = mysql_query("SELECT `username` FROM `admins`");
while($info = mysql_fetch_array($query))
{
$username = $info["username"];
$email = functions::user_info($username, 'email');

mail($email, 'Administration Msg', $msg, $headers);
}

break;
case 'admins+mods':
$query = mysql_query("SELECT * FROM `admins`, `moderators`");
while($info = mysql_fetch_array($query))
{
$username = $info["username"];
$email = functions::user_info($username, 'email');

mail($email, 'Administration Msg', $msg, $headers);
}
break;
default:

$pagetitle = "Error";
require('../incfiles/head.php');
functions::display_error('Error');
require('../incfiles/end.php');
die();
break;
}

$pagetitle = "Global E-mail Successfully Sent";
require('../incfiles/head.php');
functions::display_error('Global E-mail Successfully Sent');
require('../incfiles/end.php');
die();
} else {


$pagetitle = "Send Global E-mail";
require('../incfiles/head.php');
echo "<p><h2>Send Global E-mail</h2><p><div class='display'><form action='#' method='POST'>
<b>Message:</b><p><textarea rows='9' cols='23' name='message'></textarea><br>
<b>Select Receiver:</b><p><select name='receiver'>
<option value='users'>Users Only</option>
<option value='users+mods'>Users And Mods</option>
<option value='mods'>Mods Only</option>
<option value='admins'>Admins Only</option>
<option value='admins+mods'>Admins And Mods</option>
<option value='all'>All</option>   
</select><br><br><input type='submit' name='submit' class='button' value='Send'></form></div>";

require('../incfiles/end.php');  
die();

}

break;

case 'emailinactiveusers':

if(isset($_POST["submit"]))
{
$msg = $_POST["message"];
$ctime = time() - (2592000 * $_POST["receiver"]);  
$from = str_replace('http://', '', $config->url);
$from = str_replace('/', '', $from);
$from = str_replace('www.', '', $from);

$headers = 'MIME-Version: 1.0'."\r\n";
$headers .= 'Content-type:text/html;charset=iso-8859-1'."\r\n";
$headers .= "From: Admin@$from"."\r\n";
if(strlen($msg) < 5)
{
$pagetitle = "Your Message Must Be More Than 5";
require('../incfiles/head.php');
functions::display_error('Your Message Must Be More Than 5');
require('../incfiles/end.php');
die();
}

$query = mysql_query("SELECT `email` FROM `users` WHERE `lasttime`<'$ctime'");
while($info = mysql_fetch_array($query))
{
$email = $info["email"];
mail($email, 'Administration Msg', $msg, $headers);
}

$pagetitle = "Global Email Successfully Sent To Inactive Members";
require('../incfiles/head.php');
functions::display_error('Global Email Successfully Sent To Inactive Members');
require('../incfiles/end.php');
die();
} else {


$pagetitle = "Send Global Email To Inactive Members";
require('../incfiles/head.php');
echo "<p><h2>Send Global Email To Inactive Members</h2><p><div class='display'><form action='#' method='POST'>
<b>Message:</b><p><textarea rows='9' cols='23' name='message'></textarea><br>
<b>Select Receiver:</b><p><select name='receiver'>";
for($i = 1; $i<= 12; $i++)
{
echo '<option value="'.$i.'">'.$i.' months(s) upward</option>';
}
echo "</select><br><br><input type='submit' name='submit' class='button' value='Send'></form></div>";
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
$pagetitle = 'Manage Users';
require('../incfiles/head.php');
echo '<a name="top"></a><p><h2>Manage Users</h2><p><a href="/">'.$config->title.' Forum</a> / <a href="/panel/">Panel</a> / <a href="#">Manage Users</a><p>';

$search_post = isset($_POST['q']) ? trim($_POST['q']) : false;
$search_get = isset($_GET['q']) ? rawurldecode(trim($_GET['q'])) : false;
$search = $search_post ? $search_post : ($search_get ? $search_get : false) ;

echo '<div class="display"><form action="/panel/users" method="post">
<input type="text" value="' . ($search ? functions::checkout($search) : '') . '" name="q" /><br><br><input type="submit" value="search" name="search" /></form></div>';

$rowsperpage = 20;
if(isset($_GET["page"]) && is_numeric($_GET["page"]))
{
$page = (int)$_GET["page"];
}
else
{
$page = 1;
}

if($search)
{
$search = mysql_real_escape_string($search);
$numrows = mysql_num_rows(mysql_query("SELECT `username`,`userID` FROM `users` WHERE MATCH (`username`) AGAINST('$search' IN BOOLEAN MODE)"));
} else {
$numrows = mysql_num_rows(mysql_query("SELECT `username`,`userID` FROM `users`"));
}

$totalpages = ceil($numrows/$rowsperpage);

if($page > $totalpages)
{
$page = $totalpages;
}

$offset = ($page-1)*$rowsperpage;

if($numrows < 1)
{
echo '<div class="display">User Not Found</div>';

} else {

if($search)
{
$search = mysql_real_escape_string($search);
$query = mysql_query("SELECT `username`,`userID` FROM `users` WHERE MATCH (`username`) AGAINST('$search' IN BOOLEAN MODE) LIMIT $offset, $rowsperpage");
} else {
$query = mysql_query("SELECT `username`,`userID` FROM `users` LIMIT $offset, $rowsperpage");
}

$pagination= new pagination($rowsperpage, $page, '/panel/users?page=(page)'.($search ? '&q='.functions::checkout($search).'' : '').'', $numrows);

include"../ads.php";
echo $pagination->display() ;
echo "<table>";
$i = 0;
while($info=mysql_fetch_array($query)) { 
$username = functions::cleanoutput($info["username"]);
$uid = functions::cleanoutput($info["userID"]);

$check = mysql_num_rows(mysql_query("SELECT `username` FROM `admins` WHERE `username`='$username'"));

$check2 = mysql_num_rows(mysql_query("SELECT `username` FROM `moderators` WHERE `username`='$username'"));

$check3 = mysql_num_rows(mysql_query("SELECT * FROM `bannedusers` WHERE `by`='$user' AND `username`='$username'"));

if($i%2 == 0)
{
$css = 'w';
} else {
$css = '';
}
echo '<tr><td class="'.$css.'">'.functions::user_link($username).'<p>';

if($access == 'admin')
{
echo '<a href="?action=delete&uid='.$uid.'">del</a> - <a href="?action=notify&uid='.$uid.'">notify</a> - <a href="?action=email&uid='.$uid.'">email</a>'; 
if($check > 0 || $check2 >0)
{
echo ' - <a href="?action=unrank&uid='.$uid.'">UnRank</a>';
} else {
echo ' - <a href="?action=rank&uid='.$uid.'">Rank</a>';
}

}

if($check > 0 && $access != 'admin')
{
echo '';
} else {

if($access == 'admin' || $access== supermod)
{
if($check3 > 0)
{
echo ' - <a href="?action=unban&uid='.$uid.'">UnBan</a>';
} 
echo ' - <a href="?action=ban&uid='.$uid.'">Ban</a>';
} else {

if($check3 > 0)
{
echo '  <a href="?action=unban&uid='.$uid.'">UnBan</a>';
} else {
echo '  <a href="?action=ban&uid='.$uid.'">Ban</a>';
}

}

}
$i ++;
}
echo '</table>
'.$pagination->display().'';
include"../ads.php";
}
require('../incfiles/end.php');
}

?>