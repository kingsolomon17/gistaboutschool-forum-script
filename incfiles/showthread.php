<?php
require('incfiles/init.php');
$javascript = '<script type="text/javascript" src="http://www.google-analytics.com/ga.js"></script><script type="text/javascript" src="/static/javascript.js"></script>
';
$id = (int)$_GET["id"];
$num=mysql_num_rows(mysql_query("SELECT * FROM topics WHERE id=$id"));

if($id < 1 || $num < 1)
{
$pagetitle = 'Topic Not Found';
require('incfiles/head.php');
functions::display_error('Topic Not Found');
require('incfiles/end.php');
die();
}


$tp = mysql_num_rows(mysql_query("SELECT * FROM posts WHERE topicid=$id"));
mysql_query("UPDATE topics SET posts='$tp' WHERE id='$id'");

$tinfo=mysql_fetch_assoc(mysql_query("SELECT * FROM topics WHERE id=$id"));
$fid = $tinfo["boardid"];
$locked = $tinfo["locked"];
$ffinfo=mysql_fetch_array(mysql_query("SELECT * FROM boards WHERE id=$fid"));
$ftype = $ffinfo["type"];
$fname = $ffinfo["name"];

$ffname = functions::converturl($fname);
$title=functions::cleanoutput($tinfo["subject"]);
$stick = $tinfo["stick"];
if($stick > 0)
{
$stick = "<a href='".$config->url."topicaction?action=unstick&topic=$id' id='btn'>Unstick</a>";
} else {
$stick = "<a href='".$config->url."topicaction?action=stick&topic=$id' id='btn'>stick</a>";
}

mysql_query("UPDATE topics SET views=views+1 WHERE id='$id'");

$views = functions::cleanoutput($tinfo["views"]);
$pagetitle = 'forum - ' . $title;
require('incfiles/head.php');


If($ftype == child)
{
$typeid = $ffinfo["typeid"];
$qquery = mysql_fetch_array(mysql_query("SELECT * FROM boards WHERE id=$typeid"));
$fftype = $qquery["type"];
If($fftype == child)
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


$time = time();
if(functions::isloggedin())
{
$vcheck = mysql_num_rows(mysql_query
("SELECT username FROM usersviewingtopic WHERE username='$user' AND topicid='$id'"));
if($vcheck > 0)
{
mysql_query("UPDATE usersviewingtopic SET time='$time' WHERE username='$user' AND topicid='$id'");
} else {
mysql_query("INSERT INTO usersviewingtopic SET time='$time', username='$user', topicid='$id'");
}

} else {
$time = time();
$ip = $_SERVER["REMOTE_ADDR"];
$browser = $_SERVER["HTTP_USER_AGENT"];
$vcheck = mysql_num_rows(mysql_query
("SELECT * FROM guestviewingtopic WHERE ip='$ip' AND browser='$browser' AND topicid='$id'"));
if($vcheck > 0)
{
mysql_query("UPDATE guestviewingtopic SET time='$time' WHERE ip='$ip' AND browser='$browser' AND topicid='$id'");
} else {
mysql_query("INSERT INTO guestviewingtopic SET time='$time', ip='$ip', browser='$browser', topicid='$id'");
}

}

$recent = time() - 300;
$onlinequery=mysql_query("SELECT * FROM usersviewingtopic WHERE topicid='$id' AND time>'$recent'");
$viewingtopic = "";
while($row=@mysql_fetch_array($onlinequery))
{
$viewingtopic .= functions::user_link($row["username"]) . ', ';
}
if(empty($viewingtopic))
{
$viewingtopic = "0 users";
}
$onlineguest=mysql_num_rows(mysql_query("SELECT * FROM guestviewingtopic WHERE topicid = '$id' AND time > '$recent'"));


$qu = mysql_num_rows(mysql_query("SELECT * FROM follow WHERE follower='$user' AND type='topic' AND itemid=$id"));
if($qu > 0)
{
mysql_query("UPDATE follow SET hasread='0' WHERE type='topic' AND follower='$user' AND itemid=$id");
}

$fprows=mysql_num_rows(mysql_query("SELECT * FROM topics WHERE boardid=$fid"));
   
$pagination2 = new pagination($config->topicsperpage, 1, urls::board($ffname, $fid, '(page)'), $fprows);
   

$rel = mysql_query("SELECT * FROM topics WHERE MATCH(subject) AGAINST('$title' IN BOOLEAN MODE) LIMIT 1, 3");
if(mysql_num_rows($rel) > 0)
{
$relout = "";
while($rel2 = mysql_fetch_array($rel))
{
$reltitle = functions::cleanoutput($rel2["subject"]);
$relid=$rel2["id"];
$relout .= '<a href="'.urls::topic($reltitle, $relid).'">'.$reltitle.'</a> / ';
}
}




echo '<h2>'.$title.' - '.$fname.' - '.$config->title.'</h2><p class="bold"><a href="/">'.$config->title.' Forum</a>'.$typename.' / <a href="'.urls::board($fname, $fid).'">'.$fname.'</a> / <a href="'.urls::topic($title, $id).'">'.$title.'</a> ('.$views.' Views)<p> '.$relout.' '.$pagination2->display2().'<p>';
include"ads.php";

$rowsperpage = $config->postsperpage;
$range= 10;
if(isset($_GET["page"]) && is_numeric($_GET["page"]))
{
$page = (int)$_GET["page"];
}
else
{
$page = 1;
}
$numrows = mysql_num_rows(mysql_query("SELECT * FROM posts WHERE topicid=$id"));
$totalpages = ceil($numrows/$rowsperpage);

if($page > $totalpages)
{
$page = $totalpages;
}

$offset = ($page-1)*$rowsperpage;


$pquery = mysql_query("SELECT * FROM posts WHERE topicid=$id ORDER BY id Asc LIMIT $offset, $rowsperpage");
 
function user_link($user) 
{
$sex = functions::user_info($user, 'sex');
if(strlen($sex) == 4)
{
return '<a href="/'.$user.'" class="user">'.$user.'</a>(<span
class="m">m</span>)';
}
elseif(strlen($sex) == 6)
{
return "<a href='/$user' class='user'>$user</a>(<span
class='f'>f</span>)";
}
else {
return "<a href='/$user' class='user'>$user</a>";
}

}


