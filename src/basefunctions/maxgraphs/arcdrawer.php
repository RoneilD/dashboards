<?php
// create image
$image = imagecreate(130, 130);

// allocate some colors
$white    = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
$background = imageColorAllocate($image, 20, 205, 205);
$gray     = imagecolorallocate($image, 0xC0, 0xC0, 0xC0);
$darkgray = imagecolorallocate($image, 0x90, 0x90, 0x90);
$navy     = imagecolorallocate($image, 0x00, 0x00, 0x80);
$darknavy = imagecolorallocate($image, 0x00, 0x00, 0x50);
$blue			= imagecolorallocate($image, 0x00, 0x00, 0xFF);
$darkblue	= imagecolorallocate($image, 0x00, 0x00, 0x90);
$green		= imagecolorallocate($image, 0x00, 0xEE, 0x00);
$darkgreen	= imagecolorallocate($image, 0x00, 0x88, 0x00);
$red      = imagecolorallocate($image, 0xFF, 0x00, 0x00);
$darkred  = imagecolorallocate($image, 0x90, 0x00, 0x00);

// make the 3D effect
for ($i = 60; $i > 50; $i--) {
   imagefilledarc($image, 60, $i, 100, 55, 25, 75 , $darkgreen, IMG_ARC_PIE);
   imagefilledarc($image, 60, $i, 100, 55, 75, 360 , $darkred, IMG_ARC_PIE);
}

imagefilledarc($image, 60, 50, 100, 55, 25, 75 , $green, IMG_ARC_PIE);
imagefilledarc($image, 60, 50, 100, 55, 75, 360 , $red, IMG_ARC_PIE);

for ($i = 60; $i > 50; $i--) {
	 imagefilledarc($image, 80, ($i-10), 100, 55, 0, 25, $darkblue, IMG_ARC_PIE);
}
imagefilledarc($image, 80, 40, 100, 55, 0, 25, $blue, IMG_ARC_PIE);


// flush image
header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
?>
