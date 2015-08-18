<?PHP
	// Prep {
		// Groundwork {
			$conf		= $_POST;
			$count	= $conf["count"];
			
			$fleets = array(
				array("id"=>28, "name"=>"Long Distance", "budget"=>408104, "top"=>500000, "budkms"=>41058, "pubhol"=>1),
				array("id"=>51, "name"=>"LWT Fleet", "budget"=>68016, "top"=>80000, "budkms"=>5760, "pubhol"=>0),
				array("id"=>54, "name"=>"XB - Links", "budget"=>305745, "top"=>350000, "budkms"=>23375, "pubhol"=>1),
				array("id"=>35, "name"=>"XB - Triaxles", "budget"=>24152, "top"=>30000, "budkms"=>1872, "pubhol"=>1),
				array("id"=>47, "name"=>"Isando - Reclam Triaxles", "budget"=>83785, "top"=>100000, "budkms"=>5046, "pubhol"=>1),
				array("id"=>29, "name"=>"Entire Active Fleet", "budget"=>1166113, "top"=>1400000, "budkms"=>96225, "pubhol"=>1),
				array("id"=>50, "name"=>"Energy - Flat Decks", "budget"=>65553, "top"=>80000, "budkms"=>4731, "pubhol"=>1),
				array("id"=>32, "name"=>"Energy - Tankers", "budget"=>40985, "top"=>50000, "budkms"=>3152, "pubhol"=>1),
				array("id"=>42, "name"=>"Buckman - Total Fleet", "budget"=>44551, "top"=>50000, "budkms"=>3124, "pubhol"=>1),
				array("id"=>53, "name"=>"Energy - VDBL Tankers", "budget"=>117358, "top"=>150000, "budkms"=>7213, "pubhol"=>0)
				//array("id"=>29, "name"=>"Entire Active Fleet", "budget"=>1218352, "top"=>1400000, "budkms"=>96225, "pubhol"=>1)
				);
			
			$pubholidays	= array(2=>2, 5=>5, 27=>27);
			
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
			
			require_once(BASE."basefunctions/baseapis/FileParser/FileParser.php");
			
			$link					= mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
			$db_selected	= mysql_select_db(DB_SCHEMA, $link);
		// }
		
		// API call on Mobilize (1 day) {
			// Create date strings for query {
				$startday			= date("d"); 
				$startmonth		= date("m");
				$startyear		= date("Y");
				
				$startstring	= $startyear."-".$startmonth."-".$startday;
				
				$stopdate		= mktime(0, 0, 0, $startmonth, ($startday + 1), $startyear);
				$stopday		= date("d", $stopdate);
				$stopmonth	= date("m", $stopdate);
				$stopyear		= date("Y", $stopdate);
				
				$stopstring	= $stopyear."-".$stopmonth."-".$stopday;
			// }
			
			$url = (string)"http://login.max.manline.co.za/m4/2/api_request/";
			$url .= "Report/export?report=84&format=csv&Start_Date=".$startstring."&Stop_Date=".$stopstring."&Fleet=".$fleets[$count]["id"];
			//$url = (string)"http://max.mobilize.biz/m4/2/api_request/";
			//$url .= "Report/export?report=87&format=csv&Start_Date=".$startstring."&Stop_Date=".$stopstring."&Fleet=".$fleets[$count]["id"];
			
			$fileParser = new FileParser($url);
			
			$data = $fileParser->parseFile();
			if ($data === false) {
				print("<pre style='font-family:verdana;font-size:13'>");
				print_r($fileParser->getErrors());
				print("</pre>");
				return;
				
				print("<pre style='font-family:verdana;font-size:13'>errors");
				print_r($fileParser->getErrors());
				print("</pre>");
				
				print("<br>");
			}
		// }
		
		// API call on Mobilize (Month to date) {
			// Create date strings for query {
				$startday			= "01";
				$startmonth		= date("m");
				$startyear		= date("Y");
				
				$startstring	= $startyear."-".$startmonth."-".$startday;
				
				//$stopdate		= mktime(0, 0, 0, ($startmonth+1), 0, $startyear);
				$stopdate		= mktime(0, 0, 0, $startmonth, ((date("d")) + 1), $startyear);
				
				$stopday		= date("d", $stopdate);
				$stopmonth	= date("m", $stopdate);
				$stopyear		= date("Y", $stopdate);
				
				$stopstring	= $stopyear."-".$stopmonth."-".$stopday;
			// }
			
			$url = (string)"http://login.max.manline.co.za/m4/2/api_request/";
			$url .= "Report/export?report=84&format=csv&Start_Date=".$startstring."&Stop_Date=".$stopstring."&Fleet=".$fleets[$count]["id"];
			//$url = (string)"http://max.mobilize.biz/m4/2/api_request/";
			//$url .= "Report/export?report=87&format=csv&Start_Date=".$startstring."&Stop_Date=".$stopstring."&Fleet=".$fleets[$count]["id"];
			
			$fileParser = new FileParser($url);
			
			$mtddata = $fileParser->parseFile();
			if ($mtddata === false) {
				print("<pre style='font-family:verdana;font-size:13'>");
				print_r($fileParser->getErrors());
				print("</pre>");
				return;
				
				print("<pre style='font-family:verdana;font-size:13'>errors");
				print_r($fileParser->getErrors());
				print("</pre>");
				
				print("<br>");
			}
		// }
		
		// Budgets {
			$budurl = (string)"http://login.max.manline.co.za/m4/2/api_request/";
			$budurl .= "Report/export?report=85&format=csv&Start_Date=".$startstring."&Stop_Date=".$stopstring."&Fleet=".$fleets[$count]["id"];
			
			$fileParser = new FileParser($budurl);
			
			$buddata = $fileParser->parseFile();
			if ($buddata === false) {
				print("<pre style='font-family:verdana;font-size:13'>");
				print_r($fileParser->getErrors());
				print("</pre>");
				return;
				
				print("<pre style='font-family:verdana;font-size:13'>errors");
				print_r($fileParser->getErrors());
				print("</pre>");
				
				print("<br>");
			}
		// }
				
		// Datawork {
			// Sort functions {
				function cmpCargoId($a, $b) {
					if ($a["Cargo Id"] == $b["Cargo Id"]) {
						return 0;
					}
					return ($a["Cargo Id"] < $b["Cargo Id"]) ? -1 : 1;
				}
				
				function cmpDates($a, $b) {
					if ($a["Date"] == $b["Date"]) {
						return 0;
					}
					return ($a["Date"] < $b["Date"]) ? -1 : 1;
				}
				
				function cmpLoadingDates($a, $b) {
					if($a["Loading Arrival"]=="(none)") {
						$timea	= strtotime($a["Loading ETA"]);
					} else {
						$timea	= strtotime($a["Loading Arrival"]);
					}
					if($b["Loading Arrival"]=="(none)") {
						$timeb	= strtotime($b["Loading ETA"]);
					} else {
						$timeb	= strtotime($b["Loading Arrival"]);
					}
					if ($timea == $timeb) {
						return 0;
					}
					return ($timea < $timeb) ? -1 : 1;
				}
			// }
			
			// Budget Calcs {
				$watchdate	= 1;
				usort($buddata, "cmpDates");
				
				$totbudget2 = 0;
				foreach ($buddata as $budkey=>$budval) {
					$budget		= str_replace(",", "", $budval["Income"]);
					$totbudget2	+= $budget;
					
					if ($buddata[($budkey+1)]["Date"] == null) {
						$nextdate	= $stopday;
					} else {
						$nextdate	= substr($buddata[($budkey+1)]["Date"], 8, 2);
					}
					
					while ($watchdate < $nextdate) {
						$budgets[$watchdate]["income"] = $totbudget2;
						$watchdate++;
					}
				}
			// }
			
			// Month to Date Calcs {
				usort($mtddata, "cmpLoadingDates");
				
				$dayincome	= 0;
				$totincome	= 0;
				$totbudget	= 0;
				$totleft		= 0;
				$watchdate	= 1;
				$tripdate		= 0;
				$str				= "";
				$daycount		= 0;
				$totkms			= 0;
				$totcpk			= 0;
				
				foreach ($mtddata as $mtdkey=>$mtdval) {
					$income		= str_replace(",", "", $mtdval["Tripleg Income"]);
					$distance	= 0;
					if($income > 0) {
						if($mtdval["Loading Arrival"] == "(none)") {
							$tripdate	= substr($mtdval["Loading ETA"], 8, 2);
						} else {
							$tripdate	= substr($mtdval["Loading Arrival"], 8, 2);
						}
						
						if($mtddata[($mtdkey+1)]["Loading Arrival"] == "(none)") {
							$nextdate	= substr($mtddata[($mtdkey+1)]["Loading ETA"], 8, 2);
						} else if ($mtddata[($mtdkey+1)]["Loading Arrival"] == null) {
							$nextdate	= $stopday;
						} else {
							$nextdate	= substr($mtddata[($mtdkey+1)]["Loading Arrival"], 8, 2);
						}
						
						$dayincome	+= $income;
						$daykms			+= $mtdval["Total Kms"];
						
						while ($watchdate < $nextdate) {
							$daycount++;
							$totincome	+= $dayincome;
							
							$weekday		= date("w", mktime(0,0,1,$startmonth,$watchdate,$startyear));
							if(($weekday == 6) || ($weekday == 0)) {
								$totbudget	+= ($fleets[$count]["budget"] / 2);
							} else if(($fleets[$count]["pubhol"] == 1) && ($pubholidays[$watchdate])) {
								$totbudget	+= 0; // This fleet is not budgetted to make income on Public holidays
							} else {
								$totbudget	+= $fleets[$count]["budget"];
							}
							
							$str	= $str."&ginput".$daycount."=".$totincome;
							$str	= $str."&tinput".$daycount."=".$budgets[$watchdate]["income"];
							
							if($daykms > 0) {
								$daycpk = round(($dayincome / $daykms), 2);
							} else {
								$daycpk = 0;
							}
							
							$totkms	+= $daykms;
							
							$dayincome	= 0;
							$daykms			= 0;
							$watchdate++;
						}
					}
				}
			// }
			
			if($totkms > 0) {
				$totcpk	= round(($totincome / $totkms), 2);
			} else {
				$totcpk	= 0;
			}
			
			// Calculate latest day income {
				$dayincome	= 0;
				$distance		= 0;
				$daybudget	= $fleets[$count]["budget"];
				foreach ($data as $datkey=>$datval) {
					$income	= str_replace(",", "", $datval["Tripleg Income"]);
					$dayincome	+= $income;
					
					$distance		+= $datval["Total Kms"];
				}
				
				$dayincome	= round($dayincome, 0);
				if($distance > 0) {
					$daycpk	= round(($dayincome / $distance), 2);
				} else {
					$daycpk	= 0;
				}
				
				$budgetcpk	= round(($fleets[$count]["budget"] / $fleets[$count]["budkms"]), 2);
				$variance		= $totincome - $totbudget;
			// }
		// }
	// }
	
	print("<table width=100% cellpadding=0 cellspacing=0>");
	
	print("<tr><td align=center valign=top width=15% rowspan=2>");
	print("<embed src='".BASE."/images/Slider.swf'
		FlashVars='min=0&max=".$fleets[$count]["top"]."&slide=".$dayincome."&budget=".$fleets[$count]["budget"]."'
		quality='high'
		width='330px'
		height='1050px'
		name='slider'
		wmode='transparent'
		allowScriptAccess='sameDomain'
		allowFullScreen='false'
		type='application/x-shockwave-flash'
		pluginspage='http://www.macromedia.com/go/getflashplayer' />");
	print("</td>");
	
	print("<td align='center' width=70%>");
	
	print("<embed src='".BASE."/images/Heading.swf'
		FlashVars='fleet_name=".$fleets[$count]["name"]."&budget=".(number_format($totbudget))."&income=".(number_format($totincome))."&variance=".number_format($variance)."'
		quality='high'
		width='1213px'
		height='344px'
		name='number'
		wmode='transparent'
		allowScriptAccess='sameDomain'
		allowFullScreen='false'
		type='application/x-shockwave-flash'
		pluginspage='http://www.macromedia.com/go/getflashplayer' /");
	
	//print("<font style='color: WHITE; font-family: trebuchet; font-size:22; height:18; font-weight: bold'>".$count." "..", budget : ".$fleets[$count]["budget"]."</font>");
	print("</td>");
	/*
	print("<embed src='".BASE."/images/Number.swf'
		FlashVars='blackouts=5'
		quality='high'
		width='412px'
		height='362px'
		name='number'
		wmode='transparent'
		allowScriptAccess='sameDomain'
		allowFullScreen='false'
		type='application/x-shockwave-flash'
		pluginspage='http://www.macromedia.com/go/getflashplayer' /");
	print("</td>");
	*/
	
	print("<td rowspan=2>");
	print("<embed src='".BASE."/images/CpkSlider.swf'
		FlashVars='min=8&max=18&cpk_month=".$totcpk."&cpk_day=".$daycpk."&cpk_target=".$budgetcpk."'
		quality='high'
		width='300px'
		height=1000px'
		name='graph'
		wmode='transparent'
		allowScriptAccess='sameDomain'
		allowFullScreen='false'
		type='application/x-shockwave-flash'
		pluginspage='http://www.macromedia.com/go/getflashplayer' />");
	print("</td></tr>");
	
	print("<tr><td");
	
	print("<embed src='".BASE."/images/GraphContrib.swf'
		FlashVars='count=".$daycount.$str."'
		quality='high'
		width='1250px'
		height=630px'
		name='graph'
		wmode='transparent'
		allowScriptAccess='sameDomain'
		allowFullScreen='false'
		type='application/x-shockwave-flash'
		pluginspage='http://www.macromedia.com/go/getflashplayer' />");
	
	print("</td></tr>");
	
	print("</table>");
?>
