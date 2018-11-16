<?php

$config = new core() ;

$dbname = "kingsolomon"; // Your Sql Database Name

$dbuser = "king"; // Your sql database username

$dbhost = "localhost"; // its always localhost, except if your host states otherwise

$dbpass = "king"; // Your sql database password

////SITE SETTING////

$config->url = 'http://'.$_SERVER["HTTP_HOST"].'/';
// Your website address pls dont edit here.

$config->title = "Gistaboutschool"; // Your site title. Preferably your domain without .com/.net/.org/ etc

$config->css = "black"; // css styles options.. black, green, blue, or red..

$config->desc = "Gistaboutschool"; // Your site description,


////OTHER SETTINGS///

$config->tw = "Gistaboutschool"; // Your Twitter Username (Not URL)

$config->twi = "Gistaboutschool"; // Your Twitter Username (Not URL)

$config->fb = "chinemeremsalozie"; // Owner Facebook Page name (Not profile name or URL)

$config->fb1 = "Gistaboutschool"; // Site Facebook Page name (Not profile name or URL)

$config->go = "Gistaboutschool"; // Site Google+ Page name (Not profile name or URL)

$config->owner = "Kingsolomon Alozie"; // Name Of Owner

$config->owner1 = "Gistaboutschool"; // Name Of Owner

$config->year = '<script language="javascript" type="text/javascript" src="/content/js/year.js"></script>'; // Footer Year

$config->email = '<a href="mailto:aloziesolomon17@gmail.com">aloziesolomon17@gmail.com</a>'; // Email Of Owner
$config->mobile1 = '<a href="https://api.whatsapp.com/send?phone=2349025925116">+2349025925116</a>'; // Mobile No. Of admin

$config->postsperpage = 15;

$config->topicsperpage = 15;

$config->validExtension = array("jad","jar","zip","sis","sisx","pdf","prov","nth","thm","rar","png","jpg","gif","jpeg");

$config->imgExtension = array("png","jpg","jpeg","gif");

$config->attachmentFolder = ''.$_SERVER['DOCUMENT_ROOT'].'/attachment/';

$config->sitePassword = '1234';

 ?>
