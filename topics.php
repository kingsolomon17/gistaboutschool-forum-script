<?php
require('incfiles/init.php');
$url = functions::cleaninput($_GET["url"]);

$num=mysql_num_rows(mysql_query("SELECT * FROM boards WHERE url='$url'"));

if($num < 1)
{
if(isset($_GET["sort"]))
{
$sort = $_GET["sort"];
if($sort==posts)
{
require('userposts.php');
die();
}
if($sort==topics)
{
require('usertopics.php');
die();
}
} else {
require('profile.php');
die();
}
}
$query = mysql_fetch_array(mysql_query("SELECT * FROM boards WHERE url='$url'"));
$id = $query["id"];
$name = $query["name"];
$type = $query["type"];
$locked = $query["locked"];
$des = $query["description"];
$pagetitle = "forum - ". $name;
$javascript = '<script type="text/javascript" src="http://www.google-analytics.com/ga.js"></script><script type="text/javascript" src="/static/javascript.js"></script>
';
require('incfiles/head.php');


if($type == child)
{
$typeid = $query["typeid"];
$qquery = mysql_fetch_array(mysql_query("SELECT * FROM boards WHERE id=$typeid"));
$ftype = $qquery["type"];
if($ftype == child)
{
$typeid2 = $qquery["typeid"];
$qqquery = mysql_fetch_array(mysql_query("SELECT * FROM boards WHERE id='$typeid2'"));

$typename = ' / <a href="'.urls::board($qqquery['name'], $typeid2).'">'.ucwords($qqquery['name']).'</a>';
} else {
$typename = '';
}

$typename .= ' / <a href="'.urls::board($qquery['name'], $typeid).'">'.ucwords($qquery['name']).'</a>';
} else {
$typename .= '';
}
$modq = mysql_query("SELECT `username` FROM `moderators` WHERE `boardid` = $id");
$modname = '';
while($info = mysql_fetch_array($modq))
{
$modname .= functions::user_link($info["username"]).', ';
}

$time = time();
if(functions::isloggedin())
{
$vcheck = mysql_num_rows(mysql_query
("SELECT username  FROM usersviewingboard WHERE username='$user' AND boardid=$id"));
if($vcheck > 0)
{
mysql_query("UPDATE usersviewingboard SET time='$time' WHERE username='$user' AND boardid='$id'");
} else {
mysql_query("INSERT INTO usersviewingboard SET time='$time', username='$user', boardid='$id'");
}

} else {
$time = time();
$ip = $_SERVER["REMOTE_ADDR"];
$browser = $_SERVER["HTTP_USER_AGENT"];
$vcheck = mysql_num_rows(mysql_query
("SELECT * FROM guestviewingboard WHERE ip='$ip' AND browser='$browser' AND boardid='$id'"));
if($vcheck > 0)
{
mysql_query("UPDATE guestviewingboard SET time='$time' WHERE ip='$ip' AND browser='$browser' AND boardid='$id'");
} else {
mysql_query("INSERT INTO guestviewingboard SET time='$time', ip='$ip', browser='$browser', boardid='$id'");
}

}

$qu = mysql_num_rows(mysql_query("SELECT * FROM `follow` WHERE `follower`='$user' AND `type`='board' AND `itemid`=$id"));
if($qu > 0)
{
mysql_query("UPDATE `follow` SET `hasread`='0' WHERE `type`='board' AND `follower`='$user' AND `itemid`=$id");
}

$recent = time() - 300;
$onlinequery=mysql_query("SELECT * FROM usersviewingboard WHERE boardid='$id' AND time>'$recent'");
$viewingboard = "";
while($row=@mysql_fetch_array($onlinequery))
{
$viewingboard .= functions::user_link($row["username"]) . ', ';
}
if(empty($viewingboard))
{
$viewingboard = "0 users";
} else {
$viewingboard = functions::cleanlast($viewingboard, 2);
}
$onlineguest = mysql_num_rows(mysql_query("SELECT * FROM guestviewingboard WHERE boardid = '$id' AND time > '$recent'"));



