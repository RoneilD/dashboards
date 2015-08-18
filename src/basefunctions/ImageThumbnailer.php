<?php
/** CLASS::BaseFunctions_ImageThumbnailer
 * @author feighen oosterbroek
 * @author feighen@manlinegroup.com
 * @created 23 Dec 2010 10:45:43 AM
 */
class BaseFunctions_ImageThumbnailer {
	//: Constants
	const GD_NOT_LOADED = "PHP GD extensions are not loaded. Cannot process image";
	const UNSUPPORTED_IMAGE_FORMAT = "Supplied image cannot be thumbnailed. Only images of the following formats can be thumbnailed: gif,jpeg,jpe,jpg,png";

	//: Variables
	protected $_errors = array();
	protected $_file;
	protected $_location;
	protected $_size = array(
		"home"=>array(
			"height"=>232,
			"width"=>315
	),
		"gallery"=>array(
			"height"=>165,
			"width"=>250
	),
	);

	//: Public functions
	//: Accessors
	public function getErrors($key = null)
	{
		return $key && array_key_exists($key, $this->_errors) ? $this->_errors[$key] : $this->_errors;
	}

	/** BaseFunctions_ImageThumbnailer::getFile()
	 * @return string $this->_file filename
	 */
	public function getFile()
	{
		return $this->_file;
	}

	/** BaseFunctions_ImageThumbnailer::getLocation()
	 * @return string thumbnail location
	 */
	public function getLocation()
	{
		return $this->_location;
	}

	/** BaseFunctions_ImageThumbnailer::getSize($key = null)
	 * @param mixed $key array key entry
	 * @return mixed|array array key entry or full array
	 */
	public function getSize($key = null)
	{
		return $key && array_key_exists($key, $this->_size) ? $this->_size[$key] : $this->_size;
	}

	/** BaseFunctions_ImageThumbnailer::setErrors($error, $append = false, $key = null)
	 * @param mixed|array $error error to add or overwrite
	 * @param bool $append add to the end of the array or overwrite and existing entry
	 * @param mixed $key array key entry
	 */
	public function setErrors($error, $append = false, $key = null)
	{
		if ($append === true) {
			$current = $this->getErrors();
			$current[$key ? $key : count($current)] = $error;
		}
		$this->_errors = isset($current) ? $current : $error;
	}
	
	/** BaseFunctions_ImageThumbnailer::setFile($file)
	 * @param string $file file to be manipulated
	*/
	public function setFile($file)
	{
		if (is_string($file) === false) {
			$this->setErrors("File Variable of incorrect format. Expected String", true, null);
			return false;
		}
		$this->_file = $file;
	}
	
	/** BaseFunctions_ImageThumbnailer::setLocation($location)
	 * @param string $location where do you want to upload theses files to?
	 */
	public function setLocation($location)
	{
		$this->_location = (string)$location;
	}

	/** BaseFunctions_ImageThumbnailer::setSize($size, $append = false, $key = null)
	 * @param mixed|array $size image thumbnail dimensions
	 * @param bool $append add to the end of the array or overwrite and existing entry
	 * @param mixed $key array key entry
	 */
	public function setSize($size, $append = false, $key = null)
	{
		if ($append === true) {
			$current = $this->getSize();
			$current[$key ? $key : count($current)] = $size;
		}
		$this->_size = isset($current) ? $current : $size;
	}
	//: End

	//: Magic
	/** BaseFunctions_ImageThumbnailer::__constuct($file, $location = null)
	* Class Constructor
	* @param string $file which file needs to be thumbnailed?
	* @param string $location where do you want these images to go?
	*/
	public function __construct($file = null, $location = null)
	{
		if ($location === null || (is_string($location) === false)) {
			$location = __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."thumbnails".DIRECTORY_SEPARATOR;
		}
		$this->setLocation($location);
		if (!is_dir($location)) {
			mkdir($location, 0777);
		}
		$this->setFile($file);
		if ($file) {$this->processImage();}
	}

	/** BaseFunctions_ImageThumbnailer::__destuct()
		* Allow for Garbage Collection
		*/
	public function __destruct()
	{
		unset($this);
	}
	//: End

	public function processImage()
	{
		$file = $this->getFile();
		if (!$file) {
			$this->setErrors("Could not get file information. Filename not set", true, null);
			return false;
		}
		$ext = substr($file, strrpos($file, ".")+1);
		switch (strtolower($ext)) {
			case "gif":
				$in = "imagecreatefromgif";
				$out = "imagegif";
				break;
			case "jpg":
			case "jpe":
			case "jpeg":
				$in = "imagecreatefromjpeg";
				$out = "imagejpeg";
				break;
			case "png":
				$in = "imagecreatefrompng";
				$out = "imagepng";
				break;
			default:
				$this->setErrors($this->UNSUPPORTED_IMAGE_FORMAT, true, null);
				return false;
				break;
		}
		if (!function_exists($in)) {$this->setErrors($this->GD_NOT_LOADED, true, null);return false;}
		$fileName = substr($file, strrpos($file, DIRECTORY_SEPARATOR)+1);
		$src = $in($file);
		list($width, $height) = getimagesize($file);
		foreach ($this->getSize() as $size=>$values) {
			$w = (int)0;
			$h = (int)0;
			if ($width > $height) {
				$w = $values["width"];
				$percent = (float)$w/$width;
				if (($height*$percent) > $values["height"]) {
					$h = $values["height"];
				} else {
					$h = (int)round($height*$percent, 0);
				}
			} else {
				$w = $values["height"];
				$percent = (float)$w/$width;
				if (($height*$percent) > $values["width"]) {
					$h = $values["width"];
				} else {
					$h = (int)round($height*$percent, 0);
				}
			}
			$tmp = imagecreatetruecolor($w, $h);
			imagecopyresampled($tmp, $src, 0, 0, 0, 0, $w, $h, $width, $height);
			$out($tmp, $this->getLocation().$size."_".$fileName, 100);
			imagedestroy($tmp);
		}
	}

	//: Private functions

}