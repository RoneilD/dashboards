<?php
$base = substr(__DIR__, 0, strrpos(__DIR__, "M"));
defined("DS") || define("DS", DIRECTORY_SEPARATOR);
defined("BASE") || define("BASE", "../../../../");
include_once($base."basefunctions".DS."localdefines.php");
include_once($base."basefunctions".DS."ImageThumbnailer.php");
include_once($base."basefunctions".DS."baseapis".DS."TableManager.php");

/** CLASS::Maxine_Scaffold_Custom_Imports_Documents
 * @author feighen oosterbroek
 * @author feighen@manlinegroup.com
 * @created 17 Jan 2011 4:20:00 PM
*/
class Maxine_Scaffold_Custom_Imports_Documents {
	//: Variables
	protected $_location;
	protected $_manager;
	
	//: Public functions
	//: Accessors
	public function getLocation()
	{
		if (!$this->_location) {
			$this->setLocation();
		}
		return $this->_location;
	}
	
	public function getManager()
	{
		if (!$this->_manager) {
			$this->setManager();
		}
		return $this->_manager;
	}
	
	public function setLocation($location = null)
	{
		if ($location === null) {
			$location = DS."tmp".DS."DocumentLibrary".DS;
		}
		$this->_location = $location;
	}
	
	public function setManager(TableManager $manager = null)
	{
		if ($manager === null) {
			$manager = new TableManager("documents");
		}
		$this->_manager = $manager;
	}
	//: End
	
	//: Magic
	/** Maxine_Scaffold_Custom_Imports_Documents::__constuct()
	 * Class Constructor
	*/
	public function __construct()
	{
		echo("<link href=\"http://".$_SERVER["SERVER_NAME"]."/basefunctions/scripts/manline.css\" media=\"all\" rel=\"stylesheet\" type=\"text/css\" />".PHP_EOL);
		echo("<p class=\"standard\">".PHP_EOL);
		echo("Get list of document types:".PHP_EOL);
		$sql = (string)"SELECT c.* FROM `type` AS `c` LEFT JOIN `type` AS `p` ON `p`.`id`=`c`.`parent_id` WHERE (`p`.`name`='Documents' OR `p`.`name`='Policy and Procedures')";
		$types = $this->getManager()->runSql($sql);
		
		echo("<br />".PHP_EOL);
		echo("Get list of departments:".PHP_EOL);
		$sql = (string)"SELECT * FROM `m3_departments` WHERE `display`=1";
		$depts = $this->getManager()->runSql($sql);
		echo("<br />".PHP_EOL);
		echo("Importing Documents:<br />".PHP_EOL);
		
		$base = substr(__DIR__, 0, strrpos(__DIR__, "M"));
		$dh = dir($this->getLocation());
		if ($dh === false) {
			echo("<div class=\"error\">".PHP_EOL);
			echo("Couldn't open the directory. Exiting script".PHP_EOL);
			echo("</div>".PHP_EOL);
			return false;
		}
		while (($entry = $dh->read()) !== false) {
			if (in_array($entry, array(".", ".."))) {continue;}
			if (is_dir($this->getLocation().$entry) === true) {
				$sdh = dir($this->getLocation().$entry);
				while (($sub = $sdh->read()) !== false) {
					if (in_array($sub, array(".", ".."))) {continue;}
					if (is_dir($this->getLocation().$entry.DS.$sub) === true) {
						$ssdh = dir($this->getLocation().$entry.DS.$sub);
						while (($dd = $ssdh->read()) !== false) {
							if (in_array($dd, array(".", ".."))) {continue;}
							## copy to the correct place
							if (file_exists($base."Maxine".DS."documents".DS.$sub.DS.$dd) === true) {
								unlink($base."Maxine".DS."documents".DS.$sub.DS.$dd); ## copy will fail if file already exists
							}
							copy($this->getLocation().$entry.DS.$sub.DS.$dd, $base."Maxine".DS."documents".DS.$dd);
							$data = (array)array(
								"name"=>substr($dd, 0, strrpos($dd, ".")),
								"location"=>"documents".DS.$dd
							);
							foreach ($types as $type) {
								if ($entry == $type["name"]) {
									$data["type_id"] = $type["id"];
									break;
								}
							}
							foreach ($depts as $dept) {
								if ($sub == $dept["name"]) {
									$data["departments_id"] = $dept["id"];
									break;
								}
							}
							$this->getManager()->insert($data);
							echo("<span title=\"record inserted: $data[name]\">|</span>".PHP_EOL);
						}
						$ssdh->close();
					} else {
						## copy to the correct place
						if (file_exists($base."Maxine".DS."documents".DS.$sub) === true) {
							unlink($base."Maxine".DS."documents".DS.$sub); ## copy will fail if file already exists
						}
						copy($this->getLocation().$entry.DS.$sub, $base."Maxine".DS."documents".DS.$sub);
						$data = (array)array(
							"name"=>substr($sub, 0, strrpos($sub, ".")),
							"location"=>"documents".DS.$sub
						);
						foreach ($types as $type) {
							if ($entry == $type["name"]) {
								$data["type_id"] = $type["id"];
								break;
							}
						}
						$this->getManager()->insert($data);
						echo("<span title=\"record inserted\">|</span>".PHP_EOL);
					}
				}
				$sdh->close();
			} else {
				## copy to the correct place
				if (file_exists($base."Maxine".DS."documents".DS.$entry) === true) {
					unlink($base."Maxine".DS."documents".DS.$entry); ## copy will fail if file already exists
				}
				copy($this->getLocation().$entry, $base."Maxine".DS."documents".DS.$entry);
				$data = (array)array(
					"name"=>substr($entry, 0, strrpos($entry, ".")),
					"location"=>"documents".DS.$entry
				);
				foreach ($types as $type) {
					if ($entry == $type["name"]) {
						$data["type_id"] = $type["id"];
						break;
					}
				}
				$this->getManager()->insert($data);
				echo("<span title=\"record inserted\">|</span>".PHP_EOL);
			}
		}
		$dh->close();
	}
	
	/** Maxine_Scaffold_Custom_Imports_Documents::__destuct()
	 * Allow for Garbage Collection
	*/
	public function __destruct()
	{
		echo("</p>".PHP_EOL);
		unset($this);
	}
	//: End
	
	//: Private functions
	
}
new Maxine_Scaffold_Custom_Imports_Documents();