<?php
/** CLASS::Scaffold_Forms_Import
  * @author Feighen Oosterbroek
  * @author feighen@manlinegroup.com
  * @copyright 2010 onwards Manline Group (Pty) Ltd
*/
class Scaffold_Forms_Import
{
        //: Variables
        protected $_columns;
        protected $_html;
        
        //: Public functions
        //: Getters and Setters
        public function getColumns()
        {
                return $this->_columns;
        }
        
        public function getHtml()
        {
                if (!$this->_html) {
                        self::setHtml();
                }
                return $this->_html;
        }
        
        public function setColumns(array $columns)
        {
                $this->_columns = $columns;
        }
        
        public function setHtml($html = null)
        {
                if ($html === null) {
                        $html = (string)"<form method=\"POST\" enctype=\"multipart/form-data\">".PHP_EOL;
                        $html .= "<label for=\"csv\" style=\"color:#000;\">CSV File</label>".PHP_EOL;
                        $html .= "<input type=\"file\" id=\"csv\" name=\"csv\" /><br />".PHP_EOL;
                        $html .= "<input type=\"submit\" value=\"Save\" />";
                        $html .= "</form>".PHP_EOL;
                        if (self::getColumns()) {
                                $html .= "<div id=\"csvColumns\">".PHP_EOL;
                                $html .= "<div style=\"border:1px solid #888888;cursor:pointer;margin:0px auto;width:500px;\" onclick=\"toggle('columnsCSV');\">Imported CSV must include the following columns<br style=\"clear:both;\" /></div>".PHP_EOL;
                                $html .= "<div class=\"MAN12\" id=\"columnsCSV\" style=\"margin:0px auto;text-align:left;width:500px;\">".PHP_EOL;
                                foreach (self::getColumns() as $col) {
                                        $html .= $col."<br />".PHP_EOL;
                                }
                                $html .= "".PHP_EOL;
                                $html .= "</div>".PHP_EOL;
                                $html .= "</div>".PHP_EOL;
                                $html .= "<br style=\"clear:both;\" />".PHP_EOL;
                        }
                }
                $this->_html = $html;
        }
        //: End
        
        //: Magic
        /** Scaffold_Forms_Import::__construct($columns = null)
          * Class constructor
        */
        public function __construct($columns = null)
        {
                if ($columns !== null) {
                        self::setColumns($columns);
                }
        }
        
        /** Scaffold_Forms_Import::__destruct()
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
