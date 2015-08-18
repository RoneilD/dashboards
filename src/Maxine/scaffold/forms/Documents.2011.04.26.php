<?php
/** CLASS::Scaffold_Forms_Documents
  * @author feighen
  * @author feighen
  * @created 06 Dec 2010 11:59:00 AM
*/
class Scaffold_Forms_Documents {
        //: Variables
        protected $_html;
        protected $_record;
        
        //: Public functions
        //: Getters and Setters
        /** Scaffold_Forms_Documents::getHtml()
          * @return string $this->_html HTML form
        */
        public function getHtml()
        {
                if (!$this->_html) {
                        self::setHtml();
                }
                return $this->_html;
        }
        
        /** Scaffold_Forms_Documents::getRecord()
          * @return array $this->_record which record are we updating?
        */
        public function getRecord()
        {
                return $this->_record;
        }
        
        /** Scaffold_Forms_Documents::setHtml($html = null)
          * @param string $html HTML form definition
        */
        public function setHtml($html = null)
        {
                if ($html === null) {
                        $record = self::getRecord();
                        if (is_object("TableManager") === false) {
                                include_once(BASE.DIRECTORY_SEPARATOR."basefunctions".DIRECTORY_SEPARATOR."baseapis".DIRECTORY_SEPARATOR."TableManager.php");
                        }
                        $tableManager = new TableManager("m3_departments");
                        $tableManager->setWhere(
                                $tableManager->quoteString("`display`=?", 1)
                                );
                        $departments = $tableManager->selectMultiple();
                        $tableManager = new TableManager("type");
                        $tableManager->setWhere(
                                "ISNULL(`deleted`)"
                                );
                        $types = $tableManager->selectMultiple();
                        $html = (string)"<script type=\"text/javascript\" src=\"".BASE.DIRECTORY_SEPARATOR."basefunctions".DIRECTORY_SEPARATOR."scripts".DIRECTORY_SEPARATOR."ajax_file_upload.js\"></script>".PHP_EOL;
                        $html .= "<noscript><p class=\"error\">I'm sorry but you need to enable javascript for this to work correctly.</p></noscript>".PHP_EOL;
                        $html .= "<form method=\"POST\" id=\"uploaderForm\">".PHP_EOL;
                        $html .= "<div style=\"text-align:center;\">".PHP_EOL;
                        $html .= "<input type=\"submit\" value=\"Save\" style=\"background-color:transparent;background-image:url(".BASE."images/new/button.png);border:none;height:34px;width:114px;\" />".PHP_EOL;
                        $html .= "</div>".PHP_EOL;
                        $html .= "<label for=\"departments_id\" style=\"color:#000;\">Department</label>".PHP_EOL;
                        $html .= "<select id=\"departments_id\" name=\"departments_id\">".PHP_EOL;
                        $html .= "<option value=\"0\">Please select...</options>".PHP_EOL;
                        foreach ($departments as $dept) {
                                $html .= "<option value=\"".$dept["id"]."\"".($record && $record["department_id"] == $dept["id"] ? " selected=\"selected\"" : "").">".$dept["name"]."</option>".PHP_EOL;
                        }
                        $html .= "</select><br class=\"clear\" />".PHP_EOL;
                        $html .= "<label for=\"type_id\" style=\"color:#000;\">Document type</label>".PHP_EOL;
                        $html .= "<select id=\"type_id\" name=\"type_id\">".PHP_EOL;
                        $html .= "<option value=\"0\">Please select...</options>".PHP_EOL;
                        if ($types && is_array($types)) {
                                foreach ($types as $type) {
                                        $html .= "<option value=\"".$type["id"]."\"".($record && $record["type_id"] == $type["id"] ? " selected=\"selected\"" : "").">".$type["name"]."</option>".PHP_EOL;
                                }
                        }
                        $html .= "</select><br class=\"clear\" />".PHP_EOL;
                        $html .= "<label for=\"name[0]\" style=\"color:#000;\">Name</label>".PHP_EOL;
                        $html .= "<input type=\"text\" class=\"standard\" id=\"name[0]\" name=\"name\" readonly=\"readonly\" value=\"".($record ? $record["name"] : "")."\" /><br />".PHP_EOL;
                        $html .= "<label for=\"location_file\" style=\"color:#000;\">Location</label>".PHP_EOL;
                        $location = (string)"documents/";
                        $html .= "<input type=\"file\" id=\"location_file\" name=\"location_file\" readonly=\"readonly\" onchange=\"document.getElementById('name[0]').value = this.value.toString().substring(0, this.value.toString().lastIndexOf('.'));\" />".PHP_EOL;
                        $html .= "<input type=\"hidden\" name=\"location\" id=\"location\" />".PHP_EOL;
                        $html .= "<button onclick=\"ajaxFileUpload.doUpload('".$location."', 'location', 'location_file'); return false;\">Upload</button>".PHP_EOL;
                        $html .= "<br />".PHP_EOL;
                        $html .= "<div style=\"text-align:center;\">".PHP_EOL;
                        $html .= "<input type=\"submit\" value=\"Save\" style=\"background-color:transparent;background-image:url(".BASE."images/new/button.png);border:none;height:34px;width:114px;\" />".PHP_EOL;
                        $html .= "</div>".PHP_EOL;
                        $html .= "</form>".PHP_EOL;
                        //$html .= "<iframe id=\"targetFrame\" name=\"targetFrame\"></iframe>".PHP_EOL;
                        $html .= "<iframe id=\"targetFrame\" name=\"targetFrame\" style=\"display:none;\"></iframe>".PHP_EOL;
                        $html .= "<p id=\"upload_process\" style=\"display:none;\"><img src=\"".BASE.DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."loading.gif\" alt=\"Loading\" title=\"Please be patient. Uploading your file can take a bit of time\" /></p>".PHP_EOL;
                }
                $this->_html = $html;
        }
        
        /** Scaffold_Forms_Documents::setRecord(array $record)
          * @param array $record which record are we updating?
        */
        public function setRecord(array $record)
        {
                $this->_record = $record;
        }
        //: End
        
        //: Magic
        /** Scaffold_Forms_Documents::__constuct()
          * Class Constructor
        */
        public function __construct()
        {
                
        }
        
        /** Scaffold_Forms_Documents::__destuct()
          * Allow for Garbage Collection
        */
        public function __destruct()
        {
                unset($this);
        }
        //: End
        
        //: Private functions
        
}
