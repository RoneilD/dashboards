<?PHP
	// Personnel functions {
		// User functions {
			function pullUserList($config, $sort="firstname ASC", $search="1=1") {
				$table	= "users LEFT JOIN userdates ON users.personid=userdates.userid";
				$where	= $search." AND deleted < 1 and personid != 1";
				if($config == "people") {
					$where	.= " AND isgeneric = 0 AND isplace = 0";
				} else if($config == "calllist") {
					$where	.= " AND isgeneric = 0";
				} else if($config == "it") {
					$where	.= " AND isit = 1 AND available!=0";
				} else if($config == "inoutlist") {
					$where	.= " AND isgeneric = 0 AND oninout = 1";
				}
				$select	= "users.*, userdates.date AS birthday";
				
				$userlist = sqlPull(array("table"=>$table, "where"=>$where, "sort"=>$sort, "select"=>$select));
				return $userlist;
			}
			
			function createUser($config) {
				$data["username"]		= $config["username"];
				$data["firstname"]	= $config["firstname"];
				$data["lastname"]		= $config["lastname"];
				$data["position"]		= $config["position"];
				
				$data["extension"]	= $config["extension"];
				$data["cell"]				= $config["cell"];
				$data["email"]			= $config["email"];
				
				$data["deptid"]			= $config["deptid"];
				
				$data["isit"]				= $config["isit"];
				$data["isgeneric"]	= $config["isgeneric"];
				$data["isplace"]		= $config["isplace"];
				$data["ismanager"]	= $config["ismanager"];
				$data["issuper"]		= $config["issuper"];
				$data["isdamage"]		= $config["isdamage"];
				$data["isfleetper"]	= $config["isfleetper"];
				if($config["isadmin"]) {
					$data["isadmin"]	= $config["isadmin"];
				}
				$data["canlogin"]		= $config["canlogin"];
				$data["oninout"]		= $config["oninout"];
				
				$data["password"]		= $config["password"];
				$data["deleted"]		= 0;
				
				$newid = sqlCreate(array("table"=>"users", "fields"=>$data));
				if($newid) {
					return $newid;
				}
			}
			
			function updateUser($config) {
				$data["username"]		= $config["username"];
				$data["firstname"]	= $config["firstname"];
				$data["lastname"]		= $config["lastname"];
				$data["position"]		= $config["position"];
				
				$data["extension"]	= $config["extension"];
				$data["cell"]				= $config["cell"];
				$data["email"]			= $config["email"];
				
				$data["deptid"]			= $config["deptid"];
				
				if($_SESSION["isit"]) {
					$data["isit"]				= $config["isit"];
					$data["isgeneric"]	= $config["isgeneric"];
					$data["isplace"]		= $config["isplace"];
					$data["ismanager"]	= $config["ismanager"];
					$data["issuper"]		= $config["issuper"];
					$data["isfleetper"]	= $config["isfleetper"];
					$data["isdamage"]		= $config["isdamage"];
					if($config["isadmin"]) {
						$data["isadmin"]	= $config["isadmin"];
					}
					$data["canlogin"]		= $config["canlogin"];
					$data["oninout"]		= $config["oninout"];
				}
				
				if($config["password"]) {
					$data["password"] = $config["password"];
				}
				sqlCommit(array("table"=>"users", "where"=>"personid=".$config["personid"], "fields"=>$data));
			}
			
			function commitDeleteUser($config) {
				$data["deleted"]		= date("U");
				
				sqlCommit(array("table"=>"users", "where"=>"personid=".$config["personid"], "fields"=>$data));
			}
			
			function userBirthday($personid, $birth) {
				$data["date"]			= $birth["month"]."/".$birth["day"]."/2000";
				$data["date"]			= strtotime($data["date"]);
				$data["datetype"]	= "birthday";
				$data["userid"]		= $personid;
				
				$original = sqlPull(array("table"=>"userdates", "where"=>"userid=".$personid, "onerow"=>"1"));
				
				if($original) {
					if($original["date"]!=$data["date"]) {
						sqlCommit(array("table"=>"userdates", "where"=>"userid=".$personid." AND datetype='birthday'", "fields"=>$data));
					}
				} else {
					sqlCreate(array("table"=>"userdates", "fields"=>$data));
				}
			}
		// }
	
		// User Status functions { 
			function createUserAction($config) {
				print("Creating User Status");
				
				$data["action"]		= $config["action"];
				$data["colour"]		= $config["colour"];
				$data["callme"]		= $config["callme"];
				
				sqlCreate(array("table"=>"useractions", "fields"=>$data));
			}
			
			function commitUserAction($config) {
				$data["action"]		= $config["actionname"];
				$data["colour"]		= $config["actioncolour"];
				$data["callme"]		= $config["callme"];
				$data["deleted"]	= $config["deleted"];
				
				sqlCommit(array("table"=>"useractions", "where"=>"id=".$config["id"], "fields"=>$data));
			}
		// }
	
		// Driver Functions {
			function createDriver($config) {
				$data["firstname"]	= $config["firstname"];
				$data["lastname"]		= $config["lastname"];
				$data["idno"]				= $config["idno"];
				$data["staffno"]		= $config["staffno"];
				$data["fleetid"]		= $config["fleetid"];
				$data["cell"]				= $config["cell"];
				$data["birthday"]		= $config["birthday"];
				$data["deleted"]		= $config["active"];
				
				$newid = sqlCreate(array("table"=>"drivers", "fields"=>$data));
				if($newid) {
					return $newid;
				}
			}
			
			function updateDriver($config) {
				$data["firstname"]	= $config["firstname"];
				$data["lastname"]		= $config["lastname"];
				$data["idno"]				= $config["idno"];
				$data["staffno"]		= $config["staffno"];
				$data["fleetid"]		= $config["fleetid"];
				$data["cell"]				= $config["cell"];
				$data["birthday"]		= $config["birthday"];
				$data["deleted"]		= $config["active"];
				
				sqlCommit(array("table"=>"drivers", "fields"=>$data, "where"=>"id=".$config["driverid"]));
			}
			
			function updateDriverAction($source, $sourceid, $driverid, $action) {
				$data["source"]				= $source;
				$data["sourceid"]			= $sourceid;
				$data["driverid"]			= $driverid;
				
				$data["action"]				= $action;
				
				$currentaction	= sqlPull(array("table"=>"driver_actions", "where"=>"source=".$source." AND sourceid=".$sourceid, "onerow"=>1));
				
				if($currentaction) {
					sqlCommit(array("table"=>"driver_actions", "fields"=>$data, "where"=>"id=".$currentaction["id"]));
				} else {
					$data["createdby"]		= $_SESSION["userid"];
					$data["createddate"]	= date("U");
					
					sqlCreate(array("table"=>"driver_actions", "fields"=>$data));
				}
			}
		// }
	
		// Candidates functions {
			// Candidate Functions {
				function createCandidate($config) {
					$data["firstname"]	= $config["firstname"];
					$data["lastname"]		= $config["lastname"];
					$data["idno"]				= $config["idno"];
					$data["contactno"]	= $config["contactno"];
					$data["statusid"]		= $config["status"];
					
					$newid = sqlCreate(array("table"=>"candidates", "fields"=>$data));
					if($newid) {
						return $newid;
					}
				}
				
				function updateCandidate($config) {
					$data["firstname"]	= $config["firstname"];
					$data["lastname"]		= $config["lastname"];
					$data["idno"]				= $config["idno"];
					$data["contactno"]	= $config["contactno"];
					$data["statusid"]		= $config["status"];
					
					sqlCommit(array("table"=>"candidates", "where"=>"id=".$config["candidateid"], "fields"=>$data));
				}
			// }
		
			function updateEvents($config) {
				//sqlDelete(array("table"=>"candidate_events", "where"=>"candidateid=".$config["candidateid"]));
				print($config["candidateid"]."<br>");
				
				foreach ($config["event"] as $eventkey=>$eventval) {
					$data["candidateid"]		= $config["candidateid"];
					$data["event"]			= $eventkey;
					
					// Data preparation {
						if($eventval["passed"] == "on") {
							$data["passed"]			= 1;
						} else {
							$data["passed"]			= 0;
						}
						
						if($eventval["attended"] == "on") {
							$data["attended"]		= 1;
						} else {
							$data["attended"]		= 0;
						}
						
						if($eventval["date"]) {
							$datearray = explode("/", $eventval["date"]);
							$date = $datearray[1]."/".$datearray[0]."/".$datearray[2];
							
							$data["date"]			= strtotime($date);
						} else {
							$data["date"]			= 0;
						}
					// }
					
					if($eventval["id"]) {
						sqlCommit(array("table"=>"candidate_events", "where"=>"id=".$eventval["id"], "fields"=>$data));
					} else {
						sqlCreate(array("table"=>"candidate_events", "fields"=>$data));
					}
				}
			}
			
			// Status Functions {
				function createStatusType($config) {
					$data["name"]	= $config["statusname"];
					$data["code"]	= $config["statuscode"];
					
					sqlCreate(array("table"=>"candidate_status", "fields"=>$data));
				}
				
				function updateStatusType($config) {
					$data["name"]	= $config["statusname"];
					$data["code"]	= $config["statuscode"];
					sqlCommit(array("table"=>"candidate_status", "where"=>"id=".$config["id"], "fields"=>$data));
				}
			// }
		// }
	
		// Learner functions {
			function createLearner($config) {
				$data["firstname"]		= $config["firstname"];
				$data["middlename"]		= $config["middlename"];
				$data["lastname"]			= $config["lastname"];
				$data["idno"]					= $config["idno"];
				$data["contactno"]		= $config["contactno"];
				$data["birthdate"]		= $config["birthdate"];
				$data["interview"]		= $config["interview"];
				$data["gradecode"]		= $config["gradecode"];
				$data["licencecode"]	= $config["licencecode"];
				$data["dover"]				= $config["dover"];
				$data["medical"]			= $config["medical"];
				$data["fingerprint"]	= $config["fingerprint"];
				
				$data["street"]				= $config["address"]["street"];
				$data["city"]					= $config["address"]["city"];
				$data["province"]			= $config["address"]["province"];
				$data["postalcode"]		= $config["address"]["postalcode"];
				
				$data["statusid"]			= $config["status"];
				
				$newid = sqlCreate(array("table"=>"learners", "fields"=>$data));
				if($newid) {
					return $newid;
				}
			}
			
			function updateLearner($config) {
				$data["firstname"]		= $config["firstname"];
				$data["middlename"]		= $config["middlename"];
				$data["lastname"]			= $config["lastname"];
				$data["idno"]					= $config["idno"];
				$data["contactno"]		= $config["contactno"];
				$data["birthdate"]		= $config["birthdate"];
				$data["interview"]		= $config["interview"];
				$data["gradecode"]		= $config["gradecode"];
				$data["licencecode"]	= $config["licencecode"];
				$data["dover"]				= $config["dover"];
				$data["medical"]			= $config["medical"];
				$data["fingerprint"]	= $config["fingerprint"];
				
				$data["street"]				= $config["address"]["street"];
				$data["city"]					= $config["address"]["city"];
				$data["province"]			= $config["address"]["province"];
				$data["postalcode"]		= $config["address"]["postalcode"];
				
				$data["statusid"]			= $config["status"];
				
				sqlCommit(array("table"=>"learners", "where"=>"id=".$config["learnerid"], "fields"=>$data));
			}
		// }
	// }
	
	// Fleet functions {
		function createFleet($config) {
			$data["name"]		= $config["name"];
			
			if($config["deleted"] == 0) {
				sqlCreate(array("table"=>"fleets", "fields"=>$data));
			}
		}
		
		function updateFleet($config) {
			$data["name"]	= $config["name"];
			
			if($config["deleted"] == 1) {
				$data["deleted"]	= date("U");
			}
			
			sqlCommit(array("table"=>"fleets", "where"=>"id=".$config["faultid"], "fields"=>$data));
		}
	// }
	
	// User History functions {
    /** testUserRecordForHistory($userid)
      * test a user record for a userhistory record that matches today
      * @author Feighen Oosterbroek
      * @author feighen@manlinegroup.com
      * @param int $userid which user are we testing against
      * @return array result
    */
    function testUserRecordForHistory($userid)
    {
      $manager = new TableManager("userhistory");
      $manager->setWhere(
        $manager->quoteString("`userhistory`.`userid`=?", $userid)
        );
      $manager->setOrderBy(array(
        "column"=>"`userhistory`.`date`",
        "direction"=>"DESC"
      ));
      $history = $manager->selectMultiple();
      /** Explanation
        * one of 3 potential tests need to match
        * 1) today is between from and to date
        * 2) date added is today with no from to dates
        * 3) last added item to match
      */
      $hist = (array)array();
      if ($history) {
        $dateU = date("U");
        foreach ($history as $key=>$val) {
          if ($val["fromdate"] || $val["todate"]) { ## item 1
            if (($dateU >= $val["fromdate"]) && ($dateU < $val["todate"])) {
              $hist = $val;
              break;
            } elseif (($val["todate"] == 0) && ($dateU >= $val["fromdate"])) {
              $hist = $val;
              break;
            } elseif (($val["fromdate"] == 0) && ($dateU < $val["todate"])) {
              $hist = $val;
              break;
            } else {
              $hist["actionid"] = 0;
            }
          } elseif (date("Y-m-d") == date("Y-m-d", $val["date"])) {  ## item 2
            $hist = $val;
            break;
          }
        }
        if (!$hist) { ## item 3
          $keys = array_keys($history);
          $hist = $history[$keys[0]];
        }
      }
      return $hist;
    }
	
		function updateUserHistory($config) {
			if ($config["userid"]) {$data["userid"] = $config["userid"];}
			if ($config["actionid"]) {$data["actionid"] = $config["actionid"];}
			if ($config["comment"]) {$data["comment"] = $config["comment"];}
			if ($config["fromdate"]) {$data["fromdate"] = $config["fromdate"];}
			if ($config["todate"]) {$data["todate"] = $config["todate"];}
			$data["date"] = date("U");
			
			/* if($done = sqlCreate(array("table"=>"userhistory", "fields"=>$data))) {
				$available = sqlPull(array("table"=>"useractions", "where"=>"id=".$config["actionid"], "onerow"=>"1"));
				$userdata["available"] = $available["callme"];
				
				sqlCommit(array("table"=>"users", "where"=>"personid=".$config["userid"], "fields"=>$userdata));
			} */
			$userhistory = new TableManager("userhistory");
			$function = (string)"insert";
			if ($config["id"]) {   ## we are updating a record
			  $userhistory->setWhere(
			    $userhistory->quoteString("`userhistory`.`id`=?", intval($config["id"]))
			  );
			  $function = "update";
			}
			if (($userhistory->$function($data)) === false) {
			  throw new man_exception("Could not successfully query the database");
			}
			$actions = new TableManager("useractions");
			$actions->setWhere(
			  $actions->quoteString("`useractions`.`id`=?", $config["actionid"])
			);
			$action = $actions->selectSingle();
			$users = new TableManager("users");
			$users->setWhere(
			  $users->quoteString("`users`.`personid`=?", $config["userid"])
		  );
		  $data = (array)array(
		    "available"=>$action["callme"]
		  );
		  $users->update($data);
		}
	// }
	
	// Dashboard functions {
		// Greenmile functions {
			function createGreenmileScore($data) {
				sqlCreate(array("table"=>"greenmile_scores", "fields"=>$data));
			}
			
			function updateGreenmileScore($data) {
				sqlCommit(array("table"=>"greenmile_scores", "where"=>"date=".$data["date"], "fields"=>$data));
			}
		// }
		
		function commitPositionScore($config, $match) {
			$data	= $config;
			
			if($match == 0) {
				sqlCreate(array("table"=>"position_scores", "fields"=>$data));
			} else {
				sqlCommit(array("table"=>"position_scores", "fields"=>$data, "where"=>"id=".$match));
			}
		}
		
		function createMyDashboard($config) {
			$newid = sqlCreate(array("table"=>"user_dashboards", "fields"=>$config));
		}
		
		function commitMyDashboard($config) {
			sqlCommit(array("table"=>"user_dashboards", "where"=>"userid=".$config["userid"], "fields"=>$config));
		}
	// }
	
	// Fault functions {
		// Fault Type functions {
			function createFaultType($config) {
				$data["name"]		= $config["name"];
				$data["system"]	= $config["system"];
				
				if($config["deleted"] == 0) {
					sqlCreate(array("table"=>"fault_types", "fields"=>$data));
				}
			}
			
			function updateFaultType($config) {
				$data["name"]	= $config["name"];
				
				if($config["deleted"] == 1) {
					$data["deleted"]	= date("U");
				}
				
				sqlCommit(array("table"=>"fault_types", "where"=>"id=".$config["id"], "fields"=>$data));
			}
		// }
	
		// Driver Fault functions {
			function createDriverFault($config) {
				$data["driverid"]			= $config["driverid"];
				$data["drivername"]		= $config["drivername"];
				$data["drivernumber"]	= $config["drivernumber"];
				$data["trucknumber"]	= $config["trucknumber"];
				$data["faultid"]			= $config["faulttype"];
				$data["assignedid"]		= $config["assignedid"];
				
				$data["createdby"]		= $_SESSION["userid"];
				$data["createdtime"]	= date("U");
				$data["editedby"]			= $_SESSION["userid"];
				$data["editedtime"]		= date("U");
				
				$data["eventtype"]			= $config["eventtype"];
				if($data["eventtype"] == 0) {
					$data["eventdate"]	= 0;
				} else if($data["eventtype"] == 1) {
					$data["eventdate"]	= $config["eventmonth"];
				} else if($data["eventtype"] == 2) {
					$data["eventdate"]	= $config["eventday"];
				}
				
				$faultid	= sqlCreate(array("table"=>"dfs_faults", "fields"=>$data));
				return($faultid);
			}
			
			function saveDriverFault($config) {
				$data["driverid"]			= $config["driverid"];
				$data["drivername"]		= $config["drivername"];
				$data["drivernumber"]	= $config["drivernumber"];
				$data["trucknumber"]	= $config["trucknumber"];
				$data["faultid"]			= $config["faulttype"];
				$data["assignedid"]		= $config["assignedid"];
				
				$data["editedby"]			= $_SESSION["userid"];
				$data["editedtime"]		= date("U");
				
				$data["eventtype"]			= $config["eventtype"];
				if($data["eventtype"] == 0) {
					$data["eventdate"]	= 0;
				} else if($data["eventtype"] == 1) {
					$data["eventdate"]	= $config["eventmonth"];
				} else if($data["eventtype"] == 2) {
					$data["eventdate"]	= $config["eventday"];
				}
				
				if($config["resolved"] == 1) {
					$data["resolvedtime"]		= date("U");
					$data["resolvedby"]			= $_SESSION["userid"];
				}
				
				sqlCommit(array("table"=>"dfs_faults", "where"=>"id=".$config["faultid"], "fields"=>$data));
			}
			
			function createDriverFaultAssignee($driverfaultid, $assignedid) {
				$data["faultid"]		= $driverfaultid;
				$data["assignedid"]	= $assignedid;
				$data["sourceid"]		= $_SESSION["userid"];
				$data["date"]				= date("U");
				$data["system"]			= 2;
				
				$faultid	= sqlCreate(array("table"=>"assignedlogs", "fields"=>$data));
			}
		// }
	
		// Equip Fault functions {
			function createEquipFault($config) {
				$data["driverid"]			= $config["driverid"];
				$data["drivernumber"]	= $config["drivernumber"];
				$data["trucknumber"]	= $config["trucknumber"];
				$data["reqno"]				= $config["reqno"];
				$data["faultid"]			= $config["faulttype"];
				$data["assignedid"]		= $config["assignedid"];
				$data["cost"]					= $config["cost"];
				
				$data["createdby"]		= $_SESSION["userid"];
				$data["createdtime"]	= date("U");
				$data["editedby"]			= $_SESSION["userid"];
				$data["editedtime"]		= date("U");
				
				$faultid	= sqlCreate(array("table"=>"equip_faults", "fields"=>$data));
				return($faultid);
			}
			
			function saveEquipFault($config) {
				$data["driverid"]			= $config["driverid"];
				$data["drivernumber"]	= $config["drivernumber"];
				$data["trucknumber"]	= $config["trucknumber"];
				$data["reqno"]				= $config["reqno"];
				$data["faultid"]			= $config["faulttype"];
				$data["assignedid"]		= $config["assignedid"];
				$data["cost"]					= $config["cost"];
				
				$data["editedby"]			= $_SESSION["userid"];
				$data["editedtime"]		= date("U");
				
				if($config["resolved"] == 1) {
					$data["resolvedtime"]		= date("U");
					$data["resolvedby"]			= $_SESSION["userid"];
				}
				
				sqlCommit(array("table"=>"equip_faults", "where"=>"id=".$config["faultid"], "fields"=>$data));
			}
			
			function createEquipFaultAssignee($equipfaultid, $assignedid) {
				$data["faultid"]		= $equipfaultid;
				$data["assignedid"]	= $assignedid;
				$data["sourceid"]		= $_SESSION["userid"];
				$data["date"]				= date("U");
				$data["system"]			= 3;
				
				$faultid	= sqlCreate(array("table"=>"assignedlogs", "fields"=>$data));
			}
		// }
	
		// Fault System functions {
			function createFault($config) {
				$data["sourceid"]		= $config["sourceid"];
				$data["itid"]				= $config["itid"];
				$data["typeid"]			= $config["typeid"];
				$data["comment"]		= $config["comment"];
				$data["complete"]		= 0;
				
				$data["date"]				= date("U");
				
				sqlCreate(array("table"=>"fs_faults", "fields"=>$data));
			}
			
			function addFaultAction($config) {
				$data["date"]			= date("U");
				$data["comment"]	= $config["faultaction"];
				$data["faultid"]	= $config["faultid"];
				$data["itid"]			= $_SESSION["userid"];
				
				sqlCreate(array("table"=>"fs_actions", "fields"=>$data));
			}
			
			function saveFaultEdit($faultid, $date, $baddata) {
				$data["complete"]	= $date;
				$data["baddata"]	= $baddata;
				
				sqlCommit(array("table"=>"fs_faults", "where"=>"id=".$faultid, "fields"=>$data));
			}
		// }
		
		// Unit Fault functions {
			function createUfFault($config) {
				$data["unittypeid"]		= $config["unittype"];
				$data["unitdetail"]		= $config["unitdetail"];
				$data["actionid"]			= $config["actionid"];
				$data["truckid"]			= $config["truckid"];
				$data["createdtime"]	= $config["createdtime"];
				$data["createdby"]		= $_SESSION["userid"];
				$data["editedtime"]		= $config["createdtime"];
				$data["editedby"]			= $_SESSION["userid"];
				if($config["resolvedtime"] > 0) {
					$data["resolvedtime"]	=	$config["resolvedtime"];
					$data["resolvedby"]		=	$_SESSION["userid"];
				}
				
				$newid = sqlCreate(array("table"=>"uf_faults", "fields"=>$data));
				if($newid) {
					return $newid;
				}
			}
			
			function updateUfFault($config) {
				$data["unittypeid"]		= $config["unittype"];
				$data["unitdetail"]		= $config["unitdetail"];
				$data["actionid"]			= $config["actionid"];
				$data["truckid"]			= $config["truckid"];
				$data["editedtime"]		= $config["editedtime"];
				$data["editedby"]			= $_SESSION["userid"];
				if($config["resolvedtime"] > 0) {
					$data["resolvedtime"]	=	$config["resolvedtime"];
					$data["resolvedby"]		=	$_SESSION["userid"];
				}
				
				sqlCommit(array("table"=>"uf_faults", "where"=>"id=".$config["faultid"], "fields"=>$data));
			}
			
			function createUfComment($userid, $faultid, $comment) {
				$data["note"]					= $comment;
				$data["userid"]				= $userid;
				$data["faultid"]			= $faultid;
				$data["createdtime"]	= date("U");
				
				sqlCreate(array("table"=>"uf_notes", "fields"=>$data));
			}
			
			// Actions and Unit Types {
				function createUfAction($config) {
					$data["name"]	= $config["actionname"];
					
					sqlCreate(array("table"=>"uf_actions", "where"=>"id=".$config["actionid"], "fields"=>$data));
				}
				
				function updateUfAction($config) {
					$data["name"]			= $config["actionname"];
					$data["deleted"]	= $config["deleted"];
					
					sqlCommit(array("table"=>"uf_actions", "where"=>"id=".$config["actionid"], "fields"=>$data));
				}
				
				function createUfUnit($config) {
					$data["name"]	= $config["unitname"];
					
					sqlCreate(array("table"=>"uf_unittypes", "where"=>"id=".$config["unitid"], "fields"=>$data));
				}
				
				function updateUfUnit($config) {
					$data["name"]			= $config["unitname"];
					$data["deleted"]	= $config["deleted"];
					
					sqlCommit(array("table"=>"uf_unittypes", "where"=>"id=".$config["unitid"], "fields"=>$data));
				}
			// }
		// }
	// }
	
	// MyCaps Functions {
		// Task functions {
			function createMyCapsTask($config) {
				$data["segmentid"]		= $config["segmentid"];
				$data["name"]					= $config["taskname"];
				if($config["taskdesc"] == -1) {
					$data["details"]	= null;
				} else {
					$data["details"]			= $config["taskdesc"];
				}
				if($config["duedate"] > 1) {
					$data["duedate"]		= $config["duedate"];
				} else if($config["duedate"] == 0) {
					$data["duedate"]		= 0;
				}
				$data["goalid"]				= $config["goalid"];
				if($config["startdate"]) {
					$data["startdate"]	= $config["startdate"];
				} else {
					$data["startdate"]	= date("U");
				}
				if($config["measurement"] == -1) {
					$data["measurement"]	= null;
				} else {
					$data["measurement"]	= $config["measurement"];
				}	
				$data["maintaskid"]		= $config["maintaskid"];
				if($config["statusid"]) {
					$data["statusid"] = $config["statusid"];
				}
				if($config["tasktype"]) {
					$data["type"]		= $config["tasktype"];
				}
				
				$newid = sqlCreate(array("table"=>"mycaps_tasks", "fields"=>$data));
				if($newid) {
					return $newid;
				}
			}
			
			function commitMyCapsTask($config) {
				if($config["taskname"]) {
					$data["name"]					= $config["taskname"];
				}
				if($config["taskdesc"] == -1) {
					$data["details"]	= null;
				} else {
					$data["details"]			= $config["taskdesc"];
				}
				if($config["goalid"]) {
					$data["goalid"]				= $config["goalid"];
				}
				if($config["duedate"] > 1) {
					$data["duedate"]		= $config["duedate"];
				} else if($config["duedate"] == 0) {
					$data["duedate"]		= 0;
				}
				if($config["measurement"] == -1) {
					$data["measurement"]	= null;
				} else {
					$data["measurement"]	= $config["measurement"];
				}
				
				$data["maintaskid"]		= $config["maintaskid"];
				$data["statusid"]			= $config["statusid"];
				
				if($config["lastedited"] == 1) {
					$data["lastedited"]	= date("U");
				}
				if($config["deleted"] == 1) {
					$data["deleted"]		= date("U");
				}
				if($config["tasktype"]) {
					$data["type"]		= $config["tasktype"];
				}
				
				sqlCommit(array("table"=>"mycaps_tasks", "where"=>"id=".$config["taskid"], "fields"=>$data));
			}
		// }
		
		// Segment functions {
			function createMyCapsSegment($config) {
				$data["userid"]			= $config["userid"];
				$currentdate				= date("m")."/".date("d")."/".date("Y");
				$currentdate				= strtotime($currentdate);
				$data["startdate"]	= $currentdate;
				$data["enddate"]		= $config["enddate"];
				$data["finalized"]	= 0;
				
				$newid = sqlCreate(array("table"=>"mycaps_segments", "fields"=>$data));
				if($newid) {
					return $newid;
				}
			}
			
			function commitMyCapsSegment($config) {
				if($config["finalized"] == 1) {
					$data["finalized"]	= date("U");
				}
				if($config["enddate"] > 0) {
					$data["enddate"]	= $config["enddate"];
				}
				
				sqlCommit(array("table"=>"mycaps_segments", "fields"=>$data, "where"=>"id=".$config["segmentid"]));
			}
		// }
		
		// Goal functions {
			function createMyCapsGoal2($config) {
				sqlCreate(array("table"=>"mycaps_goals", "fields"=>$config));
			}
			
			function editMyCapsGoal2($goalid, $config) {
				sqlCommit(array("table"=>"mycaps_goals", "fields"=>$config, "where"=>"id=".$goalid));
			}
			
			function createMyCapsGoal($userid, $config) {
				$data["userid"]		= $userid;
				$data["name"]			= $config["goalname"];
				$data["priority"]	= $config["priority"];
				
				sqlCreate(array("table"=>"mycaps_goals", "fields"=>$data));
			}
			
			function editMyCapsGoal($config) {
				$data["name"]				= $config["goalname"];
				$data["priority"]		= $config["priority"];
				
				sqlCommit(array("table"=>"mycaps_goals", "fields"=>$data, "where"=>"id=".$config["goalid"]));
			}
			
			function deleteMyCapsGoal($config) {
				$data["deleted"]		= date("U");
				
				sqlCommit(array("table"=>"mycaps_goals", "fields"=>$data, "where"=>"id=".$config["goalid"]));
			}
		// }
		
		// Generate MyCaps Appointment {
			function createMyCapsAppoint($userdetails,$reminder) {
				// Sender  details {
					$from_name		= "MyCaps System";
					$from_address	= "justinw@manlinegroup.com";
				// }
				
				// Convert MYSQL datetime and construct iCal start, end and issue dates {
					$dtstart= gmdate("Ymd\T110000\Z",$reminder["duedate"]);
					$dtend= gmdate("Ymd\T120000\Z",($reminder["duedate"]));
					$todaystamp = gmdate("Ymd\T110000\Z");
				// }
				
				//Create Mime Boundry
				$mime_boundary = "----Meeting Booking----".md5(time());
				
				// Letter details and Email headers {
					$subject			= "Deadline for task ".$reminder["desc"]; //Doubles as email subject and meeting subject in calendar
					
					$meeting_description = "Here is a brief description of my meeting\n\n";
					$meeting_location = "My Office"; //Where will your meeting take place
					
					$headers = "From: ".$from_name." <".$from_address.">\n";
					//$headers .= "Reply-To: ".$from_name." <".$from_address.">\n";
					
					$headers .= "MIME-Version: 1.0\n";
					$headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
					$headers .= "Content-class: urn:content-classes:calendarmessage\n";
				// }
				
				//Create Email Body (HTML) {
					$message .= "--$mime_boundary\n";
					$message .= "Content-Type: text/html; charset=UTF-8\n";
					$message .= "Content-Transfer-Encoding: 8bit\n\n";
					
					$message .= "<html>\n";
					$message .= "<body>\n";
					$message .= '<p>Dear '.$userdetails["firstname"].' '.$userdetails["lastname"].',</p>';
					$message .= '<p>Would you like to add a calender event for your MyCaps task?</p>';
					$message .= "<p>The due date for this task is ".date("d M Y", $reminder["duedate"])."</p>";    
					$message .= "</body>\n";
					$message .= "</html>\n";
					$message .= "--$mime_boundary\n";
				// }
				
				$ical = "BEGIN:VCALENDAR\n";
				$ical	.= "PRODID:Zimbra-Calendar-Provider\n";
				$ical	.= "VERSION:2.0\n";
				$ical	.= "METHOD:REQUEST\n";
				$ical	.= "BEGIN:VEVENT\n";
				$ical	.= "UID:MANLINE".date("U")."\n";
				$ical	.= "SUMMARY:Deadline for ".$reminder["desc"]."\n";
				$ical	.= "LOCATION:PC\n";
				$ical	.= "ORGANIZER;CN=".$userdetails["firstname"]." ".$userdetails["lastname"].";ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=FALSE\n";
				$ical	.= ":mailto:".$userdetails["email"]."\n";
				$ical	.= "DTSTART:".$dtstart."\n";
				$ical	.= "DTEND:".$dtend."\n";
				$ical	.= "STATUS:CONFIRMED\n";
				$ical	.= "CLASS:PUBLIC\n";
				$ical	.= "X-MICROSOFT-CDO-INTENDEDSTATUS:BUSY\n";
				$ical	.= "TRANSP:OPAQUE\n";
				$ical	.= "X-MICROSOFT-DISALLOW-COUNTER:TRUE\n";
				$ical	.= "DTSTAMP:".date("Ymd\Thms\Z")."\n";
				$ical	.= "SEQUENCE:0\n";
				$ical	.= "DESCRIPTION: BLAH\n";
				$ical	.= "BEGIN:VALARM\n";
				$ical	.= "ACTION:DISPLAY\n";
				$ical	.= "TRIGGER;RELATED=START:-PT5M\n";
				$ical	.= "DESCRIPTION:Reminder\n";
				$ical	.= "END:VALARM\n";
				$ical	.= "END:VEVENT\n";
				$ical	.= "END:VCALENDAR";
				
				$message .= "Content-Type: text/calendar;name='meeting.ics';method=REQUEST\n";
				$message .= "Content-Transfer-Encoding: 8bit\n\n";
				$message .= $ical;            
				
				//SEND MAIL
				require_once(BASE."basefunctions/baseapis/communications/Email.class.php");
				$email = new Email();
				$email->setBody($message, true);
				$email->setHeaders($headers);
				$email->setTo(str_replace("\"'", "", $userdetails["email"]));
				$email->setSubject($subject);
				if (($email->send()) === false) {
					return false;
				}
				return true;
			}
		// }
	// }
	
	// M3 System functions {
		function clearM3Score($config) {
			$where = "month = ".$config["month"]." AND year = ".$config["year"]." AND catid = ".$config["catid"];
			
			sqlDelete(array("table"=>"m3_scores", "where"=>$where));
		}
		
		function createM3Score($config) {
			$data["month"]	= $config["month"];
			$data["year"]		= $config["year"];
			$data["catid"]	= $config["catid"];
			$data["score"]	= $config["score"];
			
			sqlCreate(array("table"=>"m3_scores", "fields"=>$data));
		}
		
		function createM3Dept($config) {
			$data["name"]	= $config["name"];
			$data["display"]	= $config["display"];
			
			sqlCreate(array("table"=>"m3_departments", "fields"=>$data));
		}
		
		function updateM3Dept($config) {
			$data["name"]			= $config["name"];
			$data["display"]	= $config["display"];
			
			sqlCommit(array("table"=>"m3_departments", "where"=>"id=".$config["id"], "fields"=>$data));
		}
		
		function createM3Cat($deptid, $config) {
			$data["name"]			= $config["catname"];
			$data["weight"]		= $config["weight"];
			$data["deptid"]		= $deptid;
			$data["deleted"]	= 0;
			
			sqlCreate(array("table"=>"m3_categories", "fields"=>$data));
		}
		
		function updateM3Cat($config) {
			$data["name"]			= $config["catname"];
			$data["weight"]		= $config["weight"];
			$data["deleted"]	= $config["delete"];
			
			sqlCommit(array("table"=>"m3_categories", "where"=>"id=".$config["catid"], "fields"=>$data));
		}
	// }
	
	// Rates functions {
		function createRateDetails($config) {
			$data["date"]							= date("U");
			
			$data["driverctcmin"]			= $config["driverctcmin"];
			$data["driverctcmax"]			= $config["driverctcmax"];
			
			$data["truckcost"]				= $config["truck"]["cost"];
			$data["truckresidual"]		= $config["truck"]["residual"];
			$data["trucklicence"]			= $config["truck"]["licence"];
			$data["truckmonths"]			= $config["truck"]["months"];
			
			$data["trailercost"]			= $config["trailer"]["cost"];
			$data["trailerresidual"]	= $config["trailer"]["residual"];
			$data["trailerlicence"]		= $config["trailer"]["licence"];
			$data["trailermonths"]		= $config["trailer"]["months"];
			
			$data["maxwriteoff"]			= $config["maxwriteoff"];
			$data["diesel"]						= $config["diesel"];
			
			$data["cpktoll"]					= $config["cpk"]["toll"];
			$data["cpkdriver"]				= $config["cpk"]["driver"];
			$data["cpktruck"]					= $config["cpk"]["truck"];
			$data["cpktrailer"]				= $config["cpk"]["trailer"];
			$data["cpktyre"]					= $config["cpk"]["tyre"];
			$data["cpkabuse"]					= $config["cpk"]["abuse"];
			$data["cpkexcess"]				= $config["cpk"]["excess"];
			$data["cpkdamages"]				= $config["cpk"]["damages"];
			$data["cpktarps"]					= $config["cpk"]["tarps"];
			
			$data["interestrate"]			= $config["interestrate"];
			$data["fixedcosts"]				= $config["fixedcosts"];
			
			$data["netmargin"]				= $config["netmargin"];
			
			$data["git"]							= $config["fixed"]["git"];
			$data["dpip"]							= $config["fixed"]["dpip"];
			$data["phones"]						= $config["fixed"]["phones"];
			$data["tracking"]					= $config["fixed"]["tracking"];
			$data["admin"]						= $config["fixed"]["admin"];
			
			sqlCreate(array("table"=>"rate_details", "fields"=>$data));
		}
		
		function createRateDetails2($config) {
			$data["trailerid"]				= $config["trailerid"];
			$data["date"]							= date("U");
			
			$data["driverctcmin"]			= $config["driverctc"];
			$data["driverctcmax"]			= $config["driverctcmax"];
			
			$data["truckcost"]				= $config["truckcost"];
			$data["truckextra"]				= $config["truckextra"];
			$data["truckresidual"]		= $config["truckresidual"];
			$data["truckmaxmonths"]		= $config["truckmaxmonths"];
			$data["trucklicence"]			= $config["trucklicence"];
			
			$data["trailercost"]			= $config["trailercost"];
			$data["trailerresidual"]	= $config["trailerresidual"];
			$data["trailerlicence"]		= $config["trailerlicence"];
			$data["trailermonths"]		= $config["trailermonths"];
			
			$data["maxwriteoff"]			= $config["maxwriteoff"];
			$data["diesel"]						= $config["diesel"];
			
			$data["cpktoll"]					= $config["cpktoll"];
			$data["cpkdriver"]				= $config["cpkdriver"];
			$data["cpktruck"]					= $config["cpktruck"];
			$data["cpktrailer"]				= $config["cpktrailer"];
			$data["cpktyre"]					= $config["cpktyre"];
			$data["cpkabuse"]					= $config["cpkabuse"];
			$data["cpkexcess"]				= $config["cpkexcess"];
			$data["cpkdamages"]				= $config["cpkdamages"];
			$data["cpktarps"]					= $config["cpktarps"];
			
			$data["interestrate"]			= $config["interest"];
			$data["fixedcosts"]				= $config["fixedcosts"];
			
			$data["netmargin"]				= $config["netmargin"];
			
			$data["git"]							= $config["git"];
			$data["dpip"]							= $config["dpip"];
			$data["phones"]						= $config["phones"];
			$data["tracking"]					= $config["tracking"];
			$data["admin"]						= $config["admin"];
			
			sqlCreate(array("table"=>"rate_details", "fields"=>$data));
		}
	// }
	
	// Rights Groups functions {
		function commitRightsGroup($config) {
			$data["name"]					= $config["name"];
			$data["description"]	= $config["desc"];
			
			if($config["groupid"]) {
				sqlCommit(array("table"=>"rights_groups", "where"=>"id=".$config["groupid"], "fields"=>$data));
			} else {
				sqlCreate(array("table"=>"rights_groups", "fields"=>$data));
			}
		}
		
		function commitPageRights($pagecode, $details) {
			$data["pagecode"]	= $pagecode;
			$data["groupid"]	= $details["groupid"];
			$data["level"]		= $details["access"];
			
			sqlCreate(array("table"=>"rights_pages", "fields"=>$data));
		}
		
		function commitUserRights($userid, $groups) {
			sqlDelete(array("table"=>"rights_users", "where"=>"userid = ".$userid));
			if($groups) {
				foreach ($groups as $grpkey=>$grpval) {
					$data["groupid"]	= $grpkey;
					$data["userid"]		= $userid;
					sqlCreate(array("table"=>"rights_users", "fields"=>$data));
				}
			}
		}
	// }
	
	// FPDF functions {
		require(BASE."/basefunctions/fpdf.php");
		class PDF extends FPDF {
			function pdfHeader($header, $spans) {
				for($i=0; $i<count($header); $i++) {
					$this->SetFillColor(200,200,200);
					$this->Cell($spans[$i],7 , $header[$i], 1, 0, 'C', 1);
				}
				$this->Ln();
			}
			
			function pdfLine($data, $spans, $height, $border) {
				$this->Cell($spans[0],$height,$data[0], $border);
				$this->Cell($spans[1],$height,$data[1], $border);
				$this->Cell($spans[2],$height,$data[2], $border, 0);
				$this->Cell($spans[3],$height,$data[3], $border, 1, 'R');
			}
			
			function pdfCandLine($data, $spans, $height, $border) {
				$this->SetFillColor(235,235,235);
				$this->Cell($spans[0],$height,$data[0], $border, 0, 'C', 1);
				$this->Cell($spans[1],$height,$data[1], $border, 0, 'R', 1);
				$this->Cell($spans[2],$height,$data[2], $border, 0, 'R', 1);
				$this->Cell($spans[3],$height,$data[3], $border, 1, 'C', 1);
				if($data[4] != 0) {
					$this->MultiCell($spans[4],6,$data[4], $border, 1, 'C');
				}
			}
		}
	// }
	
	// XML creation functions (For flashgraphs) {
		function generateLineGraph($filename, $layout, $values, $index=null, $graphkey=null, $link=0) {
			$filepath	= BASE."/images/flashxml/";
			
			$minrange	= $layout["xmin"];
			$maxrange	=	$layout["xmax"];
			
			if ($handle = fopen($filepath."/".$filename, "w+")) {
				fwrite($handle, "<chart>");
				
				fwrite($handle, "<license>J1XZ7W6WK.O91PA5T4Q79KLYCK07EK</license>");
				
				fwrite($handle, "<chart_type>line</chart_type>");
				fwrite($handle, "<chart_pref line_thickness='3' point_shape='circle' fill_shape='false' rotation_x='5' rotation_y='0' />");
				fwrite($handle, "<chart_value color='113311' background_color='FFFFFF' alpha='90' size='12' position='cursor' />");
				fwrite($handle, "<chart_border top_thickness='0' bottom_thickness='2' left_thickness='2' right_thickness='0' />");
				fwrite($handle, "<chart_transition type='dissolve' />");
				
				// Lines over the graph, cross and vertical { 
					fwrite($handle, "<chart_grid_h thickness='1' type='solid' />"); // Edit for vertical lines
					fwrite($handle, "<chart_grid_v thickness='1' type='solid' />"); // Edit for cross lines
				// }
				
				// Legend details {
					fwrite($handle, "<legend_label size='10' />");
					fwrite($handle, "<legend_transition type='slide_down' />");
					fwrite($handle, "<legend_rect fill_color='FFFFFF' fill_alpha='100' line_color='339900' line_alpha='100' line_thickness='1' />");
				// }
				
				// Axis details {
					fwrite($handle, "<axis_ticks value_ticks='true' category_ticks='true' minor_count='3' />");
					fwrite($handle, "<axis_category size='10' color='111111' alpha='90' orientation='diagonal_up' />");
					fwrite($handle, "<axis_value min='".$minrange."' max='".$maxrange."' size='20' color='111111' alpha='90' />");
				// }
				
				// Colours {
					fwrite($handle, "<series_color>");
					fwrite($handle, "<color>006600</color>");
					fwrite($handle, "<color>ff6600</color>");
					fwrite($handle, "<color>1166ff</color>");
					fwrite($handle, "<color>22AA22</color>");
					fwrite($handle, "<color>8866ff</color>");
					fwrite($handle, "<color>990099</color>");
					fwrite($handle, "<color>9999FF</color>");
					fwrite($handle, "<color>FF944C</color>");
					fwrite($handle, "<color>900000</color>");
					fwrite($handle, "<color>999999</color>");
					fwrite($handle, "<color>33CC00</color>");
					fwrite($handle, "<color>FF00FF</color>");
					fwrite($handle, "<color>FF0000</color>");
					fwrite($handle, "<color>807700</color>");
					fwrite($handle, "<color>00E6C5</color>");
					fwrite($handle, "<color>0000FF</color>");
					fwrite($handle, "<color>338499</color>");
					fwrite($handle, "<color>FF0101</color>");
					
					fwrite($handle, "</series_color>");
				// }
				
				fwrite($handle, "<chart_data>");
				// Index details {
					fwrite($handle, "<row>");
					fwrite($handle, "<string></string>");
					fwrite($handle, "<string></string>"); // Oth point on X axis
					if($index) {
						foreach ($index as $indexkey=>$indexval) {
							fwrite($handle, "<string>".$indexval."</string>");
						}
					}
					fwrite($handle, "<string></string>");
					fwrite($handle, "</row>");	
				// }
				
				// Plot values {
					foreach ($values as $linekey=>$lineval) {
						fwrite($handle, "<row>");
						fwrite($handle, "<string>".$lineval["title"]."</string>");
						fwrite($handle, "<null/>");
						foreach ($graphkey as $pointkey=>$pointval) {
							if($lineval["values"][$pointval]) {
								fwrite($handle, "<number>".$lineval["values"][$pointval]."</number>");
							} else {
								fwrite($handle, "<null />");
							}
						}
						
						fwrite($handle, "</row>");
					}
				// }
				fwrite($handle, "</chart_data>");
				
				// Links {
					if($link == 1) {
						// 'Button' drawings {
							fwrite($handle, "<draw>");
							
							fwrite($handle, "<rect x='5' y='5' width='10' height='10' fill_color='11BB11' />");
							fwrite($handle, "<text x='5' y='5'></text>");
							
							fwrite($handle, "</draw>");
						// }
						// Actual Link {
							fwrite($handle, "<link>");
							
							fwrite($handle, "<area x='5' ");
							fwrite($handle, "y='5' ");
							fwrite($handle, "width='10' ");
							fwrite($handle, "height='10' ");
							fwrite($handle, "url='javascript:flashGraphFunction()' ");
							//fwrite($handle, "target='_self' ");
							fwrite($handle, "text='Switch to numbers' ");
							fwrite($handle, "background_color='FFFFFF' ");
							fwrite($handle, "/>");
							
							fwrite($handle, "</link>");
						// }
					}
					// }
				// }
				
				fwrite($handle, "</chart>");
				
				fclose($handle);
			} else {
				print("Cannot open file (".$filename.")");
			}
		}
		
		function generateSmallLineGraph($filepath="", $filename, $values, $index=null, $graphkey=null, $link=0) {
			if ($handle = fopen($filepath."/".$filename, "w+")) {
				fwrite($handle, "<chart>");
				
				fwrite($handle, "<license>J1XZ7W6WK.O91PA5T4Q79KLYCK07EK</license>");
				
				fwrite($handle, "<chart_type>line</chart_type>");
				fwrite($handle, "<chart_pref line_thickness='3' point_shape='circle' fill_shape='false' rotation_x='5' rotation_y='0' />");
				fwrite($handle, "<chart_value color='113311' background_color='FFFFFF' alpha='90' size='12' position='cursor' />");
				fwrite($handle, "<chart_border top_thickness='0' bottom_thickness='2' left_thickness='2' right_thickness='0' />");
				fwrite($handle, "<chart_transition type='slide_left' />");
				fwrite($handle, "<chart_rect height='100' />");
				
				// Lines over the graph, cross and vertical { 
					fwrite($handle, "<chart_grid_h thickness='1' type='solid' />"); // Edit for vertical lines
					fwrite($handle, "<chart_grid_v thickness='1' type='solid' />"); // Edit for cross lines
				// }
				
				// Legend details {
					fwrite($handle, "<legend transition='slide_left' 
					 size='10' bullet='circle' /
					fill_color='FFFFFF' fill_alpha='100' line_color='339900' line_alpha='100' line_thickness='1' />");
					//fwrite($handle, "<legend_label>");
					//fwrite($handle, "<legend_rect fill_color='FFFFFF' fill_alpha='100' line_color='339900' line_alpha='100' line_thickness='1' />");
				// }
				
				// Axis details {
					fwrite($handle, "<axis_ticks value_ticks='true' category_ticks='true' minor_count='3' />");
					fwrite($handle, "<axis_category size='10' color='111111' alpha='90' orientation='diagonal_up' />");
					fwrite($handle, "<axis_value min='40' max='100' size='10' color='111111' alpha='90' />");
				// }
				
				// Colours {
					fwrite($handle, "<series_color>");
					fwrite($handle, "<color>006600</color>");
					fwrite($handle, "<color>ff6600</color>");
					fwrite($handle, "<color>1166ff</color>");
					fwrite($handle, "<color>22AA22</color>");
					fwrite($handle, "<color>8866ff</color>");
					fwrite($handle, "<color>990099</color>");
					fwrite($handle, "<color>9999FF</color>");
					fwrite($handle, "<color>FF944C</color>");
					fwrite($handle, "<color>900000</color>");
					fwrite($handle, "<color>999999</color>");
					fwrite($handle, "<color>33CC00</color>");
					fwrite($handle, "<color>FF00FF</color>");
					fwrite($handle, "<color>FF0000</color>");
					fwrite($handle, "<color>807700</color>");
					fwrite($handle, "<color>00E6C5</color>");
					fwrite($handle, "<color>0000FF</color>");
					fwrite($handle, "<color>338499</color>");
					fwrite($handle, "<color>FF0101</color>");
					
					fwrite($handle, "</series_color>");
				// }
				
				fwrite($handle, "<chart_data>");
				// Index details {
					fwrite($handle, "<row>");
					fwrite($handle, "<string></string>");
					fwrite($handle, "<string></string>"); // Oth point on X axis
					if($index) {
						foreach ($index as $indexkey=>$indexval) {
							fwrite($handle, "<string>".$indexval."</string>");
						}
					}
					fwrite($handle, "<string></string>");
					fwrite($handle, "</row>");	
				// }
				
				// Plot values {
					foreach ($values as $linekey=>$lineval) {
						fwrite($handle, "<row>");
						fwrite($handle, "<string>".$lineval["title"]."</string>");
						fwrite($handle, "<null/>");
						foreach ($graphkey as $pointkey=>$pointval) {
							if($lineval["values"][$pointval]) {
								fwrite($handle, "<number>".$lineval["values"][$pointval]."</number>");
							} else {
								fwrite($handle, "<null />");
							}
						}
						
						fwrite($handle, "</row>");
					}
				// }
				fwrite($handle, "</chart_data>");
				//fwrite($handle, "<chart_value position='outside' size='12' color='FF4400' alpha='100' />");
				
				// Links {
					if($link == 1) {
						// 'Button' drawings {
							fwrite($handle, "<draw>");
							
							fwrite($handle, "<rect x='5' y='5' width='10' height='10' fill_color='11BB11' />");
							fwrite($handle, "<text x='5' y='5'></text>");
							
							fwrite($handle, "</draw>");
						// }
						// Actual Link {
							fwrite($handle, "<link>");
							
							fwrite($handle, "<area x='5' ");
							fwrite($handle, "y='5' ");
							fwrite($handle, "width='10' ");
							fwrite($handle, "height='10' ");
							fwrite($handle, "url='javascript:flashGraphFunction()' ");
							//fwrite($handle, "target='_self' ");
							fwrite($handle, "text='Switch to numbers' ");
							fwrite($handle, "background_color='FFFFFF' ");
							fwrite($handle, "/>");
							
							fwrite($handle, "</link>");
					}
					// }
				// }
				
				fwrite($handle, "</chart>");
				
				fclose($handle);
			} else {
				print("Cannot open file (".$filename.")");
			}
		}
		
		function generateGraph($filename, $index, $values, $settings) {
			if($settings["xmax"]) {
				$xmax	= " max='".$settings["xmax"]."'";
			}
			if($settings["xmin"]) {
				$xmin	= " min='".$settings["xmin"]."'";
			}
			
			$arrival	= "type='dissolve'";
			if($settings["arrival"]) {
				if($settings["arrival"]["type"]) {
					$arrival	= "type='".$settings["arrival"]["type"]."'";
				}
				if($settings["arrival"]["order"]) {
					$arrival	.= " order='".$settings["arrival"]["order"]."'";
				}
			}
			
			$xml_string = (string)'';
			
			$xml_string	= "<chart>";
			
			$xml_string	.= "<license>J1XZ7W6WK.O91PA5T4Q79KLYCK07EK</license>";
			$xml_string	.= "<chart_type>".$settings["type"]."</chart_type>";
			$xml_string	.= "<chart_transition ".$arrival." />";
			
			// Legend {
				$xml_string .= "<legend size='".(isset($settings['font_size']) ? $settings['font_size'] : 7)."' 
				type='slide_down' 
				fill_color='FFFFFF' fill_alpha='100' 
				line_color='339900' line_alpha='100' line_thickness='0.01' 
				margin='4'
				/>";
			// }
			
			// Graph specific details {
				if($settings["type"]	== "line") {
					$xml_string	.= "<chart_pref line_thickness='2' point_size='4' />";
				}
			// }
			
			// Tooltips definition {
				$xml_string	.= "<tooltip type='flag'";
				$xml_string	.= " size='".(isset($settings['font_size']) ? $settings['font_size'] : 7)."'";
				$xml_string	.= " />";
			// }
			
			// Border details {
				$xml_string	.=	"<chart_border top_thickness='0'";
				$xml_string	.=	" bottom_thickness='1'";
				$xml_string	.=	" left_thickness='1'";
				$xml_string	.=	" right_thickness='0'";
				$xml_string	.=	" color='000000' />";
			// }
			
			// Colours {
				$xml_string .= "<series_color>";
				$xml_string .= "<color>006600</color>";
				$xml_string .= "<color>ff6600</color>";
				$xml_string .= "<color>1166ff</color>";
				$xml_string .= "<color>22AA22</color>";
				$xml_string .= "<color>8866ff</color>";
				$xml_string .= "<color>990099</color>";
				$xml_string .= "<color>E20000</color>";
				$xml_string .= "<color>FF944C</color>";
				$xml_string .= "<color>900000</color>";
				$xml_string .= "<color>999999</color>";
				$xml_string .= "<color>33CC00</color>";
				$xml_string .= "<color>FF00FF</color>";
				$xml_string .= "<color>FF0000</color>";
				$xml_string .= "<color>807700</color>";
				$xml_string .= "<color>00E6C5</color>";
				$xml_string .= "<color>0000FF</color>";
				$xml_string .= "<color>338499</color>";
				$xml_string .= "<color>FF0101</color>";
				
				$xml_string .= "</series_color>";
			// }
			
			// Cross Hair details {
				$xml_string .= "<chart_guide horizontal='false'";
        		$xml_string .= "vertical='true'";
        		$xml_string .= "thickness='0.5' ";
        		$xml_string .= "color='222222' ";
        		$xml_string .= "alpha='40' ";
        		$xml_string .= "type='solid'"; 
        		$xml_string .= "size='".(isset($settings['font_size']) ? $settings['font_size'] : 7)."'";
        		$xml_string .= "text_color='ffffff'";
        		$xml_string .= "background_color='117700'";
        		$xml_string .= "text_h_alpha='90'";
        		$xml_string .= "text_v_alpha='90' ";
        		$xml_string .= "/>";
			// }
			
			// The details that fill the graph {
				// Axis details {
					$xml_string .= "<axis_ticks value_ticks='true' category_ticks='true' minor_count='3' major_thickness='1' minor_thickness='0.5' />";
					$xml_string .= "<axis_category size='".(isset($settings['font_size']) ? $settings['font_size'] : 7)."' color='111111' alpha='90' orientation='diagonal_up' />"; // Horizontal marker formatting
					$xml_string .= "<axis_value size='".(isset($settings['font_size']) ? $settings['font_size'] : 7)."' color='111111' alpha='90'".$xmin."".$xmax." />"; // Vertical marker formatting
				// }
				
				$xml_string .= "<chart_data>";
				// Index details, being the markers along the base of the graph {
					$xml_string .= "<row>";
					$xml_string .= "<null/>"; // Oth point on X axis
					
					foreach ($index as $indexkey=>$indexval) {
						$xml_string .= "<string>".$indexval."</string>";
					}
					
					$xml_string .= "</row>";	
				// }
				
				// Plot values {
					foreach ($values as $linekey=>$lineval) {
						$xml_string .= "<row>";
						$xml_string .= "<string>".$lineval["title"]."</string>";
						if($lineval["values"]) {
							foreach ($lineval["values"] as $pointkey=>$pointval) {
								$xml_string .= "<number line_color='444444' line_thickness='0.1' line_alpha='100' tooltip='".$pointval."'>".$pointval."</number>";
							}
						}
						$xml_string .= "</row>";
					}
				// }
				
				$xml_string .= "</chart_data>";
			// }
			
			if($settings["path"]) {
				$path	= $settings["path"];
			} else {
				$path	= BASE."/images/flashxml/";
			}
			if ($handle = fopen($path.$filename, "w+")) {
				fwrite($handle, $xml_string);
				fclose($handle);
			} else {
				print("Cannot open file (".$filename.")");
			}
			unset($xml_string);
		}
	// }
	
	// Imports {
		function importUsers() {
			$rawusers = fopen(FIRSTBASE."/import/hdusers.csv", "r");
			while (!feof($rawusers)) {
				$rawline		= fgets($rawusers, 4096);
				$rawarray		= explode(";", $rawline);
				
				$firstname	= str_replace("\"", "", $rawarray[2]);
				$firstname	= str_replace("-", "", $firstname);
				$firstname	= str_replace(" ", "", $firstname);
				$lastname		= str_replace("\"", "", $rawarray[1]);
				
				if(($firstname) && ($lastname)) {
					$where = "firstname LIKE '".$firstname."' AND lastname LIKE '".$lastname."'";
					$usermatch = sqlPull(array("table"=>"users", "where"=>$where, "onerow"=>"1"));
					if(!$usermatch) {
						$username = strtolower("del".$lastname.$firstname[0]);
						$username = str_replace(" ", "", $username);
						
						$data = array();
						$data["username"]		= $username;
						$data["firstname"]	= $firstname;
						$data["lastname"]		= $lastname;
						$data["deleted"]		= date("U");
						
						$newid = sqlCreate(array("table"=>"users", "fields"=>$data));
					}
				}
			}
			fclose($rawusers);
			print("Done!");
		}
		
		function importFaults() {$rawusers = fopen(FIRSTBASE."/import/hdusers.csv", "r");
			$baka = sqlPull(array("table"=>"users", "where"=>"personid=".$_SESSION["userid"], "onerow"=>1));
			
			print("I know who you are, ".$baka["firstname"]."...");
			exit;
			$where = "1=1";
			sqlDelete(array("table"=>"fs_faults", "where"=>$where));
			
			$userlist = array();
			while (!feof($rawusers)) {
				$rawline		= fgets($rawusers, 4096);
				$rawarray		= explode(";", $rawline);
				
				$id					= str_replace("\"", "", $rawarray[0]);
				$firstname	= str_replace("\"", "", $rawarray[2]);
				$firstname	= str_replace("-", "", $firstname);
				$firstname	= str_replace(" ", "", $firstname);
				$lastname		= str_replace("\"", "", $rawarray[1]);
				if(($firstname) && ($lastname)) {
					$where = "firstname LIKE '".$firstname."' AND lastname LIKE '".$lastname."'";
					$username = strtolower("del".$lastname.$firstname[0]);
					$username = str_replace(" ", "", $username);
					
					$userlist[$id]["username"]		= $username;
					$userlist[$id]["firstname"]	= $firstname;
					$userlist[$id]["lastname"]		= $lastname;
					
				}
			}
			fclose($rawusers);
			
			//importUsers();
			
			$rawdata = fopen(FIRSTBASE."/import/helpdesk.csv", "r");
			while (!feof($rawdata)) {
				$rawline = fgets($rawdata, 4096);
				$rawline = str_replace("\"", "", $rawline);
				$rawarray = explode(";", $rawline);
				$source				= sqlPull(array("table"=>"users", "where"=>"firstname LIKE '".$firstsource."'"));
				
				$firstsource	= $userlist[$rawarray[3]]["firstname"];
				$lastsource		=	$userlist[$rawarray[3]]["lastname"];
				$source				= sqlPull(array("table"=>"users", "where"=>"firstname LIKE '".$firstsource."' AND lastname LIKE '".$lastsource."'", "onerow"=>1));
				$comment			= str_replace("'", "", $rawarray[2]);
				$firstit			= $userlist[$rawarray[5]]["firstname"];
				$lastit				=	$userlist[$rawarray[5]]["lastname"];
				$it						= sqlPull(array("table"=>"users", "where"=>"firstname LIKE '".$firstit."' AND lastname LIKE '".$lastit."'", "onerow"=>1));
				
				$startdate		= strtotime($rawarray[1]);
				$enddate			= strtotime($rawarray[7]);
				
				$data["itid"]			= $it["personid"];
				$data["sourceid"]	= $source["personid"];
				$data["typeid"]		= -1;
				$data["comment"]	= $comment;
				$data["date"]			= $startdate;
				$data["complete"]	= $enddate;
				
				if($enddate) {
					sqlCreate(array("table"=>"fs_faults", "fields"=>$data));
				}
			}
			fclose($rawdata);
		}
	// }
	
	// Note functions {
		function createCandidateNote($candidateid, $note) {
			$data["candidateid"]	= $candidateid;
			$data["note"]					= $note;
			$data["date"]					= date("U");
			$data["userid"]				= $_SESSION["userid"];
			
			sqlCreate(array("table"=>"candidate_notes", "fields"=>$data));
		}
		
		function createLearnerNote($learnerid, $note) {
			$data["learnerid"]		= $learnerid;
			$data["note"]					= $note;
			$data["date"]					= date("U");
			$data["userid"]				= $_SESSION["userid"];
			
			sqlCreate(array("table"=>"candidate_notes", "fields"=>$data));
		}
		
		function createDriverFaultNote($referid, $config) {
			$data["note"]			= $config;
			$data["date"]			= date("U");
			$data["userid"]		= $_SESSION["userid"];
			$data["faultid"]	= $referid;
			
			$noteid = sqlCreate(array("table"=>"dfs_notes", "fields"=>$data));
			return($noteid);
		}
		
		function createEquipFaultNote($referid, $config) {
			$data["note"]			= $config;
			$data["date"]			= date("U");
			$data["userid"]		= $_SESSION["userid"];
			$data["faultid"]	= $referid;
			
			$noteid = sqlCreate(array("table"=>"equip_notes", "fields"=>$data));
			return($noteid);
		}
	// }
	
	//: Communications {
		/** formatCellNumber($number)
		 * format a cellphone number
		 * @param $number string cellphone number to format
		 * @return string number if valid else false
		*/
		function formatCellNumber($number) {
			$number = preg_replace('/\s/', '', $number);
			switch (strlen($number)) {
				case 10: $number = '27'.substr($number, 1); break;
				case 11: $number = $number; break;
				case 15: $number = '27'.substr(preg_replace('/[\(|\)|\+]/', '', $number), 3); break;
				default: return false; break;
			}
			return $number;
		}
		
		/** sendBulkSMSSms($number, $message, $options)
		 * send a message or messages via bulkSMS.co.za
		 * @param $number array or string number(s) to send message tos 
		 * @param $message string message
		 * @param $options array array('username', 'password')
		 * @return true on success array errors otherwise
		 * @example sendBulkSMSSms('27828569513', 'Hey man give me some money')
		 * @example sendBulkSMSSms(array('27828598567', '440125692365'), 'Hey man give me some money')
		*/
		function sendSms_BulkSMS($number, $message, $options = array()) {
			$username = $options['username'] ? $options['username'] : 'kaluma';
			$password = $options['password'] ? $options['password'] : 'kalumasms';
			$url = (string)'http://bulksms.2way.co.za/eapi/submission/';
			$url .= strlen($message) > 160 ? 'send_batch/1/1.0' : 'send_sms/2/2.0';
			$port = 80;
			$fields = (string)'';
			$post_fields = (array)array('username'=>$username, 'password'=>$password);
			if (strlen($message) > 160) {
				$messagearray = str_split($message, 155);
				$post_fields['batch_data'] = (string)'msisdn,message'.'~';
				if (is_array($number)) {
					foreach ($number as $value) {foreach ($messagearray as $line) {if (formatCellNumber($value)) {$post_fields['batch_data'] .= '"'.formatCellNumber($value).'","'.$line.'"~';}}}
				} else {
					foreach ($messagearray as $line) {if (formatCellNumber($number)) {$post_fields['batch_data'] .= '"'.formatCellNumber($number).'","'.$line.'"~';}}
				}
				$post_fields['batch_data'] = rtrim($post_fields['batch_data'], '~');
			} else {
				$post_fields['message'] = $message;
				if (is_array($number)) {
					$post_fields['msisdn'] = (string)'';
					foreach ($number as $value) {if (formatCellNumber($value)) {$post_fields['msisdn'] .= formatCellNumber($value).',';}}
					$post_fields['msisdn'] = rtrim($post_fields['msisdn'], ',');
				} else {
					if (formatCellNumber($number)) {$post_fields['msisdn'] = formatCellNumber($number);}
				}
			}
			foreach($post_fields as $key=>$value) {
				if ($key == 'batch_data') {
					$split = preg_split('/~/', $value);
					$fields .= urlencode($key).'=';
					foreach ($split as $skey=>$sval) {$fields .= urlencode($sval).'%0A';}
					$fields = rtrim($fields, '%0A');
				} else {
					$fields .= urlencode($key).'='.urlencode($value).'&';
				}
			}
			$fields = rtrim($fields, '&');
			if ($options['test']) {
				echo '<pre>number:<br />';
				print_r($number);
				echo '<br />message:<br />';
				print_r($message);
				echo '<br />fields:<br />';
				print_r($fields);
				echo '<br />message array:<br />';
				print_r(isset($messagearray) ? $messagearray : '');
				echo '</pre>';
				return false;
			}
			$ch = curl_init(); //: open the curl connection/
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_PORT, $port);
			curl_setopt ($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $fields);
			$response_string = curl_exec($ch);
			$curl_info = curl_getinfo($ch);
			if ($response_string === false) {
				return false;
			} elseif ($curl_info['http_code'] != 200) {
				return false;
			} else {
				$result = split('\|', $response_string);
				if (count($result) != 3) {
					return false;
				} else {
					if ($result[0] == '0') {
						return true;
					} else {
						return false;
					}
				}
			}
			curl_close($ch); //: close the curl connection
		}
	// }
	
	// Display Functions {
		function openSubbar($width=860, $top=10, $zindex=0) {
			print("<div class='subbarholder' style='width:".$width."px;  z-index:".$zindex."; margin-top:".$top."px;'>");
			print("<span style='width:6px; height:27px; position:relative; background-image:url(\"".BASE."/images/new/subbarleft.png\"); float:left;'></span>");
			print("<span class='subbar' style='width:".($width-12)."px;'>");
		}
		
		function closeSubbar() {
			print("</span>");
			print("<span style='width:6px; height:27px; position:relative; background-image:url(\"".BASE."/images/new/subbarright.png\"); float:left;'></span>");
			
			print("</div>");
		}
		
		function openHeader($width=860, $zindex=0) {
			print("<div style='margin-top:0; margin-right:auto; margin-left:auto; padding:0; width:".($width+2)."px; height:40px; position:relative; z-index:".$zindex."; text-align:center;'>");
			
			print("<span style='width:7px; height:40px; position:relative; background-image:url(\"".BASE."/images/new/toolbarleft.png\"); float:left;'></span>");
			print("<span style='width:".($width-12)."px; height:40px; position:relative; background-image:url(\"".BASE."/images/new/toolbar.png\"); float:left; text-align:center;'>");
		}
		
		function closeHeader() {
			print("</span>");
			print("<span style='width:7px; height:40px; position:relative; background-image:url(\"".BASE."/images/new/toolbarright.png\"); float:left;'></span>");
			
			print("</div>");
		}
		
		/** closeTrayDiv($width = 860)
		 * Add the rounded corners to the bottom of the page
		 * @param int $width how wide does this thing need to be?
		*/
		function closeTrayDiv($width = 860, $marginTop = null) {
			if (is_int($width) === false) {
				$width = (int)860;
			}
			//: Bottom Rounded Corners
			echo("<div id=\"roundedCornerBottom\" style=\"".($marginTop ? "margin-top:".$marginTop."px;" : "")."width:".($width-14)."px;\"></div>".PHP_EOL);
			//: End
		}
		
		function maxineButton($text, $action, $top=0, $width=114) {
			print("<span name='maxinebutton' class='button' onmouseover=\"this.className='buttonOver';\" onmouseout=\"this.className='button';\" style='width:".$width."px; margin-top:".$top."px;' onclick='this.className=\"buttonActive\";".$action."' >");
			print("<p class='standard' style='color:BLACK; margin-top:10px;'>".$text."</p>");
			print("</span>");
		}

		function maxineSelect($details, $style=null) {
			$name	= $details["name"];
			if($style["width"]) {
				$width	= $style["width"];
			} else {
				$width	= 200;
			}
			if($style["margin"]) {
				$margin	= $style["margin"];
			} else {
				$margin	= "margin:auto";
			}
			
			$mouseover = "onmouseover=\"this.style.backgroundImage='url(../../images/new/mainblack.png)';\" onmouseout=\"this.style.backgroundImage='';\"";
			
			print("<div id='selectcontainer' style='width:".$width."px; height:30px; ".$margin."; position:relative; display:inline-block;'>");
			
			print("<div style='background-image:url(\"".BASE."/images/new/selectleft.png\"); height:29px; width:6px; float:left;'></div>");
			
			print("<div id='".$name."choicediv' tabindex='12' style='max-height:100px; width:".($width-25)."px; background-image:url(\"".BASE."/images/new/selectmid.png\"); height:22px; float:left; cursor:pointer; padding-top:6px;' onFocus='customSelectPressed(\"".$name."\", 0);'>");
			print($details["rows"][$details["selected"]]);
			print("</div>");
			
			print("<input name='conf[".$details["name"]."]' id='".$name."detail' type='hidden' value=".$details["selected"].">");
			
			print("<div id='".$name."dropdiv' style='max-height:180px; width:".($width-25)."px; background-color:GREY; overflow-y:scroll; display: none; z-index:1000; position:absolute; top:1px; left:7px;'>");
			print("<ul id='".$name."list' tabindex='100' style='margin:0px; padding:0px; width:".($width-43)."px; list-style: none; cursor:pointer;' onBlur='customSelectLostFocus(\"".$name."\");' onKeyPress='keyStroker(this,event)' >");
			
			foreach ($details["rows"] as $rowkey=>$rowval) {
				print("<li id='li".$name.$rowkey."' ".$mouseover." onClick='customSelectPressed(\"".$name."\", ".$rowkey.");'>".$rowval."</li>");
			}
			print("</ul>");
			print("</div>");
			
			print("<div style='background-image:url(\"".BASE."/images/new/selectright.png\"); height:29px; width:21px; position:absolute; left:".($width-20)."px;'></div>");
			
			print("</div>");
		}

		/** displayMessage($message, $type = "Notice", $class = "notice")
		 * Display a message to the user
		 * @param string $message what message do you want displayed?
		 * @param string $type what type of message are you trying to display: Error, Information, Notice
		 * @param string $class css class attached to this message: error, information, notice by default
		*/
		function displayMessage($message, $type = "Notice", $class = "notice")
		{
		        openSubbar(850);
		        echo("<p class=\"standard\" style=\"font-weight:bold;font-size:1em;\">".PHP_EOL);
		        echo("Profile Data Saved".PHP_EOL);
		        echo("</p>".PHP_EOL);
		        closeSubbar();
		        echo("<div class=\"".$class."\">".PHP_EOL);
		        switch ($type) {
		        case "Error":
		                echo("<img alt=\"".$type."\" src=\"".BASE."images/error.jpg\" />".PHP_EOL);
		                break;
		        case "Notice":
		        case "Information":
		                break;
		        }
		        echo($message.PHP_EOL);
		        echo("<br class=\"clear\" />".PHP_EOL);
		        echo("</div>".PHP_EOL);
		}
		
		/** shortenWord($word, $maxLength = 15)
		  * @param string $word what word(s) do you need to shorten
		  * @param int $maxLength how many characters before a horizontal ellipsis?
		  * @return string HTML to include on success false otherwise
		*/
		function shortenWord($word, $maxLength = 15)
		{
		       ## Tests
		       if (is_string($word) === false) {return false;}
		       if (is_int($maxLength) === false) {$maxLength = 15;}
		       if (strlen($word) < $maxLength) {
		               return $word;
		       }
		       $html = (string)"<span title=\"".$word."\">";
		       $html .= substr($word, 0, $maxLength);
		       $html .= "&hellip;";
		       $html .= "</span>";
		       return $html;
		}
		
		/** displayUserProfile($profile)
		    * @param array $profile which profile are we displaying?
		*/
		function displayUserProfile($profile)
		{
		        echo("<div class=\"userProfile\">".PHP_EOL);
		        if (($profile["staffno"] && $profile["department_id"] && $profile["jobtitle"] && $profile["location"]) && (date("Y-m-d", strtotime($profile["createDate"])) != "2011-02-01") && ($profile["createDate"] > date("Y-m-d H:i:s", strtotime("-2 weeks")))) {
		                echo "<img alt=\"New Staff Member\" src=\"".BASE."images/new/profile-new.png\" style=\"border:none;height:37px;left:5px;position:absolute;top:4px;width:38px;z-index:1;\" />";
		        }
		        ## Image
		        $staffno = $profile["staffno"];
		        if (strlen($staffno) < 4) {
		                $staffno = str_pad($staffno, 4, "0", STR_PAD_LEFT);
		        }
		        if (file_exists(BASE."images".DS."profiles".DS.$staffno.".jpg")) {
		                echo("<img alt=\"".$profile["firstname"]." ".$profile["lastname"]."\" src=\"".BASE."images".DS."profiles".DS.$staffno.".jpg\" style=\"z-index:0;\" />".PHP_EOL);
		        } else {
		                echo("<img alt=\"".$profile["firstname"]." ".$profile["lastname"]."\" src=\"".BASE."images".DS."profiles".DS."0.jpg\" style=\"z-index:0;\" />".PHP_EOL);
		        }
		        $abbrs = (array)array(
		                "Human Resources"=>"H.R",
		                "Information Technology"=>"I.T",
		                "Isando Operations"=>"Isando Ops",
		                "Operations Distribution"=>"Ops Distribution",
		                "Operations Africa"=>"Ops Africa",
		                "Operations Freight"=>"Ops Freight",
		        );
		        ## Data
		        echo("<div class=\"userProfileBasicData\">".PHP_EOL);
		        echo("<h3>".$profile["firstname"]." ".$profile["lastname"]."</h3>".PHP_EOL);
		        echo("<h4>".shortenWord(urldecode($profile["jobtitle"]), 23)."</h5><br />".PHP_EOL);
		        echo("<label style=\"font-weight:bold;margin-bottom:8px;margin-right:5px;width:80px;\">Department:</label><label style=\"margin-bottom:2px;width:98px;\">".(in_array($profile["department"], array_keys($abbrs)) ? $abbrs[$profile["department"]] : $profile["department"])."</label><br class=\"clear\" />".PHP_EOL);
		        echo("<label style=\"font-weight:bold;margin-bottom:8px;margin-right:5px;width:80px;\">Location:</label><label style=\"margin-bottom:2px;width:98px;\">".$profile["location"]."</label><br class=\"clear\" />".PHP_EOL);
		        echo("<label style=\"font-weight:bold;margin-bottom:8px;margin-right:5px;width:80px;\">Staff No.:</label><label style=\"margin-bottom:2px;width:98px;\">".$profile["staffno"]."</label><br class=\"clear\" />".PHP_EOL);
		        echo("</div>".PHP_EOL);
		        ## More|Less
		        echo("<div class=\"userProfileMoreLess\" id=\"profile_".$profile["id"]."\" onclick=\"events.cancelBubble(event);profiles.show(this);\">more</div>".PHP_EOL);
		        ## Collapsible div
		        echo("<div class=\"userProfileMoreInformation\" id=\"profileData[".$profile["id"]."]\" style=\"display:none;\">".PHP_EOL);
		        echo("<div>".PHP_EOL);
		        echo("<label style=\"font-weight:bold;margin-bottom:2px;width:75px;\">Birthday:</label><label style=\"margin-bottom:2px;width:205px;\">".date("d F", $profile["birthday"])."</label><br class=\"clear\" />".PHP_EOL);
		        if ($profile["interests"]) {
		                echo("<label style=\"font-weight:bold;width:80px;\">Interests:</label><br /><label style=\"margin-bottom:5px;width:100%;\">".urldecode($profile["interests"])."</label><br class=\"clear\" />".PHP_EOL);
		        }
		        if ($profile["family"]) {
		                echo("<label style=\"font-weight:bold;width:80px;\">Family:</label><br /><label style=\"margin-bottom:5px;width:100%;\">".urldecode($profile["family"])."</label><br class=\"clear\" />".PHP_EOL);
		        }
		        if ($profile["aspirations"]) {
		                echo("<label style=\"font-weight:bold;width:80px;\">Aspirations:</label><br /><label style=\"margin-bottom:5px;width:100%;\">".urldecode($profile["aspirations"])."</label><br class=\"clear\" />".PHP_EOL);
		        }
		        if ($profile["goals"]) {
		                echo("<label style=\"font-weight:bold;width:80px;\">Goal:</label><br /><label style=\"margin-bottom:5px;width:100%;\">".urldecode($profile["goals"])."</label><br class=\"clear\" />".PHP_EOL);
		        }
		        if ($profile["quote"]) {
		                echo("<label style=\"font-weight:bold;width:80px;\">Quote:</label><br /><label style=\"width:100%;\">".urldecode($profile["quote"])."</label><br class=\"clear\" />".PHP_EOL);
		        }
		        echo("</div>".PHP_EOL);
		        echo("</div>".PHP_EOL);
		        echo("</div>".PHP_EOL);
		}
		
		/** informationBar($message, $width = 820)
		 * show an informational message across all pages 
		 * @param array||string $message an array of messages to be scrolled through
		 * @param int $width how wide must the bar be?
		*/
		function informationBar($message, $width = 820)
		{
			echo("<div id=\"informationBar\" class=\"informationBar\" style=\"width:".$width."px;\">".PHP_EOL);
			if (is_array($message)) {
				$maxWidth = $width-30;
				$data = (array)array();
				$i = 0;
				$w = 0;
				foreach ($message as $msg) {
					$data[$i] .= $msg." | ";
					$w += strlen($msg)*10;
					if ($w > $maxWidth) {
						$w = 0;
						$i++;
					}
				}
				echo("<script type=\"text/javascript\">".PHP_EOL);
				echo("var messages = [], currentMessage;".PHP_EOL);
				foreach ($data as $val) {
					echo("messages.push(\"".substr($val, 0, -3)."\");".PHP_EOL);
				}
				echo("currentMessage = messages.length > 1 ? 1 : 0".PHP_EOL);
				echo("document.getElementById('informationBar').appendChild(document.createTextNode(messages[0]));".PHP_EOL);
				echo("window.setInterval(function () {
		        	while (document.getElementById('informationBar').childNodes[0]) {
		            	document.getElementById('informationBar').removeChild(document.getElementById('informationBar').childNodes[0]);
		            }
		            document.getElementById('informationBar').appendChild(document.createTextNode(messages[currentMessage]));
		            currentMessage++;
		            if (currentMessage >= messages.length) {
		            	currentMessage = 0;
		            }
		        }, 20000);".PHP_EOL);
				echo("</script>".PHP_EOL);
			} else {
				echo($message.PHP_EOL);
			}
			echo("</div>".PHP_EOL);
		}
	// }
?>
