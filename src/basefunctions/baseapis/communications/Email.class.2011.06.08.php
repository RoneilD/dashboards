<?php
defined(DS) || define("DS", DIRECTORY_SEPARATOR);
define('BASE', '/home/feighen/workspace/maxweb/');
$dir = dirname(__FILE__);
$apis = substr($dir, 0, strrpos($dir, DS));
require_once($dir.DS."Communications.interface.php");
/* require_once($apis.DS."Logs.class.php");
require_once($apis.DS."LogTypes.class.php");
require_once($apis.DS."LogUrgency.class.php"); */
/** Class Email
	* @file /basefunctions/baseapis/communications/Email.class.php
	* @author Feighen Oosterbroek
	* @author feighen@manlinegroup.com
	* @copyright 2009 onwards Manline group (Pty) Ltd
	* @example
		$email = new Email();
		$email->setTo(array('Feighen Oosterbroek'=>'feighen@manlinegroup.com'));
		$email->setSubject('Test');
		$email->setBody('<html><body><img src="http://www.manlinegroup.com/images/logo.jpg" alt="Manline Logo" /><br /><p>Hello World</p></body></html>');
		$email->setAttachments(array(0=>'/var/www/manline/public/index.php'));
		if (($email->send()) === false) {
			print("Email unsuccessfully sent".PHP_EOL);
			return;
		}
		print("Email successfully sent".PHP_EOL);
	* end
*/
class Email implements Communications
{
	//: Variables
	protected $_attachments;
  protected $_bcc;
  protected $_body = '';
  protected $_bodyCorrectlyFormatted = false;
  protected $_cc;
  protected $_errors = array();
  protected $_from = 'Manline <info@manlinegroup.com>';
  protected $_headers;
  protected $_log;
  protected $_replyTo;
  protected $_subject = 'No subject';
  protected $_test = false;
  protected $_to;
	
	//: Public functions
	//: Magic functions
	/** Email::__construct()
		* Class constructor
	*/
	public function __construct() {/* $this->_log = new Logs(); */}
	
	/** Email::__destruct()
		* Class destructor
		* removes instances of this class
	*/
	public function __destruct() {}
	//: End
	
	//: Getters
	public static function getAllErrors() {return $this->_errors;}
	public static function getAttachments() {return $this->_attachments;}
  public static function getBcc() {return $this->_bcc;}
  public static function getBody() {return $this->_body;}
  public static function getCc() {return $this->_cc;}
	public static function getFrom() {return $this->_from;}
  public static function getHeaders() {return $this->_headers;}
	public static function getLastError() {return $this->_errors[count($this->_errors)-1];}
	public static function getReplyTo() {return $this->_replyTo;}
  public static function getSubject() {return $this->_subject;}
  public static function getTo() {return $this->_to;}
	//: End
	
	/** Email::send()
		* @return true on success false otherwise
	*/
	public function send()
	{
		// error checking
		if (!$this->_body) {$this->_errors[] = "Cannot send out email as no email body has been defined.";}
		if (!$this->_from) {$this->_from = (array)array("Manline group"=>"info@manlinegroup.com");}
		if (!$this->_to) {$this->_errors[] = "Cannot send out email as no recipients have been defined.";}
		// assemble the data array
		$data = $this->_assemble();
		if ($this->_test) {
      echo '<pre style="font-family:verdana, arial;font-size:15px;">';
      echo 'class_vars: <br />';
      echo '<br />from: ';
      print_r($this->_from);
      echo 'to: ';
      print_r($this->_to);
      echo '<br />subject: ';
      print_r($this->_subject);
      echo '<br />attachments: ';
      print_r($this->_attachments);
      echo '<br />headers: ';
      print_r($this->_headers);
      echo '<br />data<br />';
      print_r($data);
      echo '</pre>';
      return;
    }
    if ($this->_errors) {return $this->_errors;}
    if ((mail($data['to'], $this->_subject, $data['message'], $data['headers'])) === false) {
    	// do some logging
    	$logTypes = new logTypes();
    	$logUrgency = new LogUrgency();
    	$err = $logTypes->getRow(array('where'=>'`name`="error"'));
    	$med = $logUrgency->getRow(array('where'=>'`name`="medium"'));
    	$data = (array)array();
			$data["log_typeid"] = $err['id'];
			$data["log_urgencyid"] = $med['id'];
			$data["title"] = "Could not successfully send out email";
			$data["userid"] = $_SESSION['userid'];
			$data["message"] = implode("\n", $data);
			$data["file"] = __FILE__;
			$data["update_at"] = $_SERVER['REQUEST_TIME'];
			$data["create_at"] = $_SERVER['REQUEST_TIME'];
			$data["url"] = $_SERVER['REQUEST_URI'];
			if (($this->_log->create($data)) === false) {
				throw new man_exception("Could not successfully query the database");
			}
      return false;
    }
    return true;
	}
	
