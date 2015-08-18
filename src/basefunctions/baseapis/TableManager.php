<?php
defined("DS") || define("DS", DIRECTORY_SEPARATOR);
if (!defined("DB_PASS")) {
	include_once(__DIR__.DS."..".DS."localdefines.php");
}
/** CLASS::TableManager
  * @author feighen oosterbroek
  * @author feighen@manlinegroup.com
  * @copyright 2010 Manline Group (Pty) Ltd
  * @created 11 Nov 2010 3:06:55 PM
*/
class TableManager {
	//: Constants
	const NESTING_TO_DEEP = "Array levels are not allowed to go beyond 2. Recieved 3 level array!";
	const SQL_NO_CONNECTION = "Could not successfully connect to the mysql instance";
	const SQL_NO_TABLE_NAME_DEFINED = "We could not query the database. No table was specified";
	const SQL_RETURNED_ERROR = "Unfortunately SQL could not process your request as it had errors. Last error was: ?";
	const SELECTSINGLE_NO_WHERE_DEFINED = "No where clause passed to TableManager::selectSingle(). Using selectMultiple() instead.";
	const VARIABLE_INCORRECT_TYPE = "Expected %s. Got %s";
	
	//: Variables
	protected $_cache;
	protected $_columns = array();
	protected $_connection;
	protected $_childRecords = false;
	protected $_childTables = array();
	protected $_dependantTables = array();
	protected $_errors = array();
	protected $_explain = false;
	protected $_groupBy;
	protected $_limit = array();
	protected $_logTypes = array();
	protected $_logUrgency = array();
	protected $_metaData;
	protected $_name;
	protected $_orderBy;
	protected $_primary = "id";
	protected $_queryColumns;
	protected $_queryFrom;
	protected $_sql = array();
	protected $_where;
	protected $_customIndex;
	
	
	//: Public functions
	/** TableManager::appendSqlStatement($key, $sql)
	  * @param mixed $key array key
	  * @param string $sql sql that has successfully run
	*/
	public function appendSqlStatement($key, $sql)
	{
		$sqlArray = (array)$this->getSql();
		$sqlArray[($key ? $key : count($this->getSql()))] = $sql;
		$this->setSql($sqlArray);
	}
	
	/** TableManager::deleteSingle($id)
	  * Delete a single database table record
	  * @param int $id database table record pointer
	  * @example start
	  $tableManager = new TableManager("departments);
	  $tableManager->deleteSingle(15);
	  * @example end
	*/
	public function deleteSingle($id)
	{
		if (!$this->getName()) {
			return self::SQL_NO_TABLE_NAME_DEFINED;
		}
		if ($this->__testVariable($id) !== "integer") {
			$message = sprintf(self::VARIABLE_INCORRECT_TYPE, "integer", $this->__testVariable($id));
			$this->log($this->getLogTypes("notice"), $this->getLogUrgency("low"), "Incorrect variable type passed to TableManager::deleteSingle()", $message);
			$this->setErrors($message, true);
			return false;
		}
		$sql = (string)"update `".$this->getName()."` set `deleted`='".$_SERVER["REQUEST_TIME"]."' where ".$this->quoteString("`id`=?", $id);
		if ($this->getConnection()->query($sql) !== false) {
			$this->appendSqlStatement("", $sql);
			return true;
		} else {
			$message = str_replace("?", $this->getConnection()->error, self::SQL_RETURNED_ERROR);
			$this->log($this->getLogTypes("error"), $this->getLogUrgency("high"), "mySQL returned an error", $message);
			$this->setErrors($message, true);
			return false;
		}
	}
	
	/** TableManager::deleteMultiple()
	  * delete multiple database records
	  * @example start
	  $tableManager = new TableManager("departments");
	  $tableManager->setWhere(
	  $tableManager->quoteString("id>?", 7)
	  );
	  $tableManager->deleteMultiple();
	  * @example end
	*/
	public function deleteMultiple()
	{
		if (!$this->getName()) {
			return self::SQL_NO_TABLE_NAME_DEFINED;
		}
		$sql = (string)"update `".$this->getName()."` set `deleted`='".$_SERVER["REQUEST_TIME"]."'";
		$where = $this->getWhere();
		if ($where) {$sql .= " where ".$where;}
		if ($this->getConnection()->query($sql) !== false) {
			$this->appendSqlStatement("", $sql);
			$this->setWhere(""); # Clear the where statement. Race Condition TOCTOU possible otherwise
			return true;
		} else {
			$message = str_replace("?", $this->getConnection()->error, self::SQL_RETURNED_ERROR);
			$this->log($this->getLogTypes("error"), $this->getLogUrgency("high"), "mySQL returned an error", $message);
			$this->setErrors($message, true);
			return false;
		}
	}
	
	/** TableManager::describeTable($tableName = null)
	  * which table do you want to get metadata for?
	  * @param string $tableName table name
	*/
	public function describeTable($tableName = null)
	{
		$name = is_null($tableName) ? $this->getName() : $tableName;
		if (!$name) {
			return self::SQL_NO_TABLE_NAME_DEFINED;
		}
		if ($this->__testVariable($name) !== "string") {
			$message = sprintf(self::VARIABLE_INCORRECT_TYPE, "string", $this->__testVariable($name));
			$this->log($this->getLogTypes("notice"), $this->getLogUrgency("low"), "Incorrect variable type passed to TableManager::describeTable()", $message);
			$this->setErrors($message, true);
			return false;
		}
		//$metaData = $this->getCache()->load("describeTable".$this->getName());
		//if (!$metaData) {
			$sql = (string)"describe `".$name."`";
			if (($result = $this->getConnection()->query($sql)) !== false) {
				$metaData = (array)array();
				while($row = $result->fetch_array()){
					$metaData[$row["Field"]] = $row;
				}
				$result->close();
			} else {
				$message = str_replace("?", $this->getConnection()->error, self::SQL_RETURNED_ERROR);
				$this->log($this->getLogTypes("error"), $this->getLogUrgency("high"), "mySQL returned an error", $message);
				$this->setErrors($message, true);
				return false;
			}
			$this->getCache()->save($metaData, "describeTable".$this->getName());
		//}
		
		return $metaData;
	}
	
