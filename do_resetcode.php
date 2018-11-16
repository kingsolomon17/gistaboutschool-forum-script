<?php
require('incfiles/init.php');

if(functions::isloggedin())
{
functions::go($config->url);
die();
}

if($_POST["submit"])
{
$email = mysql_real_escape_string($_POST["email"]);

if(!functions::check_email($email))
{
$pagetitle = "Post Not Found";
require('incfiles/head.php');
functions::display_error('invalid email address entered');
require('incfiles/end.php');
die();
}

$checkquery = mysql_query("SELECT `email` FROM `users` WHERE `email` = '$email'");

if(mysql_num_rows($checkquery) == 0)
{
$pagetitle = "User With This Email (".$email.") not found";
require('incfiles/head.php');
functions::display_error('User With This Email ('.$email.') not found');
require('incfiles/end.php');
die();
}

$key = $email . date('mY');
$key = mysql_real_escape_string(md5($key));

mysql_query("UPDATE `users` SET `key`='$key' WHERE `email`='$email'") or die(mysql_error());

 // Send the email:
$from = str_replace('http://', '', $config->url);
$from = str_replace('/', '', $from);
$from = str_replace('www.', '', $from);

$message = " To Reset your $config->title account Password, please click on this link:\n\n";
$message .= $config->url . 'resetcode?email=' . urlencode($email) . "&key=$key \n\n";
$sent = mail($email, 'Reset Password Confirmation', $message, 'From: admin@'.$from.'');

if($sent)
{
$pagetitle = "Please Check Your E-Mail";
require('incfiles/head.php');
functions::display_error('Please Check Your E-Mail</h2><p>We have just sent you an e-
mail To Reset Your Password.<p>Please <b>check your inbox</b> to
find the e-mail and <b>Complete Resetting Your Password</b>.<p>Please <b>check your
spam folder</b> if you cannot find it in your
inbox.');
require('incfiles/end.php');
} else {
$pagetitle = "You could not reset your password due to a system
error. We apologize for any
inconvenience.";
require('incfiles/head.php');
functions::display_error('You could not reset your password due to a system
error. We apologize for any
inconvenience.');
require('incfiles/end.php');
}

} else {

$pagetitle = "Error";
require('incfiles/head.php');
functions::display_error('Error');
require('incfiles/end.php');
}
?>