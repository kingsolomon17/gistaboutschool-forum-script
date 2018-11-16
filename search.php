<?php
require('incfiles/init.php');
$pagetitle = 'Search';
require('incfiles/head.php');


if(isset($_POST['sa']))
{
$content = functions::converturl($_POST['q']);
$surl = str_replace('http://', 'site:', $config->url);
$surl = str_replace('/', '', $surl);
$content = str_replace('-', '+', $content);
$url = 'http://www.google.com.ng/search?q='.$surl.'+'.$content.'';

functions::go($url);
}

echo '<a name="top"></a><p><h2>Search</h2><p><a href="/">'.$config->title.' Forum</a> / <a href="#">Local Search</a>';

include"incfiles/ads.php";

function ReplaceKeywords($search, $text) {
$search = str_replace('*', '', $search);
return mb_strlen($search) < 3 ? $text : preg_replace('|('.preg_quote($search, '/').')|siu','<span style="background-color: grey">$1</span>',$text);
}


$search_post = isset($_POST['q']) ? trim($_POST['q']) : false;
$search_get = isset($_GET['q']) ? rawurldecode(trim($_GET['q'])) : false;
$search = $search_post ? $search_post : ($search_get ? $search_get : false) ;


echo '<div class="display"><form action="/search" method="post">
<input type="text" value="' . ($search ? functions::checkout($search) : '') . '" name="q" placeholder="Search Here!" /><br><input type="submit" value="search" name="localsearch" /><br><input name="t" type="checkbox" value="1" ' . ($search_t ? 'checked="checked"' : '') . ' />&nbsp; search_topic_name</p></form></div>';


$error = false;
if($search && (mb_strlen($search) < 2 || mb_strlen($search) > 64))
{
$error = 'error_search_length';
}

