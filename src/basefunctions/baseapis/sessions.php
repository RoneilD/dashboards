<?php
defined("DS") || define("DS", DIRECTORY_SEPARATOR);
require_once(realpath(dirname(__FILE__)).DS."TableManager.php");
/** Object::Baseapis_Sessions
    * @author Feighen Oosterbroek
    * @author feighen@manlinegroup.com
    * @copyright 2011 onwards Manline Group (Pty) Ltd
    * @license GNU GPL
    * @see http://www.gnu.org/copyleft/gpl.html
    */
class Baseapis_Sessions
{
    //: Variables
    protected $_expiry;
    protected $_manager;
    
    //: Public functions
    //: Accessors
    /** Baseapis_Sessions::getExpiry()
        * return the expiration in minutes
        * @return int $this->_expiry
        */
    public function getExpiry()
    {
        if (!$this->_expiry) {
            $this->setExpiry();
        }
        return $this->_expiry;
    }
    
    /** Baseapis_Sessions::getManager()
        * get the table Manager
        * @return TableManager $this->_manager
        */
    public function getManager()
    {
        if (!$this->_manager) {
            $this->setManager();
        }
        return $this->_manager;
    }
    
    /** Baseapis_Sessions::setExpiry($expires = NULL)
        * set the default expiry in minutes
        * @param int $expires
        * @default (int) 60*60
        */
    public function setExpiry($expires = NULL)
    {
        if (is_int($expires) === FALSE) {
            $expires = NULL;
        }
        if ($expires === NULL) {
            $expires = (int)60*60;
        }
        $this->_expiry = $expires;
    }
    
    public function setManager(TableManager $manager = NULL)
    {
        if ($manager === NULL) {
            $test = new TableManager();
            ## Test to see if the sessions table exists and if not create it
            $sql = (string)"SHOW TABLES LIKE 's%'";
            if (($tables = $test->runSql($sql)) === FALSE) {
                return FALSE;
            }
            if ($tables) {
                $exists = (bool)FALSE;
                foreach ($tables as $tbl) {
                    $keys = array_keys($tbl);
                    if ($tbl[$keys[0]] == "sessions") {
                        $exists = TRUE;
                        break;
                    }
                }
                if ($exists === FALSE) {
                    $sql = (string)"CREATE TABLE IF NOT EXISTS `sessions` (";
                    $sql .= "`id` INT(32) NOT NULL AUTO_INCREMENT PRIMARY KEY,";
                    $sql .= "`session_id` VARCHAR(150) NOT NULL DEFAULT '' UNIQUE,";
                    $sql .= "`access` INT(32) NOT NULL DEFAULT 0,";
                    $sql .= "`data` LONGTEXT,";
                    $sql .= "`ip_address` VARCHAR(15) NOT NULL DEFAULT '000.000.000.000',";
                    $sql .= "INDEX (`access`),";
                    $sql .= "INDEX (`ip_address`)";
                    $sql .= ") ENGINE=INNODB DEFAULT CHARSET=UTF8;";
                    if (($test->runSql($sql)) === FALSE) {
                        print("<pre>");
                        print_r($test->getErrors());
                        print("</pre>");
                        return FALSE;
                    }
                }
            }
            $test->__destruct();
            unset($test);
            $manager = new TableManager("sessions");
        }
        $this->_manager = $manager;
    }
    //: End
    
    /** Baseapis_Sessions::clean($max = NULL)
        * garbage collect all data older than $max or $this->_expiry minutes
        * @param INT $max maximum lifetime
        */
    public function clean($max = NULL)
    {
        if (is_int($max) === FALSE) {
            $max = NULL;
        }
        if ($max === NULL) {
            $max = $this->getExpiry();
        }
        $manager = $this->getManager();
        $old = (int)time() - $max;
        $where = (string)$manager->quoteString("`sessions`.`access`<?", $old);
        $sql = (string)"DELETE FROM `sessions` WHERE ".$where;
        if (($manager->runSql($sql)) === FALSE) {
            return FALSE;
        }
        setcookie("session_id", 0);
        return TRUE;
    }
    
    /** Baseapis_Sessions::close()
        * close the session
        * @return BOOL TRUE
        */
    public function close()
    {
        return TRUE;
    }
    
