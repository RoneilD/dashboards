<?php
defined("DS") || define("DS", DIRECTORY_SEPARATOR);
require_once(BASE."basefunctions".DS."baseapis".DS."Logs.class.php");
require_once(BASE."basefunctions".DS."baseapis".DS."LogTypes.class.php");
require_once(BASE."basefunctions".DS."baseapis".DS."LogUrgency.class.php");
abstract class ObjectCache
{
	//: Variables
	protected $_logger;
	protected $_name;
	protected $_timeToLive = 1800;
	protected $_types = array();
	protected $_urgency = array();
	
	//: Public functions
	//: Getters
	public function getLogger()
	{
		return $this->_logger;
	}
	
	public function getName()
	{
		return $this->_name;
	}
	
	public function getTimeToLive()
	{
		return $this->_timeToLive;
	}
	
	public function getTypes()
	{
		return $this->_types;
	}
	
	public function getUrgency()
	{
		return $this->_types;
	}
	
	abstract public function load();
	
	//: Magic
	public function __construct()
	{
		self::setLogger(new Logs());
		$logTypes = new logTypes();
		$err = $logTypes->getRow(array('where'=>'`name`="error"'));
		$notice = $logTypes->getRow(array('where'=>'`name`="notice"'));
		$warn = $logTypes->getRow(array('where'=>'`name`="warning"'));
		$types = (array)array(
			'error'=>$err,
			'notice'=>$notice,
			'warning'=>$warn,
		);
		self::setTypes($types);
		$logUrgency = new LogUrgency();
		$low = $logUrgency->getRow(array('where'=>'`name`="low"'));
		$med = $logUrgency->getRow(array('where'=>'`name`="medium"'));
		$high = $logUrgency->getRow(array('where'=>'`name`="high"'));
		$urg = (array)array(
			'low'=>$low,
			'medium'=>$med,
			'high'=>$high
		);
		self::setUrgency($urg);
	}
	
	public function __destruct()
	{
		unset($this);
	}
	
	abstract public function save(array $data);
	
	//: Setters
	public function setLogger($logger)
	{
		$this->_logger = $logger;
	}
	
	public function setName($name)
	{
		$this->_name = (string)$name;
	}
	
	public function setTimeToLive($ttl)
	{
		$this->_timeToLive = (int)$ttl;
	}
	
	public function setTypes($types)
	{
		$this->_types = $types;
	}
	
	public function setUrgency($urgency)
	{
		$this->_urgency = $urgency;
	}
	
	abstract public function testTimeToLive();
	
	public function writeLog($type, $urgency, $title = null, $message = null)
	{
		$data = (array)array();
		$data["log_typeid"] = $type["id"];
		$data["log_urgencyid"] = (is_array($urgency) && array_key_exists("id", $urgency)) ? $urgency["id"] : 0;
		$data["title"] = $title ? $title : "Something went wrong";
		$data["userid"] = (isset($_SESSION) && array_key_exists("userid", $_SESSION)) ? $_SESSION["userid"] : 0;
		$data["message"] = $message ? $message : "Something went wrong";
		$data["file"] = __FILE__;
		$data["update_at"] = $_SERVER["REQUEST_TIME"];
		$data["create_at"] = $_SERVER["REQUEST_TIME"];
		$data["url"] = array_key_exists("REQUEST_URI", $_SERVER) ? $_SERVER["REQUEST_URI"] : "";
		if ((self::getLogger()->create($data)) === false) {
			return false;
		}
	}
}
