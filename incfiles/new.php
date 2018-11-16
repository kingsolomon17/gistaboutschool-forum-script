<?php
require('incfiles/init.php');
$pagetitle = 'New Topics';
require('incfiles/head.php');
require('incfiles/display.php');



$rowsperpage = $config->topicsperpage;
$range= 10;
if(isset($_GET["page"]) && is_numeric($_GET["page"]))
{
$page = (int)$_GET["page"];
}
else
{
$page = 1;
}
$offset = ($page-1)*$rowsperpage;
$limit = $rowsperpage * 20;
$numrows = mysql_num_rows(mysql_query("SELECT * FROM topics LIMIT $limit"));
$totalpages = ceil($numrows/$rowsperpage);



$pquery = mysql_query("SELECT * FROM topics ORDER BY date DESC LIMIT $offset, $rowsperpage");


$pagination= new pagination($config->topicsperpage, $page, '/new/(page)', $numrows);

echo '<a name="top"></a><p><h2>New Topics</h2><p>';
   
if($numrows < 1)
{
echo '<div class="display">No New Topics Yet</div>';

} else {
include"/ads.php";
echo $pagination->display();


$display = new display($pquery);


$display->topics();


echo $pagination->display();
include"/ads.php";
}

   

require('incfiles/end.php');
?> 