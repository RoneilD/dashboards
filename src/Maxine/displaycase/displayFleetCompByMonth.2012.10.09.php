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
				if($fleetval["displayrasta"] == 1) {
					$fleetdetails	= $fleetdayobj->getFleetScoreMonth($fleetval["id"]);
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
	
	print("<table width=100% cellpadding=0 cellspacing=0 border=0>");
	
	print("<tr><td align=center colspan=2>");
	print("<embed src='".BASE."/images/Comp_Heading.swf'
		FlashVars='heading=Month to Date Fleet Budget Comparison'
		quality='high'
		width='".(1170 * $factor)."px'
		height='".(89 * $factor)."px'
		name='slider'
		wmode='transparent'
		allowScriptAccess='sameDomain'
		allowFullScreen='false'
		type='application/x-shockwave-flash'
		pluginspage='http://www.macromedia.com/go/getflashplayer' />");
	print("</td></tr>");
	
	print("<tr><td rowspan=".$rowcount." align=right>");
	print("<embed src='".BASE."/images/Comp_Slider_MTD.swf'
		FlashVars='min=0&max=".$slidertop."&slide=".$fleetscore[29]["income"]."&budget=".$fleetscore[29]["budget"]."&graph_title=Entire Active Fleet'
		quality='high'
		width='".(225 * $factor)."px'
		height='".(650 * $factor)."px'
		name='slider'
		wmode='transparent'
		allowScriptAccess='sameDomain'
		allowFullScreen='false'
		type='application/x-shockwave-flash'
		pluginspage='http://www.macromedia.com/go/getflashplayer' />");
	print("</td>");
	
	foreach ($fleetscore as $fskey=>$fsval) {
		if(($fsval["budget"] > 0) && ($fskey != 29)) {
			if($count > 0) {
				print("<tr>");
			}
			
			print("<td align=left height=1px>");
			print("<embed src='".BASE."/images/Rasta.swf'
				FlashVars='fleet=".$fsval["name"]."&income=".$fsval["income"]."&target=".$fsval["budget"]."'
				quality='high'
				width='".(990 * $factor)."px'
				height='".(62 * $factor)."px'
				name='graph'
				wmode='transparent'
				allowScriptAccess='sameDomain'
				allowFullScreen='false'
				type='application/x-shockwave-flash'
				pluginspage='http://www.macromedia.com/go/getflashplayer' />");
			print("</td></tr>");
			$count++;
		}
	}
	
	print("</table>");
?>
