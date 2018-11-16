<?php
require('incfiles/init.php');
require('incfiles/head.php');
$limit = 15;
$urows = mysql_num_rows( mysql_query("SELECT * FROM `down` WHERE type='1'"));
$pagination2 = new pagination($limit, 1, ''.$config->url.'clickdown/(page)', $urows);

$adsquery = mysql_query("SELECT `id`,`img`,`title`, `url` FROM down WHERE `type`='1' ORDER BY `id` DESC LIMIT 15");
$num = mysql_num_rows($adsquery);
if($num==0)
{
functions::display_error('No Downloads yet');
}
else
{
	echo' <p><table summary="sp"><tr><td class=""><h3>Downloads</h3></td>';
while($adsinfo=mysql_fetch_array($adsquery))
{
$adstitle = $adsinfo["title"];
$adsimg = $adsinfo["img"];
$adsid = $adsinfo["id"];
$adsurl = $adsinfo["url"];


echo '<tr><td class="sp"><a href="'.$adsurl.'" rel="nofollow"><img src="'.$adsimg.'"><h3> '.$adstitle.'</h3></a></td>
 ';
}
echo '<tr><td class="1 w"> <center><a
href="'.$config->url.'clickdown">(1)</a> '.$pagination2->display2() . '</center></table>';
}
echo '<p><table><tr><td><a href="/download.php"><b>Go back to download page</b><tr><td></table>';
mysql_free_result($adsquery);
require('incfiles/end.php');
?>
