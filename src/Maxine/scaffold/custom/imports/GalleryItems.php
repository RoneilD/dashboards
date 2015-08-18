<?php
$base = substr(__DIR__, 0, strrpos(__DIR__, "M"));
defined("DS") || define("DS", DIRECTORY_SEPARATOR);
include_once($base."basefunctions".DS."localdefines.php");
include_once($base."basefunctions".DS."ImageThumbnailer.php");
include_once($base."basefunctions".DS."baseapis".DS."TableManager.php");

/** CLASS::Maxine_Scaffold_Custom_Imports_GalleryItems
 * @author feighen oosterbroek
 * @author feighen@manlinegroup.com
 * @created 30 Dec 2010 12:50:20 PM
*/
class Maxine_Scaffold_Custom_Imports_GalleryItems {
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
			$location = DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR."MaxwebPhotos".DIRECTORY_SEPARATOR;
		}
		$this->_location = $location;
	}
	
	public function setTableManager(TableManager $manager = null)
	{
		if ($manager === null) {
			$manager = new TableManager("galleryItems");
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
	/** Maxine_Scaffold_Custom_Imports_GalleryItems::__constuct()
		* Class Constructor
	*/
	public function __construct()
	{
		## Preparation
		$base = substr(__DIR__, 0, strrpos(__DIR__, "M"));
		$gallery = new TableManager("gallery");
		$this->setLocation();
		$this->setThumbNailer();
		$this->setTableManager();
		
		## The Work
		echo("<link href=\"http://".$_SERVER["SERVER_NAME"]."/basefunctions/scripts/manline.css\" media=\"all\" rel=\"stylesheet\" type=\"text/css\" />".PHP_EOL);
		if ($_POST) {
			$gallery->setWhere(
				$gallery->quoteString("(`gallery`.`name`=?", $_POST["directory"]).
				$gallery->quoteString(" OR `gallery`.`name`=?)", htmlspecialchars($_POST["directory"]))
			);
			$record = $gallery->selectSingle();
			$gallery->setWhere("");
			echo("<p class=\"standard\">".PHP_EOL);
			$dh = dir($this->getLocation().$_POST["directory"].DIRECTORY_SEPARATOR);
			while(($entry = $dh->read()) !== false) {
				if (in_array($entry, array(".", ".."))) {continue;}
				## Copy the file from /tmp
				if (file_exists($base."Maxine".DS."gallery".DS.$entry)) {
					unlink($base."Maxine".DS."gallery".DS.$entry);
				}
				if (copy($this->getLocation().$_POST["directory"].DS.$entry, $base."Maxine".DS."gallery".DS.$entry) === false) {
					print("Copy Failed".PHP_EOL);
					return false;
				}
				$size = getimagesize($this->getLocation().$_POST["directory"].DS.$entry);
				## Insert into the database
				$data = (array)array();
				$data["gallery_id"] = $record["id"];
				$data["file"] = "gallery".DS.$entry;
				$data["name"] = substr($entry, 0, strrpos($entry, "."));
				if ($size[0] > $size[1]) {
				        $data["is_landscape"] = 1;
				}
				$this->getTableManager()->insert($data);
				## Make the thumbnails
				$this->getThumbNailer()->setLocation($base."Maxine".DS."gallery".DS."thumbnails".DS);
				$this->getThumbNailer()->setFile($base."Maxine".DS."gallery".DS.$entry);
				$this->getThumbNailer()->processImage();
				print("<span title=\"image uploaded: ".$entry."\">|</span>".PHP_EOL);
				## remove the file
				if (file_exists($this->getLocation().$_POST["directory"].DS.$entry) === true) {
					unlink($this->getLocation().$_POST["directory"].DS.$entry);
				}
			}
			$dh->close();
			rmdir($this->getLocation().$_POST["directory"].DS); ## remove the directory
			echo("<a href=\"http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]."\">Import More...</a>".PHP_EOL);
			echo("</p>".PHP_EOL);
		} else {
			echo("<form method=\"POST\">".PHP_EOL);
			$dh = dir($this->getLocation());
			$i = 0;
			while (($entry = $dh->read()) !== false) {
				echo("<div class=\"column\" style=\"min-height:5px;\">".PHP_EOL);
				if (in_array($entry, array(".", ".."))) {continue;}
				echo("<input type=\"radio\" name=\"directory\" id=\"".$entry."\" value=\"".$entry."\" />".PHP_EOL);
				echo("<label for=\"".$entry."\">".$entry."</label><br />".PHP_EOL);
				echo("</div>".PHP_EOL);
				$i++;
				if ($i>2) {
					echo("<br class=\"clear\" />".PHP_EOL);
					$i = 0;
				}
			}
			echo("<br class=\"clear\" />".PHP_EOL);
			$dh->close();
			echo("<input type=\"submit\" value=\"Process\" />".PHP_EOL);
			echo("</form>".PHP_EOL);
		}
	}
	
	/** Maxine_Scaffold_Custom_Imports_GalleryItems::__destuct()
		* Allow for Garbage Collection
	*/
	public function __destruct()
	{
		unset($this);
	}
	//: End
	
	//: Private functions
	
}
new Maxine_Scaffold_Custom_Imports_GalleryItems();