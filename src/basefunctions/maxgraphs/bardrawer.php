<?php
// get details
	$val			= $_GET["y"];
	$w				= $_GET["x"];
	$colour		= $_GET["colour"];
// set dimensions
	// Width
	//$w = 50;
	// Height
	$h = $val;
// create image
     $im = imagecreate($w, $h);
// set colours to be used
		$bg = imagecolorallocate($im, hexdec('0x' . $colour{0} . $colour{1}), hexdec('0x' . $colour{2} . $colour{3}), hexdec('0x' . $colour{4} . $colour{5}));
		
		//$bg = imagecolorallocate($im, 255, 0, 0); // Back colour
		$black = imagecolorallocate($im, 0x00, 0x00, 0x00);
		$barcolor  = imagecolorallocate($im, 0xFF, 0x00, 0x00); // Fore colour
// draw border
     imagerectangle($im, 0,0,$w-1,$h-1,$black);

// calculate dimensions of inner bar
     //$barh = $max ? floor(($h-2) * $val / $max) : 0;
     //$barw = $w - 2;
// draw inner bar
	 if ($barw)
     //imagefilledrectangle($im, 1, 106, $barw, 106-$barh, $barcolor);
// send image header
     header("content-type: image/png");
// send png image
     imagepng($im);
     imagedestroy($im);
?>
