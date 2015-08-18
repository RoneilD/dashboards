<?php
/** Class::Cacher
	* @example
		require_once(FIRSTBASE.DIRECTORY_SEPARATOR."api".DIRECTORY_SEPARATOR."Cacher.php");
		$url = (string)"http://max.mobilize.biz/m4/2/api_request/Report/";
		$url .= "export?report=87&format=csv&Start_Date=2010-03-01&Stop_Date=2010-03-15";
		$cacher = new Cacher("displayMainData", $url);
		print("<pre style='font-family:verdana;font-size:13'>cacher");
		print_r($cacher);
		print("</pre>");
		$data = $cacher->getData();
		print("<pre style='font-family:verdana;font-size:13'>data");
		print_r($data);
		print("</pre>");
	* @end
*/
class Cacher
{
	//: Variables
	protected $_cache;
	protected $_data;
	protected $_name;
	
	//: Public functions
	//: Getters
	public function getCache()
	{
		return $this->_cache;
	}
	
	public function getData()
	{
		return $this->_data;
	}
	
	public function getName()
	{
		return $this->_name;
	}
	
	//: Magic
	/** Cacher::__construct()
		* @param string $name name for this instance of the cacher object
		* @param string $dataSource anything that would do for the fileParserClass
		* @see FileParser::__construct()
 	*/
	public function __construct($name, $dataSource)
	{
		self::setName($name);
		# Decide on which object to use
		$type = (string)"Cache";
		if (extension_loaded("mysql")) {
			$type .= "Mysql";
		} elseif (extension_loaded("apc")) {
			$type .= "Apc";
		} else {
			$type .= "File";
		}
		print("<pre style='font-family:verdana;font-size:13'>");
		print_r($type);
		print("</pre>");
		require_once(BASE."basefunctions/baseapis/cache/mechanisms/".$type.".php");
		self::setCache(new $type(self::getName()));
		if (($data = self::getCache()->load(self::getName())) === false) {
			require_once(BASE."basefunctions/baseapis/FileParser/FileParser.php");
			$fileParser = new FileParser($dataSource);
			$data = $fileParser->parseFile();
			if (self::getCache()->save($data) === false) {
				print("Something went wrong with the saving of the cache".PHP_EOL);
			}
		}
		if ($data && is_array($data)) {self::setData($data);}
	}
	
	public function __destruct()
	{
		unset($this);
	}
	
	//: Setters
	public function setCache($cache)
	{
		$this->_cache = $cache;
	}
	
	public function setData(array $data)
	{
		$this->_data = $data;
	}
	
	public function setName($name)
	{
		$this->_name = (string)$name;
	}
}