	//: Setters
	/** Email::setAttachments($attachments)
		* @param $attachments array attachments anything that fopen can handle
		* @example
			* $email->setAttachments(array(0=>'http://www.manlinegroup.com/css/manlinegroup.css', 1=>'/home/admin/hooray.txt'));
		* end
  */
  public function setAttachments($attachments) {$this->_attachments = $attachments;}
  
  /** Email::setBcc($bcc)
  	* @param $bcc array or string 
  	* @example
  		* $email->setBcc('Feighen Oosterbroek <feighen@manlinegroup.com>');
  		* $email->setBcc(array('feighen@manlinegroup.com'));
  		* $email->setBcc(array('Feighen Oosterbroek'=>'feighen@manlinegroup.com'));
  	* end
  */
  public function setBcc($bcc) {$this->_bcc = $bcc;}
  
  /** Email::setBody($body, $formatted)
  	* @param $body string the email body
  	* @param $formatted bool whether the body is already formatted correctly for emailing
  	* @example
  		* $email->setBody('<html><body><p>Hello World!</p></body></html>');
  		* $email->setBody('Hello World');
  	* end
  */
  public function setBody($body, $formatted = false) {
  	$this->_body = $body;
  	if ($formatted === true) {
  		$this->_bodyCorrectlyFormatted = $formatted;
  	}
  }
  
  /** Email::setCc($cc)
  	* @param $cc array or string 
  	* @example
  		* $email->setCc('Feighen Oosterbroek <feighen@manlinegroup.com>');
  		* $email->setCc(array('feighen@manlinegroup.com'));
  		* $email->setCc(array('Feighen Oosterbroek'=>'feighen@manlinegroup.com'));
  	* end
  */
  public function setCc($cc) {$this->_cc = $cc;}
  
  /** Email::setFrom($from)
  	* @param $from array or string 
  	* @example
  		* $email->setFrom('Feighen Oosterbroek <feighen@manlinegroup.com>');
  		* $email->setFrom(array('feighen@manlinegroup.com'));
  		* $email->setFrom(array('Feighen Oosterbroek'=>'feighen@manlinegroup.com'));
  	* end
  */
  public function setFrom($from) {$this->_from = $from;}
  
  /** Email::setHeaders($headers)
  	* @param $headers string any additional headers to be parsed
  */
  public function setHeaders($headers) {$this->_headers = $headers;}
	
	/** Email::setReplyTo($replyTo)
		* @param $replyTo array or string 
  	* @example
  		* $email->setReplyTo('Feighen Oosterbroek <feighen@manlinegroup.com>');
  		* $email->setReplyTo(array('feighen@manlinegroup.com'));
  		* $email->setReplyTo(array('Feighen Oosterbroek'=>'feighen@manlinegroup.com'));
  	* end
	*/
	public function setReplyTo($replyTo) {$this->_replyTo = $replyTo;}
	
	/** Email::setSubject($subject)
		* @param $subject string email subject
	*/
  public function setSubject($subject) {$this->_subject = $subject;}
  
  /** Email::setTest($test)
  	* @param $test bool whether this is a test run or not
  */
  public function setTest($test) {$this->_test = $test;}
  
  /** Email::setTo($to)
  	* @param $to array or string 
  	* @example
  		* $email->setTo('Feighen Oosterbroek <feighen@manlinegroup.com>');
  		* $email->setTo(array('feighen@manlinegroup.com'));
  		* $email->setTo(array('Feighen Oosterbroek'=>'feighen@manlinegroup.com'));
  	* end
  */
  public function setTo($to) {$this->_to = $to;}
	//: End
	
