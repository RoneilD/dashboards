<?php
/** @file: mysqlwrapper.class.php
	* @author Feighen Oosterbroek
	* @author feighen@manlinegroup.com
	* @copyright 2009 onwards Manline (Pty) Ltd
	* @example multiple table delete
		$where = (string)$mysql->quoteInto('c.id=?', '573');
		$opts = (array)array(
			'low_priority'=>true,
			'from'=>array(array('table'=>'candidates'), array('table'=>'candidate_events', 'on'=>'c.id=caev.candidateid'), array('table'=>'candidate_notes', 'on'=>'c.id=cano.candidateid')),
			'where'=>$where
		);
		if (($mysql->delete($opts)) === false) {
			print("<pre style='font-family:verdana;font-size:13'>");
			print_r($mysql->getErrors());
			print("</pre>");
		}
	* end multiple table delete
	* @example single table delete
		$where = (string)$mysql->quoteInto('name=?', 'truck1501');
		$opts = (array)array('low_priority'=>true, 'from'=>array(array('table'=>'trucks')), 'where'=>$where);
		if (($mysql->delete($opts)) === false) {
			print("<pre style='font-family:verdana;font-size:13'>");
			print_r($mysql->getErrors());
			print("</pre>");
		}
	* end single table delete
	* @example multiple inserts
		$table = (string)'trucks';
		$data = (array)array();
		$str = (string)'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		for ($i=0; $i<150; $i++) {
			$name = (string)'';
			for ($j=0; $j<8; $j++) {$name .= $str[mt_rand(1, strlen($str)-1)];}
			$data[] = (array)array('name'=>$name);
		}
		if (($results = $mysql->insert($table, $data)) === false) {
			print("<pre style='font-family:verdana;font-size:13'>");
			print_r($mysql->getErrors());
			print("</pre>");
		}
	* end multiple inserts
	* @example single insert
		$table = (string)'trucks';
		$data = (array)array('name'=>'truck1501');
		if (($results = $mysql->insert($table, $data)) === false) {
			print("<pre style='font-family:verdana;font-size:13'>");
			print_r($mysql->getErrors());
			print("</pre>");
		}
	* end single insert
	* @example simple select
		$config = (array)array();
		$config['options'] = (string)'SQL_CALC_FOUND_ROWS';
		$config['fields'] = (string)'c.*';;
		$config['from'] = (string)'candidates as c';
		$config['procedure'] = true;
		if (($results = $mysql->select($config)) === false) {
			print("<pre style='font-family:verdana;font-size:13'>");
			print_r($mysql->getErrors());
			print("</pre>");
		}
	* end simple select
	* @example complex select
		$config = (array)array();
		$config['options'] = (string)'SQL_CALC_FOUND_ROWS';
		$config['fields'] = (array)array('c'=>'c.id', 'fullname'=>'concat_ws(" ", c.firstname, c.lastname)', 'id_number'=>'c.idno', 'status'=>'concat_ws(" ", cs.name, concat("(", cs.code, ")"))');
		$config['from'] = (array)array(array('table'=>array(0=>'candidates as c')), array('table'=>array(0=>'candidate_status as cs'), 'on'=>array('cs.id=c.statusid')));
		if (($results = $mysql->select($config)) === false) {
			print("<pre style='font-family:verdana;font-size:13'>");
			print_r($mysql->getErrors());
			print("</pre>");
		}
	* end complex select
	* @example update
		$table = (string)'trucks';
		$where = (string)$mysql->quoteInto('id=?', 192);
		$data = (array)array('name'=>'sdf truck');
		if (($mysql->update($table, $where, $data)) === false) {
			print("<pre style='font-family:verdana;font-size:13'>");
			print_r($mysql->getErrors());
			print("</pre>");
		}
	* end update
*/
class MysqlWrapper
{
	//: variables
	protected $_config = array('host'=>'localhost', 'user'=>'root', 'pass'=>'', 'dbname'=>'maxinedb');
	protected $_errors = array();
	protected $_test;
	protected $_validators = array();
	// error constants
	const fatal = 'fatal';
	const inform = 'informational';
	// validator constants
	const char = '/.+/i';
	const flo = '/[0-9.]{,?}/';
	const num = '/[0-9]{,?}/';
	
	
	//: public functions
	//: Magic functions
	/** mysqlwrapper::__construct($config = null)
		* @param $config['host'] string defaults to localhost
		* @param $config['user'] string defaults to root
		* @param $config['pass'] string defaults to ''
		* @param $config['dbname'] string defaults to maxinedb
		* @param $config['test'] bool defaults to false
	*/
	public function __construct($config = null) {
		if ($config['host']) {$this->_config['host'] = $config['host'];}
		if ($config['user']) {$this->_config['user'] = $config['user'];}
		if ($config['pass']) {$this->_config['pass'] = $config['pass'];}
		if ($config['dbname']) {$this->_config['dbname'] = $config['dbname'];}
		if ($config['test']) {$this->_test = true;}
	}
	
