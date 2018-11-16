<?php
require('incfiles/init.php');

if(!functions::isloggedin())
{
require('incfiles/head.php');
functions::display_error('You Must Logging To Logout');
require('incfiles/end.php');
die();
}


$user=$_SESSION["user"];
mysql_query("UPDATE users SET lasttime=0 WHERE username='$user'");
unset($_SESSION["user"]);
session_destroy();
setcookie("user", "", time()-86400);
setcookie("password", "", time()-86400);
header("location: /");
exit();

?>