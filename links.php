<?php
require('incfiles/init.php');
$pagetitle = 'Featured Links';
require('incfiles/head.php');

   
$rowsperpage = 65;
if(isset($_GET["page"]) && is_numeric($_GET["page"]))
{
$page = (int)$_GET["page"];
}
else
{
$page = 1;
}

$numrows = mysql_num_rows(mysql_query("SELECT * FROM updates"));

$totalpages = ceil($numrows/$rowsperpage);
if($page > $totalpages)
{
$page = $totalpages;
}

$offset = ($page-1)*$rowsperpage;

$query = mysql_query("SELECT * FROM updates ORDER BY id DESC LIMIT $offset, $rowsperpage");


$pagination= new pagination(65, $page, '/links/(page)', $numrows);

echo '<a name="top"></a><p><h2>Featured Links</h2><p><a href="/">'.$config->title.' Forum</a> / <a href="/links">Featured Links</a>';
include"incfiles/ads.php";
   
if($numrows < 1)
{
echo '<div class="display">No Updates Yet</div>';

} else {
echo $pagination->display() .  '<p><table>';
$i = 0;
while($uinfo=mysql_fetch_array($query))
{
$url = functions::cleanoutput($uinfo["url"]);
$title = functions::cleanoutput($uinfo["title"]);
$by = functions::user_link(functions::cleanoutput($uinfo["tagger"]));
$date = functions::display_date(functions::cleanoutput($uinfo["date"]));
$id = $uinfo["id"];
if($i%2 == 0)
{
$css = 'w';
} else {
$css = '';
}

echo '<tr><td class="'.$css.'"><a
href="'.$url.'"><b>'.$title.'</b></a><br><span class=s>by <b>'.$by.'</b>. '.$date.'</span>';
$i++;
}
echo '</table><p> '.$pagination->display().'';

}

mysql_free_result($query);

include"incfiles/ads.php";

require('incfiles/end.php');
?>