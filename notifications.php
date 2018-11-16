<?php
require('incfiles/init.php');
if(!functions::isloggedin())
{
functions::go($config->url);
die();
}
$pagetitle = 'Notifications';
require('incfiles/head.php');
require('incfiles/display.php');

$rowsperpage = 10;
if(isset($_GET["page"]) && is_numeric($_GET["page"]))
{
$page = (int)$_GET["page"];
}
else
{
$page = 1;
}


$notread = mysql_num_rows(mysql_query("SELECT `hasread` FROM `notifications` WHERE `hasread`=0 AND `to`='$user'"));

$numrows = mysql_num_rows(mysql_query("SELECT * FROM `notifications` WHERE `to`='$user'".($notread > 0 ? ' LIMIT '.$notread.'' : '').""));

$totalpages = ceil($numrows/$rowsperpage);
if($page > $totalpages)
{
$page = $totalpages;
}

$offset = ($page-1)*$rowsperpage;

$query = mysql_query("SELECT * FROM `notifications` WHERE `to`='$user' ORDER BY `hasread` Asc, `date` DESC LIMIT ".($notread > 0 ? $notread : ''.$offset.', '.$rowsperpage.'').""); 


$pagination= new pagination($rowsperpage, $page, '/notifications/(page)', $numrows);

echo '<a name="top"></a><p><h2>Notifications</h2><p><a href="/">'.$config->title.' Forum</a> / <a href="/notifications">Notifications</a>';
include"incfiles/ads.php";  
if($numrows < 1)
{
echo '<div class="display">Notifications Yet</div>';

} else {
echo $pagination->display() ;

echo "<table summary='posts'><tbody>";
$i = 0;
while($info = mysql_fetch_assoc($query))
{
$id = $info["id"];
$message = functions::cleanoutput($info["message"]);
$bbcodes = new bbcode($message);
$message = $bbcodes->display();
$date = functions::display_date(functions::cleanoutput($info["date"]));
$hasread = $info["hasread"];
if($hasread == 0)
{
$new = '<img src="/icons/new.gif"> ';
mysql_query("UPDATE `notifications` SET `hasread`=1  WHERE `to`='$user' AND `id`=$id");
} else {
$new = "";
}

if($i%2 == 0)
{
$css = 'l pd w ';
} else {
$css = 'bold l pu';
}

echo '<tr><td class="'.$css.'"><div class="narrow">
'.$new.$message.'</div><span class="s"> @ '.$date.'</span>';

$i++;
}
echo '</tbody></table>';
echo ''.$pagination->display().'';
}

include"incfiles/ads.php";  

require('incfiles/end.php');
?>