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
		
		$top5	= array();
		
		$link					= mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
		$db_selected	= mysql_select_db(DB_SCHEMA, $link);
		
		if($conf["maxwidth"]) {
			$maxwidth	= $conf["maxwidth"];
			
			if($maxwidth < 1000) {
				$factor = 0.8;
			} else if($maxwidth < 1300) {
				$factor	= 0.94;
			} else if($maxwidth > 1600) {
				$factor	= 1.4;
			} else {
				$factor	= 1;
			}
		} else {
			$factor	= 1;
		}
		
		$trackfleets = array(
			array("id"=>22, "name"=>"A"),
			array("id"=>23, "name"=>"B"),
			array("id"=>55, "name"=>"XBA"),
			array("id"=>56, "name"=>"XBB"),
			array("id"=>78, "name"=>"XBC"),
			array("id"=>74, "name"=>"En")
			);
		
		$currenttime	= date("U");
		
		$backdate			= mktime(0, 0, 0, date("m"), (date("d") - 3), date("Y"));
		$startyear		= date("Y", $backdate);
		$startmonth		= date("m", $backdate);
		$startday			= date("d", $backdate);
		
		$startstring	= $startyear."-".$startmonth."-".$startday;
	// }
	
	// Fetch the report and it's results {
		foreach ($trackfleets as $fleetkey=>$fleetval) {
			$poslist	= array();
			$temppos	= array();
			
			$reporturl = "http://login.max.manline.co.za/m4/2/api_request/Report/export?report=104&responseFormat=csv&Fleet=".$fleetval["id"]."&Start_Date=".$startstring."&numberOfRowsPerPage=10000";
			
			print($reporturl."<br>");
			
			$fileParser = new FileParser($reporturl);
			$fileParser->setCurlFile("fleetPositions.csv");
			$reportresults = $fileParser->parseFile();
			
			if ($reportresults === false) {
				print("There was an error!");
				print("<pre style='font-family:verdana;font-size:13'>");
				print_r($fileParser->getErrors());
				print("</pre>");
				return;
				
				print("<br>");
			}
			
			foreach ($reportresults as $poskey=>$posval) {
				if(($posval["Truck"] != $temppos["Truck"]) && ($temppos!=null)) {
					$poslist[]	= $temppos;
				}
				
				$temppos	= $posval;
			}
			$poslist[]	= $temppos;
			
			usort($poslist, "cmpLoadingDates");
			
			$fleettrucks	= 0;
			$fleetovers		= 0;
			
			foreach ($poslist as $poskey=>$posval) {
				$fleettrucks++;
				$unixtime	= strtotime($posval["Time Created"]);
				$posage			= round((($currenttime-$unixtime) / 60), 0);
				$poslist[$poskey]["age"]	= $posage;
				if($posage > 90) {
					$fleetovers++;
				}
			}
			
			$percentover	= ($fleetovers / $fleettrucks) * 100;
			$percentover	= round($percentover, 0);
			
			$percentontime	= 100 - $percentover;
			
			$top5[$fleetval["id"]]["fleet"]		= $fleetval["id"];
			$top5[$fleetval["id"]]["percent"]	= $percentontime;
			
			for($i=0; $i < 6; $i++) {
				$description	= $poslist[$i]["Desc"];
				$activity			= $poslist[$i]["Activity"];
				$top5[$fleetval["id"]]["trucks"]	.= "&fleet".($i+1)."=".$poslist[$i]["Truck"];
				$top5[$fleetval["id"]]["times"]		.= "&minutes".($i+1)."=".$poslist[$i]["age"]." mins";
				
				$top5[$fleetval["id"]]["sub"]			.= "&detail".($i+1)."=".$description." - ".$activity;
				if($poslist[$i]["age"] > 120) {
					$top5[$fleetval["id"]]["status"]	.= "&status".($i+1)."=0";
				} else {
					$top5[$fleetval["id"]]["status"]	.= "&status".($i+1)."=1";
				}
			}
		}
	// }
	
	foreach ($top5 as $fleetkey=>$fleetval) {
		$positiondetails	= sqlPull(array("table"=>"position_scores", "where"=>"fleet=".$fleetval["fleet"], "onerow"=>1));
		
		if($positiondetails) {
			commitPositionScore($fleetval, $positiondetails["id"]);
		} else {
			commitPositionScore($fleetval, 0);
		}
	}
	
	print("Done.");
	
	function cmpLoadingDates($a, $b) {
		if ($a["Edited"] == $b["Edited"]) {
			return 0;
		}
		return ($a["Edited"] < $b["Edited"]) ? -1 : 1;
	}
?>
