<?PHP
	require_once(FIRSTBASE."/api/Candidates.class.php");
	require_once(FIRSTBASE."/api/Drivers.class.php");
	require_once(FIRSTBASE."/api/Learners.class.php");
	require_once(BASE."basefunctions/baseapis/man_exception.class.php");
	
	function driversList() {
		$drivers = new Drivers();
		// Preparation {
			$userobj = new TableManager("users");
			$userobj->setQueryColumns(array(
				"users"=>array("personid"),
				"profile"=>array("firstname", "profile.lastname")
				));
			$userobj->setCustomIndex("personid");
			$userobj->setQueryFrom(array(
				"left join"=>array(
					0=>array(
						"table"=>array("abbr"=>"profile", "table"=>"user_profiles"),
						"on"=>"`users`.`user_profiles_id`=`profile`.`id`"
						),
					)
				));
			$userobj->setWhere(
				"`users`.`deleted`=0"
				);
			
			$userlist = $userobj->selectMultiple();
			
			if($_POST["conf"]) {
				$conf	= $_POST["conf"];
			}
			// Building the where string {
				$where	= "1=1";
				if($conf["search"]["firstname"]) {
					$where	.= " AND firstname LIKE '%".$conf["search"]["firstname"]."%'";
				}
				if($conf["search"]["lastname"]) {
					$where	.= " AND lastname LIKE '%".$conf["search"]["lastname"]."%'";
				}
				if($conf["search"]["idno"]) {
					$where	.= " AND idno LIKE '%".$conf["search"]["idno"]."%'";
				}
				if($conf["search"]["staffno"]) {
					$where	.= " AND staffno LIKE '%".$conf["search"]["staffno"]."%'";
				}
				if($conf["search"]["fleetid"] > 0) {
					$where .= " AND fleetid=".$conf["search"]["fleetid"];
				}
				$where .= " AND deleted=0";
			// }
			
			// Building the Sort string {
				if($conf["sortvar"]) {
					$sort = $conf["sortvar"];
				} else {
					$sort = "1";
				}
				
				if($sort==1) {
					$sortstr = "firstname ASC, lastname ASC";
				} else if($sort==2) {
					$sortstr = "firstname DESC, lastname DESC";
				} else if($sort==3) {
					$sortstr = "idno ASC";
				} else if($sort==4) {
					$sortstr = "idno DESC";
				} else if($sort==5) {
					$sortstr = "staffno ASC";
				} else if($sort==6) {
					$sortstr = "staffno DESC";
				} else if($sort==7) {
					$sortstr = "fleetid ASC";
				} else if($sort==8) {
					$sortstr = "fleetid DESC";
				} else if($sort==9) {
					$sortstr = "fleetmanid";
				} else if($sort==10) {
					$sortstr = "fleetmanid DESC";
				} else if($sort==11) {
					$sortstr = "pdpdate DESC";
				} else if($sort==12) {
					$sortstr = "pdpdate";
				}
			// }
			
			$driverslist	= $drivers->getRowSet(array("where"=>$where, "sort"=>$sortstr));
			$fleetlist		= sqlPull(array("table"=>"fleets", "where"=>"1=1"));
			$reload				= "driversform.action=\"index.php?mode=maxine/index&action=driverslist\"; driversform.submit();";
			
			$mouseover = "onmouseover=\"this.style.backgroundImage='url(../../images/new/mainblack.png)';this.style.color='WHITE';\" onmouseout=\"this.style.backgroundImage=''; this.style.color='BLACK';\"";
		// }
		
		maxineTop("Driver List");
		print("<form id='driversform' name='driversform' action='index.php?mode=maxine/index&action=driverslist' method=post>");
		
		// Buttons {
			openHeader(1220);
			
			maxineButton("Add Driver", "goTo(\"index.php?mode=maxine/index&action=editdriver\");", 2);
			maxineButton("Search", "toggle(\"searchdiv\");", 2);
			if($_SESSION["isit"] > 0) {
				maxineButton("Fleets", "goTo(\"index.php?mode=maxine/index&action=editfleets\");", 2);
			}
			maxineButton("Back", "goTo(\"index.php?mode=maxine/index&action=peoplemenu\");", 2);
			
			closeHeader();
		// }
		
		print("<div class='tray' style='width:1220px;'>");
		
		// Search Box {
			print("<div id='searchdiv' style='display:none;'>");
			
			openSubbar(400);
			print("Search Box");
			closeSubbar();
			
			print("<table class='standard content1' style='width:400px;'>");
			
			print("<tr><td align='center' width=40%>");
			print("First Name");
			print("</td><td width=60%>");
			print("<input name=conf[search][firstname] value='".$conf["search"]["firstname"]."' style='width:220px;'>");
			print("</td></tr>");
			
			print("<tr><td align='center'>");
			print("Last Name");
			print("</td><td>");
			print("<input name=conf[search][lastname] value='".$conf["search"]["lastname"]."' style='width:220px;'>");
			print("</td></tr>");
			
			print("<tr><td align='center'>");
			print("ID Number");
			print("</td><td>");
			print("<input name=conf[search][idno] value='".$conf["search"]["idno"]."' style='width:220px;'>");
			print("</td></tr>");
			
			print("<tr><td align='center'>");
			print("Staff Number");
			print("</td><td>");
			print("<input name=conf[search][staffno] value='".$conf["search"]["staffno"]."' style='width:220px;'>");
			print("</td></tr>");
			
			print("<tr><td align='center'>");
			print("Fleet");
			print("</td><td>");
			print("<select id='fleetselect' name='conf[search][fleetid]' style='width:200px; z-index:500; color:BLACK;'>");
			print("<option>- Select -</option>");
			foreach ($fleetlist as $fleetkey=>$fleetval) {
				print("<option value=".$fleetval["id"].">".$fleetval["name"]." ".($fleetval["deleted"]>0?" (Deleted)":"")."</option>");
			}
			print("</select>");
			print("</td></tr>");
			
			print("<tr><td align='center' colspan=2>");
			maxineButton("Submit", "driversform.submit();", 2);
			print("</td></tr>");
			
			print("</table>");
			print("</div>");
		// }
		
		// Driver details {
			openSubbar(1200);
			print("Details");
			closeSubbar();
			print("<table class='standard' style='width:1200px; margin-bottom:20px;'>");
			
			if($driverslist) {
				print("<input type='hidden' id='sorttype' name='conf[sortvar]' value=".$conf["sortvar"].">");
				
				// Headers {
					print("<tr class='heading'><td align='center' width=30% onClick='document.getElementById(\"sorttype\").value=".($sort==1?"2":"1")."; ".$reload."'>");
					print("Name");
					if($sort == 1) {
						print("<img src='".BASE."/images/downarrow.png'>");
					} else if($sort == 2) {
						print("<img src='".BASE."/images/uparrow.png'>");
					}
					print("</td><td align='center' width=15% onClick='document.getElementById(\"sorttype\").value=".($sort==3?"4":"3")."; ".$reload."'>");
					print("ID No");
					if($sort == 3) {
						print("<img src='".BASE."/images/downarrow.png'>");
					} else if($sort == 4) {
						print("<img src='".BASE."/images/uparrow.png'>");
					}
					print("</td><td align='center' width=10% onClick='document.getElementById(\"sorttype\").value=".($sort==5?"6":"5")."; ".$reload."'>");
					print("Staff Number");
					if($sort == 5) {
						print("<img src='".BASE."/images/downarrow.png'>");
					} else if($sort == 6) {
						print("<img src='".BASE."/images/uparrow.png'>");
					}
					print("</td><td align='center' width=10% onClick='document.getElementById(\"sorttype\").value=".($sort==7?"8":"7")."; ".$reload."'>");
					print("Fleet");
					if($sort == 7) {
						print("<img src='".BASE."/images/downarrow.png'>");
					} else if($sort == 8) {
						print("<img src='".BASE."/images/uparrow.png'>");
					}
					print("</td><td align='center' width=10%>");
					print("Cellphone");
					print("</td><td align='center' width=15% onClick='document.getElementById(\"sorttype\").value=".($sort==9?"10":"9")."; ".$reload."'>");
					print("Fleet Manager");
					if($sort == 9) {
						print("<img src='".BASE."/images/downarrow.png'>");
					} else if($sort == 10) {
						print("<img src='".BASE."/images/uparrow.png'>");
					}
					print("</td><td align='center' width=10% onClick='document.getElementById(\"sorttype\").value=".($sort==11?"12":"11")."; ".$reload."'>");
					print("PDP Expiry");
					if($sort == 11) {
						print("<img src='".BASE."/images/downarrow.png'>");
					} else if($sort == 12) {
						print("<img src='".BASE."/images/uparrow.png'>");
					}
					print("</td></tr>");
				// }
				$row = 0;
				foreach ($driverslist as $driverkey=>$driverval) {
					$row++;
					print("<tr class='content1' style='cursor:pointer;' onClick='editDriver(\"".$driverval["id"]."\");' ".$mouseover."><td align='center'>");
					print($driverval["firstname"]." ".$driverval["lastname"]);
					print("</td><td align='center'>");
					print($driverval["idno"]);
					print("</td><td align='center'>");
					print($driverval["staffno"]);
					print("</td><td align='center'>");
					print($fleetlist[$driverval["fleetid"]]["name"]);
					print("</td><td align='center'>");
					print($driverval["cell"]);
					print("</td><td align='center'>");
					if($driverval["fleetmanid"] > 0) {
						$fmid	= $driverval["fleetmanid"];
						print($userlist[$fmid]["firstname"]." ".$userlist[$fmid]["lastname"]);
					}
					print("</td><td align='center'>");
					if($driverval["pdpexpires"] == 1) {
						if($driverval["pdpdate"]) {
							print(date("d-m-Y", $driverval["pdpdate"]));
						}
					} else {
						print("N/A");
					}
					print("</td></tr>");
				}
			}
			
			print("</table>");
			print("</div>");
		// }
		
		print("</div>");
		print("</form>");
		maxineBottom();
		
		// Javascript {
			print("<script type=\"text/javascript\">
			function editDriver(driverid) {
				goTo(\"index.php?mode=maxine/index&action=editdriver&driverid=\"+driverid+\"\");
			}
			
			</script>");
		// }
	}
	
	function editDriver() {
		// Preparation {
			$drivers = new Drivers();
			$driveractive	= (bool)true;
			
			$actions	= array(1=>"Warning", 2=>"Final Warning", 3=>"Dismissed");
			if($_GET["driverid"]) {
				$driverid	= $_GET["driverid"];
				
				$driver	= $drivers->getRow(array("where"=>"id=".$driverid));
				$driverbirthday	= date("d", $driver["birthday"]);
				$driverbirthmonth	= date("m", $driver["birthday"]);
				$driveractive = $driver["deleted"] == 0 ? true : false;
			}
			
			$fleetmanobj = new TableManager("users");
			$fleetmanobj->setQueryColumns(array(
				"users"=>array("personid", "isfleetman", "deleted"),
				"profile"=>array("id", "firstname", "profile.lastname", "department_id"),
				));
			$fleetmanobj->setQueryFrom(array(
				"left join"=>array(
					0=>array(
						"table"=>array("abbr"=>"profile", "table"=>"user_profiles"),
						"on"=>"`users`.`user_profiles_id`=`profile`.`id`"
						),
					)
				));
			$fleetmanobj->setOrderBy(array(
				"column"=>array("`profile`.`firstname`", "`profile`.`firstname`"),
				"direction"=>array("DESC", "DESC")
				));
			$fleetmanobj->setWhere(
				"`users`.`deleted`=0 AND `users`.`isfleetman`=1"
				);
			
			$fleetmans = $fleetmanobj->selectMultiple();
			
			$fleets				= sqlPull(array("table"=>"fleets", "where"=>"deleted=0"));
			if($driver["id"]) {
				$actionstaken	= sqlPull(array("table"=>"driver_actions","where"=>"driverid=".$driver["id"], "sort"=>"createddate"));
			}
			
			$pdpdate	= date("d/m/Y", $driver["pdpdate"]);
			
			$actionimages	= array(1=>BASE."/images/iconwarn.png", 2=>BASE."/images/iconfinalwarn.png", 3=>BASE."/images/icondismissed.png");
		// }
		
		maxineTop("Driver Form");
		print("<form method=post id='driverform' name='driverform' action='index.php?mode=maxine/index&action=commitdriver'>");
		
		// Buttons {
			openHeader();
			
			maxineButton("Submit", "driverform.submit();", 2);
			maxineButton("Back", "goTo(\"index.php?mode=maxine/index&action=driverslist\");", 2);
			
			closeHeader();
		// }
		
		print("<div class='tray' style='height:500px;'>");
		
		if($driver) {
			print("<input type=hidden name=conf[driverid] value=".$driverid.">");
		}
		
		// Left Column {
			print("<div style='width:400px; float:left; margin-left:20px;'>");
			
			// Personal Details {
				openSubbar(400);
				print("Personal Details");
				closeSubbar();
				
				print("<table class='standard content1' style='width:400px;'>");
				
				print("<tr><td align='center' width=40%>");
				print("First Name");
				print("</td><td width=60%>");
				print("<input name=conf[firstname] value='".$driver["firstname"]."' style='width:200px;'>");
				print("</td></tr>");
				
				print("<tr><td align='center'>");
				print("Last Name");
				print("</td><td>");
				print("<input name=conf[lastname] value='".$driver["lastname"]."' style='width:200px;'>");
				print("</td></tr>");
				
				// Birthday {
					print("<tr><td align='center'>");
					print("Birthday");
					print("</td><td>");
					// Day {
						print("<select id='dayselect' name='conf[birthday]' style='width:70px; color:BLACK; z-index:500;'>");
						for($day=1; $day<32; $day++) {
							print("<option ".($driverbirthday==$day?"selected":"").">".$day."</option>");
						}
						print("</select>");
					// }
					// {
						print("<select id='monthselect' name='conf[birthmonth]' style='width:134px; color:BLACK;'>");
						for($month=1; $month<13; $month++) {
							print("<option value=".$month." ".($driverbirthmonth==$month?"selected":"").">".date("F", mktime(0, 0, 0, $month, 1, 2000))."</option>");
						}
						print("</select>");
					// }
					print("</td></tr>");
				// }
				
				print("<tr><td align='center'>");
				print("ID Number");
				print("</td><td>");
				print("<input name=conf[idno] value='".$driver["idno"]."' style='width:200px;'>");
				print("</td></tr>");
				
				print("<tr><td align='center'>");
				print("Cellphone");
				print("</td><td>");
				print("<input name=conf[cell] value='".$driver["cell"]."' style='width:200px;'>");
				print("</td></tr>");
				
				print("</table>");
			// }
			
			// Company Details {
				openSubbar(400);
				print("Employment Details");
				closeSubbar();
				
				print("<table class='standard content1' style='width:400px;'>");
				
				print("<tr><td align='center' width=40%>");
				print("Staff Number");
				print("</td><td width=60%>");
				print("<input name=conf[staffno] value='".$driver["staffno"]."' style='width:200px;'>");
				print("</td></tr>");
				
				// Fleet managers {
					print("<tr><td align='center'>");
					print("Fleet Manager");
					print("</td><td>");
					print("<select id='fleetselect' name='conf[fleetmanid]' style='width:204px; color:BLACK;'>");
					print("<option value=0>- Please Select -</option>");
					if($fleetmans) {
						foreach ($fleetmans as $fleetmankey=>$fleetman) {
							print("<option value=".$fleetman["personid"]." ".($driver["fleetmanid"]==$fleetman["personid"]?"selected":"").">");
							print($fleetman["firstname"]." ".$fleetman["lastname"]);
							print("</option>");
						}
					}
					print("</select>");
					print("</td></tr>");
				// }
				
				// Fleet {
					print("<tr><td align='center'>");
					print("Fleet");
					print("</td><td>");
					print("<select id='fleetselect' name='conf[fleetid]' style='width:204px; color:BLACK;'>");
					print("<option value=0>- Please Select -</option>");
					if($fleets) {
						foreach ($fleets as $fleetkey=>$fleetval) {
							print("<option value=".$fleetval["id"]." ".($driver["fleetid"]==$fleetval["id"]?"selected":"").">".$fleetval["name"]."</option>");
						}
					}
					print("</select>");
					print("</td></tr>");
				// }
				
				// PDP Expiry checkbox and date{
					print("<tr><td align='center'>");
					print("PDP Expires?");
					print("</td><td align='center'>");
					print("<input type='checkbox' name='conf[pdpexpires]' value='1' ".($driver["pdpexpires"]==1?"checked":"")." onClick='showPdpDate();'>");
					print("</td></tr>");
					
					print("<tr id='pdpdaterow' style='".($driver["pdpexpires"]==0?"display:none;":"")."'><td align='center'>");
					print("PDP Expiry");
					print("</td><td align='center'>");
					print("<input id='pdpdate' name='conf[pdpdate]' value='".$pdpdate."' readonly style='width:160px;'>");
					print("<img src='".BASE."/images/calendar.png' onClick='displayDatePicker(\"conf[pdpdate]\", this, \"dmy\", \"\");'>");
					print("</td></tr>");
				// }
				
				print("<tr><td align='center'>");
				print("Active");
				print("</td><td align='center'>");
				print("<input type=checkbox name='conf[active]' ".($driveractive ? "checked" : "").">");
				print("</td></tr>");
				
				print("</table>");
			// }
				
			print("</div>");
		// }
		
		// Right Column {
			print("<div style='float:right; margin-right:20px; width:400px;'>");
			
			openSubbar(400);
			print("Actions Taken");
			closeSubbar();
			
			print("<table class='standard' style='width:400px;'>");
			
			if($actionstaken) {
				print("<input type=hidden id='faultid' name=conf[faultid]>");
				
				// Headings {
					print("<tr class='heading'><td align='center' width=10%>");
					print("Ticket");
					print("</td><td align='center' colspan=2 width=40%>");
					print("Action");
					print("</td><td align='center' width=45%>");
					print("Date");
					print("</td><td width=5%>");
					print("</td></tr>");
				// }
				
				foreach ($actionstaken as $actionkey=>$actionval) {
					print("<tr class='content1'><td align='center'>");
					print($actionval["sourceid"]);
					print("</td><td align='center'>");
					print($actions[$actionval["action"]]." ");
					print("</td><td align='LEFT'>");
					print("<img src='".$actionimages[$actionval["action"]]."'");
					print("</td><td align='center'>");
					print(date("d M Y", $actionval["createddate"]));
					print("</td><td align='center'>");
					if($actionval["source"]==1) {
						print("<img src='".BASE."/images/note.png' onClick='editEquipFault(".$actionval["sourceid"].")' >");
					}
					print("</td></tr>");
				}
			} else {
				print("<tr class='content1'><td align='center'>");
				print("No Actions against driver.");
				print("</td></tr>");
			}
			
			print("</table>");
			
			print("</div>");
		// }
		
		print("</div>");
		
		maxineBottom();
		print("</form>");
		
		// Javascript {
			print("<script>
				
			function editDriverFault(faultid) {
				document.getElementById('faultid').value	= faultid;
				
				document.getElementById('driverform').action	= 'index.php?mode=maxine/index&action=editdriverfault';
				document.getElementById('driverform').submit();
			}
			
			function editEquipFault(faultid) {
				document.getElementById('faultid').value	= faultid;
				
				document.getElementById('driverform').action	= 'index.php?mode=maxine/index&action=editequipfault';
				document.getElementById('driverform').submit();
				
			}
			
			function showPdpDate() {
			 if(document.getElementById('pdpdaterow').style.display == '') {
			 	document.getElementById('pdpdaterow').style.display = 'none';
			 } else {
			 	document.getElementById('pdpdaterow').style.display = '';
			 }
			}
			</script>");
		// }
	}
	
	function commitDriver() {
		$conf	=	 $_POST["conf"];
		
		$drivers = new Drivers();
		
		if($conf["pdpdate"]) {
			$day							= substr($conf["pdpdate"], 0, 2);
			$month						= substr($conf["pdpdate"], 3, 2);
			$year							= substr($conf["pdpdate"], -4);
			
			$pdpdate					= $month."/".$day."/".$year;
			$conf["pdpdate"]	= strtotime($pdpdate);
		} else {
			$conf["pdpdate"]	= 0;
		}
		
		if(!$conf["pdpexpires"]) {
			$conf["pdpexpires"]	= 0;
		}
		
		$birthday		= $conf["birthmonth"]."/".$conf["birthday"]."/2000";
		$conf["birthday"]		= strtotime($birthday);
		$conf["deleted"] = $conf["active"] == true ? 0 : time();
		
		if($conf["driverid"]) {
			$drivers->update("id=".$conf["driverid"], $conf);
		} else {
			$drivers->create($conf);
		}
		goHere("index.php?mode=maxine/index&action=driverslist");
	}
	
	function listPdpOverdues() {
		$pdpobj = new TableManager("drivers");
		$pdpobj->setQueryColumns(array(
			"drivers"=>array("*")
			));
		//$userobj->setCustomIndex("personid");
		
		$pdpobj->setWhere(
			"1=1"
			);
		
		$pdplist = $pdpobj->selectMultiple();
		
		print("<pre style='font-family:verdana;font-size:13'>");
		print_r($pdplist);
		print("</pre>");
	}
	
	// Fleet functions {
		function editFleets() {
			// Preparation {
				$equipfaults	= sqlPull(array("table"=>"fleets", "where"=>"deleted=0"));
				$count				= 1;
			// }
			
			maxineTop("Fleets List");
			print("<form method=post id='fleetsform' name='fleetsform' action='index.php?mode=maxine/index&action=commitfleets'>");
			
			// Buttons {
				openHeader();
				
				maxineButton("Add", "addRow();", 2);
				maxineButton("Submit", "fleetsform.submit();", 2);
				maxineButton("Back", "goTo(\"index.php?mode=maxine/index&action=driverslist\");", 2);
				
				closeHeader();
			// }
			
			print("<div class='tray'>");
			
			openSubbar(400);
			print("Details");
			closeSubbar();
			
			print("<table id='fleetstable' class='standard content1' style='width:400px; margin-bottom:20px;'>");
			
			if($equipfaults) {
				foreach ($equipfaults as $faultkey=>$faultval) {
					print("<input type=hidden name=conf[".$count."][faultid] value=".$faultval["id"].">");
					print("<input id='deletecontrol".$count."' type=hidden name=conf[".$count."][deleted] value=0>");
					
					print("<tr id='fleetrow".$count."'><td width=90%>");
					print("<input name=conf[".$count."][name] value='".$faultval["name"]."' style='width:250px;'>");
					print("</td><td align='center' width=10%>");
					print("<img src='".BASE."/images/deleteclickable.png' onClick='removeRow(".$count.");' title='Delete'>");
					print("</td></tr>");
					$count++;
				}
			}
			
			print("</table>");
			
			print("</div>");
			
			print("</form>");
			maxineBottom();
			
			// Javascript {
				print("<script type='text/javascript'>
				var rowCnt = ".$count.";
				
				function addRow() {	
					tblref			= document.getElementById('fleetstable');
					row					= tblref.insertRow(-1);
					row.id			= 'fleetrow'+rowCnt;
					
					cell				= row.insertCell(-1);
					str					= '<input id=\"deletecontrol'+rowCnt+'\" type=hidden name=conf['+rowCnt+'][deleted] value=0>';
					str					+= '<input name=conf['+rowCnt+'][name] value=\"Fleet\" style=\"width:250px;\">';
					cell.innerHTML = str;
					
					cell				= row.insertCell(-1);
					cell.align	= 'center';
					str					= '<img src=\'".BASE."/images/deleteclickable.png\' onClick=\'removeRow('+rowCnt+');\' title=\'Delete\'>';
					cell.innerHTML = str;
					
					rowCnt++;
				}
				
				function removeRow(rowCnt) {
					document.getElementById('fleetrow'+rowCnt).style.display	= 'none';
					document.getElementById('deletecontrol'+rowCnt).value			= 1;
				}
				</script>");
			// }
		}
		
		function commitFleets() {
			if($_POST["conf"]) {
				$conf = $_POST["conf"];
			}
			
			if($conf) {
				foreach ($conf as $confkey=>$confval) {
					if($confval["faultid"]) {
						updateFleet($confval);
					} else {
						createFleet($confval);
					}
				}
			}
			goHere("index.php?mode=maxine/index&action=editfleets");
		}
	// }
	
	/** convertToDriver()
		* @todo Email functionality to email hr group members | hr@manlinegroup.com
	*/
	function convertToDriver() {
		//: Preparation
		$id = isset($_POST['conf']['id']) ? $_POST['conf']['id'] : null;
		$type = isset($_POST['conf']['type']) ? $_POST['conf']['type'] : 'candidates';
		if (!$id) {throw new man_exception('Insufficient parameters for drivers::convert');}
		# set up the class variables
		$class = ucwords(strtolower($type));
		$drivers = new Drivers();
		$convert = new $class();
		$convertme = $convert->getRow(array('where'=>strtolower(substr($class, 0, 1)).'.id='.$id));
		$data = (array)array();
		foreach ($drivers->getCols() as $key=>$val) {
			if ($val == 'id') {continue;}
			if (in_array($val, array_keys($convertme))) {
				$data[$val] = $convertme[$val];
			}
		}
		$driverid = $drivers->create($data);
		//: Logging
		$data = (array)array();
		$data['driverid'] = $driverid;
		$data['convertedby'] = $_SESSION['userid'];
		$data['convertdate'] = time();
		$convert->update('id='.$id, $data);
		goHere("index.php?mode=maxine/index&action=driverslist");
	}
?>