	/** TableManager::findChildTables()
	  * Use the information_schema database to query which tables depend on this one
	  * I know that this is a bit slow, but I'm hoping that it will get better over time as upgrades to mySQL come through
	*/
	public function findChildTables()
	{
		if (!$this->getName()) {
			return self::SQL_NO_TABLE_NAME_DEFINED;
		}
		$data = $this->getCache()->load("findChildTables".$this->getName());
		if (!$data) {
			$sql = (string)"select `table_name`, `column_name`, `referenced_column_name` ";
			$sql .= "from `information_schema`.`key_column_usage` ";
			$sql .= "where `referenced_table_name`='".$this->getName()."' and `referenced_table_schema`='".DB_SCHEMA."'";
			$data = (array)array();
			if (($result = $this->getConnection()->query($sql)) !== false) {
				$i = (int)0;
				while($row = $result->fetch_array()){
					foreach (array_keys($row) as $key) {
						if (is_string($key)) {
							$data[$i][$key] = $row[$key];
						}
					}
					$i++;
				}
				$result->close();
			} else {
				$message = str_replace("?", $this->getConnection()->error, self::SQL_RETURNED_ERROR);
				$this->log($this->getLogTypes("error"), $this->getLogUrgency("high"), "mySQL returned an error", $message);
				$this->setErrors($message, true);
				return false;
			}
			$this->getCache()->save($data, "findChildTables".$this->getName());
		}
		$childTables = (array)array();
		foreach ($data as $child) {
			$childTables[] = array(
			  $child["table_name"]=>$child["table_name"],
			  "where"=>"`".$child["table_name"]."`.`".$child["column_name"]."`=%d"
			  );
		}
		
		$this->setChildTables($childTables);
	}
	
	/** TableManager::findDependantTables()
	  * which table(s) does this table depend on for information
	*/
	public function findDependantTables()
	{
		if (!$this->getName()) {
			return self::SQL_NO_TABLE_NAME_DEFINED;
		}
		$data = $this->getCache()->load("findDependantTables".$this->getName());
		if (!$data) {
			$sql = (string)"select `table_name`, `column_name`, `referenced_column_name`, `referenced_table_name` ";
			$sql .= "from `information_schema`.`key_column_usage` ";
			$sql .= "where `table_name`='".$this->getName()."' and `table_schema`='".DB_SCHEMA."'";
			$data = (array)array();
			if (($result = $this->getConnection()->query($sql)) !== false) {
				$i = (int)0;
				while($row = $result->fetch_array()){
					foreach (array_keys($row) as $key) {
						if (is_string($key)) {
							$data[$i][$key] = $row[$key];
						}
					}
					$i++;
				}
				$result->close();
			} else {
				$message = str_replace("?", $this->getConnection()->error, self::SQL_RETURNED_ERROR);
				$this->log($this->getLogTypes("error"), $this->getLogUrgency("high"), "mySQL returned an error", $message);
				$this->setErrors($message, true);
				return false;
			}
			$this->getCache()->save($data, "findDependantTables".$this->getName());
		}
		$dependantTables = (array)array();
		foreach ($data as $child) {
			$dependantTables[] = array(
			  $child["table_name"]=>$child["table_name"],
			  "where"=>"`".$child["table_name"]."`.`".$child["column_name"]."`=?"
			  );
		}
		
		$this->setDependantTables($dependantTables);
	}
	
	//: Getters and Setters
	/** TableManager::getCache()
	  * @return CacheFile object
	*/
	public function getCache()
	{
		return $this->_cache;
	}
	
	/** TableManager::getChildRecords()
	  * @return bool whether or not to retrieve child records
	*/
	public function getChildRecords()
	{
		return $this->_childRecords;
	}
	
	/** TableManager::getChildTables()
	  * @return array $this->_childTables base joined tables
	*/
	public function getChildTables()
	{
		return $this->_childTables;
	}
	
	/** TableManager::getColumns()
	  * @return $this->_cols array column names
	*/
	public function getColumns()
	{
		return $this->_columns;
	}
	
	/** TableManager::getConnection()
	  * @return object mysqli instance
	*/
	public function getConnection()
	{
		return $this->_connection;
	}
	
	/** TableManager::getDependantTables()
	  * @return array $this->_dependantTables which tables does this table depend on for data
	*/
	public function getDependantTables()
	{
		return $this->_dependantTables;
	}
	
	/** TableManager::getErrors($key = null)
	  * @param mixed $key array key entry
	*/
	public function getErrors($key = null)
	{
		return is_null($key) === false && array_key_exists($key, $this->_errors) ? $this->_errors[$key] : $this->_errors;
	}
	
	/** TableManager::getExplain()
	  * @return bool whether or not the explain select... is used as opposed to select....
	*/
	public function getExplain()
	{
		return $this->_explain;
	}
	
	/** TableManager::getGroupBy()
	  * @return array sql group by clause
	  * @example array("group"=>"column", "direction"=>"desc")
	*/
	public function getGroupBy()
	{
		return $this->_groupBy;
	}
	
	/** TableManager::getLimit()
	  * @return array limit data to x records offset by
	*/
	public function getLimit()
	{
		return $this->_limit;
	}
	
	/** TableManager::getLogTypes($key = null)
	  * @param mixed $key array key entry
	  * @return array $this->_logTypes
	*/
	public function getLogTypes($key = null)
	{
		return $key && array_key_exists($key, $this->_logTypes) ? $this->_logTypes[$key] : $this->_logTypes;
	}
	
	/** TableManager::getLogUrgency($key = null)
	  * @param mixed $key array key entry
	  * @return array $this->_logUrgency
	*/
	public function getLogUrgency($key = null)
	{
		return $key && array_key_exists($key, $this->_logUrgency) ? $this->_logUrgency[$key] : $this->_logUrgency;
	}
	
	/** TableManager::getMetaData()
	  * @return array $this->_metaData
	*/
	public function getMetaData()
	{
		return $this->_metaData;
	}
	
