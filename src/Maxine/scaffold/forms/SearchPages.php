<?php
/** CLASS::Scaffold_Forms_SearchPages
  * @author Feighen Oosterbroek
  * @author feighen@manlinegroup.com
  * @copyright 2010 onwards Manline Group (Pty) Ltd
*/
class Scaffold_Forms_SearchPages
{
  //: Variables
  protected $_html;
  
  //: Public functions
  //: Accessors
  public function getHtml()
  {
          if (!$this->_html) {$this->setHtml();}
          return $this->_html;
  }
  
  public function setHtml($html = null)
  {
          if ($html === null) {
                  $departments = new TableManager("m3_departments");
                  $list = $departments->selectMultiple();
                  $type = new TableManager("type");
                  $list2 = $type->selectMultiple();
                  $html = (string)"<div class=\"standard content1\" style=\"margin-bottom:10px;width:500px;\">";
                  $html .= "<div onclick=\"toggle('scaffoldSearchForm');var text = (this.firstChild.nodeValue.match(/Display*/) ? 'Hide Search Form' : 'Display Search Form');clearAllElements(this);this.appendChild(document.createTextNode(text));\" style=\"cursor:pointer;text-align:center;\">";
                  $html .= "Display Search Form";
                  $html .= "</div>";
                  $html .= "<form id=\"scaffoldSearchForm\" method=\"POST\" style=\"display:none;\">";
                  $html .= "<label for=\"name\">Name</label>";
                  $html .= "<input id=\"name\" name=\"name\" type=\"text\" value=\"".($_POST ? $_POST["name"] : "")."\" /><br />".PHP_EOL;
                  $html .= "<label for=\"content\">Content like</label>";
                  $html .= "<input id=\"content\" name=\"content\" type=\"text\" value=\"".($_POST ? $_POST["content"] : "")."\" /><br />".PHP_EOL;
                  $html .= "<label for=\"departments_id\">Department</label>";
                  $html .= "<select id=\"departments_id\" name=\"departments_id\">";
                  $html .= "<option value=\"0\">Please select..</option>";
                  foreach ($list as $val) {
                          $html .= "<option".($_POST && $val["id"] == $_POST["departments_id"] ? " selected=\"selected\"" : "")." value=\"".$val["id"]."\">".$val["name"]."</option>";
                  }
                  $html .= "</select><br />";
                  $html .= "<label for=\"type_id\">Type</label>";
                  $html .= "<select id=\"type_id\" name=\"type_id\">";
                  $html .= "<option value=\"0\">Please select..</option>";
                  foreach ($list2 as $val) {
                          $html .= "<option".($_POST && $val["id"] == $_POST["type_id"] ? " selected=\"selected\"" : "")." value=\"".$val["id"]."\">".$val["name"]."</option>";
                  }
                  $html .= "</select><br />";
                  $html .= "<label for=\"deleted\">Include deleted?</label>";
                  $html .= "<input".($_POST && $_POST["deleted"] ? " checked=\"checked\"" : "")." id=\"deleted\" name=\"deleted\" type=\"checkbox\" value=\"1\" />".PHP_EOL;
                  $html .= "</form>";
                  $html .= "</div>".PHP_EOL;
          }
          $this->_html = $html;
  }
  //: End
  
  //: Magic
  /** Scaffold_Forms_SearchPages::__construct()
    * Class constructor
  */
  public function __construct()
  {
          
  }
  
  /** Scaffold_Forms_SearchPages::__destruct()
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