	public function __destruct(){unset($this);}
	//: End magic functions
	
	/** mysqlwrapper::delete(array $options)
		* Generic delete as from mysql manual
			DELETE [LOW_PRIORITY] [QUICK] [IGNORE]
			tbl_name[.*] [, tbl_name[.*]] ...
			FROM table_references
			[WHERE where_condition]
		*
		* @param $options['low_priority'] bool true if you want the delete to be held off until all table locks are released
		* @param $options['from'] array which table(s) do you want to delete from array format array("table"=>"users"(required), "on")
		* @param $options['where'] string standard sql where statement
	*/
	public function delete(array $options) {
		$mysql = new mysqli($this->_config['host'], $this->_config['user'], $this->_config['pass'], $this->_config['dbname']);
		if (mysqli_connect_errno()) {
			$this->_errors[fatal][] = "Connect failed: ".mysqli_connect_error();
			return false;
		}
		$sql = (string)'delete '.(isset($options['low_priority']) && $options['low_priority'] ? 'low_priority ' : '');
		if (is_array($options['from'])) {
			foreach ($options['from'] as $key=>$val) {
				if ($val['table']) {
					if ($val['on']) {
						$split = preg_split('/_/', $val['table']);
						$sql .= substr($split[0], 0, 2).substr($split[1], 0, 2).".*, ";
					} else {
						$sql .= substr($val['table'], 0, 1).".*, ";
					}
				}
			}
			$sql = rtrim($sql, ', ');
			$sql .= ' from ';
			foreach ($options['from'] as $key=>$val) {
				if ($val['on']) {
					$split = preg_split('/_/', $val['table']);
					$sql .= " left join ".$val['table']." as ".substr($split[0], 0, 2).substr($split[1], 0, 2);
					$sql .= " on ".$val['on'];
				} else {
					$sql .= $val['table']." as ".substr($val['table'], 0, 1);
				}
				$sql .= " ";
			}
		} else {
			$this->_errors[fatal][] = 'Could not successfully determine which tables to delete from';
			return false;
		}
		if (isset($options['where']) && $options['where']) {
			$sql .= " where ".$options['where'];
		}
		if ($this->_test) {
			print("<pre style='font-family:verdana;font-size:13'>");
			print_r($sql);
			print("</pre>");
			return false;
		}
		if (($result = $mysql->query($sql)) === false) {
			$this->_errors[fatal][] = 'Could not successfully query the database.';
		}
		$mysql->close();
		return $result;
	}
	
	/** mysqlwrapper::describe($table)
		* describe a table uses show full columns from $table
		* @param $table string table to describe
		* @return array data on success false otherwise
	*/
	public function describe($table) {
		$sql = (string)'show full columns from '.$table;
		if ($this->_test) {
			print("<pre style='font-family:verdana;font-size:13'>");
			print_r($sql);
			print("</pre>");
			return false;
		}
		$mysql = new mysqli($this->_config['host'], $this->_config['user'], $this->_config['pass'], $this->_config['dbname']);
		if (mysqli_connect_errno()) {
			$this->_errors[fatal][] = "Connect failed: ".mysqli_connect_error();
			return false;
		}
		if (($result = $mysql->query($sql)) === false) {
			$this->_errors[fatal][] = 'Could not successfully query the database.';
		}
		$return = (array)array();
		while($obj = $result->fetch_object()){
			$return[$obj->Field] = (array)array(
				'field'=>$obj->Field, 'type'=>$obj->Type,'key'=>$obj->Key, 'default'=>$obj->Default, 'privileges'=>$obj->Privileges, 'comment'=>$obj->Comment
			);
		} 
		$result->close();
		$mysql->close();
		return $return;
	}
	