	/** TableManager::getName()
	  * @return $this->_name string database table name
	*/
	public function getName()
	{
		return $this->_name;
	}
	
	public function getCustomIndex()
	{
		return $this->_customIndex;
	}
	
	/** TableManager::getOrderBy()
	  * @return string standard sql order by clause
	*/
	public function getOrderBy()
	{
		return $this->_orderBy;
	}
	
	/** TableManager::getPrimary()
	  * @return $this->_primary string database table primary key column
	*/
	public function getPrimary()
	{
		return $this->_primary;
	}
	
	/** TableManager::getQueryColumns()
	  * @return array $this->_queryColumns array of table=>column mappings
	*/
	public function getQueryColumns()
	{
		return $this->_queryColumns;
	}
	
	/** TableManager::getQueryFrom()
	  * @return array $this->_queryFrom array of abbreviations=>table mappings
	*/
	public function getQueryFrom()
	{
		return $this->_queryFrom;
	}
	
	/** TableManager::getSql($key = null)
	  * @param $key string array key
	  * @return $this->_sql array of sql staements run against this instance
	*/
	public function getSql($key = null)
	{
		return ($key !== null ? $this->_sql[$key] : $this->_sql);
	}
	
	/** TableManager::getWhere()
	  * @return string sql where clause
	*/
	public function getWhere()
	{
		return $this->_where;
	}
	
	/** TableManager::setCache(CacheFile $cache = null)
	  * @param CacheFile $cache cache records for better performance
	*/
	public function setCache(CacheFile $cache = null)
	{
		if ($cache === null) {
			include_once(__DIR__.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR."mechanisms".DIRECTORY_SEPARATOR."CacheApc.php");
			//$cacheFile = __DIR__.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR.__CLASS__.".csv";
			$cache = new CacheApc("tableManager");
		}
		$this->_cache = $cache;
	}
	
	/** TableManager::setChildRecords($childRecords)
	  * @param bool $childRecords sets whether or not child records are retrieved
	*/
	public function setChildRecords($childRecords)
	{
		if ($this->__testVariable($childRecords) !== "bool") {
			$message = sprintf(self::VARIABLE_INCORRECT_TYPE, "bool", $this->__testVariable($name));
			$this->log($this->getLogTypes("notice"), $this->getLogUrgency("low"), "Incorrect variable type passed to TableManager::setChildRecords()", $message);
			$this->setErrors($message, true);
			return false;
		}
		$this->_childRecords = $childRecords;
	}
	
	/** TableManager::setChildTables(array $childTables)
	  * @param array $childTables array of tables that the current table has links to
	  * @example array(0=>array("abbr"=>"table name", "where"=>"`abbr`.`col`=?"))
	*/
	public function setChildTables(array $childTables)
	{
		$this->_childTables = $childTables;
	}
	
	/** TableManager::setColumns(array $cols)
	  * @param array $cols set the table columns
	*/
	public function setColumns(array $cols)
	{
		$this->_columns = $cols;
	}
	
	/** TableManager::setConnection($conn)
	  * @param object $conn mysqli instance
	*/
	public function setConnection(mysqli $conn)
	{
		$this->_connection = $conn;
	}
	
	/** TableManager::setDependantTables(array $dependantTables)
	  * @param array $dependantTables which tables does this table depend on for data
	  * @example array(0=>array("abbr"=>"table name", "on"=>"`abbr`.`column`", "displayColumns"=>"`abbr`.`column1`, `abbr`.`column2`..."))
	*/
	public function setDependantTables(array $dependantTables)
	{
		$this->_dependantTables = $dependantTables;
	}
	
	/** TableMananger::setErrors($errors, $append = false, $key = null)
	  * @param mixed $errors add an array of errors or append a specific error
	  * @param bool $append bool append to the array
	  * @param mixed $key array key
	*/
	public function setErrors($errors, $append = false, $key = null)
	{
		if ($append === true) {
			$current = $this->getErrors();
			$current[$key ? $key : count($current)] = $errors;
		}
		$this->_errors = isset($current) ? $current : $errors;
	}
	
	/** TableManager::setExplain($explain)
	  * @param bool $explain whether to explain the select path (query optimisation)
	*/
	public function setExplain($explain)
	{
		if ($this->__testVariable($explain !== "bool")) {
			$message = sprintf(self::VARIABLE_INCORRECT_TYPE, "bool", $this->__testVariable($name));
			$this->log($this->getLogTypes("notice"), $this->getLogUrgency("low"), "Incorrect variable type passed to TableManager::setExplain()", $message);
			$this->setErrors($message, true);
			return false;
		}
		$this->_explain = $explain;
	}
	
	/** TableManager::setGroupBy(array $groupBy)
	  * @param array $groupBy sql group results by column
	  * @example array("group"=>"column", "direction"=>"desc|asc")
	*/
	public function setGroupBy(array $groupBy)
	{
		$this->_groupBy = $groupBy;
	}
	
	/** TableManager::setLimit(array $limit)
	  * @param array $limit sql limit by clause array("limit"=>25, "offset"=>50)
	*/
	public function setLimit(array $limit)
	{
		$this->_limit = $limit;
	}
	
	/** TableManager::setLogTypes(array $logTypes = null, $append = false, $key = null)
	  * @param array $logTypes array of allowed log types
	  * @param bool $append add to the array of logtypes or overwrite?
	  * @param mixed $key $this->_logTypes array key entry
	*/
	public function setLogTypes(array $logTypes = null, $append = false, $key = null)
	{
		if ($logTypes !== null) {
			if ($append === true) {
				$current = $this->getLogTypes();
				$current[$key ? $key : count($current)] = $logTypes;
			}
			$this->_logTypes = isset($current) ? $current : $logTypes;
		} else {
			if ((!$data = $this->getCache()->load("logTypes"))) {
				$sql = (string)"SELECT * ";
				$sql .= "FROM `log_types` ";
				$sql .= " WHERE ISNULL(`deleted`)";
				$data = (array)array();
				if (($result = $this->getConnection()->query($sql)) !== false) {
					$i = (int)0;
					while($row = $result->fetch_array()){
						foreach (array_keys($row) as $key) {
							if (is_string($key)) {
								$data[$i][$key] = $row[$key];
							}
						}
						$i++;
					}
					$result->close();
				} else {
					$message = str_replace("?", $this->getConnection()->error, self::SQL_RETURNED_ERROR);
					$this->log($this->getLogTypes("error"), $this->getLogUrgency("high"), "mySQL returned an error", $message);
					$this->setErrors($message, true);
					return false;
				}
				$this->getCache()->save($data, "logTypes");
			}
			foreach ($data as $key=>$val) {
				$data[$val["name"]] = $val;
				unset($data[$key]);
			}
			$this->_logTypes = $data;
		}
	}
	
