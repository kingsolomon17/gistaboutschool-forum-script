<?php
require('incfiles/init.php');
$pagetitle = 'Recent Posts';
require('incfiles/head.php');
echo '<meta name="viewport" content="width=device-width, initial-scale=1" />';
require('incfiles/display.php');
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
$numrows = mysql_num_rows(mysql_query("SELECT * FROM posts LIMIT $limit"));

$totalpages = ceil($numrows/$rowsperpage);
if($page > $totalpages)
{
$page = $totalpages;
}

$offset = ($page-1)*$rowsperpage;

$pquery = mysql_query("SELECT * FROM posts ORDER BY date DESC LIMIT $offset, $rowsperpage"); 

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


$pagination= new pagination($config->postsperpage, $page, '/recent/(page)', $numrows);

echo '<p><h2>Recent Posts</h2><p><a href="/">'.$config->title.' Forum</a> / <a href="/recent">Recent Post</a>';

include"incfiles/ads.php";

if($numrows < 1)
{
echo '<div class="display">No Recent Posts Yet</div>';

} else {
echo $pagination->display() ;

$display = new display($pquery);

$display->posts();


echo ''.$pagination->display().'';
}

include"incfiles/ads.php";

require('incfiles/end.php');
?>