	//: Getters
	public function getErrors() {return $this->_errors;}
	public function getTest() {return $this->_test;}
	public function getValidators() {return $this->_validators;}
	//: End getters
	
	/** mysqlwrapper::insert(array $data)
		* generic insert as from mySQL manual
			INSERT [LOW_PRIORITY | DELAYED | HIGH_PRIORITY] [IGNORE]
			[INTO] tbl_name [(col_name,...)]
			{VALUES | VALUE} ({expr | DEFAULT},...),(...),...
		*
		* @param $table string table to insert into
		* @param $data array data to insert
		* @param $opts array options for select
	*/
	public function insert($table, array $data, array $opts = null) {
		$mysql = new mysqli($this->_config['host'], $this->_config['user'], $this->_config['pass'], $this->_config['dbname']);
		if (mysqli_connect_errno()) {
			$this->_errors[fatal][] = "Connect failed: ".mysqli_connect_error();
			return false;
		}
		self::_setUpvalidators($table);
		$sql = (string)"insert ";
		if ($opts['priority']) {$sql .= $opts['priority'].' ';}
		if ($opts['ignore']) {$sql .= 'ignore ';}
		$sql .= "into ".$table;
		$cols = array_keys($data);
		if (is_int($cols[0])) { // we know that you are doing a multiple insert
			$act = array_keys($data[0]);
			$sql .= " (".implode(',', $act).")";
		} elseif (is_string($cols[0])) { // single insert
			$sql .= " (".implode(',', $cols).")";
		}
		$sql .= " values ";
		if (is_string($cols[0])) {
			$sql .= "(".self::_validateData($cols, $data)."),";
			$sql = rtrim($sql, ",");
		} elseif (is_int($cols[0])) {
			foreach ($data as $key=>$val) {
				$sql .= "(".self::_validateData(array_keys($val), $val)."),";
			}
			$sql = rtrim($sql, ',');
		}
		if ($this->_test) {
			print("<pre style='font-family:verdana;font-size:13'>");
			print_r($sql);
			print("</pre>");
			return false;
		}
		if (($result = $mysql->query($sql)) === false) {
			$this->_errors[fatal][] = 'Could not successfully query the database.';
		}
		$mysql->close();
		return $result; 
	}
	
	/** mysqlwrapper::quoteInto($string, $data)
		* @param $string string quote data into this string
		* @param $data variable data to be quoted into string $string
	*/
	public function quoteInto($string, $value) {
		$mysql = new mysqli($this->_config['host'], $this->_config['user'], $this->_config['pass'], $this->_config['dbname']);
		$ret = (string)preg_replace("/\?/", "\"".$mysql->real_escape_string($value)."\"", $string);
		return $ret;
	}
	