	/** TableManager::setLogUrgency(array $logUrgency = null, $append = false, $key = null)
	  * @param array $logTypes array of allowed log types
	  * @param bool $append add to the array of logtypes or overwrite?
	  * @param mixed $key $this->_logTypes array key entry
	*/
	public function setLogUrgency(array $logUrgency = null, $append = false, $key = null)
	{
		if ($logUrgency !== null) {
			if ($append === true) {
				$current = $this->getLogUrgency();
				$current[$key ? $key : count($current)] = $logUrgency;
			}
			$this->_logUrgency = isset($current) ? $current : $logUrgency;
		} else {
			if ((!$data = $this->getCache()->load("logUrgency"))) {
				$sql = (string)"SELECT * ";
				$sql .= "FROM  `log_urgency` ";
				$sql .= " WHERE ISNULL(`deleted`)";
				$data = (array)array();
				if (($result = $this->getConnection()->query($sql)) !== false) {
					$i = (int)0;
					while($row = $result->fetch_array()){
						foreach (array_keys($row) as $key) {
							if (is_string($key)) {
								$data[$i][$key] = $row[$key];
							}
						}
						$i++;
					}
					$result->close();
				} else {
					$message = str_replace("?", $this->getConnection()->error, self::SQL_RETURNED_ERROR);
					$this->log($this->getLogTypes("error"), $this->getLogUrgency("high"), "mySQL returned an error", $message);
					$this->setErrors($message, true);
					return false;
				}
				$this->getCache()->save($data, "logUrgency");
			}
			foreach ($data as $key=>$val) {
				if ($val["name"]) {
					$data[$val["name"]] = $val;
					unset($data[$key]);
				}
			}
			$this->_logUrgency = $data;
		}
	}
	
	/** TableManager::setMetaData(array $metadata)
	  * @param array $metaData table metadata as from describe `$this->getName()`
	*/
	public function setMetaData(array $metaData)
	{
		$this->_metaData = $metaData;
	}
	
	/** TableManager::setName($name)
	  * Sets which table is to be queried
	  * @param string $name which table are you trying to query?
	*/
	public function setName($name)
	{
		if ($this->__testVariable($name) !== "string") {
			$message = sprintf(self::VARIABLE_INCORRECT_TYPE, "string", $this->__testVariable($name));
			$this->log($this->getLogTypes("notice"), $this->getLogUrgency("low"), "Incorrect variable type passed to TableManager::setName()", $message);
			$this->setErrors($message, true);
			return false;
		}
		$this->_name = (string)$name;
	}
	
	/** TableManager::setOrderBy(array $orderBy)
	  * @param array $orderBy standard SQL order by clause
	  * @example start
	  $userProfiles->setOrderBy(array("column"=>"RAND()", "direction"=>"DESC"));
	  * @example end
	  * @example start
	  $manager = new TableManager("user_profiles");
	  $manager->setOrderBy(array(
	    "column"=>array("id", "firstname", "lastname"),
	    "direction"=>array("desc", "asc", "asc"),
	  ));
	  * @exmaple end
	 */
	public function setOrderBy(array $orderBy)
	{
		if ($this->__testVariable($orderBy) !== "array") {
			$message = sprintf(self::VARIABLE_INCORRECT_TYPE, "array", $this->__testVariable($orderBy));
			$this->log($this->getLogTypes("notice"), $this->getLogUrgency("low"), "Incorrect variable type passed to TableManager::setName()", $message);
			$this->setErrors($message, true);
			return false;
		}
		$this->_orderBy = $orderBy;
	}
	
	/** TableManager::setPrimary($primaryKey)
	  * @param string $primaryKey database table ($this->_name) primary key column
	*/
	public function setPrimary($primaryKey)
	{
		if ($this->__testVariable($primaryKey) !== "string") {
			$message = sprintf(self::VARIABLE_INCORRECT_TYPE, "string", $this->__testVariable($name));
			$this->log($this->getLogTypes("notice"), $this->getLogUrgency("low"), "Incorrect variable type passed to TableManager::setPrimary()", $message);
			$this->setErrors($message, true);
			return false;
		}
		$this->_primary = $primaryKey;
	}
	
	/** TableManager::setQueryColumns(array $columns)
	  * Which columns do you want to return?
	  * @param array $columns sql database columns
	  * @example start
	  array("table"=>"column", "table"=>"column")
	  * @example end
	*/
	public function setQueryColumns(array $columns)
	{
		$this->_queryColumns = $columns;
	}
	
	/** TableManager::setQueryFrom(array $from)
	  * Which tables do you need data from?
	  * @param array $from sql database table mappings
	  * @example start
	  array("abbreviation"=>"table", "joinLeft"=>array("abbr"=>"table"), "joinInner"=>array("abbr"=>"table"))
	  * @example end
	*/
	public function setQueryFrom(array $from)
	{
		$this->_queryFrom = $from;
	}
	
	/** TableManager::setSql(array $sql)
	  * @param array $sql array of sql statements run against this instance
	*/
	public function setSql(array $sql)
	{
		$this->_sql = $sql;
	}
	
	public function setCustomIndex($customIndex) {
		$this->_customIndex	= $customIndex;
	}
	
