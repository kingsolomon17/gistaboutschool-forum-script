<?php

$url = functions::cleaninput($_GET["url"]);

$uquery = mysql_query("SELECT * FROM users WHERE username='$url'");

if(mysql_num_rows($uquery) < 1)
{
$pagetitle = "error";
require('incfiles/head.php');
functions::display_error('User Not Found');
require('incfiles/end.php');
die();
}

$userinfo = mysql_fetch_object($uquery);

$user2 = functions::cleanoutput($userinfo->username);


If($user2 == $user)
{
$user3 = "Your";
} else {
$user3 = "$user2's";
}


$pagetitle = "$user3 Profile";
require('incfiles/head.php');
require('incfiles/display.php');

echo "<h2>View $user3 Profile</h2><p>"; 
echo "<a href='/'>$config->title Forum</a> / <a href='".urls::user($user2)."'>$user3 Profile</a>";

include"incfiles/ads.php";

$sql = mysql_query("SELECT points FROM users WHERE username = '$user2' ");
$sql = mysql_fetch_array($sql);
$sql = intval($sql['points']);
$points= $sql;
echo '<p><table><tr><td class=""><span style="color: #cc0000;"><b>'.$user3.' POINTS</b></span> ('.$points.') </td></tr></table></p>';

$sql = mysql_query("SELECT credits FROM users WHERE username = '$user2' ");
$sql = mysql_fetch_array($sql);
$sql = intval($sql['credits']);
$points= $sql;
$points= $sql;
echo '<p><table><tr><td class=""><span style="color: #cc0000;"><b>'.$user3.' Credits</b></span><a href=\\panel/donate><b> ('.$points.') </b></a></td></tr></table></p>';
echo '<a href=\\panel/donate><b>Click here to Transfer Credit</b></a>';

$tcount = mysql_num_rows(mysql_query("SELECT * FROM topics WHERE poster='$user2'"));

$pcount = mysql_num_rows(mysql_query("SELECT * FROM posts WHERE poster='$user2'"));

$admincheck = mysql_num_rows(mysql_query("SELECT * FROM admins WHERE username = '$user'"));
$modcheck2 = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND type = 'super'"));


if($tcount > 0)
{
$alt =  "<tr><th>$user3 Latest Topics ( <a href='/$user2/topics'>View All $tcount Topics</a> | <a href='/$user2/posts'>Posts</a> )";

$pquery2 =$info = mysql_query("SELECT * FROM topics WHERE poster='$user2' ORDER BY id DESC LIMIT 6");

$display = new display($pquery2);

$display->topics($alt);

}


$regtime = functions::cleanoutput($userinfo->regtime);
$regtime = date("F n Y", $regtime);

$timespent = functions::cleanoutput($userinfo->timespent);
$timespent = functions::timecount($timespent);  
$lasttime = functions::cleanoutput($userinfo->lasttime); 

$lasttime = functions::display_date($lasttime);

$uid = functions::cleanoutput($userinfo->userID);

$gender = functions::cleanoutput($userinfo->sex);

$birthday = functions::cleanoutput($userinfo->birthday);

$birthmonth = functions::cleanoutput($userinfo->birthmonth);

$birthyear = functions::cleanoutput($userinfo->birthyear);

$personaltext = functions::cleanoutput($userinfo->personaltext);

$signature = functions::cleanoutput($userinfo->signature);

$bbcodes = new bbcode($signature);
$signature = $bbcodes->display();

$websitetitle = functions::cleanoutput($userinfo->websitetitle);

$websiteurl = functions::cleanoutput($userinfo->websiteurl);

$location = functions::cleanoutput($userinfo->location);

$yim = functions::cleanoutput($userinfo->yim);
$twitter = functions::cleanoutput($userinfo->twitter);

$avatar = functions::cleanoutput($userinfo->avatar);

if( ! empty($avatar))
{
$avatar = '<p><img class="img" src="'.$config->url.'avatars/'.$uid.'">';
}



if(strlen($gender) > 3)
{
$gender = "<p><b>Gender</b>: $gender";
} else {
$gender ="";
}




if($birthday > 0 && $birthmonth > 0 && $birthyear >0)
{
$birthdate = "<p><b>Birthdate</b>: $birthday $birthmonth $birthyear";
} else {
$birthdate ="";
}


if( ! empty($personaltext))
{
$personaltext = "<p><b>Personaltext</b>: $personaltext";
} else {
$personaltext ="";
}


if( ! empty($signature))
{ 
$signature = "<p><b>Signature</b>: $signature";
} else {
$signature = "";
}


if( ! empty($websitetitle) && ! empty($websiteurl))
{
$website = "<p><b>Website</b>: <a href='$websiteurl'>$websitetitle</a>";
} else {
$website ="";
}



if( ! empty($location))
{
$location = "<p><b>Location</b>: $location";
} else {
$location = "";
}


if( ! empty($yim))
{
$yim = "<p><b>Yim</b>: $yim";
} else {
$yim = "";
}

if( ! empty($twitter))
{
$twitter = "<p><b>Twitter</b>: $twitter";
} else {
$twitter = "";
}

if(functions::isloggedin())
{
If($user2 != $user)
{
$follow = mysql_num_rows(mysql_query("SELECT * FROM usersfollow WHERE follower='$user' AND following='$user2'"));

if($follow > 0)
{
$faction = '( <a href="/do_unfollowmember?session='.$sessionkey.'&member='.$uid.'&redirect='.$self.'">Un-Follow This Member</a> ) ';
} else {
$faction = '( <a href="/do_followmember?session='.$sessionkey.'&member='.$uid.'&redirect='.$self.'">Follow This Member</a> ) ';
}
$message = '<p><a href="/newpm/'.$user2.'"> Send Private Message To '.$user2.'</a>';
$message1 = '<p><a href="/sendemail/'.$user2.'"> Send E-mail Message To '.$user2.'</a>';

}

}


echo '<table><tbody><tr><td> '.$faction.' '.$message.$message1.$avatar.$gender.$birthdate.$website.$location.$personaltext.$yim.$twitter.'
<p><b>Time registered</b>: '.$regtime.'<p><b>Time spent online</b>: '.$timespent.''.$signature.'<p><b>Last seen</b>: <b>'.$lasttime.'</b><p><a href="/'.$user2.'/posts">View '.$user3.' posts ('.$pcount.')</a> | <a href="/'.$user2.'/topics">View '.$user3.' topics ('.$tcount.')</a></tbody></table>';


$followcheck = mysql_num_rows(mysql_query("SELECT * FROM usersfollow WHERE follower='$user2'"));

if($followcheck > 0)
{
$fquery = mysql_query("SELECT * FROM usersfollow WHERE follower='$user2'");
$following = '';
while($finfo = mysql_fetch_array($fquery))
{
$following .= functions::user_link(functions::cleanoutput($finfo["following"])) . ', ';
}
echo '<p><table summary="friends"><tbody><tr><th>Following:<tr><td class="user w"> '.functions::cleanlast($following, 2).'</tbody></table>';
mysql_free_result($fquery);

}

require('incfiles/end.php');
?>