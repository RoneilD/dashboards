<?php
/** CLASS::Scaffold_Forms_SearchUser_profiles
  * @author Feighen Oosterbroek
  * @author feighen@manlinegroup.com
  * @copyright 2010 onwards Manline Group (Pty) Ltd
*/
class Scaffold_Forms_SearchUser_profiles
{
  //: Variables
  protected $_html;
  
  //: Public functions
  //: Accessors
  public function getHtml()
  {
          if (!$this->_html) {
                  $this->setHtml();
          }
          return $this->_html;
  }
  
  public function setHtml($html = null)
  {
          if ($html === null)  {
                  $departments = new TableManager("m3_departments");
                  $themes = new TableManager("themes");
                  $list = $departments->selectMultiple();
                  $list2 = $themes->selectMultiple();
                  $html = (string)"<div class=\"standard content1\" style=\"margin-bottom:10px;width:500px;\">";
                  $html .= "<div onclick=\"toggle('scaffoldSearchForm');var text = (this.firstChild.nodeValue.match(/Display*/) ? 'Hide Search Form' : 'Display Search Form');clearAllElements(this);this.appendChild(document.createTextNode(text));\" style=\"cursor:pointer;text-align:center;\">";
                  $html .= "Display Search Form";
                  $html .= "</div>";
                  $html .= "<form id=\"scaffoldSearchForm\" method=\"POST\" style=\"display:none;\">";
                  $html .= "<label for=\"staffno\">Staff No.</label>";
                  $html .= "<input id=\"staffno\" name=\"staffno\" type=\"text\" value=\"".($_POST ? $_POST["staffno"] : "")."\" /><br />".PHP_EOL;
                  $html .= "<label for=\"firstname\">First Name</label>";
                  $html .= "<input id=\"firstname\" name=\"firstname\" type=\"text\" value=\"".($_POST ? $_POST["firstname"] : "")."\" /><br />".PHP_EOL;
                  $html .= "<label for=\"lastname\">Last Name</label>";
                  $html .= "<input id=\"lastname\" name=\"lastname\" type=\"text\" value=\"".($_POST ? $_POST["lastname"] : "")."\" /><br />".PHP_EOL;
                  $html .= "<label for=\"department_id\">Department</label>";
                  $html .= "<select id=\"department_id\" name=\"department_id\">";
                  $html .= "<option value=\"0\">Please select..</option>";
                  foreach ($list as $val) {
                          $html .= "<option".($_POST && $val["id"] == $_POST["department_id"] ? " selected=\"selected\"" : "")." value=\"".$val["id"]."\">".$val["name"]."</option>";
                  }
                  $html .= "</select><br />";
                  $html .= "<label for=\"jobtitle\">Position</label>";
                  $html .= "<input id=\"jobtitle\" name=\"jobtitle\" type=\"text\" value=\"".($_POST ? $_POST["jobtitle"] : "")."\" /><br />".PHP_EOL;
                  $html .= "<label for=\"location\">Location</label>";
                  $html .= "<input id=\"location\" name=\"location\" type=\"text\" value=\"".($_POST ? $_POST["location"] : "")."\" /><br />".PHP_EOL;
                  $html .= "<label for=\"theme_id\">Theme</label>";
                  $html .= "<select id=\"theme_id\" name=\"theme_id\">";
                  $html .= "<option value=\"0\">Please select..</option>";
                  foreach ($list2 as $val) {
                          $html .= "<option".($_POST && $val["id"] == $_POST["theme_id"] ? " selected=\"selected\"" : "")." value=\"".$val["id"]."\">".$val["name"]."</option>";
                  }
                  $html .= "</select><br />";
                  $html .= "<label for=\"deleted\">Include deleted?</label>";
                  $html .= "<input".($_POST && $_POST["deleted"] ? " checked=\"checked\"" : "")." id=\"deleted\" name=\"deleted\" type=\"checkbox\" value=\"1\" />".PHP_EOL;
                  $html .= "</form>";
                  $html .= "</div>";
          }
          $this->_html = $html;
  }
  //: End
  
  //: Magic
  /** Scaffold_Forms_SearchUser_profiles::__construct()
    * Class constructor
  */
  public function __construct()
  {
    
  }
  
  /** Scaffold_Forms_SearchUser_profiles::__destruct()
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
