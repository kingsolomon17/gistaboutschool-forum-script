<?php
require('incfiles/init.php');
$pagetitle = 'Edit My Profile';
require('incfiles/head.php');

if(!functions::isloggedin())
{
functions::go($config->url);
die();
}




if(isset($_POST["submit"]))
{
$gender = functions::cleaninput($_POST["gender"]);
$birthday = functions::cleaninput((int)$_POST["birthday"]);
$birthmonth = functions::cleaninput((int)$_POST["birthmonth"]);
$birthyear = functions::cleaninput((int)$_POST["birthyear"]);
$personaltext = functions::cleaninput($_POST["personaltext"]);
$signature = functions::cleaninput($_POST["signature"]);
$websitetitle = functions::cleaninput($_POST["websitetitle"]);
$websiteurl = functions::cleaninput($_POST["websiteurl"]);
$location = functions::cleaninput($_POST["location"]);
$yim = functions::cleaninput($_POST["yim"]);
$twitter = functions::cleaninput($_POST["twitter"]);
$removeavatar = $_POST["removeavatar"];


if(isset($websitetitle) && isset($websiteurl))
{
mysql_query("UPDATE users SET websitetitle='$websitetitle', websiteurl='$websiteurl' WHERE username='$user'");
}


if(isset($removeavatar) && $removeavatar == yes)
{
$uavatar = functions::user_info($user, avatar);
if(unlink($uavatar))
{
mysql_query("UPDATE users SET avatar='' WHERE username='$user'");
}
}

if(isset($birthday) && isset($birthmonth) && isset($birthyear) && $birthday > 0 && $birthmonth > 0 && $birthyear > 0 && $birthday < 32 && $birthmonth < 13 && $birthyear < date('Y')-5)
{
mysql_query("UPDATE users SET birthday='$birthday', birthmonth='$birthmonth', birthyear='$birthyear' WHERE username='$user'");
}


if(isset($personaltext) && $personaltext < 200)
{
mysql_query("UPDATE users SET personaltext='$personaltext' WHERE username='$user'");
}


if(isset($signature) && $signature < 100)
{
mysql_query("UPDATE users SET signature='$signature' WHERE username='$user'");
}


if(isset($location) && $location < 100)
{
mysql_query("UPDATE users SET location='$location' WHERE username='$user'");
}


if(isset($yim) && $yim < 100)
{
mysql_query("UPDATE users SET yim='$yim' WHERE username='$user'");
}

if(isset($twitter) && $twitter < 100)
{
mysql_query("UPDATE users SET twitter='$twitter' WHERE username='$user'");
}

if(isset($gender) && strlen($gender) > 0)
{
mysql_query("UPDATE users SET sex='$gender' WHERE username='$user'");
}

$erros=array();
if(isset($_FILES["avatar"]))
{
$ffilename = $_FILES['avatar']['name'];
if($ffilename)
{
$valid_exts = array("png","jpg","jpeg","gif");
$ext = end(explode(".",strtolower($ffilename)));
$size = $_FILES["avatar"]["size"];

$rand = rand(time());

$path = "usersavatas/".$rand.$_FILES['avatar']['name'];
$source = $_FILES['avatar']['tmp_name'];
if($size<10)
{
$errors[]="File must be larger than 10Bytes!";
}

if($size>10000000)
{
$errors[]="size too Large!";
}

if(!in_array($ext,$valid_exts))
{
$errors[] ="invalid file extension!";
}

if(count($errors) > 0)
{
$string = "";
foreach($errors as $error)
{
$string .= "$error<br/>";
}
functions::display_error($string);
require('incfiles/end.php');
die();
}

if(copy($source,
$path))
{
mysql_query("UPDATE users SET avatar='$path' WHERE username='$user'");
}
}
}

header("location: ".urls::user($user)."");
exit();
} else {

header("location: /editprofile");
exit();

} 


?>