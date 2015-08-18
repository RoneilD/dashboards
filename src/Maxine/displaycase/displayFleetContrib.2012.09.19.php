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
				
				$fleetdayobj	= new fleetDayHandler;
			// }
		// }
		
		$count		= $conf["fleetcount"];
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
		
		//$count	= 2;
		
		$fleet		= $fleetdayobj->getFleetId($count);
		
		$today	= date("j");
		
		if($fleet) {
			$fleetdetails	= $fleetdayobj->getFleetScoreMonth($fleet);
		}
		
		$fleetlist		= $fleetdayobj->getIncomeFleets();
		
		$totcontrib		= 0;
		$totbudget		= 0;
		$totkms				= 0;
		
		$flashstring	= "";
		
		if($fleetdetails) {
			foreach ($fleetdetails as $fleetdaykey=>$fleetday) {
				$totcontrib		+= $fleetday["contrib"];
				$totbudget		+= $fleetday["budgetcontrib"];
				$totkms				+= $fleetday["kms"];
				
				$flashstring	.= "&ginput".$fleetdaykey."=".$totcontrib;
				$flashstring	.= "&tinput".$fleetdaykey."=".$totbudget;
			}
		}
		
		$todaybudget	=	$fleetdetails[$today]["budgetcontrib"];
		$variance			= $totcontrib - $totbudget;
		if($totkms > 0) {
			$totcpk	= round($totcontrib / $totkms, 2);
		} else {
			$totcpk	= 0;
		}
		if($fleetdetails[$today]["kms"] > 0) {
			$daycpk	= round($fleetdetails[$today]["contrib"] / $fleetdetails[$today]["kms"], 2);
		} else {
			$daycpk	= 0;
		}
		if($fleetdetails[$today]["budkms"] > 0) {
			$budgetcpk	= round($fleetdetails[$today]["budgetcontrib"] / $fleetdetails[$today]["budkms"], 2);
		} else {
			$budgetcpk	= 0;
		}
		
		$slidertop		= $fleetdayobj->calcSliderTop($todaybudget);
	// }
	
	print("<table width=100% cellpadding=0 cellspacing=0>");
	print("<tr><td align=center width=15% rowspan=2>");
	
	print("<embed src='".BASE."/images/Comp_Slider.swf'
		FlashVars='min=0&max=".$slidertop."&slide=".round($fleetdetails[$today]["contrib"], 0)."&budget=".round($fleetdetails[$today]["budgetcontrib"], 0)."&graph_title=Daily Contrib'
		quality='high'
		width='".(250 * $factor)."px'
		height='".(750 * $factor)."px'
		name='slider'
		wmode='transparent'
		allowScriptAccess='sameDomain'
		allowFullScreen='false'
		type='application/x-shockwave-flash'
		pluginspage='http://www.macromedia.com/go/getflashplayer' />");
	
	print("</td><td align='center' width=70%>");
	
	print("<embed src='".BASE."/images/HeadingContrib.swf'
		FlashVars='fleet_name=".$fleetlist[$count]["name"]."&budget=".(number_format($totbudget))."&income=".(number_format($totcontrib))."&variance=".number_format($variance)."'
		quality='high'
		width='".(750 * $factor)."px'
		height='".(304 * $factor)."px'
		name='number'
		wmode='transparent'
		allowScriptAccess='sameDomain'
		allowFullScreen='false'
		type='application/x-shockwave-flash'
		pluginspage='http://www.macromedia.com/go/getflashplayer' /");
	print("</td><td rowspan=2>");
	print("<embed src='".BASE."/images/CpkSlider.swf'
		FlashVars='min=1&max=7&cpk_month=".$totcpk."&cpk_day=".$daycpk."&cpk_target=".$budgetcpk."'
		quality='high'
		width='".(230 * $factor)."px'
		height='".(750 * $factor)."px'
		name='graph'
		wmode='transparent'
		allowScriptAccess='sameDomain'
		allowFullScreen='false'
		type='application/x-shockwave-flash'
		pluginspage='http://www.macromedia.com/go/getflashplayer' />");
	
	print("</td></tr>");
	print("<tr><td align='center'>");
	/* print("<pre>");
	print_r($flashstring);
	print("</pre>"); */
	print("<embed src='".BASE."/images/GraphContrib.swf'
		FlashVars='count=".(date("j")).$flashstring."'
		quality='high'
		width='".(round(850 * $factor, 0))."px'
		height='".(round(415 * $factor, 0))."px'
		name='graph'
		wmode='transparent'
		allowScriptAccess='sameDomain'
		allowFullScreen='false'
		type='application/x-shockwave-flash'
		pluginspage='http://www.macromedia.com/go/getflashplayer' />");
	
	print("</td></tr>");
	print("</table>");
?>
