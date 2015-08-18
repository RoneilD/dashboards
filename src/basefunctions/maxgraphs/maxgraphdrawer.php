<?php
class pointer {
	function downPointer($image, $xpoint, $ypoint, $colour) {
		$black	= imagecolorallocate($image, 0x00, 0x00, 0x00);
		
		$values	= array(
		($xpoint-3),  ($ypoint-11),  // Top Left
		($xpoint+3),  ($ypoint-11),  // Top Right
		($xpoint+3),  ($ypoint-6),  // Inner Right
		($xpoint+6),  ($ypoint-6),  // Outer Right
		$xpoint,  $ypoint,  // Point
		($xpoint-6),  ($ypoint-6),  // Outer Left
		($xpoint-3),  ($ypoint-6)   // Inner Left
		);
		imagefilledpolygon($image, $values, 7, $colour);
		imagepolygon($image, $values, 7, $black);
	}
	
	function rightPointer($image, $xpoint, $ypoint, $colour) {
		$black	= imagecolorallocate($image, 0x00, 0x00, 0x00);
		
		$values	= array(
		($xpoint-12),  ($ypoint-1),  // Back Top
		($xpoint-9),  ($ypoint-1),  // Inner Top
		($xpoint-9),  ($ypoint-5),  // Outer Top
		($xpoint-2),  ($ypoint+2),  // Point
		($xpoint-9),  ($ypoint+9),  // Outer Bottom
		($xpoint-9),  ($ypoint+5),  // Inner Bottom
		($xpoint-12),  ($ypoint+5)   // Back Bottom
		);
		imagefilledpolygon($image, $values, 7, $colour);
		imagepolygon($image, $values, 7, $black);
	}
	
	function upPointer($image, $xpoint, $ypoint, $colour) {
		$black	= imagecolorallocate($image, 0x00, 0x00, 0x00);
		
		$values	= array(
		($xpoint-3),  ($ypoint+15),  // Top Left
		($xpoint+3),  ($ypoint+15),  // Top Right
		($xpoint+3),  ($ypoint+10),  // Inner Right
		($xpoint+6),  ($ypoint+10),  // Outer Right
		$xpoint,  ($ypoint+4),  // Point
		($xpoint-6),  ($ypoint+10),  // Outer Left
		($xpoint-3),  ($ypoint+10)   // Inner Left
		);
		imagefilledpolygon($image, $values, 7, $colour);
		imagepolygon($image, $values, 7, $black);
	}
	
	function leftPointer($image, $xpoint, $ypoint, $colour) {
		$black	= imagecolorallocate($image, 0x00, 0x00, 0x00);
		
		$values	= array(
		($xpoint+13),  ($ypoint-1),  // Back Top
		($xpoint+10),  ($ypoint-1),  // Inner Top
		($xpoint+10),  ($ypoint-5),  // Outer Top
		($xpoint+3),  ($ypoint+2),  // Point
		($xpoint+10),  ($ypoint+9),  // Outer Bottom
		($xpoint+10),  ($ypoint+5),  // Inner Bottom
		($xpoint+13),  ($ypoint+5)   // Back Bottom
		);
		imagefilledpolygon($image, $values, 7, $colour);
		imagepolygon($image, $values, 7, $black);
	}
}

