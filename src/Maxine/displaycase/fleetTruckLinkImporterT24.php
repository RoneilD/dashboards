<?php
/** Object::fleetTruckLinkImporterT24
	* @author Feighen Oosterbroek
	* @author feighen@manlinegroup.com
	* @copyright 2011 onwards Manline Group (Pty) Ltd
	* @license GNU GPL
	* @see http://www.gnu.org/copyleft/gpl.html
	*/
class fleetTruckLinkImporterT24
{
	//: Variables
	
	//: Public functions
	//: Magic
	/** fleetTruckLinkImporter::__construct()
		* Class constructor
		*/
	public function __construct()
	{
		$realPath		= realpath(dirname(__FILE__));
		$maxine			= substr($realPath, 0, strrpos($realPath, DIRECTORY_SEPARATOR));
		$rootaccess		= substr($maxine, 0, strrpos($maxine, DIRECTORY_SEPARATOR)+1);
		define("BASE", $rootaccess);
		
		include_once(BASE."basefunctions/baseapis/TableManager.php");
		include_once(BASE."basefunctions/baseapis/FileParser/FileParser.php");
		$manager = new TableManager("fleet_truck_count");
		$apiurl	= "https://t24.max.bwtsgroup.com/api_request/Report/export?report=73&responseFormat=csv"; // Live
		// $apiurl	= "http://max.mobilize.biz/m4/2/api_request/Report/export?report=141&responseFormat=csv"; // Test
		$required_fleets = (array)array(
				27, // Revenue - Adhoc
				13, // Bin
				20, // Revenue - Merensky
				26, // Revenue - Mondi
				24  // Revenue - Sappi
		);
		$rows = (array)array();
		foreach ($required_fleets as $fleetId) {
			$url = $apiurl."&Fleet=".$fleetId;
			$fileParser = new FileParser($url);
			$fileParser->setCurlFile("fleet_truck_count_".$fleetId.".csv");
			$data = $fileParser->parseFile();
			$cnt = count($data);
			$rows[] = array(
					"fleet_id"=>$fleetId,
					"count"=>$cnt,
					"t24"=>1
			);
			unset($s);
			unset($cnt);
		}
		//: Delete rows where t24=1
                $sql = (string)"DELETE FROM `fleet_truck_count` WHERE `t24`=1";
                $manager->runSql($sql);
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
new fleetTruckLinkImporterT24();
