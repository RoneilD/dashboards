<?php
/** CLASS::Scaffold_Forms_Pages
  * @author feighen
  * @author feighen
  * @created 06 Dec 2010 11:59:00 AM
  */
class Scaffold_Forms_Pages {
  //: Variables
  protected $_html;
  protected $_record;
  
  //: Public functions
  //: Getters and Setters
  /** Scaffold_Forms_Pages::getHtml()
    * @return string $this->_html HTML form
    */
  public function getHtml()
  {
    if (!$this->_html) {
      self::setHtml();
    }
    return $this->_html;
  }
  
  /** Scaffold_Forms_Pages::getRecord()
    * @return array $this->_record which record are we updating?
    */
  public function getRecord()
  {
    return $this->_record;
  }
  
  /** Scaffold_Forms_Pages::setHtml($html = null)
    * @param string $html HTML form definition
    */
  public function setHtml($html = null)
  {
    if ($html === null) {
      $record = self::getRecord();
      
      $tableManager = new TableManager("m3_departments");
      $tableManager->setWhere(
        $tableManager->quoteString("`display`=?", 1)
      );
      $departments = $tableManager->selectMultiple();
      
      $tableManager = new TableManager("pages");
      $tableManager->setWhere(
        $tableManager->quoteString("(ISNULL(`pages`.`deleted`) OR `pages`.`deleted`=?)", (int)0)
      );
      $pages = $tableManager->selectMultiple();
      
      $tableManager = new TableManager("type");
      $tableManager->setWhere(
        "ISNULL(`deleted`)"
      );
      $pagetypes = $tableManager->selectMultiple();
      
      $html = (string)"<script type=\"text/javascript\" src=\"http://".$_SERVER["SERVER_NAME"]."/basefunctions/scripts/nicEdit.js\"></script>".PHP_EOL;
      $html .= "<form method=\"POST\">".PHP_EOL;
      $html .= "<div style=\"text-align:center;\">".PHP_EOL;
      $html .= "<input type=\"submit\" value=\"Save\" style=\"background-color:transparent;background-image:url(".BASE."images/new/button.png);border:none;height:34px;width:114px;\" />".PHP_EOL;
      $html .= "</div>".PHP_EOL;
      $html .= "<label for=\"name\" style=\"color:#000;\">Name</label>".PHP_EOL;
      $html .= "<input type=\"text\" id=\"name\" name=\"name\" value=\"".($record ? $record["name"] : "")."\" /><br />".PHP_EOL;
      $html .= "<label for=\"departments_id\" style=\"color:#000;\">Department</label>".PHP_EOL;
      $html .= "<select id=\"departments_id\" name=\"departments_id\">".PHP_EOL;
      $html .= "<option value=\"0\">Please select...</options>".PHP_EOL;
      foreach ($departments as $dept) {
        $html .= "<option value=\"".$dept["id"]."\"".($record && $record["departments_id"] == $dept["id"] ? " selected=\"selected\"" : "").">".$dept["name"]."</option>".PHP_EOL;
      }
      $html .= "</select><br class=\"clear\" />".PHP_EOL;
      $html .= "<label for=\"type_id\" style=\"color:#000;\">Page type</label>".PHP_EOL;
      $html .= "<select id=\"type_id\" name=\"type_id\">".PHP_EOL;
      $html .= "<option value=\"0\">Please select...</options>".PHP_EOL;
      foreach ($pagetypes as $type) {
        $html .= "<option value=\"".$type["id"]."\"".($record && $record["type_id"] == $type["id"] ? " selected=\"selected\"" : "").">".$type["name"]."</option>".PHP_EOL;
      }
      $html .= "</select><br class=\"clear\" />".PHP_EOL;
      $html .= "<label for=\"parent_id\" style=\"color:#000;\">Parent page</label>".PHP_EOL;
      $html .= "<select id=\"parent_id\" name=\"parent_id\">".PHP_EOL;
      $html .= "<option value=\"0\">Please select...</options>".PHP_EOL;
      foreach ($pages as $page) {
        $html .= "<option value=\"".$page["id"]."\"".($record && $record["parent_id"] == $page["id"] ? " selected=\"selected\"" : "").">".$page["name"]."</option>".PHP_EOL;
      }
      $html .= "</select><br class=\"clear\" />".PHP_EOL;
      $html .= "<label for=\"content\" style=\"color:#000;\">Content</label><br style=\"clear:both;\" />".PHP_EOL;
      $html .= "<textarea id=\"content\" name=\"content\" style=\"height:200px;width:400px;\">".($record ? $record["content"] : "")."</textarea><br />".PHP_EOL;
      $html .= "<div style=\"text-align:center;\">".PHP_EOL;
      $html .= "<input type=\"submit\" value=\"Save\" style=\"background-color:transparent;background-image:url(".BASE."images/new/button.png);border:none;height:34px;width:114px;\" />".PHP_EOL;
      $html .= "</div>".PHP_EOL;
      $html .= "</form>".PHP_EOL;
      $html .= "<iframe id=\"frameLoader\" style=\"display:none;\">&nbsp;</iframe>";
      $html .= "<script type=\"text/javascript\">document.getElementById('frameLoader').onload = function () {
      var content = new nicEditor({
      uploadURI: 'http://".$_SERVER["SERVER_NAME"]."/Maxine/scaffold/nicUpload.php',
      fullPanel: true
      }).panelInstance('content',{hasPanel : true})
      }</script>".PHP_EOL;
    }
    $this->_html = $html;
  }
  
  /** Scaffold_Forms_Pages::setRecord(array $record)
    * @param array $record which record are we updating?
    */
  public function setRecord(array $record)
  {
    $this->_record = $record;
  }
  //: End
  
  //: Magic
  /** Scaffold_Forms_Pages::__constuct()
    * Class Constructor
    */
  public function __construct()
  {
    
  }
  
  /** Scaffold_Forms_Pages::__destuct()
    * Allow for Garbage Collection
    */
  public function __destruct()
  {
    unset($this);
  }
  //: End
  
  //: Private functions
  
}