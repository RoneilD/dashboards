<?php
$base = substr(__DIR__, 0, strrpos(__DIR__, "M"));
defined("DS") || define("DS", DIRECTORY_SEPARATOR);
include_once($base."basefunctions".DS."baseapis".DS."TableManager.php");
/** CLASS::Maxine_Scaffold_Custom_Imports_Birthdays
 * @author feighen
 * @author feighen
 * @created 06 Jan 2011 1:09:26 PM
*/
class Maxine_Scaffold_Custom_Imports_Birthdays {
  //: Variables
	protected $_tableManager;
	
	//: Public functions
	//: Accessors
	public function getTableManager()
	{
		return $this->_tableManager;
	}
	
	public function setTableManager(TableManager $manager = null)
	{
		if ($manager === null) {
			$manager = new TableManager("user_profiles");
		}
		$this->_tableManager = $manager;
	}
	//: End
	
	//: Magic
	public function __construct()
	{
	  $manager = new TableManager("user_profiles");
	  echo("<link href=\"http://".$_SERVER["SERVER_NAME"]."/basefunctions/scripts/manline.css\" media=\"all\" rel=\"stylesheet\" type=\"text/css\" />".PHP_EOL);
		echo("<p class=\"standard\">".PHP_EOL);
		echo "Update current records to be integer based instead of string based<br />".PHP_EOL;
		$list = $manager->selectMultiple();
		foreach ($list as $key=>$val) {
		  if (!$val["birthday"]) {continue;}
		  $manager->setWhere(
		    $manager->quoteString("`user_profiles`.`id`=?", $val["id"])
		  );
		  $data = (array)array();
		  $split = preg_split("/\s/", $val["birthday"]);
		  $data["birthday"] = mktime(0,0,0,date("m", strtotime("01-".$split[1]."-2011")),$split[0],2011);
		  if (($manager->update($data)) === false) {
		    echo "<span style=\"color:#F00;\" title=\"Update failed\">|</span>".PHP_EOL;
		    return false;
		  }
		  echo "<span style=\"color:#FFF;\" title=\"Update successful\">|</span>".PHP_EOL;
		  
		  $manager->setWhere(""); ## TOCTOU Race condition ##
		}
		echo "<br />Update table so that the birthday column is an integer column<br />".PHP_EOL;
		$sql = (string)"alter table user_profiles modify column birthday int(50) not null default '0',add index (`birthday`);";
		if (($manager->runSql($sql)) === false) {
		  echo "<echo style=\"color:#FFF;\">".PHP_EOL;
      print_r($manager->getErrors());
      echo "</pre>".PHP_EOL;
      return false;
		}
		echo "<br />Get list of users<br />".PHP_EOL;
		$sql = (string)"SELECT * FROM `users` WHERE `deleted`='0'";
		if (($list = $manager->runSql($sql)) === false) {
		  echo "<echo style=\"color:#FFF;\">".PHP_EOL;
      print_r($manager->getErrors());
      echo "</pre>".PHP_EOL;
      return false;
		}
		echo "<br />Loop through and get the userdates entry for birthday and update the user_profiles table.<br />".PHP_EOL;
    foreach ($list as $key=>$val) {
      if (!$val["user_profiles_id"]) {continue;}
      $sql = (string)"SELECT * FROM `userdates` WHERE `userid`='".$val["personid"]."'  AND `datetype`='birthday' LIMIT 1";
      if (($row = $manager->runSql($sql)) === false) {
        echo "<echo style=\"color:#FFF;\">".PHP_EOL;
        print_r($manager->getErrors());
        echo "</pre>".PHP_EOL;
        return false;
      }
      $manager->setWhere(
        $manager->quoteString("`user_profiles`.`id`=?", $val["user_profiles_id"])
      );
      $data = (array)array();
      $birthday = date("Y-m-d", $row[0]["date"]);
      $split = preg_split("/\-/", $birthday);
      $data["birthday"] = strtotime("1971-".$split[1]."-".$split[2]);
      if (($manager->update($data)) === false) {
		    echo "<span style=\"color:#F00;\" title=\"Update failed\">|</span>".PHP_EOL;
		    return false;
		  }
		  echo "<span style=\"color:#FFF;\" title=\"Update successful\">|</span>".PHP_EOL;
      $manager->setWhere(""); ## TOCTOU Race condition ##
    }
    echo "<br />Get any records form the user_profiles table where the birthday year isn't 1971<br />".PHP_EOL;
    $manager->setWhere(
      $manager->quoteString("DATE_FORMAT(FROM_UNIXTIME(`user_profiles`.`birthday`), '%Y')!=?", 1971)
    );
    $list = $manager->selectMultiple();
    foreach ($list as $key=>$val) {
      $manager->setWhere(
        $manager->quoteString("`user_profiles`.`id`=?", $val["id"])
      );
      $date = date("Y-m-d", $val["birthday"]);
      $split = preg_split("/\-/", $date);
      $data = (array)array(
        "birthday"=>strtotime("1971-".$split[1]."-".$split[2])
      );
      if (($manager->update($data)) === false) {
        echo "<span style=\"color:#F00;\" title=\"Update failed\">|</span>".PHP_EOL;
		    return false;
      }
      echo "<span style=\"color:#FFF;\" title=\"Update successful\">|</span>".PHP_EOL;
      $manager->setWhere(""); ## TOCTOU Race condition ##
    }
	}
	
	public function __destruct()
	{
	  echo "</p>".PHP_EOL;
	  unset($this);
	}
	//: End
}
new Maxine_Scaffold_Custom_Imports_Birthdays();
