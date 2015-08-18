<?php
/** CLASS::man_exception
  * @author Feighen Oosterbroek
  * @author feighen@manlinegroup.com
*/
class man_exception extends Exception {
	//: variables
	protected $string;
	protected $_html;
	
	//: Public functions
	//: Accessors
	public function getString()
	{
	        return $this->_string;
	}
	
	public function getHtml()
	{
	        return $this->_html;
	}
	
	public function setString($string)
	{
	        $this->string = $string;
	}
	
	public function setHtml($html)
	{
	        $this->_html = $html;
	}
	//: End
	
	//: Magic
	/** man_exception::__construct($string = null)
	  * Class constructor
	  * @param string $string what error message do you want to display
	*/
	public function __construct($string = null)
	{
		$this->setString($string);
		$this->setHtml(
		        $this->_buildExceptionHTML()
		);
		$this->_sendOutEmail();
		echo $this->_html; // print to the screen etc.
		exit;
	}
	
	/** man_exception::__destruct()
	  * Class destructor
	  * Allow for garbage collection
	*/
	public function __destruct()
	{
	        unset($this);
	}
	//: End
	
	//: private functions
	private function _buildExceptionHTML()
	{
		$html = (string)"<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
		$html .= "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n";
		$html .= "<head>\n";
		$html .= "<style type=\"text/css\">\n";
		$html .= ".heading{font-size:125%;font-variant:small-caps;font-weight:bold;}\n";
		$html .= "body{font-family:verdana, arial, sans-serif;font-size:0.8125em;}\n";
		$html .= "table{empty-cells:show;margin:0px;padding:0px;width:100%;}\n";
		$html .= "table table{border:1px dotted #EBEBEB;empty-cells:show;margin:5px; width:100%;}\n";
		$html .= "td{vertical-align:top;}\n";
		$html .= "</style>";
		$html .= "</head>\n";
		$html .= "<body>\n";
		$html .= "<table>\n";
		$html .= "<tbody>\n";
		$html .= "<tr><td colspan=\"100%\" class=\"heading\">".$this->string."</td></tr>\n";
		$html .= "<tr><td>";
		$html .= "Stack trace";
		$html .= "</td><td>";
		$html .= nl2br($this->getTraceAsString());
		$html .= "</td></tr>\n";
		$html .= "<tr><td>";
		$html .= "_GET parameters";
		$html .= "</td><td>";
		$html .= $this->_recurseArray($_GET);
		$html .= "</td></tr>\n";
		$html .= "<tr><td>";
		$html .= "_POST parameters";
		$html .= "</td><td>";
		$html .= $this->_recurseArray($_POST);
		$html .= "</td></tr>\n";
		$html .= "<tr><td>";
		$html .= "_SESSION parameters";
		$html .= "</td><td>";
		if (isset($_SESSION))
		{
			$html .= $this->_recurseArray($_SESSION);
		}
		$html .= "</td></tr>\n";
		$html .= "</tbody>\n";
		$html .= "</table>\n";
		$html .= "</body>\n";
		$html .= "</html>";
		return $html;
	}
	
	/** man_exception::_recurseArray($array);
		* @param $array array data to loop through and format as string
		* @return string data
	*/
	private function _recurseArray($array)
	{
		if (!is_array($array)) {return false;}
		$html = (string)"<table>\n";
		$html .= "<tbody>\n";
		foreach ($array as $key=>$val) {
			if (is_array($val)) {
				$html .= "<tr><td>".ucwords(strtolower(preg_replace('/_/', ' ', $key)))."</td><td>\n";
				$html .= $this->_recurseArray($val);
				$html .= "</td></tr>\n";
			} else {
				$html .= "<tr><td>".ucwords(strtolower(preg_replace('/_/', ' ', $key)))."</td><td>".$val."</td></tr>\n";
			}
		}
		$html .= "</tbody>\n";
		$html .= "</table>\n";
		return $html;
	}
	
	/** man_exception::_sendOutEmail()
		* send out the email to developers@manlinegroup.com
		* @return true on success false otherwise
	*/
	private function _sendOutEmail()
	{
		require_once(BASE.'basefunctions/baseapis/communications/Email.class.php');
		
		$email = new Email();
		$email->setTo(array('Manline Developers'=>'developers@manlinegroup.com'));
		$email->setSubject('Exception: '.$this->string);
		$email->setBody($this->_html);
		if (($email->send()) === false) {
			return false;
		}
		return true;
	}
}
