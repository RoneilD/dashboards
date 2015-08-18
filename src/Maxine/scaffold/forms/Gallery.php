<?php
/** CLASS::Scaffold_Forms_Gallery
  * @author feighen
  * @author feighen
  * @created 06 Dec 2010 11:59:00 AM
*/
class Scaffold_Forms_Gallery {
        //: Variables
        protected $_html;
        protected $_record;
        
        //: Public functions
        //: Getters and Setters
        /** Scaffold_Forms_Gallery::getHtml()
          * @return string $this->_html HTML form
        */
        public function getHtml()
        {
                if (!$this->_html) {
                        self::setHtml();
                }
                return $this->_html;
        }
        
        /** Scaffold_Forms_Gallery::getRecord()
          * @return array $this->_record which record are we updating?
        */
        public function getRecord()
        {
                return $this->_record;
        }
        
        /** Scaffold_Forms_Gallery::setHtml($html = null)
          * @param string $html HTML form definition
        */
        public function setHtml($html = null)
        {
                if ($html === null) {
                        $record = self::getRecord();
                        $html = (string)"<form method=\"POST\">".PHP_EOL;
                        $html .= "<div style=\"text-align:center;\">".PHP_EOL;
                        $html .= "<input type=\"submit\" value=\"Save\" style=\"background-color:transparent;background-image:url(".BASE."images/new/button.png);border:none;height:34px;width:114px;\" />".PHP_EOL;
                        $html .= "</div>".PHP_EOL;
                        $html .= "<label for=\"name\" style=\"color:#000;\">Name</label>".PHP_EOL;
                        $html .= "<input type=\"text\" id=\"name\" name=\"name\" value=\"".($record ? $record["name"] : "")."\" /><br />".PHP_EOL;
                        $html .= "<div style=\"text-align:center;\">".PHP_EOL;
                        $html .= "<input type=\"submit\" value=\"Save\" style=\"background-color:transparent;background-image:url(".BASE."images/new/button.png);border:none;height:34px;width:114px;\" />".PHP_EOL;
                        $html .= "</div>".PHP_EOL;
                        $html .= "</form>".PHP_EOL;
                }
                $this->_html = $html;
        }
        
        /** Scaffold_Forms_Gallery::setRecord(array $record)
          * @param array $record which record are we updating?
        */
        public function setRecord(array $record)
        {
                $this->_record = $record;
        }
        //: End
        
        //: Magic
        /** Scaffold_Forms_Gallery::__constuct()
          * Class Constructor
        */
        public function __construct()
        {
                
        }
        
        /** Scaffold_Forms_Gallery::__destuct()
          * Allow for Garbage Collection
        */
        public function __destruct()
        {
                unset($this);
        }
        //: End
        
        //: Private functions
        
}