	/** TableManager::setWhere($where)
	  * @param string $where standard sql where clause
	*/
	public function setWhere($where)
	{
		if ($this->__testVariable($where) !== "string") {
			$message = sprintf(self::VARIABLE_INCORRECT_TYPE, "integer", $this->__testVariable($name));
			$this->log($this->getLogTypes("notice"), $this->getLogUrgency("low"), "Incorrect variable type passed to TableManager::setWhere()", $message);
			$this->setErrors($message, true);
			return false;
		}
		$this->_where = $where;
	}
	//: End
	
	/** TableManager::log(array $type, array $urgency, $title, $message)
	  * @param array $type what type of log record is this?
	  * @param array $urgency how urgent is it that we fix this?
	  * @param string $title log title
	  * @param string $message log data
	*/
	public function log(array $type, array $urgency, $title, $message)
	{
		$data = (array)array();
		$data["log_typeid"] = $type["id"];
		$data["log_typeid"] = $urgency["id"];
		$data["title"] = $title;
		$data["message"] = $message;
		$data["userid"]  = $_SESSION["userid"];
		$data["file"] = __FILE__;
		$data["url"] = $_SERVER['REQUEST_URI'];
		$data["create_at"] = $_SERVER["REQUEST_TIME"];
		$data["update_at"] = $_SERVER["REQUEST_TIME"];
		if (!$data["log_typeid"] || !$data["log_typeid"] || $data["message"]) {
			return false;
		}
		$this->insert($data, "logs");
	}
	
	//: Magic
	/** TableManager::__constuct($name = null)
	  * Class Constructor
	  * @param string $name which table are you wanting to query?
	*/
	public function __construct($name = null)
	{
		$this->setCache();
		$this->setConnection(
			new mysqli(DB_HOST, DB_USER, DB_PASS, DB_SCHEMA)
		);
		if ($name) {
			$this->setName($name);
			$this->setMetaData(
				$this->describeTable() ? $this->describeTable() : array()
			);
			$this->setColumns(
				array_keys($this->getMetaData())
			);
			foreach ($this->getMetaData() as $key=>$val) {
				if ($val["Key"] === "PRI") {
					$this->setPrimary(
					  $val["Field"]
					  );
					break;
				}
			}
		}
		$this->setLogTypes();
		$this->setLogUrgency();
	}
	
	/** TableManager::__destuct()
	  * Close connection to mySQL
	  * Allow for Garbage Collection
	*/
	public function __destruct()
	{
		// $this->getConnection()->close();
		unset($this);
	}
	//: End
	
	/** TableManager::insert(array $data, $tableName = null)
	  * insert record(s) into the database
	  * @param array $data the data to be inserted
	  * @param string $tableName which table other than $this->getName() are you inserting into?
	  * @example first example
	  $tableManager = new TableManager("departments");
	  $data = (array)array();
	  $data["name"] = "TableManager Test Insert";
	  $data["userId"] = $_SESSION["userid"];
	  $tableManager->insert($data);
	  * @example first example end
	  * @example second example
	  $tableManager = new TableManager("departments");
	  $data = (array)array();
	  $string = (string)"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	  for ($i=0;$i<5;$i++) {
	  $data[$i]["name"] = $string[mt_rand(0, strlen($string)-1)];
	  $data[$i]["userId"] = $_SESSION["userid"];
	  }
	  $tableManager->insert($data);
	  * @example second example end
	*/
	public function insert(array $data, $tableName = null)
	{
		if (!$this->getName() && $tableName === null) {
			return self::SQL_NO_TABLE_NAME_DEFINED;
		}
		$keys = array_keys($data);
		$columns = (string)"";
		if (is_string($keys[0])) {
			foreach ($keys as $col) {
				$columns .= "`".$col."`,";
			}
		} elseif ($keys[0] === 0) {
			foreach ($data[$keys[0]] as $col=>$row) {
				$columns .= "`".$col."`,";
			}
		} else {
			foreach ($this->getColumns() as $col) {
				if ($col === $this->getPrimary()) {continue;}
				if (in_array($col, array("createDate", "deleted"))) {continue;}
				$columns .= "`".$col."`,";
			}
		}
		$columns = substr($columns, 0, -1);
		$sql = (string)"insert into `".($tableName ? $tableName : $this->getName())."` (".$columns.") values";
		if (is_string($keys[0])) { ## good bet that we are doing a single insert
			$sql .= " (";
			foreach ($data as $value) {
				$sql .= $this->quoteVariable($value).",";
			}
			$sql = substr($sql, 0, -1);
			$sql .= ")";
			
		} else { ## good bet that we are doing multiple inserts
			foreach ($data as $record) {
				$sql .= " (";
				foreach (explode(",", $columns) as $column) {
					$sql .= $this->quoteVariable($record[preg_replace("/\`/", "", $column)]).",";
				}
				$sql = substr($sql, 0, -1);
				$sql .= "),";
			}
			$sql = substr($sql, 0, -1);
		}
		if ($this->getConnection()->query($sql) !== false) {
			$this->appendSqlStatement("", $sql);
			return $this->getConnection()->insert_id;
		} else {
			$message = str_replace("?", $this->getConnection()->error, self::SQL_RETURNED_ERROR);
			$message .= PHP_EOL.$sql;
			$this->log($this->getLogTypes("error"), $this->getLogUrgency("high"), "mySQL returned an error", $message);
			$this->setErrors($message, true);
			return false;
		}
	}
	