$qu = mysql_num_rows(mysql_query("SELECT * FROM follow WHERE follower='$user' AND type='board' AND itemid=$id"));
if($qu > 0)
{
mysql_query("UPDATE follow SET hasread='0' WHERE type='topic' AND follower='$user' AND itemid=$id");
}





if(isset($_GET["sort"]))
{
$sort = $_GET["sort"];
if($sort==posts)
{
$ord = "posts";
$st1 = 'Topic: <a href="'.urls::board($name, $id, "", "updated").'">Updated</a> / <a href="'.urls::board($name, $id, "", "views").'">Views</a> / <b>Posts</b>';
}
elseif($sort==views)
{
$ord = "views";
$st1 = 'Topic: <a href="'.urls::board($name, $id, "", "updated").'">Updated</a> / <a href="'.urls::board($name, $id, "", "posts").'">Posts</a> / <b>Views</b>';
}
else
{
$ord = "lastpostdate";
$st1 = 'Topic: <a href="'.urls::board($name, $id, "", "posts").'">Posts</a> / <a href="'.urls::board($name, $id, "", "views").'">Views</a> / <b>Updated</b>';
}
} else {
$ord = "lastpostdate";
$st1 = 'Topic: <a href="'.urls::board($name, $id, "", "posts").'">Posts</a> / <a href="'.urls::board($name, $id, "", "views").'">Views</a> / <b>Updated</b>';

}





$rowsperpage = $config->topicsperpage;
$range= 10;
if(isset($_GET["page"]) && is_numeric($_GET["page"]))
{
$page = (int)$_GET["page"];
}
else
{
$page = 1;
}
$numrows = mysql_num_rows(mysql_query("SELECT * FROM topics WHERE boardid=$id"));
$totalpages = ceil($numrows/$rowsperpage);
if($page > $totalpages)
{
$page = $totalpages;
}

$offset = ($page-1)*$rowsperpage;


$pquery = mysql_query("SELECT * FROM topics WHERE boardid='$id' ORDER BY `stick` DESC, $ord DESC LIMIT $offset, $rowsperpage");

$rnum=mysql_num_rows($rquery);
$cname = functions::converturl($name);
$pagination= new pagination($config->topicsperpage, $page, urls::board($name, $id, '(page)', $sort), $numrows);

echo '<a name="top"></a><p><h2>'.$name.' - '.$config->title.'</h2><p class=bold><a href="/">'.$config->title.' Forum</a>'.$typename.' / <a href="#">'.$name.'</a> (Moderators: '.functions::cleanlast($modname, 2).' )<p>';

$subcheck = mysql_num_rows(mysql_query("SELECT * FROM boards WHERE typeid=$id"));
If($subcheck > 0)
{
$qquery = mysql_query("SELECT * FROM boards WHERE typeid=$id");
echo '<table summary="">';
$i = 0;
while($qinfo = mysql_fetch_array($qquery))
{
$qqname = $qinfo['name'];
$qqdes = $qinfo["description"];
$qqid = $qinfo['id'];

$qqrnum = mysql_num_rows(mysql_query("SELECT * FROM topics WHERE boardid=$qqid"));

if($i%2 == 0)
{
$css = 'w';
} else {
$css = '';
}

echo '<tr><td class="'.$css.'"><b><a href="'.urls::board($qqname, $qqid).'">'.$qqname.'</a></b>: '.$qqdes.' ('.$qqrnum.' topics)';
$i++;

}
echo '</table><p>';
}

echo '<a href="#">'.$name.'</a>: '.$des.'<br><p>'; 
$adsquery = mysql_query("SELECT `id`,`img` FROM `ads` WHERE `boardid`='$id' OR `boardid`='0' ORDER BY RAND() LIMIT 1");
if(mysql_num_rows($adsquery) > 0)
{
$adsinfo = mysql_fetch_array($adsquery);
$adsimg = $adsinfo["img"];
$adsid = $adsinfo["id"];
echo '';
require('incfiles/ads.php');
}

