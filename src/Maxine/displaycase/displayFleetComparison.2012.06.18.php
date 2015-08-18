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
		
		$fleetlist	= $fleetdayobj->getIncomeFleets();
		
		// Pull the details for each fleet {
			foreach ($fleetlist as $fleetkey=>$fleetval) {
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
			
			uasort($fleetscore, "cmpScores");
			$slidertop	= $fleetdayobj->calcSliderTop($fleetscore[29]["budget"]);
		// }
		print("<table width=100% cellpadding=0 cellspacing=0 border=0>");
		
		print("<tr><td align='center' colspan=2>");
		print("<embed src='".BASE."/images/Heading.swf'
			FlashVars='heading=Daily Fleet Budget Comparison'
			quality='high'
			width='1765px'
			height='104px'
			name='slider'
			wmode='transparent'
			allowScriptAccess='sameDomain'
			allowFullScreen='false'
			type='application/x-shockwave-flash'
			pluginspage='http://www.macromedia.com/go/getflashplayer' />");
		print("</td></tr>");
		
		print("<tr><td rowspan=".$rowcount.">");
		print("<embed src='".BASE."/images/Comp_Slider.swf'
			FlashVars='min=0&max=".$slidertop."&slide=".$fleetscore[29]["income"]."&budget=".$fleetscore[29]["budget"]."&graph_title=Entire Active Fleet'
			quality='high'
			width='330px'
			height='950px'
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
				
				print("<td align=center height=1px>");
				print("<embed src='".BASE."/images/Rasta.swf'
					FlashVars='fleet=".$fsval["name"]."&income=".$fsval["income"]."&target=".$fsval["budget"]."'
					quality='high'
					width='1510px'
					height='98px'
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
	// }
?>
