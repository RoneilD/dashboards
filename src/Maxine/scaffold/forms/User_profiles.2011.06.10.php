<?php
/** CLASS::Scaffold_Forms_User_profiles
  * @author feighen
  * @author feighen
  * @created 06 Dec 2010 11:59:00 AM
  */
class Scaffold_Forms_User_profiles {
	//: Variables
	protected $_html;
	protected $_record;
	
	//: Public functions
	//: Getters and Setters
	/** Scaffold_Forms_User_profiles::getHtml()
	  * @return string $this->_html HTML form
	  */
	public function getHtml()
	{
		if (!$this->_html) {
			self::setHtml();
		}
		return $this->_html;
	}
	
	/** Scaffold_Forms_User_profiles::getRecord()
	  * @return array $this->_record which record are we updating?
	  */
	public function getRecord()
	{
	  return $this->_record;
	}
	
	/** Scaffold_Forms_User_profiles::setHtml($html = null)
	  * @param string $html HTML form definition
	  */
	public function setHtml($html = null)
	{
		if ($html === null) {
		  ## Preparation
		  $record = self::getRecord();
		  if (is_object("TableManager") === false) {
		    include_once(BASE.DIRECTORY_SEPARATOR."basefunctions".DIRECTORY_SEPARATOR."baseapis".DIRECTORY_SEPARATOR."TableManager.php");
		  }
		  $tableManager = new TableManager("m3_departments");
		  $departments = $tableManager->selectMultiple();
		  $sql = (string)"SELECT * FROM `themes` WHERE (ISNULL(`deleted`) OR `deleted`=0)";
		  $themes = $tableManager->runSql($sql);
		  $users = new TableManager("users");
      $metaData = $users->getMetaData();
      if ($record) {
        $users->setChildRecords(true);
        $users->setChildTables(array(0=>array("rights_users"=>"rights_users", "where"=>"`rights_users`.`userid`=?")));
        $users->setWhere(
          $users->quoteString("`users`.`user_profiles_id`=?", (int)$record["id"])
        );
        $user = $users->selectSingle();
      }
      $sql = (string)"SELECT * FROM `rights_groups` WHERE 1=1;";
      $rights_groups = $users->runSql($sql);
      ## End
      
		  $html = (string)"<form enctype=\"multipart/form-data\" method=\"POST\">".PHP_EOL;
		  $html .= "<div style=\"width:800px;\">".PHP_EOL;
		  $html .= "<div style=\"text-align:center;\">".PHP_EOL;
		  $html .= "<input type=\"submit\" value=\"Save\" style=\"background-color:transparent;background-image:url(".BASE."images/new/button.png);border:none;height:34px;width:114px;\" />".PHP_EOL;
		  $html .= "</div>".PHP_EOL;
		  ## Profile details
		  $html .= "<fieldset style=\"width:730px;\">";
		  $html .= "<legend>Profile details</legend>";
		  $html .= "<div style=\"border:none;float:left;width:49%;\">";
		  $html .= "<label for=\"staffno\" style=\"color:#000;width:150px;\">Staff Number</label>".PHP_EOL;
		  $html .= "<input id=\"staffno\" name=\"staffno\" ".($record["staffno"] ? "readonly=\"readonly\" " : "")."type=\"text\" value=\"".($record["staffno"] ? $record["staffno"] : "")."\"><br style=\"clear:both;\" />".PHP_EOL;
		  $html .= "<label for=\"firstname\" style=\"color:#000;width:150px;\">First name</label>".PHP_EOL;
		  $html .= "<input id=\"firstname\" name=\"firstname\" type=\"text\" value=\"".($record["firstname"] ? $record["firstname"] : "")."\"><br />".PHP_EOL;
		  $html .= "<label for=\"day\" style=\"color:#000;width:150px;\">Birthday</label>".PHP_EOL;
		  $html .= "<select id=\"day\" name=\"day\" style=\"float:left;margin-bottom:5px;width:50px;\">";
		  $html .= "<option value=\"0\">Day</option>".PHP_EOL;
		  for ($i=1; $i<32; $i++) {
		    $html .= "<option ".($record["birthday"] && date("d", $record["birthday"]) == $i ? "selected=\"selected\" " : "")."value=\"".(strlen($i) == 1 ? "0".$i : $i)."\">".(strlen($i) == 1 ? "0".$i : $i)."</option>";
		  }
		  $html .= "</select>";
		  $html .= "<select id=\"month\" name=\"month\" style=\"float:left;width:120px;\">";
		  $html .= "<option value=\"0\">Month</option>".PHP_EOL;
		  for ($i=1; $i<13; $i++) {
		    $html .= "<option ".($record["birthday"] && date("F", $record["birthday"]) == date("F", mktime(0,0,0,$i, 1, 2000)) ? "selected=\"selected\" " : "")."value=\"".date("m", mktime(0,0,0,$i, 1, 2000))."\">".date("F", mktime(0,0,0,$i, 1, 2000))."</option>";
		  }
		  $html .= "</select><br style=\"clear:both;\" />";
		  $html .= "<label for=\"department_id\" style=\"color:#000;width:150px\">Department</label>".PHP_EOL;
		  $html .= "<select id=\"department_id\" name=\"department_id\">".PHP_EOL;
		  $html .= "<option value=\"0\">Please select...</options>".PHP_EOL;
		  foreach ($departments as $dept) {
		    $html .= "<option value=\"".$dept["id"]."\"".($record && $record["department_id"] == $dept["id"] ? " selected=\"selected\"" : "").">".$dept["name"]."</option>".PHP_EOL;
		  }
		  $html .= "</select><br class=\"clear\" />".PHP_EOL;
		  $locations = (array)array(
		    0=>array("name"=>"Head Office"),
		    1=>array("name"=>"Durban"),
		    2=>array("name"=>"Empangeni"),
		    3=>array("name"=>"East London"),
		    4=>array("name"=>"Kokstad"),
		    5=>array("name"=>"Pietermaritzburg"),
		    6=>array("name"=>"Newcastle"),
		    7=>array("name"=>"Vanderbijl Park"),
		    8=>array("name"=>"Isando"),
		    9=>array("name"=>"Germiston")
		  );
		  $html .= "<label for=\"location\" style=\"color:#000;width:150px;\">Location</label>".PHP_EOL;
		  $html .= "<select id=\"location\" name=\"location\" style=\"float:left;width:165px;\">".PHP_EOL;
		  foreach ($locations as $location) {
		    $html .= "<option ".($record["location"] && $record["location"] == $location["name"] ? "selected=\"selected\" " : "")."value=\"".$location["name"]."\">".$location["name"]."</option>".PHP_EOL;
		  }
		  $html .= "</select><br class=\"clear\" />".PHP_EOL;
		  $html .= "<label for=\"interests\" style=\"color:#000;\">Interests</label>".PHP_EOL;
		  $html .= "<textarea id=\"interests\" name=\"interests\">".($record ? $record["interests"] : "")."</textarea><br />".PHP_EOL;
		  $html .= "<label for=\"aspirations\" style=\"color:#000;\">Aspirations</label>".PHP_EOL;
		  $html .= "<textarea id=\"aspirations\" name=\"aspirations\">".($record ? $record["aspirations"] : "")."</textarea><br />".PHP_EOL;
		  $html .= "<label for=\"quote\" style=\"color:#000;\">Quote</label>".PHP_EOL;
		  $html .= "<textarea id=\"quote\" name=\"quote\">".($record ? $record["quote"] : "")."</textarea><br />".PHP_EOL;
		  $html .= "</div>";
		  $html .= "<div style=\"border:none;float:left;width:49%;\">";
		  $html .= "<label for=\"image_file\" style=\"color:#000;width:150px;\">Image</label>".PHP_EOL;
		  $html .= "<input type=\"file\" id=\"image_file\" name=\"image\" /><br />".PHP_EOL;
		  $html .= "<label for=\"lastname\" style=\"color:#000;width:150px;\">Last name</label>".PHP_EOL;
		  $html .= "<input id=\"lastname\" name=\"lastname\" type=\"text\" value=\"".($record["lastname"] ? $record["lastname"] : "")."\"><br class=\"clear\" /><br /><br />".PHP_EOL;
		  $html .= "<label for=\"jobtitle\" style=\"color:#000;width:150px\">Job Title</label>".PHP_EOL;
		  $html .= "<input type=\"text\" id=\"jobtitle\" name=\"jobtitle\" value=\"".($record ? $record["jobtitle"] : "")."\" /><br />".PHP_EOL;
		  $html .= "<label for=\"family\" style=\"color:#000;\">Family</label>".PHP_EOL;
		  $html .= "<textarea id=\"family\" name=\"family\">".($record ? $record["family"] : "")."</textarea><br />".PHP_EOL;
		  $html .= "<label for=\"goals\" style=\"color:#000;\">Goals</label>".PHP_EOL;
		  $html .= "<textarea id=\"goals\" name=\"goals\">".($record ? $record["goals"] : "")."</textarea><br />".PHP_EOL;
		  $html .= "</div>";
		  $html .= "</fieldset>";
		  ## User details
		  $html .= "<fieldset style=\"width:730px;\">";
      $html .= "<legend>User details</legend>";
      $html .= "<div style=\"float:left;width:49%;\">";
      $html .= "<fieldset style=\"border:none;\">";
      $html .= "<legend>Communication</legend>";
      $html .= "<label for=\"extension\" style=\"color:#000;width:150px;\">Office extension</label>";
      $html .= "<input id=\"extension\" name=\"extension\" type=\"text\" value=\"".$user["extension"]."\" /><br />";
      $html .= "<label for=\"cell\" style=\"color:#000;width:150px;\">Cellphone</label>";
      $html .= "<input id=\"cell\" name=\"cell\" type=\"text\" value=\"".$user["cell"]."\" /><br />";
      $html .= "<label for=\"email\" style=\"color:#000;width:150px;\">Email</label>";
      $html .= "<input id=\"email\" name=\"email\" type=\"email\" value=\"".$user["email"]."\" /><br />";
      $html .= "</fieldset>";
      $html .= "<fieldset style=\"border:none;\">";
      $html .= "<legend>Login and Password</legend>";
      if ($user) {
        $html .= "<input id=\"personid\" name=\"personid\" type=\"hidden\" value=\"".$user["personid"]."\" />";
      }
      $html .= "<label for=\"username\" style=\"color:#000;width:150px;\">Username</label>";
      $html .= "<input id=\"username\" name=\"username\"".(isset($user) ? "" : " onblur=\"check.checkUsernameAvailability(this);\"")." type=\"text\" value=\"".$user["username"]."\" /><br />";
      $html .= "<label for=\"pass\" style=\"color:#000;width:150px;\">Password</label>";
      $html .= "<input id=\"pass\" name=\"password\" type=\"password\" /><br />";
      $html .= "<label for=\"conpass\" style=\"color:#000;width:150px;\">Confirm password</label>";
      $html .= "<input id=\"conpass\" name=\"conpass\" type=\"password\" onblur=\"formManipulators.checkPasswordIdentical('pass', 'conpass')\" /><br />";
      $html .= "</fieldset>";
      $html .= "</div>";
      $html .= "<div style=\"float:left;width:49%;\">";
      $html .= "<fieldset style=\"border:none;\">";
      $html .= "<legend>Flags</legend>";
      if ($_SESSION["isit"]) {
        foreach ($metaData as $col=>$data) {
          if ($col == "isadmin") {continue;}
          if (in_array($data["Type"], array("int(1)", "tinyint(1)"))) {
            $html .= "<input".($user && $user[$col] ? " checked=\"checked\"" : "")." id=\"".$col."\" name=\"".$col."\" type=\"checkbox\" value=\"1\" />";
            $html .= "<label for=\"".$col."\" style=\"color:#000;\">".$col."</label><br />";
          }
        }
      } else {
        $html .= "Cannot set access flags. Please see IT dept. to change";
      }
      $html .= "</fieldset>";
      $html .= "<fieldset style=\"border:none;\">";
      $html .= "<legend>Access Groups</legend>";
      if ($_SESSION["isit"]) {
        foreach ($rights_groups as $grp) {
          $checked = (bool)false;
          if (isset($user["children"]["rights_users"]) && is_array($user["children"]["rights_users"])) {
            foreach ($user["children"]["rights_users"] as $right) {
              if ($right['groupid'] == $grp["id"]) {
                $checked = true;
                break;
              }
            }
          }
          $html .= "<input".($checked ? " checked=\"checked\"" : "")." id=\"".$grp["id"]."\" name=\"group[".$grp["id"]."]\" type=\"checkbox\" value=\"".$grp["id"]."\" />";
          $html .= "<label for=\"".$grp["id"]."\" style=\"color:#000;\">".$grp["name"]."</label><br />";
        }
      } else {
        $html .= "Cannot set access flags. Please see IT dept. to change";
      }
      $html .= "</fieldset>";
      $html .= "</div>";
      $html .= "</fieldset>";
		  $html .= "<div style=\"text-align:center;\">".PHP_EOL;
		  $html .= "<input type=\"submit\" value=\"Save\" style=\"background-color:transparent;background-image:url(".BASE."images/new/button.png);border:none;height:34px;width:114px;\" />".PHP_EOL;
		  $html .= "</div>".PHP_EOL;
		  $html .= "</div>".PHP_EOL;
		  $html .= "</form>".PHP_EOL;
		  //$html .= "<iframe id=\"targetFrame\" name=\"targetFrame\"></iframe>".PHP_EOL;
      $html .= "<iframe id=\"targetFrame\" name=\"targetFrame\" style=\"display:none;\"></iframe>".PHP_EOL;
		  $html .= "<script type=\"text/javascript\">".PHP_EOL;
		  $html .= "function selectTheme(elem) {
		  for (var i=0; i<document.getElementById('profileTheme').getElementsByTagName('IMG').length;i++) {
		  document.getElementById('profileTheme').getElementsByTagName('IMG')[i].style.border='none';
		  } 
		  elem.style.border='1px solid #000';
		  }".PHP_EOL;
		  $html .= "</script>".PHP_EOL;
		}
		$this->_html = $html;
	}
	
	/** Scaffold_Forms_User_profiles::setRecord(array $record)
    * @param array $record which record are we updating?
    */
	public function setRecord(array $record)
	{
	  $this->_record = $record;
	}
	//: End
	
	//: Magic
	/** Scaffold_Forms_User_profiles::__constuct()
	  * Class Constructor
	  */
	public function __construct()
	{
		
	}
	
	/** Scaffold_Forms_User_profiles::__destuct()
	  * Allow for Garbage Collection
	  */
	public function __destruct()
	{
		unset($this);
	}
	//: End
	
	//: Private functions
	
}
