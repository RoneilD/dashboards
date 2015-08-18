<?PHP
require_once(FIRSTBASE."/api/Learners.class.php");
require_once(FIRSTBASE."/api/Users.class.php");
// {
	ob_start();
	
	function learnerList() {
		$learners = new Learners();
		// Preparation {
			$editrights	= "";
			$access			= testRights($_SESSION["userid"], "lea001");
			if($_SESSION["isit"] == 1) {
				$access = 10;
				$editrights = "onclick=goTo('index.php?mode=maxine/index&action=pagerights&code=lea001')";
			}
			
			$conf = $_POST["conf"];
			if(!$conf["searchtype"]) {
				$conf["searchtype"] = 1;
			}
			
			// Where {
				$learnerwhere = "l.deleted = 0";
				
				// Learner Details Search {
				if($conf["search"]["firstname"]) {
					$learnerwhere .= " AND l.firstname LIKE '%".$conf["search"]["firstname"]."%'";
				}
				if($conf["search"]["lastname"]) {
					$learnerwhere .= " AND l.lastname LIKE '%".$conf["search"]["lastname"]."%'";
				}
				if($conf["search"]["idno"]) {
					$learnerwhere .= " AND idno LIKE '%".$conf["search"]["idno"]."%'";
				}
				if($conf["search"]["contactno"]) {
					$learnerwhere .= " AND contactno LIKE '%".$conf["search"]["contactno"]."%'";
				}
				if($conf["search"]["statusid"] > 0) {
					$learnerwhere .= " AND statusid = ".$conf["search"]["statusid"];
				}
				// }
				
				// Note Search {
				if($conf["notesearch"]) {
					$notewhere	= "note LIKE '%".$conf["notesearch"]."%' AND learnerid > 0";
					$notelist		= sqlPull(array("table"=>"candidate_notes", "where"=>$notewhere, "group"=>"learnerid"));
					
					if($notelist) {
						$learnerwhere	.= " AND id in (0";
						foreach ($notelist as $notekey=>$noteval) {
							$learnerwhere .= ",".$noteval["learnerid"];
						}
						$learnerwhere	.= ")";
					} else {
						$learnerwhere	= "0 = 1"; // Deliberately set the learner search to fail, as no notes match search.
					}
				}
				// }
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
			
			$learnerlist	= $learners->getRowSet(array("where"=>$learnerwhere, "sort"=>$sort, 'children'=>true));
			$statuslist		= array(1=>"", 2=>"Failed", 3=>"Passed");
		// }
		
		maxineTop("Learner List");
		print("<form id='listform' name='listform' action='index.php?mode=maxine/index&action=learnerlist' method=post>");
		
		// Buttons {
			openHeader();
			
			if($access > 0) {
				maxineButton("Add", "goTo(\"index.php?mode=maxine/index&action=editlearner\");", 2);
				maxineButton("Search", "toggle(\"searchdiv\");", 2);
			}
			
			maxineButton("Back", "goTo(\"index.php?mode=maxine/index&action=peoplemenu\");", 2);
			
			closeHeader();
		// }
		
		print("<div class='tray'>");
		
		// Search Box {
			print("<div id='searchdiv' style='display: none;'>");
			
			openSubbar(400);
			print("Search Box");
			closeSubbar();
			
			print("<table class='standard' style='width:400px;'>");
			
			// Standard search {
				print("<tr class='content1'><td align='center' width=40%>");
				print("First Name");
				print("</td><td width=60%>");
				print("<input name=conf[search][firstname] value='".$conf["search"]["firstname"]."' style='width:200px;'>");
				print("</td></tr>");
				
				print("<tr class='content1'><td align='center'>");
				print("Last Name");
				print("</td><td>");
				print("<input name=conf[search][lastname] value='".$conf["search"]["lastname"]."' style='width:200px;'>");
				print("</td></tr>");
				
				print("<tr class='content1'><td align='center'>");
				print("ID Number");
				print("</td><td>");
				print("<input name=conf[search][idno] value='".$conf["search"]["idno"]."' style='width:200px;'>");
				print("</td></tr>");
				
				print("<tr class='content1'><td align='center'>");
				print("Contact Number");
				print("</td><td>");
				print("<input name=conf[search][contactno] value='".$conf["search"]["contactno"]."' style='width:200px;'>");
				print("</td></tr>");
				
				print("<tr class='content1'><td align='center'>");
				print("Status");
				print("</td><td>");
				print("<select name=conf[search][statusid] style='width:200px; color:BLACK;'>");
				print("<option value=0 ".($conf["search"]["statusid"]==0?"selected":"").">- Select -</option>");
				print("<option value=1 ".($conf["search"]["statusid"]==1?"selected":"").">No Status</option>");
				print("<option value=2 ".($conf["search"]["statusid"]==2?"selected":"").">Failed</option>");
				print("<option value=3 ".($conf["search"]["statusid"]==3?"selected":"").">Passed</option>");
				print("</select>");
				print("</td></tr>");
				
			// }
			
			// Note search {
				print("<tr class='content1'><td align='center' width=40%>");
				print("Note Text");
				print("</td><td width=60%>");
				print("<input name=conf[notesearch] value='".$conf["notesearch"]."' style='width:200px;'>");
				print("</td></tr>");
			// }
			
			print("<tr class='content1'><td align='center' colspan=2>");
			maxineButton("Submit", "listform.submit();", 0);
			print("</td></tr>");
			
			print("</table>");
			print("</div");
		// }
		
		// Results {
			if($access > 0) {
				if(($conf["search"]["firstname"] == "Mickey") && ($conf["search"]["lastname"] == "Mouse")) {
					print("<img src='".BASE."/images/mickey.png'>");
				} else {
					openSubbar(800);
					print("<span ".$editrights.">Details</span>");
					closeSubbar();
					
					print("<table class='standard' style='width:800px; margin-bottom:20px;'>");
					
					if($learnerlist) {
						// Headers {
							print("<input type='hidden' id=sorttype name=conf[sortvar] value=".$conf["sortvar"].">");
							print("<tr class='heading'><td align='center' width=5%>");
							print("</td><td align='center' onClick='sorttype.value=".($conf["sortvar"]==1?"2":"1")."; listform.submit();' width=40%>");
							print("Learner Name");
							if($conf["sortvar"] == 1) {
								print("<img src='".BASE."/images/downarrow.png'>");
							} else if($conf["sortvar"] == 2) {
								print("<img src='".BASE."/images/uparrow.png'>");
							}
							print("<img src='".BASE."/images/miniclickable.png' onClick='sorttype.value=".($conf["sortvar"]==3?"4":"3")."; listform.submit();'>");
							if($conf["sortvar"] == 3) {
								print("<img src='".BASE."/images/downarrow.png'>");
							} else if($conf["sortvar"] == 4) {
								print("<img src='".BASE."/images/uparrow.png'>");
							}
							print("</td><td align='center' onClick='sorttype.value=".($conf["sortvar"]==5?"6":"5")."; listform.submit();' width=20%>");
							print("ID Number");
							if($conf["sortvar"] == 5) {
								print("<img src='".BASE."/images/downarrow.png'>");
							} else if($conf["sortvar"] == 6) {
								print("<img src='".BASE."/images/uparrow.png'>");
							}
							print("</td><td align='center' onClick='sorttype.value=".($conf["sortvar"]==7?"8":"7")."; listform.submit();' width=20%>");
							print("Contact Number");
							if($conf["sortvar"] == 7) {
								print("<img src='".BASE."/images/downarrow.png'>");
							} else if($conf["sortvar"] == 8) {
								print("<img src='".BASE."/images/uparrow.png'>");
							}
							print("</td><td align='center' onClick='sorttype.value=".($conf["sortvar"]==9?"10":"9")."; listform.submit();' width=15%>");
							print("Status");
							if($conf["sortvar"] == 9) {
								print("<img src='".BASE."/images/downarrow.png'>");
							} else if($conf["sortvar"] == 10) {
								print("<img src='".BASE."/images/uparrow.png'>");
							}
							print("</td></tr>");
						// }
						
						foreach ($learnerlist as $learnerkey=>$learnerval) {
							// Note Title Creation {
								$notelist			= $learnerval['candidate_notes'];
								$notesummary	= "";
								$innertext		= "";
								$popup				= "";
								if($notelist) {
									$innertext	= "<table>";
									$innertext	.= "<tr><td align=\"center\">";
									$innertext	.= "Notes";
									$innertext	.= "</td></tr>";
									
									$notecount	= 1;
									foreach ($notelist as $notekey=>$note) {
										$innertext	.= "<tr><td>";
										$textnote		= str_replace("'", "", $note["note"]);
										$innertext	.= $notecount." : ".$textnote;
										$innertext	.= "</td></tr>";
										$notecount++;
									}
									$innertext	.= "</table>";
								} else {
									$innertext	= "<table>";
									$innertext	.= "<tr><td align=\"center\">";
									$innertext	.= "No notes";
									$innertext	.= "</td></tr>";
									$innertext	.= "</table>";
								}
								$popup			= "cavtitle='".$innertext."' onmousemove='setCavTimer(event);' onmouseout='CancelCavTimer(event);'";
							// }
							
							print("<tr class='content1' style=\"cursor:pointer;\" onClick=\"goTo('index.php?mode=maxine/index&action=&action=editlearner&learnerid=".$learnerval["id"]."');\"><td align='center'>\n");
							print("<img src='".BASE."/images/note.png' ".$popup.">");
							print("</td><td align='center'>");
							if(($conf["sortvar"] == 3) || ($conf["sortvar"] == 4)) {
								print($learnerval["lastname"].", ".$learnerval["firstname"]." ".$learnerval["middlename"]);
							} else {
								print($learnerval["firstname"]." ".$learnerval["middlename"]." ".$learnerval["lastname"]);
							}
							print("</td><td align='center'>");
							print($learnerval["idno"]);
							print("</td><td align='center'>");
							print($learnerval["contactno"]);
							print("</td><td align='center'>");
							print($statuslist[$learnerval["statusid"]]);
							print("</td></tr>");
						}
					} else {
						print("<tr><td align='center' colspan=2>");
						print("No Learners match search");
						print("</td></tr>");
					}
					
					print("</table>");
				}
			} else {
				print("You do not have access to this page.");
			}
		// }
		
		print("</div>");
		
		print("</form>");
		maxineBottom();
	}
	
	function editLearner() {
		$learners = new Learners();
		$users = new Users();
		// Preparation {
			if($_GET["learnerid"]) {
				$learnerid			= $_GET["learnerid"];
				
				$learner				= $learners->getRow(array("where"=>"l.id=".$learnerid, "children"=>true));
				$interviewdate	= date("d/m/Y", $learner["interview"]);
				
				$birthday				= date("d", $learner["birthdate"]);
				$birthmonth			= date("m", $learner["birthdate"]);
				$birthyear			= date("Y", $learner["birthdate"]);
				
				$notes					= $learner['candidate_notes'];
				// This is because earlier, the value was set to 0 and now the range is 1-4
				if($learner["dover"] == 0) {$learner["dover"] = 1;}
			} else {
				$birthday					= 1;
				$birthmonth				= 1;
				$birthyear				= date("Y") - 22;
				$learner["dover"]	= 1;
			}
			
			$userlist					= $users->getRowSet(array('where'=>'`u`.`deleted`=0'));
			$style						= "style='width: 200px;'";
		// }
		
		maxineTop("Learner List");
		print("<form id='learnerform' action='index.php?mode=maxine/index&action=commitlearner' method='post'>");
		
		// Buttons {
			openHeader();
			
			maxineButton("Save", "postForm(\"learnerform\");", 2);
			if($learnerid) {
				if (!$learner["driverid"]) {
					maxineButton("Convert", "postForm(\"learnerform\", \"index.php?mode=maxine/index&action=converttodriver\");", 2);
				}
				maxineButton("Delete", "goTo(\"index.php?mode=maxine/index&action=deletelearner&learnerid=".$learnerid."\");", 2);
			}
			
			maxineButton("Back", "goTo(\"index.php?mode=maxine/index&action=learnerlist\");", 2);
			
			closeHeader();
		// }
		
		print("<div class='tray' style='height:500px;'>");
		
		// Details {
			print("<input type=hidden name=conf[learnerid] value=".$learnerid.">");
			print("<input type=hidden name='conf[id]' value='".$learnerid."' />");
			print("<input type=hidden name='conf[type]' value='learners' />");
			
			// Left table of Candidate Details {
				print("<div style='width:400px; float:left; margin-left:20px;'>");
				
				// Personal Details {
					openSubbar(400);
					print("Personal Details");
					closeSubbar();
					
					print("<table class='standard' style='width:400px;'>");
					
					print("<tr class='content1'><td width=30% align='center'>");
					print("First Name");
					print("</td><td width=70%>");
					print("<input name=conf[firstname] value='".$learner["firstname"]."' ".$style.">");
					print("</td></tr>");
					
					print("<tr class='content1'><td align='center'>");
					print("Middle Name");
					print("</td><td>");
					print("<input name=conf[middlename] value='".$learner["middlename"]."' ".$style.">");
					print("</td></tr>");
					
					print("<tr class='content1'><td align='center'>");
					print("Last Name");
					print("</td><td>");
					print("<input name=conf[lastname] value='".$learner["lastname"]."' ".$style.">");
					print("</td></tr>");
					
					print("<tr class='content1'><td align='center'>");
					print("ID Number");
					print("</td><td>");
					print("<input name=conf[idno] value='".$learner["idno"]."' ".$style.">");
					print("</td></tr>");
					
					print("<tr class='content1'><td align='center'>");
					print("Contact Number");
					print("</td><td>");
					print("<input name=conf[contactno] value='".$learner["contactno"]."' ".$style.">");
					print("</td></tr>");
					
					// Birthdate selector {
						print("<tr class='content1'><td align='center'>");
						print("Date of Birth");
						print("</td><td align='center'>");
						
						// Day {
							print("<select id='dayselect' name=conf[birthdate][day] style='width:50px; color:BLACK; z-index:500;'>");
							for ($i=1; $i<32; $i++) {
								print("<option ".($i==$birthday?"selected":"").">");
								print($i);
								print("</option>");
							}
							print("</select>");
						// }
						
						// Month {
							print("<select id='monthselect' name=conf[birthdate][month] style='width:80px; color:BLACK; z-index:500;'>");
							for ($i=1; $i<13; $i++) {
								print("<option value=".$i." ".($i==$birthmonth?"selected":"").">");
								print(date("F", mktime(0,0,0,$i,1,2000)));
								print("</option>");
							}
							print("</select>");
						// }
						
						// Year {
							$startyear	= 1940;
							$endyear		= date("Y") - 18;
							print("<select id='yearselect' name=conf[birthdate][year] style='width:60px; color:BLACK; z-index:500;'>");
							for ($i=$startyear; $i<$endyear; $i++) {
								print("<option ".($i==$birthyear?"selected":"").">");
								print($i);
								print("</option>");
							}
							print("</select>");
						// }
						
						print("</td></tr>");
					// }
					
					print("</table>");
				// }
				
				// Address {
					openSubbar(400);
					print("Address");
					closeSubbar();
					
					print("<table class='standard' style='width:400px;'>");
					
					print("<tr class='content1'><td align='center'>");
					print("Address");
					print("</td><td>");
					print("<input name=conf[address][street] value='".$learner["street"]."' ".$style.">");
					print("</td></tr>");
					
					print("<tr class='content1'><td align='center'>");
					print("City");
					print("</td><td>");
					print("<input name=conf[address][city] value='".$learner["city"]."' ".$style.">");
					print("</td></tr>");
					
					print("<tr class='content1'><td align='center'>");
					print("Province");
					print("</td><td>");
					print("<input name=conf[address][province] value='".$learner["province"]."' ".$style.">");
					print("</td></tr>");
					
					print("<tr class='content1'><td align='center'>");
					print("Postal Code");
					print("</td><td>");
					print("<input name=conf[address][postalcode] value='".$learner["postalcode"]."' ".$style.">");
					print("</td></tr>");
					
					print("</table>");
				// }
				
				print("</div>");
			// }
			
			// Right table of Candidate Details {
				print("<div style='float:right; margin-right:20px; width:400px;'>");
				
				// Requirements {
					openSubbar(400);
					print("Requirements");
					closeSubbar();
					
					print("<table class='standard' style='width:400px;'>");
					
					print("<tr class='content1'><td align='center'>");
					print("Grade Completed");
					print("</td><td>");
					print("<input name=conf[gradecode] value='".$learner["gradecode"]."' ".$style.">");
					print("</td></tr>");
					
					print("<tr class='content1'><td align='center'>");
					print("Licence Code");
					print("</td><td>");
					print("<input name=conf[licencecode] value='".$learner["licencecode"]."' ".$style.">");
					print("</td></tr>");
					
					print("<tr class='content1'><td align='center'>");
					print("Interview");
					print("</td><td align='center'>");
					print("<input name=conf[interview] value='".$interviewdate."' readonly style='width: 60%;'>");
					print("<img src='".BASE."/images/calendar.png' onClick='displayDatePicker(\"conf[interview]\", this, \"dmy\", \"\");'>");
					print("</td></tr>");
					
					print("<tr class='content1'><td align='center'>");
					print("Dover");
					print("</td><td align='center'>");
					//print("<input type='checkbox' name=conf[dover] ".($learner["dover"]>0?"checked":"").">");
					print("<table width=100%>");
					print("<tr><td align='right' width=50%>");
					print("A");
					print("<input type='radio' name=conf[dover] value=4 ".($learner["dover"]==4?"checked":"").">");
					print("</td><td align='right' width=50%>");
					print("B");
					print("<input type='radio' name=conf[dover] value=3 ".($learner["dover"]==3?"checked":"").">");
					print("</td></tr>");
					print("<tr><td align='right'>");
					print("C");
					print("<input type='radio' name=conf[dover] value=2 ".($learner["dover"]==2?"checked":"").">");
					print("</td><td align='right'>");
					print("Not Taken");
					print("<input type='radio' name=conf[dover] value=1 ".($learner["dover"]==1?"checked":"").">");
					print("</td></tr>");
					print("</table>");
					print("</td></tr>");
					
					print("<tr class='content1'><td align='center'>");
					print("Medical");
					print("</td><td align='center'>");
					print("<input type='checkbox' name=conf[medical] ".($learner["medical"]>0?"checked":"").">");
					print("</td></tr>");
					
					print("<tr class='content1'><td align='center'>");
					print("Fingerprint");
					print("</td><td align='center'>");
					print("<input type='checkbox' name=conf[fingerprint] ".($learner["fingerprint"]>0?"checked":"").">");
					print("</td></tr>");
					
					print("</table>");
					print("</td></tr>");
				// }
				
				// Status {
					openSubbar(400);
					print("Status");
					closeSubbar();
					
					print("<table class='standard' style='width:400px;'>");
					
					print("<tr class='content1'><td align='center'>");
					
					print("<div style='margin:auto; width:308px; height:32px;'>");
					print("<select id='statusselect' name='conf[status]' style='width:280px; color:BLACK; z-index:500;'>");
					foreach ($learners->getStatuses() as $key=>$val) {
						print("<option value=\"".$key."\" ".($learner["statusid"] == $key ? "selected=\"selected\"" : "").">".$val."</option>\n");
					}
					print("</select>");
					print("</div>");
					
					print("</td></tr>");
					
					print("</table>");
				// }
				
				// General Notes {
					openSubbar(400);
					print("General Notes");
					closeSubbar();
					
					print("<table class='standard content1' style='width:400px;'>");
					
					if($notes) {
						foreach ($notes as $notekey=>$noteval) {
							print("<tr><td>");
							print("<b>".date("d/m/Y", $noteval["date"])." : </b>".$noteval["note"]." (".$userlist[$noteval["userid"]]["username"].")");
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
		// }
		
		print("</div>");
		
		print("</form>");
		maxineBottom();
	}
	
	function commitLearner() {
		$conf = $_POST["conf"];
		$learners = new Learners();
		// Data prep {
			$birthdate					= mktime(0,0,0,$conf["birthdate"]["month"], $conf["birthdate"]["day"], $conf["birthdate"]["year"]);
			$conf["birthdate"]	= $birthdate;
			$conf["street"] = $conf['address']['street'];
			$conf["city"] = $conf['address']['city'];
			$conf["province"] = $conf['address']['province'];
			$conf["postalcode"] = $conf['address']['postalcode'];

			if($conf["interview"]) {
				$datearray					= explode("/", $conf["interview"]);
				$date								= $datearray[1]."/".$datearray[0]."/".$datearray[2];
				
				$conf["interview"]	= strtotime($date);
			} else {
				$conf["interview"]	= 0;
			}
			$conf["medical"]	= $conf["medical"] ? 1 : 0;
			$conf["fingerprint"] = $conf["fingerprint"] ? 1 : 0;
		// }
		
		if($conf["learnerid"]) {
			$learners->update('id='.$conf['learnerid'], $conf);
		} else {
			$conf["learnerid"] = $learners->create($conf);
		}
		if($conf["note"]) {createLearnerNote($conf["learnerid"], $conf["note"]);}
		goHere("index.php?mode=maxine/index&action=learnerlist");
	}
	
	function deleteLearner() {
		$learners = new Learners();
		$learners->delete($_GET["learnerid"]);
		goHere("index.php?mode=maxine/index&action=learnerlist");
	}
// }
?>
