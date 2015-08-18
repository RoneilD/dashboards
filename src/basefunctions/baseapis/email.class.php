<?php
defined('RECIP_ERROR') || define('RECIP_ERROR', 'No recipient(s) defined!');
defined('SUBJ_ERROR') || define('SUBJ_ERROR', 'No email subject defined!');
defined('BODY_ERROR') || define('BODY_ERROR', 'No email content defined!');
/** email.class.php
  * @author Feighen Oosterbroek
  * @author feighen@manlinegroup.com
  * @copyright Manline (Pty) Ltd
  * @example $email = new manEmail(array(
    'to'=>array('Feighen Oosterbroek <feighen@manlinegroup.com>', 'justin@manline.co.za'),
    'subject'=>'sdfsd',
    'body'=>'this is a body'
  ));
  $email->send();
  * @example $email = new manEmail(array(
    'to'=>'nonsense@noemail.co,',
    'subject'=>'sdfsd',
    'body'=>'<html><head><style type="text/css">.heading{font-weight: bold;}</style></head><body><span class="heading">This is a heading</span></body></html>'
    'from'=>'Timber 24 <info@timber24.com>'
    'headers'=>array('Priority'=>'High')
  ));
  $email->send();
  * @example $email = new manEmail(array(
    'to'=>array(array('Feighen'=>'feighen@manlinegroup.com'), 'justin@manlinegroup.com'),
    'subject'=>'sdfsd',
    'bcc'=>array(array('Jonathan Spencer'=>'jonathan@manline.co.za')),
    'body'=>'<html><head><style type="text/css">.heading{font-weight: bold;}</style></head><body><span class="heading">This is a heading</span></body></html>',
    'from'=>'Timber 24 <info@timber24.com>',
    'headers'=>array('Priority'=>'High')
  ));
  $email->send();
  * @example $email = new manEmail(); // most desired method of implementation
             $email->setTo(array(array('Feighen Oosterbroek'=>'feighen@manline.com')));
             $email->setSubject('sdf');
             $email->setBody('this is only a test');
             $email->send();
*/
class manEmail {
  //: variables
  protected $_attachments;
  protected $_bcc;
  protected $_body = '';
  protected $_cc;
  protected $_errors;
  protected $_from = 'Manline <info@manlinegroup.com>';
  protected $_headers;
  protected $_replyTo;
  protected $_subject = 'No subject';
  protected $_test = false;
  protected $_to;
  
  //: public functions
  /** manEmail::__construct($opts = null)
    * class constructor
    * @param $opts array setup all class variables
    * @return bool true on success array errors otherwise
  */
  public function __construct($opts = null) {
    # set class variables
    if ($opts && is_array($opts)) {
      foreach ($opts as $key=>$val) {
        $var = '_'.$key;
        if (property_exists(__CLASS__, $var)) {$this->$var = $val;}
      }
    }
  }
  
  /** manEmail::__destruct()
    * class destructor
    * clean up any variable reference
  */
  public function __destruct() {
    $vars = get_class_vars(__CLASS__);
    foreach ($vars as $var=>$val) {unset($this->$var);}
  }
  
  // getters
  public function getAttachments() {return $this->_attachments;}
  public function getBcc() {return $this->_bcc;}
  public function getBody() {return $this->_body;}
  public function getCc() {return $this->_cc;}
  public function getErrors() {return $this->_errors;}
  public function getFrom() {return $this->_from;}
  public function getHeaders() {return $this->_headers;}
  public function getReplyTo() {return $this->_replyTo;}
  public function getSubject() {return $this->_subject;}
  public function getTo() {return $this->_to;}
  
  /** manEmail::send()
    * actually send out the email
    
  */
  public function send() {
    # we need to do some error checking
    if (!$this->_to) {$this->_errors[] = RECIP_ERROR;}
    if (!$this->_subject) {$this->_errors[] = SUBJ_ERROR;}
    if (!$this->_body) {$this->_errors[] = BODY_ERROR;}
    // assemble the email
    $data = $this->_assemble();
    if ($this->_test) {
      echo '<pre style="font-family:verdana, arial;font-size:15px;">';
      echo 'class_vars: <br />';
      echo 'to: ';
      print_r($this->_to);
      echo '<br />from: ';
      print_r($this->_from);
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
      return false;
    }
    return true;
  }
  
  // setters
  public function setAttachments($attachments) {$this->_attachments = $attachments;}
  public function setBcc($bcc) {$this->_bcc = $bcc;}
  public function setBody($body) {$this->_body = $body;}
  public function setCc($cc) {$this->_cc = $cc;}
  public function setFrom($from) {$this->_from = $from;}
  public function setHeaders($headers) {$this->_headers = $headers;}
  public function setRepplyTo($replyTo) {$this->_replyTo = $replyTo;}
  public function setSubject($subject) {$this->_subject = $subject;}
  public function setTest($test) {$this->_test = $test;}
  public function setTo($to) {$this->_to = $to;}
  
  //: private functions
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
    $data['headers'] = (string)"From: ".$this->_from.$eol;
    if ($this->_cc) {$data['headers'] .= "Cc: ".$this->_cc.$eol;}
    if ($this->_bcc) {$data['headers'] .= "Bcc: ".$this->_bcc.$eol;}
    $data['headers'] .= "X-Mailer: PHP/".phpversion().$eol;
    if ($this->_headers) {
      foreach ($this->_headers as $key=>$val) {$data['headers'] .= $key.': '.$val.$eol;}
    }
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
    return $data;
  }
  
  /** _checkEmailAddress($address)
    * test the validity of an email address
    * @param $address string email address to test
    * @return bool true on success false otherwise
  */
  private function _checkEmailAddress($address) {
    $regexp = (string)'/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/';
    return preg_match($regexp, $address);
  }
  
  /** manEmail::_getMimetype($file)
    * @param $file string file name
    * @return array or string
  */
  private function _getMimetype($file) {
    $exec = preg_split('/\s/', exec('whereis mime.types'));
		$ext = array_pop(explode('.', $file));
		$types = (array)array();
		foreach (file($exec[1]) as $line) {
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
