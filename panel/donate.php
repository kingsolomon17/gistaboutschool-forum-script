<?php
require('../incfiles/init.php');
 
require('../incfiles/head.php');

echo "<p><h2>Select Amount of Credit To Transfer</h2><br />";
echo '<div class="display"><form action="/panel/do_donate" method="post">
<label><b>Insert Amount: </b><input name="sub" type="text" id="sub" placeholder="Insert Amount" /></label></p><br />
<label><b>Type Username: </b><input name="subvalue" type="text" id="subvalue" placeholder="Type Username" /></label></p>';

echo '<input type="submit" name="submit" class="button" value="Transfer Credit"></form></div>';

require('../incfiles/end.php');
?>