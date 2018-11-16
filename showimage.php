<?php
include 'incfiles/core.php';
include 'incfiles/settings.php';
include 'incfiles/connect.php';

$imgid = (int)$_GET["id"];

$attachquery = mysql_query("SELECT * FROM attachment WHERE id=$imgid");

if(mysql_num_rows($attachquery) > 0)
{
$attachinfo = mysql_fetch_array($attachquery);
$url = $attachinfo["url"];
$ext = strtolower($attachinfo["extension"]);

}

switch($ext)
{
case '.jpg':
case '.jpeg':
$type = 'image/jpeg';
break;
case '.gif':
$type = 'image/gif';
break;
case '.png':
$type = 'image/png';
break;
default:
$type = 'image/png';
break;
}

header('Content-Type: '.$type.'');
echo file_get_contents($config->attachmentFolder.$url);
?>