	/** mysqlwrapper::select(array $config)
		* select statement as from mysql manual
			SELECT
    	[ALL | DISTINCT | DISTINCTROW ]
    	[HIGH_PRIORITY]
    	[STRAIGHT_JOIN]
    	[SQL_SMALL_RESULT] [SQL_BIG_RESULT] [SQL_BUFFER_RESULT]
    	[SQL_CACHE | SQL_NO_CACHE] [SQL_CALC_FOUND_ROWS]
    	select_expr [, select_expr ...]
    	[FROM table_references
    	[WHERE where_condition]
    	[GROUP BY {col_name | expr | position}
    	[ASC | DESC], ... [WITH ROLLUP]]
    	[HAVING where_condition]
    	[ORDER BY {col_name | expr | position}
    	[ASC | DESC], ...]
    	[LIMIT {[offset,] row_count | row_count OFFSET offset}]
    	[PROCEDURE procedure_name(argument_list)]
    	[INTO OUTFILE 'file_name' export_options // not implemented
		*
		* @param $config[options] string options as from [ALL | DISTINCT | DISTINCTROW ] to [SQL_CALC_FOUND_ROWS]
		* @param $config[fields] string or array which data to return
		* @param $config[from] string or array where to get the data from
		* @param $config[where] string standard sql where
		* @param $config[group] string standard sql group
		* @param $config[having] string standard sql having
		* @param $config[order] string standard sql order by
		* @param $config[direction] string sort direction for order and group list('ASC', 'DESC')
		* @param $config[limit] array standard sql limit array('limit'=>52, 'offset'=>52)
		* @param $config[procedure] bool standard sql procedure statement only implemented as analyse (50, 1000)
		* @return array data on success false otherwise 
	*/
	public function select(array $config) {
		$mysql = new mysqli($this->_config['host'], $this->_config['user'], $this->_config['pass'], $this->_config['dbname']);
		if (mysqli_connect_errno()) {
			$this->_errors[fatal][] = "Connect failed: ".mysqli_connect_error();
			return false;
		}
		$sql = (string)'select ';
		if ($config['options']) {$sql .= $config['options']." ";}
		if (is_string($config['fields'])) {
			$sql .= $config['fields']." ";
		} elseif (is_array($config['fields'])) {
			foreach ($config['fields'] as $key=>$val) {
				$sql .= $val;
				if (is_string($key)) {$sql .= " as ".$key;}
				$sql.= ",";
			}
			$sql = rtrim($sql, ",");
		} else {
			$this->_errors[fatal][] = 'Fields not in a recognisable format. Needs to be either an array or a string. array("sdf"=>"s.sdf", "dfg"=>"s.dfg") or "s.sdf, s.dfg"';
			return false;
		}
		if (!$config['from']) {$this->_errors[fatal][] = 'Could not successfully ascertain which table(s) to query'; return false;}
		if (is_string($config['from'])) {
			$sql .= " from ".$config['from'];
		} elseif (is_array($config['from'])) {
			$sql .= " from ";
			foreach ($config['from'] as $key=>$val) {
				if ($key > 0 && (isset($val['on']) && !$val['on'])) {
					$this->_errors[fatal][] = 'Could not successfully join tables together. Required parameters not set';
					return false;
				}
				$join = (string)' left join ';
				if ($key > 0 && isset($val['join'])) {$join = $val['join']." join ";}
				if ($key > 0) {$sql .= $join." ";}
				$sql .= $val['table'][0]." ";
				if ($key > 0) {$sql .= " on ".$val['on'][0]." ";}
			}
		} else {
			$this->_errors[fatal][] = 'Could not successfully ascertain which table(s) to query';
			return false;
		}
		if ($config['where']) {$sql .= " where ".$config['where'];}
		$config['direction'] = isset($config['direction']) ? $config['direction'] : 'ASC';
		if ($config['group']) {$sql .= " group by ".$config['group']." ".$config['direction'];}
		if ($config['having']) {$sql .= " having ".$config['having'];}
		if ($config['order']) {$sql .= " order by ".$config['order']." ".$config['direction'];}
		if ($config['limit']) {
			$sql .= " limit ".$config['limit']['limit'];
			if (isset($config['limit']['offset'])) {
				$sql .= " offset ".$config['limit']['offset'];
			}
		}
		if ($config['procedure']) {$sql .= " procedure analyse (50, 1000)";}
		
		if ($this->_test) {
			print("<pre style='font-family:verdana;font-size:13'>");
			print_r($config);
			print("</pre>");
			print("<pre style='font-family:verdana;font-size:13'>");
			print_r($sql);
			print("</pre>");
			return false;
		}
		// do the actual query
		if (($result = $mysql->query($sql)) === false) {
			$this->_errors[fatal][] = 'Could not successfully query the database.';
		}
		$records = (array)array();
		while($obj = $result->fetch_object()){
			$data = (array)array();
			foreach ($obj as $key=>$val) {$data[$key] = $val;}
			$records[] = $data;
			unset($data);
		} 
		$result->close();
		$mysql->close();
		return $records;
	}
	
