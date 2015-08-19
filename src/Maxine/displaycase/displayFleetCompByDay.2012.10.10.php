<?PHP
	// Prep {
		// Groundwork {
			$conf		= $_POST;
			
			// Defines and includes {
				$times				= substr_count($_SERVER['PHP_SELF'],"/");
				$rootaccess		= "";
				$i						= 1;
				
				while ($i < $times) {
					$rootaccess .= "../";
					$i++;
				}
				
				define("BASE", $rootaccess);
				
				include_once(BASE."/basefunctions/localdefines.php");
				include_once(BASE."/basefunctions/dbcontrols.php");
				include_once(BASE."/basefunctions/baseapis/manapi.php");
				include_once(BASE."Maxine/api/maxineapi.php");
				
				require_once(BASE."basefunctions/baseapis/fleetDayHandler.php");
				
				$link					= mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
				$db_selected	= mysql_select_db(DB_SCHEMA, $link);
				
				$fleetdayobj = new fleetDayHandler;
			// }
		// }
		
		// Sort functions {
			function cmpBudgets($a, $b) {
				if ($a["Date"] == $b["Date"]) {
					return 0;
				}
				return ($a["Date"] < $b["Date"]) ? -1 : 1;
			}
			
			function cmpScores($a, $b) {
				if ($a["score"] == $b["score"]) {
					return 0;
				}
				return ($a["score"] > $b["score"]) ? -1 : 1;
			}
		// }
		
		// Create date strings for query {
			$startday			= date("d");
			$startmonth		= date("m");
			$startyear		= date("Y");
			
			$startstring	= $startyear."-".$startmonth."-".$startday;
			
			$stopdate		= mktime(0, 0, 0, $startmonth, (date("d") + 1), $startyear);
			$stopday		= date("d", $stopdate);
			$stopmonth	= date("m", $stopdate);
			$stopyear		= date("Y", $stopdate);
			
			$stopstring	= $stopyear."-".$stopmonth."-".$stopday;
			
			$count		= 0;
			$rowcount	= 0;
		// }
		
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
		
		$fleetlist	= $fleetdayobj->getIncomeFleets();
		
		// Pull the details for each fleet {
			foreach ($fleetlist as $fleetkey=>$fleetval) {
				if($fleetval["displayrasta"]==1) {
					$fleetdetails	= $fleetdayobj->getFleetScoreDay($fleetval["id"]);
					$fleetid			= $fleetval["id"];
					
					$fleetscore[$fleetid]["name"]	= $fleetval["name"];
					
					foreach ($fleetdetails as $daykey=>$dayval) {
						$fleetscore[$fleetid]["income"]	+= $dayval["income"];
						$fleetscore[$fleetid]["kms"]		+= $dayval["kms"];
						$fleetscore[$fleetid]["budget"]	+= $dayval["budget"];
					}
					if($fleetscore[$fleetid]["budget"] > 0) {
						$fleetscore[$fleetid]["score"]	= round($fleetscore[$fleetid]["income"] / $fleetscore[$fleetid]["budget"] * 100, 0);
					} else {
						$fleetscore[$fleetid]["score"]	= 0;
					}
					$fleetscore[$fleetid]["income"]	= round($fleetscore[$fleetid]["income"], 0);
					$fleetscore[$fleetid]["budget"]	= round($fleetscore[$fleetid]["budget"], 0);
					$rowcount++;
				}
			}
		
			uasort($fleetscore, "cmpScores");
			$slidertop	= $fleetdayobj->calcSliderTop($fleetscore[29]["budget"]);
		// }
	// }
	print("<div style=\"margin:5px auto 0;width:".round($maxwidth-20,0)."px;\">");
	print("<div style=\"float:left;\">");
	embedObject("/images/Comp_Slider.swf", array(
			"FlashVars"=>"min=0&max=".$slidertop."&slide=".$fleetscore[29]["income"]."&budget=".$fleetscore[29]["budget"]."&graph_title=Entire Active Fleet",
			"height"=>round((748 * $factor), 0),
			"name"=>'slider',
			"width"=>round((250 * $factor), 0)
	));
	print("</div>");
	print("<div style=\"float:right;width:".round(($maxwidth-25-(250*$factor)),0)."px;\">");
	print("<div style=\"margin-bottom:10px;width:100%;\">");
	embedObject("/images/Comp_Heading.swf", array(
			"FlashVars"=>"heading=Daily Fleet Budget Comparison",
			"height"=>round((95 * $factor), 0),
			"name"=>'slider',
			"width"=>round((1069 * $factor), 0)
	));
	print("</div>");
	foreach ($fleetscore as $fskey=>$fsval) {
		embedObject("/images/rasta_narrow.swf", array(
				"FlashVars"=>"fleet=".$fsval["name"]."&income=".$fsval["income"]."&target=".$fsval["budget"],
				"height"=>round((52 * $factor), 0),
				"name"=>'graph',
				"width"=>round((1069 * $factor), 0)
		));
	}
	print("</div>");
	print("</div>");