<?php
$base = substr(__DIR__, 0, strrpos(__DIR__, "M"))."Maxine/";
//$base = substr(__DIR__, 0, strrpos(__DIR__, "M"));
defined("DS") || define("DS", DIRECTORY_SEPARATOR);
if (!isset($test)) {
	$times = substr_count($_SERVER['PHP_SELF'],"/");
	$rootaccess = (string)"";
	$i = 1;

	while ($i < $times) {
		$rootaccess .= "../";
		$i++;
	}
	defined("BASE") || define("BASE", $rootaccess);
}
$base = $base ? $base : BASE;
require_once($base."basefunctions".DS."localdefines.php");
$parent = substr(dirname(realpath(__FILE__)), 0, strrpos(dirname(realpath(__FILE__)), DS));
require_once($parent.DS."ObjectCache.php");
/** CLASS::CacheFile
  * @author Feighen Oosterbroek
  * @author feighen@manlinegroup.com
  * @copyright 2010 onwards Manline Group (Pty) Ltd
*/
class CacheFile extends ObjectCache
{
        //: Variables
        protected $_data = array();
        protected $_file;
        protected $_pointer;
        
        //: Public functions
        //: Getters and Setters
        /** CacheFile::getData($key = null)
          * get the data in the cache
          * @return mixed if key isset array otherwise $this->_data
        */
        public function getData($key = null)
        {
                if ($key === null) {
                        return $this->_data;
                } else {
                        if (array_key_exists($key, $this->_data)) {
                                return $this->_data[$key];
                        } else {
                                return false;
                        }
                }
        }
        
        /** CacheFile::getFile()
          * @return string $this->_file return the filename that we are saving to
        */
        public function getFile()
        {
                return (string)$this->_file;
        }
        
        /** CacheFile::getPointer()
          * return the open file pointer
          * @return object reference $this->_pointer
        */
        public function getPointer()
        {
                return $this->_pointer;
        }
        
        /** CacheFile::setData($data, $append = false, $key = null)
          * Set the data for the cache
          * @param mixed $data what data needs to go into the cache?
          * @param bool $append append to the end of the array or overwrite?
          * @param mixed $key array key entry
        */
        public function setData($data, $append = false, $key = null)
        {
                if ($append === true) {
                        $current = self::getData();
                        $current[$key ? $key : count($current)] = $data;
                }
                $this->_data = isset($current) ? $current : $data;
        }
        
        /** CacheFile::setFile($file)
          * @param string $file file location on file system tree
          * @example start
          CacheFile::setFile(__DIR__.DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR."tableManager.csv");
          * @example end
        */
        public function setFile($file)
        {
                $this->_file = (string)$file;
        }
        
        /** CacheFile::setPointer($ptr = null)
          * @param resource $ptr fopen resource
        */
        public function setPointer($ptr = null)
        {
                if ($ptr === null) {
                        if (file_exists($this->getName()) === true) {
                        	unlink(self::getName());
                        }
                        $ptr = fopen(self::getFile(), "w+");
                }
                $this->_pointer = $ptr;
        }
        //: End
        
        public function load($key = null)
        {
                return self::getData($key);
        }
        
        //: Magic
        /** CacheFile::__construct()
          * Class constructor
        */
        public function __construct($fileName)
        {
                self::setFile($fileName);
                if (self::testTimeToLive() === true) {
                        $fp = fopen(self::getFile(), "r");
                        $data = (array)array();
                        while (($row = fgetcsv($fp)) !== false) {
                                if ((count($row) === 2) && is_array(unserialize($row[1]))) {
                                        $data[$row[0]] = unserialize($row[1]);
                                } else {
                                        $data[] = $row;
                                }
                        }
                        fclose($fp);
                        self::setData($data);
                }
        }
        
        /** CacheFile::__destruct()
          * Class destructor
          * Allow for garbage collection
        */
        public function __destruct()
        {
                $this->setPointer();
                ## Write the data array to file
                $i = (int)0;
                foreach (self::getData() as $key=>$val) {
                        if (!$val) {continue;}
                        $line = (string)"";
                        $keys = array_keys($val);
                        if (is_array($val[$keys[0]])) { ## Check to see if the data being written is more than 2 levels deep?
                                $line .= $key.",".serialize($val).PHP_EOL;
                        } else {
                                if ($i == 0) {
                                        $line .= implode(",", $keys).PHP_EOL;
                                }
                                $line .= implode(",", $val).PHP_EOL;
                        }
                        is_resource(self::getPointer()) ? fwrite(self::getPointer(), $line) : "";
                        $i++;
                }
                unset($this);
        }
        //: End
        
        public function save(array $data, $key = null)
        {
                if ($key === null) {
                        self::setData($data);
                } else {
                        self::setData($data, true, $key);
                }
        }
        
        public function testTimeToLive()
        {
                if (file_exists(self::getFile()) === false) { ## if the file doesn't exist
                        return false;
                }
                $fileStat = stat(self::getFile());
                $diff = time()-$fileStat['mtime'];
                if ($diff > parent::getTimeToLive()) {
                        return false;
                }
                return true; 
        }
        
        //: Private functions
}
