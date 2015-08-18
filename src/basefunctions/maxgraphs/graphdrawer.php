<?php
// get details and set dimensions {
	// Set 1 {
		$values[1][1]	= 100;
		$values[1][2]	= 110;
		$values[1][3]	= 118;
		$values[1][4]	= 130;
		$values[2][1]	= 120;
		$values[2][2]	= 130;
		$values[2][3]	= 140;
		$values[2][4]	= 130;
		$values[3][1]	= 40;
		$values[3][2]	= 120;
		$values[3][3]	= 115;
		$values[3][4]	= 130;
		$values[4][1]	= 140;
		$values[4][2]	= 60;
		$values[4][3]	= 25;
		$values[4][4]	= 145;
		
		$height			= 400;
		$width			= 650;
		
		$rows				= 5;
		
		$barwidth		= 15;
		$bargap			= 12;
	// }
	
	// Set 2 {
		/*
		$values[1][1]	= 10;
		$values[1][2]	= 10;
		$values[1][3]	= 8;
		$values[1][4]	= 10;
		$values[2][1]	= 10;
		$values[2][2]	= 3;
		$values[2][3]	= 7.5;
		$values[2][4]	= 10;
		$values[3][1]	= 4;
		$values[3][2]	= 10;
		$values[3][3]	= 12;
		$values[3][4]	= 10;
		
		$height			= 120;
		$width			= 150;
		
		$rows				= 5;
		
		$barwidth		= 4;
		$bargap			= 4;
		*/
	// }
	
	// Set 3 {
		/*
		$values[1][1]	= 30;
		$values[1][2]	= 55;
		$values[1][3]	= 27;
		$values[1][4]	= 30;
		$values[2][1]	= 20;
		$values[2][2]	= 30;
		$values[2][3]	= 40;
		$values[2][4]	= 30;
		$values[3][1]	= 40;
		$values[3][2]	= 20;
		$values[3][3]	= 33;
		$values[3][4]	= 30;
		$values[4][1]	= 40;
		$values[4][2]	= 52;
		$values[4][3]	= 25;
		$values[4][4]	= 45;
		
		$height			= 300;
		$width			= 440;
		
		$rows				= 5;
		
		$barwidth		= 15;
		$bargap			= 10;
		*/
	// }
	
	if($width < 200) {
		$linefont		= 6;
		$headerfont	= 10;
	} else if($width < 450) {
		$linefont		= 8;
		$headerfont	= 14;
	} else {
		$linefont		= 10;
		$headerfont	= 18;
	}
	
	$colour			= "CCCCCC";
	$maxheight	= 0;
	foreach ($values as $divkey=>$divval) {
		foreach ($divval as $barkey=>$barval) {
			if($barval > $maxheight) {
				$maxheight = $barval;
			}
		}
	}
	$ceiling = $height / 20;
	$heightdif = round(($height - $ceiling) / $maxheight, 2);
// }

// create image {
	$im = imagecreate($width+1, $height+1);
// }
// set colours to be used {
	$white		= imagecolorallocate($im, 255, 255, 255);
	//$bg = imagecolorallocate($im, hexdec('0x' . $colour{0} . $colour{1}), hexdec('0x' . $colour{2} . $colour{3}), hexdec('0x' . $colour{4} . $colour{5}));
	$bg					= imagecolorallocate($im, 200, 200, 200);
	$black			= imagecolorallocate($im, 0x00, 0x00, 0x00);
	$red				= imagecolorallocate($im, 255, 0, 0);
	$yellow			= imagecolorallocate($im, 255, 255, 0);
	$green			= imagecolorallocate($im, 40, 150, 40);
	$blue				= imagecolorallocate($im, 90, 90, 255);
	$colourlist	= array("1"=>$green, "2"=>$yellow, "3"=>$red, "4"=>$blue);
	$barcolor		= imagecolorallocate($im, 0xFF, 0x00, 0x00); // Fore colour
// }

// draw backdrop {
	//imagerectangle($im, 0,0,$width+1,$height+1,$black);
	imageline($im, 5, 0, $width-5, 0, $black); // Top
	imageline($im, 0, 5, 0, $height-5, $black); // Right
	imageline($im, $width, 5, $width, $height-5, $black); // Left
	imageline($im, 5, $height, $width-5, $height, $black); // Bottom
	
	imagearc($im, 4, 4, 10, 10, 181, 269, $black); // Top left
	imagearc($im, $width-4, 4, 10, 10, 271, 359, $black); // Top right
	imagearc($im, 4, $height-4, 10, 10, 91, 179, $black); // Bottom left
	imagearc($im, $width-4, $height-4, 10, 10, 1, 89, $black); // Top left
	// imagearc(resource, x, y, height, width, degree start, degree end, colour)
	
	imagefill($im, 20, 20, $bg);
// }

// Draw lines {
	$linediv = $height / $rows;
	for ($linecount=$linediv; $linecount<$height; $linecount+=$linediv) {
		imageline($im, 0, $linecount, $width, $linecount, $black);
		$pixelheight = $height - $linecount;
		$linetext = round($pixelheight/$heightdif, 2);
		//imagettftext($im, $linefont, 0, 5, $linecount+10, $black, "verdana.ttf", $linetext);
	}
// }

// calculate dimensions of inner bar {
     //$barh = $max ? floor(($h-2) * $val / $max) : 0;
     //$barw = $w - 2;
// }

// draw the bars {
	$divpos = $width/10;
	$divcount = count($values);
	$divwidth = round($width / $divcount, 2);
	foreach ($values as $divkey=>$divval) {
		$barpos = $divpos;
		foreach ($divval as $barkey=>$barval) {
			$barheight = $height - ($barval * $heightdif);
			imagefilledrectangle($im, $barpos, $barheight, ($barpos+$barwidth), ($height-1), $colourlist[$barkey]);
			$barpos += $bargap; 
		}
		$divpos += $divwidth;
	}
// }

// Title {
	// The text to draw
	$text = "Graph Name";
	// Replace path by your own font path
	$font = "verdana.ttf";
	
	// Add show beneath text
	//imagettftext($im, $headerfont, 0, 11, 21, $black, $font, $text);
	
	// Add text
	//imagettftext($im, $headerfont, 0, 10, 20, $blue, $font, $text);
// }

// send image header and png image {
	header("content-type: image/png");
	imagepng($im);
	imagedestroy($im);
// }
?>
