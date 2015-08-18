<?php
/** Object::fleetTruckLinkImporter
	* @author Feighen Oosterbroek
    * @author feighen@manlinegroup.com
    * @copyright 2011 onwards Manline Group (Pty) Ltd
    * @license GNU GPL
    * @see http://www.gnu.org/copyleft/gpl.html
	*/
class fleetTruckLinkImporter {
	//: Variables
	
	//: Public functions
	//: Magic
	/** fleetTruckLinkImporter::__construct()
		* Class constructor
		*/
	public function __construct() {
		$realPath		= realpath(dirname(__FILE__));
		$maxine			= substr($realPath, 0, strrpos($realPath, DIRECTORY_SEPARATOR));
		$rootaccess	= substr($maxine, 0, strrpos($maxine, DIRECTORY_SEPARATOR)+1);
		define("BASE", $rootaccess);
		
		include_once(BASE."basefunctions/baseapis/TableManager.php");
		include_once(BASE."basefunctions/baseapis/FileParser/FileParser.php");
		$manager = new TableManager("fleet_truck_count");
		$apiurl	= "http://login.max.manline.co.za/m4/2/api_request/Report/export?report=140&responseFormat=csv"; // Live
		// $apiurl	= "http://max.mobilize.biz/m4/2/api_request/Report/export?report=141&responseFormat=csv"; // Test
		$required_fleets = (array)array(
				29, // Entire Active
				28, // LD
				51, // LWT Fleet
				81, // Haz Fleet
				32, // Energy - Tankers
				53, // Energy - VDBL Tankers
				42, // Energy - Buckman
				54, // XB - Links
				35, // XB - Triaxles
				75, // XB - 7/11 Links
				82, // Wilmar Bulk Fleet
				60, // Manline Consolidated
				33, // Energy - Total Fleet
				76, // Freight Consolidated
				77, // Africa Consolidated
				71, // XB Zac
				73  // XB Jacques
		);
		$rows = (array)array();
		foreach ($required_fleets as $fleetId) {
			$url = $apiurl."&Fleet=".$fleetId;
			$fileParser = new FileParser($url);
			$fileParser->setCurlFile("fleet_truck_count_".$fleetId.".csv");
			$data = $fileParser->parseFile();
			$s = preg_split("/\,/", (isset($data[1]) && isset($data[1]["Truck(s)"]) ? $data[1]["Truck(s)"] : ""));
			/*if ($fleetId == 75) { //: confirm a fleet is correct
				print("<pre>");
				print_r($data);
				print("</pre>");
				print("<pre>");
				print_r($s);
				print("</pre>");
			}*/
			$cnt = count($s);
			$rows[] = array(
					"fleet_id"=>$fleetId,
					"count"=>$cnt
			);
			unset($s);
			unset($cnt);
		}
		//: Truncate the table
		$manager->truncate();
		$manager->insert($rows);
	}
	
	/** fleetTruckLinkImporter::__destruct()
		* Class destructor
		* Allow for garbage collection
		*/
	public function __destruct() {
		unset($this);
	}
	//: End
}
new fleetTruckLinkImporter();