<?php
require('../incfiles/init.php');

if(!functions::isloggedin())
{
$pagetitle = "You Must Be LoggedIn To Proceed";
require('../incfiles/head.php');
functions::display_error('You Must Be LoggedIn To Proceed');
require('../incfiles/end.php');
die();
}


$modcheck = mysql_query("SELECT * FROM moderators WHERE username = '$user'");


$admincheck = mysql_query("SELECT * FROM admins WHERE username = '$user'");

if(mysql_num_rows($admincheck) < 1 && mysql_num_rows($modcheck) < 1)
{
$pagetitle = "Insufficient Permission";
require('../incfiles/head.php');
functions::display_error('Insufficient Permission');
require('../incfiles/end.php');
die();
}

$password = $_POST["passkeys"];
$redirect = $_POST["redirect"];
$password = functions::cleaninput($password);
$from = mysql_num_rows($admincheck) > 0 ? 'admins' : 'moderators' ;

$checkquery = mysql_query("SELECT username, passkeys FROM $from WHERE username='$user' AND passkeys='$password'") or die(mysql_error());

if(mysql_num_rows($checkquery) < 1)
{

$pagetitle = "incorrect passkeys, make sure you type your passkeys Correctly !";
require('../incfiles/head.php');
functions::display_error("incorrect passkeys, make sure you type your passkeys Correctly !<br/><a href='javascript:history.go(-1)'>Go back</a>");
require('../incfiles/end.php');
die();
}

$recent = date("U")-604800;
if(mysql_num_rows($admincheck) > 0) 
{
$info = mysql_fetch_array($admincheck);
$keydate = $info["keydate"];

if($keydate < $recent)
{
$key = RAND(1000000, 2000000);
$email = functions::user_info($user, 'email');
$time = time();
$done = mysql_query("UPDATE $from SET passkeys='$key', keydate='$time' WHERE username='$user'");
$from = str_replace('http://', '', $config->url);
$from = str_replace('/', '', $from);
$from = str_replace('www.', '', $from);

$message = " Use The Key Below To Access Yor $config->title $from Panel:\n\n";
$message .= "KEY: $key";
$sent = mail($email, 'Access Key', $message, 'From: admin@'.$from.'');
if(isset($sent) && isset($done))
{
$pagetitle = "Your Passkey Has Expired We Have Sent You A New Passkey";
require('../incfiles/head.php');
echo '<p><h2>Please Check Your E-Mail</h2><p>We have just sent you an e-
mail.<p>Please <b>check your inbox</b> to
find the e-mail and <b>copy your passkey</b>.<p>Please <b>check your
spam folder</b> if you cannot find it in your
inbox.';
require('../incfiles/end.php');
die();

} else {
$pagetitle = "We Couldn't reset your passkey due to a system
error. We apologize for any
inconvenience.";
require('../incfiles/head.php');
echo "<p><h2>We Couldn't reset your passkey due to a system
error. We apologize for any
inconvenience.</h2><p>";
require('../incfiles/end.php');
die();

}

}


$_SESSION["access"] = 'admin';
} else {

$info = mysql_fetch_array($modcheck);
$type = $info["type"];
$keydate = $info["keydate"];
if($keydate < $recent)
{
$key = RAND(1000000, 2000000);
$email = functions::user_info($user, 'email');
$time = time();
$done = mysql_query("UPDATE $from SET passkeys='$key', keydate='$time' WHERE username='$user'");
$from = str_replace('http://', '', $config->url);
$from = str_replace('/', '', $from);
$from = str_replace('www.', '', $from);

$message = " Use The Key Below To Access Yor $config->title $from Panel:\n\n";
$message .= "KEY: $key";
$sent = mail($email, 'Access Key', $message, 'From: Admin@'.$from.'');
if(isset($sent) && isset($done))
{
$pagetitle = "Your Passkey Has Expired We Have Sent You A New Passkey";
require('../incfiles/head.php');
echo '<p><h2>Please Check Your E-Mail</h2><p>We have just sent you an e-
mail.<p>Please <b>check your inbox</b> to
find the e-mail and <b>copy your passkey</b>.<p>Please <b>check your
spam folder</b> if you cannot find it in your
inbox.';
require('../incfiles/end.php');
die();

} else {
$pagetitle = "We Couldn't reset your passkey due to a system
error. We apologize for any
inconvenience.";
require('../incfiles/head.php');
echo "<p><h2>We Couldn't reset your passkey due to a system
error. We apologize for any
inconvenience.</h2><p>";
require('../incfiles/end.php');
die();

}

}

if($type == super)
{
$_SESSION["access"] = 'supermod';
} else {

$_SESSION["access"] = 'mod';
}
}

if(empty($redirect))
{
functions::go($config->url);
} else {
functions::go('http://'.$redirect);
}
die();
?>