<?php
$fp = fopen("counter.txt", "r");
	if(!$fp){
echo " Could not open the file" ;
}else {
$count = fread ($fp,1024) ; 
fclose ($fp); 
$count = $count +1; 
echo " <strong>Total Pageviews: " . number_format($count)  . " </strong> " ;
$fp = fopen("counter.txt", "w" ) ; 
fwrite($fp,$count) ; 
fclose ($fp) ;
}
?>