if($search && !$error) {
$array = explode(' ', $search);
$count = count($array);
$query = mysql_real_escape_string($search);

$qfrom = $search_t ? 'topics' : 'posts' ;

$qitem = $search_t ? 'subject' : 'message' ;

$total = mysql_num_rows(mysql_query("SELECT * FROM $qfrom WHERE MATCH ($qitem) AGAINST('$query' IN BOOLEAN MODE)"));

if($total < 1)
{

echo '<div class="display">No Search Result</div>';

} else {

$rowsperpage = $search_t ? ''.$config->topicsperpage.'' : ''.$config->postsperpage.'' ;
if(isset($_GET["page"]) && is_numeric($_GET["page"]))
{
$page = (int)$_GET["page"];
}
else
{
$page = 1;
}

$limit = $rowsperpage * 20;
$numrows = mysql_num_rows(mysql_query("SELECT * FROM $qfrom WHERE MATCH ($qitem) AGAINST('$query' IN BOOLEAN MODE) LIMIT $limit"));

$totalpages = ceil($numrows/$rowsperpage);

if($page > $totalpages)
{
$page = $totalpages;
}

$offset = ($page-1)*$rowsperpage;


$pquery = mysql_query("SELECT * FROM $qfrom WHERE MATCH ($qitem) AGAINST('$query' IN BOOLEAN MODE) LIMIT $offset, $rowsperpage"); 

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


$pagination= new pagination($rowsperpage, $page, '/search/'.functions::checkout($search).'/(page)'.($search_t ? '/'.'t' : '').'', $numrows);

echo $pagination->display() ;

if($search_t)
{
$admincheck = mysql_num_rows(mysql_query("SELECT * FROM admins WHERE username = '$user'"));

$modcheck2 = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND type = 'super'"));

$modcheck = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND type != 'super'"));

echo "<table><form action='/multitopicaction' method='POST' enctype='multipart/form-data'>"; 

$i = 0;
while($info=mysql_fetch_array($pquery)) { 
$title = functions::cleanoutput($info["subject"]);
foreach($array as $val){
$title = ReplaceKeywords($val, $title);
}

$id2 = functions::cleanoutput($info["id"]); 
$author = functions::user_link(functions::cleanoutput($info["poster"]));
$dt = $info["date"];
$date = functions::display_date(functions::cleanoutput($info["date"]));
$lastposter = functions::user_link(functions::cleanoutput($info["lastposter"]));
$bid = functions::cleanoutput($info["boardid"]);
$stick = functions::cleanoutput($info["stick"]);
$views = functions::cleanoutput($info["views"]);
$rrows = mysql_num_rows(mysql_query("SELECT * FROM posts WHERE topicid=$id2"));
$nmam = functions::converturl($title);

$binfo = mysql_fetch_array(mysql_query("SELECT * FROM boards WHERE id=$bid"));
$bname = $binfo["name"];
$btype = $binfo["type"];

 $modcheck3 = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND boardid = '$bid'"));


if($admincheck > 0 || $modcheck2 > 0 || $modcheck3 > 0)
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


echo '<tr><td class="'.$css.'"><a name="'.$id2.'"></a>'.$lnk.' ' . ($stick > 0 ? '<img src="/icons/sticky.gif" \>' : '<img src="/icons/normal_post.gif"> ') . ' <b><a href="'.urls::board($bname, $bid).'">'.$bname.'</a></b> / <b><a href="'.urls::topic($title, $id2).'">'.$title.'</a> </b> '.$pagination2->display2().' '.$new.'<br><span class=s>by <b>'.$author.'</b>. <b>'.$rrows.'</b> posts & <b>'.$views.'</b> views. '.$date.' (<b>'.$lastposter.'</b>)</span>';
$i++;
}
mysql_free_result($pquery);   
if($admincheck > 0 || $modcheck2 > 0 || $modcheck > 0)
{
$jj = "<div class='display'><input type='submit' name='delete' value='Delete'> $l2</div><p>";
} else {
$jj = "";
}




echo '</table><p>'.$jj.' <input type="hidden" name="redirect" value="'.$self.'"></form>';

} else {

$admincheck = mysql_num_rows(mysql_query("SELECT * FROM admins WHERE username = '$user'"));

$modcheck2 = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND type = 'super'"));

$modcheck = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND type != 'super'"));


echo "<table><form action='/multipostaction' method='POST' enctype='multipart/form-data'> ";
$i = 0;
while($pinfo = mysql_fetch_assoc($pquery))
{
$pid = $pinfo["id"];
$poster = functions::cleanoutput($pinfo["poster"]);
$topicid = $pinfo["topicid"];
$hide = $pinfo["hide"];
$position = $pinfo["position"];
$postpage =  ceil($position/$config->postsperpage);
if($postpage < 1)
{
$postpage = 1;
} else {
$postpage = $postpage;
}

$ptype = $pinfo["type"];
$message = functions::cleanoutput($pinfo["message"]);
foreach($array as $val){
$message = ReplaceKeywords($val, $message);
}

$bbcodes = new bbcode($message);
$message = $bbcodes->display();
$pdate = functions::display_date(functions::cleanoutput($pinfo["date"]));

$tinfo=mysql_fetch_assoc(mysql_query("SELECT * FROM topics WHERE id=$topicid"));
$title=functions::cleanoutput($tinfo["subject"]);
$bid = $tinfo["boardid"];
$binfo=mysql_fetch_assoc(mysql_query("SELECT * FROM boards WHERE id=$bid"));
$bname=functions::cleanoutput($binfo["name"]);

  $modcheck3 = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND boardid = '$bid'"));


  
$title2 = ($ptype == topic) ? $title : "Re: " . $title;

if($ptype != topic)
{
if($admincheck > 0 || $modcheck2 > 0 || $modcheck3 > 0)
{
$lnk = "<input type='checkbox' name=multi[] value=$pid>";
} else {
$lnk = "";
}
} else {
$lnk = "";
}

if($admincheck > 0 || $poster == $user || $modcheck2 > 0)
{
$alink2 = '(<a href="/editpost?post='.$pid.'&redirect='.$self.'#'.$pid.'">Modify</a>) (<a href="/newpost?topic='.$topicid.'&post='.$pid.'">Quote</a>)'.functions::likepost($pid).'';
} else {
$alink2 = (functions::isloggedin()) ? '(<a href="/newpost?topic='.$topicid.'&post='.$pid.'">Quote</a>) (<a href="/reportpost?post='.$pid.'&redirect='.$self.'#'.$pid.'">Report</a>) '.functions::likepost($pid).'' : functions::likepost($pid) ;
}
if($hide > 0)
{
if($admincheck > 0 || $modcheck2 > 0 || $modcheck3 > 0)
{
$alink3 = '(<a href="/hidepost?post='.$pid.'&action=unhide&session='.$sessionkey.'&redirect='.$self.'#'.$pid.'">Unhide Post</a>) ';
} else {
$message = $bbcodes->hide();
$alink1 = "";
$alink2 = "";
$alink3 = "";
}
} else {
if($admincheck > 0 || $modcheck2 > 0 || $modcheck3 > 0)
{
$alink3 = '(<a href="/hidepost?post='.$pid.'&action=hide&session='.$sessionkey.'&redirect='.$self.'#'.$pid.'">Hide Post</a>) ';
}
else {
$alink3 = '';
}
}


echo '<tr><td class="bold l pu"><a name="'.$pid.'"></a><a name="msg'.$pid.'"></a><a name="'.$tid.'.0"></a><img src="/icons/xx.gif"> <a href="'.urls::board($bname, $bid).'">'.$bname.'</a> / <a href="'.urls::topic($title, $topicid, $postpage).'#'.$pid.'">'.$lnk.' '.$title2.'</a> by '.user_link($poster).': <span class="s">'.$pdate.'</span>
<tr><td class="l pd w "><div class="narrow">
'.$message.'
<p>'.$sh.'</div><p class="s">
'.$alink3.' '.$alink2.'   
';

if($hide > 0 && $modcheck < 1 && $admincheck < 1 && $modcheck2 < 1)
{
$attachcheck = '';
} else {

$attachcheck = mysql_num_rows(mysql_query("SELECT * FROM attachment WHERE postid=$pid"));

if($attachcheck > 0)
{
$attachquery = mysql_query("SELECT * FROM attachment WHERE postid=$pid");

while($atinfo = mysql_fetch_array($attachquery))
{
$atname = $atinfo["name"];
$aturl = $atinfo["url"];
$ext = $atinfo["extension"];
$atid = $atinfo["id"];
$img_exts = $config->imgExtension;
if(file_exists($config->attachmentFolder.$aturl))
{
if(!in_array($ext,$img_exts))
{
$filedata = '<p><a href="/download?id='.$atid.'" id="btn"><b>'.$atname.'</b></a>';
if($admincheck > 0 || $modcheck2 > 0 || $modcheck3 > 0)
{
$filedata .= ' <a href="/deleteattachment?id='.$atid.'&redirect='.$self.'#'.$pid.'">del</a>';
}
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
$ffileimg = '<p><img class="attachmentimage img" src="/showimage?id='.$atid.'" /></a>';
if($admincheck > 0 || $modcheck2 > 0 || $modcheck3 > 0)
{
$ffileimg .= '<a href="/deleteattachment?id='.$atid.'&redirect='.$self.'#'.$pid.'">del</a>';
}
}
echo $filedata . $ffileimg;

}
}
}

}
$i++;
}
mysql_free_result($pquery);

if($admincheck > 0 || $modcheck2 > 0)
{
$jj = "<div class='display'><input type='submit' name='delete' value='Delete'> <input type='submit' name='hide' value='Hide'></div><p>";
}
elseif($modcheck > 0)
{
$jj = "<div class='display'><input type='submit' name='hide' value='Hide'></div><p>";
} else {
$jj = "";
}

echo '</table><p>'.$jj.'<input type="hidden" name="redirect" value="'.$self.'"></form>';


}








echo ''.$pagination->display().'';


}
} else {
functions::display_error($error);
}

include"incfiles/ads.php";

require('incfiles/end.php');
?>