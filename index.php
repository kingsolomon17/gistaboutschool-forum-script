<?php
require('incfiles/init.php');
require('incfiles/head.php');
//Add falling snowflakes
echo '<div id="snowflakeContainer">
    <p class="snowflake"></p>
</div>';
echo ' <script type="text/javascript" src="/snow/fallingsnow_v6.js"></script>' ;
require('incfiles/advt.php');

        
	    $hm = "/home/gistabo1/public_html/ticker"; 
	     $hm2 = "http://www.gistaboutschool.com/ticker";
	    include "$hm/hhnt.php";
        	

$query = mysql_query("SELECT * FROM `boards` WHERE `type`='parent'");
echo '<table
class="boards"><tr><th><h3>'.strtoupper($config->title).'
 FORUMS<img src="'.$config->url.'content/christmastree.jpg"width="35px" ~="30px" \></h3>';
$i = 0;
while($info = mysql_fetch_array($query))
{
$id = $info["id"];
$name = $info["name"];
$des = $info["description"];
$query2 = mysql_query("SELECT * FROM `boards` WHERE `type`='child' AND `typeid`='$id'");

if($i%2 == 0)
{
$css = 'l w';
} else {
$css = 'l ';
}
$check2 = mysql_fetch_array(mysql_query("SELECT * FROM `boards` WHERE `type`='child' AND `typeid`='$id' ORDER BY `id` DESC LIMIT 1"));
$cid = $check2["id"]; 

echo '<tr><td class="'.$css.'"><a href="'.urls::board($name, $id).'" title="'.$des.'"><b>'.$name.'</b></a>: ';
while($info2 = mysql_fetch_array($query2))
{
$id2 = $info2["id"];
$name2 = $info2["name"];
$des2 = $info2["description"];

if($cid == $id2)
{
$echo = '<a href="'.urls::board($name2, $id2).'" title="'.$des2.'"><b>'.$name2.'</b></a>';
} else {
$echo = '<a href="'.urls::board($name2, $id2).'" title="'.$des2.'"><b>'.$name2.'</b></a>, ';
}
echo $echo;
}


$i++;
}
echo '</table>';
mysql_free_result($query);

$limit = 65;
$urows = mysql_num_rows( mysql_query("SELECT * FROM `updates`"));
$pagination2 = new pagination($limit, 1, ''.$config->url.'links/(page)', $urows);
require('incfiles/advt.php');
echo '<p><table
class="boards"><tr><th><b><img src="'.$config->url.'icons/christmas-smiley.png" width="20px" height="20px" \> <a
href="'.$config->url.'links">Featured Links</a> / <a
href="http://www.gistaboutschool.com/download.php">Downloads</a> / <a href="http://facebook.com/Gist-About-School-149328199023008'.$config->fb1.'">Facebook</a> / <a href="http://plus.google.com/'.$config->go.'">Google+</a> / <a href="https://chat.whatsapp.com/50yYM9tpgHdDzFr27FhI4a">Whatsapp</a> / <a href="http://www.gistaboutschool.com/270/how-to-advertise-on-gistaboutschool">How To Place Ads On Gistaboutschool</a> <img src="'.$config->url.'icons/christmas-smiley.png" width="20px" height="20px" \></b><tr><td
class="featured w">';

