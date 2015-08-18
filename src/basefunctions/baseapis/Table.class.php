<?php
require_once(BASE.'/basefunctions/baseapis/manapi.php');
/** class::table
	* @author Feighen Oosterbroek
	* @author feighen@manlinegroup.com
	* @copyright 2009 onwards Manline (Pty) Ltd
	* Generic table data manipulation class
*/
abstract class Table {
	//: Variables
	protected $_cols = array();
	protected $_name;
	protected $_primary;
	
	//: Public functions
	public function __construct($name) {
		$this->_name = $name;
		$this->buildCols();
	}
	
	public function __destruct() {unset($this);}
	
	public function buildCols() {
		$query = 'describe '.$this->_name;
		$this->_colDetails = sqlQuery($query);
		foreach ($this->_colDetails as $key=>$val) {
			if ($val['Key'] == 'PRI') {$this->_primary = $val['Field'];}
			$this->_cols[$val['Field']] = $val['Field'];
		}
	}
	
	abstract public function cascadeDelete($id);
	
	/** Table::create($data, $options)
		* @param $data array array data to be inserted into the database
		* @param $options array array options to be passed to sqlCreate
		* @return true|array data on success false otherwise
		* @example $users = new Users();
							 $users->create(array('name'=>'sdf'), array('record'=>true));
	*/
	public function create(array $data, array $options = array()) {
		foreach ($data as $key=>$val) {if (!in_array($key, $this->_cols)) {unset($data[$key]);}}
		$opts = (array)array();
		$opts['table'] = $this->_name;
		$opts['fields'] = $data;
		$data = sqlCreate($opts);
		if (array_key_exists("record", $options) && $options['record']) {$data = sqlPull(array('table'=>$this->_name, 'where'=>$this->_primary.'='.$data, 'onerow'=>true));}
		return $data;
	}
	
	public function delete($id, array $options = array()) {
		$opts = (array)array();
		$opts['table'] = $this->_name;
		$opts['fields']['deleted'] = time();
		$opts['where'] = $this->_primary.'='.$id;
		return sqlCommit($opts);
	}
	
	public function getCols() {return $this->_cols;}
	
	abstract public function getRow(array $options);
	
	abstract public function getRowSet(array $options = array());
	
	/** Table::reinstate($id)
		* @param $id int record pointer
		* @see sqlCommit
		* @return true on success false otherwise
	*/
	public function reinstate($id) {
		$opts = (array)array();
		$opts['table'] = $this->_name;
		$opts['fields']['deleted'] = false;
		$opts['where'] = 'id='.$id;
		return sqlCommit($opts);
	}
	
	/** Table::update($where, $data, $options)
		* @param $where string standar sql where statement
		* @param $data array array data to be inserted into the database
		* @param $options array array options to be passed to sqlCreate
		* @return true|array data on success false otherwise
		* @example $users = new Users();
							 $users->update('firstname like "%john%"', array('name'=>'sdf'), array('record'=>true));
	*/
	public function update($where, array $data, array $options = array()) {
		foreach ($data as $key=>$val) {if (!in_array($key, $this->_cols)) {unset($data[$key]);}}
		$opts = (array)array();
		$opts['table'] = $this->_name;
		$opts['fields'] = $data;
		$opts['where'] = $where;
		if ((sqlCommit($opts)) === false) {
			return false;
		} else {
			if ($options['record']) {
				return sqlPull(array('table'=>$this->_name, 'where'=>$where));
			}
			return true;
		}
	}
}
