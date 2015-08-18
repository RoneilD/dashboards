<?PHP
	function maxineTop($title="") {
		// Prep {
			## User Data
			$mycapsSegments = new TableManager("users");
			$mycapsSegments->setWhere(
			  $mycapsSegments->quoteString("`users`.`personid`=?", (int)$_SESSION["userid"])
			);
			$user = $mycapsSegments->selectSingle();
			
			//$user				= sqlPull(array("table"=>"users", "where"=>"personid=".$_SESSION["userid"], "select"=>"firstname, lastname, user_profiles_id", "onerow"=>1));
			$smsaccess	= testRights($_SESSION["userid"], "sms001");
			
			## myCAPS segment check
			if (isset($_SESSION["userid"]) && $_SESSION["userid"]) {
				$mycapsSegments = new TableManager("mycaps_segments");
				$mycapsSegments->setWhere(
					$mycapsSegments->quoteString("`mycaps_segments`.`userid`=?", (int)$_SESSION["userid"]).
					$mycapsSegments->quoteString(" AND `mycaps_segments`.`finalized`=?", 0)
				);
				$segment = $mycapsSegments->selectSingle();
			}
			
			## Profile Data
			if ($user) {
				$user_profiles = new TableManager("user_profiles");
				## Test for fortune column
				$cols = $user_profiles->getColumns();
				if (in_array("fortune", $cols) === FALSE) {
				  $sql = (string)"ALTER TABLE `user_profiles` ADD COLUMN `fortune` TINYINT(1) NOT NULL DEFAULT 0, ADD INDEX (`fortune`);";
				  if (($user_profiles->runSql($sql)) === FALSE) {
				    $errors = $user_profiles->getErrors();
				    echo "<div class=\"error\">mySQL statement encountered an error. Last error was:<br />".$errors[count($errors)-1]."</div>";
				    return FALSE;
				  }
				}
				$user_profiles->setWhere(
				  $user_profiles->quoteString("`user_profiles`.`id`=?", $user["user_profiles_id"])
				);
				$profile = $user_profiles->selectSingle();
				
			}
			
			## Alerts
			$manager = new TableManager("alerts");
			$manager->setWhere(
			  $manager->quoteString("`alerts`.`deleted`=?", (int)0).
			  $manager->quoteString(" AND `alerts`.`time_start`<=?", strtotime(date("Y-m-d")." 00:00")).
			  $manager->quoteString(" AND `alerts`.`time_end`>=?", strtotime(date("Y-m-d")." 23:59"))
			);
			$manager->setCustomIndex("id");
			$alerts = $manager->selectMultiple();
			
			## Theme
			$themes = new TableManager("themes");
			$themes->setQueryColumns(array(
			  "themes"=>array("*"),
			));
			$themes->setQueryFrom(array("left join"=>array(
			  0=>array(
			    "table"=>array("abbr"=>"user_profiles", "table"=>"user_profiles"),
			    "on"=>"`themes`.`id`=`user_profiles`.`theme_id`"
			  ),
			  1=>array(
			    "table"=>array("abbr"=>"users", "table"=>"users"),
			    "on"=>"`user_profiles`.`id`=`users`.`user_profiles_id`"
			  ),
			)));
			$where = (string)"(ISNULL(`themes`.`deleted`)";
			$where .= $themes->quoteString(" OR `themes`.`deleted`=?)", 0);
			if ($_SESSION["userid"]) {
			  $where .= $themes->quoteString(" AND `users`.`personid`=?", (int)$_SESSION["userid"]);
			} else {
			  $where .= $themes->quoteString(" AND `themes`.`id`=?", (int)1);
			}
			$themes->setWhere($where);
			$theme = $themes->selectSingle();

			$backgroundImage = (string)BASE."images/new/themes/".$theme["background-image"];
			$_SESSION["backgroundRepeat"] = (string)BASE."images/new/themes/".$theme["background-repeater"];
			
			$size = getimagesize($backgroundImage);
			$_SESSION["bgImageSize"] = $size;
		// }
		print("<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>");
		
		print("<html>");
		
		// Header Info {
			print("<head>");
			
			print("<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />".PHP_EOL);
			
			print("<link href='".BASE."/images/favicon.ico' rel='SHORTCUT ICON' />".PHP_EOL);
			//print("<link href='".BASE."/basefunctions/scripts/manlinecss.php' rel='stylesheet' type='text/css' />");
			print("<link href='".BASE."/basefunctions/scripts/manline.css' media='all' rel='stylesheet' type='text/css' />".PHP_EOL);
			
			print("<script type='text/javascript' language='javascript' src='".BASE."/basefunctions/scripts/manline.js'></script>");
			$headTitle = (string)"Maxweb";
			if ($title) {
				$headTitle .= " - ".$title;
			}
			print("<title>".$headTitle."</title>".PHP_EOL);
			
			print("</head>");
		// }
		
		print("<body id=\"windowBody\" style='background-image:url(\"".$backgroundImage."\");'>");
		//print("<form action='#' method='post'>");
		
		// Header 1 {
			print("<h1>");
			print("<img alt=\"Maxweb Logo\" id=\"maxwebLogo\" src='".BASE."/images/new/logo.png' style=\"height:58px;margin-top:2px;width:299px;\">");
			
			print("<span style='float:right; text-align:right;'>");
			
			
			$links = (array)array(
				"Home"=>array(
					"class"=>"headerA",
					"href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?action=home",
					"id"=>"home"
				),
				"Documents"=>array(
					"class"=>"headerA",
					"href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?action=viewdocuments",
					"id"=>"Documents"
				),
				"Gallery"=>array(
					"class"=>"headerA",
					"href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?action=viewgallery",
					"id"=>"Gallery"
				),
				"InOut"=>array(
					"class"=>"headerA",
					"href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?action=inoutboard",
					"id"=>"InOut"
				),
				"Max"=>array(
					"class"=>"headerA",
					"href"=>"http://login.max.manline.co.za",
					"id"=>"Max",
					"target"=>"_blank"
				),
				"Zimbra"=>array(
					"class"=>"headerA",
					"href"=>"http://mail.manline.co.za",
					"id"=>"Zimbra",
					"target"=>"_blank"
				)
			);
			foreach ($links as $text=>$data) {
			        echo("<a class=\"".$data["class"]."\" href=\"".$data["href"]."\" id=\"".$data["id"]."\" ".(isset($data["target"]) ? "target=\"".$data["target"]."\" " : "")."title=\"".$text."\"></a>".PHP_EOL);
			}
			
			print("</span>");
			
			print("</h1>");
		// }
		
		// Header 2 {
			$messages = (array)array();
			if (isset($profile) && $profile) {
			  if (!$profile["staffno"] || !$profile["department_id"] || !$profile["jobtitle"] || !$profile["location"]) {
			    $messages[] = "Your profile information is incomplete.";
			  }
			}
			if (isset($segment)) {
				if (date("U") > ((int)$segment["enddate"]+86400)) {$messages[] = "Your CAPS is overdue";}
			}
			if (isset($alerts) && $alerts) {
			  foreach ($alerts as $val) {
			    $messages[] = $val["message"];
			  }
			}
			## Profile Data
			if ($messages) {informationBar($messages);}
			print("<h2>");
			
			print("<span style='width:10px; height:51px; background-image:url(\"".BASE."/images/new/menubarleft.png\"); float:left;'></span>");
			print("<span style='width:850px; height:36px; background-image:url(\"".BASE."/images/new/menubarmid.png\"); float:left; padding-top:15px;'>");
			
			// Left Span - Quick Navigation {
				print("<span style='float:left; margin-top:-5px;'>");
				## Menu
				// $items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/", "text"=>"", "title"=>"");
				
				$items = (array)array();
				if ($user) {$items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=mycapslist2", "text"=>"myCAPS", "title"=>"click to go to your CAPs");}
				$items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=showm3", "text"=>"Public M3", "title"=>"View public M3 Graphs");
				$items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=dockeeperfront", "text"=>"Customer Documents", "title"=>"View Documents required by Customers");
				if ($user) {
					$items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=m3sys", "text"=>"M3", "title"=>"View M3 Graphs");
					$items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=displaymydash", "text"=>"Personal Dashboard", "title"=>"Click to view your personal dashboard");
					$items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=greenmileinput", "text"=>"Green Mile Controls", "title"=>"Click to update Green Mile Details");
					$items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=ratescalc2", "text"=>"Rates Calculator", "title"=>"Click to view the rates calculator");
					if ($user["isit"] || $user["ismanager"]) {
						$items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=nineblockinput", "text"=>"9 Blocker", "title"=>"Click to view your 9 Blocker");
					  $items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?action=listalerts", "text"=>"Alerts", "title"=>"Click to list all alerts");
					  $items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?action=edituserpool", "text"=>"User Pools", "title"=>"Click to view and edit User Pools");
					}
					$items[] = array("text"=>"Faults System", "children"=>array(
						//array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=driverfaults", "text"=>"Driver Faults", "title"=>"Click to view driver faults"),
						array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=equipfaults", "text"=>"Equipment Faults", "title"=>"Click to view equipment faults"),
						array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=uflist", "text"=>"Unit Faults", "title"=>"Click to view unit faults"),
						array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=faultsys", "text"=>"Fault Logging", "title"=>"Log a new fault")
					));
					$items[] = array("text"=>"Personnel Tools", "children"=>array(
						array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=learnerlist", "text"=>"Learner", "title"=>"Click to view a list of learners"),
						array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=candmenu", "text"=>"Candidates", "title"=>"Click to view list of candidates"),
						array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=driverslist", "text"=>"Drivers", "title"=>"Click to view list of drivers"),
						array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=listusers", "text"=>"Users", "title"=>"Click to view user list")
					));
					$items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?action=updateprofile", "text"=>"Edit Your Profile", "title"=>"Click to edit your profile");
				}
				/** Cameras link affects
				  4 = > Jonathan Spencer
				  5 = > Bradley Roberts
				  141 = > Jerome Govender
				  168 = > Lwazi Ally
				  186 = > Ndumiso Langa
				*/
				if (in_array($_SESSION["userid"], array(4, 5, 141, 168, 186))) {
				  $items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/cameras/", "text"=>"Manline CCTV", "title"=>"Click to view the cameras");
				}
				if($_SESSION["isadmin"] || ($smsaccess > 0)) {
					$items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=smssystem", "text"=>"Send an SMS", "title"=>"Click to use the SMS system");
				}
				if($_SESSION["isit"] == 1) {
					$items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=rightscontrol", "text"=>"Access Rights", "title"=>"Click to set user access rights");
					$items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=loggingreport", "text"=>"Logging Report", "title"=>"Click to view the logging report");
					if($_SESSION["isadmin"]) {
						$items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=smssystem", "text"=>"Send an SMS", "title"=>"Click to use the SMS system");
					}
					$items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=sandbox", "text"=>"Sandbox", "title"=>"Click to go to the coding sandbox");
					//$items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=encoder", "text"=>"Text Encoder", "title"=>"Click to view the text encoder");
					//$items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=truckfinder", "text"=>"Truck Finder", "title"=>"Click to view the truck finder");
					$items[] = array("text"=>"Scaffolding", "children"=>array(
						array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=list&name=documents", "text"=>"Documents", "title"=>"Click to list all items"),
						array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=list&name=gallery", "text"=>"Gallery", "title"=>"Click to list all items"),
						array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=list&name=galleryItems", "text"=>"Gallery Items", "title"=>"Click to list all items"),
						array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=list&name=newspaper_articles", "text"=>"Newspaper Articles", "title"=>"Click to list all items"),
						array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=list&name=pages", "text"=>"Pages", "title"=>"Click to list all items"),
						array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=list&name=themes", "text"=>"Themes", "title"=>"Click to list all items"),
						array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=list&name=type", "text"=>"Type", "title"=>"Click to list all items"),
						array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?mode=maxine/index&action=list&name=user_profiles", "text"=>"User Profiles", "title"=>"Click to list all items")
					));
				}
				/* $items[] = array("text"=>"Corporate Clothing", "children"=>array(
					array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/documents/Ladies Clothing Catalogue.pdf", "text"=>"Ladies", "title"=>"Click to download ladies clothing catalogue"),
					array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/documents/Mens Clothing Catalogue.pdf", "text"=>"Mens", "title"=>"Click to download mens clothing catalogue")
				)); */
				$items[] = array("href"=>"/Maxine/documents/ManlinePriceList.pdf", "text"=>"Corporate Clothing", "title"=>"Click to download");
				$items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/documents/Map to Head Office.pdf", "text"=>"Map to Head office", "title"=>"Click to download map to head office");
				$items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?action=maxexpress", "text"=>"Max Express", "title"=>"Click to view Max Express editions");
				$items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?action=viewnews", "text"=>"News", "title"=>"Click to view newspaper articles");
				$items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?action=monthlyvideos", "text"=>"Monthly Videos", "title"=>"Click to view Neil's monthly video updates");
				$sql = (string)"SELECT * FROM `m3_departments`";
				$departs = $themes->runSql($sql);
				$depts = (array)array();
				foreach ($departs as $dept) {
					$depts[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?action=listdepartment&department=".urlencode($dept["name"]), "text"=>$dept["name"], "title"=>"Click to view department members");
				}
				$items[] = array("href"=>"http://".$_SERVER["SERVER_NAME"]."/Maxine/index.php?action=listdepartment", "text"=>"Staff Profiles", "title"=>"Click to view all staff members", "children"=>$depts);
				echo(menu($items));
				print("</span>");
			// }
			
			// Right Span - Login Details {
				print("<span style='height:34px; float:right; margin-top:-5px; vertical-align:bottom;'>");
				
				if($_SESSION["userid"] > 0) {
					print("<p class='standard' style='float:left; margin-right:10px; margin-top:8px;'>Logged in as ".$profile["firstname"]." ".$profile["lastname"]."</p>");
					
					echo("<a href=\"/Maxine/?logout\" id=\"logoutbutton\"></a>".PHP_EOL);
				} else {
					$name = "";
					if($_COOKIE["loggedname"]) {
						$name = $_COOKIE["loggedname"];
					}
					print("<form id=\"loginform\" name='loginform' action='index.php?mode=maxine/index&action=loginaction' method='post'>");
					print("<span style='width:16px; height:19px; background-image:url(\"".BASE."/images/new/loginicon.png\"); float:left; margin-top:5px;'></span>");
					
					print("<span style='width:10px; height:25px; background-image:url(\"".BASE."/images/new/loginleft.png\"); float:left; margin-left:10px; margin-top:4px; '></span>");
					print("<input id=\"username\" name='conf[username]' value='".$name."' class='loginmid' onKeyPress='submitenter(this,event);' />");
					print("<span style='width:10px; height:25px; background-image:url(\"".BASE."/images/new/loginright.png\"); float:left; margin-top:4px; '></span>");
					
					print("<span style='width:10px; height:25px; background-image:url(\"".BASE."/images/new/loginleft.png\"); float:left; margin-left:10px; margin-top:4px;'></span>");
					print("<input type='password' id='passbox' name='conf[password]' class='loginmid' onKeyPress='submitenter(this,event);' />");
					print("<span style='width:10px; height:25px; background-image:url(\"".BASE."/images/new/loginright.png\"); float:left; margin-top:4px; '></span>");
					
					echo("<a href=\"#\" id=\"loginbutton\" onclick=\"document.getElementById('loginform').submit();\"></a>".PHP_EOL);
					
					print("</form>");
				}
				
				print("</span>");
			// }
			
			print($title);
			
			print("</span>");
			print("<span style='width:10px; height:51px; background-image:url(\"".BASE."/images/new/menubarright.png\"); float:left;'></span>");
			
			print("</h2>");
			if (isset($_SESSION["userid"]) && in_array($_SESSION["userid"], array(4, 23, 175)) || (isset($profile["fortune"]) && $profile["fortune"])) {
				$fortune = shell_exec("fortune -s -n 100");
				echo "<div style=\"height:24px;margin:-14px auto 15px;position:relative;width:850px;\">";
				echo "<img alt=\"left corner\" src=\"".BASE."images/new/cookiebar_left.png\" style=\"height:24px;left:0px;position:absolute;top:0px;width:10px;\" />";
				echo "<div style=\"background-image:url(".BASE."images/new/cookiebar_mid.png);font-size:0.8em;height:24px;margin:-3px auto 0px;vertical-align:top;width:830px;\">".$fortune."</div>";
				echo "<img alt=\"right corner\" src=\"".BASE."images/new/cookiebar_right.png\" style=\"height:24px;position:absolute;right:0px;top:0px;width:10px;\" />";
				echo "</div>";
			}
		// }
	}
	
	function maxineBottom() {
		print("</div>");
		echo("<script type=\"text/javascript\">
		var iframe = document.createElement('IFRAME');
		iframe.setAttribute('id', 'scumOnload'), bind = {keyPresses:[]};
		iframe.onload = function() {handleBackgroundRepeat(this);}
		iframe.setAttribute('style', 'border:none;height:1px;position:absolute;');
		document.body.appendChild(iframe);
		function getOffset(el) {
    		var _x = 0, _y = 0;
    		while( el && !isNaN( el.offsetLeft ) && !isNaN( el.offsetTop ) ) {
        		_x += el.offsetLeft - el.scrollLeft;
        		_y += el.offsetTop - el.scrollTop;
        		el = el.parentNode;
    		}
    		return { top: _y, left: _x };
		}
		
		function handleBackgroundRepeat(elem) {
			var maxBgSize = ".($_SESSION["bgImageSize"][1] ? $_SESSION["bgImageSize"][1] : 1500).", originalBG = parent.document.body.style.backgroundImage, top = getOffset(elem).top, wrapperdiv = document.createElement('DIV');
			if (parseInt(top) > parseInt(maxBgSize)) {
				parent.document.getElementById('windowBody').setAttribute('style', 'background-image:url(\"".$_SESSION["backgroundRepeat"]."\");background-repeat:repeat-y;');
				wrapperdiv.setAttribute('style', 'background-image:'+originalBG+';background-position: center top;height:".$_SESSION["bgImageSize"][1]."px;left:0px;position:absolute;top:0px;width:100%;z-index:-10;');
				parent.document.getElementById('windowBody').appendChild(wrapperdiv);
			}
		}
		
		document.onclick = function() {
		  navigation.hideAll();
		}
		bind.checkForKonamiCode = function (e) {
		  if (!e) {e = window.event;}
		  if (e.keyCode == 27) {
		    document.getElementById('maxwebLogo').src='".BASE."images/new/logo.png';
		    bind.keyPresses = [];
		    return false;
		  }
		  if (bind.keyPresses.length > 9) {bind.keyPresses.shift();}
		  bind.keyPresses.push(e.keyCode);
		  var i, konamiCode = [38,38,40,40,37,39,37,39,66,65],retVal=true;
		  for (i=0;i<konamiCode.length;i++) {
		    if (!bind.keyPresses[i]) {retVal = false;break;}
		    if (bind.keyPresses[i] != konamiCode[i]) {retVal = false;break;}
		  }
		  if (retVal == true) {document.getElementById('maxwebLogo').src='".BASE."images/konami_logo.png';}
		};
		window.onkeyup = bind.checkForKonamiCode;
		</script>".PHP_EOL);
		echo("<script src=\"".BASE."basefunctions/scripts/navigation.js\" type=\"text/javascript\"></script>".PHP_EOL);
		print("</body>");
		print("</html>");
	}
	
	/** menu(array $items)
	 * create a menu
	 * @param array $items what items do you want to display?
	 * @return string html menu
	 */
	function menu(array $items)
	{
		// $html .= "".PHP_EOL;
		$html = (string)"<div style=\"float:left;position:relative;width:220px;\">".PHP_EOL;
		$html .= "<img alt=\"left cap\" src=\"".BASE."images/new/droplist_left.png\" style=\"height:22px;left:4px;margin-top:5px;position:absolute;top:0px;width:6px;\" />".PHP_EOL;
		$html .= "<ul class=\"menu\" id=\"navigationMenu\" onclick=\"events.cancelBubble(event);navigation.toggle(this);\">".PHP_EOL;
		$html .= "<li><a href=\"#\" onclick=\"return false;\" title=\"Go to\">Jump to&hellip;</a></li>".PHP_EOL;
		foreach ($items as $item) {
			if (isset($item["children"]) && $item["children"]) {
				$mouseevents = (string)"onmouseover=\"navigation.showSubMenu(this, 'ul');\" ";
				$mouseevents .= "onmouseout = \"navigation.hideSubMenu(this, 'ul');\"";
				$html .= "<li class=\"menuItem\" ".$mouseevents." style=\"display:none;\">".PHP_EOL;
				if (isset($item["href"]) && !$item["href"]) {
					$html .= "<a href=\"#\" onclick=\"return false;\">".(isset($item["text"]) ? $item["text"] : "")."<img alt=\"subitems\" src=\"".BASE."images/new/list-arrow.png\" style=\"float:right;height:10px;width:10px;\" /></a>".PHP_EOL;
				} else {
					$html .= "<a href=\"".(isset($item["href"]) ? $item["href"] : "")."\" title=\"".(isset($item["title"]) ? $item["title"] : "")."\">".(isset($item["text"]) ? $item["text"] : "")."<img alt=\"subitems\" src=\"".BASE."images/new/list-arrow.png\" style=\"float:right;height:10px;width:10px;\" /></a>".PHP_EOL;
				}
				$html .= "<ul style=\"display:none;\">".PHP_EOL;
				foreach ($item["children"] as $val) {
					$html .= "<li class=\"menuItem\" style=\"display:none;\"><a href=\"".(isset($val["href"]) ? $val["href"] : "")."\" title=\"".(isset($val["title"]) ? $val["title"] : "")."\">".(isset($val["text"]) ? $val["text"] : "")."</a></li>".PHP_EOL;
				}
				$html .= "</ul></li>".PHP_EOL;
			} else {
				$html .= "<li class=\"menuItem\" style=\"display:none;\"><a href=\"".(isset($item["href"]) ? $item["href"] : "")."\" title=\"".(isset($item["title"]) ? $item["title"] : "")."\">".(isset($item["text"]) ? $item["text"] : "")."</a></li>".PHP_EOL;
			}
		}
		$html .= "</ul>".PHP_EOL;
		$html .= "<img alt=\"left cap\" onclick=\"events.cancelBubble(event);navigation.toggle(document.getElementById('navigationMenu'), this);\" src=\"".BASE."images/new/droplist_right.png\" style=\"cursor:pointer;height:22px;margin-top:5px;position:absolute;right:0px;top:0px;width:21px;\" />".PHP_EOL;
		$html .= "</div>".PHP_EOL;
		
		return $html;
	}
?>
