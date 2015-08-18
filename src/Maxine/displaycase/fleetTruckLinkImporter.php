<?php
//: Defines
defined('BASE') || define('BASE', substr(dirname(realpath(__FILE__)), 0, strrpos(dirname(realpath(__FILE__)), 'Maxine')));
//: End
//: Includes
include_once(BASE."basefunctions/baseapis/fleetDayHandler.php");
//: End

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
		$realPath = realpath(dirname(__FILE__));
		$maxine	= substr($realPath, 0, strrpos($realPath, DIRECTORY_SEPARATOR));
		$rootaccess = substr($maxine, 0, strrpos($maxine, DIRECTORY_SEPARATOR)+1);
		defined("BASE") || define("BASE", $rootaccess);
		
		include_once(BASE."basefunctions/baseapis/TableManager.php");
		include_once(BASE."basefunctions/baseapis/FileParser/FileParser.php");
		
		$manager = new TableManager("fleet_truck_count");
		
		//: Do a test to see if table includes subbie_trucks field
		$fields = $manager->describeTable();
		if (in_array('subbie_count', array_keys($fields)) === FALSE)
		{
			$sql = (string)"ALTER TABLE `fleet_truck_count` ADD COLUMN `subbie_count` INT NOT NULL DEFAULT 0;";
			if (($manager->runSql($sql)) === FALSE)
			{
				print("Altering table failed".PHP_EOL);
				return FALSE;
			}
		}
		//: End
		
		$subbietrucksurl = "https://login.max.bwtsgroup.com/api_request/Report/export?report=153&responseFormat=csv"; // Max
		$apiurl	= "https://login.max.bwtsgroup.com/api_request/Report/export?report=145&responseFormat=csv"; // Max
		$t24apiurl = "https://t24.max.bwtsgroup.com/api_request/Report/export?report=73&responseFormat=csv"; // T24
		// $apiurl	= "http://max.mobilize.biz/m4/2/api_request/Report/export?report=141&responseFormat=csv"; // Test
		
		//: Get the list of subbie trucks
		$fileParser = new FileParser($subbietrucksurl);
		$fileParser->setCurlFile("subbie_trucks.csv");
		$data = $fileParser->parseFile();
		$subbieTrucks = (array)array();
		foreach ($data as $val)
		{
			if (array_key_exists("Trucks", $val) === FALSE)
			{
				continue;
			}
			$trucks = preg_split("/\,/", $val["Trucks"]);
			if (is_array($trucks) === FALSE)
			{
				continue;
			}
			foreach ($trucks as $trucklist)
			{
				$subbieTrucks[] = $trucklist;
			}
		}
		//print_r($subbieTrucks);
		unset($data);
		//return FALSE;
		//: End
		
		$fleetDayHandler = new fleetDayHandler();
		$required_fleets = $fleetDayHandler->getIncomeFleets();
		$rows = (array)array();
		foreach ($required_fleets as $val) {
			if (array_key_exists('fleets', $val))
			{
				$count = (int)0;
				$subbie_count = (int)0;
				foreach ($val['fleets'] as $subfleets)
				{
					$manager->setWhere(
						$manager->quoteString('`fleet_id`=?', $subfleets[0])
					);
					$record = $manager->selectSingle();
					$count += (int)$record['count'];
					$subbie_count += (int)$record['subbie_count'];
				}
				$rows[] = (array)array(
					'fleet_id'=>$val['id'],
					'count'=>((isset($count) && $count) ? $count : 0),
					'subbie_count'=>((isset($subbie_count) && $subbie_count) ? $subbie_count : 0),
				);
			}
			else
			{
				$url = $apiurl."&Fleet=".$val["maxid"]."&Start%20Date=".date("Y-m-d")."&Stop%20Date=".date('Y-m-d', strtotime('+1 day'));
				if (array_key_exists('t24', $val))
				{
					$url = $t24apiurl."&Fleet=".$val["maxid"]."&Start%20Date=".date("Y-m-d")."&Stop%20Date=".date('Y-m-d', strtotime('+1 day'));
				}
				print_r('url: '.$url.PHP_EOL);
				$fileParser = new FileParser($url);
				$fileParser->setCurlFile("fleet_truck_count_".$val["id"].".csv");
				$data = $fileParser->parseFile();
				// print_r($data);
				/*if ($fleetId == 75) { //: confirm a fleet is correct
				print("<pre>");
				print_r($data);
				print("</pre>");
				}*/
				$sub_cnt = (int)0;
				foreach ($data as $row)
				{
					if (in_array($row['Truck'], $subbieTrucks))
					{
						$sub_cnt++;
					}
				}
				//print($sub_cnt.PHP_EOL);
				$record = (array)array(
					'fleet_id'=>$val['id'],
					'count'=>count($data),
					'subbie_count'=>$sub_cnt
				);
				if (array_key_exists('t24', $val))
				{
					$record['t24'] = (int)1;
				}
				$rows[] = $record;
			}
		}
		//: Loop through and update/insert records
		foreach ($rows as $row)
		{
			$where = (string)$manager->quoteString('`fleet_id`=?', $row['fleet_id']);
			if (array_key_exists('t24', $row) && ($row['t24'] === 1))
			{
				$where .= $manager->quoteString(' AND `t24`=?', $row['t24']);
			}
			$manager->setWhere($where);
			$record = $manager->selectSingle();
			if ($record) //: Update
			{
				print_r($record);
				print_r($row);
				$nDifference = (float)$row['count'] == 0 ? 100 : ((($record['count']-$row['count'])/$row['count']) * 100 );
				print_r('diff:  '.$nDifference.PHP_EOL);
				if (($nDifference > 50) || ($nDifference < -50))
				{
					continue;
				}
				$manager->setWhere(
					$manager->quoteString('`id`=?', $record['id'])
					);
				$manager->update($row);
			}
			else //: Insert
			{
				$manager->insert($row);
			}
		}
		
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