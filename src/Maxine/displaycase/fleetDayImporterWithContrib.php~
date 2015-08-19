<?PHP
	//: Test to see if it is already running
	/* 
	 * ps -> list the processes
	 * grep -> filter
	 * cut -> get the process_id field
	 * wc -> get a count 
	*/
	$pid = trim(shell_exec("ps aux | grep fleetDayImporterWithContrib | cut -d\" \" -f 3 | wc -l"));
	if(($pid > 4)) {
		syslog(LOG_INFO, "FleetDayImporterWithContrib.php is already running.");
		return FALSE;
	}
	//: End
	$starttimer		= date("U");
	// Preparation {
		$realPath		= realpath(dirname(__FILE__));
		$maxine			= substr($realPath, 0, strrpos($realPath, DIRECTORY_SEPARATOR));
		$rootaccess	= substr($maxine, 0, strrpos($maxine, DIRECTORY_SEPARATOR)+1);
		define("BASE", $rootaccess);
		
		include_once(BASE."basefunctions/localdefines.php");
		include_once(BASE."basefunctions/dbcontrols.php");
		include_once(BASE."basefunctions/baseapis/manapi.php");
		include_once(BASE."Maxine/api/maxineapi.php");
		
		require_once(BASE."basefunctions/baseapis/fleetDayHandler.php");
		
		$link					= mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
		$db_selected	= mysql_select_db(DB_SCHEMA, $link);
	// }
	
	$fleetdayobj	= new fleetDayHandler();
	$today	= date("d");
	
	$customday	= 0;
	
	if($customday > 0) {
		$fleetscore		= $fleetdayobj->pullFleetDayWithContrib($customday);
		$fleetdayobj->saveFleetDay($fleetscore);
		
		print("<pre style='font-family:verdana;font-size:13'>");
		print_r($fleetscore);
		print("</pre>");
	} else {
		print("Pulling today.<br>");
		$fleetscore		= $fleetdayobj->pullFleetDayWithContrib($today);
		$fleetdayobj->saveFleetDay($fleetscore);
		
		if($today > 1) {
			print("Pulling yesterday.<br>");
			$fleetscore		= $fleetdayobj->pullFleetDayWithContrib(($today - 1));
			$fleetdayobj->saveFleetDay($fleetscore);
		}
		
		$backday			= $fleetdayobj->findBackDay($today, "contrib_max");
		print("Backday: ".$backday.", Today: ".$today."<br>");
		if($backday > 0) {
			print("Pulling from further back.<br>");
			$fleetscore		= $fleetdayobj->pullFleetDayWithContrib($backday);
			$fleetdayobj->saveFleetDay($fleetscore);
		}
	}
	
	$endtimer	= date("U");
	$totaltimer	= $endtimer - $starttimer;
	print("That took ".$totaltimer." seconds.");
?>