class drawXlinegraph {
	function drawXLineGraph($data) {
		// set data, style (fonts, etc) to be used (This must all be done before ANY GD functions) {
			// Data {
				$values				= $data["values"];
				$name					= $data["meta"]["name"];
				
				$imgheight		= $data["meta"]["height"];
				$imgwidth			= $data["meta"]["width"];
				
				$graphleft		= 5;
				$graphright		= $data["meta"]["width"] - 6;
				
				$graphwidth		= $graphright - $graphleft;
				
				$leftmargin		= $data["meta"]["margins"]["left"];
				//$leftmargin		= 0;
				
				$topval				= $data["meta"]["topval"];
				$bottomval		= $data["meta"]["bottomval"];
				
				$rows					= $data["meta"]["rows"];
				
				$keylist			= $data["meta"]["keylist"];
				if($data["meta"]["keywidth"]) {
					$keywidth			= $data["meta"]["keywidth"];
				} else {
					$keywidth 		= 100;
				}
				if($data["meta"]["keysperrow"]) {
					$keysperrow		= $data["meta"]["keysperrow"];
				} else {
					$keysperrow		= 4;
				}
				$colours			= $data["meta"]["colours"];
				
				
				$lines				= count($values);
				
				$keyrows = floor(($lines - 1) / $keysperrow);
				$graphtop			= 30;
				$graphbottom	= $data["meta"]["height"] - 25 - ($keyrows * 15);
				
				$maxheight		= 0;
				
				if($data["pointer"]) {
					$pointer = $data["pointer"];
				}
				
				foreach ($values as $divkey=>$divval) {
					foreach ($divval as $barkey=>$barval) {
						if($barval > $maxheight) {
							$maxheight = $barval;
						}
					}
				}
				
				$divlist			= $data["meta"]["divlist"];
				$divtitlelist	= $data["meta"]["divtitles"];
				$keycount			= count($divlist)+1; // The number of lines, + 1 so a row set is not presented at the final coordinate of x;
				
				$valrange		 	= $topval - $bottomval; // The range of values, as from least (bottom of graph) to most (top of graph)
				$ratio				= ($graphbottom - $graphtop) / $valrange;
				$difinc				= round(($graphright / $keycount), 2); // The pixel increment between each division of $baselinevalues;
				
				$valinc = ($topval - $bottomval) / $rows; // The value increment between each line; 
				$pixinc = ($graphbottom - $graphtop) / $rows; // The pixel increment between each line;
			// }
			
			// Font size and header placement{
				$font = BASE."/basefunctions/fonts/verdana.ttf";
				if($graphwidth < 200) {
					$linefontsize		= 6;
					$titlesize			= 7;
					$subtitlesize		= 6;
					$headery				= 8;
				} else if($graphwidth < 450) {
					$linefontsize		= 7;
					$titlesize			= 8;
					$subtitlesize		= 7;
					$headery				= 10;
				} else {
					$linefontsize		= 8;
					$titlesize			= 12;
					$subtitlesize		= 10;
					$headery				= 15;
				}
			// }
		// }
		
		// create image {
			$im = imagecreate($imgwidth, $imgheight);
		// }
		
		// Colours {
			$white			= imagecolorallocate($im, 255, 255, 255);
			$imgbacksource		= MAXINETOP;
			$graphbacksource	= MAXINEBACK;
			$imgback		= imagecolorallocate($im, hexdec('0x' . $imgbacksource{1} . $imgbacksource{2}), hexdec('0x' . $imgbacksource{3} . $imgbacksource{4}), hexdec('0x' . $imgbacksource{5} . $imgbacksource{6}));
			$graphback		= imagecolorallocate($im, hexdec('0x' . $graphbacksource{1} . $graphbacksource{2}), hexdec('0x' . $graphbacksource{3} . $graphbacksource{4}), hexdec('0x' . $graphbacksource{5} . $graphbacksource{6}));
			//$bg					= imagecolorallocate($im, 200, 200, 200);
			$black			= imagecolorallocate($im, 0x00, 0x00, 0x00);
			$red				= imagecolorallocate($im, 255, 0, 0);
			$yellow			= imagecolorallocate($im, 255, 255, 0);
			$green			= imagecolorallocate($im, 40, 150, 40);
			$blue				= imagecolorallocate($im, 90, 90, 255);
			$barcolor		= imagecolorallocate($im, 0xFF, 0x00, 0x00); // Fore colour
			
			foreach ($colours as $colourkey=>$colourval) {
				$colourlist[$colourkey] = imagecolorallocate($im, hexdec('0x' . $colourval{0} . $colourval{1}), hexdec('0x' . $colourval{2} . $colourval{3}), hexdec('0x' . $colourval{4} . $colourval{5}));;
			}
		// }
		
		// draw Image backdrop {
			//imagerectangle($im, 0,0,$imgwidth+1,$imgheight+1,$black);
			imageline($im, (15+$leftmargin), 0, ($imgwidth-15), 0, $black);														// Top
			imageline($im, $leftmargin, 15, $leftmargin, $imgheight-10, $black);											// Left
			imageline($im, ($imgwidth-1), 15, ($imgwidth-1), ($imgheight-16), $black);										// Right
			imageline($im, 10, ($imgheight-1), ($imgwidth-16), ($imgheight-1), $black);								// Bottom
			
			imagearc($im, (14+$leftmargin), 14, 30, 30, 181, 269, $black);														// Top left
			imagearc($im, ($imgwidth-15), 14, 30, 30, 271, 359, $black);															// Top right
			imagearc($im, (14+$leftmargin), ($imgheight-14), 30, 30, 91, 179, $black);								// Bottom left
			imagearc($im, ($imgwidth-15), ($imgheight-15), 30, 30, 1, 89, $black);											// Bottom right
			// imagearc(resource, x, y, imgheight, imgwidth, degree start, degree end, colour)
			
			//imagearc($im, 40, 40, 30, 20, 91, 179, $black);								// Bottom left
			
			imagefill($im, 20, 20, $imgback);
		// }
		
		// Draw Graph Backdrop{
			//imagerectangle($im, 0,0,$graphwidth+1,$graphheight+1,$black);
			imageline($im, ($graphleft+$leftmargin+5), ($graphtop), ($graphright-5), ($graphtop), $black); // Top
			imageline($im, ($graphleft+$leftmargin), ($graphtop+5), ($graphleft+$leftmargin), ($graphbottom-5), $black); // Left
			imageline($im, ($graphright), ($graphtop+5), ($graphright), ($graphbottom-5), $black); // Right
			imageline($im, (10+$leftmargin), ($graphbottom), ($graphright-5), ($graphbottom), $black); // Bottom
			
			imagearc($im, ($graphleft+$leftmargin+4), ($graphtop+4), 10, 10, 181, 269, $black); // Top left
			imagearc($im, ($graphright-4), ($graphtop+4), 10, 10, 271, 359, $black); // Top right
			imagearc($im, ($graphleft+$leftmargin+4), ($graphbottom-4), 10, 10, 91, 179, $black); // Bottom left
			imagearc($im, ($graphright-4), ($graphbottom-4), 10, 10, 1, 89, $black); // Bottom Right
			// imagearc(resource, x, y, graphheight, graphwidth, degree start, degree end, colour)
			
			imagefill($im, ($graphleft+5), ($graphtop+5), $graphback);
		// }
		
		// Draw dividing lines {
			for ($linecount=0; $linecount < $rows; $linecount++) {
				$linepos	= $graphbottom - (($linecount * $pixinc));
				$linetext	= ($linecount * $valinc) + $bottomval;
				imagettftext($im, $linefontsize, 0, ($graphleft+5), ($linepos-1), $black, $font, $linetext);
				if($linecount != 0) {
					imageline($im, $graphleft, $linepos, $graphright, $linepos, $black);
				}
			}
		// }
		
		// Draw the base values across the graph bottom {
			$basecount = 1;
			if($keycount >= 10) {
				$tilt = 90;
			} else {
				$tilt = 0;
			}
			
			foreach ($divlist as $key=>$keyval) {
				$textxpos = $basecount * $difinc + 13;
				imagettftext($im, $linefontsize, $tilt, $textxpos, ($graphbottom - 1), $black, $font, $divtitlelist[$keyval]);
				$basecount++;
			}
		// }
		
		// draw the data lines {
			//imagettftext($im, $linefontsize, 0, 600, 20, $black, $font, $basecount." ".$difinc);
			$linecount	= 1;
			//imagesetthickness($im, 1);
			
			foreach ($values as $linekey=>$lineval) {
				$oldx				= "x";
				$oldy				= "x";
				$pointcount = 1;
				
				foreach ($divlist as $pointkey=>$pointval) {
					if($lineval[$pointval]) {
						$pointvalue	= $lineval[$pointval];
						$ypos				= $graphbottom - (($pointvalue - $bottomval) * $ratio);
						$xpos = $pointcount * $difinc + 10;
						imagefilledarc($im, $xpos, $ypos, 7, 7, 1, 360 , $colourlist[$linekey], IMG_ARC_PIE);
						if($oldx != "x") {
							imageline($im, $oldx, $oldy, $xpos, $ypos, $colourlist[$linekey]);
						}
						
						if($pointer[$linekey]==$pointval) {
							$pointerx	= $xpos;
							$pointery	= $ypos - 2;
							$pointercolour = $colourlist[$linekey];
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
		
		// Pointer {
			$myclass = new pointer;
			if((!$data["pointer"]["direction"]) && ($data["pointer"])) {
				$data["pointer"]["direction"] = 1;
			} else {
				$data["pointer"]["direction"] = $data["pointer"]["direction"];
			}
			if($data["pointer"]["direction"] == 1) {
				$myclass->downPointer($im, $pointerx, $pointery, $pointercolour);
			} else if($data["pointer"]["direction"] == 2) {
				$myclass->rightPointer($im, $pointerx, $pointery, $pointercolour);
			} else if ($data["pointer"]["direction"] == 3){
				$myclass->upPointer($im, $pointerx, $pointery, $pointercolour);
			} else if($data["pointer"]["direction"] == 4) {
				$myclass->leftPointer($im, $pointerx, $pointery, $pointercolour);
			}
		// }
		
		// Titling {
			$title = $data["meta"]["title"];
			$subtitle = $data["meta"]["subtitle"];
			
			$lettercount	= strlen($title);
			$titlex				= ($imgwidth / 2) - ($lettercount * 4);
			imagettftext($im, $titlesize, 0, $titlex, $headery, $black, $font, $title);
			
			$lettercount	= strlen($subtitle);
			$titlex				= ($imgwidth / 2) - ($lettercount * 3);
			imagettftext($im, $subtitlesize, 0, $titlex, ($headery+$titlesize), $black, $font, $subtitle);
		// }
		
		// Key {
			$keytextcount = 1;
			
			$keyy					= $imgheight-10;
			$keyx				 	= 30;
			foreach ($values as $valkey=>$valval) {
				$lettercount	= strlen($title);
				imagefilledrectangle($im, ($keyx-14), ($keyy-8), ($keyx-6), ($keyy-1), $colourlist[$valkey]);
				imagerectangle($im, ($keyx-15), ($keyy-9), ($keyx-5), $keyy, $black);
				imagettftext($im, $linefontsize, 0, $keyx, $keyy, $black, $font, $keylist[$valkey]);
				$keytextcount++;
				$keyx					+= $keywidth - 20;
				if($keytextcount > $keysperrow) {
					$keytextcount = 1;
					$keyx = 30;
					$keyy -= 15;
				}
				if($keyx > ($imgwidth - 70)) {
					$keyx			= 30;
					$keyy			= $keyy - 15;
				}
			}
		// }
		
		// Save image to directory for program to access {
			ImagePng ($im, BASE."/images/graphs/".$name.".png");
		// }
	}
}

class drawXBargraph {
	function drawXBarGraph($data) {
		// set data, style (fonts, etc) to be used (This must all be done before ANY GD functions) {
			// Data {
				$name				= $data["meta"]["name"];
				if($data["meta"]["class"]) {
					$class		= $data["meta"]["class"];
				} else {
					$class		= "sets";
				}
				
				$keylist		= $data["key"];
				
				$imgheight		= $data["meta"]["height"];
				$imgwidth			= $data["meta"]["width"];
				
				$graphleft		= 5;
				$graphright		= $data["meta"]["width"] - 6;
				$graphtop			= 30;
				$graphbottom	= $data["meta"]["height"] - 50;
				
				$graphwidth		= $graphright - $graphleft;
				
				$leftmargin		= 0;
				
				$colours		= $data["meta"]["colours"];
				
				$topval			= $data["meta"]["topval"];
				$bottomval	= $data["meta"]["bottomval"];
				$cutoff			= $data["meta"]["cutoff"];
				
				$rows				= $data["meta"]["rows"];
				$barlist 		= $data["meta"]["barlist"];
				
				$barwidth		= $data["meta"]["barwidth"];
				$bargap			= $data["meta"]["bargap"];
				
				$values			= $data["values"];
				$maxheight	= 0;
				foreach ($values as $divkey=>$divval) {
					foreach ($divval as $barkey=>$barval) {
						if($barval > $maxheight) {
							$maxheight = $barval;
						}
					}
				}
				
				if($data["pointer"]) {
					$pointer = $data["pointer"];
				}
				$settotal		= count($values)+1; // The number of lines, + 1 so a row set is not presented at the final coordinate of x;
				
				$valrange	 	= $topval - $bottomval; // The range of values, as from least (bottom of graph) to most (top of graph)
				$ratio			= ($graphbottom - $graphtop) / $valrange;
				$divinc			= round((($graphright - $graphleft) / $settotal), 2); // The pixel increment between each division of $baselinevalues;
			// }
			
			// Font size and header placement{
				$font = BASE."/basefunctions/fonts/verdana.ttf";
				if($graphwidth < 200) {
					$linefontsize		= 6;
					$titlesize			= 7;
					$subtitlesize		= 6;
					$headery				= 8;
				} else if($graphwidth < 450) {
					$linefontsize		= 7;
					$titlesize			= 10;
					$subtitlesize		= 7;
					$headery				= 11;
				} else {
					$linefontsize		= 8;
					$titlesize			= 12;
					$subtitlesize		= 10;
					$headery				= 15;
				}
			// }
		// }
		
		// create image {
			$im = imagecreate($imgwidth+1, $imgheight+1);
		// }
		
		// Colours {
			$white		= imagecolorallocate($im, 255, 255, 255);
			//$bg = imagecolorallocate($im, hexdec('0x' . $colour{0} . $colour{1}), hexdec('0x' . $colour{2} . $colour{3}), hexdec('0x' . $colour{4} . $colour{5}));
			$imgbacksource		= MAXINETOP;
			$graphbacksource	= MAXINEBACK;
			$imgback		= imagecolorallocate($im, hexdec('0x' . $imgbacksource{1} . $imgbacksource{2}), hexdec('0x' . $imgbacksource{3} . $imgbacksource{4}), hexdec('0x' . $imgbacksource{5} . $imgbacksource{6}));
			$graphback		= imagecolorallocate($im, hexdec('0x' . $graphbacksource{1} . $graphbacksource{2}), hexdec('0x' . $graphbacksource{3} . $graphbacksource{4}), hexdec('0x' . $graphbacksource{5} . $graphbacksource{6}));
			$black			= imagecolorallocate($im, 0x00, 0x00, 0x00);
			$red				= imagecolorallocate($im, 255, 0, 0);
			$yellow			= imagecolorallocate($im, 255, 255, 0);
			$lime				= imagecolorallocate($im, 195, 245, 90);
			$green			= imagecolorallocate($im, 40, 150, 40);
			$blue				= imagecolorallocate($im, 90, 90, 255);
			$barcolor		= imagecolorallocate($im, 0xFF, 0x00, 0x00); // Fore colour
			
			$colourcount = 1;
			if($colours) {
				foreach ($colours as $colourkey=>$colourval) {
					$colourlist[$colourkey] = imagecolorallocate($im, hexdec('0x' . $colourval{0} . $colourval{1}), hexdec('0x' . $colourval{2} . $colourval{3}), hexdec('0x' . $colourval{4} . $colourval{5}));;
					$colourcount++;
				}
			} else {
				$colourlist	= array("0"=>$green, "1"=>$yellow, "2"=>$red, "3"=>$blue, "4"=>$lime,);
			}
		// }
		
		// draw Image backdrop {
			//imagerectangle($im, 0,0,$imgwidth+1,$imgheight+1,$black);
			imageline($im, (15), 0, ($imgwidth-15), 0, $black);														// Top
			imageline($im, $leftmargin, 15, $leftmargin, $imgheight-10, $black);					// Left
			imageline($im, ($imgwidth), 15, ($imgwidth), ($imgheight-15), $black);				// Right
			imageline($im, 10, ($imgheight), ($imgwidth-15), ($imgheight), $black);				// Bottom
			
			imagearc($im, (14), 14, 30, 30, 181, 269, $black);														// Top left
			imagearc($im, ($imgwidth-14), 14, 30, 30, 271, 359, $black);									// Top right
			imagearc($im, (14), ($imgheight-14), 30, 30, 91, 179, $black);								// Bottom left
			imagearc($im, ($imgwidth-14), ($imgheight-14), 30, 30, 1, 89, $black);				// Bottom right
			// imagearc(resource, x, y, imgheight, imgwidth, degree start, degree end, colour)
			
			//imagearc($im, 40, 40, 30, 20, 91, 179, $black);								// Bottom left
			
			imagefill($im, 20, 20, $imgback);
		// }
		
		// Draw Graph Backdrop{
			//imagerectangle($im, 0,0,$graphwidth+1,$graphheight+1,$black);
			imageline($im, ($graphleft+5), ($graphtop), ($graphright-5), ($graphtop), $black); // Top
			imageline($im, ($graphleft), ($graphtop+5), ($graphleft), ($graphbottom-5), $black); // Left
			imageline($im, ($graphright), ($graphtop+5), ($graphright), ($graphbottom-5), $black); // Right
			imageline($im, (10), ($graphbottom), ($graphright-5), ($graphbottom), $black); // Bottom
			
			imagearc($im, ($graphleft+4), ($graphtop+4), 10, 10, 181, 269, $black); // Top left
			imagearc($im, ($graphright-4), ($graphtop+4), 10, 10, 271, 359, $black); // Top right
			imagearc($im, ($graphleft+4), ($graphbottom-4), 10, 10, 91, 179, $black); // Bottom left
			imagearc($im, ($graphright-4), ($graphbottom-4), 10, 10, 1, 89, $black); // Bottom Right
			// imagearc(resource, x, y, graphheight, graphwidth, degree start, degree end, colour)
			
			imagefill($im, ($graphleft+5), ($graphtop+5), $graphback);
		// }
		
		// Draw dividing lines {
			$valinc = ($topval - $bottomval) / $rows; // The value increment between each line; 
			$pixinc = ($graphbottom - $graphtop) / $rows; // The pixel increment between each line;
			//imagettftext($im, $linefontsize, 0, 350, 80, $black, $font, "Rows : ".$rows.", Valinc : ".$valinc.", Pixinc : ".$pixinc);
			for ($linecount=0; $linecount < $rows; $linecount++) {
				$linepos	= $graphbottom - (($linecount * $pixinc));
				$linetext	= ($linecount * $valinc) + $bottomval;
				imagettftext($im, $linefontsize, 0, ($graphleft+2), ($linepos-1), $black, $font, $linetext);
				if($linecount != 0) {
					imageline($im, $graphleft, $linepos, $graphright, $linepos, $black);
				}
			}
		// }
		
		// draw the bars {
			if($class == "sets") {
				$setcount = 1;
				foreach ($values as $setkey=>$setval) {
					// $fallback is the calculated distance back from the X point so that the set is centered around it.
					$bartotal	= count($setval);
					$fallback = floor($bartotal / 2);
					$fallback = $fallback * $barwidth;
					
					$xpos			= ($setcount * $divinc) - $fallback;
					$barcount	= 1;
					
					foreach ($barlist as $listkey=>$listval) {
						if($setval[$listval]) {
							$barheight = $graphbottom - (($setval[$listval] - $bottomval) * $ratio);
							//imagettftext($im, $linefontsize, 0, $xpos, $barheight, $black, $font, $setval[$listval]);
							imagefilledrectangle($im, $xpos, $barheight, ($xpos+$barwidth), ($graphbottom-1), $colourlist[$listkey]);
							$barcount++;
							$xpos += $bargap;
						}
					}
					
					imagettftext($im, $linefontsize, 90, ($xpos+10), ($graphbottom-1), $black, $font, $setkey);
					$setcount++;
				}
			} else if($class == "span") {
				$xpos = 50;
				$count = 1;
				foreach ($values as $setkey=>$setval) {
					foreach ($setval as $barkey=>$barval) {
						if($barval < $topval) {
							$barheight = $graphbottom - (($barval - $bottomval) * $ratio);
						} else {
							$barheight = $graphtop;
						}
						
						imagefilledrectangle($im, ($xpos), $barheight, ($xpos+$barwidth), ($graphbottom-1), $colourlist[$barkey]);
						imagerectangle($im, ($xpos), $barheight, ($xpos+$barwidth), ($graphbottom), $black);
						$xpos += $bargap;
						
						if($pointer == $count) {
							$myclass	= new pointer;
							$pointerx	= $xpos - floor($barwidth/2);
							$myclass->downPointer($im, $pointerx, $barheight, $colourlist[$barkey]);
						}
					}
					$count++;
				}
			} else if($class == "comparison") {
				$setcount = 1;
				foreach ($values as $setkey=>$setval) {
					// $fallback is the calculated distance back from the X point so that the set is centered around it.
					$bartotal	= count($setval);
					$fallback = floor($bartotal / 2);
					$fallback = $fallback * $barwidth;
					
					$xpos			= ($setcount * $divinc) - $fallback;
					$barcount	= 1;
					
					foreach ($barlist as $listkey=>$listval) {
						if($setval[$listval]) {
							$barheight = $graphbottom - (($setval[$listval] - $bottomval) * $ratio);
							imagefilledrectangle($im, $xpos, $barheight, ($xpos+$barwidth), ($graphbottom-1), $colourlist[$setcount]);
							$barcount++;
							$xpos += $bargap;
						}
					}
					
					imagettftext($im, $linefontsize, 90, ($xpos+10), ($graphbottom-1), $black, $font, $keylist[$setkey][1]);
					$setcount++;
				}
			}
		// }
		
		// Draw the Cutoff line, if required {
			if($cutoff) {
				$cutoffy = $graphbottom - ($cutoff * $ratio);
				imageline($im, $graphleft, $cutoffy, $graphright, $cutoffy, $red);
			}
		// }
		
		// Titling {
			$title				= $data["meta"]["title"];
			$subtitle			= $data["meta"]["subtitle"];
			
			$lettercount	= strlen($title);
			$titlex				= ($imgwidth / 2) - ($lettercount * 4);
			imagettftext($im, $titlesize, 0, $titlex, $headery, $black, $font, $title);
			
			$lettercount	= strlen($subtitle);
			$titlex				= ($imgwidth / 2) - ($lettercount * 3);
			imagettftext($im, $subtitlesize, 0, $titlex, ($headery+$titlesize), $black, $font, $subtitle);
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
