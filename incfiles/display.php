<?php
class display{
private $sql;
   
function __construct($sql){
$this->sql = $sql;
}



public function posts()
{

global $config;
global $self;
global $user;
global $sessionkey;

echo '<p><table summary="posts"><tbody>';

$admincheck = mysql_num_rows(mysql_query("SELECT * FROM admins WHERE username = '$user'"));

$modcheck2 = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND type = 'super'"));

$modcheck = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND type != 'super'"));


echo "<form action='/multipostaction' method='POST' enctype='multipart/form-data'> ";
$i = 0;
while($pinfo = mysql_fetch_assoc($this->sql))
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


echo '<tr><td class="bold l pu"><a name="'.$pid.'"></a><a name="msg'.$pid.'"></a><a name="'.$tid.'.0"></a>'.$lnk.' <img src="/icons/xx.gif"> <a href="'.urls::board($bname, $bid).'">'.$bname.'</a> / <a href="'.urls::topic($title, $topicid, $postpage).'#'.$pid.'">'.$title2.'</a> by '.user_link($poster).': <span class="s">'.$pdate.'</span>
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
$filedata = '<p><a href="/download?id='.$atid.'" id="btn"><b>'.$atname.'</b></a></a>';
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



public function topics($alt)
{

global $config;
global $self;
global $user;


echo '<p><table>'.$alt.'';

   
$admincheck = mysql_num_rows(mysql_query("SELECT * FROM admins WHERE username = '$user'"));

$modcheck2 = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND type = 'super'"));

$modcheck = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user' AND type != 'super'"));

echo "<form action='/multitopicaction' method='POST' enctype='multipart/form-data'>"; 

$i = 0;
while($info=mysql_fetch_array($this->sql)) { 
$title = functions::cleanoutput($info["subject"]);
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



}


}

?>