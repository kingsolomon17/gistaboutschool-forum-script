<?php
require('incfiles/init.php');
require('incfiles/head.php');
$limit = 10;
$urows = mysql_num_rows( mysql_query("SELECT * FROM `downs`"));
$pagination2 = new pagination($limit, 1, ''.$config->url.'downloading/(page)', $urows);

$doquery = mysql_query("SELECT `id`,`name` FROM downs ORDER BY `id` ASC LIMIT 10");
$num = mysql_num_rows($doquery);
if($num==0)
{
functions::display_error('No Downloads Categories Yet');
}
else
{
	echo' <p><table summary="sp"><tr><td class=""><h3>Downloads Categories</h3></td>';
while($doinfo=mysql_fetch_array($doquery))
{
	
$dotitle = $doinfo["name"];
$doid = $doinfo["id"];

if($doid == 1){
	echo '<tr><td class="w"><a href="'.$dotitle.'.php" rel="nofollow"><h3> '.$dotitle.'</h3></a></td>';
}elseif($doid == 2){
	echo '<tr><td class="w"><a href="'.$dotitle.'.php" rel="nofollow"><h3> '.$dotitle.'</h3></a></td>';
}elseif($doid == 3){
	echo '<tr><td class="w"><a href="'.$dotitle.'.php" rel="nofollow"><h3> '.$dotitle.'</h3></a></td>';
}elseif($doid == 4){
	echo '<tr><td class="w"><a href="'.$dotitle.'.php" rel="nofollow"><h3> '.$dotitle.'</h3></a></td>';
}elseif($doid == 5){
	echo '<tr><td class="w"><a href="'.$dotitle.'.php" rel="nofollow"><h3> '.$dotitle.'</h3></a></td>';
}elseif($doid == 6){
	echo '<tr><td class="w"><a href="'.$dotitle.'.php" rel="nofollow"><h3> '.$dotitle.'</h3></a></td>';
}elseif($doid == 7){
	echo '<tr><td class="w"><a href="'.$dotitle.'.php" rel="nofollow"><h3> '.$dotitle.'</h3></a></td>';
}elseif($doid == 8){
	echo '<tr><td class="w"><a href="'.$dotitle.'.php" rel="nofollow"><h3> '.$dotitle.'</h3></a></td>';
}elseif($doid == 9){
	echo '<tr><td class="w"><a href="'.$dotitle.'.php" rel="nofollow"><h3> '.$dotitle.'</h3></a></td>';
}elseif($doid == 10){
	echo '<tr><td class="w"><a href="'.$dotitle.'.php" rel="nofollow"><h3> '.$dotitle.'</h3></a></td>';
}elseif($doid == 11){
	echo '<tr><td class="w"><a href="'.$dotitle.'.php" rel="nofollow"><h3> '.$dotitle.'</h3></a></td>';
}elseif($doid == 12){
	echo '<tr><td class="w"><a href="'.$dotitle.'.php" rel="nofollow"><h3> '.$dotitle.'</h3></a></td>';
}elseif($doid == 13){
	echo '<tr><td class="w"><a href="'.$dotitle.'.php" rel="nofollow"><h3> '.$dotitle.'</h3></a></td>';
}elseif($doid == 14){
	echo '<tr><td class="w"><a href="'.$dotitle.'.php" rel="nofollow"><h3> '.$dotitle.'</h3></a></td>';
}elseif($doid == 15){
	echo '<tr><td class="w"><a href="'.$dotitle.'.php" rel="nofollow"><h3> '.$dotitle.'</h3></a></td>';
}elseif($doid == 16){
	echo '<tr><td class="w"><a href="'.$dotitle.'.php" rel="nofollow"><h3> '.$dotitle.'</h3></a></td>';
}elseif($doid == 17){
	echo '<tr><td class="w"><a href="'.$dotitle.'.php" rel="nofollow"><h3> '.$dotitle.'</h3></a></td>';
}elseif($doid == 18){
	echo '<tr><td class="w"><a href="'.$dotitle.'.php" rel="nofollow"><h3> '.$dotitle.'</h3></a></td>';
}elseif($doid == 19){
	echo '<tr><td class="w"><a href="'.$dotitle.'.php" rel="nofollow"><h3> '.$dotitle.'</h3></a></td>';
}elseif($doid == 20){
	echo '<tr><td class="w"><a href="'.$dotitle.'.php" rel="nofollow"><h3> '.$dotitle.'</h3></a></td>';
}else{
	echo '<tr><td class="w"><a href="'.$dotitle.'.php" rel="nofollow"><h3> '.$dotitle.'</h3></a></td>';
}

}
echo '<tr><td class="1 w"> <center><a
href="'.$config->url.'downloading">(1)</a> '.$pagination2->display2() . '</center></table>';
}
mysql_free_result($doquery);
require('incfiles/end.php');
?>
