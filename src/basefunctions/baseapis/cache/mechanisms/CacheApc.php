<?php
$parent = (string)substr(dirname(realpath(__FILE__)), 0, strrpos(dirname(realpath(__FILE__)), DIRECTORY_SEPARATOR));
require_once($parent.DIRECTORY_SEPARATOR."ObjectCache.php");
class CacheApc extends ObjectCache
{
	//: Variables
	
	//: Public functions
	public function load()
	{
	  if ($this->__testForApc() === false) {return false;}
		if (($data = apc_fetch(parent::getName())) === false) {
			# logging me hearties
			$types = parent::getTypes();
			$urgency = parent::getUrgency();
			parent::writeLog(
				$types['notice'],
				array_key_exists("low", $urgency) ? $urgency["low"] : "",
				'Could not successfully read from cache',
				'APC Cache info: '.PHP_EOL.serialize(apc_cache_info())
			);
			return false;
		}
		return unserialize($data);
	}
	
	//: Magic
	public function __construct($name)
	{
	  if ($this->__testForApc() === false) {return false;}
		parent::__construct();
		parent::setName($name);
	}
	
	/** CacheApc::__destruct()
		* Class destructor
		* unset reference to $this and allow for GC
	*/
	public function __destruct()
	{
		unset($this);
	}
	//: End
	
	public function save(array $data)
	{
	  if ($this->__testForApc() === false) {return false;}
		if (apc_add(parent::getName(), serialize($data), parent::getTimeToLive()) === false) {
			return false;
		}
		return true;
	}
	
	public function testTimeToLive()
	{
		
	}
	
	//: Private functions
	/** Cache_Apc::__testForApc()
	  * @return bool does apc exist on this server
	*/
	private function __testForApc()
	{
	  return function_exists("apc_add");
	}
}
