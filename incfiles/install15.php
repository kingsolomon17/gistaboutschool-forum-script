<?php

require('incfiles/init.php');
require('incfiles/head.php');



$array = array("birthday","birthmonth","birthyear");

$array2 = array("personaltext","signature","websitetitle","websiteurl","location","yim","twitter");
$at = count($array);

for ($i = 0; $i <= $at; $i++)
{
$add = $array[$i];
$ut = mysql_query("ALTER TABLE attachment ADD $add bigint(100) NOT NULL") or die(mysql_error());

}
$at = count($array2);
for ($i = 0; $i <= $at; $i++)
{
$add = $array2[$i];
$ut = mysql_query("ALTER TABLE attachment ADD $add text COLLATE latin1_general_ci NOT NULL") or die(mysql_error());
}


require('incfiles/end.php');
?>