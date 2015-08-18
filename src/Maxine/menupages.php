<?PHP
	function firstMenu() {
		maxineTop();
		
		print("<div class='tray'>");
		print("<table style='width:860px;'>");
		
		// Open Row 1 {
			print("<tr><td align='center'>");
			$ioaction = "onmouseover='buttonJump(\"inout\");' onmouseout='buttonStandard(\"inout\");'";
			$ioaction .= " onmousedown='buttonPress(\"inout\");'";
			print("<img src='".BIGBUTTONS."/inout.png' id=inoutbutton onClick=goTo('index.php?mode=maxine/index&action=inoutboard') ".$ioaction.">");
			print("</td>");
			
			print("<td align='center'>");
			$mycapsaction = "onmouseover='buttonJump(\"mycaps\");' onmouseout='buttonStandard(\"mycaps\");'";
			$mycapsaction .= " onmousedown='buttonPress(\"mycaps\");'";
			print("<img src='".BIGBUTTONS."/mycaps.png' id=mycapsbutton onClick=goTo('index.php?mode=maxine/index&action=mycapslist2') ".$mycapsaction.">");
			print("</td>");
			
			print("<td align='center'>");
			$pubm3action = "onmouseover='buttonJump(\"pubm3\");' onmouseout='buttonStandard(\"pubm3\");'";
			$pubm3action .= " onmousedown='buttonPress(\"pubm3\");'";
			print("<img src='".BIGBUTTONS."/pubm3.png' id=pubm3button onClick=goTo('index.php?mode=maxine/index&action=showm3') ".$pubm3action.">");
			print("</td>");
			
			print("<td align='center'>");
			$m3action = "onmouseover='buttonJump(\"m3\");' onmouseout='buttonStandard(\"m3\");'";
			$m3action .= " onmousedown='buttonPress(\"m3\");'";
			print("<img src='".BIGBUTTONS."/m3.png' id=m3button onClick=goTo('index.php?mode=maxine/index&action=m3sys') ".$m3action.">");
			print("</td></tr>");
		// }
		
		// Open Row 2 {
			print("<tr>");
			
			print("<td align='center'>");
			$mydashaction = "onmouseover='buttonJump(\"mydash\");' onmouseout='buttonStandard(\"mydash\");'";
			$mydashaction .= " onmousedown='buttonPress(\"mydash\");'";
			print("<img src='".BIGBUTTONS."/mydash.png' id=mydashbutton onClick=goTo('index.php?mode=maxine/index&action=displaymydash'); ".$mydashaction.">");
			print("</td>");
			
			print("<td align='center'>");
			$ratescalcaction = "onmouseover='buttonJump(\"ratescalc\");' onmouseout='buttonStandard(\"ratescalc\");'";
			$ratescalcaction .= " onmousedown='buttonPress(\"ratescalc\");'";
			print("<img src='".BIGBUTTONS."/ratescalc.png' id=ratescalcbutton onClick=goTo('index.php?mode=maxine/index&action=ratescalc2'); ".$ratescalcaction.">");
			print("</td>");
			
			print("<td align='center'>");
			$faultsaction = "onmouseover='buttonJump(\"faults\");' onmouseout='buttonStandard(\"faults\");'";
			$faultsaction .= " onmousedown='buttonPress(\"faults\");'";
			print("<img src='".BIGBUTTONS."/faults.png' id=faultsbutton onClick=goTo('index.php?mode=maxine/index&action=faultsmenu'); ".$faultsaction.">");
			print("</td>");
			
			print("<td align='center'>");
			$peopleaction = "onmouseover='buttonJump(\"people\");' onmouseout='buttonStandard(\"people\");'";
			$peopleaction .= " onmousedown='buttonPress(\"people\");'";
			print("<img src='".BIGBUTTONS."/people.png' id=peoplebutton onClick=goTo('index.php?mode=maxine/index&action=peoplemenu'); ".$peopleaction.">");
			print("</td>");
			
			print("</tr>");
		// }
		
		if($_SESSION["isit"]==1) {
			// IT Row 1 {
			print("<tr><td align='center'>");
			print("</td>");
			
			print("<td align='center'>");
			$faultaction = "onmouseover='buttonJump(\"faultsys\");' onmouseout='buttonStandard(\"faultsys\");'";
			$faultaction .= " onmousedown='buttonPress(\"faultsys\");'";
			print("<img src='".BIGBUTTONS."/faultsys.png' id=faultsysbutton onClick=goTo('index.php?mode=maxine/index&action=faultsys') ".$faultaction.">");
			print("</td>");
			
			print("<td align='center'>");
			$rightaction = "onmouseover='buttonJump(\"access\");' onmouseout='buttonStandard(\"access\");'";
			$rightaction .= " onmousedown='buttonPress(\"access\");'";
			print("<img src='".BIGBUTTONS."/access.png' id=accessbutton onClick=goTo('index.php?mode=maxine/index&action=rightscontrol') ".$rightaction.">");
			print("</td>");
			
			print("<td align='center'>");
			$loggingaction = "onmouseover='buttonJump(\"loggingreport\");' onmouseout='buttonStandard(\"loggingreport\");'";
			$loggingaction .= " onmousedown='buttonPress(\"loggingreport\");'";
			print("<img src='".BIGBUTTONS."/loggingreport.png' id=loggingreportbutton onClick=goTo('index.php?mode=maxine/index&action=loggingreport') ".$loggingaction.">");
			print("</td></tr>");
			// }
			
			// IT Row 2 {
			print("<tr><td align='center'>");
			if($_SESSION["isadmin"]) {
				$smsaction = "onmouseover='buttonJump(\"sms\");' onmouseout='buttonStandard(\"sms\");'";
				$smsaction .= " onmousedown='buttonPress(\"sms\");'";
				print("<img src='".BIGBUTTONS."/sms.png' id=smsbutton onClick=goTo('index.php?mode=maxine/index&action=smssystem') ".$smsaction.">");
				
			}
			print("</td>");
			
			print("<td align='center'>");
			$sandaction = "onmouseover='buttonJump(\"sandbox\");' onmouseout='buttonStandard(\"sandbox\");'";
			$sandaction .= " onmousedown='buttonPress(\"sandbox\");'";
			print("<img src='".BIGBUTTONS."/sandbox.png' id=sandboxbutton onClick=goTo('index.php?mode=maxine/index&action=sandbox') ".$sandaction.">");
			print("</td>");
			
			print("<td align='center'>");
			$coderaction = "onmouseover='buttonJump(\"coder\");' onmouseout='buttonStandard(\"coder\");'";
			$coderaction .= " onmousedown='buttonPress(\"coder\");'";
			print("<img src='".BIGBUTTONS."/coder.png' id=coderbutton onClick=goTo('index.php?mode=maxine/index&action=encoder') ".$coderaction.">");
			print("</td>");
			
			print("<td>");
			$truckfinderaction = "onmouseover='buttonJump(\"truckfinder\");' onmouseout='buttonStandard(\"truckfinder\");'";
			$truckfinderaction .= " onmousedown='buttonPress(\"truckfinder\");'";
			print("<img src='".BIGBUTTONS."/truckfinder.png' id=truckfinderbutton onClick=goTo('index.php?mode=maxine/index&action=truckfinder'); ".$truckfinderaction.">");
			print("</td></tr>");
			// }
		}
		
		print("</table>");
		print("</div>");
		
		maxineBottom();
		
		// JavaScript {
			print("<script type='text/javascript'>
				
				// cavTitles {
				var g_iCavTimer;
				var g_CarEle = null;
				var g_iCavDivLeft;
				var g_iCavDivTop;
				
				function setCavTimer(evt) {
				var e = (window.event) ? window.event : evt;
				var src = (e.srcElement) ? e.srcElement : e.target;
				
				g_iCavDivLeft = e.clientX + 20 + document.body.scrollLeft;
				g_iCavDivTop = e.clientY - 5 + document.body.scrollTop;
				
				window.clearTimeout(g_iCavTimer);
				g_iCavTimer = window.setTimeout('ShowCavTitle()', 500);
				g_CarEle = src;
				}
				
				function ShowCavTitle() {
				for (var i = g_CarEle.attributes.length - 1; i >= 0; i--) {
				if (g_CarEle.attributes[i].name.toUpperCase() == 'CAVTITLE') {
				var div = document.getElementById('cavTitleDiv');
				if (div)
				break;
				
				div = document.createElement('<DIV>');
				div.id = 'cavTitleDiv';
				div.style.position = 'absolute';
				div.style.visibility = 'visible';
				div.style.zIndex = 10;
				div.style.backgroundColor = 'white';
				div.style.border = '1px solid black';
				
				var sLeft = new String();
				sLeft = g_iCavDivLeft.toString();
				sLeft += 'px';
				div.style.left = sLeft;
				var sTop = new String();
				sTop = g_iCavDivTop.toString();
				sTop += 'px';
				div.style.top = sTop;
				
				var titletext	= g_CarEle.attributes[i].value
				div.innerHTML = titletext;
				document.body.appendChild(div);
				
				var iWidth = div.scrollWidth + 10;
				var sWidth = new String();
				sWidth = iWidth.toString();
				sWidth += 'px';
				div.style.width = sWidth;
				
				break;
				}
				}
				}
				
				function CancelCavTimer(evt)
				{
				var e = (window.event) ? window.event : evt;
				var src = (e.srcElement) ? e.srcElement : e.target;
				
				var div = document.getElementById('cavTitleDiv');
				if (div)
				document.body.removeChild(div);
				
				window.clearTimeout(g_iCavTimer);
				g_CarEle = null;
				}
				// }
			</script>");
		// }
	}
	
	function peopleMenu() {
		maxineTop("Personnel Menu");
		// Buttons {
			openHeader();
			
			maxineButton("Back", "goTo(\"index.php?mode=maxine/index&action=firstmenu\");", 2);
			
			closeHeader();
		// }
		
		print("<div class='tray'>");
		print("<table style='width:860px;'>");
		
		// Row 1 {
		print("<tr><td align='center'>");
		$learneraction = "onmouseover='buttonJump(\"learner\");' onmouseout='buttonStandard(\"learner\");'";
		$learneraction .= " onmousedown='buttonPress(\"learner\");'";
		print("<img src='".BIGBUTTONS."/learner.png' id=learnerbutton onClick=goTo('index.php?mode=maxine/index&action=learnerlist'); ".$learneraction.">");
		print("</td>");
		
		print("<td align='center'>");
		$candidatesaction = "onmouseover='buttonJump(\"candidates\");' onmouseout='buttonStandard(\"candidates\");'";
		$candidatesaction .= " onmousedown='buttonPress(\"candidates\");'";
		print("<img src='".BIGBUTTONS."/candidates.png' id=candidatesbutton onClick=goTo('index.php?mode=maxine/index&action=candmenu'); ".$candidatesaction.">");
		print("</td></tr>");
		// }
		
		// Row 2 {
		print("<tr><td align='center'>");
		$driversaction = "onmouseover='buttonJump(\"drivers\");' onmouseout='buttonStandard(\"drivers\");'";
		$driversaction .= " onmousedown='buttonPress(\"drivers\");'";
		print("<img src='".BIGBUTTONS."/drivers.png' id=driversbutton onClick=goTo('index.php?mode=maxine/index&action=driverslist') ".$driversaction.">");
		print("</td>");
		
		print("<td align='center'>");
		$useraction = "onmouseover='buttonJump(\"users\");' onmouseout='buttonStandard(\"users\");'";
		$useraction .= " onmousedown='buttonPress(\"users\");'";
		print("<img src='".BIGBUTTONS."/users.png' id=usersbutton onClick=goTo('index.php?mode=maxine/index&action=listusers') ".$useraction.">");
		print("</td>");
		
		print("</td></tr>");
		// }
		
		print("</table>");
		print("</div>");
		
		maxineBottom();
	}
	
	function faultsMenu() {
		maxineTop("Faults System");
		
		// Buttons {
			openHeader();
			
			maxineButton("Back", "goTo(\"index.php?mode=maxine/index&action=firstmenu\");", 2);
			
			closeHeader();
		// }
		
		print("<div style='text-align:center; margin:auto; width:860px;'>");
		print("<table class=tray style='width:860px'>");
		
		// Row 1 {
		print("<tr><td align='center'>");
		$driverfaultaction = "onmouseover='buttonJump(\"driverfault\");' onmouseout='buttonStandard(\"driverfault\");'";
		$driverfaultaction .= " onmousedown='buttonPress(\"driverfault\");'";
		print("<img src='".BIGBUTTONS."/driverfault.png' id=driverfaultbutton onClick=goTo('index.php?mode=maxine/index&action=driverfaults'); ".$driverfaultaction.">");
		print("</td>");
		
		print("<td align='center'>");
		$equipfaultaction = "onmouseover='buttonJump(\"equipfault\");' onmouseout='buttonStandard(\"equipfault\");'";
		$equipfaultaction .= " onmousedown='buttonPress(\"equipfault\");'";
		print("<img src='".BIGBUTTONS."/equipfault.png' id=equipfaultbutton onClick=goTo('index.php?mode=maxine/index&action=equipfaults'); ".$equipfaultaction.">");
		print("</td>");
		
		print("<td align='center'>");
		$unitfaultaction = "onmouseover='buttonJump(\"unitfault\");' onmouseout='buttonStandard(\"unitfault\");'";
		$unitfaultaction .= " onmousedown='buttonPress(\"unitfault\");'";
		print("<img src='".BIGBUTTONS."/unitfault.png' id=unitfaultbutton onClick=goTo('index.php?mode=maxine/index&action=uflist'); ".$unitfaultaction.">");
		print("</td></tr>");
		// }
		
		print("</table>");
		print("</div>");
		
		maxineBottom();
	}
?>
