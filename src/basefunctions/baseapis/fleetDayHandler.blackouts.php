<?PHP
/** Class::fleetDayHandler
	* @author Justin Ward
	* @author justinw@manlinegroup.com
	* @copyright 2010 Manline Group (Pty) Ltd
	
	* This class requires access to the FileParser class. 
	
	* @example First Example Start
		* require_once(BASE.'basefunctions/baseapis/fleetDayHandler.php');
		* $fleetdayobj	= new fleetDayHandler();
		* $today	= date("d");
		* $fleetscore		= $fleetdayobj->pullFleetDay($today);
	* @example First Example End
**/
	require_once(BASE."basefunctions/baseapis/FileParser/FileParser.php");
	
	class fleetDayHandler {
		protected $_incomefleets = array(
			array("id"=>29, "name"=>"Entire Active Fleet", "budget"=>1303701, "budkms"=>103011, "pubhol"=>1, "display"=>1, "displayblackouts"=>1, "displayrasta"=>1),
			array("id"=>28, "name"=>"Long Distance", "budget"=>508752, "budkms"=>44705, "pubhol"=>1, "display"=>1, "displayblackouts"=>1, "displayrasta"=>1),
			array("id"=>51, "name"=>"LWT Fleet", "budget"=>86894, "budkms"=>7173, "pubhol"=>1, "display"=>1, "displayblackouts"=>1, "displayrasta"=>1),
			array("id"=>81, "name"=>"Freight Haz Fleet", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>1, "displayrasta"=>1),
			//array("id"=>47, "name"=>"Isando - Reclam Triaxles", "budget"=>79539, "budkms"=>4903, "pubhol"=>1, "display"=>1, "displayblackouts"=>1, "displayrasta"=>1),
			//array("id"=>50, "name"=>"Energy - Flat Decks", "budget"=>92133, "budkms"=>6953, "pubhol"=>1, "display"=>1, "displayblackouts"=>1, "displayrasta"=>1),
			array("id"=>32, "name"=>"Energy - Tankers", "budget"=>44016, "budkms"=>3313, "pubhol"=>1, "display"=>1, "displayblackouts"=>1, "displayrasta"=>1),
			array("id"=>53, "name"=>"Energy - VDBL Tankers", "budget"=>117358, "budkms"=>7213, "pubhol"=>0, "display"=>1, "displayblackouts"=>1, "displayrasta"=>1),
			array("id"=>42, "name"=>"Energy - Buckman", "budget"=>43112, "budkms"=>2962, "pubhol"=>1, "display"=>1, "displayblackouts"=>1, "displayrasta"=>1),
			array("id"=>54, "name"=>"XB - Links", "budget"=>300880, "budkms"=>23117, "pubhol"=>1, "display"=>1, "displayblackouts"=>1, "displayrasta"=>1),
			array("id"=>35, "name"=>"XB - Triaxles", "budget"=>22220, "budkms"=>1722, "pubhol"=>1, "display"=>1, "displayblackouts"=>1, "displayrasta"=>1),
			array("id"=>75, "name"=>"XB - 7/11 Links", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>1, "displayrasta"=>1),
			array("id"=>82, "name"=>"Wilmar Bulk Fleet", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>1),
			array("id"=>67, "name"=>"Long Distance - Jimmy", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
			array("id"=>68, "name"=>"Long Distance - Kevin", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
			array("id"=>69, "name"=>"Long Distance - Kershan", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
			array("id"=>83, "name"=>"Ashton Fleet", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>1),
			
			// The count in displayMainDash controls how far down this list the dashboards go.  Fleets after this point won't appear in normal cycles
			array("id"=>60, "name"=>"Manline Consolidated", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>0),
			
			array("id"=>33, "name"=>"Energy - Total Fleet", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
			array("id"=>76, "name"=>"Freight Consolidated", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
			array("id"=>77, "name"=>"Africa Consolidated", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
			
			//array("id"=>71, "name"=>"XB Zac", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>0),
			//array("id"=>72, "name"=>"XB Jaap", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>0),
			//array("id"=>73, "name"=>"XB Jacques", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>0)
		);
		
		protected $_apiurl	= "http://login.max.manline.co.za/m4/2/api_request/Report/export?";
		protected $_day			= 0;
		protected $_date		= 0;
		
		// Getters, or functions which return protected variables or details from them {
			public function getIncomeFleets() {
				return $this->_incomefleets;
			}
			
			public function getFleetId($index) {
				return $this->_incomefleets[$index]["id"];
			}
		// }
		
		// Standard day functions {
			public function pullFleetDay($day, $range=0) {
				$this->_day		= $day;
				$this->_date	= mktime(0, 0, 0, date("m"), $day, date("Y"));
				
				// Create date strings for query {
					$startmonth		= date("m");
					$startyear		= date("Y");
					$startday			= date("d", mktime(0, 0, 0, $startmonth, ($this->_day - $range), $startyear));
					
					$startstring	= $startyear."-".$startmonth."-".$startday;
					//$startstring	= $startyear."-".$startmonth."-".$startday." 00:00";
					
					$stopdate		= mktime(0, 0, 0, $startmonth, ($this->_day + 1), $startyear);
					$stopday		= date("d", $stopdate);
					$stopmonth	= date("m", $stopdate);
					$stopyear		= date("Y", $stopdate);
					
					$stopstring	= $stopyear."-".$stopmonth."-".$stopday;
					//$stopstring	= $startyear."-".$startmonth."-".$startday." 23:59";
					
					print($startstring." to ".$stopstring.PHP_EOL);
				// }
				
				// Go through each Income Fleet, checking various details and getting the trips for the day {
					foreach ($this->_incomefleets as $incfleetkey=>$incfleetval) {
						$dayincome = 0;
						$daykms = 0;
						$blackoutcount = 0;
						
						// Pull the day's trips for this fleet {
							// Import the trip data {
								//$tripurl = "http://max.mobilize.biz/m4/2/api_request/Report/export?report=84&responseFormat=csv&Start_Date=2011-02-11&Stop_Date=2011-02-12&Fleet=29"; 
								$tripurl = $this->_apiurl."report=84&responseFormat=csv&Start_Date=".$startstring."&Stop_Date=".$stopstring."&Fleet=".$incfleetval["id"];
								print($tripurl.PHP_EOL);
								
								$fileParser = new FileParser($tripurl);
								
								$tripdata = $fileParser->parseFile();
								
								if ($tripdata === false) {
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
							
							foreach ($tripdata as $tripkey=>$tripval) {
								$triptime	= "";
								if(($tripval["Loading Arrival"] == "(none)") || ($tripval["Loading Arrival"] == null)) {
									$triptime	= $tripval["Loading ETA"];
								} else {
									$triptime	= $tripval["Loading Arrival"];
								}
								
								$cutofftime	= $stopyear."-".$stopmonth."-".$stopday." 00:00:00";
								//$cutofftime	= $startyear."-".$startmonth."-".$startday." 22:00:00";
								
								if($triptime != $cutofftime) {
									$dayincome	+= array_key_exists("Tripleg Income", $tripval) ? str_replace(",", "", $tripval["Tripleg Income"]) : 0;
									//$daycontrib	+= str_replace(",", "", $tripval["Tripleg Contrib"]);
									$daykms			+= array_key_exists("Total Kms", $tripval) ? $tripval["Total Kms"] : 0;
								}
							}
							
							$fleetscore[$incfleetval["id"]]["fleetid"] = $incfleetval["id"];
							$fleetscore[$incfleetval["id"]]["income"] = $dayincome;
							$fleetscore[$incfleetval["id"]]["kms"] = $daykms;
							$fleetscore[$incfleetval["id"]]["day"] = $this->_day;
							$fleetscore[$incfleetval["id"]]["date"] = $this->_date;
							$fleetscore[$incfleetval["id"]]["updated"] = date("U");
						// }
					}
				// }
				
				return $fleetscore;
			}
			
			public function findBackDay($today) {
				$backday	= $today - 2; // This is where to start searching from.  Currently, we pull the most recent 2 days every 5 minutes, so back days must start before that.
				$now			= date("U");
				while($backday >= 0) {
					// 2 hour renewal time for days within 10 days and 4 hours for those further back
					if(($today - $backday) < 9) {
						$timespan	= 60*60*2;
					} else {
						$timespan	= 60*60*4;
					}
					
					$backdate = mktime(0, 0, 0, date("m"), $backday , date("Y"));
					$record	= sqlPull(array("table"=>"fleet_scores", "where"=>"date=".$backdate." AND fleetid=29", "select"=>"id, fleetid, updated, date", "customkey"=>"fleetid", "onerow"=>1));
					
					// If there is no record for days already past, that day needs to be fixed 
					if(!$record) {
						print("No record for this day!");
						break;
					} else {
						$updateage	= $now - $record["updated"];
						print("For Backday ".$backday.", Update Age is ".$updateage." (".$now." - ".$record["updated"].")".PHP_EOL);
						if($updateage > $timespan) {
							break;
						}
					}
					$backday--;
				}
				return $backday;
			}
		// }
		
		// Contrib day functions {
			public function pullFleetDayWithContrib($day, $range=0) {
				$this->_day		= $day;
				$this->_date	= mktime(0, 0, 0, date("m"), $day, date("Y"));
				
				// Create date strings for query {
					$startmonth		= date("m");
					$startyear		= date("Y");
					$startday			= date("d", mktime(0, 0, 0, $startmonth, ($this->_day - $range), $startyear));
					
					$startstring	= $startyear."-".$startmonth."-".$startday;
					//$startstring	= $startyear."-".$startmonth."-".$startday." 00:00";
					
					$stopdate		= mktime(0, 0, 0, $startmonth, ($this->_day + 1), $startyear);
					$stopday		= date("d", $stopdate);
					$stopmonth	= date("m", $stopdate);
					$stopyear		= date("Y", $stopdate);
					
					$stopstring	= $stopyear."-".$stopmonth."-".$stopday;
					//$stopstring	= $startyear."-".$startmonth."-".$startday." 23:59";
					
					print($startstring." to ".$stopstring.PHP_EOL);
				// }
				
				// Go through each Income Fleet, checking various details and getting the trips for the day {
					foreach ($this->_incomefleets as $incfleetkey=>$incfleetval) {
						$daycontrib				= 0;
						$daykms						= 0;
						
						// Pull the day's trips for this fleet {
							// Import the trip data {
								//$tripurl = "http://max.mobilize.biz/m4/2/api_request/Report/export?report=84&responseFormat=csv&Start_Date=2011-02-11&Stop_Date=2011-02-12&Fleet=29"; 
								$tripurl = $this->_apiurl."report=138&responseFormat=csv&Start_Date=".$startstring."&Stop_Date=".$stopstring."&Fleet=".$incfleetval["id"];
								print($tripurl.PHP_EOL);
								
								$fileParser = new FileParser($tripurl);
								
								$tripdata = $fileParser->parseFile();
								
								if ($tripdata === false) {
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
							
							foreach ($tripdata as $tripkey=>$tripval) {
								$triptime	= "";
								if((array_key_exists("Loading Arrival", $tripval)) && (isset($tripval["Loading Arrival"])) && ($tripval["Loading Arrival"] == "(none)") || ($tripval["Loading Arrival"] == null)) {
									$triptime	= isset($tripval["Loading ETA"]) ? $tripval["Loading ETA"] : 2/24;
								} else {
									$triptime	= isset($tripval["Loading Arrival"]) ? $tripval["Loading Arrival"] : 2/24;
								}
								
								$cutofftime	= $stopyear."-".$stopmonth."-".$stopday." 00:00:00";
								//$cutofftime	= $startyear."-".$startmonth."-".$startday." 22:00:00";
								
								if($triptime != $cutofftime) {
								    if (isset($tripval["Tripleg Contrib"])) {
								        $daycontrib	+= str_replace(",", "", $tripval["Tripleg Contrib"]);
									}
									if (isset($tripval["Total Kms"])) {
									    $daykms			+= $tripval["Total Kms"];
									}
								}
							}
							
							$fleetscore[$incfleetval["id"]]["fleetid"]				= $incfleetval["id"];
							$fleetscore[$incfleetval["id"]]["contrib"]				= $daycontrib;
							$fleetscore[$incfleetval["id"]]["day"]						= $this->_day;
							$fleetscore[$incfleetval["id"]]["date"]						= $this->_date;
							$fleetscore[$incfleetval["id"]]["contribupdated"]	= date("U");
						// }
					}
				// }
				
				return $fleetscore;
			}
			
			public function findContribBackDay($today) {
				$backday	= $today - 2; // This is where to start searching from.  Currently, we pull the most recent 2 days every 5 minutes, so back days must start before that.
				$now			= date("U");
				while($backday >= 0) {
					// 4 hour renewal time
						$timespan	= 60*60*4;
					
					$backdate = mktime(0, 0, 0, date("m"), $backday , date("Y"));
					$record	= sqlPull(array("table"=>"fleet_scores", "where"=>"date=".$backdate." AND fleetid=29", "select"=>"id, fleetid, contribupdated, date", "customkey"=>"fleetid", "onerow"=>1));
					
					// If there is no record for days already past, that day needs to be fixed 
					if(!$record) {
						print("No record for this day!");
						break;
					} else {
						$updateage	= $now - $record["contribupdated"];
						print("For Backday ".$backday.", Update Age is ".$updateage." (".$now." - ".$record["contribupdated"].")<br>");
						if($updateage > $timespan) {
							break;
						}
					}
					$backday--;
				}
				return $backday;
			}
		// }
		
		//: Order functions
		/** fleetdayHandler::importOrders()
		 * Import order data so that we can get an accurate read on the number of blackouts
		 * @author Feighen Oosterbroek
		 * @author feighen@manlinegroup.com
		 * @return FALSE on failure NULL otherwise
		 */
		public function importOrders() {
			foreach ($this->_incomefleets as $incfleetkey=>$incfleetval) {
				//: Preparation
				$blackoutcount = (float)0;
				//: End
				//: Confirm the budgeted blackouts
				$budgeturl = $this->_apiurl."report=85&responseFormat=csv&Start_Date=".date("Y-m-d")."&Stop_Date=".date("Y-m-d", strtotime("+1 day"))."&Fleet=".$incfleetval["id"];
				$fileParser = new FileParser($budgeturl);
				$fileParser->setCurlFile("budget.".$incfleetval["id"].".csv");
				$budgetdata = $fileParser->parseFile();
				if ($budgetdata === false) {
					print("<pre style='font-family:verdana;font-size:13'>");
					print_r($fileParser->getErrors());
					print("</pre>");
					return;
					print("<pre style='font-family:verdana;font-size:13'>errors");
					print_r($fileParser->getErrors());
					print("</pre>");
					print("<br />");
				}
				//: Collate
				foreach ($budgetdata as $budgetkey=>$budgetval) {
					$truckbudget = isset($budgetval["Income"]) ? str_replace(",", "", $budgetval["Income"]) : "";
					$truckbudget = str_replace("R", "", $truckbudget);
					// Calculate the number of trucks per fleet that have a budget and no trip
					if(isset($budgetval["Blackout Status"]) && (($budgetval["Blackout Status"] == "1") || ($budgetval["Blackout Status"] == "Yes"))) {
						if($truckbudget > 0) {
							$blackoutcount++;
						}
					}
				}
				//: End
				$ordersurl = $this->_apiurl."report=98&responseFormat=csv&Start_Date=".date("Y-m-d")."&Stop_Date=".date("Y-m-d", strtotime("+1 day"));
				$fileParser = new FileParser($ordersurl);
				$fileParser->setCurlFile("orders".$incfleetval["id"].".csv");
				$ordersdata = $fileParser->parseFile();
				if ($ordersdata === false) {
					print("<pre style='font-family:verdana;font-size:13'>");
					print_r($fileParser->getErrors());
					print("</pre>");
					return;
					print("<pre style='font-family:verdana;font-size:13'>errors");
					print_r($fileParser->getErrors());
					print("</pre>");
					print("<br>");
				}
				foreach ($ordersdata as $orderkey=>$orderval) {
					if($orderval["Fleetid"] != "(null)") {
						if (isset($blackoutcount)) {
							$blackoutcount--;
						}
					}
				}
				//: Insert or update
				$fleetscore = (array)array();
				$fleetscore["blackouts"] = $blackoutcount;
				//: check to see if this data needs to be updated or if it can just be inserted
				$record = sqlPull(array(
						"onerow"=>TRUE,
						"table"=>"fleet_scores",
						"where"=>"`fleetid`=".$incfleetval["id"]." AND `date`=".mktime(0,0,0,date("m"),date("d"),date("Y"))
				));
				if (isset($record) && $record) { //: Update
					sqlCommit(array(
							"table"=>"fleet_scores",
							"where"=>"id=".$record["id"],
							"fields"=>$fleetscore
					));
				} else { //: Insert
					sqlCreate(array(
							"table"=>"fleet_scores",
							"fields"=>$fleetscore
					));
				}
			}
		}
		//: End
			
		//: Budget functions
		/** fleetdayHandler::importBudget()
		 * Import this months budget data
		 * @author Feighen Oosterbroek
		 * @author feighen@manlinegroup.com
		 * @return FALSE on failure NULL otherwise
		 */
		public function importBudget() {
			for ($i=1;$i<=date("t");$i++) {
				foreach ($this->_incomefleets as $incfleetkey=>$incfleetval) {
					$blackoutcount = (float)0;
					$daybudget = (float)0;
					$daybudkms = (float)0;
					$daybudgetcontrib = (float)0;
					//: Get the data
					$budgeturl = $this->_apiurl."report=85&responseFormat=csv&Start_Date=".date("Y-m-".(strlen($i) === 1 ? "0".$i : $i))."&Stop_Date=".($i == date("t")? date("Y-".date("m", strtotime("+1 month"))."-01") : date("Y-m-".(strlen($i) === 1 ? "0".($i+1) : $i+1)))."&Fleet=".$incfleetval["id"];
					$fileParser = new FileParser($budgeturl);
					$fileParser->setCurlFile("budget".$incfleetval["id"].".csv");
					$budgetdata = $fileParser->parseFile();
					if ($budgetdata === false) {
						print("<pre style='font-family:verdana;font-size:13'>");
						print_r($fileParser->getErrors());
						print("</pre>");
						return;
						print("<pre style='font-family:verdana;font-size:13'>errors");
						print_r($fileParser->getErrors());
						print("</pre>");
						print("<br />");
					}
					//: End
					//: Collate data
					foreach ($budgetdata as $budgetkey=>$budgetval) {
						$truckbudget = isset($budgetval["Income"]) ? str_replace(",", "", $budgetval["Income"]) : "";
						$truckbudget = str_replace("R", "", $truckbudget);
						$daybudget += $truckbudget;
					
						$truckbudgetcontrib	= isset($budgetval["Contribution"]) ? str_replace(",", "", $budgetval["Contribution"]) : "";
						$truckbudgetcontrib	= str_replace("R", "", $truckbudgetcontrib);
						$daybudgetcontrib += $truckbudgetcontrib;
					
						$daybudkms += isset($budgetval["Kms"]) ? $budgetval["Kms"] : 0;
					
						// Calculate the number of trucks per fleet that have a budget and no trip
						if(isset($budgetval["Blackout Status"]) && (($budgetval["Blackout Status"] == "1") || ($budgetval["Blackout Status"] == "Yes"))) {
							if($truckbudget > 0) {
								$blackoutcount++;
							}
						}
					}
					//: End
					//: Insert or update
					$fleetscore = (array)array();
					$fleetscore["fleetid"] = $incfleetval["id"];
					$fleetscore["budget"] = $daybudget;
					$fleetscore["budgetcontrib"] = $daybudgetcontrib;
					$fleetscore["budkms"] = $daybudkms;
					$fleetscore["day"] = $i;
					$fleetscore["date"] = strtotime(date("Y-m-".(strlen($i) === 1 ? "0".$i : $i)));
					$fleetscore["updated"] = date("U");
					$fleetscore["blackouts"] = $blackoutcount;
					//: check to see if this data needs to be updated or if it can just be inserted
					$record = sqlPull(array(
							"onerow"=>TRUE,
							"table"=>"fleet_scores",
							"where"=>"`fleetid`=".$fleetscore["fleetid"]." AND `date`=".$fleetscore["date"]
					));
					if (isset($record) && $record) { //: Update
						sqlCommit(array(
								"table"=>"fleet_scores",
								"where"=>"id=".$record["id"],
								"fields"=>$fleetscore
						));
					} else { //: Insert
						sqlCreate(array(
								"table"=>"fleet_scores",
								"fields"=>$fleetscore
						));
					}
					//: End
				}
				/* //: Testing
				if ($i > 1) {
					break;
				}
				//: End */
			}
		}
		//: End
		
		public function saveFleetDay($fleetscore) {
			// Create or commit records to database {
				$record	= sqlPull(array("table"=>"fleet_scores", "where"=>"date=".$this->_date, "customkey"=>"fleetid"));
				$check = (array)array(
					"income","contrib","kms","budget","budgetcontrib","budkms"
				);
				foreach ($fleetscore as $fleetkey=>$fleetval) {
					if($record[$fleetkey]) {
						if ($this->confirmFleetScoreData($record[$fleetkey], $fleetval) === FALSE) {
							continue;
						}
						//sqlDelete(array("table"=>"fleet_scores", "where"=>"fleetid=".$fleetkey." AND day=".$this->_day));
						//sqlCreate(array("table"=>"fleet_scores", "fields"=>$fleetval));
						sqlCommit(array("table"=>"fleet_scores", "where"=>"fleetid=".$fleetkey." AND date=".$this->_date, "fields"=>$fleetval));
					} else {
						sqlCreate(array("table"=>"fleet_scores", "fields"=>$fleetval));
					}
				}
			// }
		}
		
		public function getFleetScoreDay($fleet) {
			$date			= mktime(0,0,0,date("m"), date("d"), date("Y"));
			
			$fleetday	= sqlPull(array("table"=>"fleet_scores", "where"=>"fleetid=".$fleet." AND date=".$date, "sort"=>"day", "customkey"=>"day"));
			
			//$fleetday	= $this->useArtificialBudgets($fleet, $fleetday);
			
			return $fleetday;
		}
		
		public function getFleetScoreMonth($fleet) {
			$startdate	= mktime(0, 0, 0, date("m"), 1, date("Y"));
			$enddate		= mktime(0, 0, 0, date("m"), date("d"), date("Y"));
			/*
			print("<div style='color:WHITE;'>");
			print("Start Date: ".date("d m Y", $startdate)." ".$startdate."<br>");
			print("End Date ".date("d m Y", $enddate)." ".$enddate."<br>");
			print("</div>");
			*/
			
			$day				= date("d");
			
			$fleetdays	= sqlPull(array("table"=>"fleet_scores", "where"=>"fleetid=".$fleet." AND date>=".$startdate." AND date<=".$enddate, "sort"=>"day", "customkey"=>"day"));
			
			//$fleetdays	= $this->useArtificialBudgets($fleet, $fleetdays);
			
			return $fleetdays;
		}
		
		public function calcSliderTop($budget) {
			$slidertop	= 0;
			$increment	= 5000;
			$margin			= $budget + $increment / 5;
			while($slidertop < $margin) {
				if($slidertop > ($increment * 10)) {
					$increment *= 2;
				}
				
				$slidertop	+= $increment;
			}
			return $slidertop;
		}
		
		//: Private Functions
		/** confirmFleetScoreData(array $current, array $proposed)
		 * Sanity check data to be updated
		 * @param array $current Current data set from `DB_SCHEMA`.`fleets_scores`
		 * @param array $proposed Data to be used to update
		 * @return TRUE if all good FALSE on failure
		 */
		private function confirmFleetScoreData(array $current, array $proposed) {
			$check = (array)array(
				"income","contrib","kms","budget","budgetcontrib","budkms"					
			);
			$public_holidays = (array)array(
				2012=>array("2012-01-01","2012-01-02","2012-03-21","2012-04-06","2012-04-09","2012-04-27","2012-05-01","2012-06-16","2012-08-09","2012-09-24","2012-12-16","2012-12-17","2012-12-25","2012-12-26"),
				2013=>array("2012-01-01","2012-03-21","2012-03-29","2012-04-01","2012-04-27","2012-05-01","2012-06-16","2012-06-17","2012-08-09","2012-09-24","2012-12-16","2012-12-25","2012-12-26")
			);
			foreach ($check as $val) {
			    //: If the data we are trying to update doesn't exist skip the column
			    if (!isset($proposed[$val])) {continue;}
			    switch ($val) {
			    case "income":
			    case "contrib":
			    case "kms":
			        if (!$proposed[$val] && $current[$val]) {return FALSE;}
			        break;
			    }
			    if ((date("w", $this->_date) !== 0) && (!in_array(date("Y-m-d", $this->_date), $public_holidays[date("Y", $this->_date)]))) {
			        switch ($val) {
			        case "budget":
			        case "budgetcontrib":
			        case "budkms":
			            if (!$proposed[$val] && $current[$val]) {return FALSE;}
			            break;
			        }
			    }
			}
			return TRUE;
		}
		
		private function useArtificialBudgets($fleet, $fleetdays) {
			$pubholidays	= array(41=>41);
				
			$month				= date("m");
			$year					= date("Y");
			$fleetnumber	= 0;
			foreach ($this->_incomefleets as $fleetkey=>$fleetval) {
				if($fleetval["id"] == $fleet) {
					$fleetnumber	= $fleetkey;
				}
			}
				
			foreach ($fleetdays as $daykey=>$dayval) {
				$day	= $dayval["day"];
		
				$weekday		= date("w", mktime(0,0,1,$month,$day,$year));
		
				$daybudget	= 0;
				$daybudget	= $this->_incomefleets[$fleetnumber]["budget"];
		
		
				if(($this->_incomefleets[$fleetnumber]["pubhol"] == 1) && ($pubholidays[$day])) {
					$daybudget	= 0; // This fleet is not budgetted to make income on Public holidays
				} else if(($weekday == 6) || ($weekday == 0)) {
					$daybudget	= ($daybudget / 2);
				}
		
				$fleetdays[$daykey]["budget"]	= $daybudget;
				$fleetdays[$daykey]["budkms"]	= $this->_incomefleets[$fleetnumber]["budkms"];
			}
				
			return $fleetdays;
		}
		//: End
	} // This is the end of the class.  Do not put class functions after it.
?>