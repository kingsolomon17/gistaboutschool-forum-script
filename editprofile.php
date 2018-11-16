<?php
require('incfiles/init.php');
$pagetitle = 'Edit My Profile';
require('incfiles/head.php');

if(!functions::isloggedin())
{
functions::go($config->url);
die();
}


$gender = functions::cleanoutput(functions::user_info($user, gender));
$birthday = functions::cleanoutput(functions::user_info($user, birthday));
$birthmonth = functions::cleanoutput(functions::user_info($user, birthmonth));
$birthyear = functions::cleanoutput(functions::user_info($user, birthyear));
$personaltext = functions::cleanoutput(functions::user_info($user, personaltext));
$signature = functions::cleanoutput(functions::user_info($user, signature));
$websitetitle = functions::cleanoutput(functions::user_info($user, websitetitle));
$websiteurl = functions::cleanoutput(functions::user_info($user, websiteurl));
$location = functions::cleanoutput(functions::user_info($user, location));
$yim = functions::cleanoutput(functions::user_info($user, yim));
$twitter = functions::cleanoutput(functions::user_info($user, twitter));



echo '<h2>Edit My Profile</h2><p> <a href="/">'.$config->title.' Forum</a> / <a href="/'.$user.'">My profile</a> / Edit My Profile<p>
<form method="POST" action="/do_editprofile" name="editform" enctype="multipart/form-data">
<table summary="profile editing form">
<tbody><tr><td><b>Email</b>: '.functions::user_info($user, email).' &nbsp;&nbsp;<a href="/changeemail">Change Email</a>
<tr><td class="w"><b>Birthday</b>:
<input type="text" size="2" name="birthday" value="'.$birthday.'">
<input type="text" size="2" name="birthmonth" value="'.$birthmonth.'">
<input type="text" size="4" name="birthyear" value="'.$birthyear.'">
<tr><td class=""><b>Gender</b>:
<select name="gender" size="1"><option value="-" selected=""></option><option value="male">Male</option><option value="female">Female</option></select>
<tr><td valign="top" class="w"><b>Personal text</b>:<br> <textarea name="personaltext" rows="5" cols="32">'.$personaltext.'</textarea>
<tr><td valign="top"><b>Signature</b>: <br><textarea name="signature" rows="5" cols="32">'.$signature.'</textarea>
<tr><td class="w"><b>Picture</b>:<input type="file" name="avatar"><p><input type="checkbox" name="removeavatar" value="yes"> Remove this image<tr><td><b>Website title</b>: <input type="text" name="websitetitle" value="'.$websitetitle.'">
<tr><td class="w"><b>Website URL</b>: <input type="text" name="websiteurl" value="'.$websiteurl.'">
<tr><td><b>Location</b>: <input type="text" name="location" value="'.$location.'">
<tr><td class="w"><b>YIM</b>: <input type="text" name="yim" value="'.$yim.'">
<tr><td><b>Twitter</b>: <input type="text" name="twitter" value="'.$twitter.'">
<tr><td class="w"><a href="/send_confirmation_email_for_account_deactivation?session='.$sessionkey.'">Deactivate Account</a>
</tbody></table>
<p><input type="submit" name="submit" value="Update Profile"></form>';


require('incfiles/end.php');
?>