	/** TableManager::quoteString($string, $insertedText)
	  * Use quoted identifiers
	  * @param string $string string in format "sdf=?"
	  * @param mixed $insertedText characters to replace into
	*/
	public function quoteString($string, $insertedText)
	{
		$exceptable = (array)array(
		  "string", "array", "integer", "float", "bool", "numeric"
		  );
		if (in_array($this->__testVariable($insertedText), $exceptable) === false) {
		  $message = sprintf(self::VARIABLE_INCORRECT_TYPE, "string|array|integer|float|bool|numeric", $this->__testVariable($insertedText));
		  $this->log($this->getLogTypes("notice"), $this->getLogUrgency("low"), "Incorrect variable type passed to TableManager::quoteString()", $message);
		  $this->setErrors($message, true);
		  return false;
		}
		switch ($this->__testVariable($insertedText)) {
		case "array":
		  $returnedString = (string)"";
		  foreach ($insertedText as $key=>$value) {
		    switch ($this->__testVariable($value)) {
		    case "float":
		      $returnedString .=  str_replace($key, sprintf('%F', $value), $string);
		      break;
		    case "int":
		      $returnedString .=  str_replace($key, intval($value), $string);
		      break;
		    case "string":
		    default:
		      $returnedString .=  str_replace($key, "'".addcslashes($value, "\000\n\r\\'\"\032")."'", $string);
		      break;
		    }
		  }
		  return $returnedString;
		  break;
		case "integer":
		  return str_replace("?", intval($insertedText), $string);
		  break;
		case "float":
		case "numeric":
		  return str_replace("?", sprintf('%F', $insertedText), $string);
		  break;
		case "string":
		default:
		  return str_replace("?", "'".addcslashes($insertedText, "\000\n\r\\'\"\032")."'", $string);
		  break;
		}
	}
	
	/** TableManager::quoteVariable($var)
	  * @param mixed $var variable to be quoted
	  * @return mixed quoted variable
	*/
	public function quoteVariable($var)
	{
		$test = $this->__testVariable($var);
		switch ($test) {
		case "array":
		  foreach ($var as $key=>$val) {
		    if (is_array($val)) {
		      foreach ($val as $keyl=>$vall) {
		        if (is_array($vall)) {
		          return self::NESTING_TO_DEEP;
		        } else {
		          $var[$key][$keyl] = $this->quoteVariable($vall);
		        }
		      }
		    } else {
		      $var[$key] = $this->quoteVariable($val);
		    }
		  }
		  break;
		case "integer":
		  return intval($var);
		  break;
		case "float":
		  return sprintf('%F', $var);
		  break;
		case "string":
		  return "'".addcslashes($var, "\000\n\r\\'\"\032")."'";
		  break;
		default:
		  return "'".addcslashes($var, "\000\n\r\\'\"\032")."'";
		  break;
		}
	}
	
	/** TableManager::runSql($sql)
	  * @param string $sql what bit of random sql do you want to run?
	  * @todo pass all functions to here to do sql queries
	*/
	public function runSql($sql)
	{
		if ($this->__testVariable($sql) !== "string") {
			$this->setErrors("TableManager::runSql($sql) expects parameter 1 to be a string. Recieved: ".$this->__testVariable($sql), true, "");
			return false;
		}
		if (($result = $this->getConnection()->query($sql)) !== false) {
			$i = (int)0;
			$this->appendSqlStatement("", $sql);
			if ($result instanceof mysqli_result) {
			  $data = (array)array();
				while($row = $result->fetch_array()){
					foreach (array_keys($row) as $key) {
						if (is_string($key)) {
							$data[$i][$key] = $row[$key];
						}
					}
					$i++;
				}
				$result->close();
				return $data;
			} else {
				return $result;
			}
		} else {
			$message = str_replace("?", $this->getConnection()->error, self::SQL_RETURNED_ERROR);
			$this->log($this->getLogTypes("error"), $this->getLogUrgency("high"), "mySQL returned an error", $message);
			$this->setErrors($message, true);
			return false;
		}
	}
	
	/** TableManager::selectSingle()
	  * return a single record from the database
	  * @return array data on success false otherwise
	  * @example start
	  $tableManager = new TableManager("departments");
	  $tableManager->setWhere(
	  $tableManager->quoteString("id=?", 7)
	  );
	  $data = $tableManager->selectSingle();
	  * @example end
	*/
	public function selectSingle()
	{
		if (!$this->getName()) {
			return self::SQL_NO_TABLE_NAME_DEFINED;
		}
		$where = $this->getWhere();
		if (!$where) {
			return $this->selectMultiple(true);
		}
		$sql = (string)"";
		if ($this->getExplain() === true) {
			$sql .= "EXPLAIN ";
		}
		$sql .= "SELECT";
		if ($this->getQueryColumns()) {
			$sql .= " ";
			foreach ($this->getQueryColumns() as $table=>$columns) {
				foreach ($columns as $column) {
					if (strstr($column, "AS") || strstr($column, "as")) {
						$sql .= "".$column.",";
					} elseif($column === "*") {
						$sql .= "`".$table."`.".$column.",";
					} else {
						$sql .= "`".$table."`.`".$column."`,";
					}
				}
			}
			$sql =substr($sql, 0, -1);
		} else {
			$sql .= " *";
		}
		$sql .= " FROM ";
		$queryFrom = $this->getQueryFrom();
		if ($queryFrom) {
			$sql .= "`".$this->getName()."` AS `".$this->getName()."`";
			foreach ($queryFrom as $joinType=>$tables) {
				foreach ($tables as $table) {
					$sql .= " ".strtoupper($joinType)." `".$table["table"]["table"]."` AS `".$table["table"]["abbr"]."` ON ". $table["on"];
				}
			}
		} else {
			$sql .= "`".$this->getName()."` AS `".$this->getName()."`";
		}
		if ($where) {$sql .= " where ".$where;}
		$order = $this->getOrderBy();
		if ($order) {
		  if (is_array($order["column"])) {
		    $sql .= " ORDER BY";
		    if (is_array($order["direction"])) {
		      foreach ($order["column"] as $key=>$val) {
		        $sql .= $val." ".($order["direction"][$key] ? strtoupper($order["direction"][$key]) : "ASC").",";
		      }
		      $sql = substr($sql, 0, -1);
		    } else {
		      foreach ($order["column"] as $val) {
		        $sql .= $val." ".($order["direction"] ? strtoupper($order["direction"]) : "ASC").",";
		      }
		      $sql = substr($sql, 0, -1);
		    }
		  } else {
		    $sql .= " ORDER BY ".$order["column"]." ".($order["direction"] ? strtoupper($order["direction"]) : "ASC");
		  }
		}
		$group = $this->getGroupBy();
		if ($group) {
			$sql .= " group by ".$group["column"].($group["direction"] ? " ".$group["direction"] : "");
		}
		$limit = $this->getLimit();
		if ($limit) {
			$sql .= " limit ".$limit["limit"];
		} else {
			$sql .= " limit 1";
		}
		// print("<pre>");print_r($sql);print("</pre>");
		$this->appendSqlStatement("", $sql);
		$data = (array)array();
		if (($result = $this->getConnection()->query($sql)) !== false) {
			$this->appendSqlStatement("", $sql);
			while($row = $result->fetch_array()){
				foreach (array_keys($row) as $key) {
					if (is_string($key)) {
						$data[$key] = $row[$key];
					}
				}
			}
			$result->close();
		} else {
			$message = str_replace("?", $this->getConnection()->error, self::SQL_RETURNED_ERROR);
			$this->log($this->getLogTypes("error"), $this->getLogUrgency("high"), "mySQL returned an error", $message);
			$this->setErrors($message, true);
			return false;
		}
		if ($this->getChildRecords() === true && $this->getChildTables()) {
			foreach ($this->getChildTables() as $child) {
				$keys = array_keys($child);
				$sql = (string)"select * from `".$child[$keys[0]]."` as `".$keys[0]."` where ".preg_replace("/\?/", ($data["personid"] ? $data["personid"] : $data["id"]), $child["where"]);
				if ($order && $order["children"][$child[$keys[0]]]) {
				  $sql .= " ORDER BY ".$order["children"][$child[$keys[0]]]["column"].($order["children"][$child[$keys[0]]]["direction"] ? " ".$order["children"][$child[$keys[0]]]["direction"] : " ASC");
				}
				if (($result = $this->getConnection()->query($sql)) !== false) {
					$i = (int)0;
					while($row = $result->fetch_array()){
						foreach (array_keys($row) as $key) {
							if (is_string($key)) {
								$data["children"][$keys[0]][$i][$key] = $row[$key];
							}
						}
						$i++;
					}
					$result->close();
				} else {
					$message = str_replace("?", $this->getConnection()->error, self::SQL_RETURNED_ERROR);
					$this->log($this->getLogTypes("error"), $this->getLogUrgency("high"), "mySQL returned an error", $message);
					$this->setErrors($message, true);
					return false;
				}
			}
		}
		return $data;
	}
	
