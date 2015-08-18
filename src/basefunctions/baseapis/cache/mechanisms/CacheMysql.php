<?php
$parent = substr(dirname(realpath(__FILE__)), 0, strrpos(dirname(realpath(__FILE__)), DIRECTORY_SEPARATOR));
require_once(BASE."basefunctions".DIRECTORY_SEPARATOR."localdefines.php");
require_once($parent.DIRECTORY_SEPARATOR."ObjectCache.php");
class CacheMysql extends ObjectCache
{
	//: Variables
	protected $_connection;
	
	//: Public functions
	public function deleteFromCacheTimes()
	{
		$query = (string)"delete from `cache_query_times` where `table`='".parent::getName()."'";
		self::query($query, false);
	}
	
	public function dropTable($table)
	{
		$query = (string)"drop table `".$table."`";
		if (self::query($query, false) === false) {
			# logging me hearties
			$types = parent::getTypes();
			$urgency = parent::getUrgency();
			parent::writeLog(
				$types['error'],
				$urgency['high'],
				'Could not successfully drop table: '.$table,
				'SQL statement could not be successfully executed.'.PHP_EOL.'SQL Statement: '.self::getConnection()->real_escape_string($query)
			);
			return false;
		}
		return true;
	}
	
	//: Getters
	public function getConnection()
	{
		return $this->_connection;
	}
	
	public function load()
	{
		if (self::testTimeToLive() === false) {
			if (self::_testForCacheDataTable()) {
				self::dropTable(parent::getName());
			}
			return false;
		}
		$query = (string)"select * from `".parent::getName()."`";
		if (($returnData = self::query($query)) === false) {
			# logging me hearties
			$types = parent::getTypes();
			$urgency = parent::getUrgency();
			parent::writeLog(
				$types['notice'],
				$urgency['low'],
				'Could not successfully read from cache',
				'SQL query returned a null result.'.PHP_EOL.'SQL Statement: '.self::getConnection()->real_escape_string($query)
			);
			return false;
		}
		return $returnData;
	}
	
	//: Magic
	public function __construct($name)
	{
		parent::__construct();
		parent::setName($name);
		self::setConnection(mysqli::__construct(DB_HOST, DB_USER, DB_PASS, DB_SCHEMA));
		self::_testForCacheDateTable();
	}
	
	public function __destruct()
	{
		unset($this);
	}
	
	/** CacheMysql::query($sql)
		* @param string sql query to be run
		* @return array data on success false otherwise
	*/
	public function query($sql, $result = true)
	{
		if (($resultData = self::getConnection()->query($sql)) === false) {
			# logging me hearties
			$types = parent::getTypes();
			$urgency = parent::getUrgency();
			parent::writeLog(
				$types['error'],
				$urgency['high'],
				'Could not successfully query the database',
				"mySQL returned the following error: ".self::getConnection()->real_escape_string(self::getConnection()->error).PHP_EOL
			);
			return false;
		}
		if ($result) {
			$returnData = (array)array();
			while ($obj = $resultData->fetch_object()) {
				$row = (array)array();
				foreach ($obj as $key=>$val) {
					$row[$key] = $val;
				}
				$returnData[] = $row;
			}
			return $returnData;
		} else {
			return true;
		}
	}
	
	public function save(array $data)
	{
		if (!$data || !is_array($data)) {
			return false;
		}
		self::deleteFromCacheTimes();
		# the first thing we need to do is test for the tables existence
		if ((self::_testForCacheDataTable()) === false) {
			# make the table
			$query = (string)"create table `".parent::getName()."` (
			`id` integer(100) unsigned not null auto_increment primary key,".PHP_EOL;
			foreach (array_keys($data[1]) as $key=>$val) {
				$query .= "`".$val."` varchar(350),".PHP_EOL;
			}
			$query = substr($query, 0, strrpos($query, ",")).PHP_EOL;
			$query .= ") engine=InnoDb default charset=UTF8;";
			if ((self::query($query, false)) === false) {
				# logging me hearties
				$types = parent::getTypes();
				$urgency = parent::getUrgency();
				parent::writeLog(
					$types['error'],
					$urgency['high'],
					'Could not successfully create data storage table',
					"mySQL returned the following error: ".self::getConnection()->real_escape_string(self::getConnection()->error)."\n"
					);
				return false;
			}
		}
		$cols = (string)"";
		foreach (array_keys($data[1]) as $key=>$val) {
			$cols .= "`".$val."`,";
		}
		$cols = substr($cols, 0, strrpos($cols, ","));
		$query = (string)"insert into `".parent::getName()."` (".$cols.") values ";
		foreach ($data as $key=>$val) {
			$cols = (string)"";
			foreach ($val as $vkey=>$vval) {
				$cols .= '"'.self::getConnection()->real_escape_string($vval).'",';
			}
			$cols = substr($cols, 0, strrpos($cols, ","));
			$query .= "(".$cols."),".PHP_EOL;
		}
		$query = substr($query, 0, strrpos($query, ','));
		if ((self::query($query, false)) === false) {
			# logging me hearties
			$types = parent::getTypes();
			$urgency = parent::getUrgency();
			parent::writeLog(
				$types['error'],
				$urgency['high'],
				'Could not successfully insert into data storage table',
				"mySQL returned the following error: ".self::getConnection()->real_escape_string(self::getConnection()->error)."\n"
			);
			return false;
		}
		$query = (string)'insert into `cache_query_times` (`table`, `timestamp`) values ("'.parent::getName().'", "'.time().'");';
		self::query($query, false);
		return true;
	}
	
	//: Setters
	public function setConnection($connection)
	{
		$this->_connection = $connection;
	}
	
	public function testTimeToLive()
	{
		$query = (string)"select `timestamp` 
		from `cache_query_times` 
		where `table`='".parent::getName()."'";
		if (($result = self::query($query)) === false) {
			return true;
		}
		$diff = (int)time()-$result[0]['timestamp'];
		if ($diff > parent::getTimeToLive()) {
			self::deleteFromCacheTimes();
			return false;
		}
		return true;
	}
	
	//: Private functions
	private function _testForCacheDateTable()
	{
		$query = (string)"describe `cache_query_times`;";
		if ((self::query($query, false)) === false) {
			$query = (string)"create table `cache_query_times` (
				`id` integer(100) unsigned not null auto_increment primary key,
				`table` char(200),
				`timestamp` integer(100) unsigned not null default 0,
				index `cacheQueryTimes` (`table`(100), `timestamp`)
			) engine=InnoDb default charset=UTF8;";
			if (self::query($query, false) === false) {
				# logging me hearties
				$types = parent::getTypes();
				$urgency = parent::getUrgency();
				parent::writeLog(
					$types['error'],
					$urgency['high'],
					'Could not successfully create table for cache timing',
					"mySQL returned the following error: ".self::getConnection()->real_escape_string(self::getConnection()->error)."\n"
				);
				return false;
			}
			return true;
		}
		return true;
	}
	
	private function _testForCacheDataTable()
	{
		$query = (string)"describe `".parent::getName()."`;";
		if ((self::query($query, false)) === false) {
			return false;
		}
		return true;
	}
}
