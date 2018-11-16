<?php
require('incfiles/init.php');

if(!functions::isloggedin())
{
functions::go($config->url);
die();
}
$pagetitle = "Latest Topics On Boards You're Following";
require('incfiles/head.php');
require('incfiles/display.php');

$fcheck = mysql_query("SELECT * FROM `follow` WHERE `follower`='$user' AND `type`='board'");

if(mysql_num_rows($fcheck) < 1)
{
functions::display_error("You Havn't Followed Any Board Yet");
require('incfiles/end.php');
die();

}
mysql_free_result($fcheck);

$rowsperpage = $config->topicsperpage;
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

$fquery = mysql_query("SELECT * FROM `follow` WHERE `follower`='$user' AND `type`='board'");
while($finfo = mysql_fetch_array($fquery))
{
$following .= "boardid='" . $finfo["itemid"] . "' OR ";
}
$following .= "boardid='ggggggggfffffffsggsggsgsggsgsghhhshdfffffffffffdfdfdffd'";

mysql_free_result($fquery);


$numrows = mysql_num_rows(mysql_query("SELECT * FROM topics WHERE $following LIMIT $limit")) or mysql_error();

$totalpages = ceil($numrows/$rowsperpage);
if($page > $totalpages)
{
$page = $totalpages;
}

$offset = ($page-1)*$rowsperpage;

$pquery = mysql_query("SELECT * FROM topics WHERE $following ORDER BY date DESC LIMIT $offset, $rowsperpage") or mysql_error();


$pagination= new pagination($rowsperpage, $page, '/newtopics/(page)', $numrows);

include"incfiles/ads.php";   

$fquery = mysql_query("SELECT * FROM `follow` WHERE `follower`='$user' AND `type`='board'");
while($finfo = mysql_fetch_array($fquery))
{
$bid = $finfo["itemid"];
$binfo = mysql_fetch_array(mysql_query("SELECT `name` FROM `boards` WHERE `id`='$bid'"));
$bname = $binfo["name"];
$following2 .=  '<a href="'.urls::board($bname, $bid).'">'.$bname.'</a>, ';
}
echo '<p><table summary="posts"><tbody><tr><td class="bold l pu"><b>You Are Following</b>:<tr><td class="l pd w ">';
echo functions::cleanlast($following2, 2);
echo '</table>';
mysql_free_result($fquery);

echo "<p><h2>Latest Topics On Boards You're Following</h2><p><a href='/'>".$config->title." Forum</a> / <a href='/newtopics'>Followed Boards</a>";

include"incfiles/ads.php";   

if($numrows < 1)
{
echo '<div class="display"> No Recent Topics Yet</div>';

} else {
include"ads.php";
echo $pagination->display();

$display = new display($pquery);

$display->topics();

echo $pagination->display();

mysql_query("UPDATE follow SET `hasread`='0' WHERE `follower`='$user' AND `type`='board'");
}
require('incfiles/end.php');
?>