	/** TableManager::selectMultiple($selectSingle = false)
	  * return mySQL datasets
	  * @param bool $selectSingle whether or not to include self::SELECTSINGLE_NO_WHERE_DEFINED
	  * @return array dataset
	  * @example start
	  $tableManager = new TableManager("departments");
	  $tablemanager->setWhere("ISNULL(`departments`.`deleted`)");
	  $data = $tableManager->selectMultiple();
	  * @example end
	  * @example start
	  $userProfiles = new TableManager("user_profiles");
	  $userProfiles->setWhere(
	  $userProfiles->quoteString("userid>?", 0)
	  );
	  $userProfiles->setQueryColumns(array(
	  "user_profiles"=>array(
	  "*"
	  ),
	  "users"=>array(
	  0=>"concat_ws(' ', `users`.`firstname`, `users`.`lastname`) AS `full_name`"
	  ),
	  "m3_departments"=>array(
	  0=>"`m3_departments`.`name` AS `department`"
	  )
	  ));
	  $userProfiles->setQueryFrom(array(
	  "left join"=>array(0=>array(
	  "table"=>array("abbr"=>"users", "table"=>"users"),
	  "on"=>"`users`.`personid`=`user_profiles`.`userid`"
	  ),
	  1=>array(
	  "table"=>array("abbr"=>"m3_departments", "table"=>"m3_departments"),
	  "on"=>"`m3_departments`.`id`=`user_profiles`.`department_id`"
	  ))
	  ));
	  $userProfiles->setOrderBy(array("column"=>"RAND()", "direction"=>"DESC"));
	  $userProfiles->setLimit(array("limit"=>5));
	  $profiles = $userProfiles->selectMultiple();
	  * @example end
	*/
	public function selectMultiple($selectSingle = false)
	{
		if (!$this->getName()) {
			return self::SQL_NO_TABLE_NAME_DEFINED;
		}
		$sql = (string)"";
		if ($this->getExplain() === true) {
			$sql .= "EXPLAIN ";
		}
		$sql .= "SELECT";
		if ($this->getQueryColumns()) {
			$sql .= " ";
			foreach ($this->getQueryColumns() as $table=>$columns) {
				foreach ($columns as $column) {
					if (is_array($column)) {continue;}
					if (strstr($column, "AS") || strstr($column, "as")) {
						$sql .= "".$column.",";
					} elseif($column === "*") {
						$sql .= "`".$table."`.".$column.",";
					} else {
						$sql .= "`".$table."`.`".$column."`,";
					}
				}
			}
			$sql =substr($sql, 0, -1);
		} else {
			$sql .= " *";
		}
		$sql .= " FROM ";
		$queryFrom = $this->getQueryFrom();
		if ($queryFrom) {
			$sql .= "`".$this->getName()."` AS `".$this->getName()."`";
			foreach ($queryFrom as $joinType=>$tables) {
				foreach ($tables as $table) {
					$sql .= " ".strtoupper($joinType)." `".$table["table"]["table"]."` AS `".$table["table"]["abbr"]."` ON ". $table["on"];
				}
			}
		} else {
			$sql .= "`".$this->getName()."` AS `".$this->getName()."`";
		}
		$where = $this->getWhere();
		if ($where) {$sql .= " WHERE ".$where;}
		$group = $this->getGroupBy();
		if ($group) {
			$sql .= " GROUP BY ".$group["column"].($group["direction"] ? " ".$group["direction"] : "");
		}
		$order = $this->getOrderBy();
		if ($order) {
		  if (is_array($order["column"])) {
		    $sql .= " ORDER BY ";
		    if (is_array($order["direction"])) {
		      foreach ($order["column"] as $key=>$val) {
		        $sql .= $val." ".($order["direction"][$key] ? strtoupper($order["direction"][$key]) : "ASC").",";
		      }
		      $sql = substr($sql, 0, -1);
		    } else {
		      foreach ($order["column"] as $val) {
		        $sql .= $val." ".($order["direction"] ? strtoupper($order["direction"]) : "ASC").",";
		      }
		      $sql = substr($sql, 0, -1);
		    }
		  } else {
		    $sql .= " ORDER BY ".$order["column"]." ".(isset($order["direction"]) ? strtoupper($order["direction"]) : "ASC");
		  }
		}
		$limit = $this->getLimit();
		if ($limit) {
			$sql .= " LIMIT ".$limit["limit"];
		}
		
		$customIndex	= $this->getCustomIndex();
		
		$data = (array)array();
		if (($result = $this->getConnection()->query($sql)) !== false) {
			$i = (int)0;
			$this->appendSqlStatement("", $sql);
			while($row = $result->fetch_array()){
				if($customIndex) {
					$rowIndex	= $row[$customIndex];
				} else {
					$rowIndex	= $i;
				}
				
				foreach (array_keys($row) as $key) {
					if (is_string($key)) {
						$data[$rowIndex][$key] = $row[$key];
					}
				}
				$i++;
			}
			$result->close();
		} else {
			$message = str_replace("?", $this->getConnection()->error, self::SQL_RETURNED_ERROR);
			$this->log($this->getLogTypes("error"), $this->getLogUrgency("high"), "mySQL returned an error", $message);
			$this->setErrors($message, true);
			return false;
		}
		if ($this->getChildRecords() === true && $this->getChildTables()) {
			foreach ($this->getChildTables() as $child) {
				$keys = array_keys($child);
				foreach ($data as $key=>$record) {
					$data[$key]["children"] = (array)array();
					$data[$key]["children"][$child[$keys[0]]] = (array)array();
					$sql = (string)"select * from `".$child[$keys[0]]."` as `".$keys[0]."` where ".preg_replace("/\?/", ($record["personid"] ? $record["personid"] : $record["id"]), $child["where"]);
					$sql .= " AND (`deleted`='0' OR ISNULL(`deleted`))";
					if ($order && $order["children"][$child[$keys[0]]]) {
					  $sql .= " ORDER BY ".$order["children"][$child[$keys[0]]]["column"].($order["children"][$child[$keys[0]]]["direction"] ? " ".$order["children"][$child[$keys[0]]]["direction"] : " ASC");
					}
					if (($result = $this->getConnection()->query($sql)) !== false) {
						$i = (int)0;
						$this->appendSqlStatement("", $sql);
						$childData = (array)array();
						while($row = $result->fetch_array()){
							foreach (array_keys($row) as $k=>$field) {
								if (is_string($field)) {
									$childData[$field] = $row[$field];
								}
							}
							$data[$key]["children"][$child[$keys[0]]][] = $childData;
							$i++;
						}
						$result->close();
					} else {
						$message = str_replace("?", $this->getConnection()->error, self::SQL_RETURNED_ERROR);
						$this->log($this->getLogTypes("error"), $this->getLogUrgency("high"), "mySQL returned an error", $message);
						$this->setErrors($message, true);
						return false;
					}
				}
			}
		}
		return $data;
	}
	