	//: Private functions
	/** manEmail::_assemble()
    * assemble email data
    * @return array data
  */
  private function _assemble() {
    $eol = "\n";
    $mime_boundary = md5(time())."-2";
    $mime_boundary2 = $mime_boundary."-3";
    $data = (array)array();
    // set the to addresses
    if (is_array($this->_to)) {
      $data['to'] = $this->_recurseArray($this->_to);
    } else {
      $data['to'] = $this->_to;
    }
    $data['to'] = rtrim($data['to'], ', ');
    // headers
    if ($this->_headers) {
      $data['headers'] = $this->_headers;
    } else {
    	$data['headers'] = (string)"From: ".$this->_from.$eol;
    	if ($this->_cc) {$data['headers'] .= "Cc: ".$this->_cc.$eol;}
    	if ($this->_bcc) {$data['headers'] .= "Bcc: ".$this->_bcc.$eol;}
    	$data['headers'] .= "X-Mailer: PHP/".phpversion().$eol;
    }
    if ($this->_bodyCorrectlyFormatted === true) {
    	$data['message'] = $this->_body;
    } else {
    	$data['headers'] .= "Mime-Version: 1.0".$eol;
    	$data['headers'] .= "Content-Type: multipart/mixed; boundary=\"".$mime_boundary."\"".$eol.$eol;
    	$data['headers'] .= "This is a MIME-formatted message.  If you see this text it means that your".$eol;
    	$data['headers'] .= "E-mail software does not support MIME-formatted messages.".$eol.$eol;
    	// message content
    	$data['message'] .= "--".$mime_boundary.$eol;
    	$data['message'] .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary2\"".$eol.$eol;
    	$data['message'] .= "This is a MIME-formatted message.  IF you see this text it means that your".$eol;
    	$data['message'] .= "E-mail softare does not support MIME-formatted messages.".$eol.$eol;
    	$data['message'] .= "--".$mime_boundary2.$eol;
    	$data['message'] .= "Content-Type: text/plain; charset=iso-8859-1; format=flowed".$eol;
    	$data['message'] .= "Content-Transfer-Encoding: 7bit".$eol;
    	$data['message'] .= "Content-Disposition: inline".$eol.$eol;
    	$data['message'] .= strip_tags(str_replace("<br>", "\n", $this->_body ));
    	$data['message'] .= $eol.$eol;
    	$data['message'] .= "--".$mime_boundary2.$eol;
    	$data['message'] .= "Content-Type: text/html; charset=iso-8859-1;".$eol;
    	$data['message'] .= "Content-Transfer-Encoding: quoted-printable".$eol;
    	$data['message'] .= "Content-Disposition: inline".$eol.$eol;
    	$data['message'] .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">".$eol;
    	$data['message'] .= "<html>".$eol;
    	$data['message'] .= "<body>".$eol;
    	$data['message'] .= $this->_body.$eol;
    	$data['message'] .= "</body>".$eol;
    	$data['message'] .= "</html>".$eol;
    	$data['message'] .= $eol.$eol;
    	$data['message'] .= "--".$mime_boundary2."--".$eol.$eol;
    	// file attachments
    	if ($this->_attachments) {
    		foreach ($this->_attachments as $val) {
    			$fp = (array)array();
    			if ((@$fp = fopen($val, 'rb')) === false) {$this->_errors[] = 'Could not open file: '.$val.' for reading'; continue;}
    			$filedata = fread($fp, (@filesize($val) ? filesize($val) : 10240));
    			fclose($fp);
    			$split = preg_split('/\//', $val);
    			$name = $split[count($split)-1];
    			$type = $this->_getMimetype($name);
    			$attachment = chunk_split(base64_encode($filedata));
    			$data['message'] .= "--".$mime_boundary.$eol;
    			$data['message'] .= "Content-Type: ".$type[0]."; name=\"".$name."\"".$eol;
    			$data['message'] .= "Content-Transfer-Encoding: base64".$eol;
    			$data['message'] .= "Content-Description: attachment; $eol filename=\"".$name."\"".$eol.$eol; // !! This line needs TWO end of lines !! IMPORTANT !!
    			$data['message'] .= $attachment.$eol.$eol;
    		}
    	}
		}
    return $data;
  }
	
	/** _checkEmailAddress($address)
    * test the validity of an email address
    * @param $address string email address to test
    * @return bool true on success false otherwise
  */
  private function _checkEmailAddress($address) {
    $regexp = (string)'/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/';
    return preg_match($regexp, $address);
  }
	
  /** manEmail::_getMimetype($file)
    * @param $file string file name
    * @return array or string
  */
  private function _getMimetype($file) {
  	$isWindows = DIRECTORY_SEPARATOR == '\\' ? true : false;
  	$types = (array)array();
  	$cmd = (string)"";
  	if ($isWindows === true) {
  		$cmd .= "dir mime.types /s/b";
  	} else {
  		$cmd .= "whereis mime.types";
		}
		$exec = preg_split('/\s/', exec($cmd));
		$ext = array_pop(explode('.', $file));
		foreach (file($exec[1]) as $line) {
			$m = (array)array();
			if (isset($ext) && preg_match('/^([^#]\S+)\s+.*'.$ext.'.*$/', $line, $m)) {
				$types[] = $m[1];
				return $types;
			}
		}
		return $types;
  }
  
	/** _recurseArray($array)
    * recursively loop through an array and output to a string
    * @param $array array array to loop through
  */
  private function _recurseArray($array) {
    $string = (string)'';
    foreach ($array as $key=>$val) {
      if (is_array($val)) {
        $string .= $this->_recurseArray($val);
      } else {
        if (($this->_checkEmailAddress($val)) === false) {continue;}
        if (is_string($key)) {
          $string .= $key.' <'.$val.'>, ';
        } else {
          $string .= $val.', ';
        }
      }
    }
    return $string;
  }
}
