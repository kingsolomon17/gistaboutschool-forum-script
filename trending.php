<?php
require('incfiles/init.php');
$pagetitle = 'Trending Topics';

require('incfiles/head.php');
require('incfiles/display.php');

$rowsperpage = $config->topicsperpage;
if(isset($_GET["page"]) && is_numeric($_GET["page"]))
{
$page = (int)$_GET["page"];
}
else
{
$page = 1;
}

$limit = $rowsperpage * 10;
$numrows = mysql_num_rows(mysql_query("SELECT * FROM topics LIMIT $limit"));
$totalpages = ceil($numrows/$rowsperpage);

if($page > $totalpages)
{
$page = $totalpages;
}

$offset = ($page-1)*$rowsperpage;


$pquery = mysql_query("SELECT * FROM topics ORDER BY lastpostdate DESC LIMIT $offset, $rowsperpage");


$pagination= new pagination($config->topicsperpage, $page, '/trending/(page)', $numrows);

echo '<p><h2>Trending Topics</h2><p><a href="/">'.$config->title.' Forum</a> / <a href="/recent">Trending Topics</a>';

include"incfiles/ads.php";   

if($numrows < 1)
{
echo '<div class="display">No Trending Topics Yet</div>';

} else {
echo $pagination->display();

$display = new display($pquery);

$display->topics();

echo $pagination->display();
}

include"incfiles/ads.php";  

require('incfiles/end.php');
?>