<?php
require('incfiles/init.php');
$pagetitle = "Registration Form";
require('incfiles/head.php'); 
if(functions::isloggedin())
{
functions::go($config->url);
die();
}

$email = $_SESSION["regemail"];
if(!$email)
{
functions::go("".$config->url."confirm");
} else {

echo ''.$email.'<div class="display"><form action="/do_register" method="post"><br><b>Please Dont add space in your username</b><br><br><b>Username:</b><br><input size="20" type="text" name="username" value=""><br><b>Password:</b> (6 - 20 char)<br><input size="20" type="password" name="password" value=""><br><b>Confirm Password:</b><br><input type="password" size="17" name="verify_password" value=""><br><br><input type="submit" name="submit" value="Sign Up"></form>';
echo '</div>';
}
Include('incfiles/end.php');
?>