$query = mysql_query("SELECT * FROM `updates` ORDER BY `id` DESC LIMIT $limit");
$num = mysql_num_rows($query);
if($num==0)
{
echo "No updates yet";
}
else
{
while($uinfo=mysql_fetch_array($query))
{
$url = functions::cleanoutput($uinfo["url"]);
$title = functions::cleanoutput($uinfo["title"]);

echo '" <a
href="'.$url.'"><b>'.$title.'</b></a> "<br>';
}
echo '<tr><td class="l w"> <center><a
href="'.$config->url.'links">(1)</a> '.$pagination2->display2() . '</center>';
}
mysql_free_result($query);
echo '</table>';
require('incfiles/sponsored.php');
require('incfiles/advt.php');
$recent = date("U")-300;
$recent2 = date("U")-86400;
$uonline = mysql_num_rows(mysql_query("SELECT * FROM `users` WHERE `lasttime`>'$recent'"));
$uonline2 = mysql_num_rows(mysql_query("SELECT * FROM `users` WHERE `lasttime`>'$recent2'"));
$gonline = mysql_num_rows(mysql_query("SELECT * FROM `guestsonline` WHERE `time`>'$recent'"));
$gonline2 = mysql_num_rows(mysql_query("SELECT * FROM `guestsonline` WHERE `time`>'$recent2'"));
echo '<p><table><tr><td><h3>Members
Online:</h3> (<b><a href="'.$config->url.'who">'.$uonline.'
Members</b></a> and <b>'.$gonline.' Guests</b>
online in <b>last 5 minutes</b> and 
<br><b><a href="'.$config->url.'who24">'.$uonline2.'
Members</b></a> and <b>'.$gonline2.' Guests</b>
online in <b>last 24 hours</b>) <b>'; 
Include ("count.php");
echo '</td></tr></table>';

echo '<p><table><tr><td><font color="#cc0000"><b>Gistaboutschool Forum Android App </b><a href="http://www.gistaboutschool.com/uplods/Gistaboutschool%20androad%20app.apk"><b>Download</b></font></a></td></tr></table>';
echo '<p><table><tr><td><b><a href="/download.php">Download apps,musics,videos,Pass questions,eboks,and more</a></td></tr></table>';

require('incfiles/ads1.php');

$bquery = mysql_query("SELECT * FROM `users` WHERE `birthmonth`='".(date("n"))."' AND `birthday`='".(date("j"))."'") or die(mysql_error());

if(mysql_num_rows($bquery) > 0)
{
$out = "";
while($binfo = mysql_fetch_array($bquery))
{
$sex = $binfo['sex'];
if(strlen($sex) == 4)
{
$age = '<span class="m">' . functions::ageCount($binfo["birthday"] . "/" . $binfo["birthmonth"] . "/" . $binfo["birthyear"]) . '</span>';
}
elseif(strlen($sex) == 6)
{
$age = '<span
class="f">' . functions::ageCount($binfo["birthday"] . "/" . $binfo["birthmonth"] . "/" . $binfo["birthyear"]) . '</span>';
}
else {
$age = functions::ageCount($binfo["birthday"] . "/" . $binfo["birthmonth"] . "/" . $binfo["birthyear"]);
}

$out .= '<a href="'.urls::user($binfo["username"]).'">'.$binfo["username"].'</a>('.$age.'), ';
}
echo '<tr><td class="l w"><center><b>Birthdays:</b><br> '.functions::cleanlast($out, 2).'</center>';
} else {
echo '';
}
echo '</table><p class="small">(<a href="#up"><b>Go
Up</b></a>)<p><table id="down"><tr><td
class="small w grad"><form action="'.$config->url.'search"> <input type="text" name="q"
size="32"placeholder="Search Here!"><input type="submit" name="sa"
value="Google"><input type="submit"
name="localsearch" value="Forum Search"></form><br><b><a href="'.$config->url.'" title="'.$config->title.'
Forum">'.$config->title.'</a></b> - Copyright &copy; 2017 - '.$config->year.' <a href="http://facebook.com/'.$config->fb.'" title="'.$config->owner.'">'.$config->owner.'</a>. All rights reserved.&reg; See <a href="/privacy-policy">Privacy Policy</a> , <a href="/terms-conditions">Terms & Conditions</a> 
<br /> Mail: '.$config->email.' <br /><b>Disclaimer:</b> Every Gistaboutschool member is <b>solely responsible</b> for <b>anything</b> that he/she <b>posts</b> or <b>uploads</b> on Gistaboutschool.</table>';

echo '</div><script src="/snow/fallingsnow_v6.js"></script></body></html>';
die();
?>
