<?php 
require('../incfiles/init.php');
if(!functions::isloggedin())
{
functions::go("/login?redirect=$self");
die();
}

$pagetitle = 'Downloading...';
require('../incfiles/head.php');

$rowsperpage = 3;
if(isset($_GET["page"]) && is_numeric($_GET["page"]))
{
$page = (int)$_GET["page"];
}
else
{
$page = 1;
}

$numrows = mysql_num_rows(mysql_query("SELECT * FROM(SELECT * FROM `downloads`WHERE `to`='$user' ORDER BY `hasread` Asc, `id` DESC) as wow GROUP BY `from` ORDER BY `hasread` Asc, `id` DESC"));

$totalpages = ceil($numrows/$rowsperpage);
if($page > $totalpages)
{
$page = $totalpages;
}

$offset = ($page-1)*$rowsperpage;

$query = mysql_query("SELECT * FROM(SELECT * FROM `pms`WHERE `to`='$user' ORDER BY `hasread` Asc, `id` DESC) as wow GROUP BY `from` ORDER BY `hasread` Asc, `id` DESC LIMIT $offset, $rowsperpage"); 






include("../incfiles/ads.php");  

require("../incfiles/end.php");
?>