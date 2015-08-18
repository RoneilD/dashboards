<?PHP
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
		
		require_once(BASE."basefunctions/baseapis/FileParser/FileParser.php");
		
		$top5	= array();
		
		$link					= mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
		$db_selected	= mysql_select_db(DB_SCHEMA, $link);
		
		if($conf["maxwidth"]) {
			$maxwidth	= $conf["maxwidth"];
			
			if($maxwidth < 1000) {
				$factor = 0.8;
			} else if($maxwidth < 1300) {
				$factor	= 0.94;
			} else if($maxwidth > 1600) {
				$factor	= 1.4;
			} else {
				$factor	= 1;
			}
		} else {
			$factor	= 1;
		}
		
		$trackfleets = array(
			array("id"=>22, "name"=>"A"),
			array("id"=>23, "name"=>"B"),
			array("id"=>70, "name"=>"C"),
			array("id"=>55, "name"=>"XBA"),
			array("id"=>56, "name"=>"XBB")
			);
		
		$currenttime	= date("U");
		
		$rawstartdate	= mktime(0,0,0,date("m"),(date("d")-8),date("Y"));
		$startdate		= date("Y-m-d", $rawstartdate);
		
		$rawstopdate	= mktime(0,0,0,date("m"),(date("d")+1),date("Y"));;
		$stopdate			= date("Y-m-d", $rawstopdate);
	// }
	
	// Fetch the report and it's results {
		$reporturl = "http://login.max.manline.co.za/m4/2/api_request/Report/export?report=109&responseFormat=csv&Start_Date=".$startdate."&Stop_Date=".$stopdate."&numberOfRowsPerPage=10000";
		
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
		
		$count			= 0;
		$watchmark	= 1000;
		foreach ($reportresults as $tripkey=>$tripval) {
			if(($tripval["Actual Empty Kms"] > $watchmark) || ($tripval["Ex Empty Kms"] > $watchmark)) {
				$count++;
				print("A trip leg for cargo with Trip Number \"".$tripval["Trip Number"]."\" and Cargo ID \"".$tripval["Cargo ID"]."\" has high values.<br>");
				print("Running from ".$tripval["Location From"]." to ".$tripval["Location To"].".<br>");
				if($tripval["Actual Empty Kms"] > $watchmark) {
					print("Actual was high with ".$tripval["Actual Empty Kms"]." kms.<br>");
				}
				if($tripval["Ex Empty Kms"] > $watchmark) {
					print("Expected was high with ".$tripval["Ex Empty Kms"]." kms.<br>");
				}
				print("<br>");
			}
		}
		print($count." matches.<br>");
	// }
?>
