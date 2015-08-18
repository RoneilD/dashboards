<?php
/** CLASS::Scaffold_Forms_Newspaper_articles
  * @author feighen
  * @author feighen
  * @created 31 Dec 2010 8:10:53 AM
*/
class Scaffold_Forms_Newspaper_articles {
        //: Variables
        protected $_html;
        protected $_record;
        
        //: Public functions
        //: Getters and Setters
        /** Scaffold_Forms_Newspaper_articles::getHtml()
          * @return string $this->_html HTML form
        */
        public function getHtml()
        {
                if (!$this->_html) {
                        self::setHtml();
                }
                return $this->_html;
        }
        
        /** Scaffold_Forms_Newspaper_articles::getRecord()
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
                        $html = (string)"<script type=\"text/javascript\" src=\"".BASE.DIRECTORY_SEPARATOR."basefunctions".DIRECTORY_SEPARATOR."scripts".DIRECTORY_SEPARATOR."ajax_file_upload.js\"></script>".PHP_EOL;
                        $html .= "<form method=\"post\" id=\"uploaderForm\" onsubmit=\"document.getElementById('date_published').value = document.getElementById('date_published').value.replace(/\//g, '-');\">".PHP_EOL;
                        $html .= "<div style=\"text-align:center;\">".PHP_EOL;
                        $html .= "<input type=\"submit\" value=\"Save\" style=\"background-color:transparent;background-image:url(".BASE."images/new/button.png);border:none;height:34px;width:114px;\" />".PHP_EOL;
                        $html .= "</div>".PHP_EOL;
                        
                        $html .= "<label for=\"periodical\" style=\"color:#000;\">Periodical</label>".PHP_EOL;
                        $html .= "<input type=\"text\" name=\"periodical\" id=\"periodical\" value=\"".($record ? $record["periodical"] : "")."\" /><br />".PHP_EOL;
                        
                        $html .= "<label for=\"date_published\" style=\"color:#000;\">Date Published</label>".PHP_EOL;
                        $html .= "<input type=\"text\" name=\"date_published\" id=\"date_published\" value=\"".($record ? $record["date_published"] : "")."\" />".PHP_EOL;
                        $html .= "<img src='".BASE."/images/calendar.png' onClick='displayDatePicker(\"date_published\", this, \"ymd\", \"\");' /><br />".PHP_EOL;
                        
                        $html .= "<input type=\"hidden\" class=\"standard\" id=\"name[0]\" name=\"name\" readonly=\"readonly\" value=\"".($record ? $record["name"] : "")."\" />".PHP_EOL;
                        $html .= "<label for=\"location_file\" style=\"color:#000;\">Image</label>".PHP_EOL;
                        $location = (string)"news/";
                        $html .= "<input type=\"file\" id=\"location_file\" name=\"location_file\" readonly=\"readonly\" onchange=\"document.getElementById('name[0]').value = this.value.toString().substring(0, this.value.toString().lastIndexOf('.'));\" />".PHP_EOL;
                        $html .= "<input type=\"hidden\" name=\"image\" id=\"file\" />".PHP_EOL;
                        $html .= "<button onclick=\"ajaxFileUpload.doUpload('".$location."', 'file', 'location_file'); return false;\">Upload</button>".PHP_EOL;
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
        
        /** Scaffold_Forms_Newspaper_articles::setRecord(array $record)
          * @param array $record which record are we updating?
        */
        public function setRecord(array $record)
        {
                $this->_record = $record;
        }
        //: End
        
        //: Magic
        /** Scaffold_Forms_Newspaper_articles::__constuct()
          * Class Constructor
        */
        public function __construct()
        {
                
        }
        
        /** Scaffold_Forms_Newspaper_articles::__destuct()
          * Allow for Garbage Collection
        */
        public function __destruct()
        {
                unset($this);
        }
        //: End
        
        //: Private functions
        
}