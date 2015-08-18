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
				
				$fleetdayobj	= new fleetDayHandler();
			// }
			/** returnFleetTruckCount()
			 * get all fleets truck count
			 * @return array on success false otherwise
			 */
			function returnFleetTruckCount() {
				require_once(BASE."/basefunctions/baseapis/TableManager.php");
				$manager = new TableManager("fleet_truck_count");
				$manager->setCustomIndex("fleet_id");
				return $manager->selectMultiple();
			}
			$truckcount = returnFleetTruckCount();
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
	//: Display
	print("<div style=\"margin:5px auto 0;width:".(round((230 * $factor), 0)+round((250 * $factor), 0)+round((850 * $factor), 0))."px;\">");
	//: Left column
	print("<div style=\"float:left;width:".round((250 * $factor), 0)."px;\">");
	//: Cpk Slider
	embedObject("/images/Comp_Slider.swf", array(
			"FlashVars"=>"min=0&max=".$slidertop."&slide=".round($fleetdetails[$today]["contrib"], 0)."&budget=".round($fleetdetails[$today]["budgetcontrib"], 0)."&graph_title=Daily Contrib",
			"height"=>round((750 * $factor), 0),
			"name"=>'Comp_Slider',
			"width"=>round((250 * $factor), 0)
	));
	//: End
	print("</div>");
	//: End
	//: Middle column
	print("<div style=\"float:left;width:".(round(850 * $factor, 0))."px;\">");
	//: Heading
	embedObject("/images/heading.swf", array(
			"FlashVars"=>"graph_type=CONTRIBUTION&fleet_name=".$fleetlist[$count]["name"]."&budget=".(number_format($totbudget))."&income=".(number_format($totcontrib))."&variance=".number_format($variance),
			"height"=>round((225 * $factor), 0),
			"name"=>'HeadingContrib',
			"width"=>round((850 * $factor), 0)
	));
	//: End
	//: Cricket Graph
	embedObject("/images/graph_contrib.swf", array(
			"FlashVars"=>"count=".(date("j")).$flashstring,
			"height"=>round((357 * $factor), 0),
			"name"=>'GraphContrib',
			"width"=>round((850 * $factor), 0)
	));
	//: End
	//: Kms details
	if (date(d) < 10) {
		if (isset($fleetdetails[substr(date("d"), 1)]) && $fleetdetails[substr(date("d"), 1)]) {
			$kms = ($fleetdetails[substr(date("d"), 1)]["kms"] ? round(($fleetdetails[substr(date("d"), 1)]["kms"]/$truckcount[$fleet]["count"]),0) : 0);
			$budkms = ($fleetdetails[substr(date("d"), 1)]["budkms"] ? round(($fleetdetails[substr(date("d"), 1)]["budkms"]/$truckcount[$fleet]["count"]),0) : 0);
		}
	} else {
		if (isset($fleetdetails[date("d")]) && $fleetdetails[date("d")]) {
			$kms = ($fleetdetails[date("d")]["kms"] ? round(($fleetdetails[date("d")]["kms"]/$truckcount[$fleet]["count"]),0) : 0);
			$budkms = ($fleetdetails[date("d")]["budkms"] ? round(($fleetdetails[date("d")]["budkms"]/$truckcount[$fleet]["count"]),0) : 0);
		}		
	}
	$days = (int)0;
	$totkms = (float)0;
	$totbudkms = (float)0;
	$public_holidays = (array)array();
	foreach ($fleetdetails as $day=>$value) {
		$totkms += isset($value["kms"]) ? $value["kms"] : 0;
		if (isset($value["budkms"]) && $value["budkms"]) {
			$days += 1;
			$totbudkms += isset($value["budkms"]) ? $value["budkms"] : 0;
		} else {
			$days += 0;
		}
	}
	$vars = (string)"cd_actual=".(isset($kms) ? $kms : 0);
	$vars .= "&cd_budget=".(isset($budkms) ? $budkms : 0);
	$vars .= "&cd_variance=".(isset($kms) && isset($budkms) ? round(($kms-$budkms),0) : "");
	$vars .= "&cd_percent=".(isset($kms) && isset($budkms) ? round(($kms/$budkms)*100,0) : "");
	$vars .= "&mtd_actual=".round(($totkms/$days)/$truckcount[$fleet]["count"],0);
	$vars .= "&mtd_budget=".round(($totbudkms/$days)/$truckcount[$fleet]["count"],0);
	$vars .= "&mtd_variance=".round(($totkms/$days)/$truckcount[$fleet]["count"]-(($totbudkms/$days)/$truckcount[$fleet]["count"]),0);
	$vars .= "&mtd_percent=".round((((($totkms/$days)/$truckcount[$fleet]["count"])/(($totbudkms/$days)/$truckcount[$fleet]["count"]))*100),0);
	$vars .= "&mef_actual=".round((($totkms/$days)/$truckcount[$fleet]["count"])*26, 0);
	$vars .= "&mef_budget=".round((($totbudkms/$days)/$truckcount[$fleet]["count"])*26, 0);
	$vars .= "&mef_variance=".round(((($totkms/$days)/$truckcount[$fleet]["count"])*26)-((($totbudkms/$days)/$truckcount[$fleet]["count"])*26), 0);
	$vars .= "&mef_percent=".round((((($totkms/$days)/$truckcount[$fleet]["count"])*26)/((($totbudkms/$days)/$truckcount[$fleet]["count"])*26))*100, 0);
	embedObject("/images/kms_table.swf", array(
			"FlashVars"=>$vars,
			"height"=>round((164*$factor), 0),
			"name"=>"kms_table",
			"width"=>round((850*$factor), 0)
	));
	//: End
	print("</div>");
	//: End
	//: Right column
	print("<div style=\"float:left;width:".round((230 * $factor), 0)."px;\">");
	//: Cpk slider 2
	embedObject("/images/CpkSlider.swf", array(
			"FlashVars"=>"min=1&max=7&cpk_month=".$totcpk."&cpk_day=".$daycpk."&cpk_target=".$budgetcpk,
			"height"=>round((750 * $factor), 0),
			"name"=>'CpkSlider',
			"width"=>round((230 * $factor), 0)
	));
	//: End
	print("</div>");
	//: End
	print("</div>");
	//: End
