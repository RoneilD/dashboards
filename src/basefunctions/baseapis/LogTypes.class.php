<?php
defined("DS") || define("DS", DIRECTORY_SEPARATOR);
require_once(BASE."basefunctions".DS."baseapis".DS."Table.class.php");
if (!defined("DB_PASS")) {
	include_once(__DIR__.DS."..".DS."localdefines.php");
}
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_SCHEMA) or die(mysqli_error($link));
class LogTypes extends Table
{
	//: Variables
	protected $_children = array(
		'logs'=>array('table'=>'logs', 'cols'=>array('*'), 'customkey'=>'log')
	);
	protected $_cols = array();
	protected $_dependantTables = array();
	protected $_name = 'log_types';
	protected $_primary;
	
	//: Public functions
	//: Magic functions
	/** Logging::__construct()
		* class constructor
		* @see Table::__construct()
		* @return null
	*/
	public function __construct() {parent::__construct($this->_name);}
	
	/** Logging::__destruct()
		* Class destructor
	*/
	public function __destruct() {unset($this);}
	//: End
	
	/** Logging::cascadeDelete($id)
		* @param $id int record pointer
		* @return true on success false otherwise
	*/
	public function cascadeDelete($id)
	{
		if ($this->_children) {
			foreach ($this->_children as $key=>$val) {
				$opts = (array)array();
				$opts['table'] = $val['table'];
				$opts['fields']['deleted'] = $_SERVER['REQUEST_TIME'];
				$opts['where'] = 'log_typeid='.$id;
				if (($update = sqCommit($opts)) === false) {
					throw new man_exception('Could not successfully query the database on line: '.__LINE__.' of file: '.__FILE__);
				}
			}
		}
		$opts = (array)array();
		$opts['table'] = $this->_name;
		$opts['fields']['deleted'] = $_SERVER['REQUEST_TIME'];
		$opts['where'] = 'id='.$id;
		return sqlCommit($opts);
	}
	
	//: Getters
	/** Logging::getRow($options)
		* @param $options array
		* @param $options['where'] string standard sql where statement
		* @return array data
	*/
	public function getRow(array $options)
	{
		$options['select'] = substr($this->_name, 0, 1).'.*';
		$options['table'] = $this->_name.' as '.substr($this->_name, 0, 1);
		foreach ($this->_dependantTables as $key=>$val) {
			$alias = array_keys($val['table']);
			$name = array_values($val['table']);
			$options['select'] .= ', '.$val['cols'];
			$options['table'] .= ' left join '.$name[0].' as '.$alias[0].' on '.$val['on'];
		}
		$options['onerow'] = 1;
		$data = sqlPull($options);
		if (isset($options['children']) && $options['children']) {
			foreach ($this->_children as $key=>$val) {
				$child = (array)array();
				$child['select'] = is_array($val['cols']) ? implode(',', $val['cols']) : $val['cols'];
				$child['table'] = $val['table'];
				$child['where'] = 'log_typeid='.$data['id'];
				$child['customkey'] = $val['customkey'];
				$data[$val['table']] = sqlPull($child);
			}
		}
		return $data;
	}
	
	/** Logging::getRowSet($options)
		* @param $options array
		* @return array data on success false otherwise
	*/
	public function getRowSet(array $options = array())
	{
		$options['select'] = substr($this->_name, 0, 1).'.*';
		$options['table'] = $this->_name.' as '.substr($this->_name, 0, 1);
		foreach ($this->_dependantTables as $key=>$val) {
			$alias = array_keys($val['table']);
			$name = array_values($val['table']);
			$options['select'] .= ', '.$val['cols'];
			$options['table'] .= ' left join '.$name[0].' as '.$alias[0].' on '.$val['on'];
		}
		$data = sqlPull($options);
		if ($options['children']) {
			foreach ($data as $id=>$row) {
				foreach ($this->_children as $key=>$val) {
					$child = (array)array();
					$child['select'] = is_array($val['cols']) ? implode(',', $val['cols']) : $val['cols'];
					$child['table'] = $val['table'];
					$child['where'] = 'log_typeid='.$id;
					$child['customkey'] = $val['customkey'];
					$chilren = sqlPull($child);
					$data[$id][$val['table']] = $chilren;
				}
			}
		}
		return $data;
	}
	//: End
}