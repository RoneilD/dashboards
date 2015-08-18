<?PHP
	// Preparation {
		$maxine			= realpath(dirname(__FILE__));;
		$rootaccess	= substr($maxine, 0, strrpos($maxine, DIRECTORY_SEPARATOR)+1);
		define("BASE", $rootaccess);
		
		include_once(BASE."basefunctions/localdefines.php");
		include_once(BASE."basefunctions/dbcontrols.php");
		include_once(BASE."basefunctions/baseapis/manapi.php");
		include_once(BASE."Maxine/api/maxineapi.php");
		
		require_once(BASE."basefunctions/baseapis/FileParser/FileParser.php");
		
		$top5	= array();
		
		$link					= mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
		$db_selected	= mysql_select_db(DB_SCHEMA, $link);
		
		$currenttime	= date("U");
		
		// Master fleets {
			$masterfleets[33]["id"]		= 33;
			$masterfleets[33]["name"]	= "En Total";
			
			$masterfleets[76]["id"]		= 76;
			$masterfleets[76]["name"]	= "Fr Total";
			
			$masterfleets[77]["id"]		= 77;
			$masterfleets[77]["name"]	= "Af Total";
		// }
		
		// Sub fleets {
			$subfleets[42]["id"]			= 42;
			$subfleets[42]["name"]		= "En Buckman";
			$subfleets[42]["master"]	= 33;
			$subfleets[42]["count"]			= 0;
			
			$subfleets[50]["id"]			= 50;
			$subfleets[50]["name"]		= "En Flats";
			$subfleets[50]["master"]	= 33;
			$subfleets[50]["count"]			= 0;
			
			$subfleets[32]["id"]			= 32;
			$subfleets[32]["name"]		= "En Tanks";
			$subfleets[32]["master"]	= 33;
			$subfleets[32]["count"]			= 0;
			
			$subfleets[53]["id"]			= 53;
			$subfleets[53]["name"]		= "En VDBL";
			$subfleets[53]["master"]	= 33;
			$subfleets[53]["count"]			= 0;
			
			
			$subfleets[28]["id"]			= 28;
			$subfleets[28]["name"]		= "LD";
			$subfleets[28]["master"]	= 76;
			$subfleets[28]["count"]			= 0;
			
			$subfleets[51]["id"]			= 51;
			$subfleets[51]["name"]		= "LWT";
			$subfleets[51]["master"]	= 76;
			$subfleets[51]["count"]			= 0;
			
			
			$subfleets[54]["id"]			= 54;
			$subfleets[54]["name"]		= "XB Links";
			$subfleets[54]["master"]	= 77;
			$subfleets[54]["count"]			= 0;
			
			$subfleets[35]["id"]			= 35;
			$subfleets[35]["name"]		= "XB Tris";
			$subfleets[35]["master"]	= 77;
			$subfleets[35]["count"]			= 0;
			
			$subfleets[75]["id"]			= 75;
			$subfleets[75]["name"]		= "XB 711";
			$subfleets[75]["master"]	= 77;
			$subfleets[75]["count"]			= 0;
		// }
	// }
	
	// Fetch the report and it's results {
		$reporturl = "http://login.max.manline.co.za/m4/2/api_request/Report/export?report=87&responseFormat=csv&numberOfRowsPerPage=10000";
		
		print($reporturl."<br>");
		
		$fileParser = new FileParser($reporturl);
		$fileParser->setCurlFile("fleetPositions.csv");
		$reportresults = $fileParser->parseFile();
		
		if ($reportresults === false) {
			print("There was an error!");
			print("<pre style='font-family:verdana;font-size:13'>");
			print_r($fileParser->getErrors());
			print("</pre>");
			return;
			
			print("<br>");
		}
	// }
	
	$fleetlist	= array();
	
	foreach ($reportresults as $memberkey=>$memberval) {
		$fleetid							= $memberval["Fleet Id"];
		$truckid							= $memberval["Truck Id"];
		$fleetlist[$fleetid]	= $memberval["Fleet"];
		
		$mastercheck	= array_key_exists($fleetid, $masterfleets);
		if($mastercheck == true) {
			$masterfleets[$fleetid]["trucks"][$truckid]["id"]		= $truckid;
			$masterfleets[$fleetid]["trucks"][$truckid]["name"]	= $memberval["Truck"];
		}
		
		$subcheck			= array_key_exists($fleetid, $subfleets);
		if($subcheck == true) {
			$subfleets[$fleetid]["trucks"][$truckid]["id"]		= $truckid;
			$subfleets[$fleetid]["trucks"][$truckid]["name"]	= $memberval["Truck"];
			$subfleets[$fleetid]["trucks"][$truckid]["count"]	= 0;
			$subfleets[$fleetid]["count"]++;
		}
	}
	
	foreach ($subfleets as $subkey=>$subval) {
		foreach ($subval["trucks"] as $truckkey=>$truckval) {
			$membercheck	= array_key_exists($truckval["id"], $masterfleets[$subval["master"]]["trucks"]);
			if($membercheck == true) {
				$masterfleets[$subval["master"]]["trucks"][$truckval["id"]]["count"]++;
			}
		}
	}
	
	foreach ($masterfleets as $masterkey=>$masterval) {
		foreach ($masterval["trucks"] as $memberkey=>$memberval) {
			if($memberval["count"] > 1) {
				print($memberval["name"]." is a member of ".$memberval["count"]." fleets.<br>");
			}
			if(!$memberval["count"]) {
				print($memberval["name"]." is not a member of any fleets.<br>");
			}
		}
	}
	
	print("<br>");
	
	foreach ($subfleets as $subkey=>$subval) {
		print($subval["name"]." has ".$subval["count"]." trucks.<br>");
	}
	
	print("-- End --");
?>
