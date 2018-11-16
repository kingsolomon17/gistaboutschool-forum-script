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
if($_POST['submit'])
{
$password = md5(functions::cleaninput($_POST['password']));
$verify_password = md5(functions::cleaninput($_POST['verify_password']));
$username = functions::cleaninput($_POST['username']);
$email = functions::cleaninput($_SESSION["regemail"]);
if(strlen($_POST['username'])<3 || strlen($_POST['username'])>20 ) {
echo '<div class="display">Error! Username must be between 4 - 20 characters<br/><a href="javascript:history.go(-1)">Go back</a></div>'; }
elseif ( mysql_num_rows(mysql_query("SELECT username FROM users WHERE username='$username'"))>0 || mysql_num_rows(mysql_query("SELECT * FROM boards WHERE url='$username'"))>0 || file_exists($username) || file_exists($username.'.php'))
{ echo '<div class="display">This Username already Exist! <br/><a href="javascript:history.go(-1)">Go back</a></div>'; }
elseif (mysql_num_rows(mysql_query("SELECT email FROM users WHERE email='$email'"))>0)
{
echo '<div class="display">This email is already in use, Please choose another email! <br/><a href="javascript:history.go(-1)">Go back</a></div>';
}
elseif(!preg_match("^[A-Za-z0-9]+$^", "$username"))
{ echo '<div class="display">Username contain invalid characters! <br/><a href="javascript:history.go(-1)">Go back</a></div>'; }
elseif ( ! $_POST['password'] || ! $_POST['verify_password'])
{
echo '<div class="display">Error! you have not entered all fields! <br/><a href="javascript:history.go(-1)">Go back</a></div>';
}
elseif (!functions::check_email($email))
{
echo '<div class="display">Invalid Email! <br/><a href="javascript:history.go(-1)">Go back</a></div>';
}
elseif ( $password != $verify_password )
{
echo '<div class="display">Passwords does not match! <br/><a href="javascript:history.go(-1)">Go back</a></div>';
}
elseif (strlen($_POST['password'])<5 || strlen($_POST['password'])>20 )
{
echo '<div class="display">Password must be between 5 - 20 characters! <br/><a href="javascript:history.go(-1)">Go back</a></div>';
}

else{
@$a=mysql_query("INSERT INTO users (username, email, password,regtime) VALUES ('$username', '$email', '$password', '$time')");
if ($a){
mysql_query("DELETE FROM confirm WHERE email='$email'");
unset($_SESSION["regemail"]);
session_destroy();
echo "<div class='display'>Your account has been created succesfully.<br/><br/><a href='".$config->url."login'>Click here to login</a></div>";
} else {
echo '<div class="display">There was an error in the registration process, please contact the site administrator</div>';
}
}} else {

functions::display_error('Error');

}
}

require('incfiles/end.php');
?>