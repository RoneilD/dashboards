<?php
/** class::Candidates
	* @author Feighen OOsterbroek
	* @author feighen@manlinegroup.com
	* @copyright 2009 onwards Manline (Pty) Ltd
*/
require_once(BASE.'/basefunctions/baseapis/man_exception.class.php');
require_once(BASE.'/basefunctions/baseapis/Table.class.php');
class Candidates extends Table {
	//: Variables
	protected $_children = array(
		'candidate_events'=>array('table'=>'candidate_events', 'cols'=>array('id', 'attended', 'passed', 'date', 'event'), 'customkey'=>'event'),
		'candidate_notes'=>array('table'=>'candidate_notes', 'cols'=>array('id', 'note', 'date'), 'customkey'=>'id')
	);
	protected $_cols = array();
	protected $_dependantTables = array(
		'candidate_status'=>array('table'=>array('cs'=>'candidate_status'), 'on'=>'cs.id=c.statusid', 'cols'=>'if(c.statusid, cs.code, if(isnull(c.statusid), "No Status", if (c.statusid=0, "Passed", "Failed"))) as status'),
		'drivers'=>array('table'=>array('d'=>'drivers'), 'on'=>'d.id=c.driverid', 'cols'=>'concat_ws(" ", d.firstname, d.lastname) as driver'),
		'users'=>array('table'=>array('u'=>'users'), 'on'=>'u.personid=c.convertedby', 'cols'=>'u.personid')
	);
	protected $_name = 'candidates';
	protected $_primary;
	
	//: Public functions
	/** Candidates::__construct()
		* class constructor
		* @see Table::__construct()
		* @return null
	*/
	public function __construct() {parent::__construct($this->_name);}
	
	public function __destruct() {unset($this);}
	
	/** Candidates::cascadeDelete($id)
		* @param $id integer record pointer
		* @return true on success false otherwise
	*/
	public function cascadeDelete($id) {
		foreach ($this->_children as $key=>$val) {
			$opts = (array)array();
			$opts['table'] = $val['table'];
			$opts['fields']['deleted'] = time();
			$opts['where'] = 'candidateid='.$id;
			if (($update = sqCommit($opts)) === false) {
				throw new man_exception('Could not successfully query the database on line: '.__LINE__.' of file: '.__FILE__);
			}
		}
		$opts = (array)array();
		$opts['table'] = $this->_name;
		$opts['fields']['deleted'] = time();
		$opts['where'] = 'id='.$id;
		return sqlCommit($opts);
	}
	
	/** Candidates::getRow($options)
		* @param $options array
		* @param $options['where'] string standard sql where statement
		* @return array data
	*/
	public function getRow(array $options) {
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
		if ($options['children']) {
			foreach ($this->_children as $key=>$val) {
				$child = (array)array();
				$child['select'] = is_array($val['cols']) ? implode(',', $val['cols']) : $val['cols'];
				$child['table'] = $val['table'];
				$child['where'] = 'candidateid='.$data['id'];
				$child['customkey'] = $val['customkey'];
				$data[$val['table']] = sqlPull($child);
			}
		}
		return $data;
	}
	
	/** Candidates::getRowSet($options)
		* @param $options array
		* @return array data on success false otherwise
	*/
	public function getRowSet(array $options = array()) {
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
					$child['where'] = 'candidateid='.$id;
					$child['customkey'] = $val['customkey'];
					$chilren = sqlPull($child);
					$data[$id][$val['table']] = $chilren;
				}
			}
		}
		return $data;
	}
	
	/** Candidates::setVariables($config)
		* @param $config array
		* @param $config['children'] = array chilren in format array('table'=>'pick_my_socks_up', 'cols'=>'*' | array('field1', 'field2'))
		* @param $config['dependants'] = array dependants in format array(0=>array('table'=>array('alias'=>'tablename'), 'on'=>string standard sql on, 'cols'=>array('field', 'field', 'field')))
		* @return null
	*/
	public function setVariables(array $config = array()) {
		if (isset($config['children'])) {$this->_children = $config['children'];}
		if (isset($config['dependants'])) {$this->_dependantTables = $config['dependants'];}
		return null;
	}
	
	//: Private functions
	
}
