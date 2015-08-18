<?php
/** CLASS::Scaffold_Forms_SearchGalleryItems
  * @author Feighen Oosterbroek
  * @author feighen@manlinegroup.com
  * @copyright 2010 onwards Manline Group (Pty) Ltd
*/
class Scaffold_Forms_SearchGalleryItems
{
  //: Variables
  protected $_html;
  
  //: Public functions
  //: Accessors
  public function getHtml()
  {
          if (!$this->_html) {
                  $this->setHtml();
          }
          return $this->_html;
  }
  
  public function setHtml($html = null)
  {
          if (is_string($html) === false) {
                  $html = null;
          }
          if ($html === null) {
                  $gallery = new TableManager("gallery");
                  $list = $gallery->selectMultiple();
                  $html = (string)"<div class=\"standard content1\" style=\"margin-bottom:10px;width:500px;\">";
                  $html .= "<div onclick=\"toggle('scaffoldSearchForm');var text = (this.firstChild.nodeValue.match(/Display*/) ? 'Hide Search Form' : 'Display Search Form');clearAllElements(this);this.appendChild(document.createTextNode(text));\" style=\"cursor:pointer;text-align:center;\">";
                  $html .= "Display Search Form";
                  $html .= "</div>";
                  $html .= "<form id=\"scaffoldSearchForm\" method=\"POST\" style=\"display:none;\">";
                  $html .= "<label for=\"name\">Name</label>";
                  $html .= "<input id=\"name\" name=\"name\" type=\"text\" value=\"".($_POST ? $_POST["name"] : "")."\" /><br />".PHP_EOL;
                  $html .= "<label for=\"gallery_id\">Gallery</label>";
                  $html .= "<select id=\"gallery_id\" name=\"gallery_id\" style=\"width:200px;\">";
                  $html .= "<option value=\"0\">Please select..</option>";
                  foreach ($list as $val) {
                          $html .= "<option".($_POST && $val["id"] == $_POST["gallery_id"] ? " selected=\"selected\"" : "")." value=\"".$val["id"]."\">".$val["name"]."</option>";
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
  /** Scaffold_Forms_SearchGalleryItems::__construct()
    * Class constructor
  */
  public function __construct()
  {
    
  }
  
  /** Scaffold_Forms_SearchGalleryItems::__destruct()
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
