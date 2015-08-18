<?PHP
	function displayMainDash() {
		print("<!DOCTYPE html><html><head><meta content=\"text/html; charset=iso-8859-1\" http-equiv=\"Content-Type\"><title>Dashboard</title></head><body style='background-image:url(".BASE."/images/background.png);background-repeat:repeat;margin:0px; padding:0px;'>");
		print("<table style='width:100%; height:100%; cursor: none;' cellspacing=0 cellpadding=0 border=0>");
		
		print("<tr><td id='canvasstd' align='center' valign='top'>");
		
		print("</td></tr>");
		
		print("</table>");
		print("</body>");
		
		// Javascript {
			print("<script>
				var cyclecount	= 0;
				var fleetcount	= 0;
				var greencount	= 0;
				var screenwidth	= screen.width;
				var interval1	= setInterval('ajaxTicker()', 30000);
				//ajaxTicker();
				
				function ajaxTicker() {
					var ajaxRequest;  // The variable that makes Ajax possible!
					
					// Rip the records variables into a string for Posting {
						var params	= 'fleetcount='+fleetcount;
						params		+= '&maxwidth='+screenwidth;
						//params		+= '&test=A';
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
					
					if(cyclecount == 2) {
						ajaxRequest.open('POST', './displaycase/displayFleetCompByDay.php', true);
					} else if(cyclecount == 5) {
						ajaxRequest.open('POST', './displaycase/displayFleetCompByMonth.php', true);
					} /* else if(cyclecount == 6) {
						ajaxRequest.open('POST', './displaycase/displayFleetPositions.php', true);
					} */ else if(cyclecount == 6) {
						ajaxRequest.open('POST', './displaycase/displayblackouts.php', true);
					} else {
						ajaxRequest.open('POST', './displaycase/displayFleetDetails.php', true);
						fleetcount++;
					}
					
					//Send the proper header information along with the request
					ajaxRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
					ajaxRequest.setRequestHeader('Content-length', params.length);
					ajaxRequest.setRequestHeader('Connection', 'close');
					
					ajaxRequest.send(params);
					cyclecount++;
					if(cyclecount > 7) {
						cyclecount	= 0;
					}
					if(fleetcount > 17) {
						fleetcount = 0;
					}
					if(greencount > 1) {
						greencount	= 0;
					}
					params=null;
				}
				window.onunload = function() {
					cyclecount=null;
					fleetcount=null;
					greencount=null;
					screenwidth=null;
					window.clearInterval(interval1);
					interval1=null;
				};
			</script></html>");
		// }
	}
	
	function displayContribDash() {
		print("<!DOCTYPE html><html><body style='background-image:url(".BASE."/images/background.png);background-repeat:repeat;margin:0px; padding:0px;'>");
		print("<table style='width:100%; height:100%; cursor: none;' cellspacing=0 cellpadding=0 border=0>");
		
		print("<tr><td id='canvasstd' align='center' valign='top'>");
		
		print("</td></tr>");
		
		print("</table>");
		print("</body>");
		
		// Javascript {
			print("<script>
				var cyclecount	= 0;
				var fleetcount	= 0;
				var greencount	= 0;
				var screenwidth	= screen.width;
				var interval1	= setInterval('ajaxTicker()', 30000);
				
				ajaxTicker();
				
				function ajaxTicker() {
					var ajaxRequest;  // The variable that makes Ajax possible!
					
					// Rip the records variables into a string for Posting {
						var params	= 'fleetcount='+fleetcount;
						params		+= '&maxwidth='+screenwidth;
						//params		+= '&test=A';
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
					
					ajaxRequest.open('POST', './displaycase/displayFleetContrib.php', true);
					fleetcount++;
					
					//Send the proper header information along with the request
					ajaxRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
					ajaxRequest.setRequestHeader('Content-length', params.length);
					ajaxRequest.setRequestHeader('Connection', 'close');
					
					ajaxRequest.send(params);
					cyclecount++;
					if(cyclecount > 1) {
						cyclecount	= 0;
					}
					if(fleetcount > 14) {
						fleetcount = 0;
					}
					params=null;
				}
				window.onunload = function() {
					cyclecount=null;
					fleetcount=null;
					greencount=null;
					screenwidth=null;
					window.clearInterval(interval1);
					interval1=null;
				};
			</script></html>");
		// }
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
		print("<!DOCTYPE html><html><body style='background-image:url(".BASE."/images/background.png);background-repeat:repeat;margin:0px; padding:0px;'>");
		print("<table style='width:100%; height:100%; cursor: none;' cellspacing=0 cellpadding=0 border=0>");
		
		print("<tr><td id='canvasstd' align='center' valign='top'>");
		print("<font color=WHITE>Blackouts</font>");
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
					
					if(cyclecount == 1) {
						ajaxRequest.open('POST', './displaycase/displayFleetPositions.php', true);
					} else {
						ajaxRequest.open('POST', './displaycase/displayblackouts.php', true);
					}
					
					//Send the proper header information along with the request
					ajaxRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
					ajaxRequest.setRequestHeader('Content-length', params.length);
					ajaxRequest.setRequestHeader('Connection', 'close');
					
					ajaxRequest.send(params);
					
					cyclecount++;
					if(cyclecount > 1) {
						cyclecount	= 0;
					}
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
				$scriptstr	= "var patternscript=new Array(".$patterncount.")\n";
				$count			= 0;
				foreach ($pattern as $patternkey=>$patternval) {
					$scriptstr	.= "patternscript[".$count."]=".$patternval.";";
					$count++;
				}
			} else {
				$duration			= 30;
				$patterncount	= 0;
				$scriptstr		= "var patternscript=new Array(1)\n";
				$scriptstr		.= "patternscript[0]=0;";
			}
		// }
		
		print("<!DOCTYPE html><html><head><link href='".BASE."/images/favicon.ico' rel='SHORTCUT ICON' /><meta content=\"text/html; charset=iso-8859-1\" http-equiv=\"Content-Type\">".PHP_EOL);
		print("<script type='text/javascript' language='javascript' src='".BASE."/basefunctions/scripts/manline.js'></script>");
		print("<link href='".BASE."/basefunctions/scripts/manline.css' media='all' rel='stylesheet' type='text/css' />".PHP_EOL);
		print("<title>Maxine</title>".PHP_EOL);
		print("</head><body style='margin:0px; padding:0px; background-image:url(\"".BASE."/images/background.png\");background-repeat:repeat;'>");
		
		print("<div onmouseover='checkIfHeaderBarHidden();' style=\"cursor:pointer;height:20px;left:0;position:absolute;top:0;width:100%;\">&nbsp;</div>");
		
		print("<h1 id=\"h1HeaderBar\" onmouseout='fadeHeaderBar()' onmouseover='checkIfHeaderBarVisible();' style='background-image:url(\"".DISPLAYCASE."/header_bar.png\"); margin-top:0px; width:100%; height:92px;'>");
		print("<img src='".DISPLAYCASE."/heading.png' style='float:left; margin-left:10px; margin-top:29px;'>");
		
		// Control bar {
			print("<div style='height:70px; width:155px; float:right; margin-top:10px;'>");
			
			print("<img title=\"You can also use the c key on your keyboard\" src='".DISPLAYCASE."/settings.png' onclick='goTo(\"index.php?mode=maxine/index&action=mydashdetails\");' style='margin-left:15px; margin-top:10px;'>");
			print("<img title=\"You can also use the h key on your keyboard\" src='".DISPLAYCASE."/home.png' onclick='goTo(\"index.php?action=home\");' style='margin-left:15px; margin-top:10px;'>");
			
			print("</div>");
		// }
		
		// forward/rewind/pause bar {
			print("<div style='background-image:url(\"".DISPLAYCASE."/dashcontrols/background.png\"); height:51px; width:146px; float:right; margin-top:20px; margin-right:10px;'>");
			
			print("<img title=\"You can also use the left arrow on your keyboard\" src='".DISPLAYCASE."/dashcontrols/back.png' onClick='rewindSequence();' style='margin-left:25px;'>");
			print("<img title=\"You can also use the space bar on your keyboard\" id='pausebutton' src='".DISPLAYCASE."/dashcontrols/pause.png' onClick='pausePressed();' style='margin-left:15px; width:31px; height:51px;'>");
			print("<img title=\"You can also use the space bar on your keyboard\" id='playbutton' src='".DISPLAYCASE."/dashcontrols/play.png' onClick='playPressed();' style='margin-left:15px; display:none;'>");
			print("<img title=\"You can also use the right arrow on your keyboard\" src='".DISPLAYCASE."/dashcontrols/forward.png' onClick='fasttrackSequence();' style='margin-left:15px;'>");
			
			print("</div>");
		// }
		print("</h1>");
		
		print("<div id='canvassdiv'>");
		
		/*
		print("<embed src='".BASE."/images/loading.swf'
		FlashVars='time=".$duration."'
		quality='high'
		width='250px'
		height='100px'
		name='number'
		wmode='transparent'
		allowScriptAccess='sameDomain'
		allowFullScreen='false'
		type='application/x-shockwave-flash'
		pluginspage='http://www.macromedia.com/go/getflashplayer' / >");
		*/
		
		print("</body>");
		
		// Javascript {
			print("<script>
				".$scriptstr."
				var cnt=0,cycleCounter = 0, cycleduration = ".($duration * 1000).",hbto, max = ".$patterncount.", screenwidth = screen.width, interval1 = setInterval('ajaxTicker()', cycleduration);
				ajaxTicker();
				
				function ajaxTicker() {
					var ajaxRequest;  // The variable that makes Ajax possible!
					// Rip the records variables into a string for Posting {
						var params	= 'fleetcount='+(patternscript[cycleCounter] >= 200 ? patternscript[cycleCounter].toString().substring(2) : patternscript[cycleCounter]);
						params		+= '&maxwidth='+screenwidth;
						//params		+= '&scrheight='+screenheight;
						//params		+= '&test=A';
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
							
							document.getElementById('canvassdiv').innerHTML = response;
							response=null;
						}
					}
					
					if(patternscript[cycleCounter] == 100) {
						ajaxRequest.open('POST', './displaycase/displayFleetCompByDay.php', true);
					} else if(patternscript[cycleCounter] == 101) {
						ajaxRequest.open('POST', './displaycase/displayFleetCompByMonth.php', true);
					} else if(patternscript[cycleCounter] == 102) {
						ajaxRequest.open('POST', './displaycase/displayGreenmile.php', true);
					} else if(patternscript[cycleCounter] == 103) {
						ajaxRequest.open('POST', './displaycase/displayGreenmileMinor.php', true);
					} else if(patternscript[cycleCounter] == 104) {
						ajaxRequest.open('POST', './displaycase/displayblackouts.php', true);
					} else if(patternscript[cycleCounter] == 105) {
						ajaxRequest.open('POST', './displaycase/displayFleetPositions.php', true);
					} else if(patternscript[cycleCounter] == 150) {
						ajaxRequest.open('POST', './displaycase/displayFleetContribCompByDay.php', true);
					} else if(patternscript[cycleCounter] == 151) {
						ajaxRequest.open('POST', './displaycase/displayFleetContribCompByMonth.php', true);
					} else if(patternscript[cycleCounter] >= 200) {
						ajaxRequest.open('POST', './displaycase/displayFleetContrib.php', true);
					} else {
						ajaxRequest.open('POST', './displaycase/displayFleetDetails.php', true);
					}
					
					//Send the proper header information along with the request
					ajaxRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
					ajaxRequest.setRequestHeader('Content-length', params.length);
					ajaxRequest.setRequestHeader('Connection', 'close');
					
					ajaxRequest.send(params);
					cycleCounter++;
					if(cycleCounter >= max) {
						cycleCounter	= 0;
					}
					params=null;
				}
				
				function rewindSequence() {
					if(document.getElementById('playbutton').style.display	== 'none') {
						pauseSequence();
					}
					
					cycleCounter	-= 2;
					if(cycleCounter < 0) {
						cycleCounter	+= max;
					}
					
					ajaxTicker();
					
					if(document.getElementById('playbutton').style.display	== 'none') {
						playSequence();
					}
				}
				
				function fasttrackSequence() {
					if(document.getElementById('playbutton').style.display	== 'none') {
						pauseSequence();
					}
					
					ajaxTicker();
					
					if(document.getElementById('playbutton').style.display	== 'none') {
						playSequence();
					}
				}
				
				function playSequence() {
					interval1	= setInterval('ajaxTicker()', cycleduration);
				}
				
				function pauseSequence() {
					window.clearInterval(interval1);
				}
				
				function playPressed() {
					document.getElementById('pausebutton').style.display	= '';
					document.getElementById('playbutton').style.display	= 'none';
					
					playSequence();
				}
				
				function pausePressed() {
					document.getElementById('pausebutton').style.display	= 'none';
					document.getElementById('playbutton').style.display	= '';
					
					pauseSequence();
				}
					
				function returnElement(el) {return typeof el === \"object\" ? el : document.getElementById(el);}
				function fadeIn(e) {
					if (e === null) {return false;}
					var t;
					e.style.opacity = 0;
					e.style.display = \"\";
					t=window.setInterval(function() {
						e.style.opacity = parseFloat(e.style.opacity, 10)+0.1;
						if (parseFloat(e.style.opacity, 10) === 1) {
							window.clearInterval(t);
						}
					} ,30);
				}
				function fadeOut(e, r) {
					if (e === null) {return false;}
					var t;
					e.style.opacity = 1;
					t=window.setInterval(function() {
						e.style.opacity = parseFloat(e.style.opacity, 10)-0.1;
						if (parseFloat(e.style.opacity, 10) === 0) {
							e.style.display = \"none\";
							if (r === true) {
								if (e.parentNode !== null) {e.parentNode.removeChild(e);}
							}
							window.clearInterval(t);
						}
					} ,30);
				}
				function fadeHeaderBar() {
					hbto=window.setTimeout(function () {
						fadeOut(returnElement('h1HeaderBar'));
					},2000);
				}
				function checkIfHeaderBarHidden() {
					var h=returnElement('h1HeaderBar');
					if (h.style.display === 'none') {
						fadeIn(h);
						fadeHeaderBar();
					}
				}
				function checkIfHeaderBarVisible() {
					var h=returnElement('h1HeaderBar');
					if (h.style.display === '') {
						window.clearTimeout(hbto);
					}
				}
					
				window.onkeypress = function(event) {
					if (event.which === 32) {
						if (cnt === 0) {
							cnt++;
							pausePressed();
						} else {
							cnt=0;
							playPressed();
						}
					}
					if (event.which === 99) { /* Options */
						goTo(\"index.php?mode=maxine/index&action=mydashdetails\");
					}
					if (event.which === 104) { /* Home */
						goTo(\"index.php?action=home\");
					}
					if (event.keyCode === 39) { /* Right Arrow Pressed */
						fasttrackSequence();
					}
					if (event.keyCode === 37) { /* Left Arrow Pressed */
						rewindSequence();
					}
				}
				window.onload=fadeHeaderBar;
				window.onunload = function() {
					patternscript=null;
					cycleCounter=null;
					cycleduration=null;
					max=null;
					screenwidth=null;
					window.clearInterval(interval1);
					interval1=null;
				};
			</script></html>");
		// }
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
					$sorted[$key] = $val["name"];
				}
				asort($sorted);
				foreach ($sorted as $key=>$val) {
					$fleetlist[$key] = $tmp[$key];
				}
				unset($sorted);
				unset($tmp);
				//: End Sorting
				
				$userdash			= sqlPull(array("table"=>"user_dashboards", "where"=>"userid=".$_SESSION["userid"], "onerow"=>1));
				$pattern			= explode(";", $userdash["pattern"]);
				
				$selectstr1	= "<select name=conf[pattern][";
				
				$selectstr2	.="][fleetid] style=\'width: 90%;\'>";
				$selectstr2 .= "<optgroup label=\"Fleets\">";
				foreach ($fleetlist as $fleetkey=>$fleetval) {
					if ((isset($_SESSION["isit"]) && $_SESSION["isit"]>0) || (isset($user["ismanager"]) && $user["ismanager"]>0)) {
						$selectstr2	.= "<option value=\"20".$fleetkey."\">".$fleetval["name"]." - Contribution</option>";
						$selectstr2	.= "<option value=\"".$fleetkey."\">".$fleetval["name"]." - Income</option>";
					} else {
						$selectstr2	.= "<option value=\"".$fleetkey."\">".$fleetval["name"]."</option>";
					}
				}
				$selectstr2 .= "</optgroup>";
				$selectstr2 .= "<optgroup label=\"Misc\">";
				$selectstr2	.= "<option value=104>Blackouts</option>";
				if ((isset($_SESSION["isit"]) && $_SESSION["isit"]>0) || (isset($user["ismanager"]) && $user["ismanager"]>0)) {
					$selectstr2	.="<option value=150>Fleet Comparison by Day - Contribution</option>";
					$selectstr2	.="<option value=100>Fleet Comparison by Day - Income</option>";
					$selectstr2	.="<option value=151>Fleet Comparison by Month - Contribution</option>";
					$selectstr2	.="<option value=101>Fleet Comparison by Month - Income</option>";
				} else {
					$selectstr2	.="<option value=100>Fleet Comparison by Day</option>";
					$selectstr2	.="<option value=101>Fleet Comparison by Month</option>";
				}
				$selectstr2	.= "<option value=102>Green Mile</option>";
				$selectstr2	.= "<option value=105>Position Updates</option>";
				$selectstr2 .= "</optgroup>";
				$selectstr2 .= "</select>";
				
				$count			= 1;
			// }
			print("<!DOCTYPE html><html><head><link href='".BASE."/images/favicon.ico' rel='SHORTCUT ICON' />".PHP_EOL);
			print("<script type='text/javascript' language='javascript' src='".BASE."/basefunctions/scripts/manline.js'></script>");
			print("<link href='".BASE."/basefunctions/scripts/manline.css' media='all' rel='stylesheet' type='text/css' />".PHP_EOL);
			print("<title>Maxine</title>".PHP_EOL);
			print("</head><body id='body' style='background-image:url(".BASE."/images/background.png);background-repeat:repeat;margin:0px; padding:0px;'>");
			
			print("<form name='dashdetailsform' action='index.php?mode=maxine/index&action=updatemydashdetails' method='post'>");
			
			print("<table width=100% height=100% cellspacing=0 cellpadding=0 border=0>");
			print("<tr height=92px><td align='center' background='".DISPLAYCASE."/header_bar.png'>");
			print("<table width=100% cellspacing=0 cellpadding=0 border=0>");
			
			print("<tr><td>");
			print("<img src='".DISPLAYCASE."/heading.png'>");
			print("</td><td align='right'>");
			print("<img src='".DISPLAYCASE."/settings.png' onclick=goTo('index.php?mode=maxine/index&action=mydashdetails')>");
			print("<img src='".DISPLAYCASE."/home.png' onclick='goTo(\"index.php?action=home\");'>");
			print("</td></tr>");
			
			print("</table>");
			print("</td></tr>");
			
			print("<tr><td height=16px></td></tr>");
			
			// Duration Select {
				print("<tr><td align='center' height=1px>");
				print("<table style='width:50%;' cellspacing=0 cellpadding=0>");
				
				// Top row {
					print("<tr>");
					print("<td height=11px width=11px background='".BASE."/images/displaycase/top_left.png'></td>");
					print("<td background='".BASE."/images/displaycase/top.png'></td>");
					print("<td height=11px width=11px background='".BASE."/images/displaycase/top_right.png'></td>");
					print("</tr>");
				// }
				
				print("<tr><td width=11px background='".BASE."/images/displaycase/left.png'>");
				print("</td>");
				
				print("<td align='center' background='".BASE."/images/displaycase/middle.png'>");
				print("<table width=100%>");
				
				print("<tr><td width=25% align='center'>");
				print("<font color=WHITE size=4>Duration :</font>");
				print("</td>");
				
				print("<td width=25% align='left' background='".BASE."/images/displaycase/middle.png'>");
				print("<select name=conf[duration] style='width:80%;'>");
				print("<option ".(5==$userdash["duration"]?"selected":"").">5</option>");
				print("<option ".(10==$userdash["duration"]?"selected":"").">10</option>");
				for($i=1; $i<9; $i++) {
					$timespan	= $i * 15;
					print("<option ".($timespan==$userdash["duration"]?"selected":"").">".$timespan."</option>");
				}
				print("</select>");
				
				print("</td>");
				
				print("<td width=50% background='".BASE."/images/displaycase/middle.png'>");
				print("<font color=WHITE size=2>Adjust this setting if you would like the graphs to appear more or less frequently.</font>");
				print("</td></tr>");
				
				print("</table>");
				print("</td><td background='".BASE."/images/displaycase/right.png'>");
				print("</td></tr>");
				
				// Bottom row {
					print("<tr>");
					print("<td height=11px width=11px background='".BASE."/images/displaycase/bottom_left.png'></td>");
					print("<td background='".BASE."/images/displaycase/bottom.png'></td>");
					print("<td height=11px width=11px background='".BASE."/images/displaycase/bottom_right.png'></td>");
					print("</tr>");
				// }
				
				print("</table><br /><br />");
				print("</td></tr>");
			// }
			
			// Drag and Drop components {
				print("<tr><td align='center'>");
				print("<table width=50% cellspacing=0 cellpadding=0>");
				
				// Top row {
					print("<tr>");
					print("<td height=11px width=11px background='".BASE."/images/displaycase/top_left.png'></td>");
					print("<td background='".BASE."/images/displaycase/top.png'></td>");
					print("<td height=11px width=11px background='".BASE."/images/displaycase/top_right.png'></td>");
					print("</tr>");
				// }
				
				print("<tr><td width=11px background='".BASE."/images/displaycase/left.png'>");
				print("</td><td>");
				
				// Drag and drop table {
					print("<table width=100% background='".BASE."/images/displaycase/middle.png'>");
					print("<tr><td width=10% align='center'>");
					print("</td><td width=80% align='center'>");
					print("<font color=WHITE>Graph Selection</font>");
					print("</td><td width=10% align='right'>");
					print("<img src='".DISPLAYCASE."/add.png' onClick='addRow();'>");
					print("</td></tr>");
					
					print("<tr><td align='center' colspan=3>");
					print("<div id='list' style='position:relative;width:500px;'>");
					
					foreach ($pattern as $patternkey=>$patternval) {
						print("<div id='e".$count."' class='list' style='height:45px;background:'".BASE."/images/displaycase/middle.png;'>");
						
						print("<input type=hidden id='status".$count."' name=conf[pattern][".$count."][status] value=1>");
						print("<img id='e".$count."handle' src='".DISPLAYCASE."/rearrange.png' style='float:left; margin-top:8px;'>");
						// Fleet Select {
							print("<select name=conf[pattern][".$count."][fleetid] style='width:90%; margin-top:5px;'>");
							print("<optgroup label=\"Fleets\">");
							foreach ($fleetlist as $fleetkey=>$fleetval) {
								if ((isset($_SESSION["isit"]) && $_SESSION["isit"]>0) || (isset($user["ismanager"]) && $user["ismanager"]>0)) {
									print("<option value='20".$fleetkey."' ".("20".$fleetkey==$patternval?"selected":"").">".$fleetval["name"]." - Contribution</option>");
									print("<option value='".$fleetkey."' ".($fleetkey==$patternval?"selected":"").">".$fleetval["name"]." - Income</option>");
								} else {
									print("<option value='".$fleetkey."' ".($fleetkey==$patternval?"selected":"").">".$fleetval["name"]."</option>");
								}
							}
							print("</optgroup>");
							print("<optgroup label=\"Misc\">");
							print("<option value=104 ".(104==$patternval?"selected":"").">Blackouts</option>");
							if ((isset($_SESSION["isit"]) && $_SESSION["isit"]>0) || (isset($user["ismanager"]) && $user["ismanager"]>0)) {
								print("<option value=150 ".(150==$patternval?"selected":"").">Fleet Comparison by Day - Contribution</option>");
								print("<option value=100 ".(100==$patternval?"selected":"").">Fleet Comparison by Day - Income</option>");
								print("<option value=151 ".(151==$patternval?"selected":"").">Fleet Comparison by Month - Contribution</option>");
								print("<option value=101 ".(101==$patternval?"selected":"").">Fleet Comparison by Month - Income</option>");
							} else {
								print("<option value=100 ".(100==$patternval?"selected":"").">Fleet Comparison by Day</option>");
								print("<option value=101 ".(101==$patternval?"selected":"").">Fleet Comparison by Month</option>");
							}
							print("<option value=102 ".(102==$patternval?"selected":"").">Green Mile Major</option>");
							print("<option value=103 ".(103==$patternval?"selected":"").">Green Mile Minor</option>");
							print("<option value=105 ".(105==$patternval?"selected":"").">Position Updates</option>");
							print("</optgroup>");
							print("</select>");
						// }
						print("<img src='".DISPLAYCASE."/delete.png' style='float:right;' onClick='deleteRow(".$count.");'>");
						
						print("</div>");
						$count++;
					}
					
					print("</div>");
					
					print("</td></tr>");
					print("</table>");
				// }
				
				print("</td><td background='".BASE."/images/displaycase/right.png'>");
				print("</td></tr>");
				
				// Bottom row {
					print("<tr>");
					print("<td height=11px width=11px background='".BASE."/images/displaycase/bottom_left.png'></td>");
					print("<td background='".BASE."/images/displaycase/bottom.png'></td>");
					print("<td height=11px width=11px background='".BASE."/images/displaycase/bottom_right.png'></td>");
					print("</tr>");
				// }
				
				print("</table>");
				print("</td></tr>");
			// }
			
			// Buttons {
				print("<tr><td align='center'>");
				print("<table width=25%>");
				
				print("<tr><td align='center'>");
				print("<img src='".DISPLAYCASE."/buttonSave.png' onclick='dashdetailsform.submit();'>");
				print("</td><td align='center'>");
				print("<img src='".DISPLAYCASE."/buttonCancel.png' onclick=goTo('index.php?mode=maxine/index&action=displaymydash')>");
				print("</td></tr>");
				
				print("</table>");
				print("</td></tr>");
			// }
			
			print("</form>");
			print("</td></tr>");
			print("</table>");
			
			print("</body>");
			
			// Script {
				print("<script>
					var itemCount	= ".$count.";
					function addRow() {
						var tempList = document.getElementById('list');
						newRow	= itemCount;
						var newRowObj						= document.createElement('DIV');
						newRowObj.style.cssText	= 'height:45px;background:\'".BASE."/images/displaycase/middle.png;\'';
						newRowObj.id						= 'e'+newRow;
						
						var newRowHtml	= '<input type=hidden id=\'status'+newRow+'\' name=conf[pattern]['+newRow+'][status] value=1>';
						newRowHtml			+= '<img id=\'e'+newRow+'handle\' src=\'".DISPLAYCASE."/rearrange.png\'>';
						newRowHtml			+= '".$selectstr1."'+newRow+'".$selectstr2."';
						newRowHtml			+= '<img src=\'".DISPLAYCASE."/delete.png\' onClick=\'deleteRow('+newRow+');\'>';
						
						newRowObj.innerHTML			= newRowHtml;
						tempList.appendChild(newRowObj);
						
						new dragObject('e'+newRow, 'e'+newRow+'handle', null, null, itemDragBegin, itemMoved, itemDragEnd, false);
						
						itemCount++;
						load();
					}
					
					function deleteRow(rowId) {
						document.getElementById('e'+rowId).style.display	= 'none';
						document.getElementById('status'+rowId).value	= 0;
					}
					
					var List;
					var PlaceHolder;
					
					load();
					
					function Position(x, y) {
						this.X = x;
						this.Y = y;
						
						this.Add = function(val) {
							var newPos = new Position(this.X, this.Y);
							if(val != null) {
								if(!isNaN(val.X))
									newPos.X += val.X;
								if(!isNaN(val.Y))
									newPos.Y += val.Y
							}
							return newPos;
						}
						
						this.Subtract = function(val) {
							var newPos = new Position(this.X, this.Y);
							if(val != null) {
								if(!isNaN(val.X))
									newPos.X -= val.X;
								if(!isNaN(val.Y))
									newPos.Y -= val.Y
							}
							return newPos;
						}
						
						this.Min = function(val) {
							var newPos = new Position(this.X, this.Y)
							if(val == null)
								return newPos;
							
							if(!isNaN(val.X) && this.X > val.X)
								newPos.X = val.X;
							if(!isNaN(val.Y) && this.Y > val.Y)
								newPos.Y = val.Y;
							
							return newPos;  
						}
						
						this.Max = function(val) {
							var newPos = new Position(this.X, this.Y);
							if(val == null)
								return newPos;
							
							if(!isNaN(val.X) && this.X < val.X)
								newPos.X = val.X;
							if(!isNaN(val.Y) && this.Y < val.Y)
								newPos.Y = val.Y;
							
							return newPos; 
						} 
						
						this.Bound = function(lower, upper) {
							var newPos = this.Max(lower);
							return newPos.Min(upper);
						}
						
						this.Check = function() {
							var newPos = new Position(this.X, this.Y);
							if(isNaN(newPos.X))
								newPos.X = 0;
							if(isNaN(newPos.Y))
								newPos.Y = 0;
								
							return newPos;
						}
						
						this.Apply = function(element) {
							if(typeof(element) == 'string')
								element = document.getElementById(element);
							if(element == null)
								return;
							
							// The following is commented out so that there is no left-right movement;
							// if(!isNaN(this.X)) {element.style.left = this.X + 'px'; }
							if(!isNaN(this.Y))
								element.style.top = this.Y + 'px';  
						}
					}
					
					function hookEvent(element, eventName, callback) {
						if(typeof(element) == 'string')
						element = document.getElementById(element);
						if(element == null)
							return;
						if(element.addEventListener)
							element.addEventListener(eventName, callback, false);
						else if(element.attachEvent)
							element.attachEvent('on' + eventName, callback);
					}
					
					function unhookEvent(element, eventName, callback) {
						if(typeof(element) == 'string')
							element = document.getElementById(element);
						if(element == null)
							return;
						if(element.removeEventListener)
							element.removeEventListener(eventName, callback, false);
						else if(element.detachEvent)
							element.detachEvent('on' + eventName, callback);
					}
					
					function cancelEvent(e) {
						e = e ? e : window.event;
						if(e.stopPropagation)
							e.stopPropagation();
						if(e.preventDefault)
							e.preventDefault();
						
						e.cancelBubble = true;
						e.cancel = true;
						e.returnValue = false;
						return false;
					}
					
					function absoluteCursorPosition(eventObj) {
						eventObj = eventObj ? eventObj : window.event;
						
						if(isNaN(window.scrollX))
							return new Position(eventObj.clientX + document.documentElement.scrollLeft + document.body.scrollLeft, eventObj.clientY + document.documentElement.scrollTop + document.body.scrollTop);
						else
							return new Position(eventObj.clientX + window.scrollX,
						eventObj.clientY + window.scrollY);
					}
					
					function dragObject(element, attachElement, lowerBound, upperBound, startCallback, moveCallback, endCallback, attachLater) {
						if(typeof(element) == 'string')
							element = document.getElementById(element);
						if(element == null)
							return;
						
						if(lowerBound != null && upperBound != null) {
							var temp = lowerBound.Min(upperBound);
							upperBound = lowerBound.Max(upperBound);
							lowerBound = temp;
						}
						
						var cursorStartPos = null;
						var elementStartPos = null;
						var dragging = false;
						var listening = false;
						var disposed = false;
						
						function dragStart(eventObj) { 
							if(dragging || !listening || disposed) return;
							dragging = true;
							
							if(startCallback != null)
								startCallback(eventObj, element);
							
							cursorStartPos = absoluteCursorPosition(eventObj);
							
							elementStartPos = new Position(parseInt(element.style.left), parseInt(element.style.top));
							
							elementStartPos = elementStartPos.Check();
							
							hookEvent(document, 'mousemove', dragGo);
							hookEvent(document, 'mouseup', dragStopHook);
							
							return cancelEvent(eventObj);
						}
						
						function dragGo(eventObj) {
							if(!dragging || disposed) return;
							
							var newPos = absoluteCursorPosition(eventObj);
							newPos = newPos.Add(elementStartPos).Subtract(cursorStartPos);
							newPos = newPos.Bound(lowerBound, upperBound)
							newPos.Apply(element);
							if(moveCallback != null)
								moveCallback(newPos, element, eventObj);
							
							return cancelEvent(eventObj); 
						}
						
						function dragStopHook(eventObj) {
							dragStop();
							return cancelEvent(eventObj);
						}
						
						function dragStop() {
							if(!dragging || disposed) return;
							unhookEvent(document, 'mousemove', dragGo);
							unhookEvent(document, 'mouseup', dragStopHook);
							cursorStartPos = null;
							elementStartPos = null;
							if(endCallback != null)
								endCallback(element);
							dragging = false;
						}
						
						this.Dispose = function() {
							if(disposed) return;
							this.StopListening(true);
							element = null;
							attachElement = null
							lowerBound = null;
							upperBound = null;
							startCallback = null;
							moveCallback = null
							endCallback = null;
							disposed = true;
						}
						
						this.StartListening = function() {
							if(listening || disposed) return;
							listening = true;
							hookEvent(attachElement, 'mousedown', dragStart);
						}
						
						this.StopListening = function(stopCurrentDragging) {
							if(!listening || disposed) return;
							unhookEvent(attachElement, 'mousedown', dragStart);
							listening = false;
						
							if(stopCurrentDragging && dragging)
								dragStop();
						}
						
						this.IsDragging = function(){ return dragging; }
						this.IsListening = function() { return listening; }
						this.IsDisposed = function() { return disposed; }
						
						if(typeof(attachElement) == 'string')
						attachElement = document.getElementById(attachElement);
						if(attachElement == null)
						attachElement = element;
						
						if(!attachLater)
						this.StartListening();
					}
					
					function load() {
						List = document.getElementById('list');
						
						PlaceHolder = document.createElement('DIV');
						PlaceHolder.className = 'list';
						PlaceHolder.style.backgroundColor = 'rgb(225,225,225)';
						PlaceHolder.SourceI = null;
						
						for(var dragCount=1; dragCount<(List.childNodes.length + 1); dragCount++) {
							new dragObject('e'+dragCount, 'e'+dragCount+'handle', new Position(0,-20), null, itemDragBegin, itemMoved, itemDragEnd, false);
						}
					}
					
					function itemDragBegin(eventObj, element) { 
						element.style.top = element.offsetTop + 'px';
						element.style.left = element.offsetLeft + 'px';
						element.className = 'drag';
						PlaceHolder.style.height = element.style.height;
						List.insertBefore(PlaceHolder, element);
						PlaceHolder.SourceI = element;
					}
					
					function itemMoved(newPos, element, eventObj) {
						eventObj	= eventObj ? eventObj : window.event;
						
						parentPos	= element.parentNode.offsetTop;
						var yPos			= eventObj.pageY - parentPos;
						
						var temp;
						var bestItem = 'end';
						for(var i=0; i<List.childNodes.length; i++) {
							if(List.childNodes[i].className == 'list') {
								temp = parseInt(List.childNodes[i].style.height);
								if(temp/2 >= yPos) {
									bestItem = List.childNodes[i];
									break;
								}     
								yPos -= temp;
							}
						}
						
						if(bestItem == PlaceHolder || bestItem == PlaceHolder.SourceI)
							return;
						
						PlaceHolder.SourceI = bestItem;
						if(bestItem != 'end')
							List.insertBefore(PlaceHolder, List.childNodes[i]);
						else
							List.appendChild(PlaceHolder);
					}
					
					function itemDragEnd(element) {
						if(PlaceHolder.SourceI != null) {
							PlaceHolder.SourceI = null;  
							List.replaceChild(element, PlaceHolder);
						}
					
						element.className = 'list';
						element.style.top = '0px';
						element.style.left = '0px';
					}
				</script></html>");
			// }
		}
		
		function updateMyDashDetails() {
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
			
			goHere("index.php?mode=maxine/index&action=displaymydash");
		}
	// 
	
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
		$manager->setCustomIndex("fleet_id");
		return $manager->selectMultiple();
	}
	
	function checkFleetScoreUpdates() {
		/*
		$inputletter	= "Z";
		$letter				= strtolower($inputletter);
		$number				= ord($letter) - 96;
		$rowcount			= $number*2;
		print($letter." ".$number."<br>");
		
		print("<table>");
		$rowpos	= 1;
		for($row=1; $row<$rowcount; $row++) {
			print("<tr>");
			$columnpos	= 1;
			for($column=1; $column<($number * 2); $column++) {
				$posletter	= chr($rowpos+96);
				print("<td width=12px>");
				
				if($columnpos	== ($number - $rowpos + 1)) {
					print($posletter."<br>");
				}
				print("</td>");
				if($column < $number) {
					$columnpos++;
				} else {
					$columnpos--;
				}
			}
			print("</tr>");
			if($row < $number) {
				$rowpos++;
			} else {
				$rowpos--;
			}
		}
		print("</table>");
		
		print("<div>");
		$rowpos	= 1;
		for($row=1; $row<$rowcount; $row++) {
			$posletter	= chr($rowpos+96);
			$righthand	= ($number+$rowpos)*16 - $number*17;
			print("<div>");
			print("<span style='margin-left:".(($number-$rowpos)*8)."px;'>".$posletter."</span>");
			
			if(($row > 1) && ($row < ($rowcount -1))) {
				print("<span style='margin-left:".$righthand."px;'>".$posletter."</span>");
			}
			print("</div>");
			
			if($row < $number) {
				$rowpos++;
			} else {
				$rowpos--;
			}
		}
		print("</div>");
		
		exit;
		*/
		// Prep {
			$rows = returnFleetTruckCount();
			if($_POST["conf"]) {
				$conf	= $_POST["conf"];
			}
			
			if($conf) {
				$startdate	= unixDate($conf["startdate"]); 
				$stopdate		= unixDate($conf["stopdate"]);
				
			} else {
				$startdate	= mktime(0, 0, 0, date("m"), 1, date("Y"));
				$stopdate		= mktime(0, 0, 0, (date("m") + 1), 1, date("Y"));
			}
			
			$where	= "date >= ".$startdate." AND date <= ".$stopdate;
			
			if($conf["fleetid"] > 0) {
				$fleetid	= $conf["fleetid"];
				$where		.= " AND fleetid=".$conf["fleetid"];
			} else {
				$fleetid	= 0;
			}
			
			$updatelist	= sqlPull(array("table"=>"fleet_scores", "where"=>$where, "sort"=>"date"));
			
			$fleetdayobj		= new fleetDayHandler;
			$tempfleetlist	= $fleetdayobj->getIncomeFleets();
			$fleetlist			= array();
			
			foreach ($tempfleetlist as $tempkey=>$tempval) {
				$fleetlist[$tempval["id"]]	= $tempval;
			}
			
			$totincome		= 0;
			$totbudget		= 0;
			$totcontrib		= 0;
			$background		= "content1";
		// }
		
		maxineTop("Updates and current values");
		print("<form name='scorecheckform' id='scorecheckform' action='index.php?mode=maxine/index&action=checkfleetscoreupdates' method='post'>");
		
		openHeader(1202);
		closeHeader();
		
		print("<div class='tray' style='width:1202px;'>");
		// Selector {
			openSubbar(350);
			print("Parameters");
			closeSubbar();
			
			print("<table class='standard' style='width:350px;'>");
			
			print("<tr class='content1'><td align='center' width=40%>");
			print("Fleet");
			print("</td><td align='center' width=60%>");
			print("<select id=\"conf[fleetid]\" name='conf[fleetid]' value='".$fleetid."' style='width:180px;'>");
			print("<option value=0 ".($fleetid==0?"selected":"").">All</option>");
			foreach ($fleetlist as $fleetkey=>$fleetval) {
				print("<option value='".$fleetval["id"]."' ".($fleetid==$fleetval["id"]?"selected":"").">".$fleetval["name"]."</option>");
			}
			print("</select>");
			print("</td></tr>");
			
			print("<tr class='content1'><td align='center'>");
			print("Start Date");
			print("</td><td align='center'>");
			print("<input id='conf[startdate]' name='conf[startdate]' value='".date("d/m/Y", $startdate)."' readonly style='width: 160px; text-align: center;'>");
			print("<img src='".BASE."/images/calendar.png' onClick='displayDatePicker(\"conf[startdate]\", this, \"dmy\", \"\");'>");
			print("</td></tr>");
			
			print("<tr class='content1'><td align='center'>");
			print("Stop Date");
			print("</td><td align='center'>");
			print("<input id='conf[stopdate]' name='conf[stopdate]' value='".date("d/m/Y", $stopdate)."' readonly style='width: 160px; text-align: center;'>");
			print("<img src='".BASE."/images/calendar.png' onClick='displayDatePicker(\"conf[stopdate]\", this, \"dmy\", \"\");'>");
			print("</td></tr>");
			
			print("<tr class='content1'><td align='center' colspan=2>");
			maxineButton("Submit", "scorecheckform.submit();");
			maxineButton("Export", "exportFleetScoreData();");
			print("</td></tr>");
			
			print("</table>");
		// }
			
		if($updatelist) {
			openSubbar(1200);
			closeSubbar();
			
			print("<table class='standard' style='width:1200px; margin-bottom:20px;'>");
			
			// Headers {
				print("<tr class='heading'><td width=5%>");
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
				print("</td><td width=9%>");
				print("Budget Contrib");
				print("</td><td width=9%>");
				print("Contrib");
				print("</td><td width=9%>");
				print("Contrib Updated");
				print("</td><td width=8%>");
				print("Kms");
				print("</td><td width=12%>");
				print(shortenWord("Ave. Kms per truck", 6));
				print("</td><td width=12%>");
				print(shortenWord("Budget Ave. Kms per truck", 6));
				print("</td></tr>");
			// }
			$totkmspertruck = (int)0;
			$totbudgetkmspertruck = (int)0;
			foreach ($updatelist as $updatekey=>$updateval) {
				$difference	= (date("U") - $updateval["updated"]);
				
				$diffhours	= $difference / 60 / 60;
				$diffhours	= floor($diffhours);
				
				$diffmins		= $difference - $diffhours * 60 * 60;
				$diffmins		= $diffmins / 60;
				$diffmins		= floor($diffmins);
				
				$day				= date("d", $updateval["date"]);
				
				print("<tr class='".$background."'><td>");
				print($updateval["id"]);
				print("</td><td>");
				print($fleetlist[$updateval["fleetid"]]["name"]);
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
				$totkmspertruck	+= ($updateval["kms"]/(isset($rows[$updateval["fleetid"]]) && isset($rows[$updateval["fleetid"]]["count"]) ? $rows[$updateval["fleetid"]]["count"] : 1));
				$totbudgetkmspertruck	+= ($updateval["budkms"]/(isset($rows[$updateval["fleetid"]]) && isset($rows[$updateval["fleetid"]]["count"]) ? $rows[$updateval["fleetid"]]["count"] : 1));
				
				if($background == "content2") {
					$background	= "content1";
				} else {
					$background	= "content2";
				}
			}
			
			print("<tr class='".$background."'><td colspan=5>");
			print("</td><td>");
			print($totincome);
			print("</td><td>");
			print($totbudget);
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
			print("</table>");
		}
		print("</div>");
		closeTrayDiv(1202);
		$js = "<script type=\"text/javascript\">";
		$js .= "function exportFleetScoreData() {";
		$js .= "var u;";
		$js .= "u='/Maxine/index.php?mode=maxine/index&action=exportfleetscoreupdates';";
		$js .= "u+='&fleet='+document.getElementById('conf[fleetid]').value;";
		$js .= "u+='&start='+document.getElementById('conf[startdate]').value;";
		$js .= "u+='&end='+document.getElementById('conf[stopdate]').value;";
		$js .= "document.location=u;";
		$js .= "}";
		$js .= "</script>";
		print($js);
		maxineBottom();
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