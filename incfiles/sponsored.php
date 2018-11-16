<?php
$adsquery = mysql_query("SELECT `id`,`img`,`title` FROM sponsored WHERE `boardid`='0' ORDER BY RAND() LIMIT 10");

if(mysql_num_rows($adsquery) > 0)
{
$adsinfo = mysql_fetch_array($adsquery);
$adstitle = $adsinfo["title"];
$adsimg = $adsinfo["img"];
$adsid = $adsinfo["id"];

echo '<p><table summary="sp"><tr><td class=""><h3>Sponsored Posts</h3></td><tr><td class="w"><a href="/clicksponsored?id='.$adsid.'" rel="follow"><img src="'.$adsimg.'"width="100px" height="66px"><h3> '.$adstitle.'</h3></a></td>
</table></p>';

} else {
echo '<p>';
}

?>