<?PHP
	function displayMainDash() {
		
		//HTML
		include("./displaycase/content/main.php");
		
	}
	
	function displayContribDash() {
		
		//HTML
		include("./displaycase/content/contribution.php");
	
	}
	
	function displayGreenmileDash() {
		print("<!DOCTYPE html><html><body style='background-image:url(".BASE."/images/background.png);background-repeat:repeat;margin:0px; padding:0px;'>");
		print("<table style='width:100%; height:100%; cursor: none;' cellspacing=0 cellpadding=0 border=0>");
		
		print("<tr><td id='canvasstd' align='center' valign='top'>");
		print("<font color=WHITE>TEST</font>");
		print("</td></tr>");
		
		print("</table>");
		print("</body>");
		
		// Javascript {
			print("<script>
				var cyclecount	= 0;
				var fleetcount	= 0;
				var screenwidth	= screen.width;
				var interval1	= setInterval('ajaxTicker()', 30000);
				ajaxTicker();
				
				function ajaxTicker() {
					var ajaxRequest;  // The variable that makes Ajax possible!
					
					// Rip the records variables into a string for Posting {
						var params	= '&maxwidth='+screenwidth;
					// }
					
					try {
						// Opera 8.0+, Firefox, Safari
						ajaxRequest = new XMLHttpRequest();
					} catch (e) {
						// Internet Explorer Browsers
						try {
							ajaxRequest = new ActiveXObject('Msxml2.XMLHTTP');
						} catch (e) {
							try{
								ajaxRequest = new ActiveXObject('Microsoft.XMLHTTP');
							} catch (e){
								// Something went wrong
								alert('Your browser broke!');
								return false;
							}
						}
					}
					
					// Create a function that will receive data sent from the server
					ajaxRequest.onreadystatechange = function(){
						if(ajaxRequest.readyState == 4){
							var response	= ajaxRequest.responseText;
							document.getElementById('canvasstd').innerHTML = response;
							response=null;
						}
					}
					
					ajaxRequest.open('POST', './displaycase/displayGreenmile.php', true);
					
					//Send the proper header information along with the request
					ajaxRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
					ajaxRequest.setRequestHeader('Content-length', params.length);
					ajaxRequest.setRequestHeader('Connection', 'close');
					
					ajaxRequest.send(params);
					params=null;
				}
				window.onunload = function() {
					cyclecount=null;
					fleetcount=null;
					screenwidth=null;
					window.clearInterval(interval1);
					interval1=null;
				};
			</script></html>");
		// }
	}
	
	function displayBlackoutDash() {
		
		//HTML
		include("./displaycase/content/blackouts.php");
	
		// }
	}
	
	function displayNoDash() {
		print("<!DOCTYPE html><html><body style='padding:0px; background-color:BLACK;'>");
		
		print("<div style='width:747px;height:343px;position:absolute;left:50%;top:50%;margin-left:-374px;margin-top:-172px;'>");
		print("<img src='".DISPLAYCASE."/dashoffline.png'>");
		print("</div>");
		
		print("</body></html>");
	}
	
	function displayFleetPositionsDash() {
		print("<!DOCTYPE html><html><body style='background-image:url(".BASE."/images/background.png);background-repeat:repeat;margin:0px; padding:0px;'>");
		print("<table style='width:100%; height:100%; cursor: none;' cellspacing=0 cellpadding=0 border=0>");
		
		print("<tr><td id='canvasstd' align='center' valign='top'>");
		print("<font color=WHITE>Fleet Positions</font>");
		print("</td></tr>");
		
		print("</table>");
		print("</body>");
		
		
		// Javascript {
			print("<script>
				var cyclecount	= 0;
				var fleetcount	= 0;
				var screenwidth	= screen.width;
				var interval1	= setInterval('ajaxTicker()', 30000);
				ajaxTicker();
				
				function ajaxTicker() {
					var ajaxRequest;  // The variable that makes Ajax possible!
					
					// Rip the records variables into a string for Posting {
						var params	= '&maxwidth='+screenwidth;
					// }
					
					try {
						// Opera 8.0+, Firefox, Safari
						ajaxRequest = new XMLHttpRequest();
					} catch (e) {
						// Internet Explorer Browsers
						try {
							ajaxRequest = new ActiveXObject('Msxml2.XMLHTTP');
						} catch (e) {
							try{
								ajaxRequest = new ActiveXObject('Microsoft.XMLHTTP');
							} catch (e){
								// Something went wrong
								alert('Your browser broke!');
								return false;
							}
						}
					}
					
					// Create a function that will receive data sent from the server
					ajaxRequest.onreadystatechange = function(){
						if(ajaxRequest.readyState == 4){
							var response	= ajaxRequest.responseText;
							
							document.getElementById('canvasstd').innerHTML = response;
							response=null;
						}
					}
					
					ajaxRequest.open('POST', './displaycase/displayFleetPositions.php', true);
					
					//Send the proper header information along with the request
					ajaxRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
					ajaxRequest.setRequestHeader('Content-length', params.length);
					ajaxRequest.setRequestHeader('Connection', 'close');
					
					ajaxRequest.send(params);
					params=null;
				}
				window.onunload = function() {
					cyclecount=null;
					fleetcount=null;
					screenwidth=null;
					window.clearInterval(interval1);
					interval1=null;
				};
			</script></html>");
		// }
	}
	
	function displayMyDash() {
		// Prep {
			$mydashboard	= sqlPull(array("table"=>"user_dashboards", "where"=>"userid=".$_SESSION["userid"], "onerow"=>1));
			
			if($mydashboard) {
				$pattern			= explode(";", $mydashboard["pattern"]);
				$duration			= $mydashboard["duration"];
				
				$patterncount	= count($pattern);
				$scriptstr		= "var patternscript=new Array(".$patterncount.")\n";
				$count			= 0;
				foreach ($pattern as $patternkey=>$patternval) {
					$scriptstr	.= "patternscript[".$count."]=".$patternval.";";
					$count++;
				}
			} else {
				$duration		= 30;
				$patterncount	= 0;
				$scriptstr		= "var patternscript=new Array(1)\n";
				$scriptstr		.= "patternscript[0]=0;";
			}
		// }
		
		//HTML
		include("./displaycase/content/personal.php");
		
	}
	
	// Personal Dash functions {
		function myDashDetails() {
			// Prep {
				## User Data
				$users = new TableManager("users");
				$users->setWhere(
						$users->quoteString("`users`.`personid`=?", (int)$_SESSION["userid"])
				);
				$user = $users->selectSingle();
				$fleetdayobj	= new fleetDayHandler;
				//: Sorting
				$sorted = (array)array();
				$fleetlist = (array)array();
				$tmp		= $fleetdayobj->getIncomeFleets();
				foreach ($tmp as $key=>$val) {
					$sorted[$val['id']] = $val["name"];
				}
				asort($sorted, SORT_STRING);
				foreach ($sorted as $key=>$val) {
					$fleetlist[$key] = $sorted[$key];
				}
				unset($sorted);
				unset($tmp);
				//: End Sorting
				
				$userdash = sqlPull(array("table"=>"user_dashboards", "where"=>"userid=".$_SESSION["userid"], "onerow"=>1));
				$pattern = explode(";", $userdash["pattern"]);
				
				//get the list of fleets
				$fleets_data = $fleetdayobj->getIncomeFleets();
				$fleet_groups = array();
				
				//loop through fleets and set a new array with fleet groups only
				foreach($fleets_data as $k => $fleet){
					$group_name = (isset($fleet["structure"][1])) ? $fleet["structure"][0]." - ".$fleet["structure"][1] : $fleet["structure"][0];
					$fleet_groups[] = $group_name;
					
				}
				
				//sort the fleet groups alphabetically
				sort($fleet_groups);
				
				//remove any duplicates
				$fleet_groups = array_values(array_unique($fleet_groups));
				
				//HTML				
				include("./displaycase/content/builder.php");
			// }
		}
		
		function updateMyDashDetails() {
			// print_r($_POST);exit;
			$conf			= $_POST["conf"];
			$pattern	= sqlPull(array("table"=>"user_dashboards", "where"=>"userid=".$_SESSION["userid"]));
			$orderstr	= "";
			$count		= 0;
			foreach ($conf["pattern"] as $stepkey=>$stepval) {
				if($stepval["status"] > 0) {
					if($count > 0) {
						$orderstr	.= ";";
					}
					
					$orderstr	.= $stepval["fleetid"];
					$count++;
				}
			}
			$data["userid"]		= $_SESSION["userid"];
			$data["pattern"]	= $orderstr;
			$data["duration"]	= $conf["duration"];
			
			
			if($pattern) {
				commitMyDashboard($data);
			} else {
				createMyDashboard($data);
			}
			
			goHere("/?personal");
			
			
		}
	//
	
	/*
	 * Save function for creating custom slides
	 *
	 */
	function saveCustomSlide(){
		$conf		 = $_POST["conf"];
		
		$fleetdayobj = new fleetDayHandler;
		if ($conf){
			if(empty($conf["slide_id"])){//create new slide

				$slider = (array)array(
					'slide_name'=>$conf['slide_name'],
					'fleet_ids'=>$conf['fleet_ids']
				);
				
				if($fleetdayobj->createSlider($slider, (array_key_exists('userid', $_SESSION) ? (int)$_SESSION['userid'] : (int)0))){
					goHere("/Maxine/?mydashdetails");
				}				
				
			}
			else{//update slide
				$slider = (array)array(
					'id'=>$conf['slide_id'],
					'slide_name'=>$conf['slide_name'],
					'fleet_ids'=>$conf['fleet_ids']
				);
				$fleetdayobj->updateSlider($slider);
				goHere("/Maxine/?mydashdetails");
			}
		}
	}
	
	function deleteCustomSlide(){
		$slide_id = $_POST["slide_id"];
		if($slide_id){
			$fleetdayobj = new fleetDayHandler;
			$fleetdayobj->deleteSlider($slide_id);
			goHere("/Maxine/?mydashdetails");
		}
	}
	
	/** getUserSliders()
	* Get a list of sliders saved by this user
	*/
	function getUserSliders()
	{
		sleep(1);
		$fleetdayobj = new fleetDayHandler;
		$results = $fleetdayobj->getUserSliders((int)$_SESSION['userid'], TRUE);
		if ($results === FALSE)
		{
			echo json_encode(array(0=>'Getting data failed'));
			return FALSE;
		}
		echo json_encode($results);
	}
	
	function fetchRightDays() {
		// Preparation {
			require_once(BASE."basefunctions/baseapis/FileParser/FileParser.php");
			if($_POST["conf"]) {
				$conf		= $_POST["conf"];
				
				// Build date strings for CURL pull {
					$startday			= substr($conf["startdate"], 0, 2);
					$startmonth		= substr($conf["startdate"], 3, 2);
					$startyear		= substr($conf["startdate"], 6, 4);
					
					$startstring	= $startyear."-".$startmonth."-".$startday;
					
					$stopday			= substr($conf["stopdate"], 0, 2);
					$stopmonth		= substr($conf["stopdate"], 3, 2);
					$stopyear			= substr($conf["stopdate"], 6, 4);
					
					$stopstring		= $stopyear."-".$stopmonth."-".$stopday;
				// }
				
				// Fetch the report and it's results {
					$reporturl = "http://login.max.manline.co.za/m4/2/api_request/Report/export?report=26&responseFormat=csv&Start_Date=".$startstring."&Stop_Date=".$stopstring."&numberOfRowsPerPage=10000";
					
					$fileParser = new FileParser($reporturl);
					$fileParser->setCurlFile("greenmiledays".(date("U")).".csv");
					$reportresults = $fileParser->parseFile();
					
					if ($reportresults === false) {
						print("<pre style='font-family:verdana;font-size:13'>");
						print_r($fileParser->getErrors());
						print("</pre>");
						return;
						
						print("<br>");
					}
				// }
				
				if($reportresults) {
					// Create and process the start of the csv file {
						$destination			= FIRSTBASE."/displaycase/greenmiledays/";
						$destname					= $startstring."-".$stopstring;
						$destname					= str_replace(".csv", "", $destname);
						$destname					.= ".csv";
						
						$desthandle				= fopen($destination.$destname, "w");
						
						foreach ($reportresults[1] as $header=>$discard) {
							fwrite($desthandle, $header.",");
						}
						fwrite($desthandle, "Load Diff,Offload Diff\r\n");
					// }
					
					foreach ($reportresults as $reskey=>$resval) {
						foreach ($resval as $column=>$value) {
							fwrite($desthandle, $value.",");
						}
						
						// Loading Difference {
							$loadplanned	= $resval["Planned Loading Arrival"];
							$loadactual		= $resval["Loading Arrival"];
							if($loadactual != "(none)") {
								$plannedday		= substr($loadplanned, 8, 2);
								$plannedmonth	= substr($loadplanned, 5, 2);
								$plannedyear	= substr($loadplanned, 0, 4);
								
								$actualday		= substr($loadactual, 8, 2);
								$actualmonth	= substr($loadactual, 5, 2);
								$actualyear		= substr($loadactual, 0, 4);
								
								if($plannedmonth < $actualmonth) {
									$monthdays	= date("t", mktime(0,0,0,$plannedmonth,$plannedday,$plannedyear));
									$loaddiff		= $actualday + $monthdays - $plannedday;
								} else if($plannedmonth > $actualmonth) {
									$monthdays	= date("t", mktime(0,0,0,$actualmonth,$actualday,$actualyear));
									$loaddiff		= $plannedday + $monthdays - $actualday;
								} else if($plannedday < $actualday) {
									$loaddiff		= $actualday - $plannedday;
								} else {
									$loaddiff		= $plannedday - $actualday;
								}
								
								$diffyear			= $plannedyear - $actualyear;
							} else {
								$loaddiff	= -1;
							}
							
							$reportresults[$reskey]["Load Diff"]	= $loaddiff;
						// }
						
						// Offoading Difference {
							$offloadplanned	= $resval["Planned Offloading Arrival"];
							$offloadactual		= $resval["Offloading Arrival"];
							if($offloadactual != "(none)") {
								$plannedday		= substr($offloadplanned, 8, 2);
								$plannedmonth	= substr($offloadplanned, 5, 2);
								$plannedyear	= substr($offloadplanned, 0, 4);
								
								$actualday		= substr($offloadactual, 8, 2);
								$actualmonth	= substr($offloadactual, 5, 2);
								$actualyear		= substr($offloadactual, 0, 4);
								
								if($plannedmonth < $actualmonth) {
									$monthdays	= date("t", mktime(0,0,0,$plannedmonth,$plannedday,$plannedyear));
									$offloaddiff		= $actualday + $monthdays - $plannedday;
								} else if($plannedmonth > $actualmonth) {
									$monthdays	= date("t", mktime(0,0,0,$actualmonth,$actualday,$actualyear));
									$offloaddiff		= $plannedday + $monthdays - $actualday;
								} else if($plannedday < $actualday) {
									$offloaddiff		= $actualday - $plannedday;
								} else {
									$offloaddiff		= $plannedday - $actualday;
								}
								
								$diffyear			= $plannedyear - $actualyear;
							} else {
								$offloaddiff	= -1;
							}
							
							$reportresults[$reskey]["Offload Diff"]	= $offloaddiff;
						// }
						
						fwrite($desthandle, $loaddiff.",".$offloaddiff."\r\n"); // Add details to csv
					}
				}
				
				fclose($desthandle); // close the csv
			}
		// }
		
		maxineTop("Header");
		print("<form name='dayreportform' action='index.php?mode=maxine/index&action=fetchrightdays' method='post'>");
		
		openHeader(1200);
		maxineButton("Submit", "dayreportform.submit();", 2);
		if($reportresults) {
			maxineButton("Download", "goTo(\"".BASE."/basefunctions/downloadcsv.php?filename=".$destname."&filepath=".$destination."\");", 2);
		}
		maxineButton("Back", "goTo(\"index.php?mode=maxine/index&action=firstmenu\");", 2);
		closeHeader();
		
		print("<div class='tray' style='width:1200px;'>");
		// Date Select {
			openSubbar(500);
			print("Date Select");
			closeSubbar();
			
			print("<div class='standard content1' style='width:500px;'>");
			
			print("<span style='width:50%; display:inline-block;'>");
			print("Start Date");
			print("<input name='conf[startdate]' id='startdate' value='".$conf["startdate"]."' readonly style='width: 110px; text-align: center;'>");
			print("<img src='".BASE."/images/calendar.png' onClick='displayDatePicker(\"conf[startdate]\", this, \"dmy\", \"\");' />");
			print("</span>");
			
			print("<span style='width:50%; display:inline-block;'>");
			print("Stop Date");
			print("<input name='conf[stopdate]' id='stopdate' value='".$conf["stopdate"]."' readonly style='width: 110px; text-align: center;'>");
			print("<img src='".BASE."/images/calendar.png' onClick='displayDatePicker(\"conf[stopdate]\", this, \"dmy\", \"\");' />");
			print("</span>");
			
			print("</div>");
		// }
		
		if($reportresults) {
			openSubbar(1200);
			print("Results");
			closeSubbar();
			
			print("<table class='standard' style='width:1200px;'>");
			
			// Headers {
			print("<tr class='heading' style='font-size:10px;'>");
				foreach ($reportresults[1] as $header=>$discard) {
					print("<td>");
					print($header);
					print("</td>");
				}
				print("</tr>");
			// }
			
			foreach ($reportresults as $reskey=>$resval) {
				print("<tr class='content1' style='height:38px;'>");
				foreach ($resval as $column=>$value) {
					print("<td>");
					print("<p class='standard' style='color:BLACK;'>".$value."</font>");
					print("</td>");
				}
				print("</tr>");
			}
			
			print("</table>");
		}
		
		print("</div>");
		
		print("</form>");
		maxineBottom();
	}
	
	/** returnFleetTruckCount()
	 * get all fleets truck count
	 * @return array on success false otherwise
	 */
	function returnFleetTruckCount() {
		$manager = new TableManager("fleet_truck_count");
		$manager->setCustomIndex('fleet_id');
		return $manager->selectMultiple();
	}
	
	function checkFleetScoreUpdates() {
		// Prep {
			$rows = returnFleetTruckCount();
			if(array_key_exists('conf', $_POST) && $_POST["conf"]) {
				$conf = $_POST["conf"];
			}
			if(isset($conf) && $conf) {
				$startdate	= unixDate($conf["startdate"]); 
				$stopdate	= unixDate($conf["stopdate"]);
				
			} else {
				$startdate	= mktime(0, 0, 0, date("m"), 1, date("Y"));
				$stopdate	= mktime(0, 0, 0, (date("m") + 1), 1, date("Y"));
			}
			
			$where	= "date >= ".$startdate." AND date <= ".$stopdate;
			
			if(isset($conf) && ($conf["fleetid"] > 0)) {
				$fleetid	= $conf["fleetid"];
				$where		.= " AND fleetid=".$conf["fleetid"];
			} else {
				$fleetid	= 0;
			}
			
			$updatelist = sqlPull(array("table"=>"fleet_scores", "where"=>$where, "sort"=>"date"));
			
			$fleetdayobj = new fleetDayHandler;
			$fleetlist = (array)array();
			$tmp = $fleetdayobj->getIncomeFleets();
			foreach ($tmp as $key=>$val) {
				$sorted[$val['id']] = $val["name"];
			}
			asort($sorted, SORT_STRING);
			foreach ($sorted as $key=>$val) {
				$fleetlist[$key] = $sorted[$key];
			}
			unset($sorted);
			unset($tmp);
			
			$totincome		= 0;
			$totbudget		= 0;
			$totcontrib		= 0;
			$background		= "content1";
		// }
	
		print('<!DOCTYPE html>');
		print('<head>');
		print('<meta charset="utf-8">');
        print('<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">');
 		print('<title>Dashboards - Barloworld Transport</title>');
       	print('<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />');
       	print('<link href="favicon.ico" rel="shortcut icon" />');
        print('<link rel="stylesheet" href="'.BASE.'basefunctions/scripts/bootstrap.min.css">');
        print('<link href="'.BASE.'basefunctions/scripts/font-awesome.min.css" rel="stylesheet">');
        print('<link rel="stylesheet" href="'.BASE.'Maxine/displaycase/content/site/css/fonts.css">');
        print('<link rel="stylesheet" href="'.BASE.'Maxine/displaycase/content/site/css/main.css">');
        print('<script src="'.BASE.'Maxine/displaycase/content/site/js/vendor/modernizr-2.6.2.min.js"></script>');
        print('<script src="'.BASE.'basefunctions/scripts/jquery.min.js"></script>');
		print('<script src="'.BASE.'basefunctions/scripts/jquery.ui.touch-punch.min.js"></script>');
        print('<!--[if lt IE 9]>');
        print('<script src="'.BASE.'basefunctions/scripts/html5shiv.min.js"></script>');
        print('<script src="'.BASE.'basefunctions/scripts/respond.js"></script>');
        print('<![endif]-->');
        print('<script src="'.BASE.'Maxine/displaycase/content/site/js/vendor/jcircle.js"></script>');
        print('<script src="'.BASE.'Maxine/displaycase/content/site/js/vendor/jquery.flot/jquery.flot.js"></script>');
        print('<script src="'.BASE.'Maxine/displaycase/content/site/js/vendor/jquery.flot/jquery.flot.time.js"></script>');
        print('<script src="'.BASE.'Maxine/displaycase/content/site/js/vendor/jquery.flot/jquery.flot.tooltip.js"></script>');
		print('</head>'.PHP_EOL);
		print('<body>');
		print('<div id="root"></div>');
        print('<div id="page" style="overflow-y:auto;">');
        print('<header>');
        print('<div class="controlsWrapper">');
        print('<a href="#" class="menu"></a>');
        print('</div><!-- controlsWrapper -->');
        print('</header>');
        print('<nav>');
        print('<ul>');
        print('<li><a href="/?personal">Dashboard</a></li>');
        print('<li><a href="/?mydashdetails">Dashboard Builder</a></li>');
        print('<li><a href="/?importfleetday">Import Day</a></li>');
        print('<li><a href="/?checkfleetscoreupdates">Fleet Scores</a></li>');
        print('<li><a href="/?ocddata">OCD Data</a></li>');
        print('<li><a href="/?logout">Logout</a></li>');
        print('</ul>');
        print('</nav>');
        //: Page Content
        print('<div id="blackouts">');
        //: Form
        print('<div class="fleetWrapper" style="height:10%;">');
        print('<form method="POST">');
        print('<table><tbody><tr>');
        print('<td>'); //: Col 1
        print('<label for="conf[fleetid]">Fleet:</label>');
        print('<select id="conf[fleetid]" name="conf[fleetid]" value="'.$fleetid.'">');
        print('<option value="0" '.($fleetid==0?"selected":"").'>All</option>');
        foreach ($fleetlist as $fleetkey=>$fleetval) {
        	if (!$fleetval)
        	{
        		continue;
        	}
        	print('<option value="'.$fleetkey.'"'.($fleetid==$fleetkey ? ' selected="selected"' : '').'>'.$fleetval.'</option>');
        }
        print('</select>');
        print('</td>');
        print('<td>'); //: Col 2
        print('<label for="conf[startdate]">Start Date:</label>');
        print('<input id="conf[startdate]" name="conf[startdate]" value="'.date("d/m/Y", $startdate).'" readonly style="width: 160px; text-align: center;">');
        print('<img src="'.BASE.'/images/calendar.png" style="cursor:pointer" onClick="displayDatePicker(\'conf[startdate]\', this, \'dmy\', \'\');">');
        print('</td>');
        print('<td>'); //: Col 3
        print('<label for="conf[stopdate]">Stop Date:</label>');
        print('<input id="conf[stopdate]" name="conf[stopdate]" value="'.date("d/m/Y", $stopdate).'" readonly style="width: 160px; text-align: center;">');
        print('<img src="'.BASE.'/images/calendar.png" style="cursor:pointer" onClick="displayDatePicker(\'conf[stopdate]\', this, \'dmy\', \'\');">');
        print('</td>');
        print('<td>'); //: Col 4
        print('<input type="Submit" value="Search" />');
        print('</td>');
        print('</tr></tbody></table>');
        print('</form>');
        print('</div>');
        //: End
        //: Table
        print('<div class="fleetWrapper" style="height:90%;">');
        if($updatelist) {
        	print("<table style='margin-bottom:20px;'>");
			//: Headers
			print("<thead><tr><td width=5%>");
			print("ID");
			print("</td><td width=11%>");
			print("Fleet");
			print("</td><td width=7%>");
			print("Date");
			print("</td><td width=12%>");
			print("Updated");
			print("</td><td width=6%>");
			print("Difference");
			print("</td><td width=9%>");
			print("Income");
			print("</td><td width=8%>");
			print("Budget");
			print("</td><td width=5%>");
			print(shortenWord('Subbie Income', 8));
			print("</td><td width=5%>");
			print(shortenWord('Subbie Kms', 8));
			print("</td><td width=8%>");
			print("Budget Contrib");
			print("</td><td width=8%>");
			print("Contrib");
			print("</td><td width=8%>");
			print(shortenWord("Contrib Updated", 8));
			print("</td><td width=5%>");
			print("Kms");
			print("</td><td width=9%>");
			print(shortenWord("Ave. Kms per truck", 4));
			print("</td><td width=9%>");
			print(shortenWord("Budget Ave. Kms per truck", 6));
			print("</td></tr></thead>");
			//: End
			$totkmspertruck = (int)0;
			$totbudgetkmspertruck = (int)0;
			$totbudgetcontrib = (float)0.00;
			$totkms = (float)0.00;
			print('<tbody>');
			foreach ($updatelist as $updatekey=>$updateval) {
				$difference	= (date("U") - $updateval["updated"]);
				$diffhours	= $difference / 60 / 60;
				$diffhours	= floor($diffhours);
				$diffmins	= $difference - $diffhours * 60 * 60;
				$diffmins	= $diffmins / 60;
				$diffmins	= floor($diffmins);
				$day		= date("d", $updateval["date"]);
				
				print("<tr><td>");
				print($updateval["id"]);
				print("</td><td>");
				print($fleetlist[$updateval["fleetid"]]);
				print("</td><td>");
				print(date("d m Y", $updateval["date"]));
				print("</td><td>");
				if($updateval["updated"] <> 0) {
					print(date("H:i d-m-Y", $updateval["updated"]));
				} else {
					print("Empty");
				}
				print("</td><td>");
				print($diffhours."h ".$diffmins."m");
				print("</td><td>");
				print($updateval["income"]);
				print("</td><td>");
				print($updateval["budget"]);
				print("</td><td>");
				print($updateval["subbie_income"]);
				print("</td><td>");
				print($updateval["subbie_kms"]);
				print("</td><td>");
				print($updateval["budgetcontrib"]);
				print("</td><td>");
				print($updateval["contrib"]);
				print("</td><td>");
				if($updateval["contribupdated"] <> 0) {
					print(date("H:i d-m-Y", $updateval["contribupdated"]));
				} else {
					print("Empty");
				}
				print("</td><td>");
				print($updateval["kms"]);
				print("</td><td>");
				print(round(($updateval["kms"]/(isset($rows[$updateval["fleetid"]]) && isset($rows[$updateval["fleetid"]]["count"]) ? $rows[$updateval["fleetid"]]["count"] : 1)), 2));
				print("</td><td>");
				print(round(($updateval["budkms"]/(isset($rows[$updateval["fleetid"]]) && isset($rows[$updateval["fleetid"]]["count"]) ? $rows[$updateval["fleetid"]]["count"] : 1)), 2));
				print("</td></tr>");
				
				$totincome	+= $updateval["income"];
				$totbudget	+= $updateval["budget"];
				$totcontrib	+= $updateval["contrib"];
				$totbudgetcontrib	+= $updateval["budgetcontrib"];
				$totkms	+= $updateval["kms"];
				$totkmspertruck	+= ($updateval["kms"]/((isset($rows[$updateval["fleetid"]]) && isset($rows[$updateval["fleetid"]]["count"]) && $rows[$updateval["fleetid"]]["count"]) ? $rows[$updateval["fleetid"]]["count"] : 1));
				$totbudgetkmspertruck	+= ($updateval["budkms"]/((isset($rows[$updateval["fleetid"]]) && isset($rows[$updateval["fleetid"]]["count"]) && $rows[$updateval["fleetid"]]["count"]) ? $rows[$updateval["fleetid"]]["count"] : 1));
			}
			print("<tr><td colspan=5>");
			print("</td><td>");
			print($totincome);
			print("</td><td>");
			print($totbudget);
			print("</td><td>");
			print("</td><td>");
			print("</td><td>");
			print($totbudgetcontrib);
			print("</td><td>");
			print($totcontrib);
			print("</td><td>");
			print("</td><td>");
			print(round($totkms, 2));
			print("</td><td>");
			print(round($totkmspertruck, 2));
			print("</td><td>");
			print(round($totbudgetkmspertruck, 2));
			print("</td></tr>");
			print('<tbody>');
			print("</table>");
        }
        print('</div>');
        //: End
        print('</div>');
        //: End
        //: End Page
		print('<script>window.jQuery || document.write("<script src=\"'.BASE.'Maxine/displaycase/content/site/js/vendor/jquery-1.9.1.min.js\"><\/script>")</script>');
        print('<script src="'.BASE.'basefunctions/scripts/jquery.color.min.js"></script>');
        print('<script src="'.BASE.'Maxine/displaycase/content/site/js/plugins.js"></script>');
        print('<script src="'.BASE.'Maxine/displaycase/content/site/js/styling.js"></script>');     
        print('<script src="'.BASE.'Maxine/displaycase/content/site/js/main.js"></script>');
        print('<script type="text/javascript" language="javascript" src="'.BASE.'/basefunctions/scripts/manline.js"></script>');
		print('</body>'.PHP_EOL);
		print('</html>');
	}
	
	function exportFleetScoreUpdates() {
		require_once BASE.'basefunctions/baseapis/PHPExcel/php-excel.class.php';
		$manager = new TableManager("fleet_scores");
		$data = __sanitizeData($_GET);
		$where = (string)"1=1";
		if (isset($data["fleet"]) && $data["fleet"]) {
			$where .= $manager->quoteString(" AND `fleetid`=?", (int)$data["fleet"]);
		}
		if (isset($data["start"]) && $data["start"]) {
			$where .= $manager->quoteString(" AND `date`>=?", (int)unixDate($data["start"]));
		}
		if (isset($data["end"]) && $data["end"]) {
			$where .= $manager->quoteString(" AND `date`<=?", (int)unixDate($data["end"]));
		}
		$manager->setWhere($where);
		$manager->setOrderBy(array("column"=>"date"));
		$records = $manager->selectMultiple();
		$rows = returnFleetTruckCount();
		$xlsdata = (array)array();
		$fleetdayobj = new fleetDayHandler();
		$tempfleetlist = $fleetdayobj->getIncomeFleets();
		$fleetlist = array();
		foreach ($tempfleetlist as $tempkey=>$tempval) {
			$fleetlist[$tempval["id"]]	= $tempval;
		}
		unset($tempfleetlist);
		unset($fleetdayobj);
		$row = (array)array(
				"Date",
				"Fleet",
				"Income budget",
				"Income",
				"Contribution budget",
				"Contribution",
				"Truck count",
				"Kms",
				"Ave. Kms per truck",
				"Budget Ave. Kms per truck"
		);
		$xlsdata[] = $row;
		foreach ($records as $val) {
			$row = (array)array(
					date("Y-m-d", $val["date"]),
					$fleetlist[$val["fleetid"]]["name"],
					$val["budget"],
					$val["income"],
					$val["budgetcontrib"],
					$val["contrib"],
					$rows[$val["fleetid"]]["count"],
					$val["kms"],
					round(($val["kms"]/(isset($rows[$val["fleetid"]]) && isset($rows[$val["fleetid"]]["count"]) ? $rows[$val["fleetid"]]["count"] : 1)), 2),
					round(($val["budkms"]/(isset($rows[$val["fleetid"]]) && isset($rows[$val["fleetid"]]["count"]) ? $rows[$val["fleetid"]]["count"] : 1)), 2)
			);
			$xlsdata[] = $row;
		}
		$xls = new Excel_XML('UTF-8', TRUE);
		$xls->addArray($xlsdata);
		$xls->generateXML('fleet_score_data');
	}
	
	// Green Mile Dash functions {
		function dashInput() {
			// Preparation {
				if($_POST["conf"]["date"]) {
					$pulldate	= $_POST["conf"]["date"];
					
					$month		= $pulldate["month"];
					$year			= $pulldate["year"];
				} else {
					$month		= date("m");
					$year			= date("Y");
				}
				
				$currentyear	= date("Y");
				$pulldate			= mktime(0, 0, 0, $month, 1, $year);
				
				$greenmilescore	=	sqlPull(array("table"=>"greenmile_scores", "where"=>"date=".$pulldate, "onerow"=>1));
				
				$onchange = "onchange='dashinputform.action=\"index.php?mode=maxine/index&action=dashinput\"; dashinputform.submit();'";
			// }
			
			maxineTop("Greenmile Inputs");
			print("<form name='dashinputform' action='index.php?mode=maxine/index&action=commitdashinput' method='post'>");
			
			openHeader();
			maxineButton("Submit", "dashinputform.submit()");
			maxineButton("Back", "goTo(\"index.php?mode=maxine/index&action=firstmenu\");");
			closeHeader();
			
			print("<div class='tray'>");
			
			// Date Select {
				openSubbar(400);
				print("Date");
				closeSubbar();
				
				print("<table class='standard' style='width:400px;'>");
				
				print("<tr class='content1'><td align='center'>");
				
				print("<select name=conf[date][month] ".$onchange." style='width:50%;'>");
				for($i=1; $i<13; $i++) {
					print("<option value=".$i." ".($month==$i?"selected":"").">");
					print(date("F", mktime(0,0,0,$i,1,$year)));
					print("</option>");
				}
				print("</select>");
				
				print("<select name=conf[date][year] ".$onchange." style='width:50%;'>");
				for($i=0; $i<6; $i++) {
					$displayyear	= $currentyear - $i;
					print("<option ".($displayyear==$year?"selected":"").">".$displayyear."</option>");
				}
				print("</select>");
				
				print("</td></tr>");
				
				print("</table>");
			// }
			
			// Misc {
				openSubbar(600);
				print("Miscellaneous");
				closeSubbar();
				
				print("<table class='standard' style='width:600px;'>");
				
				print("<tr class='content1'><td align='center'>");
				print("Invoice in full");
				print("</td><td width=25%>");
				print("<input name=conf[details][invoicefull] value='".$greenmilescore["invoicefull"]."' style='text-align:right;'>");
				print("</td>");
				
				print("<td align='center' width=25%>");
				print("POD");
				print("</td><td width=25%>");
				print("<input name=conf[details][pods] value='".$greenmilescore["pods"]."' style='text-align:right;'>");
				print("</td></tr>");
				
				print("<tr class='content1'><td align='center'>");
				print("Invoice Error");
				print("</td><td>");
				print("<input name=conf[details][invoiceerrors] value='".$greenmilescore["invoiceerrors"]."' style='text-align:right;'>");
				print("</td>");
				
				print("<td align='center'>");
				print("Shortages");
				print("</td><td>");
				print("<input name=conf[details][shortages] value='".$greenmilescore["shortages"]."' style='text-align:right;'>");
				print("</td>");
				
				print("<tr class='content1'><td align='center'>");
				print("Complaints");
				print("</td><td>");
				print("<input name=conf[details][complaints] value='".$greenmilescore["complaints"]."' style='text-align:right;'>");
				print("</td>");
				
				print("<td colspan=2>");
				print("</td></tr>");
				
				print("</table>");
			// }
			
			// Defects & Opportunities {
				openSubbar(600);
				print("Defects & Opportunities");
				closeSubbar();
				
				print("<table class='standard' style='width:600px;'>");
				
				print("<tr class='content1'><td align='center' width=25%>");
				print("Defect");
				print("</td><td width=25%>");
				print("<input name=conf[details][defects] value='".$greenmilescore["defects"]."' style='text-align:right;'>");
				print("</td>");
				
				print("<td align='center' width=25%>");
				print("Opportunities");
				print("</td><td width=25%>");
				print("<input name=conf[details][opportunities] value='".$greenmilescore["opportunities"]."' style='text-align:right;'>");
				print("</td></tr>");
				
				print("</table>");
			// }
			
			print("</div>");
			closeTrayDiv();
			
			print("</form>");
			maxineBottom();
		}
		
		function commitDashInput() {
			$conf					= $_POST["conf"];
			$date					= mktime(0, 0, 0, $conf["date"]["month"], 1, $conf["date"]["year"]);
			
			$conf["details"]["date"]	= $date;
			
			$dashdetails	= sqlPull(array("table"=>"greenmile_scores", "where"=>"date=".$date, "onerow"=>1));
			
			if($dashdetails) {
				updateGreenmileScore($conf["details"]);
			} else {
				createGreenmileScore($conf["details"]);
			}
			
			goHere("index.php?mode=maxine/index&action=dashinput");
		}
	// }
	
	// Functions to import a single day by user {
		function selectCustomFleetday() {
			$maxday	= date("d");
			
			print("<form name='customfleetdayform' action='index.php?mode=maxine/index&action=importcustomfleetday' method='post'>");
			maxineHeader("top");
			print("<img src='".TOPBUTTONS."/buttonsubmit.png' onClick='customfleetdayform.submit();'>");
			print("<img src='".TOPBUTTONS."/buttonback.png' onClick=goTo('index.php?mode=maxine/index&action=firstmenu');>");
			print("</td></tr>");
			
			print("<tr><td align='center'>");
			print("<table class=TRAY width=25%>");
			
			print("<tr><td class=TOPROW align='center'>");
			print("<font class=HEADING>Day to Import</font>");
			print("</td></tr>");
			
			print("<tr><td>");
			print("<select name=conf[dayselect] style='width:100%;'>");
			for($i=1; $i<=$maxday; $i++) {
				print("<option>".$i."</option>");
			}
			print("</select>");
			print("</td></tr>");
			
			print("</table>");
			print("</td></tr>");
			
			maxineFoot();
			print("</form>");
		}
		
		function importCustomFleetday() {
			$conf	= $_POST["conf"];
			
			$fleetdayobj	= new fleetDayHandler();
			
			$fleetscore		= $fleetdayobj->pullFleetDay($conf["dayselect"]);
			
			$fleetdayobj->saveFleetDay($fleetscore);
			
			print("DONE.");
			goHere("index.php?mode=maxine/index&action=selectcustomfleetday");
		}
	// }
?>