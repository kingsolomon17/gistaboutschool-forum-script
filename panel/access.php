<?php
if(!functions::isloggedin())
{
$pagetitle = "You Must Be LoggedIn To Proceed";
require($_SERVER['DOCUMENT_ROOT'].'/incfiles/head.php');
functions::display_error('You Must Be LoggedIn To Proceed');
require($_SERVER['DOCUMENT_ROOT'].'/incfiles/end.php');
die();
}


$modcheck = mysql_num_rows(mysql_query("SELECT * FROM moderators WHERE username = '$user'"));

$admincheck = mysql_num_rows(mysql_query("SELECT * FROM admins WHERE username = '$user'"));

if($admincheck < 1 && $modcheck < 1)
{
$pagetitle = "Insufficient Permission";
require($_SERVER['DOCUMENT_ROOT'].'/incfiles/head.php');
functions::display_error('Insufficient Permission');
require($_SERVER['DOCUMENT_ROOT'].'/incfiles/end.php');
die();
}

if( ! $_SESSION["access"])
{
$pagetitle = "Please Insert Your Panel Access Passkeys";
require($_SERVER['DOCUMENT_ROOT'].'/incfiles/head.php');
echo '<p><h2>Please Insert Your Panel Access Passkeys</h2><p><div class="display"><form method="POST" action="/panel/do_access">
<input type="text" name="passkeys"><br><br>
<input type="submit" name="submit" value="Login"><input name="redirect" type="hidden" value="'.$self.'">
</form><br><a href="/panel/resetcode">Forgot passkeys?</a><p></div>';
require($_SERVER['DOCUMENT_ROOT'].'/incfiles/end.php');
die();
} else {
$access = $_SESSION["access"];
$modboard = $_SESSION["board"];
}

?>