<?php
$parent = (string)substr(dirname(realpath(__FILE__)), 0, strrpos(dirname(realpath(__FILE__)), DIRECTORY_SEPARATOR));
require_once($parent.DIRECTORY_SEPARATOR."ObjectCache.php");
class CacheApc extends ObjectCache
{
	//: Variables
	
	//: Public functions
	public function load()
	{
		if (($data = apc_fetch(parent::getName())) === false) {
			# logging me hearties
			$types = parent::getTypes();
			$urgency = parent::getUrgency();
			parent::writeLog(
				$types['notice'],
				$urgency['low'],
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
	
	public function save(array $data)
	{
		if (apc_add(parent::getName(), serialize($data), parent::getTimeToLive()) === false) {
			# logging me hearties
			# logging me hearties
			$types = parent::getTypes();
			$urgency = parent::getUrgency();
			parent::writeLog(
				$types['error'],
				$urgency['high'],
				'Could not successfully save to cache',
				'APC Cache info: '.PHP_EOL.serialize(apc_cache_info())
			);
			return false;
		}
		return true;
	}
	
	public function testTimeToLive()
	{
		
	}
}
