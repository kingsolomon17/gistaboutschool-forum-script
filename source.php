<?php
require('incfiles/init.php');
$pagetitle = "source code";
require('incfiles/head.php');


if($_POST["submit"])
{
$url=trim($_POST["url"]);
$f=@fopen($url, 'r');
if(!$f)
{
echo "<div class='msg'>File url is invalid! </div><br/>";
} else {


while($c=fread($f, 1024))
$filedata.=$c;


echo "<table id='down'><tr><td
class='small w grad'><textarea rows='40' cols='20'>$filedata</textarea></table>";
}
} else {
echo "<table id='down'><tr><td
class='small w grad'><form method='post' ><b>File URL:</b><br/><input type='text' name='url' value='http://' size='15'><input type='submit' name='submit' value='source'></form></table>";
}
require('incfiles/end.php');
?> 