<?php
header("Content-type: image/png");
// Create the image
$height=20;

$im = imagecreatetruecolor(300, $height);
//Temp BGCOLOR (center of c-finder)
$bg1 = 255;
$bg2 = 0;
$bg3 = 0;

$c1 = imagecolorallocate($im, $bg1, $bg2, $bg3); //Background
imagefilledrectangle($im, 0, 2, 100, $height-2, $c1);
imagepng($im);
imagedestroy($im);
?>
