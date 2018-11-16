<?php

include ($_SERVER['DOCUMENT_ROOT'].'/incfiles/core.php');
include ($_SERVER['DOCUMENT_ROOT'].'/incfiles/settings.php');
include ($_SERVER['DOCUMENT_ROOT'].'/incfiles/connect.php');
include ($_SERVER['DOCUMENT_ROOT'].'/incfiles/function.php');
include ($_SERVER['DOCUMENT_ROOT'].'/incfiles/bbcode.php');

header("Content-Type: application/xml");

echo '<?xml version="1.0" encoding="utf-8" standalone="yes" ?><rss version="2.0"><channel>
<title>'.$config->title.'</title>
<link>'.$config->url.'</link>
<description>'.$config->title.' Forum</description><language>en-En</language><webMaster> '.$config->email.'</webMaster>';

$query = mysql_query("SELECT * FROM `updates` WHERE `topicid`>0 ORDER BY `id` DESC");

if(mysql_num_rows($query) > 0)
{
while($info = mysql_fetch_array($query))
{
$tid = $info["topicid"];

$tinfo = mysql_fetch_array(mysql_query("SELECT * FROM `topics` WHERE `id`='$tid'")); 
$date = date("r", $tinfo["date"]);
$mmessage = $tinfo["message"];
$bbcodes = new bbcode($mmessage);
$message = $bbcodes->display();
$link = $config->url . $tinfo["id"] . '/' . $tinfo["subject"];

echo '<item>
<title>'.$tinfo["subject"].'</title>
<link>'.$link.'</link>
<description><![CDATA['.$message.']]></description>
<pubDate>'.$date.'</pubDate>
</item>';
}
}
echo '</channel></rss>';

?>