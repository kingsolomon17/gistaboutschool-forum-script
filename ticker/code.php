<?php

include "header.php";
?>
<td align=center>
Copy the below code in to the pages where you want HHNT (HIOX Horizontal News Ticker)<br><br>
	<table width=400 height=250 align=center class=maintext>
        <tr>
        <td>
	<form name="select_all">
	<textarea style="color: green; font-size: 13px;" name="text_area" rows="6" cols="80" border=0px>
        <?php
        	$url = $_SERVER['SCRIPT_FILENAME'];
                $pp = strrpos($url,"/");
                $url = substr($url,0,$pp);
                $ura = $_SERVER['SCRIPT_NAME'];
                $host = $_SERVER['SERVER_NAME'];
                $ser = "http://$host";
                $ura= $ser.$ura; 
                $pp1 = strrpos($ura,"/");
                $ura = substr($ura,0,$pp1);
		$url1=explode('/', $url);
		//$url=array_pop($url1);
		$url1=implode('/', $url1);
		$ura1=explode('/', $ura);
		//$ura=array_pop($ura1);
		$ura1=implode('/', $ura1);
                echo "&lt?php 
	    $"."hm = \"$url1\"; 
	    $"."hm2 = \"$ura1\"; 
	    include \"$"."hm/hhnt.php\";
        ?&gt;";
        ?>
	</textarea><br>
	<input type="button" value="Select All" onClick="javascript:this.form.text_area.focus();this.form.text_area.select();">
	</form>
	</td>
        </tr>
        </table>
</td>
<?php
include "footer.php";

?>
