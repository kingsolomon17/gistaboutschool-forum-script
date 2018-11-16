<?php
require('incfiles/init.php');
$pagetitle = "Verify Email";
require('incfiles/head.php');
if(functions::isloggedin())
{
functions::go($config->url);
die();
}

if(isset($_GET['email']) && preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', $_GET['email']))
{
$email = $_GET['email'];
}
if(isset($_GET['key']))
{
$key = $_GET['key'];
}

if(isset($email) && isset($key))
{
$email = functions::cleaninput($email);
$key = functions::cleaninput($key);
//Check if email exist

$checkquery = mysql_query("SELECT * FROM confirm WHERE email = '$email'");

if(mysql_num_rows($checkquery) > 0)
{

$info = mysql_fetch_array($checkquery);
$key4 = $info["key"];
if($key == $key4)
{

//Check if email has already been validated
$checkemail=mysql_query("SELECT * FROM users WHERE email='$email'");
if(mysql_num_rows($checkemail)>0)
{
echo "<div class='msg'>This email has already been validated</div>";
}
else
{
$_SESSION["regemail"]=$email;
if($_SESSION["regemail"])
{
header("location: register");
exit();
}
else
{
$msg="Oops ! An error occured , please try again later";
echo "<div class='msg'>$msg</div>";
}
}
} else {
 echo "<div class='msg'>Invalid Confirmation Keys</div>";
}

} else {
echo "<div class='msg'>Invalid Confirmation Email</div>";
}

}



 else {
echo '<a name="top"></a><p><h2>Verify Email to Join '.$config->title.'</h2>
 <p>
<div class="display"> Before you can join '.$config->title.' , we need to
verify your email address. Please enter it
below:<br><form method="POST" action="/do_confirm">Email: <input name="email"
type="text"> (Please note that Hotmail email
addresses dont work)<br><input type="submit" name="submit" value="Send
Email"></form></div>';
}
require('incfiles/end.php');

?>