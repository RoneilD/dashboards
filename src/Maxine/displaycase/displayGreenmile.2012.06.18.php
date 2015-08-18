<?PHP
	// Prep {
		$conf		= $_POST;
		// Groundwork {
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
			
			if(date("j") > 1) {
				$currentmonth	= mktime(0, 0, 0, (date("m")-2), 1, date("Y"));
				$prevmonth		= mktime(0, 0, 0, (date("m")-3), 1, date("Y"));
			} else {
				$currentmonth	= mktime(0, 0, 0, (date("m")-4), 1, date("Y"));
				$prevmonth		= mktime(0, 0, 0, (date("m")-5), 1, date("Y"));
			}
			
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
		// }
		
		$deptlist				= sqlPull(array("table"=>"greenmile_depts", "where"=>"deleted=0"));
		$count					= 0;
		$currentoverall	= 0;
		$prevoverall		= 0;
		
		foreach ($deptlist as $deptkey=>$deptval) {
			$currenttotalscore	= 0;
			$prevtotalscore			= 0;
			
			$currentscore				= 0;
			$prevscore					= 0;
			
			$catcount						= 0;
			$difference					= 0;
			
			//print($deptval["name"]."<br>");
			$catlist	= sqlPull(array("table"=>"greenmile_categories","where"=>"deptid=".$deptval["id"]." AND deleted=0"));
			
			foreach ($catlist as $catkey=>$catval) {
				$currentcatscore	= sqlPull(array("table"=>"greenmile_scores", "where"=>"date=".$currentmonth." AND catid=".$catval["id"], "onerow"=>1));
				$prevcatscore			= sqlPull(array("table"=>"greenmile_scores", "where"=>"date=".$prevmonth." AND catid=".$catval["id"], "onerow"=>1));
				
				$currenttotalscore	+= $currentcatscore["score"];
				$prevtotalscore			+= $prevcatscore["score"];
				$catcount++;
				
				//print("Current Category Score: ".$currentcatscore["score"]."<br>");
				//print("Previous Category Score: ".$prevcatscore["score"]."<br>");
			}
			
			//print("Current Total Score: ".$currenttotalscore."<br>");
			//print("Previous Total Score: ".$prevtotalscore."<br>");
			
			$currentscore	= round($currenttotalscore / $catcount, 2);
			$prevscore		= round($prevtotalscore / $catcount, 2);
			if($currentscore > $prevscore) {
				$difference	= round($currentscore - $prevscore, 2);
				$direction	= "up";
			} else if($currentscore < $prevscore) {
				$difference	= round($prevscore - $currentscore, 2);
				$direction	= "down";
			} else {
				$difference	= 0;
				$direction	= 0;
			}
			
			//print("Current Score: ".$currentscore."<br>");
			//print("Previous Score: ".$prevscore."<br>");
			
			//print("-------------<br>");
			$display[$count]	= "title1=".$deptval["name"]."&score=".$currentscore."&difference=".$difference."&sign=".$direction;
			$count++;
			$currentoverall	+= $currentscore;
			$prevoverall		+= $prevscore;
		}
	// }
	
	/*
	$currentoverall	= round($currentoverall / 4, 2);
	$prevoverall		= round($prevoverall / 4, 2);
	$difference			= round($currentoverall - $prevoverall, 2);
	$direction			= "";
	
	if($currentoverall > $prevoverall) {
		$difference	= round($currentoverall - $prevoverall, 2);
		$direction	= "up";
	} else if($currentoverall < $prevoverall) {
		$difference	= round($prevoverall - $currentoverall, 2);
		$direction	= "down";
	} else {
		$direction	= 0;
		$direction	= "unchanged";
	}
	$overalldisplay	= "score=".$currentoverall."&difference=".$difference."&sign=".$direction;
	*/
	
	print("<table height=100% width=100% cellpadding=0 cellspacing=0 border=0>");
	
	// Row 1, Header {
		print("<tr><td align='center'>");
		print("<embed src='".BASE."/images/Heading.swf'
			FlashVars='heading=SQM'
			quality='high'
			width='".(1300 * $factor)."px'
			height='".(85 * $factor)."px'
			name='header'
			wmode='transparent'
			allowScriptAccess='sameDomain'
			allowFullScreen='false'
			type='application/x-shockwave-flash'
			pluginspage='http://www.macromedia.com/go/getflashplayer' />");
		print("</td></tr>");
	// }
	
	// Row 2, Trend images {
		print("<tr><td align='center'>");
		print("<table cellpadding=0 cellspacing=0 height=100% width=100%>");
		
		print("<tr><td align='center'>");
		print("<embed src='".BASE."/images/sqmLeft.swf'
			FlashVars='".$display[0]."'
			quality='high'
			width='".(450 * $factor)."px'
			height='".(280 * $factor)."px'
			name='sqm1'
			wmode='transparent'
			allowScriptAccess='sameDomain'
			allowFullScreen='false'
			type='application/x-shockwave-flash'
			pluginspage='http://www.macromedia.com/go/getflashplayer' />");
		print("</td>");
		
		print("<td rowspan=2>");
		print("<embed src='".BASE."/images/sqmCentre.swf'
			FlashVars='".$display[4]."'
			quality='high'
			width='".(320 * $factor)."px'
			height='".(600 * $factor)."px'
			name='sqm1'
			wmode='transparent'
			allowScriptAccess='sameDomain'
			allowFullScreen='false'
			type='application/x-shockwave-flash'
			pluginspage='http://www.macromedia.com/go/getflashplayer' />");
		print("</td>");
		
		print("<td align='center'>");
		print("<embed src='".BASE."/images/sqmRight.swf'
			FlashVars='".$display[1]."'
			quality='high'
			width='".(450 * $factor)."px'
			height='".(280 * $factor)."px'
			name='sqm1'
			wmode='transparent'
			allowScriptAccess='sameDomain'
			allowFullScreen='false'
			type='application/x-shockwave-flash'
			pluginspage='http://www.macromedia.com/go/getflashplayer' />");
		print("</td></tr>");
		
		print("<tr><td align='center'>");
		print("<embed src='".BASE."/images/sqmLeft.swf'
			FlashVars='".$display[2]."'
			quality='high'
			width='".(450 * $factor)."px'
			height='".(280 * $factor)."px'
			name='sqm1'
			wmode='transparent'
			allowScriptAccess='sameDomain'
			allowFullScreen='false'
			type='application/x-shockwave-flash'
			pluginspage='http://www.macromedia.com/go/getflashplayer' />");
		print("</td>");
		
		print("<td align='center'>");
		print("<embed src='".BASE."/images/sqmRight.swf'
			FlashVars='".$display[3]."'
			quality='high'
			width='".(450 * $factor)."px'
			height='".(280 * $factor)."px'
			name='sqm1'
			wmode='transparent'
			allowScriptAccess='sameDomain'
			allowFullScreen='false'
			type='application/x-shockwave-flash'
			pluginspage='http://www.macromedia.com/go/getflashplayer' />");
		print("</td></tr>");
		
		print("</table>");
		print("</td></tr>");
	// }
	
	print("</table>");
?>
