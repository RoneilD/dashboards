<?PHP
	function testRights($userid, $pageid) {
		$usergroups = sqlPull(array("table"=>"rights_users", "where"=>"userid = ".$userid));
		
		$level = 0;
		if($usergroups) {
			foreach ($usergroups as $uskey=>$usval) {
				$pageright = sqlPull(array("table"=>"rights_pages", "where"=>"pagecode LIKE '".$pageid."' AND groupid = ".$usval["groupid"], "onerow"=>1));
				
				if(($pageright) && ($pageright["level"] > $level)) {
					$level = $pageright["level"];
				}
			}
		}
		return $level;
	}
	
	function rightsControl() {
		maxineHeader("top");
		print("<input type='button' class=toprow value='Groups' onClick=goTo('index.php?mode=maxine/index&action=rightsgroups')>");
		print("<input type='button' class=toprow value='Back' onClick=goTo('index.php?mode=maxine/index&action=firstmenu')>");
		print("</td></tr>");
		
		print("<tr><td align='center'>");
		print("<table class=tray width=60% cellspacing=1 cellpadding=0>");
		
		print("<tr bgcolor='".MAXINETOP."'><td align='center'>");
		print("<font class=standard><b>RIGHTS CONTROL</b></font>");
		print("</td></tr>");
		
		print("</table>");
		maxineFoot();
	}
	
	function rightsGroups() {
		// Preparation {
			$grouplist = sqlPull(array("table"=>"rights_groups", "where"=>"1=1"));
		// }
		
		maxineTop("Access Control");
		print("<form method='post' name='rightsgroupform' action='index.php?mode=maxine/index&action=editrightsgroup'>");
		
		openHeader();
		maxineButton("Add", "rightsgroupform.submit();", 2);
		maxineButton("Back", "goTo(\"index.php?mode=maxine/index&action=firstmenu\");", 2);
		closeHeader();
		
		print("<div class='tray'>");
		
		openSubbar(400);
		print("Rights Groups");
		closeSubbar();
		
		print("<table id='grouptable' class='standard' style='width:400px;'>");
		
		print("<input type='hidden' id='groupidtag' name='conf[groupid]' value=0>");
		if($grouplist) {
			foreach ($grouplist as $grpkey=>$grpval) {
				$onclick	= "document.getElementById(\"groupidtag\").value=".$grpval["id"]."; rightsgroupform.submit();";
				print("<tr class='content1' onClick='".$onclick."'><td align='center' title='".$grpval["description"]."'>");
				print($grpval["name"]);
				print("</td></tr>");
			}
		} else {
			print("<tr><td align='center'>");
			print("<font class=colheading>No Rights Groups yet.</font>");
			print("</td></tr>");
		}
		
		print("</table>");
		
		print("</div>");
		closeTrayDiv();
		
		print("</form>");
		maxineBottom();
	}
	
	function editRightsGroup() {
		// Preparation {
			if($_POST["conf"]) {
				$conf = $_POST["conf"];
			}
			
			if($conf["groupid"] > 0) {
				$group = sqlPull(array("table"=>"rights_groups", "where"=>"id=".$conf["groupid"], "onerow"=>1));
			}
		// }
		
		maxineTop("Access Control");
		print("<form name='editgroupform' action='index.php?mode=maxine/index&action=updaterightsgroup' method='post'>");
		
		// Buttons {
			openHeader();
			maxineButton("Update", "editgroupform.submit();", 2);
			maxineButton("Back", "goTo(\"index.php?mode=maxine/index&action=rightsgroups\");", 2);
			closeHeader();
		// }
		
		print("<div class='tray'>");
		
		// Details {
			openSubbar(600);
			if($group) {
				print("'".$group["name"]."' Rights Group");
				print("<input type='hidden' name=conf[groupid] value=".$group["id"].">");
			} else {
				print("New Right Groups");
			}
			closeSubbar();
			
			print("<table class='standard' style='width:600px;'>");
			
			print("<tr class='content1'><td width=30% align='center'>");
			print("Name");
			print("</td><td width=70%>");
			print("<input name=conf[name] value='".$group["name"]."' style='width: 100%;'>");
			print("</td></tr>");
			
			print("<tr class='content1'><td align='center'>");
			print("Description");
			print("</td><td>");
			print("<textarea name=conf[desc] style='width: 100%;'>".$group["description"]);
			print("</textarea>");
			print("</td></tr>");
			
			print("</table>");
		// }
		
		print("</div>");
		closeTrayDiv();
		
		print("</form>");
		maxineBottom();
	}
	
	function updateRightsGroup() {
		$conf = $_POST["conf"];
		
		commitRightsGroup($conf);
		goHere("index.php?mode=maxine/index&action=rightsgroups");
	}
	
	function pageRights() {
		// Preparation {
			$code				= $_GET["code"];
			$groups			= sqlPull(array("table"=>"rights_groups", "where"=>"1=1"));
		// }
		
		print("<form id=pagerightsform action='index.php?mode=maxine/index&action=updatepagerights' method='post'>");
		maxineHeader("top");
		
		// Buttons {
			print("<img src='".TOPBUTTONS."/buttonupdate.png' onClick='pagerightsform.submit();'>");
			print("<img src='".TOPBUTTONS."/buttonback.png' onClick=goTo('index.php?mode=maxine/index&action=firstmenu');>");
			print("</td></tr>");
		// }
		
		print("<tr><td align='center'>");
		
		print("<table class=tray width=60%>");
		print("<tr><td class=toprow align='center' colspan=2>");
		print("<font class=heading>".$code."</font>");
		print("</td></tr>");
		
		print("<tr><td align='center'>");
		print("<font class=colheading>Group</font>");
		print("</td><td align='center'>");
		print("<font class=colheading>Access</font>");
		print("</td></tr>");
		
		print("<input type='hidden' name=conf[pagecode] value='".$code."'>");
		
		$count = 0;
		foreach ($groups as $grpkey=>$grpval) {
			// Individual Preparation {
				$pagerights	= sqlPull(array("table"=>"rights_pages", "where"=>"pagecode LIKE '".$code."' AND groupid = ".$grpval["id"], "onerow"=>1));
			// }
			print("<input type='hidden' name=conf[groups][".$count."][groupid] value=".$grpval["id"].">");
			print("<tr><td align='center' width=90%>");
			print("<font class=standard>".$grpval["name"]."</font>");
			print("</td><td align='center' width=10%>");
			print("<input type='checkbox' name=conf[groups][".$count."][access] value=1 ".($pagerights["level"]==1?"checked":"").">");
			print("</td></tr>");
			$count++;
		}
		
		print("</table>");
		
		maxineFoot();
		print("</form>");
	}
	
	function updatePageRights() {
		$conf = $_POST["conf"];
		
		sqlDelete(array("table"=>"rights_pages", "where"=>"pagecode LIKE '".$conf["pagecode"]."'"));
		
		foreach ($conf["groups"] as $grpkey=>$grpval) {
			if(!$grpval["access"]) {
				$grpval["access"] = 0;
			}
			commitPageRights($conf["pagecode"], $grpval);
		}
		goHere("index.php?mode=maxine/index&action=pagerights&code=".$conf["pagecode"]);
	}
?>
