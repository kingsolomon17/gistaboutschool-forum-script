<?php

$limit = 65;
$limit2 = $lmit * 8;
$urows = mysql_num_rows( mysql_query("SELECT * FROM updates LIMIT $limit2"));
$pagination2 = new pagination($limit, 1, ''.$config->url.'links/(page)', $urows);

echo '<p><table><tr><td><b><a href="/download.php">Download apps, musics , videos , Pass questions , eboks ,and more</a></td></tr></table>';
echo '</table><p class="small">(<a href="#up"><b>Go
Up</b></a>)<p><table id="down"><tr><td
class="small w grad"><form action="'.$config->url.'search"><input type="text" name="q" size="32" placeholder="Search Here!"><input type="submit" name="sa"
value="Google"><input type="submit"
name="localsearch" value="Forum Search"></
form><br>Links: <a
href="/links"> (0) </a> '.$pagination2->display2().'<br><b> <a href="http://facebook.com/'.$config->fb1.'" title="'.$config->owner1.'">'.$config->owner1.'</a> </b> - Copyright &copy; 2017 - '.$config->year.' <a href="http://facebook.com/'.$config->fb.'" title="'.$config->owner.'">'.$config->owner.'</a>. All rights reserved.&reg; See <a href="/privacy-policy">Privacy Policy</a> , <a href="/terms-conditions">Terms & Conditions</a> 
<br /> Mail: '.$config->email.' <br /> <b>Disclaimer:</b> Every GISTABOUTSCHOOL member is <b>solely responsible</b> for <b>anything</b> that he/she <b>posts</b> or <b>uploads</b> on GISTABOUTSCHOOL.
</table>';

echo '</div><script type="text/javascript">
var infolinks_pid = 3075138;
var infolinks_wsid = 0;
</script>
<script type="text/javascript" src="http://resources.infolinks.com/js/infolinks_main.js"></script></body></html>';
die();
?>