$pagination= new pagination($rowsperpage, $page, urls::topic($title, $id, '(page)'), $numrows);

echo $pagination->display() . ' (<a href="/newpost?topic='.$id.'"><b>Reply</b></a>) '.functions::follow($id, 'topic').' (<b><a href="#skip">Down</a></b>) ';


  $modcheck = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND boardid = '$fid'"));

$admincheck = mysql_num_rows(mysql_query("SELECT * FROM admins WHERE username = '$user'"));

$modcheck2 = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND type = 'super'"));


echo "<form action='/multipostaction' method='POST' enctype='multipart/form-data'> <p><table summary='posts'><tbody>";
$i = 0;
while($pinfo = mysql_fetch_assoc($pquery))
{
$pid = $pinfo["id"];
$poster = functions::cleanoutput($pinfo["poster"]);
$topicid = $pinfo["topicid"];
$hide = $pinfo["hide"];
$ptype = $pinfo["type"];
$message = functions::cleanoutput($pinfo["message"]);
$bbcodes = new bbcode($message);
$message = $bbcodes->display();

$pdate = functions::display_date(functions::cleanoutput($pinfo["date"]));






$title2 = ($ptype == topic) ? $title : "Re: " . $title;

if($ptype != topic)
{
$sh = "";
if($admincheck > 0 || $modcheck2 > 0)
{
$lnk = "<input type='checkbox' name=multi[] value=$pid>";
}
elseif($modcheck > 0)
{
$lnk = "<input type='checkbox' name=multi[] value=$pid>";
} else {
$lnk = "";
}
} else {
$lnk = "";
$sh = '';
echo '<!-- AddToAny BEGIN -->
<a class="a2a_dd" href="https://www.addtoany.com/share">Share</a>
<script async src="https://static.addtoany.com/menu/page.js"></script>
<!-- AddToAny END -->';

}






if($admincheck > 0 || $poster == $user || $modcheck2 > 0)
{
$alink2 = '(<a href="/editpost?post='.$pid.'&redirect='.$self.'">Modify</a>) (<a href="/newpost?topic='.$id.'&post='.$pid.'">Quote</a>)'.functions::likepost($pid).'';
} else {
$alink2 = (functions::isloggedin()) ? '(<a href="/newpost?topic='.$id.'&post='.$pid.'">Quote</a>) (<a href="/reportpost?post='.$pid.'&redirect='.$self.'">Report</a>) '.functions::likepost($pid).'' : functions::likepost($pid) ;
}
if($hide > 0)
{
if($modcheck > 0 || $admincheck > 0 || $modcheck2 > 0)
{
$alink3 = '(<a href="/hidepost?post='.$pid.'&action=unhide&session='.$sessionkey.'&redirect='.$self.'">Unhide Post</a>) ';
} else {
$message = $bbcodes->hide();
$alink1 = "";
$alink2 = "";
$alink3 = "";
}
} else {
if($modcheck > 0 || $admincheck > 0 || $modcheck2 > 0)
{
$alink3 = '(<a href="/hidepost?post='.$pid.'&action=hide&session='.$sessionkey.'&redirect='.$self.'">Hide Post</a>) ';
}
else {
$alink3 = '';
}
}




echo '<tr><td class="bold l pu"><a name="'.$pid.'"></a><a name="msg'.$pid.'"></a><a name="'.$id.'.0"></a>'.$lnk.' <a href="#'.$pid.'">'.$title2.'</a> by '.user_link($poster).': <span class="s">'.$pdate.'</span>
<tr><td class="l pd w "><div class="narrow">
'.$message.'
<p>'.$sh.'</div><p class="s">
'.$alink3.' '.$alink2.'   
';

$attachcheck = mysql_num_rows(mysql_query("SELECT * FROM attachment WHERE postid=$pid"));

if($attachcheck > 0)
{
$attachquery = mysql_query("SELECT * FROM attachment WHERE postid=$pid");

while($atinfo = mysql_fetch_array($attachquery))
{
$atname = $atinfo["name"];
$ext = $atinfo["extension"];
$atid = $atinfo["id"];
$aturl = $atinfo["url"];
$img_exts = $config->imgExtension;
if(file_exists($aturl))
{
if(!in_array($ext,$img_exts))
{
$filedata = '<p><a href="/download?id='.$atid.'" id="btn"><b>'.$atname.'</b></a>';
}
else
{
$filedata = "";
}

if(!in_array($ext,$img_exts))
{
$ffileimg = "";
}
else
{
$ffileimg = '<p><img class="attachmentimage img" src="/showimage?id='.$atid.'" />';
}
echo $filedata . $ffileimg;

}

}
}

$i++;
}
mysql_free_result($pquery);
if($locked==0)
{
$l2="<a href='".$config->url."topicaction?action=lock&topic=$id&session=$sessionkey' id='btn'>Lock</a>";
}
else
{
$l2="<a href='".$config->url."topicaction?action=unlock&topic=$id&session=$sessionkey' id='btn'>Unlock</a>";
}


