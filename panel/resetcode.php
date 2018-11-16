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


$modcheck = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user'"));


$admincheck = mysql_num_rows(mysql_query("SELECT * FROM admins WHERE username = '$user'"));

if($admincheck < 1 && $modcheck < 1)
{
$pagetitle = "Insufficient Permission";
require('../incfiles/head.php');
functions::display_error('Insufficient Permission');
require('../incfiles/end.php');
die();
}



if(isset($_POST["submit"]))
{
$verify = $_POST["verify"];
if($verify == yes)
{

$key = RAND(1000000, 2000000);

$from = $admincheck > 0 ? 'admins' : 'moderators' ;

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
$pagetitle = "Your Passkeys Has Reset successful";
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

} else {
header("location: /panel");
exit();
}
}  else {
$pagetitle = "Are You Sure You Want To Reset Passkey?";
require('../incfiles/head.php');
echo "<p><h2>Are You Sure You Want To Reset Passkey?</h2><p><div class='display'>'<form action='#' method='POST'>
<input type='text' name='verify' value='yes'><br><input type='submit' name='submit' class='button' value='Submit'></form></div>";

require('../incfiles/end.php');
}
?>