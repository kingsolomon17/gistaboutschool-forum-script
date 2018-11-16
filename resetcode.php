<?php
require('incfiles/init.php');
if(functions::isloggedin())
{
functions::go($config->url);
die();
}

if(isset($_GET['email']) && functions::check_email($_GET['email']))
{
$email = $_GET['email'];
}
if(isset($_GET['key']))
{
$key = $_GET['key'];
}

if(isset($email) && !empty($key))
{
$email = functions::cleaninput($email);
$key = functions::cleaninput($key);
//Check if email exist

$checkquery = mysql_query("SELECT `email`,`key` FROM `users` WHERE `email` = '$email' AND `key`='$key'");

if(mysql_num_rows($checkquery) > 0)
{

if($_POST['submit'])
{
$password = md5(functions::cleaninput($_POST['password']));
$verify_password = md5(functions::cleaninput($_POST['verify_password']));

if(!$_POST['password'] || !$_POST['verify_password'])
{
$pagetitle = "Error! you have not entered all fields!";
require('incfiles/head.php');
echo '<div class="display">Error! you have not entered all fields! <br/><a href="javascript:history.go(-1)">Go back</a></div>';
require('incfiles/end.php');
die();
}
if($password != $verify_password)
{
$pagetitle = "Passwords does not match!";
require('incfiles/head.php');
echo '<div class="display">Passwords does not match! <br/><a href="javascript:history.go(-1)">Go back</a></div>';
require('incfiles/end.php');
die();
}

if(strlen($_POST['password'])<5 || strlen($_POST['password'])>20)
{
$pagetitle = "Password must be between 5 - 20 characters!";
require('incfiles/head.php');
echo '<div class="display">Password must be between 5 - 20 characters! <br/><a href="javascript:history.go(-1)">Go back</a></div>';
require('incfiles/end.php');
die();
}

mysql_query("UPDATE `users` SET `password`='$password', `key`='' WHERE `email`='$email'") or die(mysql_error());

$from = str_replace('http://', '', $config->url);
$from = str_replace('/', '', $from);
$from = str_replace('www.', '', $from);
$message = "your $config->title account Password Has Been Successfully Reset:\n\n";
mail($email, 'Password Succssessfully Reset', $message, 'From: admin@'.$from.'');

$pagetitle = "Passwords Successfully Reset";
require('incfiles/head.php');
echo '<div class="display">Passwords Successfully Reset <br/><a href="/login">Click To Login</a></div>';
require('incfiles/end.php');
die();

} else {
$pagetitle = "Reset Password";
require('incfiles/head.php');
echo '<a name="top"></a><p><h2>Fill The Form Below To Reset Password</h2>
 <p><div class="display"><form action="#" method="post"><br><b>New Password:</b> (6 - 20 char)<br><input size="20" type="password" name="password" value=""><br><b>Confirm New Password:</b><br><input type="password" size="17" name="verify_password" value=""><br><br><input type="submit" name="submit" value="Reset"></form></div>';
require('incfiles/end.php');
die();
}

} else {
$pagetitle = "Invalid Confirmation Keys";
require('incfiles/head.php');
 echo "<div class='msg'>Invalid Confirmation Keys</div>";
require('incfiles/end.php');
die();
}

} else {
$pagetitle = "Reset Password";
require('incfiles/head.php');
echo '<a name="top"></a><p><h2>Verify Email to Reset Your Password</h2>
 <p>
<div class="display"> Before you can reset your password, we need to
verify your email address. Please enter it
below:<br><form method="POST" action="/do_resetcode">Email: <input name="email"
type="text"> <input type="submit" name="submit" value="Send
Email"></form></div>';
}
require('incfiles/end.php');

?>