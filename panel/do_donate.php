<?php
require('../incfiles/init.php');


$url = functions::cleaninput($_GET["url"]);

$uquery = mysql_query("SELECT * FROM users");

$userinfo = mysql_fetch_object($uquery);

$user2 = functions::cleanoutput($userinfo->username);


If($user2 == $user)
{
$user3 = "Your";
} else {
$user3 = "$user2's";
}


$sql = mysql_query("SELECT credits FROM users");
$sql = mysql_fetch_array($sql);
$sql = intval($sql['credits']);
$credits = $sql;

$sub = $_POST['sub'];
$subvalue = $_POST['subvalue'];
$user2 = $subvalue;
$user = $_SESSION['user'];
$qquery=mysql_query("SELECT * FROM users");
while($row = mysql_fetch_assoc($qquery))
{
$cname = $row["username"];
$id = $row["userid"];
}

if($credits < $sub || $sub > $credits)
{
$pagetitle = "InSufficient Credits!";
require('../incfiles/head.php');
functions::display_error('InSufficient Credits!');
require('../incfiles/end.php');
die();
}else{
$sql = mysql_query("UPDATE users SET credits = (credits - $sub) WHERE username ='$user'");
$ssql = mysql_query("UPDATE users SET credits = (credits + $sub) WHERE username ='$user2'");
$pagetitle = "Credits Has Been Transfer Successfully!";
require('../incfiles/head.php');
functions::display_error('Credits Has Been Transfer Successfully!');
require('../incfiles/end.php');
die();
}