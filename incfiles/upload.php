<?php

class upload extends core{

private $source;

function __construct($source){

$this->source = $source;
}

public function proceedimgupload($width, $height, $destination) {

$source = $this->source;

list($width, $height) = getimagesize($source);
if ($width > 150 || $height > 150) {
$ratioh = $max_height / $height;
$ratiow = $max_width / $width;
$ratio = min($ratioh, $ratiow);
// New dimensions
$newwidth = intval($ratio * $width);
$newheight = intval($ratio * $height);
    
$newImage = imagecreatetruecolor($newwidth, $newheight);
    
$exts = array("gif", "jpg", "jpeg", "png");
$pathInfo = pathinfo($source);
$ext = trim(strtolower($pathInfo["extension"]));
$sourceImage = null;
// Generate source image depending on file type
switch ($ext) {
case "jpg":
case "jpeg":
$sourceImage = imagecreatefromjpeg($source);
break;
case "gif":
$sourceImage = imagecreatefromgif($source);
break;
case "png":
$sourceImage = imagecreatefrompng($source);
break;
}

imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    
// Output file depending on type
switch ($ext) {
case "jpg":
case "jpeg":
imagejpeg($newImage, $destination);
break;
case "gif":
imagegif($newImage, $destination);
break;
case "png":
imagepng($newImage, $destination);
break;
}
}
}

}

?>