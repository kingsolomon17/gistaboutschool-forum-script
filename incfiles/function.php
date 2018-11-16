<?php
class functions extends core{

public static function go($url){
header('Location: '.$url);
exit();
}

public static function converturl($text)
{
$text = html_entity_decode(trim($text), ENT_QUOTES, 'UTF-8');
$text=str_replace(" ","-", $text);$text=str_replace("--","-", $text);
$text=str_replace("@","-",$text);$text=str_replace("/","-",$text);

$text=str_replace("\\","-",$text);$text=str_replace(":","",$text);
$text=str_replace("\"","",$text);$text=str_replace("'","",$text);
$text=str_replace("<","",$text);$text=str_replace(">","",$text);
$text=str_replace(",","",$text);$text=str_replace("?","",$text);
$text=str_replace(";","",$text);$text=str_replace(".","",$text);
$text=str_replace("[","",$text);$text=str_replace("]","",$text);
$text=str_replace("(","",$text);$text=str_replace(")","",$text);
$text=str_replace("*","",$text);$text=str_replace("!","",$text);
$text=str_replace("$","-",$text);$text=str_replace("&","-and-",$text);
$text=str_replace("%","",$text);$text=str_replace("#","",$text);
$text=str_replace("^","",$text);$text=str_replace("=","",$text);
$text=str_replace("+","",$text);$text=str_replace("~","",$text);
$text=str_replace("`","",$text);$text=str_replace("--","-",$text);
$text = preg_replace("/(Г?|Г?|б ?|б ?|Г?|Г?|б ?|б ?|б ?|б ?|б ?|Д?|б ?|б ?|б ?|б ?|б ?)/", 'a', $text);$text = preg_replace("/(aМ?|aМ?|aМ?|aМ?|aМ?|Г?|Г?М?|Г?М?|Г?М?|Г?М?|Г?М?|Д?|Д?М?|ДМ?|Д?М?|Д?М?|Д?М?)/", 'a', $text);
$text = preg_replace("/(ГЁ|Г?|б  |б  |б  |Г?|б ?|б  |б ?|б ?|б ?)/", 'e', $text);$text = preg_replace("/(eМ?|eМ?|eМ?|eМ?|eМ?|Г?|Г?М?|Г?М?|Г?М?|Г?М?|Г?М?)/", 'e', $text);
$text = preg_replace("/(Г?|Г?|б ?|б ?|Д?)/", 'i', $text);$text = preg_replace("/(iМ?|iМ?|iМ?|iМ?|iМ?)/", 'i', $text);
$text = preg_replace("/(Г?|Г?|б ?|б ?|Г?|Г?|б ?|б ?|б ?|б ?|б ?|Ж?|б ?|б ?|б ?|б ?|б ?)/", 'o', $text);$text = preg_replace("/(oМ?|oМ?|oМ?|oМ?|oМ?|Г?|Г?М?|Г?М?|Г?М?|Г?М?|Г?М?|Ж?|Ж?М?|Ж?М?|Ж?М?|Ж?М?|Ж?М?)/", 'o', $text);
$text = preg_replace("/(Г |Г |б ?|б ?|Е?|Ж?|б ?|б ?|б ?|б ?|б ?)/", 'u', $text);$text = preg_replace("/(uМ?|uМ?|uМ?|uМ?|uМ?|Ж?|Ж?М?|Ж?М?|Ж?М?|Ж?М?|Ж?М?)/", 'u', $text);
$text = preg_replace("/(б ?|Г |б ?|б ?|б  )/", 'y', $text);$text = preg_replace("/(Д?)/", 'd', $text);
$text = preg_replace("/(yМ?|yМ?|yМ?|yМ?|yМ?)/", 'y', $text);$text = preg_replace("/(Д?)/", 'd', $text);
$text = preg_replace("/(Г?|Г?|б ?|б ?|Г?|Г?|б ?|б ?|б ?|б Ё|б ?|Д?|б ?|б ?|б ?|б ?|б ?)/", 'A', $text);$text = preg_replace("/(AМ?|AМ?|AМ?|AМ?|AМ?|Г?|Г?М?|Г?М?|Г?М?|Г?М?|Г?М?|Д?|Д?М?|Д?М?|Д?М|Д?М?|Д?М?)/", 'A', $text);
$text = preg_replace("/(Г?|Г?|б ё|б  |б  |Г?|б ?|б  |б ?|б ?|б ?)/", 'E', $text);$text = preg_replace("/(EМ?|EМ?|EМ?|EМ?|EМ?|Г?|Г?М?|Г?М?|Г?М?|Г?М?|Г?М?)/", 'E', $text);
$text = preg_replace("/(Г?|Г?|б ?|б ?|ДЁ)/", 'I', $text);$text = preg_replace("/(IМ?|IМ?|IМ?|IМ?|IМ?)/", 'I', $text);
$text = preg_replace("/(Г?|Г?|б ?|б ?|Г?|Г?|б ?|б ?|б ?|б ?|б ?|Ж?|б ?|б ?|б ?|б ?|б ?)/", 'O', $text);$text = preg_replace("/(OМ?|OМ?|OМ?|OМ?|OМ?|Г?|Г?М?|Г?М?|Г?М?|Г?М?|Г?М?|Ж?|Ж?М?|Ж?М?|Ж?М?|Ж?М?|Ж?М?)/", 'O', $text);
$text = preg_replace("/(Г?|Г?|б ?|б ?|ЕЁ|Ж?|б ?|б Ё|б ?|б ?|б ?)/", 'U', $text);$text = preg_replace("/(UМ?|UМ?|UМ?|UМ?|UМ?|Ж?|Ж?М?|Ж?М?|Ж?М?|Ж?М?|Ж?М?)/", 'U', $text);
$text = preg_replace("/(б ?|Г?|б ?|б ?|б ё)/", 'Y', $text);$text = preg_replace("/(Д?)/", 'D', $text);
$text = preg_replace("/(YМ?|YМ?|YМ?|YМ?|YМ?)/", 'Y', $text);$text = preg_replace("/(Д)/", 'D', $text);
$text=strtolower($text);
return $text;
}

public static function isloggedin()
{
if(isset($_SESSION["user"])) return true;
else return false;
}

public static function checkin($str) {
if (function_exists('iconv')) {
$str = iconv("UTF-8", "UTF-8", $str);
}
$str = preg_replace('#(\.|\?|!|\(|\)){3,}#', '\1\1\1', $str);
$str = nl2br($str);
$str = preg_replace('!\p{C}!u', '', $str);$str = str_replace('<br />', "\n", $str);
$str = preg_replace('# {2,}#', ' ', $str);$str = preg_replace("/(\n)+(\n)/i", "\n\n", $str);
return trim($str);
}

public static function check($str)
{
$str = htmlentities(trim($str), ENT_QUOTES, 'UTF-8');
$str = nl2br($str);
$str = strtr($str, array(
chr(0) => '',
chr(1) => '',
chr(2) => '',
chr(3) => '',
chr(4) => '',
chr(5) => '',
chr(6) => '',
chr(7) => '',
chr(8) => '',
chr(9) => '',
chr(10) => '',
chr(11) => '',
chr(12) => '',
chr(13) => '',
chr(14) => '',
chr(15) => '',
chr(16) => '',
chr(17) => '',
chr(18) => '',
chr(19) => '',
chr(20) => '',
chr(21) => '',
chr(22) => '',
chr(23) => '',
chr(24) => '',
chr(25) => '',
chr(26) => '',
chr(27) => '',
chr(28) => '',
chr(29) => '',
chr(30) => '',
chr(31) => ''
));
$str = str_replace("'", "&#39;", $str);
$str = str_replace('\\', "&#92;", $str);
$str = str_replace("|", "I", $str);
$str = str_replace("||", "I", $str);
$str = str_replace("/\\\$/", "&#36;", $str);
$str = mysql_real_escape_string($str);
return $str;
}

public static function checkout($str, $br = 0, $tags = 0)
{
$str = htmlentities(trim($str), ENT_QUOTES, 'UTF-8');
if ($br == 1)
$str = nl2br($str);
elseif ($br == 2)
$str = str_replace("<br />", ' ', $str);
if ($tags == 1)
$str = bbcode::tags($str);
elseif ($tags == 2)
$str = bbcode::notags($str);
if ($threads == 1)
$str = bbcode::threads($str);
elseif ($threads == 2)
$str = bbcode::comrade($str);
$replace = array(
chr(0) => '',
chr(1) => '',
chr(2) => '',
chr(3) => '',
chr(4) => '',
chr(5) => '',
chr(6) => '',
chr(7) => '',
chr(8) => '',
chr(9) => '',
chr(11) => '',
chr(12) => '',
chr(13) => '',
chr(14) => '',
chr(15) => '',
chr(16) => '',
chr(17) => '',
chr(18) => '',
chr(19) => '',
chr(20) => '',
chr(21) => '',
chr(22) => '',
chr(23) => '',
chr(24) => '',
chr(25) => '',
chr(26) => '',
chr(27) => '',
chr(28) => '',
chr(29) => '',
chr(30) => '',
chr(31) => ''
);
return strtr($str, $replace);
}


public static function cleaninput($string)
{
return $string;
}
public static function cleanoutput($string)
{
$string=stripcslashes($string);
//$string=preg_replace("/[!#$%^&*<>+=]/", ' ', $string);
//$string=str_replace('"', ' ', $string);
$string=htmlentities($string, ENT_QUOTES);
return $string;
}
public static function user_info($user, $field)
{
//echo"$user and $field success";
$query=mysql_query("SELECT $field FROM users WHERE username='$user'");
$info=mysql_fetch_array($query);
$info=$info[$field];
return $info;
}

public static function user_info2($uid, $field)
{
//echo"$user and $field success";
$query2=mysql_query("SELECT $field FROM users WHERE userID=$uid") or mysql_error();
//echo"$field<br/>";
$info=@mysql_fetch_array($query2);
//print_r($info);
$info=$info[$field];
return $info;
}
function ago($timestamp)
{
$difference=time()-$timestamp;
$periods=array("second","minute","hour","day","week","month","years","decade");
$lengths=array("60","60","24","7","4.35","12","10");
for($j=0; $difference>=$lengths[$j]; $j++)
$difference/=$lengths[$j];
$difference=round($difference);
if($difference!=1)
$periods[$j].="s";
$text="$difference $periods[$j] ago";
return $text;
}


public static function updateguest()
{
$guestip=$_SERVER["REMOTE_ADDR"];
$guestbrowser=$_SERVER["HTTP_USER_AGENT"];
$query=mysql_query("SELECT * FROM `guestsonline` WHERE `guestip`='$guestip' AND `browser`='$guestbrowser'");
if(mysql_num_rows($query)>0)
{
$time=time();
mysql_query("UPDATE guestsonline SET `time`='$time' WHERE `browser`='$guestbrowser' AND `guestip`='$guestip'");
}
else
{
$time=time();
mysql_query("INSERT INTO guestsonline set `time`='$time', `guestip`='$guestip', `browser`='$guestbrowser'"); 
}
}

public static function updateonline()
{
global $user;
$time=time();
$uinfo=mysql_fetch_array(mysql_query("SELECT * FROM users WHERE `username`='$user'"));
$tsgone=$uinfo["tsgone"];
$oldtime=$uinfo["oldtime"];
$checktime=date("U")-86400;
mysql_query("UPDATE users SET `lasttime`='$time' WHERE `username`='$user'");
if($tsgone<$checktime)
{
mysql_query("UPDATE users SET `tsgone`='$time', `oldtime`='$tsgone'");
}
}

public static function maindate($time)
{
date_default_timezone_set('Africa/Lagos');
$time = date('l jS F Y \a\t h:i a');
return $time;
}

public static function forumcats($type)
{
$query = mysql_query("SELECT * FROM forums WHERE type = '$type'");
while($rinfo=mysql_fetch_assoc($query))
{
$name = $rinfo["name"];
$des = $rinfo["description"];
$id = $rinfo["id"];
echo '<a
href="'.$this->url.'forumcat/'.$id.'/'.$name.'" title="'.$des.'"><b>'.$name.'</b></a>,';
}
} 


public static function updates($limit)
{
$query=mysql_query("SELECT * FROM updates ORDER BY id DESC LIMIT $limit");
$num=mysql_num_rows($query);
if($num==0)
{
return "No updates yet";
}
else
{
while($uinfo=mysql_fetch_array($query))
{
$title=$uinfo["title"];
$url=$uinfo["url"];
$id = $uinfo["id"];
return ' » <a
href="'.$url.'"><b>'.$title.'</b></a> «<br>';
}
}
}
public static function check_email($email)
{
If (strlen($email) == 0) return false;
If (eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[A-z0-9-]+)*(\.[A-z]{2,4})$", $email)) return true;
Return false;
}

public static function display_error($error)
{
if(empty($error))
{
echo '';
}
else
{
echo '<div class="display">'.$error.'</div>';
}  
}
   
public static function display_date($time)
{
if(date('Y', $time) == date('Y', time())) {
if(date('z', $time) == date('z', time()))
{
return '<b></b> Today at <b>' . date("h:i a", $time).'</b>';
}
elseif(date('z', $time) == date('z', time()) - 1)
{
return '<b></b> Yesterday at <b>' . date("h:i a", $time).'</b>';
}
elseif(date('W', $time) == date('W', time()))
{
return '<b></b> on <b>' . date("D", $time).'</b> at <b>'.date("h:i a", $time).'</b>';
}
elseif(date('W', $time) == date('W', time()) - 1)
{
return '<b></b> on <b>' . date("D", $time).'</b> at <b>'.date("h:i a", $time).'</b>';
}
elseif(date('n', $time) == date('n', time()))
{
return '<b></b> on <b>' . date("D d", $time).'</b> at <b>'.date("h:i a", $time).'</b>';
}
elseif(date('n', $time) == date('n', time()) - 1)
{
return '<b></b> on <b>' . date("D d", $time).'</b> at <b>'.date("h:i a", $time).'</b>';
}
else
{
return '<b>' . date("h:i a", $time) .'</b> On <b>' . date("n M", $time) . '</b>';
}
} 
else
{
return '<b>' . date("h:i a", $time) .'</b> On <b>' . date("n M Y", $time) . '</b>';
}
}   
public static function user_link($user) 
{
$sex = self::user_info($user, 'sex');
if(strlen($sex) == 4)
{
return '<a href="/'.$user.'">'.$user.'</a>(<span
class="m">m</span>)';
}
elseif(strlen($sex) == 6)
{
return "<a href='/$user'>$user</a>(<span
class='f'>f</span>)";
}
else {
return "<a href='/$user'>$user</a>";
}

}

