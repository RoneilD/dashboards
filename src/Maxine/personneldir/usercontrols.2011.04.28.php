<?PHP
	// User Functions {
		function listUsers() {
			// rightscode is 'usr001'.
			$users = new Users();
			
			// Preparation {
				$access			= testRights($_SESSION["userid"], "usr001");
				$editrights = "onclick=goTo('index.php?mode=maxine/index&action=pagerights&code=usr001')";
				
				if($_POST["conf"]) {
					$conf	= $_POST["conf"];
				}
				
				// Building the Where string {
					$where	= "1=1";
					
					if($conf["search"]["firstname"]) {
						$where .= " AND firstname LIKE '%".$conf["search"]["firstname"]."%'";
					}
					if($conf["search"]["lastname"]) {
						$where .= " AND lastname LIKE '%".$conf["search"]["lastname"]."%'";
					}
					if($conf["search"]["dept"] > 0) {
						$where .= " AND deptid=".$conf["search"]["dept"];
					}
				// }
				
				// Building the Sort string {
					if($conf["sortvar"]) {
						$sort = $conf["sortvar"];
					} else {
						$sort = "1";
					}
					
					if($sort==1) {
						$sortstr = "up.firstname ASC, up.lastname ASC";
					} else if($sort==2) {
						$sortstr = "up.firstname DESC, up.lastname DESC";
					} else if($sort==5) {
						$sortstr = "position ASC";
					} else if($sort==6) {
						$sortstr = "position DESC";
					} else if($sort==7) {
						$sortstr = "deptid DESC";
					} else if($sort==8) {
						$sortstr = "deptid ASC";
					}
				// }
				
				//$userlist = sqlPull(array("table"=>"users", "where"=>"isgeneric=0", "sort"=>$sortstr));
				$userlist = $users->getRowSet(array("sort"=>$sortstr, "where"=>$where." and u.deleted=0 and u.personid!=1", "children"=>true));
				$deptlist	= sqlPull(array("table"=>"m3_departments", "where"=>"1=1"));
				$reload		= "userlistform.action=\"index.php?mode=maxine/index&action=listusers\"; userlistform.submit()'";
				
				$mouseover = "onmouseover=\"this.style.backgroundImage='url(../../images/new/mainblack.png)';\" onmouseout=\"this.style.backgroundImage='';\"";
			// }
			
			maxineTop("Users");
			print("<form name='userlistform' id='userlistform' action='index.php?mode=maxine/index&action=edituser' method='post'>");
			
			// Buttons {
				openHeader();
				if(($_SESSION["isit"] == 1) || ($access > 0)) {
					maxineButton("Add User", "goTo(\"index.php?mode=maxine/index&action=edituser\");", 2);
					maxineButton("Search", "toggle(\"searchdiv\");", 2);
				}
				maxineButton("Back", "goTo(\"index.php?mode=maxine/index&action=peoplemenu\");", 2);
				closeHeader();
			// }
			
			print("<div class='tray'>");
			
			// Search {
				print("<div id='searchdiv' style='display:none'>");
				
				openSubbar(400);
				print("Search Box");
				closeSubbar();
				
				print("<table class='standard' style='width:400px;'>");
				
				print("<tr class='content1'><td align='center' width=40%>");
				print("First Name");
				print("</td><td width=60% align='left'>");
				print("<input name=conf[search][firstname] value='".$conf["search"]["firstname"]."' style='width:200px'>");
				print("</td></tr>");
				
				print("<tr class='content1'><td align='center'>");
				print("Last Name");
				print("</td><td align='left'>");
				print("<input name=conf[search][lastname] value='".$conf["search"]["lastname"]."' style='width:200px;'");
				print("</td></tr>");
				
				print("<tr class='content1'><td align='center'>");
				print("Department");
				print("</td><td>");
				print("<select name='conf[search][dept]' id='deptselect' style='z-index:500; width:180px; color:BLACK;'>");
				print("<option value=0>- Select Department -</option>");
				foreach ($deptlist as $deptkey=>$deptval) {
					print("<option value=".$deptval["id"]." ".($conf["search"]["dept"]==$deptval["id"]?"selected":"").">".$deptval["name"]."</option>");
				}
				print("</select>");
				print("</td></tr>");
				
				print("<tr class='content1'><td align='center' colspan=2>");
				maxineButton("Submit", $reload, 2);
				print("</td></tr>");
				
				print("</table>");
				
				
				print("</div>");
			// }
			
			if(($_SESSION["isit"] == 1) || ($access > 0)) {
				if($userlist) {
					// Hidden data controls {
						print("<input type='hidden' id='sorttype' name='conf[sortvar]' value=".$conf["sortvar"].">");
						print("<input type=hidden id='useridinput' name='conf[personid]'>"); // This variable is set to the selected users id, and then submitted with the form.
					// }
					
					openSubbar(800);
					print("<span ".$editrights.">User List</span>");
					closeSubbar();
					
					print("<table class='standard' style='width:800px; margin-bottom:25px;'>");
					
					// Headers {
						print("<tr class='heading'>");
						
						print("<td align='center' width=45% onClick='sorttype.value=".($sort==1?"2":"1")."; ".$reload.">");
						print("Name");
						if($sort == 1) {
							print("<img src='".BASE."/images/downarrow.png'>");
						} else if($sort == 2) {
							print("<img src='".BASE."/images/uparrow.png'>");
						}
						print("</td>");
						
						print("<td align='center' width=30% onClick='sorttype.value=".($sort==5?"6":"5")."; ".$reload.">");
						print("Position");
						if($sort == 5) {
							print("<img src='".BASE."/images/downarrow.png'>");
						} else if($sort == 6) {
							print("<img src='".BASE."/images/uparrow.png'>");
						}
						print("</td>");
						
						print("<td align='center' width=25% onClick='sorttype.value=".($sort==7?"8":"7")."; ".$reload.">");
						print("Department");
						if($sort == 7) {
							print("<img src='".BASE."/images/downarrow.png'>");
						} else if($sort == 8) {
							print("<img src='".BASE."/images/uparrow.png'>");
						}
						
						print("</td></tr>");
					// }
					
					$count = 1;
					foreach ($userlist as $userkey=>$userval) {
						$bday			= date("d", $userval['userdates']['birthday']['date']);
						$bmonth		= date("m", $userval['userdates']['birthday']['date']);
						
						print("<tr class='content1' style='cursor:pointer' onclick='openUser(".$userval["personid"].");' ".$mouseover."><td align='center'>");
						if(($userval["isgeneric"] < 1) && ($userval["isplace"] < 1)) {
							print($userval["firstname"]." ".$userval["lastname"]." (".$userval["username"].")");
						} else {
							print($userval["username"]);
						}
						
						if(($bday == 01) && ($bmonth == 01) && ($userval["isgeneric"] < 1) && ($userval["isplace"] < 1)) {
							print("<img src='".BASE."/images/redcross.png'>");
						}
						
						print("</td><td align='center'>");
						print($userval["position"]);
						print("</td><td align='center'>");
						if(($userval["deptid"] == 0) && ($userval["isgeneric"] < 1) && ($userval["isplace"] < 1)) {
							print("No Department");
						} else {
							print($userval['department']);
						}
						print("</td></tr>");
						$count++;
					}
					
					print("</table>");
				} else {
					print("<tr><td align='center'>");
					print("No Users meet search.");
					print("</td></tr>");
				}
			} else {
				print("<tr><td align='center'>");
				print("You do not have access to this page.");
			}
			
			print("</div>");
			closeTrayDiv();
			
			print("</form>");
			maxineBottom();
			//$onclick	= "personid.value=".$userval["personid"]."; userlistform.submit();";
						
			// Javascript {
				print("<script>
					function openUser(userid) {
						document.getElementById('useridinput').value	= userid;
						document.getElementById('userlistform').submit();
					}
					</script>");
			// }
		}
		
		function editUserForm() {
			$users = new Users();
			// Data Prep {
				$isit	= $_SESSION["isit"];
				if($_POST["conf"]) {
					$conf = $_POST["conf"];
					$user = $users->getRow(array("where"=>"personid=".$conf["personid"], "children"=>true));
					$passtip = "If nothing is entered, password will remain the same.";
				} else {
					$passtip = "This is a compulsory field";
					$user["oninout"]	= 1;
				}
				
				if($conf["personid"]) {
					$birthday = sqlPull(array("table"=>"userdates", "where"=>"userid=".$conf["personid"]." AND datetype='birthday'", "onerow"=>"1"));
					
					$bday		= date("d", $birthday["date"]);
					$bmonth	= date("m", $birthday["date"]);
				}
				
				$depts				= sqlPull(array("table"=>"m3_departments", "where"=>"1=1"));
				$rightsgroups	= sqlPull(array("table"=>"rights_groups", "where"=>"1=1"));
			// }
			
			maxineTop("User Form");
			print("<form id='userform' name='userform' action='index.php?mode=maxine/index&action=commituser' method='post'>");
			
			// Buttons {
				openHeader();
				//print("<img src='".TOPBUTTONS."/buttonuserhistory.png' onClick='userform.action=\"index.php?mode=maxine/index&action=manageuserdates\"; userform.submit();'>");
				
				maxineButton("Submit", "testDetails(); userform.submit();", 2);
				maxineButton("Delete", "userform.action=\"index.php?mode=maxine/index&action=deleteuser\"; userform.submit();", 2);
				maxineButton("Back", "goTo(\"index.php?mode=maxine/index&action=listusers\");", 2);
				
				closeHeader();
			// }
			
			print("<div class='tray' style='height:600px;'>");
			
			// Input {
				print("<input type=hidden name='conf[personid]' value=".$user["personid"].">");
				
				// Left Hand Side {
					print("<div style='width:400px; margin-left:20px; float:left;'>");
					
					// Personal Details {
						openSubbar(400);
						print("Personal Details");
						closeSubbar();
						
						print("<table class='standard content1' style='width:400px;'>");
						
						print("<tr><td width=30% align='center'>");
						print("User Name");
						print("</td><td width=70%>");
						print("<input name=conf[username] value='".$user["username"]."' style='width: 100%; background-color: ".MAXINEBACK."; border-width: 1px;' title='This will automatically be convered to lower case.'>");
						print("</td></tr>");
						
						/*
						print("<tr><td align='center'>");
						print("First Name");
						print("</td><td>");
						print("<input name=conf[firstname] value='".$user["firstname"]."'  style='width: 100%; background-color: ".MAXINEBACKALT."; border-width: 1px;'>");
						print("</td></tr>");
						
						print("<tr><td align='center'>");
						print("Last Name");
						print("</td><td>");
						print("<input name=conf[lastname] value='".$user["lastname"]."' style='width: 100%; background-color: ".MAXINEBACK."; border-width: 1px;'>");
						print("</td></tr>");
						*/
						
						print("<tr><td align='center'>");
						print("Position");
						print("</td><td>");
						print("<input name=conf[position] value='".$user["position"]."'  style='width: 100%; background-color: ".MAXINEBACKALT."; border-width: 1px;'>");
						print("</td></tr>");
						
						print("</table>");
					// }
					
					// Contact Details {
						openSubbar(400);
						print("Contact Details");
						closeSubbar();
						
						print("<table class='standard content1' style='width:400px;'>");
						
						print("<tr><td align='center' width=30%>");
						print("Office Extension");
						print("</td><td width=70%>");
						print("<input name=conf[extension] value='".$user["extension"]."' style='width: 100%; background-color: ".MAXINEBACK."; border-width: 1px;'>");
						print("</td></tr>");
						
						print("<tr><td align='center'>");
						print("Cell Number");
						print("</td><td>");
						print("<input name=conf[cell] value='".$user["cell"]."' style='width: 100%; background-color: ".MAXINEBACKALT."; border-width: 1px;'>");
						print("</td></tr>");
						
						print("<tr><td align='center'>");
						print("Email");
						print("</td><td>");
						print("<input name=conf[email] value='".$user["email"]."' style='width: 100%; background-color: ".MAXINEBACK."; border-width: 1px;' onblur=\"if (this.value && ((validateEmailAddress(this)) === false)) {alert('Please ensure that the email address you have entered is a valid email address.'); this.value='';}\">");
						print("</td></tr>");
						
						print("</table>");
					// }
					
					// Misc {
						openSubbar(400);
						print("Miscellaneous");
						closeSubbar();
						
						print("<table class='standard content1' style='width:400px;'>");
						
						// Password {
							print("<tr><td align='center' width=30%>");
							print("Password");
							print("</td><td width=70%>");
							print("<input type=password name='conf[password]' style='width:200px; margin-top:2px;' title='".$passtip."'>");
							print("</td></tr>");
						// }
						
						/*
						// Birthday {
							print("<tr><td align='center' width=30%>");
							print("Birthday");
							print("</td><td align='center' width=70%>");
							
							print("<select id='dayselect' name='conf[birth][day]' style='width:80px; color:BLACK; z-index:500;'>");
							for ($day=1; $day < 32; $day++) {
								print("<option value=".$day." ".($bday==$day?"selected":"").">");
								print($day);
								print("</option>");
							}
							print("</select>");
							
							print("<select id='monthselect' name='conf[birth][month]' style='width: 120px; color:BLACK; z-index:500;'>");
							for ($month=1; $month < 13; $month++) {
								print("<option value=".$month." ".($bmonth==$month?"selected":"").">");
								print(date("F", mktime(0, 0, 0, $month, 01, 2000)));
								print("</option>");
							}
							print("</select>");
							
							print("</td></tr>");
						// }
						
						// Department {
							print("<tr><td align='center'>");
							print("Department");
							print("</td><td>");
							if($depts) {
								print("<select name=conf[deptid] style='width: 228px; color:BLACK;'>");
								print("<option value=0>- Department -</option>");
								foreach ($depts as $deptkey=>$deptval) {
									print("<option value=".$deptval["id"]." ".($deptval["id"]==$user["deptid"]?"selected":"").">".$deptval["name"]."</option>");
								}
								print("</select>");
							} else {
								print("No Departments listed");
							}
							print("</td></tr>");
						// }
						*/
						print("</table>");
					// }
					
					print("</div>");
				// }
				
				// Right Hand Side {
					print("<div style='width:400px; margin-right:20px; float:right;'>");
					
					if($isit==1) {
						// Flags {
							openSubbar(400);
							print("Flags");
							closeSubbar();
							
							print("<table class='standard content1' style='width:400px;'>");
							
							print("<tr><td width=70% align='center'>");
							print("Allow Login");
							print("</td><td width=30% align='center'>");
							print("<input type=checkbox name=conf[canlogin] ".($user["canlogin"]==1?"checked":"").">");
							print("</td></tr>");
							
							print("<tr><td align='center'>");
							print("Show on In Out board");
							print("</td><td align='center'>");
							print("<input type=checkbox name=conf[oninout] ".($user["oninout"]==1?"checked":"").">");
							print("</td></tr>");
							
							print("<tr><td align='center'>");
							print("Manager");
							print("</td><td align='center'>");
							print("<input type=checkbox name=conf[ismanager] ".($user["ismanager"]==1?"checked":"").">");
							print("</td></tr>");
							
							print("<tr><td align='center'>");
							print("Supervisor");
							print("</td><td align='center'>");
							print("<input type=checkbox name=conf[issuper] ".($user["issuper"]==1?"checked":"").">");
							print("</td></tr>");
							
							print("<tr><td align='center'>");
							print("Fleet Manager");
							print("</td><td align='center'>");
							print("<input type='checkbox' name=conf[isfleetman] ".($user["isfleetman"]==1?"checked":"").">");
							print("</td></tr>");
							
							print("<tr><td align='center'>");
							print("IT");
							print("</td><td align='center'>");
							print("<input type=checkbox name=conf[isit] ".($user["isit"]==1?"checked":"").">");
							print("</td></tr>");
							
							print("<tr><td align='center'>");
							print("Generic User");
							print("</td><td align='center'>");
							print("<input type=checkbox name=conf[isgeneric] ".($user["isgeneric"]==1?"checked":"").">");
							print("</td></tr>");
							
							print("<tr><td align='center'>");
							print("Place");
							print("</td><td align='center'>");
							print("<input type=checkbox name=conf[isplace] ".($user["isplace"]==1?"checked":"").">");
							print("</td></tr>");
							
							print("<tr><td align='center'>");
							print("Fleets Performance");
							print("</td><td align='center'>");
							print("<input type='checkbox' name=conf[isfleetper] ".($user["isfleetper"]==1?"checked":"").">");
							print("</td></tr>");
							
							print("<tr><td align='center'>");
							print("Damages, Shortages & Incidents");
							print("</td><td align='center'>");
							print("<input type='checkbox' name=conf[isdamage] ".($user["isdamage"]==1?"checked":"").">");
							print("</td></tr>");
							
							print("</table>");
						// }
						
						// Groups {
							openSubbar(400);
							print("Access Groups");
							closeSubbar();
							
							print("<table class='standard content1' style='width:400px'>");
							
							foreach ($rightsgroups as $grpkey=>$grpval) {
								// Individual preparation {
									$userrights = null;
									if($conf["personid"]) {$userrights = $user['rights_users'][$grpval['id']];}
									$sel = (string)"";
									if ($userrights) {$sel = 'checked="checked"';}
								// }
								print("<tr><td align='center' width=70% title='".$grpval["description"]."'>");
								print($grpval["name"]);
								print("</td><td width=30% align='center'>");
								print("<input type='checkbox' name=conf[groups][".$grpval["id"]."] value=1 ".$sel.">");
								print("</td></tr>");
							}
							
							print("</table>");
						// }
					} else {
						print("Flags and Access Groups");
						
						print("Please contact IT to change these settings");
					}
					
					print("</div");
				// }
			// }
			
			print("</div");
			
			print("</form>");
			maxineBottom();
			
			// Javascript {
				print("<script type=\"text/javascript\">
					function testDetails() {return false;}
					function goThrough() {alert('20');}
					/** validateEmailAddress(obj)
					* @param obj object HTML element reference
					* @return true on success false otherwise
					*/
					function validateEmailAddress(obj) {
					var val = obj.value;
					var replaced = obj.value.toString().replace('/\s/', '');
					if (!val || !replaced) {return false;}
					if (val.match(/^(.+)@([^@]+)$/)) {return true;}
					return false;
					}
					</script>");
			// }
		}
		
		function manageUserDates() {
			if($_POST["conf"]) {
				$conf = $_POST["conf"];
			}
			
			//$dates = sqlPull(array("table"=>"userdates", "where"=>"userid=".$conf["personid"]." AND datetype='misc'", "sort"=>"date DESC"));
			$dates = sqlPull(array("table"=>"userdates", "where"=>"userid=".$conf["personid"]));
			
			print("<table width=100%>");
			
			print("<tr><td align='center'>");
			
			print("<table id=userdatetable bgcolor='BLACK' width=60% cellspacing=1 cellpadding=0>");
			print("<tr bgcolor=".MANGREEN."><td align='center' colspan=2>");
			print("Miscellaneous User Dates");
			print("</td></tr>");
			
			if($dates) {
				foreach ($dates as $datekey=>$dateval) {
					print("<tr bgcolor='WHITE'><td align='center' width=30%>");
					print(date("d F Y", $dateval["date"]));
					print("</td><td align='center' width=70%>");
					print($dateval["comment"]);
					print("</td></tr>");
				}
			}
			print("</table>");
			
			print("</td></tr>");
			
			print("<tr><td align='center'>");
			print("<input type=button value='Add' onClick='addRow();' style='width:120px;'>");
			print("</td></tr>");
			
			print("<tr><td align='center'>");
			print("<input type=button value='Save' style='width:120px;'>");
			print("</td></tr>");
			
			print("<tr><td align='center'>");
			print("<input type=button value='Back' onClick=goTo('index.php?mode=maxine/index&action=listusers') style='width:120px;'>");
			print("</td></tr>");
			
			print("</table>");
			
			// Javascript {
				print("<script type='text/javascript'>
					function addRow() {
					tblref = document.getElementById('userdatetable');
					row = tblref.insertRow(-1);
					row.style.backgroundColor = 'WHITE';
					
					cell = row.insertCell(-1);
					str = 'Test';
					cell.innerHTML = str;
					
					cell = row.insertCell(-1);
					str = '<input style=\"width:100%;\">';
					cell.innerHTML = str;
					
					// rowCnt++;
					}
					
					</script>");
			// }
		}
		
		function commitUser() {
			$users = new Users();
			if($_POST["conf"]) {$conf = $_POST["conf"];}
			
			// Manipulations {
				$conf["username"] = strtolower($conf["username"]);
				$conf["isit"] = $conf["isit"] == true ? 1 : 0;
				$conf["deleted"] = 0;
				// IT only {
					if($_SESSION["isit"]==1) {
						$conf["isgeneric"]	= $conf["isgeneric"] == true ? 1 : 0;
						$conf["isplace"]		= $conf["isplace"] == true ? 1 : 0;
						$conf["canlogin"]		= $conf["canlogin"] == true ? 1 : 0;
						$conf["oninout"]		= $conf["oninout"] == true ? 1 : 0;
						$conf["ismanager"]	= $conf["ismanager"] == true ? 1 : 0;
						$conf["issuper"]		= $conf["issuper"] == true ? 1 : 0;
						$conf["isfleetman"]	= $conf["isfleetman"] == true ? 1 : 0;
						$conf["isfleetper"]	= $conf["isfleetper"] == true ? 1 : 0;
						$conf["isdamage"]		= $conf["isdamage"] == true ? 1 : 0;
					}
				// }
			// }
			
			if(strlen($conf["password"]) == 0) {
				unset($conf["password"]);
			}
			
			if($conf["personid"]) {
				$users->update("personid=".$conf["personid"], $conf);
			} else {
				if(!$conf["password"]) {
					$conf["password"] = md5($conf["username"]."9");
				}
				$conf["personid"] = $users->create($conf);
			}
			
			userBirthday($conf["personid"], $conf["birth"]);
			if($_SESSION["isit"]) {commitUserRights($conf["personid"], $conf["groups"]);}
			
			goHere("index.php?mode=maxine/index&action=listusers");
		}
		
		function deleteUser() {
			$users = new Users();
			if($_POST["conf"]) {$conf = $_POST["conf"];}
			$users->delete($conf["personid"]);
			goHere("index.php?mode=maxine/index&action=listusers");
		}
	// }
	
	// Departments {
		function viewM3Dept() {
			// Preparation {
				if($_POST["conf"]) {
					$conf = $_POST["conf"];
				}
				// Setup $conf and attached variables {
					if(!$conf["present"]) {
						$conf["present"] = "weight";
					}
					if($conf["pointerdirection"]) {
						$pointerdir	= $conf["pointerdirection"];
						if($pointerdir > 4) {
							$pointerdir = 1;
						}
					} else {
						$pointerdir = 1;
					}
				// }
				
				$deptdetails	= sqlPull(array("table"=>"m3_departments", "where"=>"id=".$conf["deptid"]." AND display=1", "onerow"=>"1"));
				
				$m3cats				= sqlPull(array("table"=>"m3_categories", "where"=>"deptid=".$conf["deptid"]." AND deleted=0"));
				$catcount			= count($m3cats) + 1;
				
				// Combine selections with pulled details {
					if($conf["present"]=="weight") {
						$columnlist[0]	= array("name"=>"Weighted Total", "id"=>0);
						if($conf["graphdisplay"][0] == null) {
							$conf["graphdisplay"][0] = 1;
						}
						$columncount		= 2;
						$tdwidth				= 50;
					} else {
						$columnlist			= $m3cats;
						$count = 1;
						if($columnlist) {
							foreach ($columnlist as $colkey=>$colval) {
								if($conf["graphdisplay"][$colval["id"]] == null) {
									$conf["graphdisplay"][$colval["id"]] = 0;
								}
							}
						}
					}
					
					if($conf["daterange"]) {
						$startmonth 	= $conf["daterange"]["startmonth"];
						$startyear		= $conf["daterange"]["startyear"];
						
						$endmonth			= $conf["daterange"]["endmonth"];
						$endyear			= $conf["daterange"]["endyear"];
					} else {
						$startmonth		= 4;
						
						$startyear		= date("Y");
						if(date("m") <= 4) {
							$startyear--;
						}
						
						$endmonth			= date("m") - 1;
						$endyear			= date("Y");
						if($endmonth == 0) {
							$endmonth	= 12;
							$endyear	= date("Y") - 1;
						}
					}
				// }
				
				// Colours {
					$basecolours			= array(
						"006600",
						"990099",
						"9999FF",
						"FF944C",
						"900000",
						"999999",
						"33CC00",
						"FF00FF",
						"FF0000",
						"807700",
						"00E6C5",
						"0000FF",
						"338499",
						"FF0101",
						"CE7811"
						);
					$colourcount = 0;
					$colours[0] = "067928";
					if($m3cats) {
						foreach ($m3cats as $cckey=>$ccval) {
							$colours[$ccval["id"]] = $basecolours[$colourcount];
							$colourcount++;
						}
					}
				// }
				
				$deptscores		= array();
				
				// This compares the start and end dates for the search.  If greater, it switches them around {
					if(($startyear > $endyear) || (($startyear == $endyear) && ($startmonth > $endmonth))) {
						$tempyear		= $startyear;
						$tempmonth	= $startmonth;
						
						$startyear	= $endyear;
						$startmonth	= $endmonth;
						
						$endyear		= $tempyear;
						$endmonth		= $tempmonth;
					}
				// }
				
				// Cycle through months and years of range {
					$yearcount	= $startyear;
					$monthcount	= $startmonth;
					while($yearcount <= $endyear) {
						// If $yearcount == $endyear, then the search ends at $endmonth, otherwise there is another year to go through
						// and the search must go to the last month of this year {
							if($yearcount == $endyear) {
								$stopmonth = $endmonth + 1;
							} else {
								$stopmonth = 12;
							}
						// }
						while($monthcount <= $stopmonth) {
							// In this loop, we must cycle through each category of the department, so that the data is pulled by category,
							// month and year.
							
							// Make sure $datekey has 6 digits, YYYYMM, by adding a 0 between year and month if $monthcount < 10 {
								if($monthcount < 10) {
									$datekey = $yearcount."0".$monthcount;
								} else {
									$datekey = $yearcount.$monthcount;
								}
							// }
							
							// Loading Graph Keylist and Division Titles {
								$graphdata["meta"]["divlist"][]						= $datekey;
								$graphdata["meta"]["divtitles"][$datekey]	= date("M", mktime(0, 0, 0, $monthcount, 1, $yearcount))." ".$yearcount;
								
								$index[]						= date("M", mktime(0, 0, 0, $monthcount, 1, $yearcount))." ".$yearcount;
								$graphkey[$datekey]	= $datekey;
							// }
							
							$weighttotal = 0;
							if($m3cats) {
								foreach ($m3cats as $catkey=>$catval) {
									$m3values[$catval["id"]]["title"] = $catval["name"];
									$score = sqlPull(array("table"=>"m3_scores", "where"=>"catid=".$catval["id"]." AND month=".$monthcount." AND year=".$yearcount, "onerow"=>"1"));
									
									// Calculate the weighting of the score {
										$weight = round(($score["score"] * $catval["weight"] / 100), 1);
										$weighttotal += $weight;
										if($conf["present"]=="weight") {
											if(($score["score"]) && ($catval["weight"])) {
												$weight = round(($score["score"] * $catval["weight"] / 100), 1);
											} else if($score["score"]) {
												$score["score"] = 0;
											}
										}
									// }
									
									// If there is no score yet, assign it a value of 0 for $graphdata so there is data, and 'No Value' for $data
									if(!$score["score"]) {
										$data[$datekey][$catval["id"]]									= "No Value";		// Loading $data for spreadsheet presentation
										if($conf["graphdisplay"][$catval["id"]] == 1) {
											$graphdata["values"][$catval["id"]][$datekey]	= 0;					// Loading $graphdata for graph presentation
										}
									} else {
										$data[$datekey][$catval["id"]]									= $score["score"]; // Loading $data for spreadsheet presentation
										$m3values[$catval["id"]]["values"][$datekey]		= $score["score"];
										
										
										if($conf["graphdisplay"][$catval["id"]] == 1) {
											$graphdata["values"][$catval["id"]][$datekey]	= $score["score"]; // Loading $graphdata for graph presentation	
										}
									}
									$graphdata["meta"]["keylist"][$catval["id"]] = $catval["name"];
								}
							}
							
							$monthcount++;
							
							if($weighttotal == 0) {
								$weighteddata[0][$datekey]				= null;
								$m3values[0]["values"][$datekey]	= null;
							} else {
								$weighteddata[0][$datekey]				= round($weighttotal, 0);
								$m3values[0]["values"][$datekey]	= round($weighttotal, 0);
							}
						}
						$yearcount++;
						// If, after incrementing, $yearcount is still <= $endyear, it means there is still another year of data to pull,
						// Hence we go back month 1 of the next year in the search.
						if($yearcount <= $endyear) {
							$monthcount = 1;
						}
					}
				// }
				
				if($conf["present"]=="weight") {
					$spreaddata			= array();
					foreach ($weighteddata[0] as $weightkey=>$weightval) {
						$spreaddata[$weightkey][0]	= round($weightval, 0);
					}
				} else {
					$spreaddata			= $data;
					
					$columncount		= $catcount;
					$tdwidth				= floor(100/$catcount);
				}
				
				$border = "border-style:none none none solid; border-width:0px 0px 0px 1px;";
				
				// Use details to create XML for graph {
					$swfsrc				= BASE."/basefunctions/flashcharts/charts.swf";
					$libpath			= BASE."/basefunctions/flashcharts/charts_library";
					
					$m3values[0]["title"]	= $deptdetails["name"];
					
					$graphvalues	= array();
					if($conf["present"] == 	"weight") {
						$filename			= "m3deptgraph.xml";
						$graphvalues[0]	= $m3values[0];
					} else {
						$filename			= "m3catgraph.xml";
						foreach ($conf["graphdisplay"] as $choicekey=>$choiceval) {
							if($choiceval == 1) {
								$graphvalues[$choicekey]	= $m3values[$choicekey];
							}
						}
					}
					$layout["xmin"]	= 40;
					$layout["xmax"]	= 100;
					
					generateLineGraph($filename, $layout, $graphvalues, $index, $graphkey, 1);
					
					$xmlpaths[]		= BASE."/images/flashxml/".$filename;
				// }
			// }
			
			print("<title>Manline M3</title>");
			maxineHeader("top");
			print("<form id=m3form action='index.php?mode=maxine/index&action=viewm3dept' method=post>");
			
			print("<img src='".TOPBUTTONS."/buttonback.png' onClick=goTo('index.php?mode=maxine/index&action=m3sys')>");
			print("</td></tr>");
			print("<tr><td height=5px></td></tr>");
			
			// Selector Area {
				print("<input type=hidden name=conf[deptid] value=".$conf["deptid"].">");
				print("<input type=hidden id=pointerval name=conf[pointerdirection] value=".$pointerdir.">");
				print("<tr><td align='center'>");
				print("<table class=tray width=40%>");
				
				print("<tr><td class=toprow align='center' colspan=3>");
				print("Date Range");
				print("</td></tr>");
				
				print("<tr><td colspan=3 height=5px></td></tr>");
				
				// Start Date {
					print("<tr><td width=30% align='center'>");
					print("Start Date");
					print("</td><td width=40% align='center'>");
					print("<select name=conf[daterange][startmonth] style='width:90%'>");
					for($i=1; $i<=12; $i++) {
						print("<option ".($startmonth==$i?"selected":"")." value=".$i.">");
						print(date("F", mktime(0, 0, 0, $i, 1, 2007))." (".$i.")");
						print("</option");
					}
					print("</select>");
					print("</td><td width=30% align='center'>");
					print("<select name=conf[daterange][startyear] style='width:90%;'>");
					for($i=2006; $i<=(date("Y")); $i++) {
						print("<option ".($startyear==$i?"selected":"").">");
						print($i);
						print("</option>");
					}
					print("</select>");
					print("</td></tr>");
				// }
				
				// End Date {
					print("<tr><td align='center'>");
					print("End Date");
					print("</td><td align='center'>");
					print("<select name=conf[daterange][endmonth] style='width:90%'>");
					for($i=1; $i<=12; $i++) {
						print("<option ".($endmonth==$i?"selected":"")." value=".$i.">");
						print(date("F", mktime(0, 0, 0, $i, 1, 2007))." (".$i.")");
						print("</option");
					}
					print("</select>");
					print("</td><td align='center'>");
					print("<select name=conf[daterange][endyear] style='width:90%'>");
					for($i=2006; $i<=(date("Y")); $i++) {
						print("<option ".($endyear==$i?"selected":"").">");
						print($i);
						print("</option>");
					}
					print("</select>");
					print("</td></tr>");
				// }
				
				print("<tr><td colspan=3 height=10px></td></tr>");
				
				print("<tr><td align='center'>");
				print("Presentation");
				print("</td><td align='center'>");
				print("<input type='radio' name=conf[present] value='weight' ".($conf["present"]=="weight"?"checked":"").">");
				print("Weighted Total");
				print("</td><td align='center'>");
				print("<input type='radio' name=conf[present] value='component' ".($conf["present"]=="component"?"checked":"").">");
				print("Components");
				print("</td></tr>");
				
				print("<tr><td colspan=3 height=10px></td></tr>");
				
				print("<tr><td colspan=3 align='center'>");
				print("<img src='".TOPBUTTONS."/buttonsubmit.png' onClick='m3form.submit();'>");
				print("</td></tr>");
				
				print("<tr><td colspan=3 height=5px></td></tr>");
				
				print("</table>");
				print("</td></tr>");
			// }
			
			print("<tr><td height=5px></td></tr>");
			
			// Spreadsheet {
				print("<input id=pointerline type=hidden value=0 name=conf[pointerline]>");
				print("<input id=pointerpoint type=hidden value=0 name=conf[pointerpoint]>");
				print("<tr><td align='center'>");
				print("<table class=tray cellspacing=1 cellpadding=0 width=80%>");
				
				print("<tr><td class=toprow colspan=".$columncount." align='center' onClick='switchGraphs();'>");
				print($deptdetails["name"]);
				print("</td></tr>");
				
				print("<tr>");
				if($columnlist) {
					print("<td width=".$tdwidth." align='center'>");
					print("Date");
					print("</td>");
					foreach ($columnlist as $colkey=>$colval) {
						if($conf["graphdisplay"][$colval["id"]] == 1) {
							$colborder		= "border-style: inset; border-width: 2px; border-color: ".MAXINETOP."; cursor: pointer;";
						} else {
							$colborder		= "border-style: outset; border-width: 2px; border-color: ".MAXINETOP."; cursor: pointer;";
						}
						$colonclick	= "onclick='changeCol(".$colval["id"].");'";
						
						print("<input type=hidden id=coltop".$colval["id"]." name=conf[graphdisplay][".$colval["id"]."] value=".$conf["graphdisplay"][$colval["id"]].">");
						print("<td align='center' id=coltd".$colval["id"]." width=".$tdwidth."% style='".$colborder."' ".$colonclick." >");
						print($colval["name"]);
						print("</td>");
					}
				}
				print("</tr>");
				
				$oldrow				= 0;
				$rowcolour		= MAXINEBACKALT;
				$maxvalue			= 0;
				$minvalue			= 100;
				if($spreaddata) {
					foreach ($spreaddata as $rowkey=>$rowval) {
						$count	= 0;
						if($rowcolour == MAXINEBACKALT) {
							$rowcolour = MAXINEBACK;
						} else {
							$rowcolour = MAXINEBACKALT;
						}
						
						print("<tr bgcolor='".$rowcolour."'>");
						print("<td align='center'>");
						print($graphdata["meta"]["divtitles"][$rowkey]);
						print("</td>");
						foreach ($columnlist as $colkey=>$colval) {
							// Preparation per Column {
								$olddata			= $spreaddata[$oldrow][$colval["id"]];
								$currentdata	= $rowval[$colval["id"]];
								$title				= "";
								$bgcolour			= "";
								$note					= "";
								
								if($olddata == $currentdata) {
									$style = "style='color: black; font-family: verdana; font-size:11; height:8;'";
									$title = "title='No Change'";
								} else if(($currentdata == "No Value") || ($olddata == "No Value") || ($oldrow == 0)) {
									$style = "style='color: black; font-family: verdana; font-size:11; height:8;'";
									$title = "title='Insufficient Data'";
								} else if ($olddata > $currentdata) {
									$style = "style='color: red; font-family: verdana; font-size:11; height:8; font-weight:bold'";
									$title = "title='Fell by ".($olddata - $currentdata)."'";
									$note	= "-";
								} else if ($olddata < $currentdata) {
									$style = "style='color: green; font-family: verdana; font-size:11; height:8; font-weight:bold'";
									$title = "title='Rose by ".($currentdata - $olddata)."'";
									$note	= "+";
								}
								
								if(($colkey == $conf["pointerline"]) && ($rowkey==$conf["pointerpoint"])) {
									$bgcolour	= "bgcolor='".$colours[$colkey]."'";
									$style		= "style='color: white; font-family: verdana; font-size:11; height:8; font-weight:bold'";
								} else {
									$note = ""; // Reset to blank in the case of tds that are not selected.
								}
								$tdonclick = "onclick='pointerline.value=".$colkey."; pointerpoint.value=".$rowkey."; m3form.submit();'";
								
								if($conf["graphdisplay"][$colval["id"]] == 1) {
									if(($maxvalue < $currentdata) && ($currentdata > 0)) {
										$maxvalue = $currentdata;
									}
									if(($minvalue > $currentdata) && ($currentdata >= 0)) {
										$minvalue = $currentdata;
									}
								}
							// }
							
							print("<td ".$title." ".$tdonclick." ".$bgcolour." align='right' style='".$border."'>");
							print($currentdata.$note);
							print("</td>");
							
							$count++;
						}
						print("</tr>");
						$oldrow = $rowkey;
					}
				}
				
				print("</table>");
				print("</td></tr>");
			// }
			
			// Graph {
				// Details {
					$showgraph = 0;
					
					$graphtop			= (ceil($maxvalue/5) * 5);
					$graphbottom	= (floor($minvalue/5) * 5);
					$numrows			= $graphtop - $graphbottom;
					if($numrows <= 10) {
						$numrows			= $numrows / 2 + 1;
						$graphtop			+= 2;
					} else if($numrows > 25) {
						$numrows			= $numrows / 10 + 1;
						$graphtop			+= 10;
					} else {
						$numrows			= $numrows / 5 + 1;
						$graphtop			+= 5;
					}
					
					if($conf["present"]=="weight") {
						$graphdata["meta"]["name"]		= "deptgraphweight";
						$graphdata["values"]					= $weighteddata;
						$graphdata["meta"]["keylist"]	= array(0=>"Weighted Total");
						//$colours[0] = "DD1111";
						if($conf["graphdisplay"][0] == 1) {
							$showgraph = 1;
						}
					} else {
						$graphdata["meta"]["name"]				= "deptgraphcomponent";
						foreach ($conf["graphdisplay"] as $displaykey=>$displayval) {
							if(($displayval == 1) && ($displaykey != 0)) {
								$showgraph = 1;
							}
						}
					}
					
					$graphdata["meta"]["height"]			= 400;
					$graphdata["meta"]["width"]				= 700;
					
					$graphdata["meta"]["topval"]		= $graphtop;
					$graphdata["meta"]["bottomval"]	= $graphbottom;
					$graphdata["meta"]["rows"]			= $numrows;
					
					$graphdata["meta"]["keywidth"]		= 190;
					$graphdata["meta"]["keysperrow"]	= 4;
					$graphdata["meta"]["title"]				= "M3 for ".$deptdetails["name"];
					$graphdata["meta"]["subtitle"]		= date("F", mktime(0, 0, 0, $startmonth, 1, 2007))." ".$startyear." - ";
					$graphdata["meta"]["subtitle"]		.= date("F", mktime(0, 0, 0, $endmonth, 1, 2007))." ".$endyear;
					
					$graphdata["meta"]["colours"]			= $colours;
					$graphdata["meta"]["onclick"]			= "pointerval.value=".($pointerdir+1)."; m3form.submit();";
					
					if(($conf["pointerline"]) && ($conf["pointerline"] > 0)) {
						$graphdata["pointer"][$conf["pointerline"]] = $conf["pointerpoint"];
						$graphdata["pointer"]["direction"]					= $pointerdir;
					}
				// }
				
				print("<tr><td height=12px></td></tr>");
				
				print("<tr><td align='center'>");
				print("<table cellspacing=0 cellpadding=0>");
				
				print("<tr id=oldgraphrow><td>");
				if($showgraph == 1) {
					drawLineGraphX($graphdata);
				} else {
					print("Insufficient data for Graph.");
				}
				print("</td></tr>");
				
				// Flashgraph {
					print("<tr id=newgraphrow style='display: none'><td align='center'>");
					
					print("<EMBED src='".$swfsrc."?library_path=".$libpath."&xml_source=".BASE."/images/flashxml/".$filename."' 
						quality=high 
						bgcolor=".MAXINEBACKALT."  
						WIDTH='600' 
						HEIGHT='400'
						NAME='charts' 
						ALIGN='' 
						swLiveConnect='true' 
						TYPE='application/x-shockwave-flash' 
						PLUGINSPAGE='http://www.macromedia.com/go/getflashplayer'>");
					print("</EMBED>");
					
					print("</td></tr>");
				// }
				
				print("</table>");
				print("</td></tr>");
			// }
			
			print("</form>");
			maxineFoot();
			
			// Script {
				print("<script type='text/javascript'>
					function changeCol(colid) {
					viewchoice = document.getElementById('coltop'+colid).value;
					if(viewchoice == 1) {
					document.getElementById('coltop'+colid).value = 0;
					document.getElementById('coltd'+colid).style.border = 'outset';
					} else {
					document.getElementById('coltop'+colid).value = 1;
					document.getElementById('coltd'+colid).style.border = 'inset';
					}
					
					document.getElementById('coltd'+colid).style.borderColor = '".MAXINETOP."';
					document.getElementById('coltd'+colid).style.borderWidth = '2px';
					}
					
					function switchGraphs() {
					document.getElementById('oldgraphrow').style.display = 'none';
					document.getElementById('newgraphrow').style.display = '';
					}
					
					</script>");
			// }
		}
		
		function editM3Depts() {
			// Preparation {
				$deptlist = sqlPull(array("table"=>"m3_departments", "where"=>"1=1"));
				$count		= 0;
			// }
			
			maxineTop("Departments");
			print("<title>Manline M3</title>");
			print("<form name='departmentsform' id='departmentsform' action='index.php?mode=maxine/index&action=commitm3depts' method='post'>");
			
			// Buttons {
				openHeader();
				maxineButton("Add", "addRow();", 2);
				maxineButton("Submit", "departmentsform.submit();", 2);
				maxineButton("Back", "onclick=goTo(\"index.php?mode=maxine/index&action=m3sys\");", 2);
				closeHeader();
			// }
			
			print("<div class='tray'>");
			
			// List of Departments {
				openSubbar(500);
				print("Details");
				closeSubbar();
				print("<table id='depttable' class='standard' style='width:500px; margin-bottom:25px;'>");
				
				// Headers {
					print("<tr class='heading'><td align='center' width=90%>");
					print("Description");
					print("</td><td align='center' width=10%>");
					print("Show");
					print("</td></tr>");
				// }
				
				foreach ($deptlist as $deptkey=>$deptval) {
					print("<input type='hidden' name='conf[depts][".$count."][id]' value=".$deptval["id"].">");
					
					print("<tr class='content1'><td align='center'>");
					print("<input name='conf[depts][".$count."][name]' value='".$deptval["name"]."' style='width:400px;'>");
					print("</td><td align='center'>");
					print("<input type='checkbox' name='conf[depts][".$count."][display]' ".($deptval["display"]==1?"checked":"").">");
					print("</td></tr>");
					$count++;
				}
				
				print("</table>");
			// }
			
			print("</div>");
			
			print("</form>");
			maxineBottom();
			
			// Javascript {
				print("<script>
					var rowCnt = ".$count.";
					
					function addRow() {
						tblref = document.getElementById('depttable');
						row = tblref.insertRow(rowCnt+1);
						row.className = 'content1';
						
						cell = row.insertCell(0);
						str = '<input name=\"conf[depts]['+rowCnt+'][name]\" style=\"width:400px;\" value=\"Department Name '+(rowCnt + 1)+'\">';
						cell.innerHTML = str;
						
						cell = row.insertCell(1);
						str = '<input type=\"checkbox\" name=\"conf[depts]['+rowCnt+'][display]\" checked>';
						cell.innerHTML = str;
						
						rowCnt++;
					}
				</script>");
			// }
		}
		
		function commitM3Depts() {
			if($_POST["conf"]) {
				$conf = $_POST["conf"];
			}
			
			foreach ($conf["depts"] as $deptkey=>$deptval) {
				if($deptval["display"] == "on") {
					$deptval["display"]	= 1;
				} else {
					$deptval["display"]	= 0;
				}
				
				if($deptval["id"]) {
					updateM3Dept($deptval);
				} else {
					createM3Dept($deptval);
				}
			}
			goHere("index.php?mode=maxine/index&action=m3sys");
		}
	// }
?>
