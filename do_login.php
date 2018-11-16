<?php
require('incfiles/init.php');
if(functions::isloggedin())
{
if(empty($redirect))
{
functions::go($config->url);
} else {
functions::go('http://'.$redirect);
}
die();
}


if(isset($_POST["submit"])) {
//STEP DOWN VALUES
$username=$_POST["username"];
$password=$_POST["password"];
$redirect=$_POST["redirect"];
//CLEANUP VALUES
$username=functions::cleaninput($username);
$password=functions::cleaninput($password);
$password=md5($password);
$checkquery=mysql_query("SELECT username, password FROM users WHERE username='$username' AND password='$password'") or die(mysql_error());
$num=mysql_num_rows($checkquery);
if($num>0)
{
$banq = mysql_num_rows(mysql_query("SELECT * FROM `bannedusers` WHERE `username`='$username' AND `boardid`=0"));
if($banq > 0)
{
$binfo = mysql_fetch_array(mysql_query("SELECT * FROM `bannedusers` WHERE username='$username'"));
$reason = $binfo["reason"];
$date = $binfo["date"];
$unbandate = $binfo["unbandate"];
$today = time();
if($today < $unbandate) {
$pagetitle = "You Have Been Banished By one of the Administrator!.";
require('incfiles/head.php');
$ud = date('l jS F Y \a\t g:I A', $unbandate);
$bd = date('l jS F Y \a\t g:I A', $date);
echo "<h2>You Have Been Banished By one of the Administrator!.</h2><div class='display'><p>Reason: $reason<p>Banned Date: $bd<p>Unbanned Date: $ud</div>";
require('incfiles/end.php');
die();
} else {
mysql_query("DELETE FROM bannedusers WHERE username='$username' ANd boardid=0");
$pagetitle = "U have Just Been Unbanned";
require('incfiles/head.php');
$msg="You Have Just Been Unbanned Pls Go Back And Login Again";
echo "<div class='display'>$msg<br/><a href='javascript:history.go(-1)'>Go back</a></div>";
}
}




else {
$CookieExpire = 100; //The number of days after which the cookie would expire.
$time = 60 * 60 * 24 * $CookieExpire;
$time = time() + $time;
setcookie("username", "$username", "$time");
setcookie("password", "$password", "$time");
$key = $username . date('mY');
$key = strtoupper(md5($key));
setcookie("sessionkey", "$key", "$time");
$id=functions::user_info($username, userID); $date=date();
$guestip=$_SERVER["REMOTE_ADDR"];
$guestbrowser=$_SERVER["HTTP_USER_AGENT"];
mysql_query("UPDATE guestsonline SET time=0 WHERE browser='$guestbrowser' AND guestip='$guestip'");

mysql_query("UPDATE users SET sessionkey='$key' WHERE username='$username'");
if(empty($redirect))
{
functions::go($config->url);
} else {
functions::go('http://'.$redirect);
}

}
}
else
{ 
$pagetitle = "incorrect username and/or password, make sure you type correctly your username & password !";
require('incfiles/head.php');
$msg="incorrect username and/or password, make sure you type correctly your username & password !";
echo "<div class='display'>$msg<br/><a href='javascript:history.go(-1)'>Go back</a></div>";
}
}
else
{
$pagetitle = "Error";
require('incfiles/head.php');
$msg="Error";
echo "<div class='display'>$msg<br/><a href='javascript:history.go(-1)'>Go back</a></div>";
}

require('incfiles/end.php');
?>