public static function follow($id, $type)
{
if(self::isloggedin() && isset($type))
{
global $user;
global $self;
$session = $_COOKIE["sessionkey"];
$query=mysql_num_rows(mysql_query("SELECT * FROM follow WHERE type='$type' AND follower='$user' AND itemid=$id"));

if($query > 0)
{
return "<a href='/follow?action=unfollow&type=$type&itemid=$id&session=$session&redirect=$self' class='button5'><b> <font color='#181855'> Unfollow</font></b></a>";
}
else
{
return "<a href='/follow?action=follow&type=$type&itemid=$id&session=$session&redirect=$self' class='button5'><b><font color='#181855'>Follow</font></b></a>";
}
} else {
return "";
}
}




public static function likepost($id)
{

$query2 = mysql_num_rows(mysql_query("SELECT * FROM likepost WHERE postid=$id"));
$lnum = ($query2 > 0) ? " $query2 Likes" : "";

if(self::isloggedin())
{
global $user;
global $self;

$query = mysql_num_rows(mysql_query("SELECT * FROM likepost WHERE liker='$user' AND postid=$id"));

$session = $_COOKIE["sessionkey"];



if($query > 0)
{
return "<b>$lnum</b> (<a href='/likepost?action=unlike&post=$id&session=$session&redirect=$self'>Unlike</a>)";
}
else
{
return "<b>$lnum</b> (<a href='/likepost?action=like&post=$id&session=$session&redirect=$self'>Like</a>)";
}
} else {
return "<b>$lnum</b>";
}
}


