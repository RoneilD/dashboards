<?php
/** CLASS::Baseapis_Paginator
  * @author feighen
  * @author feighen
  * @created 28 Dec 2010 11:16:19 AM
*/
class Baseapis_Paginator {
        //: Variables
        protected $_cssClasses = array();
        protected $_currentPage = 1;
        protected $_data = array();
        protected $_footer;
        protected $_html = array();
        protected $_maxDisplay = 10;
        protected $_postCurrent = 6;
        protected $_preCurrent = 3;
        protected $_recordsPerPage;
        
        //: Public functions
        //: Accessors
        public function getCssClasses()
        {
                if (!$this->_cssClasses) {
                        $this->setCssClasses();
                }
                return $this->_cssClasses;
        }
        
        public function getCurrentPage()
        {
                return $this->_currentPage;
        }
        
        public function getData($key = null)
        {
                if (is_null($key) === false) {
                        return array_key_exists($key, $this->_data) ? $this->_data[$key] :false;
                }
                return $this->_data;
        }
        
        public function getFooter()
        {
                return $this->_footer;
        }
        
        /** Baseapis_Paginator::getHtml($class = null)
          * @param mixed $class which class entry are you wanting to retrieve?
        */
        public function getHtml($class = null)
        {
                if ($class) {
                        if (array_key_exists($class, $this->_html)) {
                                return $this->_html[$class];
                        } else {
                                return false;
                        }
                } else {
                        return $this->_html;
                }
        }
        
        public function getMaxDisplay()
        {
                return $this->_maxDisplay;
        }
        
        public function getPostCurrent()
        {
                return $this->_postCurrent;
        }
        
        public function getPreCurrent()
        {
                return $this->_preCurrent;
        }
        
        public function getRecordsPerPage()
        {
                return $this->_recordsPerPage;
        }
        
        /** Baseapis_Paginator::setCssClasses($classes = null, $append = false, $key = null)
          * @param mixed $classes array class names or string class name
          * @param bool $append are we adding a new class to an existant array?
          * @param mixed array key
          * @example $this->setCssClasses(array("paginatorTopRounded", "paginatorTop", "paginatorBottom", "paginatorBottomRounded"));
          * @example $this->setCssClasses("paginator_Top", true, null);
        */
        public function setCssClasses($classes = null, $append = false, $key = null)
        {
                if ($classes === null) {
                        $classes = (array)array(
                                "paginatorBottom",
                                "paginatorBottomRounded",
                                "paginatorTop",
                                "paginatorTopRounded"
                        );
                        $append = false;
                }
                if ($append === true) {
                        $current = $this->getCssClasses();
                        $current[$key ? $key : count($current)] = $classes;
                }
                $this->_cssClasses = isset($current) ? $current : $classes; 
        }
        
        public function setCurrentPage($current = null)
        {
                if (is_int($current) === false) {$current = null;}
                if ($current === null) {
                        $current = (int)1;
                }
                $this->_currentPage = $current;
        }
        
        public function setData($data, $sorted = false, $append = false, $key = null)
        {
                if ($append === true) {
                        $current = $this->getData();
                        $current[$key ? $key : count($current)] = $data;
                }
                if ($sorted === false) {
                        $unsorted = $data;
                        $data = (array)array();
                        $i = (int)0;
                        $j = (int)1;
                        foreach ($unsorted as $key=>$val) {
                                $data[$j][] = $val;
                                $i++;
                                if ($i === $this->getRecordsPerPage()) {
                                        $i = 0;
                                        $j++;
                                }
                        }
                }
                $this->_data = isset($current) ? $current : $data;
        }
        
        public function setFooter($footer)
        {
                if (is_bool($footer) === false) {
                        $footer = false;
                }
                $this->_footer = $footer;
        }
        
