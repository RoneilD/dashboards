<?PHP
	$starttimer		= date("U");
	// Preparation {
		$realPath		= realpath(dirname(__FILE__));
		$maxine			= substr($realPath, 0, strrpos($realPath, DIRECTORY_SEPARATOR));
		$rootaccess	= substr($maxine, 0, strrpos($maxine, DIRECTORY_SEPARATOR)+1);
		define("BASE", $rootaccess);
		
		include_once(BASE."basefunctions/localdefines.php");
		include_once(BASE."basefunctions/dbcontrols.php");
		include_once(BASE."basefunctions/baseapis/manapi.php");
		include_once(BASE."Maxine/api/maxineapi.php");
		
		require_once(BASE."basefunctions/baseapis/FileParser/FileParser.php");
		
		$link					= mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
		$db_selected	= mysql_select_db(DB_SCHEMA, $link);
	// }
	
	// Get right times for two months back
		$startdate		= mktime(0, 0, 0, (date("m")-2), 1, date("Y"));
		$stopdate			= mktime(0, 0, 0, (date("m")-1), 1, date("Y"));
		
		$startstring	= date("Y-m-d", $startdate);
		$stopstring		= date("Y-m-d", $stopdate);
		
		print("2 Month back : ".$startstring." to ".$stopstring."<br>");
		
		$righttimes	= getRightTimes($startstring, $stopstring);
		$righttimes["date"]	= $startdate;
		
		print("<pre style='font-family:verdana;font-size:13'>");
		print_r($righttimes);
		print("</pre>");
		
		$dashdetails	= sqlPull(array("table"=>"greenmile_scores", "where"=>"date=".$startdate, "onerow"=>1));
		
		if($dashdetails) {
			updateGreenmileScore($righttimes);
		} else {
			createGreenmileScore($righttimes);
		}
	// }
	
	// Get right times for previous month
		$startdate		= mktime(0, 0, 0, (date("m")-1), 1, date("Y"));
		$stopdate			= mktime(0, 0, 0, date("m"), 1, date("Y"));
		
		$startstring	= date("Y-m-d", $startdate);
		$stopstring		= date("Y-m-d", $stopdate);
		
		print("Previous Month : ".$startstring." to ".$stopstring."<br>");
		
		$righttimes	= getRightTimes($startstring, $stopstring);
		$righttimes["date"]	= $startdate;
		
		print("<pre style='font-family:verdana;font-size:13'>");
		print_r($righttimes);
		print("</pre>");
		
		$dashdetails	= sqlPull(array("table"=>"greenmile_scores", "where"=>"date=".$startdate, "onerow"=>1));
		
		if($dashdetails) {
			updateGreenmileScore($righttimes);
		} else {
			createGreenmileScore($righttimes);
		}
	// }
	
	// Get right times for current month
		$startdate		= mktime(0, 0, 0, date("m"), 1, date("Y"));
		$stopdate			= mktime(0, 0, 0, date("m"), date("d"), date("Y"));
		
		$startstring	= date("Y-m-d", $startdate);
		$stopstring		= date("Y-m-d", $stopdate);
		
		print("Current Month : ".$startstring." to ".$stopstring."<br>");
		
		$righttimes	= getRightTimes($startstring, $stopstring);
		$righttimes["date"]	= $startdate;
		
		print("<pre style='font-family:verdana;font-size:13'>");
		print_r($righttimes);
		print("</pre>");
		
		$dashdetails	= sqlPull(array("table"=>"greenmile_scores", "where"=>"date=".$startdate, "onerow"=>1));
		
		if($dashdetails) {
			updateGreenmileScore($righttimes);
		} else {
			createGreenmileScore($righttimes);
		}
	// }
	
	$endtimer	= date("U");
	$totaltimer	= $endtimer - $starttimer;
	print("That took ".$totaltimer." seconds.");
	
	function getRightTimes($startstring, $stopstring) {
		// Fetch the report and it's results {
			$reporturl = "http://login.max.manline.co.za/m4/2/api_request/Report/export?report=26&responseFormat=csv&Start_Date=".$startstring."&Stop_Date=".$stopstring."&numberOfRowsPerPage=10000";
			
			$fileParser = new FileParser($reporturl);
			$fileParser->setCurlFile("greenmiledays.csv");
			$reportresults = $fileParser->parseFile();
			
			if ($reportresults === false) {
				print("<pre style='font-family:verdana;font-size:13'>");
				print_r($fileParser->getErrors());
				print("</pre>");
				return;
				
				print("<br>");
			}
		// }
		
		$loadcount				= 0;
		$offloadcount			= 0;
		
		$rightdayload			= 0;
		$righthourload		= 0;
		
		$rightdayoffload	= 0;
		$righthouroffload	= 0;
		
		foreach ($reportresults as $reskey=>$resval) {
			// Loading Difference {
				$loadhourdiff	= $resval["Loading Difference (hrs)"];
				$loaddaydiff	= calcDaysBetweenPlannedVsActual($resval["Planned Loading Arrival"], $resval["Loading Arrival"]);
				
				//print($resval["Planned Loading Arrival"]." ".$resval["Loading Arrival"]."<br>");
				//print($loadhourdiff." ".$loaddaydiff."");
				
				if($loaddaydiff >= 0) {
					$loadcount++;
					if(($loadhourdiff < 4) && ($loadhourdiff > -4)) {
						//print(" Load Hour +");
						$righthourload++;
					}
					if($loaddaydiff == 0) {
						$rightdayload++;
						//print(" Load Day +");
					}
				}
			// }
			
			// Offoading Difference {
				$offloadhourdiff	= $resval["Offloading Difference (hrs)"];
				$offloaddaydiff		= calcDaysBetweenPlannedVsActual($resval["Planned Offloading Arrival"], $resval["Offloading Arrival"]);
				
				if($offloaddaydiff >=0) {
					$offloadcount++;
					if(($offloadhourdiff < 4) && ($offloadhourdiff > -4)) {
						$righthouroffload++;
					}
					if($offloaddaydiff == 0) {
						$rightdayoffload++;
					}
				}
			// }
		}
		
		if(($loadcount > 0) && ($offloadcount > 0)) {
			$result["loadtime"]			= round(($righthourload / $loadcount) * 100, 2);
			$result["loadday"]			= round(($rightdayload / $loadcount) * 100, 2);
		
			$result["offloadtime"]		= round(($righthouroffload / $offloadcount) * 100, 2);
			$result["offloadday"]	= round(($rightdayoffload / $offloadcount) * 100, 2);
		} else {
			$result["loadday"]			= 0;
			$result["loadtime"]			= 0;
			
			$result["offloadday"]		= 0;
			$result["offloadtime"]	= 0;
		}
		
		return $result;
	}
	
	function calcDaysBetweenPlannedVsActual($planned, $actual) {
		if($actual != "(none)") {
			$plannedday		= substr($planned, 8, 2);
			$plannedmonth	= substr($planned, 5, 2);
			$plannedyear	= substr($planned, 0, 4);
			
			$actualday		= substr($actual, 8, 2);
			$actualmonth	= substr($actual, 5, 2);
			$actualyear		= substr($actual, 0, 4);
			
			if($plannedmonth < $actualmonth) {
				$monthdays	= date("t", mktime(0,0,0,$plannedmonth,$plannedday,$plannedyear));
				$diff				= $actualday + $monthdays - $plannedday;
			} else if($plannedmonth > $actualmonth) {
				$monthdays	= date("t", mktime(0,0,0,$actualmonth,$actualday,$actualyear));
				$diff				= $plannedday + $monthdays - $actualday;
			} else if($plannedday < $actualday) {
				$diff				= $actualday - $plannedday;
			} else {
				$diff				= $plannedday - $actualday;
			}
			
			$diffyear			= $plannedyear - $actualyear;
		} else {
			$diff	= -1;
		}
		return $diff;
	}
?>