	/** TableManager::truncate($tableName = null)
	  * @param string $tableName which table do you want to delete?
	  * @return true on success false otherwise
	*/
	public function truncate($tableName = null)
	{
		if ($tableName === null) {
			$tableName = $this->getName();
		}
		if (!$tableName) {
			return self::SQL_NO_TABLE_NAME_DEFINED;
		}
		$sql = (string)"TRUNCATE TABLE ".$tableName;
		if ($this->getConnection()->query($sql) !== false) {
			$this->appendSqlStatement("", $sql);
			return true;
		} else {
			$message = str_replace("?", $this->getConnection()->error, self::SQL_RETURNED_ERROR);
			$this->log($this->getLogTypes("error"), $this->getLogUrgency("high"), "mySQL returned an error", $message);
			$this->setErrors($message, true);
			return false;
		}
	}
	
	/** TableManager::update(array $data)
	  * Standard SQL update
	  * @param array $data data to be updated
	  * @return array data on success, string error otherwise
	  * @example starts
	  $tablemanager = new TableManager("departments");
	  $tableManager->setWhere(
	  $tableManager->quoteString("`departments`.`id`=?", 45)
	  );
	  $tableManager->update(array(
	  "deleted"=>$_SERVER["REQUEST_TIME"],
	  "userId"=>175
	  ));
	  * @example ends
	*/
	public function update(array $data)
	{
		if (!$this->getName()) {
			return self::SQL_NO_TABLE_NAME_DEFINED;
		}
		$sql = (string)"update `".$this->getName()."` set ";
		foreach ($data as $key=>$val) {
			$sql .= "`".$key."`=".$this->quoteVariable($val).",";
		}
		$sql = substr($sql, 0, -1);
		$where = $this->getWhere();
		if ($where) {$sql .= " where ".$where;}
    
		if (($result = $this->getConnection()->query($sql)) !== false) {
			$this->appendSqlStatement("", $sql);
			return $this->selectMultiple();
			$result->close();
		} else {
			$message = str_replace("?", $this->getConnection()->error, self::SQL_RETURNED_ERROR);
			$this->log($this->getLogTypes("error"), $this->getLogUrgency("high"), "mySQL returned an error", $message);
			$this->setErrors($message, true);
			return false;
		}
	}
	
	//: Private functions
	/** TableManager::__testVariable($var)
	  * Test what type a variable is
	  * @param $var variable to test
	  * @return string variable type on success, null otherwise
	*/
	private function __testVariable($var)
	{
		$return = null;
		if (is_array($var)) {
			return "array";
		} elseif (is_int($var)) {
			return "integer";
		} elseif (is_bool($var)) {
			return "bool";
		} elseif (is_float($var)) {
			return "float";
		} elseif (is_null($var)) {
			return "null";
		} elseif (is_numeric($var)) {
			return "numeric";
		} elseif (is_object($var)) {
			return "object";
		} elseif (is_resource($var)) {
			return "resource";
		} elseif (is_string($var)) {
			return "string";
		} elseif (is_scalar($var)) {
			return "scalar";
		}
		return $return;
	}
}