if($numrows < 1)
{
echo '<div class="display">No Topic Created Yet<br>(<a href="'.$config->url.'newtopic?board='.$id.'">Create New Topic</a>)</div>';

} else {

echo $pagination->display() .  ' (<a href="'.$config->url.'newtopic?board='.$id.'">Create New Topic</a>) '.functions::follow($id, 'board').'<p><table><tr><th>'.$st1.'';
echo '<tr><th>';

$modcheck = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND boardid = '$id'"));

$admincheck = mysql_num_rows(mysql_query("SELECT * FROM admins WHERE username = '$user'"));

$modcheck2 = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND type = 'super'"));

echo "<form action='/multitopicaction' method='POST' enctype='multipart/form-data'>"; 


$i= 0;
while($info=mysql_fetch_array($pquery)) { 
$title = functions::cleanoutput($info["subject"]);
$id2 = functions::cleanoutput($info["id"]); 
$author = functions::user_link(functions::cleanoutput($info["poster"]));
$dt = $info["date"];
$date = functions::display_date(functions::cleanoutput($info["date"]));
$lastposter = functions::user_link(functions::cleanoutput($info["lastposter"]));
$forumid = $info["boardid"];
$views = $info["views"];
$stick = $info["stick"];
$rrows=mysql_num_rows(mysql_query("SELECT * FROM posts WHERE topicid=$id2"));
$nmam = functions::converturl($title);

if($stick > 0)
{
$pt = '<img src="/icons/sticky.gif" \>';
} else {
$pt = '<img src="/icons/normal_post.gif" \>';
}

if($admincheck > 0 || $modcheck2 > 0)
{
$lnk = "<input type='checkbox' name=multi[] value=$id2>";
}
elseif($modcheck > 0)
{
$lnk = "<input type='checkbox' name=multi[] value=$id2>";
} else {
$lnk = "";
}

$pagination2 = new pagination($config->postsperpage, 1, urls::topic($title, $id2, '(page)'), $rrows);

if($i%2 == 0)
{
$css = 'w';
} else {
$css = '';
}
if(date('z', $dt) == date('z', time()))
{
$new = '<img src="/icons/new.gif">';
} else {
$new = "";
}

echo '<tr><td class="'.$css.'"><a name="'.$id2.'"></a>'.$lnk.' '.$pt.' <b><a href="'.urls::topic($title, $id2).'">'.$title.'</a> </b> '.$pagination2->display2().' '.$new.'<br><span class=s>by <b>'.$author.'</b>. <b>'.$rrows.'</b> posts & <b>'.$views.'</b> views. '.$date.' (<b>'.$lastposter.'</b>)</span>';
$i++;
}
mysql_free_result($pquery);

if($locked==0)
{
$l2="<a href='".$config->url."boardaction?action=lock&board=$id&session=$sessionkey' id='btn'>Lock</a>";
}
else
{
$l2="<a href='".$config->url."boardaction?action=unlock&board=$id&session=$sessionkey' id='btn'>Unlock</a>";
}


if($admincheck > 0 || $modcheck2 > 0)
{
$jj = "<div class='display'><input type='submit' name='delete' value='Delete'> $l2</div><p>";
}
elseif($modcheck > 0)
{
$jj = "<div class='display'><input type='submit' name='delete' value='Delete'> $l2</div><p>";
} else {
$jj = "";
}




echo '</tbody></table><p>'.$jj.' <input type="hidden" name="redirect" value="'.$self.'"><input type="hidden" name="boardid" value="'.$id.'"></form>'.$pagination->display().'  (<a href="'.$config->url.'newtopic?board='.$id.'">Create New Topic</a>) '.functions::follow($id, 'board').'';
}

$adsquery = mysql_query("SELECT `id`,`img` FROM `ads` WHERE `boardid`='$id' OR `boardid`='0' ORDER BY RAND() LIMIT 1");
if(mysql_num_rows($adsquery) > 0)
{
$adsinfo = mysql_fetch_array($adsquery);
$adsimg = $adsinfo["img"];
$adsid = $adsinfo["id"];
echo '';
require('incfiles/ads.php');
}

echo '<p class="nocopy">Viewing this board: '.$viewingboard.' and '.$onlineguest.' guest(s)</p>';
require('incfiles/end.php');
?>