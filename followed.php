<?php
require('incfiles/init.php');
$pagetitle = 'followed Topics';
require('incfiles/head.php');
require('incfiles/display.php');

if(!functions::isloggedin())
{
functions::go($config->url);
die();
}


$rowsperpage = $config->topicsperpage;
if(isset($_GET["page"]) && is_numeric($_GET["page"]))
{
$page = (int)$_GET["page"];
}
else
{
$page = 1;
}

$numrows = mysql_num_rows(mysql_query("SELECT * FROM follow WHERE follower='$user' AND type='topic'"));

$totalpages = ceil($numrows/$rowsperpage);
if($page > $totalpages)
{
$page = $totalpages;
}

$offset = ($page-1)*$rowsperpage;

$pquery2 = mysql_query("SELECT * FROM follow WHERE follower='$user' AND type='topic' ORDER BY hasread DESC LIMIT $offset, $rowsperpage");


$pagination= new pagination($config->topicsperpage, $page, '/followed/(page)', $numrows);

echo '<p><h2>Followed Topics</h2><p><a href="/">'.$config->title.' Forum</a> / <a href="/followed">Followed Topics</a>';

include"incfiles/ads.php";   

if($numrows < 1)
{
echo '<div class="display">No Followd Topics Yet</div>';

} else {
echo $pagination->display() .  '<p>';
echo '<table><tr><th>Followed Topics: (<a href="/do_markallasread?session='.$sessionkey.'&redirect='.$self.'">Clear Unviewed Topics</a>)';

   
$admincheck = mysql_num_rows(mysql_query("SELECT * FROM admins WHERE username = '$user'"));
$modcheck2 = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND type = 'super'"));

$modcheck = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND type != 'super'"));

echo "<form action='/multitopicaction' method='POST' enctype='multipart/form-data'>"; 

$i = 0;
while($finfo=mysql_fetch_array($pquery2)) { 
$tid = $finfo["itemid"];
$hasread = $finfo["hasread"];
$info=mysql_fetch_array(mysql_query("SELECT * FROM topics WHERE id=$tid"));
$title = functions::cleanoutput($info["subject"]);
$id2 = functions::cleanoutput($info["id"]); 
$author = functions::user_link(functions::cleanoutput($info["poster"]));
$dt = $info["date"];
$date = functions::display_date(functions::cleanoutput($info["date"]));
$lastposter = functions::user_link(functions::cleanoutput($info["lastposter"]));
$bid = functions::cleanoutput($info["boardid"]);
$views = functions::cleanoutput($info["views"]);
$stick = functions::cleanoutput($info["stick"]);
$rrows = mysql_num_rows(mysql_query("SELECT * FROM posts WHERE topicid=$id2"));
$nmam = functions::converturl($title);

$binfo = mysql_fetch_array(mysql_query("SELECT * FROM boards WHERE id=$bid"));
$bname = $binfo["name"];
$btype = $binfo["type"];


  $modcheck3 = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND boardid = '$bid'"));


if($admincheck > 0 || $modcheck2 > 0 || $modcheck3 > 0)
{
$lnk = "<p><input type='checkbox' name=multi[] value=$id2>";
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


echo '<tr><td class="'.$css.'"><a name="'.$id2.'"></a>'.$lnk.' ' . ($stick > 0 ? '<img src="/icons/sticky.gif" \>' : '<img src="/icons/normal_post.gif"> ') . ' <b><a href="'.urls::board($bname, $bid).'">'.$bname.'</a></b> / <b><a href="'.urls::topic($title, $id2).'">'.$title.'</a> </b> '.$pagination2->display2().' '.$new.'<br><span class=s>by <b>'.$author.'</b>. <b>'.$rrows.'</b> posts, <b>'.$views.'</b> views & <b>'.$hasread.'</b> Unread. '.$date.' (<b>'.$lastposter.'</b>)</span>';
$i++;
}
mysql_free_result($pquery);   
if($admincheck > 0 || $modcheck2 > 0 || $modcheck > 0)
{
$jj = "<div class='display'><input type='submit' name='delete' value='Delete'> $l2</div><p>";
} else {
$jj = "";
}




echo '</table><p>'.$jj.' <input type="hidden" name="redirect" value="'.$self.'"></form> '.$pagination->display().'';
}
include"incfiles/ads.php";   

require('incfiles/end.php');
?>