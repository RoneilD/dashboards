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
      $html .= "<noscript><p class=\"error\">I'm sorry but you need to enable javascript for this to work correctly.</p></noscript>".PHP_EOL;
      $html .= "<form encoding=\"multipart/form-data\" enctype=\"multipart/form-data\" method=\"post\" id=\"uploaderForm\" onsubmit=\"document.getElementById('date_published').value = document.getElementById('date_published').value.replace(/\//g, '-');\">".PHP_EOL;
      $html .= "<div style=\"text-align:center;\">".PHP_EOL;
      $html .= "<input type=\"submit\" value=\"Save\" style=\"background-color:transparent;background-image:url(".BASE."images/new/button.png);border:none;height:34px;width:114px;\" />".PHP_EOL;
      $html .= "</div>".PHP_EOL;
      
      $html .= "<label for=\"periodical\" style=\"color:#000;\">Periodical</label>".PHP_EOL;
      $html .= "<input type=\"text\" name=\"periodical\" id=\"periodical\" value=\"".($record ? $record["periodical"] : "")."\" /><br />".PHP_EOL;
      
      $html .= "<label for=\"date_published\" style=\"color:#000;\">Date Published</label>".PHP_EOL;
      $html .= "<input type=\"text\" name=\"date_published\" id=\"date_published\" value=\"".($record ? $record["date_published"] : "")."\" />".PHP_EOL;
      $html .= "<img src='".BASE."/images/calendar.png' onClick='displayDatePicker(\"date_published\", this, \"ymd\", \"\");' /><br />".PHP_EOL;
      
      /* File uploader segment */
      $html .= "<label for=\"uploadFile\" style=\"color:#000;\">Choose file....</label>";
      $html .= "<input id=\"uploadFile\" name=\"uploadFile\" type=\"file\" />";
      $html .= "<button id=\"uploadButton\" onclick=\"return ajaxFileUpload.startUpload('uploaderForm', this);\">Upload....</button>";
      /* File uploader segment */
      
      $html .= "<br style=\"clear:both;\" />".PHP_EOL;
      $html .= "<div style=\"text-align:center;\">".PHP_EOL;
      $html .= "<input type=\"submit\" value=\"Save\" style=\"background-color:transparent;background-image:url(".BASE."images/new/button.png);border:none;height:34px;width:114px;\" />".PHP_EOL;
      $html .= "</div>".PHP_EOL;
      $html .= "</form>".PHP_EOL;
      $html .= "<script type=\"text/javascript\">
      ajaxFileUpload.setReturnDataFormat({
      name:'".($record ? "image" : "image[]")."'
      });
      ajaxFileUpload.setLocation(\"/Maxine/news/\");
      ajaxFileUpload.enableForm('uploaderForm');
      </script>";
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