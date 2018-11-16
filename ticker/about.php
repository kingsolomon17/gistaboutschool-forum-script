<?php
include "header.php";
?>

<td>

	<table width=80% height=230 align=center bgcolor=white class=maintext>
	<tr><td style="color: green; font-size: 13px;">
	<ul type=circle><li>Hscripts.com is one of the most used free web resourse site online.</li>
	<li>This site started on 6.10.2004 and now has around 2,00,000 unique visitors every month.</li>
	<li>Free scripts will be available from the link: <a style="color: #0A3FFF; text-decoration: none;" href="http://hscripts.com/scripts/php">
http://hscripts.com/scripts/php</a></li>
	<li>Licensed scripts can be purchased from the link: <a style="color: #0A3FFF; text-decoration: none;" href="http://hscripts.com/scripts/php/licensed/index.php">
http://hscripts.com/scripts/php/licensed/index.php</a></li>
	<li>Hscripts.com is part of HIOX network of websites.</li>
	<li>HIOX network include sites as mentioned in the link: <a style="color: #0A3FFF; text-decoration: none;" href="http://hiox.com">
http://hiox.com</a></li>
	</ul>
	</td></tr></table>

<?php

$oout=$_GET['out'];
if($oout=="signout"){
	session_unset();
	session_destroy();
echo "<br><div align=center style='color:black;'>Click here to<a style='color:#aa4444; text-decoration:none;' href=code.php>&nbsp;RETURN&nbsp;</a>home page</div>";
}

echo "</td>";
include "footer.php";
?>