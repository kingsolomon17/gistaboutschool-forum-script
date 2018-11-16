<?php
$adsquery = mysql_query("SELECT `id`,`img` FROM `ads` WHERE `boardid`='0' ORDER BY RAND() LIMIT 1");
if(mysql_num_rows($adsquery) > 0)
{
$adsinfo = mysql_fetch_array($adsquery);
$adsimg = $adsinfo["img"];
$adsid = $adsinfo["id"];
echo '<p><div class="ratatamp"><a href="/click?id='.$adsid.'" rel="nofollow"><img src="'.$adsimg.'"></a></div><p>';
} else {
echo '<p>';
}
?>