        public function setHtml($html = null)
        {
                if ($html === null) {
                        ## Preparation
                        $data = $this->getData();
                        $current = $this->getCurrentPage();
                        $get = $_GET;
                        $baseUrl = (string)"http://".$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"];
                        if (isset($get["page"])) {unset($get["page"]);}
                        $baseUrl .= "?";
                        foreach ($get as $key=>$val) {
                                $baseUrl .= $key."=".urlencode($val)."&";
                        }
                        $baseUrl = substr($baseUrl, 0, -1);
                        if (strstr($baseUrl, "?")) {
                                $baseUrl .= "&";
                        } else {
                                $baseUrl .= "?";
                        }
                        
                        ## Content
                        $html = (array)array();
                        foreach ($this->getCssClasses() as $class) {
                                $string = (string)"<div class=\"".$class."\">".PHP_EOL;
                                $string .= "<!--:first|previous buttons:-->".PHP_EOL;
                                if ($current > 1) {
                                        $string .= "<a href=\"".$baseUrl."page=1\" title=\"Click to go to the first page\"><img src=\"http://".$_SERVER["SERVER_NAME"]."/images/arrow-first.png\" alt=\"First Page\" class=\"paginatorFirst\" /></a>".PHP_EOL;
                                        $string .= "<a href=\"".$baseUrl."page=".($current-1)."\" title=\"Click to go to the previous page\"><img src=\"http://".$_SERVER["SERVER_NAME"]."/images/arrow-previous.png\" alt=\"Previous Page\" class=\"paginatorPrevious\" /></a>".PHP_EOL;
                                } else {
                                        $string .= "<img src=\"http://".$_SERVER["SERVER_NAME"]."/images/arrow-first.png\" alt=\"First Page\" class=\"paginatorFirst\" />".PHP_EOL;
                                        $string .= "<img src=\"http://".$_SERVER["SERVER_NAME"]."/images/arrow-previous.png\" alt=\"Previous Page\" class=\"paginatorPrevious\" />".PHP_EOL;
                                }
                                $string .= "<!--:Pages:-->".PHP_EOL;
                                $string .= "<span style=\"color:#000;\">Pages:</span> ".PHP_EOL;
                                if (count($this->getData()) > $this->getMaxDisplay()) {
                                        $current = $this->getCurrentPage();
                                        if ($current < 3) {
                                                for ($i=1; $i<=$this->getMaxDisplay(); $i++) {
                                                        if ($i === $current) {
                                                                $string .= $i.PHP_EOL;
                                                        } else {
                                                                $string .= "<a href=\"".$baseUrl."page=".$i."\" title=\"Click to go to page: ".$i."\">".$i."</a>".PHP_EOL;
                                                        }
                                                }
                                        } else {
                                             $first = $current - $this->getPreCurrent();
                                             $last = $current + $this->getPostCurrent();
                                             if ($last > count($this->getData())) {
                                                     $last = count($this->getData());
                                             }
                                             if ($last == count($this->getData())  || $this->getCurrentPage() == count($this->getData())) {
                                                     $first = count($this->getData()) - $this->getMaxDisplay();
                                             }
                                             for ($i=$first; $i<=$last; $i++) {
                                                        if ($i === $current) {
                                                                $string .= $i.PHP_EOL;
                                                        } else {
                                                                $string .= "<a href=\"".$baseUrl."page=".$i."\" title=\"Click to go to page: ".$i."\">".$i."</a>".PHP_EOL;
                                                        }
                                                }
                                        }
                                } else {
                                        for ($i=1; $i<=count($data); $i++) {
                                                if ($i === $current) {
                                                        $string .= $i.PHP_EOL;
                                                } else {
                                                        $string .= "<a href=\"".$baseUrl."page=".$i."\" title=\"Click to go to page: ".$i."\">".$i."</a>".PHP_EOL;
                                                }
                                        }
                                }
                                $string .= "<!--:next|last buttons:-->".PHP_EOL;
                                if ($current < count($data)) {
                                        $string .= "<a href=\"".$baseUrl."page=".($current+1)."\" title=\"Click to go to the next page\"><img src=\"http://".$_SERVER["SERVER_NAME"]."/images/arrow-next.png\" alt=\"Next Page\" class=\"paginatorNext\" /></a>".PHP_EOL;
                                        $string .= "<a href=\"".$baseUrl."page=".(count($data))."\" title=\"Click to go to the last page\"><img src=\"http://".$_SERVER["SERVER_NAME"]."/images/arrow-last.png\" alt=\"Last Page\" class=\"paginatorLast\" /></a>".PHP_EOL;
                                } else {
                                        $string .= "<img src=\"http://".$_SERVER["SERVER_NAME"]."/images/arrow-next.png\" alt=\"Next Page\" class=\"paginatorNext\"  />".PHP_EOL;
                                        $string .= "<img src=\"http://".$_SERVER["SERVER_NAME"]."/images/arrow-last.png\" alt=\"Last Page\" class=\"paginatorLast\" />".PHP_EOL;
                                }
                                $string .= "</div>".PHP_EOL;
                                $html[$class] = $string;
                        }
                        // $html .= "".PHP_EOL;
                }
                $this->_html = $html;
        }
        
        public function setMaxDisplay($current = null)
        {
                if (is_int($current) === false) {$current = null;}
                if ($current === null) {
                        $current = (int)10;
                }
                $this->_maxDisplay = $current;
        }
        
        public function setPreCurrent($current = null)
        {
                if (is_int($current) === false) {$current = null;}
                if ($current === null) {
                        $current = (int)2;
                }
                $this->_preCurrent = $current;
        }
        
        public function setPostCurrent($current = null)
        {
                if (is_int($current) === false) {$current = null;}
                if ($current === null) {
                        $current = (int)7;
                }
                $this->_postCurrent = $current;
        }
        
        public function setRecordsPerPage($records = null)
        {
                if (is_int($records) === false) {$records = null;}
                if ($records === null) {
                        $records = (int)9;
                }
                $this->_recordsPerPage = (int)$records;
        }
        //: End
        
        //: Magic
        /** Baseapis_Paginator::__constuct(array $data)
          * Class Constructor
          * @param array $data the data to be paginated 
        */
        public function __construct(array $data)
        {
                $this->setRecordsPerPage();
                $this->setData($data);
        }
        
        /** Baseapis_Paginator::__destuct()
          * Allow for Garbage Collection
        */
        public function __destruct()
        {
                unset($this);
        }
        //: End
        
        //: Private functions
        
}