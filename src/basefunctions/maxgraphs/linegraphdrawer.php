<?php
class drawXlinegraph {
	function drawXLineGraph($data) {
		// set data, style (fonts, etc) to be used (This must all be done before ANY GD functions) {
			// Data {
				$name				= $data["meta"]["name"];
				
				$height			= $data["meta"]["height"];
				$width			= $data["meta"]["width"];
				
				$topval			= $data["meta"]["topval"];
				$bottomval	= $data["meta"]["bottomval"];
				
				$rows				= $data["meta"]["rows"];
				
				$values			= $data["values"];
				$maxheight	= 0;
				foreach ($values as $divkey=>$divval) {
					foreach ($divval as $barkey=>$barval) {
						if($barval > $maxheight) {
							$maxheight = $barval;
						}
					}
				}
				
				$baselist		= $data["base"];
				$basecount	= count($baselist)+1; // The number of lines, + 1 so a row set is not presented at the final coordinate of x;
				
				$valrange	 	= $topval - $bottomval; // The range of values, as from least (bottom of graph) to most (top of graph)
				$ratio			= $height / $valrange;
				$difinc			= round(($width / $basecount), 2); // The pixel increment between each division of $baselinevalues;
			// }
			
			// Font size and header placement{
				$font = BASE."/basefunctions/fonts/verdana.ttf";
				if($width < 200) {
					$linefontsize		= 6;
					$headerfontsize	= 10;
					$headery				= 10;
				} else if($width < 450) {
					$linefontsize		= 8;
					$headerfontsize	= 12;
					$headery				= 14;
				} else {
					$linefontsize		= 10;
					$headerfontsize	= 18;
					$headery				= 20;
				}
			// }
		// }
		
		// create image {
			$im = imagecreate($width+1, $height+1);
		// }
		
		// Colours {
			$white		= imagecolorallocate($im, 255, 255, 255);
			//$bg = imagecolorallocate($im, hexdec('0x' . $colour{0} . $colour{1}), hexdec('0x' . $colour{2} . $colour{3}), hexdec('0x' . $colour{4} . $colour{5}));
			$bg					= imagecolorallocate($im, 200, 200, 200);
			$black			= imagecolorallocate($im, 0x00, 0x00, 0x00);
			$red				= imagecolorallocate($im, 255, 0, 0);
			$yellow			= imagecolorallocate($im, 255, 255, 0);
			$green			= imagecolorallocate($im, 40, 150, 40);
			$blue				= imagecolorallocate($im, 90, 90, 255);
			$colourlist	= array("1"=>$green, "2"=>$yellow, "3"=>$red, "4"=>$blue, "5"=>$black);
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
		
		// Behind-the-scenes data {
			
		// }
		
		// Draw dividing lines {
			$valinc = ($topval - $bottomval) / $rows; // The value increment between each line; 
			$pixinc = $height / $rows; // The pixel increment between each line;
			//imagettftext($im, $linefontsize, 0, 350, 80, $black, $font, "Rows : ".$rows.", Valinc : ".$valinc.", Pixinc : ".$pixinc);
			for ($linecount=0; $linecount < $rows; $linecount++) {
				$linepos	= $height - (($linecount * $pixinc));
				$linetext	= ($linecount * $valinc) + $bottomval;
				imagettftext($im, $linefontsize, 0, 5, ($linepos-1), $black, $font, $linetext);
				if($linecount != 0) {
					imageline($im, 0, $linepos, $width, $linepos, $black);
				}
			}
		// }
		
		// Draw the base values across the graph bottom {
			$basecount = 1;
			foreach ($baselist as $basekey=>$baseval) {
				imagettftext($im, $linefontsize, 0, $basecount*$difinc, ($height - 1), $black, $font, $baseval);
				$basecount++;
			}
		// }
		
		// draw the data lines {
			//imagettftext($im, $linefontsize, 0, 600, 20, $black, $font, $basecount." ".$difinc);
			$linecount	= 1;
			foreach ($values as $linekey=>$lineval) {
				$oldx				= "x";
				$oldy				= "x";
				$pointcount = 1;
				foreach ($baselist as $pointkey=>$pointval) {
					if($lineval[$pointval]) {
						$ypos = $height - (($lineval[$pointval] - $bottomval) * $ratio);
						$xpos = $pointcount*$difinc;
						imagefilledarc($im, $xpos, $ypos, 7, 7, 1, 360 , $colourlist[$linecount], IMG_ARC_PIE);
						if($oldx != "x") {
							imageline($im, $oldx, $oldy, $xpos, $ypos, $colourlist[$linecount]);
						}
						
						$oldx = $xpos;
						$oldy = $ypos;
					} else {
						$oldx = "x";
						$oldy = "x";
					}
					$pointcount++;
				}
				$linecount++;
			}
		// }
		
		// Title {
			$title = $data["meta"]["title"];
			// Add shadow beneath text
			imagettftext($im, $headerfontsize, 0, 11, ($headery+1), $black, $font, $title);
			
			// Add text
			imagettftext($im, $headerfontsize, 0, 10, $headery, $blue, $font, $title);
		// }
		
		// Save image to directory for program to access {
			/*
			header("content-type: image/png");
			imagepng($im);
			imagedestroy($im);
			*/
			
			ImagePng ($im, BASE."/images/graphs/".$name.".png");
		// }
	}
}
?>