	//: Setters
	/** mysqlwrapper::setConfig(array $config)
		* @param $config['host'] string defaults to localhost
		* @param $config['user'] string defaults to root
		* @param $config['pass'] string defaults to ''
		* @param $config['dbname'] string defaults to maxinedb
	*/
	public function setConfig(array $config) {$this->config = $config;}
	/** mysqlwrapper::setTest($test)
		* @param $test bool whether testing is enabled
	*/
	public function setTest($test) {$this->_test = $test;}
	//: End setters
	
	/** mysqlwrapper::update($where, array $data)
		* update as from mysql manual
			UPDATE [LOW_PRIORITY] [IGNORE] table_references
			SET col_name1={expr1|DEFAULT} [, col_name2={expr2|DEFAULT}] ...
			[WHERE where_condition]
		*
		* @param $where string standard sql where
		* @param $data array data to be updated
	*/
	public function update($table, $where, array $data) {
		self::_setUpvalidators($table);
		$mysql = new mysqli($this->_config['host'], $this->_config['user'], $this->_config['pass'], $this->_config['dbname']);
		if (mysqli_connect_errno()) {
			$this->_errors[fatal][] = "Connect failed: ".mysqli_connect_error();
			return false;
		}
		$sql = (string)"update low_priority ".$table." set ";
		if (is_array($data)) {
			foreach ($data as $key=>$val) {
				$sql .= "`".$key."`=\"".$mysql->real_escape_string($val)."\",";
			}
			$sql = rtrim($sql, ",");
		} else {
			$this->_errors[fatal][] = 'Could not successfully determine which columns to update';
		}
		$sql .= " where ".$where;
		if ($this->_test) {
			print("<pre style='font-family:verdana;font-size:13'>");
			print_r($sql);
			print("</pre>");
			return false;
		}
		if (($result = $mysql->query($sql)) === false) {
			$this->_errors[fatal][] = 'Could not successfully query the database.';
			return false;
		}
		$mysql->close();
		return $result; 
	}
	
	//: Private functions
	/** mysqlwrapper::_setUpValidators($table)
		* @param $table string table name
	*/
	private function _setUpvalidators($table) {
		$meta = self::describe($table);
		foreach ($meta as $key=>$val) {
			$this->_validators[$val['field']] = array();
			preg_match('/[0-9]{1,}/', $val['type'], $out);
			if (preg_match("/int/", $val['type'])) {
				$reg = self::num;
			} elseif (preg_match("/char/", $val['type'])) {
				$reg = self::char;
				if (preg_match('/\?/', self::char) && isset($out[0])) {
					$this->_validators[$val['field']][] = array('Length', false, array(0, $out[0]));
				}
			}
			if (preg_match('/\?/', $reg)) {$reg = preg_replace('/\?/', isset($out[0]) && $out[0] ? $out[0] : '', $reg);}
			$this->_validators[$val['field']][] = array('Regex', false, $reg);
		}
	}
	
	/** mysqlwrapper::_validatedata($cols, $data)
		* @param $cols array column meta data
		* @param $data array actual column data
		* @return string sql escaped and validated data
	*/
	private function _validateData($cols, $data) {
		$mysql = new mysqli($this->_config['host'], $this->_config['user'], $this->_config['pass'], $this->_config['dbname']);
		$sql = (string)'';
		foreach ($cols as $key=>$val) {
			if (isset($this->_validators[$val]) && $this->_validators[$val]) { // validate the data
				foreach ($this->_validators[$val] as $vkey=>$vval) {
					if (preg_match($vval[2], $data[$val]) === false) {
						$this->_errors[fatal][] = 'Could not successfully validate column data for column: '.$val;
						return false;
					}
				}
				$sql .= "\"".$mysql->real_escape_string($data[$val])."\","; 
			} else { // we need to escape and filter the input
				$sql .= "\"".$mysql->real_escape_string($data[$val])."\",";
			}
		}
		$mysql->close();
		unset($mysql);
		return rtrim($sql, ',');
	}
}