    /** Baseapis_Sessions::destroy($id = NULL)
        * Cleanly destroy a specific session
        * @param STRING $id
        */
    public function destroy($id = NULL)
    {
        if (is_string($id) === FALSE) {
            $id = NULL;
        }
        if ($id === NULL) {
            $id = $this->makeHashId();
        }
        $manager = $this->getManager();
        $where = (string)$manager->quoteString("`sessions`.`session_id`=?", $id);
        $sql = (string)"DELETE FROM `sessions` WHERE ".$where;
        if (($manager->runSql($sql)) === FALSE) {
            return FALSE;
        }
        setcookie("session_id", 0);
        return TRUE;
    }
    
    /** Baseapis_Sessions::makeHashId($regenerate = FALSE)
        * Make a session hashed id
        * @param bool $regenerate do you wish to regenerate this unique id?
        * @return string $hash
        */
    public function makeHashId($regenerate = FALSE)
    {
        if (($regenerate === FALSE) && isset($_COOKIE["session_id"]) && $_COOKIE["session_id"]) {
            return $_COOKIE["session_id"];
        }
        $hash = (string)"";
        $alnum = (string)"abcdefghijlkmnopqrstuvwxyz";
        $alnum .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $alnum .= "0123456789";
        $alnum .= "~!@#$%^&*";
        for ($i=0; $i<20; $i++) {$hash .= $alnum[mt_rand(0, strlen($alnum)-1)];}
        $hash = hash("sha512", $hash);
        setcookie("session_id", $hash);
        return $hash;
    }
    
    //: Magic
    /** Baseapis_Sessions::__construct()
        * Class constructor
        */
    public function __construct()
    {
        session_set_save_handler(
            array($this, "open"),
            array($this, "close"),
            array($this, "read"),
            array($this, "write"),
            array($this, "destroy"),
            array($this, "clean")
        );
    }
    //: End
    
    /** Baseapis_Sessions::open($path = NULL, $name = NULL)
        * open a new session
        * @param STRING $path unused (here only for completeness)
        * @param STRING $name unused (here only for completeness)
        */
    public function open($path = NULL, $name = NULL)
    {
        if (is_string($path) === FALSE) {
            $path = NULL;
        }
        if (is_string($name) === FALSE) {
            $name = NULL;
        }
        $manager = $this->getManager();
        if ($manager->getColumns()) {
            return TRUE;
        }
        return FALSE;
    }
    
    /** Baseapis_Sessions::read($id = NULL)
        * read from a given session id
        */
    public function read($id = NULL)
    {
        if (is_string($id) === FALSE) {
            $id = NULL;
        }
        if ($id === NULL) {
            $id = $this->makeHashId();
        }
        $manager = $this->getManager();
        $manager->setWhere(
            $manager->quoteString("`sessions`.`session_id`=?", $id)
        );
        if (($row = $manager->selectSingle()) === FALSE) {
            return "";
        }
        return isset($row["data"])?$row["data"]:"";
    }
    
    public function regenerateId($id = NULL)
    {
        if (is_string($id) === FALSE) {
            $id = NULL;
        }
        if ($id === NULL) {
            $id = $this->makeHashId();
        }
        $manager = $this->getManager();
        $manager->setWhere(
            $manager->quoteString("`sessions`.`session_id`=?", $id)
        );
        $data = (array)array(
            "session_id"=>$this->makeHashId(TRUE)
        );
        if (($manager->update($data)) === FALSE) {
            setcookie("session_id", $id);
            return FALSE;
        }
        return TRUE;
    }
    
    /** Baseapis_Sessions::write($id = NULL,$data)
        * write data to a given session
        */
    public function write($id = NULL, $data)
    {
        if (is_string($id) === FALSE) {
            $id = NULL;
        }
        if ($id === NULL) {
            $id = $this->makeHashId();
        }
        $manager = $this->getManager();
        $manager->setWhere(
            $manager->quoteString("`sessions`.`session_id`=?", $id)
        );
        $sql = (string)"REPLACE INTO `sessions` (`session_id`, `access`, `data`, `ip_address`) VALUES (";
        $sql .= $manager->quoteString("?,", $id);
        $sql .= $manager->quoteString("?,", time());
        $sql .= $manager->quoteString("?,", (is_array($data) ? serialize($data) : $data));
        $sql .= $manager->quoteString("?", $_SERVER["REMOTE_ADDR"]);
        $sql .= ")";
        if (($manager->runSql($sql)) === FALSE) {
            return FALSE;
        }
        return TRUE;
    }
}
