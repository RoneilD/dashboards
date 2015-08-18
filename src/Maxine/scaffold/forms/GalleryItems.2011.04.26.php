<?php
/** CLASS::Scaffold_Forms_GalleryItems
  * @author feighen
  * @author feighen
  * @created 06 Dec 2010 11:59:00 AM
*/
class Scaffold_Forms_GalleryItems {
        //: Variables
        protected $_html;
        protected $_record;
        
        //: Public functions
        //: Getters and Setters
        /** Scaffold_Forms_GalleryItems::getHtml()
          * @return string $this->_html HTML form
        */
        public function getHtml()
        {
                if (!$this->_html) {
                        self::setHtml();
                }
                return $this->_html;
        }
        
        /** Scaffold_Forms_GalleryItems::getRecord()
          * @return array $this->_record which record are we updating?
        */
        public function getRecord()
        {
                return $this->_record;
        }
        
        /** Scaffold_Forms_GalleryItems::setHtml($html = null)
          * @param string $html HTML form definition
        */
        public function setHtml($html = null)
        {
                if ($html === null) {
                        $record = self::getRecord();
                        if (is_object("TableManager") === false) {
                                include_once(BASE.DIRECTORY_SEPARATOR."basefunctions".DIRECTORY_SEPARATOR."baseapis".DIRECTORY_SEPARATOR."TableManager.php");
                        }
                        $tableManager = new TableManager("gallery");
                        $tableManager->setWhere(
                                "ISNULL(`deleted`)"
                                );
                        $gallery = $tableManager->selectMultiple();
                        $html = (string)"<script type=\"text/javascript\" src=\"".BASE.DIRECTORY_SEPARATOR."basefunctions".DIRECTORY_SEPARATOR."scripts".DIRECTORY_SEPARATOR."ajax_file_upload.js\"></script>".PHP_EOL;
                        $html .= "<script type=\"text/javascript\" src=\"".BASE.DIRECTORY_SEPARATOR."basefunctions".DIRECTORY_SEPARATOR."scripts".DIRECTORY_SEPARATOR."ajax_directory_list.js\"></script>".PHP_EOL;
                        $html .= "<noscript><p class=\"error\">I'm sorry but you need to enable javascript for this to work correctly.</p></noscript>".PHP_EOL;
                        $html .= "<form method=\"POST\" id=\"uploaderForm\" style=\"width:750px;\">".PHP_EOL;
                        $html .= "<div style=\"text-align:center;\">".PHP_EOL;
                        $html .= "<input type=\"submit\" value=\"Save\" style=\"background-color:transparent;background-image:url(".BASE."images/new/button.png);border:none;height:34px;width:114px;\" />".PHP_EOL;
                        $html .= "</div>".PHP_EOL;
                        $html .= "<label for=\"gallery_id\" style=\"color:#000;\">Gallery</label>".PHP_EOL;
                        $html .= "<select id=\"gallery_id\" name=\"gallery_id\">".PHP_EOL;
                        $html .= "<option value=\"0\">Please select...</options>".PHP_EOL;
                        foreach ($gallery as $gall) {
                                $html .= "<option value=\"".$gall["id"]."\"".($record && $record["gallery_id"] == $gall["id"] ? " selected=\"selected\"" : "").">".$gall["name"]."</option>".PHP_EOL;
                        }
                        $html .= "</select><br class=\"clear\" />".PHP_EOL;
                        $html .= "<fieldset id=\"appendHere\">".PHP_EOL;
                        $html .= "<legend>Items</legend>".PHP_EOL;
                        ## upload a single file to the gallery
                        $html .= "<div style=\"float:left;width:350px;\">".PHP_EOL;
                        $html .= "<label for=\"is_landscape\" style=\"color:#000;\">Is Landscape?</label>".PHP_EOL;
                        $html .= "<input".($record["is_landscape"] ? " checked=\"checked\"" : "")." id=\"is_landscape\" name=\"is_landscape\" type=\"checkbox\" value=\"1\" />";
                        $html .= "<label for=\"name[0]\" style=\"color:#000;\">Name</label>".PHP_EOL;
                        $html .= "<input type=\"text\" class=\"standard\" id=\"name[0]\" name=\"name[0]\" readonly=\"readonly\" value=\"".($record ? $record["name"] : "")."\" /><br />".PHP_EOL;
                        $html .= "<label for=\"location_file[0]\" style=\"color:#000;\">Location</label>".PHP_EOL;
                        $location = (string)"gallery/";
                        $html .= "<input type=\"file\" id=\"location_file[0]\" name=\"location_file[0]\" readonly=\"readonly\" onchange=\"document.getElementById('name[0]').value = this.value.toString().substring(0, this.value.toString().lastIndexOf('.'));\" />".PHP_EOL;
                        $html .= "<input type=\"hidden\" name=\"file[0]\" id=\"file[0]\" />".PHP_EOL;
                        $html .= "<button onclick=\"ajaxFileUpload.doUpload('".$location."', 'file[0]', 'location_file[0]'); return false;\">Upload</button>".PHP_EOL;
                        $html .= "<br />".PHP_EOL;
                        $html .= "</div>".PHP_EOL;
                        ## Use a directory path
                        $html .= "<div style=\"border-left:1px solid #DCDCDC;float:left;padding-left:10px;text-align:left;width:350px;\">".PHP_EOL;
                        $html .= "<label for=\"file_path\" style=\"color:#000;\" title=\"Examples: http://www.facebook.com/images/, /var/www/pookybear etc....\">Location</label>".PHP_EOL;
                        $html .= "<input type=\"text\" id=\"file_path\" name=\"file_path\" />&nbsp;";
                        $html .= "<button onclick=\"directory.doScan(document.getElementById('file_path').value, 'fileListing'); return false;\">Find Files...</button><br />".PHP_EOL;
                        $html .= "<label for=\"checkAll\" style=\"color:#000;\">Check All</label>";
                        $html .= "<input type=\"checkbox\" id=\"checkAll\" name=\"checkAll\" disabled=\"disabled\" onclick=\"directory.handleFormInputs('uploaderForm', this);\" />";
                        $html .= "<br />".PHP_EOL;
                        $html .= "<div id=\"fileListing\"></div>".PHP_EOL;
                        $html .= "</div>".PHP_EOL;
                        $html .= "</fieldset>".PHP_EOL;
                        $html .= "<div style=\"text-align:center;\">".PHP_EOL;
                        $html .= "<input type=\"submit\" value=\"Save\" style=\"background-color:transparent;background-image:url(".BASE."images/new/button.png);border:none;height:34px;width:114px;\" />".PHP_EOL;
                        $html .= "</div>".PHP_EOL;
                        $html .= "</form>".PHP_EOL;
                        //$html .= "<iframe id=\"targetFrame\" name=\"targetFrame\"></iframe>".PHP_EOL;
                        $html .= "<iframe id=\"targetFrame\" name=\"targetFrame\" style=\"display:none;\"></iframe>".PHP_EOL;
                        $html .= "<p id=\"upload_process\" style=\"display:none;\"><img src=\"".BASE.DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."new".DIRECTORY_SEPARATOR."loading.png\" alt=\"Loading\" title=\"Please be patient. Uploading your file can take a bit of time\" /></p>".PHP_EOL;
                }
                $this->_html = $html;
        }
        
        /** Scaffold_Forms_GalleryItems::setRecord(array $record)
          * @param array $record which record are we updating?
        */
        public function setRecord(array $record)
        {
                $this->_record = $record;
        }
        //: End
        
        //: Magic
        /** Scaffold_Forms_GalleryItems::__constuct()
          * Class Constructor
        */
        public function __construct()
        {
                
        }
        
        /** Scaffold_Forms_GalleryItems::__destuct()
          * Allow for Garbage Collection
        */
        public function __destruct()
        {
                unset($this);
        }
        //: End
        
        //: Private functions
        
}