<?php
echo '<?xml version="1.0" encoding="UTF-8" ?>';
echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN" 
  "http://www.w3.org/TR/1998/REC-html40-19980424/strict.dtd">';
echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
echo '<head><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-9607805725347179",
    enable_page_level_ads: true
  });
</script>';
echo '<link rel="stylesheet" type="text/css" href="/snow/snow.css">';

echo '<link rel="alternate" href="rss.php" title="RSS Feeds" type="application/rss+xml">'; 
echo '<link rel="shortcut icon" href="/favicon.ico" />';
echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
echo '<meta name="keywords" content="school news, waec, University, neco, Nabteb, Nigeria news, Nysc, Polytechnic, Collage of education, jamb">';
echo '<meta name="description" content="Gistaboutschool forum learn more about your schools and update about your country ,connect and share ideas.">';
echo '<meta name="msvalidate.01" content="6DE9083CEE5BFB26FD62582D3D0890E3">';
echo '<meta name="yandex-verification" content="593bd815115e1132">'; 
 include_once("analyticstracking.php");
if(!$pagetitle)
{
echo "<title>$config->title Forum</title>";
} else {
echo "<title>{$config->title} {$pagetitle}</title>";
}
 include "css.php";
$javas = (isset($javascript)) ? $javascript : "";

 echo "$javas</head><body><div class='body'>";
?>


<?php
$user = $_SESSION["user"];
$self=''.$_SERVER["HTTP_HOST"].''.$_SERVER["REQUEST_URI"].'';
$totalmembers = mysql_num_rows(mysql_query("SELECT * FROM `users`"));
$totaltopics = mysql_num_rows(mysql_query("SELECT * FROM `topics`"));
$time = time();
$tdate = functions::maindate($time);
$out = 'Welcome,';
if(functions::isloggedin())
{
$banned = functions::user_info($user, banned);
if($banned==0)
{

if (functions::user_info($user, lasttime) > (time() - 300))
{
$totalonsite = functions::user_info($user, timespent) + time() - functions::user_info($user, lasttime);
mysql_query("UPDATE users SET timespent='$totalonsite' WHERE username='$user'");
}

functions::updateonline();
} else {
header('location: '.$config->url.'logout');
exit(); 
}

$tfcheck = mysql_num_rows(mysql_query("SELECT * FROM `follow` WHERE follower='$user' AND type='topic'"));
if($tfcheck > 0)
{
$tfollowq = mysql_fetch_array(mysql_query("SELECT IFNULL(SUM(hasread), 0) AS hastotal FROM follow WHERE follower='$user' AND type='topic'"));
$tfollow = $tfollowq["hastotal"];

if($tfollow > 0)
{
$tfollowed = " / <a href='".$config->url."followed'>Followed
Topics(".$tfollow.")</a>";
} else {
$tfollowed = " / <a href='".$config->url."followed'>Followed
Topics</a>";
}
} else {
$tfollowed = "";
}



$bfcheck = mysql_num_rows(mysql_query("SELECT * FROM `follow` WHERE follower='$user' AND type='board'"));
if($bfcheck > 0)
{
$bfollowq = mysql_fetch_array(mysql_query("SELECT IFNULL(SUM(hasread), 0) AS hastotal FROM follow WHERE follower='$user' AND type='board'"));
$bfollow = $bfollowq["hastotal"];

if($bfollow > 0)
{
$bfollowed = " / <a href='".$config->url."newtopics'>Followed
Boards(".$bfollow.")</a>";
} else {
$bfollowed = " / <a href='".$config->url."newtopics'>Followed
Boards</a>";
}
} else {
$bfollowed = "";
}



$ufollowq = mysql_fetch_array(mysql_query("SELECT IFNULL(SUM(hasread), 0) AS hastotal FROM usersfollow WHERE follower='$user'"));
$ufollow = $ufollowq["hastotal"];

if($ufollow > 0)
{
$following = " / <a href='".$config->url."following'>Following(".$ufollow.")</a>";
} else {
$following = "";
}

$ncheck = mysql_num_rows(mysql_query("SELECT `hasread` FROM `notifications` WHERE `to`='$user'"));
if($ncheck > 0)
{
$noti = mysql_num_rows(mysql_query("SELECT `hasread` FROM `notifications` WHERE `hasread`=0 AND `to`='$user'"));
if($noti > 0)
{
$notifications = " / <a href='".$config->url."notifications'>Notifications(".$noti.")</a>";
} else {
$notifications = " / <a href='".$config->url."notifications'>Notifications</a>";
}
} else {
$notifications = "";
}


$pmcheck = mysql_num_rows(mysql_query("SELECT `hasread` FROM `pms` WHERE `to`='$user'"));
if($pmcheck > 0)
{
$pmnum = mysql_num_rows(mysql_query("SELECT `hasread` FROM `pms` WHERE `hasread`=0 AND `to`='$user'"));
if($pmnum > 0)
{
$pms = " / <a href='".$config->url."pm'>Messages(".$pmnum.")</a>";
} else {
$pms = " / <a href='".$config->url."pm'>Messages</a>";
}
} else {
$pms = "";
}

$modcheck = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user'"));

$admincheck = mysql_num_rows(mysql_query("SELECT * FROM admins WHERE username = '$user'"));

if($admincheck > 0)
{
$panel = " / <a href='".$config->url."panel'>Admin Panel</a>";
} elseif($modcheck > 0)
{
$panel = " / <a href='".$config->url."panel'>Moderators Panel</a>";
} else {
$panel = "";
}

$username = functions::user_info($user, username);
$uid = functions::user_info($user, userID);
$out .= " <b>".functions::user_link($username)."</b>: <a href='".$config->url."editprofile'>Edit Profile</a>".$bfollowed.$tfollowed.$following.$pms.$notifications." / <a href='".$config->url."trending'>Trending</a> / <a href='".$config->url."recent'>Recent</a> / <a href='".$config->url."new.php'>New</a> / <a href='".$config->url."following'>Following</a> / <a href='".$config->url."download.php'>Downloads</a> ".$panel." /<a href='".$config->url."logout?session=".$sessionkey."'>Logout</a><br>";
} else {
functions::updateguest();
$out .= ' <b>Guest</b>: <a href="'.$config->url.'register">Join
'.$config->title.'</a> / <a href="'.$config->url.'login?redirect='.urlencode($self).'">Login</a> /
<a href="'.$config->url.'trending">Trending</a> / <a href="'.$config->url.'recent">Recent</a> / <a href="'.$config->url.'new.php">New</a> / <a href="'.$config->url.'download.php">Downloads</a><br>';
}

echo '<table summary=""
id="up"><tbody><tr><td class="grad"><h1><a href="'.$config->url.'"
class=g title="'.$config->title.'
Forum">'.$config->title.' Forum</a></h1>'.$out.'<b>Stats: </b>'.$totalmembers.' members,
'.$totaltopics.' topics. <b>Date:</b> '.$tdate.'<p><form action="'.$config->url.'search" method="post"> <input type="text" name="q" size="32" placeholder="Search Here!"><input type="submit" name="sa"
value="Google"><input type="submit"
name="localsearch" value="Forum Search"></form></tbody></table><p><h2></h2>';

?>