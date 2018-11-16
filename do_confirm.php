<?php
require('incfiles/init.php');
require('incfiles/head.php');

if(functions::isloggedin())
{
functions::go($config->url);
die();
}

if($_POST["submit"])
{
$errors = array();
$email = mysql_real_escape_string($_POST["email"]);

if(!functions::check_email($email))
{
$errors[] = "invalid email address entered";
}

$checkemail = mysql_query("SELECT * FROM users WHERE email='$email'");

$checkemail2=mysql_num_rows(mysql_query("SELECT * FROM confirm WHERE email='$email'"));

if(mysql_num_rows($checkemail)>0)
{
$errors[] = "Email already exist";
}

if(count($errors) == 0)
{
//create a random key
$key = $email . date('mY');
$key = mysql_real_escape_string(md5($key));
$key2 = RAND(1000000, 2000000);
$date = time();

if($checkemail2 > 0)
{
$info = mysql_fetch_array(mysql_query("SELECT * FROM confirm WHERE email='$email'"));
$key = $info["key"];
$done = mysql_query("UPDATE confirm SET email='$email', `date`='$date', `key`='$key' WHERE email='$email'") or die(mysql_error());
} else {
mysql_query("INSERT INTO confirm SET `date`='$date', `email`='$email', `key`='$key'") or die(mysql_error());

}

 // Send the email:
$message = " To activate your $config->title account, please click on this link:\n\n";
$message .= $config->url . 'confirm?email=' . urlencode($email) . "&key=$key \n\n";
$sent = mail($email, 'Registration Confirmation', $message, 'From: admin@gistaboutschool.com');

if($sent)
{
echo '<a name="top"></a><p><h2>Please Check Your E-Mail</h2><p>We have just sent you an e-
mail.<p>Please <b>check your inbox</b> to
find the e-mail and <b>complete your
registration</b>.<p>Please <b>check your
spam folder</b> if you cannot find it in your
inbox. <p>Welcome to '.$config->title.'!<p>';
} else {
echo '<a name="top"></a><p><h2>You could not be registered due to a system
error. We apologize for any
inconvenience.</h2><p>';
}
} else {
foreach($errors as $error)
{
$erro.="$error<br>";
}
echo "<p><h2>$erro</h2><p>";
}
} else {
functions::display_error('Error');
}
require('incfiles/end.php');
?>