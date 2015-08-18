<?PHP
// {
	require_once(FIRSTBASE."/api/Candidates.class.php");
	require_once(FIRSTBASE."/api/Drivers.class.php");
	
	function candidateMenu() {
		// rightscode is 'can001'.
		
		// Preparation {
			$access			= testRights($_SESSION["userid"], "can001");
			$editrights = "onclick=goTo('index.php?mode=maxine/index&action=pagerights&code=can001')";
		// }
		
		maxineTop("Candidates System");
		
		// Buttons {
			openHeader();
			maxineButton("Back", "goTo(\"index.php?mode=maxine/index&action=peoplemenu\");", 2);
			closeHeader();
		// }
		
		print("<div class='tray'>");
		if(($_SESSION["isit"] == 1) || ($access > 0)) {
			openSubbar(600);
			print("Options");
			closeSubbar();
			print("<table class='standard content1' style='width:600px; margin-bottom:20px;'>");
			
			print("<tr><td align='center'>");
			$candidatesaction = "onmouseover='buttonJump(\"candidates\");' onmouseout='buttonStandard(\"candidates\");'";
			$candidatesaction .= " onmousedown='buttonPress(\"candidates\");'";
			print("<img src='".BIGBUTTONS."/candidates.png' id=candidatesbutton onClick=goTo('index.php?mode=maxine/index&action=candidatelist'); ".$candidatesaction.">");
			print("</td>");
			
			print("<td align='center'>");
			$reportsaction = "onmouseover='buttonJump(\"reports\");' onmouseout='buttonStandard(\"reports\");'";
			$reportsaction .= " onmousedown='buttonPress(\"reports\");'";
			print("<img src='".BIGBUTTONS."/reports.png' id=reportsbutton onClick=goTo('index.php?mode=maxine/index&action=candidatereports'); ".$reportsaction.">");
			print("</td></tr>");
			
			print("<tr><td align='center'>");
			$hardcopyaction = "onmouseover='buttonJump(\"hardcopy\");' onmouseout='buttonStandard(\"hardcopy\");'";
			$hardcopyaction .= " onmousedown='buttonPress(\"hardcopy\");'";
			print("<img src='".BIGBUTTONS."/hardcopy.png' id=hardcopybutton onClick=goTo('index.php?mode=maxine/index&action=candhardcopy'); ".$hardcopyaction.">");
			print("</td>");
			
			print("<td align='center'>");
			$statusaction = "onmouseover='buttonJump(\"status\");' onmouseout='buttonStandard(\"status\");'";
			$statusaction .= " onmousedown='buttonPress(\"status\");'";
			print("<img src='".BIGBUTTONS."/status.png' id=statusbutton onClick=goTo('index.php?mode=maxine/index&action=statustypelist'); ".$statusaction.">");
			print("</td></tr>");
			
			print("</table>");
		} else {
			print("You do not have access to this page.");
		}
		print("</div>");
		
		maxineBottom();
	}
	
	// Candidate Functions {
		function candidateList() {
			$candidates = new Candidates();
			// Preparation {
				$conf = $_POST["conf"];
				if($conf["search"]["statusid"] === null) {$conf["search"]["statusid"] = -2;}
				
				// Where {
					if($conf["search"]["firstname"]) {
						$where = "c.firstname LIKE '%".$conf["search"]["firstname"]."%'";
					} else if($conf["search"]["lastname"]) {
						$where = "c.lastname LIKE '%".$conf["search"]["lastname"]."%'";
					} else if($conf["search"]["idno"]) {
						$where = "c.idno LIKE '%".$conf["search"]["idno"]."%'";
					} else if($conf["search"]["contactno"]) {
						$where = "c.contactno LIKE '%".$conf["search"]["contactno"]."%'";
					} else if($conf["search"]["statusid"] > -2) {
						$where = "statusid = ".$conf["search"]["statusid"];
					} else {
						$where = "1=1";
					}
					$where .= " AND c.deleted=0";
				// }
				
				// Sort {
					if(($conf["sortvar"] == 1) || ($conf["sortvar"] == 2)) {
						$sort = "firstname";
					} else if(($conf["sortvar"] == 3) || ($conf["sortvar"] == 4)) {
						$sort = "lastname";
					} else if(($conf["sortvar"] == 5) || ($conf["sortvar"] == 6)) {
						$sort = "idno";
					} else if(($conf["sortvar"] == 7) || ($conf["sortvar"] == 8)) {
						$sort = "contactno";
					} else if(($conf["sortvar"] == 9) || ($conf["sortvar"] == 10)) {
						$sort = "statusid";
					} else {
						$sort = "firstname";
					}
					if($conf["sortvar"]) {
						if((fmod($conf["sortvar"],2)) == 1) {
							$sort .= " ASC";
						} else {
							$sort .= " DESC";
						}
					} else {
						$sort .= " ASC";
					}
				// }
				
				$candidatelist = $candidates->getRowSet(array("where"=>$where, "sort"=>$sort, 'children'=>true));
				$statuslist	= sqlPull(array("table"=>"candidate_status", "where"=>"1=1"));
			// }
			
			maxineTop("Candidate List");
			print("<form name='listform' action='index.php?mode=maxine/index&action=candidatelist' method=post>");
			
			// Buttons {
				openHeader();
				maxineButton("Add", "goTo(\"index.php?mode=maxine/index&action=editcandidate\");", 2);
				maxineButton("Search", "toggle(\"searchdiv\");", 2);
				maxineButton("Back", "goTo(\"index.php?mode=maxine/index&action=candmenu\");", 2);
				closeHeader();
			// }
			
			print("<div class='tray'>");
			
			// Search Box {
				print("<div id='searchdiv' style='display:none;'>");
				
				openSubbar(400);
				print("Search Box");
				closeSubbar();
				
				print("<table class='standard content1' style='width:400px;'>");
				
				print("<tr><td align='center' width=40%>");
				print("First Name");
				print("</td><td width=60%>");
				print("<input name=conf[search][firstname] value='".$conf["search"]["firstname"]."' style='width:200px;'>");
				print("</td></tr>");
				
				print("<tr><td align='center'>");
				print("Last Name");
				print("</td><td>");
				print("<input name=conf[search][lastname] value='".$conf["search"]["lastname"]."' style='width:200px;'>");
				print("</td></tr>");
				
				print("<tr><td align='center'>");
				print("ID Number");
				print("</td><td>");
				print("<input name=conf[search][idno] value='".$conf["search"]["idno"]."' style='width:200px;'>");
				print("</td></tr>");
				
				print("<tr><td align='center'>");
				print("Contact Number");
				print("</td><td>");
				print("<input name=conf[search][contactno] value='".$conf["search"]["contactno"]."' style='width:200px;'>");
				print("</td></tr>");
				
				print("<tr><td align='center'>");
				print("Status");
				print("</td><td>");
				print("<select id='statusselect' name='conf[search][statusid]' style='width:200px; color:BLACK;'>");
				print("<option value=-2>- Please Select -</option>");
				foreach ($statuslist as $statuskey=>$statusval) {
					print("<option value=".$statusval["id"]." ".($conf["search"]["statusid"]==$statusval["id"]?"selected":"").">".$statusval["code"]." (".$statusval["name"].")</option>");
				}
				print("<option value=-1 ".($conf["search"]["statusid"]==-1?"selected":"").">Failed</option>");
				print("<option value=0 ".($conf["search"]["statusid"]==0?"selected":"").">Passed</option>");
				print("</select>");
				print("</td></tr>");
				
				print("<tr><td align='center' colspan=2>");
				maxineButton("Submit", "listform.submit();", 2);
				print("</td></tr>");
				
				print("</table>");
				print("</div>");
			// }
			
			// List {
				openSubbar(800);
				print("Details");
				closeSubbar();
				
				print("<table class='standard' style='width:800px;'>");
				
				if($candidatelist) {
					// Headers {
						print("<input type='hidden' id='sorttype' name='conf[sortvar]' value=".$conf["sortvar"].">");
						print("<tr class='heading'><td align='center' onClick='document.getElementById(\"sorttype\").value=".($conf["sortvar"]==1?"2":"1")."; listform.submit();' width=35%>");
						print("Candidate Name");
						print("<img src='".BASE."/images/miniclickable.png' onClick='document.getElementById(\"sorttype\").value=".($conf["sortvar"]==3?"4":"3")."; listform.submit();'>");
						print("</td><td align='center' onClick='document.getElementById(\"sorttype\").value=".($conf["sortvar"]==5?"6":"5")."; listform.submit();' width=20%>");
						print("ID Number");
						print("</td><td align='center' onClick='document.getElementById(\"sorttype\").value=".($conf["sortvar"]==7?"8":"7")."; listform.submit();' width=20%>");
						print("Contact Number");
						print("</td><td align='center' onClick='document.getElementById(\"sorttype\").value=".($conf["sortvar"]==9?"10":"9")."; listform.submit();' width=25%>");
						print("Status");
						print("</td></tr>");
					// }
					
					$bgcolour	= MAXINEBACKALT;
					$row = 0;
					foreach ($candidatelist as $candidatekey=>$candidateval) {
						$row++;
						print("<tr class='content1' style='cursor:pointer;' onClick=\"goTo('index.php?mode=maxine/index&action=&action=editcandidate&candidateid=".$candidateval["id"]."');\"><td align='center'>");
						if(($conf["sortvar"] == 3) || ($conf["sortvar"] == 4)) {
							print($candidateval["lastname"].", ".$candidateval["firstname"]);
						} else {
							print($candidateval["firstname"]." ".$candidateval["lastname"]);
						}
						print("</td><td align='center'>");
						print($candidateval["idno"]);
						print("</td><td align='center'>");
						print($candidateval["contactno"]);
						print("</td><td align='center'>");
						print(($candidateval['status'] ? $candidateval['status'] : 'Failed'));
						print("</td></tr>");
					}
				} else {
					print("<tr><td align='center' colspan=2>");
					print("No Candidates in Database");
					print("</td></tr>");
				}
				print("</table>");
			// }
			
			print("</div>");
			
			print("</form>");
			maxineBottom();
		}
		
		function editCandidate() {
			$candidates = new Candidates();
			$drivers = new Drivers();
			// Preparation {
				$convert = (bool)true;
				if($_GET["candidateid"]) {
					$candidateid = $_GET["candidateid"];
					$candidate = $candidates->getRow(array("where"=>"c.id=".$candidateid, 'children'=>true));
					$events = $candidate['candidate_events'];
					$notes = $candidate['candidate_notes'];
					if ($candidate['driverid']) {$convert = false;}
				}
				
				$statuslist			= sqlPull(array("table"=>"candidate_status", "where"=>"1=1"));
				$userlist 			= pullUserList("", "");
				
				$style					= "style='width: 250px; border-style: inset; border-color: #D3DFC7; border-width: 1px;'";
			// }
			
			maxineTop("Candidate Details");
			print("<form id='candidateform' name='candidateform' action='index.php?mode=maxine/index&action=commitcandidate' method=post>");
			
			// Buttons {
				openHeader(1100);
				maxineButton("Save", "postForm(\"candidateform\");", 2);
				if($candidateid) {
					if ($convert === true) {
						maxineButton("Convert", "postForm(\"candidateform', 'index.php?mode=maxine/index&action=converttodriver\");", 2);
					}
					maxineButton("Delete", "goTo(\"index.php?mode=maxine/index&action=deletecandidate&candid=".$candidateid."\");", 2);
				}
				
				maxineButton("Back", "goTo(\"index.php?mode=maxine/index&action=candidatelist\");", 2);
				closeHeader();
			// }
			
			print("<div class='tray' style='width:1100px; height:500px;'>");
			
			// Hidden Inputs {
				print("<input type=hidden name='conf[candidateid]' value='".$candidateid."' />");
				print("<input type=hidden name='conf[id]' value='".$candidateid."' />");
				print("<input type=hidden name='conf[type]' value='candidates' />");
			// }
			
			// Left table of Candidate Details {
				print("<div style='float:left; width:550px;'>");
				
				// Personal Details {
					openSubbar(400);
					print("Personal Details");
					closeSubbar();
					
					print("<table class='standard content1' style='width:400px;'>");
					
					print("<tr><td width=30% align='center'>");
					print("First Name");
					print("</td><td width=70%>");
					print("<input name=conf[firstname] value='".$candidate["firstname"]."' ".$style.">");
					print("</td></tr>");
					
					print("<tr><td align='center'>");
					print("Last Name");
					print("</td><td>");
					print("<input name=conf[lastname] value='".$candidate["lastname"]."' ".$style.">");
					print("</td></tr>");
					
					print("<tr><td align='center'>");
					print("ID Number");
					print("</td><td>");
					print("<input name=conf[idno] value='".$candidate["idno"]."' ".$style.">");
					print("</td></tr>");
					
					print("<tr><td align='center'>");
					print("Contact Number");
					print("</td><td>");
					print("<input name=conf[contactno] value='".$candidate["contactno"]."' ".$style.">");
					print("</td></tr>");
					
					print("</table>");
				// }
				
				// Test Details {
					openSubbar(400);
					print("Progress Details");
					closeSubbar();
					
					print("<table class='standard' style='width:400px;'>");
					
					// Headers {
						print("<tr class='heading'><td width=30% align='center'>");
						print("Event");
						print("</td><td width=20% align='center'>");
						print("Attended");
						print("</td><td wisth=20% align='center'>");
						print("Passed");
						print("</td><td width=30% align='center'>");
						print("Date");
						print("</td></tr>");
					// }
					
					// Tests {
						if($events["test1"]["date"] > 0) {
							$date = date("d/m/Y", $events["test1"]["date"]);
						} else {
							$date = "";
						}
						if($events["test1"]["id"]) {
							print("<input type='hidden' name=conf[event][test1][id] value=".$events["test1"]["id"].">");
						}
						print("<tr class='content1'><td align='center'>");
						print("Test 1");
						print("</td><td align='center'>");
						print("<input type='checkbox' name=conf[event][test1][attended] ".($events["test1"]["attended"]==1?"checked":"").">");
						print("</td><td align='center'>");
						print("<input type='checkbox' name=conf[event][test1][passed] ".($events["test1"]["passed"]==1?"checked":"").">");
						print("</td><td width=30% align='right'>");
						
						print("<input name=conf[event][test1][date] readonly value='".$date."' style='width: 80%;'>");
						print("<img src='".BASE."/images/calendar.png' onClick='displayDatePicker(\"conf[event][test1][date]\", this, \"dmy\", \"\");'>");
						
						print("</td></tr>");
						
						if($events["test2"]["date"] > 0) {
							$date = date("d/m/Y", $events["test2"]["date"]);
						} else {
							$date = "";
						}
						if($events["test2"]["id"]) {
							print("<input type='hidden' name=conf[event][test2][id] value=".$events["test2"]["id"].">");
						}
						print("<tr class='content1'><td align='center'>");
						print("Test 2");
						print("</td><td align='center'>");
						print("<input type='checkbox' name=conf[event][test2][attended] ".($events["test2"]["attended"]==1?"checked":"").">");
						print("</td><td align='center'>");
						print("<input type='checkbox' name=conf[event][test2][passed] ".($events["test2"]["passed"]==1?"checked":"").">");
						print("</td><td align='right'>");
						
						print("<input name=conf[event][test2][date] readonly value='".$date."' style='width: 80%;'>");
						print("<img src='".BASE."/images/calendar.png' onClick='displayDatePicker(\"conf[event][test2][date]\", this, \"dmy\", \"\");'>");
						
						print("</td></tr>");
						
						if($events["test3"]["date"] > 0) {
							$date = date("d/m/Y", $events["test3"]["date"]);
						} else {
							$date = "";
						}
						if($events["test3"]["id"]) {
							print("<input type='hidden' name=conf[event][test3][id] value=".$events["test3"]["id"].">");
						}
						print("<tr class='content1'><td align='center'>");
						print("Test 3");
						print("</td><td align='center'>");
						print("<input type='checkbox' name=conf[event][test3][attended] ".($events["test3"]["attended"]==1?"checked":"").">");
						print("</td><td align='center'>");
						print("<input type='checkbox' name=conf[event][test3][passed] ".($events["test3"]["passed"]==1?"checked":"").">");
						print("</td><td align='right'>");
						
						print("<input name=conf[event][test3][date] readonly value='".$date."' style='width: 80%;'>");
						print("<img src='".BASE."/images/calendar.png' onClick='displayDatePicker(\"conf[event][test3][date]\", this, \"dmy\", \"\");'>");
						
						print("</td></tr>");
					// }
					
					// Interviews {
						if($events["interview1"]["date"] > 0) {
							$date = date("d/m/Y", $events["interview1"]["date"]);
						} else {
							$date = "";
						}
						if($events["interview1"]["id"]) {
							print("<input type='hidden' name=conf[event][interview1][id] value=".$events["interview1"]["id"].">");
						}
						print("<tr class='content1'><td align='center'>");
						print("Interview 1");
						print("</td><td align='center'>");
						print("<input type='checkbox' name=conf[event][interview1][attended] ".($events["interview1"]["attended"]==1?"checked":"").">");
						print("</td><td align='center'>");
						print("</td><td align='right'>");
						
						print("<input name=conf[event][interview1][date] value='".$date."' readonly style='width: 80%;'>");
						print("<img src='".BASE."/images/calendar.png' onClick='displayDatePicker(\"conf[event][interview1][date]\", this, \"dmy\", \"\");'>");
						
						print("</td></tr>");
						
						if($events["interview2"]["date"] > 0) {
							$date = date("d/m/Y", $events["interview2"]["date"]);
						} else {
							$date = "";
						}
						if($events["interview2"]["id"]) {
							print("<input type='hidden' name=conf[event][interview2][id] value=".$events["interview2"]["id"].">");
						}
						print("<tr class='content1'><td align='center'>");
						print("Interview 2");
						print("</td><td align='center'>");
						print("<input type='checkbox' name=conf[event][interview2][attended] ".($events["interview2"]["attended"]==1?"checked":"").">");
						print("</td><td align='center'>");
						print("</td><td align='right'>");
						
						print("<input name=conf[event][interview2][date] value='".$date."' readonly style='width: 80%;'>");
						print("<img src='".BASE."/images/calendar.png' onClick='displayDatePicker(\"conf[event][interview2][date]\", this, \"dmy\", \"\");'>");
						
						print("</td></tr>");
					// }
					
					// PDIT {
						if($events["pdit1"]["date"] > 0) {
							$date = date("d/m/Y", $events["pdit1"]["date"]);
						} else {
							$date = "";
						}
						if($events["pdit1"]["id"]) {
							print("<input type='hidden' name=conf[event][pdit1][id] value=".$events["pdit1"]["id"].">");
						}
						print("<tr class='content1'><td align='center'>");
						print("PDIT");
						print("</td><td align='center'>");
						print("<input type='checkbox' name=conf[event][pdit1][attended] ".($events["pdit1"]["attended"]==1?"checked":"").">");
						print("</td><td align='center'>");
						print("<input type='checkbox' name=conf[event][pdit1][passed] ".($events["pdit1"]["passed"]==1?"checked":"").">");
						print("</td><td align='right'>");
						
						print("<input name=conf[event][pdit1][date] value='".$date."' readonly style='width: 80%;'>");
						print("<img src='".BASE."/images/calendar.png' onClick='displayDatePicker(\"conf[event][pdit1][date]\", this, \"dmy\", \"\");'>");
						
						print("</td></tr>");
					// }
					
					print("</table>");
				// }
				
				print("</div>");
			// }
			
			// Right table of Candidate Details {
				print("<div style='float:right; width:550px;'>");
				
				// Status {
					openSubbar(400);
					print("Status");
					closeSubbar();
					
					print("<div class='content1' style='width:400px; height:42px;'>");
					
					print("<select id='statusselect' name='conf[statusid]' style='width:350px; color:BLACK; z-index:1000;'>");
					print("<option></option>");
					foreach ($statuslist as $statuskey=>$statusval) {
						print("<option value=".$statusval["id"]." ".($candidate["statusid"]==$statusval["id"]?"selected":"").">".$statusval["code"]." (".$statusval["name"].")</option>");
					}
					print("<option value=-1 ".($candidate["statusid"]==-1?"selected":"").">Failed</option>");
					print("<option value=0 ".($candidate["statusid"]==0?"selected":"").">Passed</option>");
					print("</select>");
					
					print("</div>");
				// }
				
				// General Notes {
					openSubbar(400);
					print("General Notes");
					closeSubbar();
					
					print("<table class='standard content1' style='width:400px'>");
					
					if($notes) {
						foreach ($notes as $notekey=>$noteval) {
							print("<tr><td>");
							print(date("d/m/Y", $noteval["date"])." : ".$noteval["note"]." (".$userlist[$noteval["userid"]]["username"].")");
							print("</td></tr>");
						}
					}
					
					print("<tr><td>");
					print("<textarea name=conf[note] style='width:100%; height:100%; border:0px;'></textarea>");
					print("</td></tr>");
					
					print("</table>");
				// }
				
				print("</div>");
			// }
			
			print("</div>");
			
			print("</form>");
			maxineBottom();
		}
		
		function commitCandidate() {
			$conf = $_POST["conf"];
			$candidates = new Candidates();
			if($conf["candidateid"]) {
				$candidates->update('id='.$conf["candidateid"], $conf);
			} else {
				$conf["candidateid"] = $candidates->create($conf);
			}
			updateEvents($conf);
			if($conf["note"]) {createCandidateNote($conf["candidateid"], $conf["note"]);}
			goHere("index.php?mode=maxine/index&action=candidatelist");
		}
		
		function deleteCandidate() {
			$candidates = new Candidates();
			$candidates->delete($_GET["candid"]);
			goHere("index.php?mode=maxine/index&action=candidatelist");
		}
		
		function candidateHardCopy() {
			// Preparation {
				$statuslist	= sqlPull(array("table"=>"candidate_status", "where"=>"1=1"));
			// }
			
			// PDF Creation {
				$pdf=new PDF();
				
				$pdf->AddPage();
				$border = "TRBL";
				
				$pdf->SetFont('Arial','B',10);
				$pdf->SetFillColor(200,200,200);
				$pdf->Cell(185, 7, "Candidate Form", $border, 1, "C", 1);
				
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(50, 7, "Name", $border, 0, "", 1);
				$pdf->Cell(135, 7, "", $border, 1);
				$pdf->Cell(50, 7, "ID Number", $border, 0, "", 1);
				$pdf->Cell(135, 7, "", $border, 1);
				$pdf->Cell(50, 7, "Contact Number", $border, 0, "", 1);
				$pdf->Cell(135, 7, "", $border, 1);
				
				$pdf->Cell(185, 7, "", "", 1);
				
				// Status Checks {
					$pdf->SetFont('Arial','B',8);
					
					// Headers {
						$pdf->Cell(50, 7, "", $border, 0, "", 1);
						$pdf->Cell(30, 7, "Attended", $border, 0, "C", 1);
						$pdf->Cell(30, 7, "Passed", $border, 0, "C", 1);
						$pdf->Cell(75, 7, "Date", $border, 1, "C", 1);
					// }
					
					$pdf->Cell(50, 7, "Test 1", $border, 0, "", 1);
					$pdf->Cell(30, 7, "", $border, 0);
					$pdf->Cell(30, 7, "", $border, 0);
					$pdf->Cell(75, 7, "", $border, 1);
					
					$pdf->Cell(50, 7, "Test 2", $border, 0, "", 1);
					$pdf->Cell(30, 7, "", $border, 0);
					$pdf->Cell(30, 7, "", $border, 0);
					$pdf->Cell(75, 7, "", $border, 1);
					
					$pdf->Cell(50, 7, "Test 3", $border, 0, "", 1);
					$pdf->Cell(30, 7, "", $border, 0);
					$pdf->Cell(30, 7, "", $border, 0);
					$pdf->Cell(75, 7, "", $border, 1);
					
					$pdf->Cell(50, 7, "Interview 1", $border, 0, "", 1);
					$pdf->Cell(30, 7, "", $border, 0);
					$pdf->Cell(30, 7, "", $border, 0, "", 1);
					$pdf->Cell(75, 7, "", $border, 1);
					
					$pdf->Cell(50, 7, "Interview 2", $border, 0, "", 1);
					$pdf->Cell(30, 7, "", $border, 0);
					$pdf->Cell(30, 7, "", $border, 0, "", 1);
					$pdf->Cell(75, 7, "", $border, 1);
					
					$pdf->Cell(50, 7, "PDIT", $border, 0, "", 1);
					$pdf->Cell(30, 7, "", $border, 0);
					$pdf->Cell(30, 7, "", $border, 0);
					$pdf->Cell(75, 7, "", $border, 1);
				// }
				
				$pdf->Cell(185, 7, "", "", 1);
				
				// Status List {
					$pdf->SetFont('Arial','',8);
					foreach ($statuslist as $statkey=>$statval) {
						$pdf->Cell(20, 7, $statval["code"], $border, 0, "C", 1);
						$pdf->Cell(158, 7, $statval["name"], $border, 0, "", 1);
						$pdf->Cell(7, 7, "", $border, 1);
					}
				// }
				
				$pdf->Cell(185, 7, "", "", 1);
				
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(185, 7, "Comment", $border, 1, "C", 1);
				$pdf->Cell(185, 25, "", $border, 1);
				
				$pdf->Cell(185, 7, "", "", 1);
				
				$pdf->Cell(50, 7, "Signature", $border, 0, "C", 1);
				$pdf->Cell(60, 7, "", $border, 0);
				$pdf->Cell(25, 7, "Date", $border, 0, "C", 1);
				$pdf->Cell(50, 7, "", $border, 1);
				
				$pdf->Output(BASE."/images/graphs/candidateform.pdf", "F");
			// }
			
			maxineTop("Candidate Form");
			
			openHeader();
			maxineButton("Back", "goTo(\"index.php?mode=maxine/index&action=candmenu\");", 2);
			closeHeader();
			
			print("<div class='tray'>");
			print("<p style='margin-top:40px;'><a href='download.php?filename=candidateform.pdf' style='color:WHITE;'>Click here to download the pdf</a><p>");
			print("</div>");
			
			maxineBottom();
		}
	// }
	
	// Report Functions {
		function candidateReports() {
			$candidates = new Candidates();
			// Preparation {
				$conf				= $_POST["conf"];
				$datearray	= explode("/", $conf["searchdate"]);
				
				$date				= $datearray[1]."/".$datearray[0]."/".$datearray[2];
				$date				= strtotime($date);
				
				$where = "";
				if($conf["searchtype"] == 1) {
					$where = "statusid = ".$conf["statusid"];
				} else if($conf["searchtype"] == 2) {
					$where = "statusid > 0";
				} else if($conf["searchtype"] == 3) {
					$where = "statusid = 0";
				} else if($conf["searchtype"] == 4) {
					$where = "statusid = -1";
				} else {
					$where = "1=1";
				}
				$sort = "";
				// Sort {
					if(($conf["sortvar"] == 1) || ($conf["sortvar"] == 2)) {
						$sort = "firstname";
					} else if(($conf["sortvar"] == 3) || ($conf["sortvar"] == 4)) {
						$sort = "lastname";
					} else if(($conf["sortvar"] == 5) || ($conf["sortvar"] == 6)) {
						$sort = "idno";
					} else if(($conf["sortvar"] == 7) || ($conf["sortvar"] == 8)) {
						$sort = "contactno";
					} else if(($conf["sortvar"] == 9) || ($conf["sortvar"] == 10)) {
						$sort = "statusid";
					} else {
						$sort = "firstname";
					}
					if($conf["sortvar"]) {
						if((fmod($conf["sortvar"],2)) == 1) {
							$sort .= " ASC";
						} else {
							$sort .= " DESC";
						}
					} else {
						$sort .= " ASC";
					}
				// }
				
				$where .= " AND c.deleted = 0";
				$candidatelist = $candidates->getRowSet(array("where"=>$where, "sort"=>$sort, 'children'=>true));
				$statuslist = sqlPull(array("table"=>"candidate_status", "where"=>"1=1"));
			// }
			
			maxineTop("Candidate Reports");
			print("<form name='reportform' id='reportform' action='index.php?mode=maxine/index&action=candidatereports' method=post>");
			
			// Buttons {
				$pdfclick = "onClick=reportform.action=\"index.php?mode=maxine/index&action=pdfcandlist\"; reportform.submit();";
				
				openHeader();
				maxineButton("Print", "goTo(\"index.php?mode=maxine/index&action=printcandlist\");", 2);
				maxineButton("PDF", $pdfclick, 2);
				maxineButton("Back", "goTo(\"index.php?mode=maxine/index&action=candmenu\");", 2);
				closeHeader();
			// }
			
			print("<div class='tray'>");
			
			// Selector {
				openSubbar(400);
				print("Selector");
				closeSubbar();
				
				print("<table class='standard' style='width:400px;'>");
				
				print("<tr class='content1'><td align='center' width=10%>");
				print("<input type='radio' value=1 name=conf[searchtype] ".($conf["searchtype"]==1?"checked":"").">");
				print("</td><td align='center' width=25%>");
				print("Status");
				print("</td><td width=65%>");
				if($statuslist) {
					print("<select id='statusselect' name='conf[statusid]' style='width:200px; color:BLACK;'>");
					foreach ($statuslist as $statkey=>$statval) {
						print("<option value=".$statval["id"]." ".($conf["statusid"]==$statval["id"]?"selected":"").">");
						print($statval["code"]." (".$statval["name"].")");
						print("</option>");
					}
					print("</select>");
				}
				print("</td></tr>");
				
				print("<tr class='content1'><td align='center'>");
				print("<input type='radio' value=2 name=conf[searchtype] ".($conf["searchtype"]==2?"checked":"").">");
				print("</td><td align='center'>");
				print("All Current");
				print("</td><td>");
				print("</td></tr>");
				
				print("<tr class='content1'><td align='center'>");
				print("<input type='radio' value=3 name=conf[searchtype] ".($conf["searchtype"]==3?"checked":"").">");
				print("</td><td align='center'>");
				print("Passed");
				print("</td><td>");
				print("</td></tr>");
				
				print("<tr class='content1'><td align='center'>");
				print("<input type='radio' value=4 name=conf[searchtype] ".($conf["searchtype"]==4?"checked":"").">");
				print("</td><td align='center'>");
				print("Failed");
				print("</td><td>");
				print("</td></tr>");
				
				print("<tr class='content1'><td align='center' colspan=3>");
				maxineButton("Submit", "reportform.submit();", 2);
				print("</td></tr>");
				
				print("</table>");
			// }
			
			// Results {
				if($candidatelist) {
					openSubbar(800);
					print("Results");
					closeSubbar();
					
					print("<table class='standard' style='width:800px; margin-bottom:20px;'>");
					
					print("<input type='hidden' id=sorttype name=conf[sortvar] value=".$conf["sortvar"].">");
					
					// Headers {
						print("<tr class='heading'><td align='center' onClick='sorttype.value=".($conf["sortvar"]==1?"2":"1")."; reportform.submit();' width=20%>");
						print("Name");
						print("<img src='".BASE."/images/miniclickable.png' onClick='sorttype.value=".($conf["sortvar"]==3?"4":"3")."; reportform.submit();'>");
						print("</td><td align='center' onClick='sorttype.value=".($conf["sortvar"]==5?"6":"5")."; reportform.submit();' width=15%>");
						print("ID Number");
						print("</td><td align='center' onClick='sorttype.value=".($conf["sortvar"]==7?"8":"7")."; reportform.submit();' width=15%>");
						print("Contact Number");
						print("</td><td align='center' onClick='sorttype.value=".($conf["sortvar"]==9?"10":"9")."; reportform.submit();' width=15%>");
						print("Progress");
						print("</td><td align='center' width=35%>");
						print("Comments");
						print("</td></tr>");
					// }
					
					$row = 0;
					foreach ($candidatelist as $candidatekey=>$candidateval) {
						$bgcolour = $row % 2 == 1 ? MAXINEBACK : MAXINEBACKALT;
						$row++;
						$notes	= "";
						$notes	= $candidateval['candidate_notes'];
						
						print("<tr class='content1'><td align='center'>");
						
						if(($conf["sortvar"] == 3) || ($conf["sortvar"] == 4)) {
							print($candidateval["lastname"].", ".$candidateval["firstname"]);
						} else {
							print($candidateval["firstname"]." ".$candidateval["lastname"]);
						}
						
						print("</td><td align='center'>");
						print($candidateval["idno"]);
						print("</td><td align='center'>");
						print($candidateval["contactno"]);
						print("</td><td align='center'>");
						print(($candidateval['status'] ? $candidateval['status'] : 'Failed'));
						print("</td><td>");
						if($notes) {
							foreach ($notes as $notekey=>$noteval) {
								print("<b>".date("d/M/Y", $noteval["date"])."</b> ".$noteval["note"]."<br>");
							}
						}
						print("</td></tr>");
					}
					
					print("</table>");
				}
			// }
			
			print("</div>");
			
			print("</form>");
			maxineBottom();
		}
		
		function printCandidateList() {
			$candidates = new Candidates();
			// Preparation {
				$conf = $_POST["conf"];
				
				$where = "";
				if($conf["searchtype"] == 1) {
					$where = "statusid = ".$conf["statusid"];
				} else if($conf["searchtype"] == 2) {
					$where = "statusid > 0";
				} else if($conf["searchtype"] == 3) {
					$where = "statusid = 0";
				} else if($conf["searchtype"] == 4) {
					$where = "statusid = -1";
				} else {
					$where = "1=1";
				}
				$where .= " AND c.deleted = 0";
				$candidatelist = $candidates->getRowSet(array("where"=>$where, 'children'=>true));
				
				$statuslist	= sqlPull(array("table"=>"candidate_status", "where"=>"1=1"));
				$style			= "style='border-style: none none solid none; border-width: 1px;'";
			// }
			
			print("<table width=100% height=100% cellspacing=0 style='border-color: BLACK; border-style: solid; border-width: 1px;'>");
			
			print("<tr bgcolor='#DDDDDD'><td align='center' height=1px colspan=5 ".$style.">");
			print("Candidate List");
			print("</td></tr>");
			
			print("<tr><td align='center' width=15% ".$style.">");
			print("Name");
			print("</td><td align='center' width=15% ".$style.">");
			print("ID Number");
			print("</td><td align='center' width=15% ".$style.">");
			print("Contact Number");
			print("</td><td align='center' width=15% ".$style.">");
			print("Status");
			print("</td><td align='center' width=40% ".$style.">");
			print("Notes");
			print("</td></tr>");
			
			foreach ($candidatelist as $candidatekey=>$candidateval) {
				// Individual Preparation {
					$notelist		= $candidateval['candidate_notes'];
					$notecount	= count($notelist);
					if($candidateval["statusid"] == null) {
						$status		= -10;
					} else {
						$status		= $candidateval["statusid"];
					}
				// }
				
				print("<tr><td align='center' ".$style.">");
				print($candidateval["firstname"]." ".$candidateval["lastname"]);
				print("</td><td align='center' ".$style.">");
				print($candidateval["idno"]);
				print("</td><td align='center' ".$style.">");
				print($candidateval["contactno"]);
				print("</td><td align='center' ".$style.">");
				print(($candidateval['status'] ? $candidateval['status'] : 'Failed'));
				print("</td><td align='center' ".$style.">");
				if($notecount > 0) {
					print("<table>");
					foreach ($notelist as $note0key=>$noteval) {
						print("<tr><td>");
						print("<b>".date("d/M/Y", $noteval["date"])."</b> ".$noteval["note"]);
						print("</td></tr>");
					}
					print("</table>");
				} else {
					print("No Notes");
				}
				print("</td></tr>");
			}
			
			print("<tr><td height=100%;>");
			print("</td></tr>");
			
			print("</table>");
			
			// Javascript {
				print("<script type=\"text/javascript\">window.print();</script>");
			// }
			sleep(10);
			goHere("index.php?mode=maxine/index&action=candidatereports");
		}
		
		function pdfCandidateList() {
			// Preparation {
				$conf = $_POST["conf"];
				
				$where = "";
				if($conf["searchtype"] == 1) {
					$where = "statusid = ".$conf["statusid"];
				} else if($conf["searchtype"] == 2) {
					$where = "statusid > 0";
				} else if($conf["searchtype"] == 3) {
					$where = "statusid = 0";
				} else if($conf["searchtype"] == 4) {
					$where = "statusid = -1";
				} else {
					$where = "1=1";
				}
				$where .= " AND deleted = 0";
				$candidatelist = sqlPull(array("table"=>"candidates", "where"=>$where));
				
				$statuslist	= sqlPull(array("table"=>"candidate_status", "where"=>"1=1"));
				$spans		= array(70, 70, 70, 70, 280);
				$header		= array("Name","ID Number","Contact Number","Status");
			// }
			
			// PDF Creation {
				$pdf=new PDF("L");
				
				$pdf->SetFont('Arial','B',8);
				
				$pdf->AddPage();
				$pdf->pdfHeader($header, $spans);
				$border = "TRBL";
				
				foreach ($candidatelist as $candkey=>$candval) {
					// Individual Preparation {
						if($candval["statusid"] == null) {
							$status		= -10;
						} else {
							$status		= $candval["statusid"];
						}
						$notelist	= sqlPull(array("table"=>"candidate_notes", "where"=>"candidateid=".$candval["id"]));
					// }
					$row		= array();
					$row[0] = $candval["firstname"]." ".$candval["lastname"];
					$row[1] = $candval["idno"];
					$row[2] = $candval["contactno"];
					
					if($status == -1) {
						$row[3] = "Failed";
					} else if($status == 0) {
						$row[3] = "Passed";
					} else if($status > 0) {
						$row[3] = $statuslist[$status]["name"];
					} else {
						$row[3] = "No Status";
					}
					
					if($notelist) {
						$row[4] = "";
						$count	= count($notelist);
						foreach ($notelist as $notekey=>$noteval) {
							$row[4]	.= date("d/M/Y", $noteval["date"])." - ";
							$row[4]	.= $noteval["note"];
							$count--;
							if($count > 0) {
								$row[4] .= "\n";
							}
						}
					} else {
						$row[4]	= 0;
					}
					
					$pdf->pdfCandLine($row, $spans, 7, $border);
				}
				
				$pdf->Output(BASE."/images/graphs/candidatespdf.pdf", "F");
			// }
			
			maxineHeader("top");
			
			print("<img src='".TOPBUTTONS."/buttonback.png' onClick=goTo('index.php?mode=maxine/index&action=candidatereports');>");
			print("</td><tr>");
			
			print("<tr><td align='center'>");
			print("<a href='download.php?filename=candidatespdf.pdf'>Click here to download the pdf</a>");
			print("</td></tr>");
			
			maxineFoot();
		}
	// }
	
	// Status Functions {
		function statusTypeList() {
			// Preparation {
				$statuslist = sqlPull(array("table"=>"candidate_status", "where"=>"1=1"));
			// }
			
			maxineTop("Status Types");
			print("<form name='statusform' action='index.php?mode=maxine/index&action=commitstatustypes' method='post'>");
			
			// Buttons {
				openHeader();
				
				maxineButton("Add", "addStatusType();", 2);
				maxineButton("Save", "statusform.submit();", 2);
				maxineButton("Back", "goTo(\"index.php?mode=maxine/index&action=candmenu\");", 2);
				
				closeHeader();
			// }
			
			print("<div class='tray'>");
			
			// Details {
				$count = 1;
				openSubbar(500);
				print("Status List");
				closeSubbar();
				
				print("<table id='statustable' class='standard' style='width:500px; margin-bottom:20px;'>");
				
				// Header {
					print("<tr class='heading'><td align='center' width=40%>");
					print("Code");
					print("</td><td align='center' width=50%>");
					print("Name");
					print("</td><td width=10%>");
					print("Delete");
					print("</td></tr>");
				// }
				
				if($statuslist) {
					foreach ($statuslist as $statkey=>$statval) {
						print("<input type=hidden name='conf[".$count."][id]' value='".$statval["id"]."'>");
						
						print("<tr class='content1'><td>");
						print("<input name='conf[".$count."][statuscode]' value='".$statval["code"]."'>");
						print("</td><td>");
						print("<input name='conf[".$count."][statusname]' value='".$statval["name"]."'>");
						print("</td><td>");
						print("<input type=checkbox name='conf[".$count."][delete]'>");
						print("</td></tr>");
						
						$count++;
					}
				} else {
					print("<tr class='content1'><td align='center' colspan=3>");
					print("There are no Status Types");
					print("</td></tr>");
				}
				
				print("</table>");
			// }
			
			print("</div>");
			
			print("</form>");
			maxineBottom();
			
			// Javascript {
				print("<script type='text/javascript'>
				var rowCnt = ".$count.";
				
				function addStatusType() {
					tblref = document.getElementById('statustable');
					row = tblref.insertRow(-1);
					row.className	= 'content1';
					
					cell = row.insertCell(-1);
					str = '<input name=conf['+rowCnt+'][statuscode] value=\"Code\">';
					cell.innerHTML = str;
					
					cell = row.insertCell(-1);
					str = '<input name=conf['+rowCnt+'][statusname] value=\"Status Name\">';
					cell.innerHTML = str;
					
					cell = row.insertCell(-1);
					str = '<input type=checkbox name=conf['+rowCnt+'][delete]>';
					cell.innerHTML = str;
					
					rowCnt++;
				}
				</script>");
			// }
		}
		
		function commitStatusTypes() {
			$conf = $_POST["conf"];
			
			foreach ($conf as $confkey=>$confval) {
				if($confval["id"]) {
					updateStatusType($confval);
				} else {
					createStatusType($confval);
				}
			}
			
			goHere("index.php?mode=maxine/index&action=statustypelist");
		}
	// }
// }
?>