$tag = mysql_num_rows(mysql_query("SELECT * FROM updates WHERE topicid = '$id'"));

if($tag > 0)
{
$tag = "<a href='".$config->url."topicaction?action=untag&topic=$id' id='btn'>Untag</a> <a href='".$config->url."topicaction?action=edittag&topic=$id' id='btn'>Edit tag</a>";
} else {
$tag = "<a href='".$config->url."topicaction?action=tag&topic=$id' id='btn'>Tag</a>";
}

if($admincheck > 0 || $modcheck2 > 0)
{
$jj = "<div class='display'><input type='submit' name='delete' value='Delete'> <input type='submit' name='hide' value='Hide'> <a href='".$config->url."topicaction?action=deletetopic&topic=$id' id='btn'>Delete Topic</a> <a href='".$config->url."topicaction?action=move&topic=$id' id='btn'>Move</a> $stick $l2 $tag</div><p>";
}
elseif($modcheck > 0)
{
$jj = "<div class='display'><input type='submit' name='hide' value='Hide'> <a href='".$config->url."topicaction?action=deletetopic&topic=$id&session=$sessionkey' id='btn'>Delete Topic</a> <a href='".$config->url."topicaction?action=move&topic=$id' id='btn'>Move</a> $l2 $tag</div><p>";
} else {
$jj = "";
}

echo '</table><p>'.$jj.'<input type="hidden" name="redirect" value="'.$self.'"><input type="hidden" name="topicid" value="'.$id.'"></form><a name="skip"></a>';
echo $pagination->display() . ' (<a href="/newpost?topic='.$id.'"><b>Reply</b></a>) '.functions::follow($id, 'topic').'';
include"ads.php";
echo '<p class="nocopy">Viewing this topic: '.$viewingtopic.' and '.$onlineguest.' guest(s)</p>';
require('incfiles/end.php');
?>