public static function timecount($seconds)
{

$seconds = (int)$seconds;
If($seconds < 1)
{
$out = $seconds." seconds(s)";
return $out;
}

if($seconds < 60)
{
$out = $seconds." seconds(s)";
return $out;
}

if($seconds <= 3600)
{
$min = (int)floor($seconds/60);
$sec = (int)$seconds%60;
$out = $min." minute(s), ".$sec." second(s)";
}
elseif($seconds <= 86400) 
{
$hours = (int)floor($seconds/3600);
$hourRemainder = (int)$seconds%3600;
$min = (int)floor($hourRemainder/60);
$sec = (int)$seconds%60;
$out = $hours." hour(s), ".$min. " minute(s), ".$sec." second(s)";
}
elseif($seconds <= 31536000 )
{
$days = (int)floor($seconds/86400);
$daysRemainder = (int)$seconds%86400;
$hours = (int)floor($daysRemainder/3600);
$hourRemainder = (int)($seconds - 86400)%3600;
$min = (int)floor($hourRemainder/60);
$sec = (int)$seconds%60;
$out = $days." day(s), ".$hours." hour(s), ".$min." munite(s), ".$sec." seconds(s)";
}

elseif($seconds <= 604800)
{
$weeks = (int)floor($seconds/604800);
$weeksRemender = (int)$seconds%604800;
$days = (int)floor($weeksRemender/86400);
$daysRemainder = (int)$seconds%86400;
$hours = (int)floor($daysRemainder/3600);
$hourRemainder = (int)($seconds - 86400)%3600;
$min = (int)floor($hourRemainder/60);
$sec = (int)$seconds%60;
$out = $weeks." week(s), ".$days." day(s), ".$hours." hour(s), ".$min." munite(s), ".$sec." seconds(s)";
}

elseif($seconds <= 2678400)
{
$months = (int)floor($seconds/2678400);
$monthsRemender = (int)$seconds%2678400;
$weeks = (int)floor($monthsRemender/604800);
$weeksRemender = (int)$seconds%604800;
$days = (int)floor($weeksRemender/86400);
$daysRemainder = (int)$seconds%86400;
$hours = (int)floor($daysRemainder/3600);
$hourRemainder = (int)($seconds - 86400)%3600;
$min = (int)floor($hourRemainder/60);
$sec = (int)$seconds%60;
$out = $months." month(s), ".$weeks." week(s), ".$days." day(s), ".$hours." hour(s), ".$min." munite(s), ".$sec." seconds(s)";
}
else
{
$years = (int)floor($seconds/31536000);
$yearsRemainder = (int)$seconds%31536000;
$months = (int)floor($yearsRemainder/2678400);
$monthsRemender = (int)$seconds%2678400;
$weeks = (int)floor($monthsRemender/604800);
$weeksRemender = (int)$seconds%604800;
$days = (int)floor($weeksRemender/86400);
$daysRemainder = (int)$seconds%86400;
$hours = (int)floor($daysRemainder/3600);
$hourRemainder = (int)($seconds - 86400)%3600;
$min = (int)floor($hourRemainder/60);
$sec = (int)$seconds%60;
$out = $years." year(s), ".$months." month(s), ".$weeks." week(s), ".$days." day(s), ".$hours." hour(s), ".$min." munite(s), ".$sec." seconds(s)";
}
return $out;
}

