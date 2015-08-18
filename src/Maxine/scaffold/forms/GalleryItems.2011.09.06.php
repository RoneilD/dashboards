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
      $html .= "<noscript><p class=\"error\">I'm sorry but you need to enable javascript for this to work correctly.</p></noscript>".PHP_EOL;
      $html .= "<form encoding=\"multipart/form-data\" enctype=\"multipart/form-data\" id=\"uploaderForm\" method=\"POST\" style=\"width:750px;\">".PHP_EOL;
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
      ### File uploader | directory uploader
      $html .= "<fieldset style=\"float:left;margin-right:5px;width:".($record ? "97%" : "450px").";\">";
      $html .= "<legend>Upload file(s)</legend>";
      /* File uploader segment */
      $html .= "<label for=\"uploadFile\" style=\"color:#000;\">Choose file....</label>";
      $html .= "<input id=\"uploadFile\" name=\"uploadFile\" type=\"file\" />";
      $html .= "<button id=\"uploadButton\" onclick=\"return ajaxFileUpload.startUpload('uploaderForm', this);\">Upload....</button>";
      /* File uploader segment */
      $html.= "</fieldset>";
      if (!$record) {
        $html .= "<fieldset style=\"float:left;margin-right:5px;width:250px;\">";
        $html .= "<legend>Search a directory</legend>";
        $html .= "<label for=\"directory\" style=\"color:#000;\">Directory path</label>";
        $html .= "<input id=\"directory\" name=\"directory\" type=\"text\" />";
        $file = (string)substr(__DIR__, 0, strrpos(__DIR__, DIRECTORY_SEPARATOR)+1)."pastLocations.txt";
        if (is_file($file)) {
          $data = file($file);
          if ($data) {
            $html .= "<ul style=\"list-style-position:outside;margin-left:20px;\">";
            foreach ($data as $line) {
              $html .= "<li onclick=\"document.getElementById('directory').value = this.firstChild.nodeValue;\" style=\"cursor:pointer;\">".$line."</li>";
            }
            $html .= "</ul>";
          }
        }
        $html.= "</fieldset>";
      }
      ### End
      $html .= "<br style=\"clear:both;\" /><br style=\"clear:both;\" /><div style=\"text-align:center;\">".PHP_EOL;
			$html .= "<input type=\"submit\" value=\"Save\" style=\"background-color:transparent;background-image:url(".BASE."images/new/button.png);border:none;height:34px;width:114px;\" />".PHP_EOL;
			$html .= "</div>".PHP_EOL;
			$html .= "</form>";
			$html .= "<script type=\"text/javascript\">
      ajaxFileUpload.setReturnDataFormat({
      name:'".($record  ? "file" : "file[]")."'
      });
      ajaxFileUpload.setLocation(\"/Maxine/gallery/\");
      ajaxFileUpload.enableForm('uploaderForm');
      </script>";
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