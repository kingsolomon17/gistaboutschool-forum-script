<?php
require('incfiles/init.php');
$userid = $_GET["username"];

$num=mysql_num_rows(mysql_query("SELECT * FROM boards WHERE name='$userid'"));

if($num > 0)
{
require('topics.php');
die();
}
?> 