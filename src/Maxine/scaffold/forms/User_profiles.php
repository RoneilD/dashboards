<?php
/** CLASS::Scaffold_Forms_User_profiles
  * @author Feighen Oosterbroek
  * @author feighen@manlinegroup.com
  * @copyright 2010 onwards Manline Group (Pty) Ltd
  */
class Scaffold_Forms_User_profiles
{
  //: Variables
  protected $_html;
  protected $_record = array();
  
  //: Public functions
  //: Accessors
  /** Scaffold_Forms_User_profiles::getHtml()
    * @return string $this->_html HTML form
    */
  public function getHtml()
  {
    if (!$this->_html) {$this->setHtml();}
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
  public function setHtml($html = NULL)
  {
    ## Preparation
    $record = $this->getRecord();
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
      9=>array("name"=>"Germiston"),
      10=>array("name"=>"Cape Town"),
      11=>array("name"=>"Wilmar Depot"),
      12=>array("name"=>"Sasolburg"),
      13=>array("name"=>"Boksburg"),
      14=>array("name"=>"Port Elizabeth"),
      15=>array("name"=>"Delmas"),
      16=>array("name"=>"Slurry"),
      17=>array("name"=>"Hercules"),
      18=>array("name"=>"Heriotdale")
    );
    ## End
    if (is_string($html) === FALSE) {$html = NULL;}
    if ($html === NULL) {
      $html = (string)"<style type=\"text/css\">";
      $html .= "#buttons{padding:5px 30px;text-align:right;}";
      $html .= "#content{padding:10px 15px;text-align:left;}";
      $html .= "#tabs{background-color:#ebf0ea;}";
      $html .= "#tabImages{margin:0px auto;}";
      $html .= "#userProfile{background-color:#bccdba;font:13px/1.22 Arial,Helvetica,sans-serif;margin:0px;padding:0px;width:100%;}";
      $html .= ".lbl{color:#000;margin:2px 0px 19px;padding:0px;width:auto;}";
      $html .= ".lblcb{color:#000;margin-bottom:15px;margin-top:0px;padding:0px;width:auto;}";
      $html .= ".pc{display:inline-block;float:left;margin-right:15px;text-align:left;width:auto;}";
      $html .= ".tablImages{cursor:pointer;height:39px;padding:10px 15px;width:44px;}";
      $html .= ".tablImages:first-child{background-color:#FFF;}";
      $html .= "input,select,textarea{background-color:#FFF;border:1px solid #999;margin:0px 0px 15px;padding:0px;width:100%;}";
      $html .= "input[type=checkbox],input[type=radio]{width:auto;}";
      $html .= "textarea{height:54px;}";
      $html .= "</style>".PHP_EOL;
      $html .= "<form enctype=\"multipart/form-data\" id=\"userProfile\" method=\"POST\">";
      if (isset($user) && isset($user["personid"])) {
        $html .= "<input name=\"personid\" type=\"hidden\" value=\"".$user["personid"]."\" />";
      }
      $html .= "<div id=\"tabs\"><span id=\"tabImages\">";
      ## Mouse Events
      $mouse_events = (string)"onclick=\"up.mouse(this, event);\" onmouseout=\"up.mouse(this, event);\" onmouseover=\"up.mouse(this, event);\"";
      $html .= "<img alt=\"profile\" class=\"tablImages\" ".$mouse_events." src=\"".BASE."images/new/icon-profile.png\" />";
      $html .= "<img alt=\"communication\" class=\"tablImages\" ".$mouse_events." src=\"".BASE."images/new/icon-communication.png\" />";
      $html .= "<img alt=\"login\" class=\"tablImages\" ".$mouse_events." src=\"".BASE."images/new/icon-login.png\" />";
      if (isset($_SESSION["isit"]) && $_SESSION["isit"]) {
        $html .= "<img alt=\"flags\" class=\"tablImages\" ".$mouse_events." src=\"".BASE."images/new/icon-flags.png\" />";
        $html .= "<img alt=\"accessgroups\" class=\"tablImages\" ".$mouse_events." src=\"".BASE."images/new/icon-accessgroups.png\" />";
      }
      $html .= "</span></div>";
      $html .= "<div id=\"content\">";
      ## Profile
      $html .= "<div id=\"profile\">";
      $html .= "<span class=\"pc\">"; ## labels
      $html .= "<label class=\"lbl\" for=\"staffno\">Staff Number</label><br />";
      $html .= "<label class=\"lbl\" for=\"firstname\">First name</label><br />";
      $html .= "<label class=\"lbl\" for=\"lastname\">Last name</label><br />";
      $html .= "<label class=\"lbl\" for=\"jobtitle\">Job Title</label><br />";
      $html .= "<label class=\"lbl\" for=\"department_id\" style=\"margin-bottom:21px;\">Department</label><br />";
      $html .= "<label class=\"lbl\" for=\"location\">Location</label><br />";
      $html .= "<label class=\"lbl\" for=\"day\" style=\"margin-bottom:21px;\">Birthday</label><br />";
      if (isset($_SESSION["isit"]) && $_SESSION["isit"]) {
        $html .= "<label class=\"lbl\" for=\"image_file\">Image</label><br />".PHP_EOL;
      }
      $html .= "<label class=\"lbl\" for=\"fortune\" title=\"Would you like a fortune cookie to be displayed on each page?\">Fortune</label><br />";
      $html .= "<label class=\"lbl\" for=\"theme_id\">Theme</label><br />";
      $html .= "</span>";
      $html .= "<span class=\"pc\" style=\"margin-right:30px;text-align:left;width:32%;\">"; ## inputs
      $html .= "<input id=\"staffno\" name=\"staffno\" ".(isset($record) && isset($record["staffno"]) ? "readonly=\"readonly\" " : "")." type=\"text\" value=\"".(isset($record) && isset($record["staffno"]) ? $record["staffno"] : "")."\">";
      $html .= "<input id=\"firstname\" name=\"firstname\" type=\"text\" value=\"".(isset($record) && isset($record["firstname"]) ? $record["firstname"] : "")."\">";
      $html .= "<input id=\"lastname\" name=\"lastname\" type=\"text\" value=\"".(isset($record) && isset($record["lastname"]) ? $record["lastname"] : "")."\">";
      $html .= "<input id=\"jobtitle\" name=\"jobtitle\" type=\"text\" value=\"".(isset($record) && isset($record["jobtitle"]) ? $record["jobtitle"] : "")."\" />";
      $html .= "<select id=\"department_id\" name=\"department_id\">";
		  $html .= "<option value=\"0\">Please select...</options>";
		  foreach ($departments as $dept) {
		    $html .= "<option value=\"".$dept["id"]."\"".(isset($record) && isset($record["department_id"]) && $record["department_id"] == $dept["id"] ? " selected=\"selected\"" : "").">".$dept["name"]."</option>";
		  }
		  $html .= "</select>";
		  $html .= "<select id=\"location\" name=\"location\">";
		  foreach ($locations as $location) {
		    $html .= "<option ".(isset($record) && isset($record["location"]) && $record["location"] == $location["name"] ? "selected=\"selected\" " : "")."value=\"".$location["name"]."\">".$location["name"]."</option>";
		  }
		  $html .= "</select>";
		  $html .= "<select id=\"day\" name=\"day\" style=\"float:left;margin-bottom:15px;margin-right:15px;width:60px;\">";
		  $html .= "<option value=\"0\">Day</option>";
		  for ($i=1; $i<32; $i++) {
		    $html .= "<option ".(isset($record) && isset($record["birthday"]) && date("d", $record["birthday"]) == $i ? "selected=\"selected\" " : "")."value=\"".(strlen($i) == 1 ? "0".$i : $i)."\">".(strlen($i) == 1 ? "0".$i : $i)."</option>";
		  }
		  $html .= "</select>";
		  $html .= "<select id=\"month\" name=\"month\" style=\"float:left;width:120px;\">";
		  $html .= "<option value=\"0\">Month</option>";
		  for ($i=1; $i<13; $i++) {
		    $html .= "<option ".(isset($record) && isset($record["birthday"]) && date("F", $record["birthday"]) == date("F", mktime(0,0,0,$i, 1, 2000)) ? "selected=\"selected\" " : "")."value=\"".date("m", mktime(0,0,0,$i, 1, 2000))."\">".date("F", mktime(0,0,0,$i, 1, 2000))."</option>";
		  }
		  $html .= "</select>";
		  if (isset($_SESSION["isit"]) && $_SESSION["isit"]) {
		    $html .= "<input type=\"file\" id=\"image_file\" name=\"image\" size=\"15\" />";
		  }
		  $html .= "<input".(isset($record) && isset($record["fortune"]) && $record["fortune"] ? " checked=\"checked\"" : "")." id=\"fortune\" name=\"fortune\" type=\"checkbox\" value=\"1\" /><br class=\"clear\" />";
		  $html .= "<input id=\"theme_id\" name=\"theme_id\" type=\"hidden\" value=\"".(isset($record) && isset($record["theme_id"]) ? $record["theme_id"] : "")."\"  />";
		  $i = (int)0;
		  $html .= "<div>";
		  foreach ((is_array($themes) ? $themes : array()) as $theme) {
		    $html .= "<img alt=\"theme\" onclick=\"up.resetBorders(this);document.getElementById('theme_id').value='".$theme["id"]."';\" src=\"".BASE."images/new/themes/".$theme["background-image"]."\" style=\"border:1px solid #".(isset($record) && isset($record["theme_id"]) && ($record["theme_id"] === $theme["id"]) ? "F00" : "000").";cursor:pointer;height:30px;margin:5px;width:30px;\" />";
		    $i++;
		    if ($i === 3) {$i=0;$html .= "<br class=\"clear\" />";}
		  }
		  $html .= "</div><br class=\"clear\" >";
      $html .= "</span>";
      $html .= "<span class=\"pc\">"; ## labels
      $html .= "<label class=\"lbl\" for=\"family\" style=\"margin-bottom:56px;\">Family</label><br class=\"clear\" />";
      $html .= "<label class=\"lbl\" for=\"goals\" style=\"margin-bottom:56px;\">Goals</label><br class=\"clear\" />";
      $html .= "<label class=\"lbl\" for=\"interests\" style=\"margin-bottom:56px;\">Interests</label><br class=\"clear\" />";
      $html .= "<label class=\"lbl\" for=\"aspirations\" style=\"margin-bottom:56px;\">Aspirations</label><br class=\"clear\" />";
      $html .= "<label class=\"lbl\" for=\"quote\" style=\"margin-bottom:56px;\">Quote</label><br class=\"clear\" />";
      $html .= "</span>";
      $html .= "<span class=\"pc\" style=\"width:32%;\">"; ## inputs
      $html .= "<textarea id=\"family\" name=\"family\">".($record ? $record["family"] : "")."</textarea><br class=\"clear\" />";
      $html .= "<textarea id=\"goals\" name=\"goals\">".($record ? $record["goals"] : "")."</textarea><br />";
      $html .= "<textarea id=\"interests\" name=\"interests\">".($record ? $record["interests"] : "")."</textarea><br />";
      $html .= "<textarea id=\"aspirations\" name=\"aspirations\">".($record ? $record["aspirations"] : "")."</textarea><br />";
      $html .= "<textarea id=\"quote\" name=\"quote\">".($record ? $record["quote"] : "")."</textarea><br />";
      $html .= "</span>";
      $html .= "<br class=\"clear\" /></div>";
      ## Communication
      $html .= "<div id=\"communication\" style=\"display:none;\">";
      $html .= "<span class=\"pc\">"; ## labels
      $html .= "<label class=\"lbl\" for=\"extension\">Office extension</label><br />";
      $html .= "<label class=\"lbl\" for=\"cell\">Cellphone</label>";
      $html .= "</span>";
      $html .= "<span class=\"pc\" style=\"margin-right:30px;width:30%;\">"; ## inputs
      $html .= "<input id=\"extension\" name=\"extension\" type=\"text\" value=\"".(isset($user) && isset($user["extension"]) ? $user["extension"] : "")."\" /><br style=\"clear:both;\" />";
      $html .= "<input id=\"cell\" name=\"cell\" type=\"text\" value=\"".(isset($user) && isset($user["cell"]) ? $user["cell"] : "")."\" /><br />";
      $html .= "</span>";
      $html .= "<span class=\"pc\">"; ## labels
      $html .= "<label class=\"lbl\" for=\"email\">Email</label>";
      $html .= "</span>";
      $html .= "<span class=\"pc\" style=\"width:37.4%;\">"; ## inputs
      $html .= "<input id=\"email\" name=\"email\" type=\"email\" value=\"".$user["email"]."\" /><br />";
      $html .= "</span>";
      $html .= "<br class=\"clear\" /></div>";
      ## Login
      $html .= "<div id=\"login\" style=\"display:none;\">";
      $html .= "<span class=\"pc\">"; ## labels
      $html .= "<label class=\"lbl\" for=\"username\">Username</label>";
      $html .= "</span>";
      $html .= "<span class=\"pc\" style=\"margin-right:30px;width:32%;\">"; ## inputs
      $html .= "<input id=\"username\" name=\"username\" type=\"text\" value=\"".(isset($user) && isset($user["username"]) ? $user["username"] : "")."\" />";
      $html .= "</span>";
      $html .= "<span class=\"pc\">"; ## labels
      $html .= "<label class=\"lbl\" for=\"password\">Password</label><br />";
      $html .= "<label class=\"lbl\" for=\"con_pass\">Confirm password</label>";
      $html .= "</span>";
      $html .= "<span class=\"pc\" style=\"width:29%;\">"; ## inputs
      $html .= "<input id=\"password\" name=\"password\" type=\"password\" /><br />";
      $html .= "<input id=\"con_pass\" onblur=\"formManipulators.checkPasswordIdentical('password', 'con_pass');\" type=\"password\" />";
      $html .= "</span>";
      $html .= "<br class=\"clear\" /></div>";
      if (isset($_SESSION["isit"]) && $_SESSION["isit"]) {
        ## Flags
        $cols = (array)array();
        $i = (int)0;
        foreach ($metaData as $col=>$data) {
          if ($col == "isadmin") {continue;}
          if (in_array($data["Type"], array("int(1)", "tinyint(1)"))) {
            $cols[$i][$col] = $data;
            $i++;
            if ($i===3) {$i=0;}
          }
        }
        $html .= "<div id=\"flags\" style=\"display:none;\">";
        $html .= "<span class=\"pc\">"; ## inputs
        foreach ((is_array($cols[0]) ? $cols[0] : array())  as $col=>$data) {
          $html .= "<input".(isset($user) && isset($user[$col]) && $user[$col] ? " checked=\"checked\"" : "")." id=\"".$col."\" name=\"".$col."\" style=\"float:left;margin-bottom:15px;\" type=\"checkbox\" value=\"1\" /><br class=\"clear\" />";
        }
        $html .= "</span>";
        $html .= "<span class=\"pc\" style=\"width:25%;\">"; ## labels
        foreach ((is_array($cols[0]) ? $cols[0] : array())  as $col=>$data) {
          $html .= "<label class=\"lblcb\" for=\"".$col."\">".$col."</label><br class=\"clear\" />";
        }
        $html .= "</span>";
        $html .= "<span class=\"pc\">"; ## inputs
        foreach ((is_array($cols[1]) ? $cols[1] : array())  as $col=>$data) {
          $html .= "<input".(isset($user) && isset($user[$col]) && $user[$col] ? " checked=\"checked\"" : "")." id=\"".$col."\" name=\"".$col."\" style=\"float:left;margin-bottom:15px;\" type=\"checkbox\" value=\"1\" /><br class=\"clear\" />";
        }
        $html .= "</span>";
        $html .= "<span class=\"pc\" style=\"width:25%;\">"; ## labels
        foreach ((is_array($cols[1]) ? $cols[1] : array())  as $col=>$data) {
          $html .= "<label class=\"lblcb\" for=\"".$col."\">".$col."</label><br class=\"clear\" />";
        }
        $html .= "</span>";
        $html .= "<span class=\"pc\">"; ## inputs
        foreach ((is_array($cols[2]) ? $cols[2] : array())  as $col=>$data) {
          $html .= "<input".(isset($user) && isset($user[$col]) && $user[$col] ? " checked=\"checked\"" : "")." id=\"".$col."\" name=\"".$col."\" style=\"float:left;margin-bottom:15px;\" type=\"checkbox\" value=\"1\" /><br class=\"clear\" />";
        }
        $html .= "</span>";
        $html .= "<span class=\"pc\" style=\"width:25%;\">"; ## labels
        foreach ((is_array($cols[2]) ? $cols[2] : array())  as $col=>$data) {
          $html .= "<label class=\"lblcb\" for=\"".$col."\">".$col."</label><br class=\"clear\" />";
        }
        $html .= "</span>";
        $html .= "<br class=\"clear\" /></div>";
        ## Access groups
        $cols = (array)array();
        $i = (int)0;
        foreach ($rights_groups as $grp) {
          $cols[$i][] = $grp;
          $i++;
          if ($i===3) {$i=0;}
        }
        $html .= "<div id=\"accessgroups\" style=\"display:none;\">";
        $html .= "<span class=\"pc\">"; ## inputs
        foreach ((is_array($cols[0]) ? $cols[0] : array())  as $key=>$data) {
          $checked = (bool)false;
          if (isset($user["children"]["rights_users"]) && is_array($user["children"]["rights_users"])) {
            foreach ($user["children"]["rights_users"] as $right) {
              if ($right['groupid'] == $data["id"]) {
                $checked = true;
                break;
              }
            }
          }
          $html .= "<input".($checked ? " checked=\"checked\"" : "")." id=\"".$data["id"]."\" name=\"group[".$data["id"]."]\" style=\"float:left;margin-bottom:15px;\" type=\"checkbox\" value=\"".$data["id"]."\" /><br class=\"clear\" />";
        }
        $html .= "</span>";
        $html .= "<span class=\"pc\" style=\"width:25%;\">"; ## labels
        foreach ((is_array($cols[0]) ? $cols[0] : array())  as $key=>$data) {
          $html .= "<label class=\"lblcb\" for=\"".$data["id"]."\" title=\"".$data["description"]."\">".$data["name"]."</label><br class=\"clear\" />";
        }
        $html .= "</span>";
        $html .= "<span class=\"pc\">"; ## inputs
        foreach ((is_array($cols[1]) ? $cols[1] : array())  as $key=>$data) {
          $checked = (bool)false;
          if (isset($user["children"]["rights_users"]) && is_array($user["children"]["rights_users"])) {
            foreach ($user["children"]["rights_users"] as $right) {
              if ($right['groupid'] == $data["id"]) {
                $checked = true;
                break;
              }
            }
          }
          $html .= "<input".($checked ? " checked=\"checked\"" : "")." id=\"".$data["id"]."\" name=\"group[".$data["id"]."]\" style=\"float:left;margin-bottom:15px;\" type=\"checkbox\" value=\"".$data["id"]."\" /><br class=\"clear\" />";
        }
        $html .= "</span>";
        $html .= "<span class=\"pc\" style=\"width:25%;\">"; ## labels
        foreach ((is_array($cols[1]) ? $cols[1] : array())  as $key=>$data) {
          $html .= "<label class=\"lblcb\" for=\"".$data["id"]."\" title=\"".$data["description"]."\">".$data["name"]."</label><br class=\"clear\" />";
        }
        $html .= "</span>";
        $html .= "<span class=\"pc\">"; ## inputs
        foreach ((is_array($cols[2]) ? $cols[2] : array())  as $key=>$data) {
          $checked = (bool)false;
          if (isset($user["children"]["rights_users"]) && is_array($user["children"]["rights_users"])) {
            foreach ($user["children"]["rights_users"] as $right) {
              if ($right['groupid'] == $data["id"]) {
                $checked = true;
                break;
              }
            }
          }
          $html .= "<input".($checked ? " checked=\"checked\"" : "")." id=\"".$data["id"]."\" name=\"group[".$data["id"]."]\" style=\"float:left;margin-bottom:15px;\" type=\"checkbox\" value=\"".$data["id"]."\" /><br class=\"clear\" />";
        }
        $html .= "</span>";
        $html .= "<span class=\"pc\" style=\"width:25%;\">"; ## labels
        foreach ((is_array($cols[2]) ? $cols[2] : array())  as $key=>$data) {
          $html .= "<label class=\"lblcb\" for=\"".$data["id"]."\" title=\"".$data["description"]."\">".$data["name"]."</label><br class=\"clear\" />";
        }
        $html .= "</span>";
        $html .= "<br class=\"clear\" /></div>";
      }
      $html .= "<br class=\"clear\" /></div>";
      $html .= "<div id=\"buttons\">";
      $html .= "<span class=\"button\" onclick=\"document.getElementById('userProfile').submit();\" style=\"height:26px;padding-top:8px;width:114px;\">Save</span>";
      $html .= "<span class=\"button\" onclick=\"document.location='/';\" style=\"height:26px;padding-top:8px;width:114px;\">Cancel</span>";
      $html .= "</div>";
      $html .= "</form>".PHP_EOL;
      $html .= "<script type=\"text/javascript\">";
      $html .= "var up={};";
      $html .= "up.mouse = function(e, ev) {";
      $html .= "var i,p1=e.parentNode,p2=document.getElementById('content');";
      $html .= "if (!ev) {var ev = window.event;} if (!ev) {return false;}";
      $html .= "switch (ev.type) {";
      $html .= "case 'click': ";
      $html .= "for (i=0;i<p1.childNodes.length;i++){p1.childNodes[i].style.backgroundColor='inherit';if (p1.childNodes[i].getAttribute('alt') == e.getAttribute('alt')) {e.setAttribute('obg', '#FFF');e.style.backgroundColor='#FFF';}} ";
      $html .= "for (i=0;i<p2.childNodes.length;i++) {p2.childNodes[i].style.display='none';}";
      $html .= "document.getElementById(e.getAttribute('alt')).style.display='';";
      $html .= "break;";
      $html .= "case 'mouseout': e.style.backgroundColor=e.getAttribute('obg'); break;";
      $html .= "case 'mouseover': e.setAttribute('obg', e.style.backgroundColor);e.style.backgroundColor='#FFF'; break;";
      $html .= "}";
      $html .= "};";
      $html .= "up.resetBorders = function(e){";
      $html .= "var i,p=e.parentNode;";
      $html .= "for(i=0;i<p.childNodes.length;i++){p.childNodes[i].style.borderColor='#000';}";
      $html .= "e.style.borderColor='#F00';";
      $html .= "};";
      $html .= "</script>".PHP_EOL;
      //$html .= "";
    }
    $this->_html = $html;
  }
  
  /** Scaffold_Forms_User_profiles::setRecord(array $record)
    * @param array $record which record are we updating?
    */
  public function setRecord(array $record = NULL)
  {
    $this->_record = $record;
  }
  //: End
  
  //: Magic
  /** Scaffold_Forms_User_profiles::__construct()
    * Class constructor
    */
  public function __construct(){}
  
  /** Scaffold_Forms_User_profiles::__destruct()
    * Class destructor
    * Allow for garbage collection
    */
  public function __destruct()
  {
    unset($this);
  }
  //: End
  
  //: Private functions
}
