<?php
require('incfiles/init.php');

if(!functions::isloggedin())
{
functions::go($config->url);
die();
}
$pagetitle = "Latest Posts By People You're Following";
require('incfiles/head.php');
require('incfiles/display.php');
$fcheck = mysql_query("SELECT * FROM usersfollow WHERE follower='$user'");

if(mysql_num_rows($fcheck) < 1)
{
functions::display_error("You Havn't Followed Any Member Yet");
require('incfiles/end.php');
die();

}
mysql_free_result($fcheck);

$rowsperpage = $config->postsperpage;
if(isset($_GET["page"]) && is_numeric($_GET["page"]))
{
$page = (int)$_GET["page"];
}
else
{
$page = 1;
}

$limit = $rowsperpage * 20;


$following = "";

$fquery = mysql_query("SELECT * FROM usersfollow WHERE follower='$user'");
while($finfo = mysql_fetch_array($fquery))
{
$following .= "poster='" . $finfo["following"] . "' OR ";
}
$following .= "poster='ggggggggfffffffsggsggsgsggsgsghhhshdfffffffffffdfdfdffd'";

mysql_free_result($fquery);


$numrows = mysql_num_rows(mysql_query("SELECT * FROM posts WHERE $following LIMIT $limit")) or mysql_error();

$totalpages = ceil($numrows/$rowsperpage);
if($page > $totalpages)
{
$page = $totalpages;
}

$offset = ($page-1)*$rowsperpage;

$pquery = mysql_query("SELECT * FROM posts WHERE $following ORDER BY date DESC LIMIT $offset, $rowsperpage") or mysql_error();

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


$pagination= new pagination($config->postsperpage, $page, '/following/(page)', $numrows);

echo "<p><h2>Latest Posts By People You're Following</h2><p><a href='/'>".$config->title." Forum</a> / <a href='/following'>Following</a>";

include"incfiles/ads.php";

if($numrows < 1)
{
echo '<div class="display"> No Recent Posts Yet</div>';

} else {
echo $pagination->display();

$display = new display($pquery);

$display->posts();

echo $pagination->display();

mysql_query("UPDATE usersfollow SET hasread='0' WHERE follower='$user'");
}

include"incfiles/ads.php";

$fquery = mysql_query("SELECT * FROM usersfollow WHERE follower='$user'");
while($finfo = mysql_fetch_array($fquery))
{
$following2 .= functions::user_link(functions::cleanoutput($finfo["following"])) . ', ';
}
echo '<p><table summary="posts"><tbody><tr><td class="bold l pu">Following:<tr><td class="l pd w ">';
echo functions::cleanlast($following2, 2);
echo '</table>';
mysql_free_result($fquery);
require('incfiles/end.php');
?>