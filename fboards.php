<?php
require('incfiles/init.php');
$pagetitle = 'followed Boards';
require('incfiles/head.php');
require('incfiles/display.php');

if(!functions::isloggedin())
{
functions::go($config->url);
die();
}

echo '<a name="top"></a><p><h2>Followed Boards</h2><p><a href="/">'.$config->title.' Forum</a> / <a href="/followedboards">Followed Boards</a><p>';
   
$query = mysql_query("SELECT * FROM `follow` WHERE `follower`='$user' AND `type`='board' ORDER BY `hasread` DESC");

if(mysql_num_rows($query) < 1)
{
echo '<div class="display">No Followd Boards Yet</div>';

} else {
include"ads.php";
echo '<table>

$i = 0;
while($finfo=mysql_fetch_array($query)) { 
$bid = $finfo["itemid"];
$hasread = $finfo["hasread"];

$info=mysql_fetch_array(mysql_query("SELECT * FROM `boards` WHERE `id`=$bid"));
$name = functions::cleanoutput($info["name"]);

if($i%2 == 0)
{
$css = 'w';
} else {
$css = '';
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
include"ads.php";
}

   

require('incfiles/end.php');
?>