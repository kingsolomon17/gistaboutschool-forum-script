<?php
require('incfiles/init.php');
$pagetitle = "login";
require('incfiles/head.php');
if(functions::isloggedin())
{
if(empty($redirect))
{
functions::go($config->url);
} else {
functions::go('http://'.$redirect);
}
die();
}

$redirect=$_GET["redirect"];
echo '<a name="top"></a><p><h2>Login To '.$config->title.'</h2><p><div class="display"><form method="POST" action="/do_login">
    Username:<br><input type="text" name="username"><br><br>
    Password:<br><input type="password" name="password"><br><br>
    
    <input type="submit" name="submit" value="Login"><input name="redirect" type="hidden" value="'.$redirect.'">
    </form><br>
    <a href="/resetcode">Forgot password?</a><p>
    <a href="/confirm">Not registered click here?</a><p></div>';

require('incfiles/end.php');
?>