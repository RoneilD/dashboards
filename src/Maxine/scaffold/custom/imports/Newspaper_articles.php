<?php
$base = substr(__DIR__, 0, strrpos(__DIR__, "M"));
defined("DS") || define("DS", DIRECTORY_SEPARATOR);
include_once($base."basefunctions".DS."localdefines.php");
include_once($base."basefunctions".DS."ImageThumbnailer.php");
include_once($base."basefunctions".DS."baseapis".DS."TableManager.php");
/** CLASS::Maxine_Scaffold_Custom_Imports_Newspaper_articles
 * @author feighen
 * @author feighen
 * @created 31 Dec 2010 9:00:54 AM
*/
class Maxine_Scaffold_Custom_Imports_Newspaper_articles {
	//: Variables
	protected $_location;
	protected $_tableManager;
	protected $_thumbNailer;
	
	//: Public functions
	//: Accessors
	public function getLocation()
	{
		return $this->_location;
	}
	
	public function getTableManager()
	{
		return $this->_tableManager;
	}
	
	public function getThumbNailer()
	{
		return $this->_thumbNailer;
	}
	
	public function setLocation($location = null)
	{
		if (is_string($location) === false) {
			$location = DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR."news".DIRECTORY_SEPARATOR;
		}
		$this->_location = $location;
	}
	
	public function setTableManager(TableManager $manager = null)
	{
		if ($manager === null) {
			$manager = new TableManager("newspaper_articles");
		}
		$this->_tableManager = $manager;
	}
	
	public function setThumbNailer(BaseFunctions_ImageThumbnailer $thumbNailer = null)
	{
		if ($thumbNailer === null) {
			$thumbNailer = new BaseFunctions_ImageThumbnailer();
		}
		$this->_thumbNailer = $thumbNailer;
	}
	//: End
	
	//: Magic
	/** Maxine_Scaffold_Custom_Imports_Newspaper_articles::__constuct()
		* Class Constructor
	*/
	public function __construct()
	{
		## Preparation
		$base = substr(__DIR__, 0, strrpos(__DIR__, "M"));
		$this->setLocation();
		$this->setThumbNailer();
		$this->setTableManager();
		
		## The Work
		$dh = dir($this->getLocation());
		while (($entry = $dh->read()) !== false) {
			if (in_array($entry, array(".", ".."))) {continue;}
			## Copy the file from /tmp
			if (file_exists($base."Maxine".DS."news".DS.$entry)) {
				unlink($base."Maxine".DS."news".DS.$entry);
			}
			if (copy($this->getLocation().$entry, $base."Maxine".DS."news".DS.$entry) === false) {
				print("Copy Failed".PHP_EOL);
				return false;
			}
			$digits = preg_match("/\d{1,}/", $entry, $out);
			$date = date("Y-m-d", strtotime($out[0]));
			$text = preg_match("/[a-zA-Z\-]{1,}/", $entry, $out);
			$periodical = preg_replace(
				array("/-/", "/businessday/", "/businessreport/", "/fleetwatch/", "/themercury/", "/thewitness/"),
				array(" ", "business day", "business report", "fleet watch", "the mercury", "the witness"),
				$out[0]
			);
			
			$data = (array)array();
			$data["periodical"] = ucwords($periodical);
			$data["date_published"] = $date;
			$data["image"] = "news".DS.$entry;
			$this->getTableManager()->insert($data);
			## Make the thumbnails
			$this->getThumbNailer()->setLocation($base."Maxine".DS."news".DS."thumbnails".DS);
			$this->getThumbNailer()->setFile($base."Maxine".DS."news".DS.$entry);
			$this->getThumbNailer()->processImage();
			print("<span title=\"image uploaded: ".$entry."\">|</span>".PHP_EOL);
		}
	}
	
	/** Maxine_Scaffold_Custom_Imports_Newspaper_articles::__destuct()
		* Allow for Garbage Collection
	*/
	public function __destruct()
	{
		unset($this);
	}
	//: End
	
	//: Private functions
	
}
new Maxine_Scaffold_Custom_Imports_Newspaper_articles();