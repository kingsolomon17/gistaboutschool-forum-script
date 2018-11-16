<!-- This utility is provided by HIOX INDIA   -->
<!-- This is a copyright product of HIOXINDIA -->
<!--     Visit us at hioxindia.com            -->
<!--     Scripts from hscripts.com            -->


<?php 
$file1 = "$hm/colors.txt";
$lines1 = file($file1);
//$count = count($lines1)

foreach ($lines1 as $line_num1 => $line1)
{
	$spos = strpos($line1,'ackground-color="');
	
	if($spos != null && $spos > 0)
	{
	$spos2 = strpos($line1,'"',($spos+18));
	//echo(".....".$spos."------".$spos2);

	$str = substr($line1,18,($spos2-($spos+17)));
	$bgcol = $str;
	//echo($bgcol);
	}
	
	$spos1 = strpos($line1,"ont-color=");
	if($spos1 != null && $spos1 > 0)
	{
	$spos2 = strpos($line1,'"',($spos1+12));
	//echo(".....".$spos."------".$spos2);

	$str = substr($line1,12,($spos2-($spos1+11)));
	$fontcol = $str;
	$linkcol = $fontcol;	
	//echo("||".$fontcol);
	}

	$spos1 = strpos($line1,"ink-color=");
	if($spos1 != null && $spos1 > 0)
	{
	$str = substr($line1,12,(strlen($line1)-15));
	$linkcol = $str;
	echo("||".$linkcol);
	}


	$spos = strpos($line1,"crollamount=");
	if($spos != null && $spos > 0)
	{
	$spos2 = strpos($line1,'"',($spos+14));
	//echo(".....".$spos."------".$spos2);

	$str = substr($line1,14,($spos2-($spos+13)));
	$scamount = $str;
	//echo("||".$scamount);
	}

	$spos = strpos($line1,"crolldelay=");
	if($spos != null && $spos > 0)
	{
	$spos2 = strpos($line1,'"',($spos+13));
	//echo(".....".$spos."------".$spos2);

	$str = substr($line1,13,($spos2 - ($spos+12)));
	$scdelay = $str;
	//echo("||".$scdelay);
	}

	$spos = strpos($line1,"idth=");
	if($spos != null && $spos > 0)
	{
	$spos2 = strpos($line1,'"',($spos+7));
	//echo(".....".$spos."------".$spos2);

	$str = substr($line1,7,($spos2-($spos+6)));
	$width = $str;
	//echo("||".$width);
	}

	$spos = strpos($line1,"eight=");
	if($spos != null && $spos > 0)
	{
	$spos2 = strpos($line1,'"',($spos+8));
	//echo(".....".$spos."------".$spos2);

	$str = substr($line1,8,($spos2-($spos+7)));
	$height = $str;
	//echo("||".$height);
	}
	
	$spos = strpos($line1,"ont-size=");
	if($spos != null && $spos > 0)
	{
	$spos2 = strpos($line1,'"',($spos+11));
	//echo(".....".$spos."------".$spos2);

	$str = substr($line1,11,($spos2-($spos+10)));
	$fsize = $str;
	//echo("||".$fsize);
	}

}

?>

<script language=javascript>
var sd = 1;
function movefast()
{
sd = sd+1;
marquee.scrollAmount = sd;
}

function moveslow()
{
sd = sd-1;
marquee.scrollAmount = sd;
}
</script>

<table width=<?php echo($width);?> bgcolor=<?php echo($bgcol);?> border=1 cellpadding=0 cellspacing=0
	style="font-family: serif, san-serif, verdana;">
<tr><td>
<marquee id=marquee bgcolor=<?php echo($bgcol); ?> 
scrollamount=<?php echo($scamount); ?> scrolldelay=<?php echo($scdelay); ?> 
onmouseover="this.stop()" onmouseout="this.start()">
<?php 

$file1 = "$hm/news.txt";

$lines = file($file1);
$count = count($lines);

$newsf = false;
$linkf = true;

foreach ($lines as $line_num => $line)
{
	if($newsf == false && $linkf == true)
	{
		$spos = strpos($line,"NEWS=");
		if($spos != null && $spos > 0)
		{
             $spos2 = strpos($line,'"',($spos+7));

             $newsstr = substr($line,7,($spos2-($spos+6)));
		 //echo("<div align=left style=\"color: ".$fontcol.";\" >".$newsstr."</div>");	
 		 $newsf = true;
		}
	}
	else if( $linkf == true &&  $newsf == true)
	{
		$spos = strpos($line,"LINK=");
		if($spos != null && $spos > 0)
		{
		 $spos2 = strpos($line,'"',($spos+7));
	
             $linkstr = substr($line,7,($spos2-($spos+6)));

		echo("<a href=".$linkstr." style=\"color: ".$linkcol."; font-size: ".$fsize."; text-decoration: none; 
font-weight: bold;\">".$newsstr."</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ");	
	       $newsf = false;
             $linkf = true;
		}
		else
		{
		  	$linkf = false;
			echo("<div style=\"color: red; font-weight: bold;\"> Improper Usage of HIOX NEWS TICKER </div>");
		}
	}
}
?>
</marquee>
</td>
<td width=100 height=<?php echo($height);?> bgcolor=<?php echo($bgcol);?> >
<div align=right height=<?php echo($height);?> style="background-color: <?php echo($bgcol); ?>; color: white; 
		font-size: <?php echo($fsize); ?>;"> 
  

</div>
</td></tr>
</table>
