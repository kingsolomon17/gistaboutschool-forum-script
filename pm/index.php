<?php 
require('../incfiles/init.php');
if(!functions::isloggedin())
{
functions::go("/login?redirect=$self");
die();
}

$pagetitle = 'Private Message';
require('../incfiles/head.php');

$rowsperpage = 10;
if(isset($_GET["page"]) && is_numeric($_GET["page"]))
{
$page = (int)$_GET["page"];
}
else
{
$page = 1;
}

$numrows = mysql_num_rows(mysql_query("SELECT * FROM(SELECT * FROM `pms`WHERE `to`='$user' ORDER BY `hasread` Asc, `id` DESC) as wow GROUP BY `from` ORDER BY `hasread` Asc, `id` DESC"));

$totalpages = ceil($numrows/$rowsperpage);
if($page > $totalpages)
{
$page = $totalpages;
}

$offset = ($page-1)*$rowsperpage;

$query = mysql_query("SELECT * FROM(SELECT * FROM `pms`WHERE `to`='$user' ORDER BY `hasread` Asc, `id` DESC) as wow GROUP BY `from` ORDER BY `hasread` Asc, `id` DESC LIMIT $offset, $rowsperpage"); 

$pagination= new pagination($rowsperpage, $page, '/pm/(page)', $numrows);

echo '<p><h2>Private Message</h2><p><a href="/">'.$config->title.' Forum</a> / <a href="/pm">Private Message</a>';
include("../incfiles/ads.php");  
if($numrows < 1)
{
echo '<div class="display">You Have No Private Message</div>';

} else {
echo $pagination->display() ;

echo "<table summary='posts'><tbody>";
$i = 0;
while($info = mysql_fetch_assoc($query))
{
$id = $info["id"];
$message = functions::cleanoutput($info["message"]);
$sender = functions::cleanoutput($info["from"]);
$bbcodes = new bbcode($message);
$message = $bbcodes->display();
$message = strip_tags($message);
$message = functions::reducetext($message, 50);
$date = functions::ago($info["date"]);
$hasread = $info["hasread"];

$unread = mysql_num_rows(mysql_query("SELECT * FROM `pms` WHERE `to`='$user' AND `from`='$sender' AND hasread=0"));

$tmsg = mysql_num_rows(mysql_query("SELECT * FROM `pms` WHERE `from`='$sender' AND `to`='$user' OR `from`='$user' AND `to`='$sender'"));

if($hasread == 0)
{
$new = '<img src="/icons/new.gif"> ';

} else {
$new = "";
}

if($i%2 == 0)
{
$css = 'l pd w ';
} else {
$css = 'bold l pu';
}

echo '<tr><td class="'.$css.'"><div class="narrow">'.$new.' <a href="/pm/'.$sender.'">'.$sender.' ('.$unread.'/'.$tmsg.')</a><br>'.$message.'</div><span class="s"> @ '.$date.'</span>';

$i++;
}
echo '</tbody></table>';
echo ''.$pagination->display().'';
}

include("../incfiles/ads.php");  

require('../incfiles/end.php');
?>