<?php
include "header.php";
?>
<td align=center>
<br>
	<table width=80% height=300 align=center bgcolor=white class=maintext>
	<tr><td style="color: green; font-size: 13px;">
 	<?php
		$file = "README.txt";
		$open = fopen($file, "r");
		$size = filesize($file);
		$count = nl2br(fread($open, $size));
		
		echo($count);
	?>
	</td></tr></table>
</td>
<?php
include "footer.php";
?>