public static function reducetext($text, $count, $wrapText='....')
{
if(strlen($text)>$count)
{
preg_match('/^.{0,'.$count.'}(?:.*?)\b/siu', $text, $matches);
$text=$matches[0];
}
else
{
$wrapText='';
}
return $text.$wrapText;
}

public static function cleanlast($var, $amount)
{
$out = substr($var, 0, strlen($var) - $amount);
return $out;
}

public static function ageCount($birthday){
list($day,$month,$year) = explode("/",$birthday);
$year_diff  = date("Y") - $year;
$month_diff = date("m") - $month;
$day_diff   = date("d") - $day;
if($day_diff < 0 && $month_diff == 0)
{
$year_diff --;
}
if($day_diff < 0 && $month_diff < 0)
{
$year_diff --;
}
return $year_diff;
}

}

if (isset($_COOKIE["username"]) && isset
($_COOKIE["password"]) && !isset($_SESSION
["user"]))
{
$cuser = $_COOKIE["username"];
$cpass = $_COOKIE["password"];
$check = "SELECT `username` FROM `users`
WHERE `username` = '$cuser'
AND `password` = '$cpass'";
$check = mysql_query($check);
$check = mysql_num_rows($check);
if ($check > 0)
{
$_SESSION["user"] = $cuser;
} else {
setcookie("username", "", time()-86400);
setcookie("password", "", time()-86400);
}
}

if(functions::isloggedin())
{
$user = $_SESSION["user"];
$sessionkey = $_COOKIE["sessionkey"];
}

$self=''.$_SERVER["HTTP_HOST"].''.$_SERVER["REQUEST_URI"].'';

?>