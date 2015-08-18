<?php
defined(DS) || define("DS", DIRECTORY_SEPARATOR);
require_once(BASE."basefunctions".DS."baseapis".DS."communications".DS."Communications.interface.php");
require_once(BASE."basefunctions".DS."baseapis".DS."communications".DS."Sms.class.php");
require_once(BASE."basefunctions".DS."baseapis".DS."Logs.class.php");
require_once(BASE."basefunctions".DS."baseapis".DS."LogTypes.class.php");
require_once(BASE."basefunctions".DS."baseapis".DS."LogUrgency.class.php");
/** Class BulkSms
	* @file /basefunctions/baseapis/communications/Email.class.php
	* @author Feighen Oosterbroek
	* @author feighen@manlinegroup.com
	* @copyright 2009 onwards Manline group (Pty) Ltd
	* @example
		$BulkSms = new BulkSms();
		$BulkSms->setTo(array(0=>'0828248597'));
		$BulkSms->setBody('This is only a test');
		if (($BulkSms->send()) === false) {
			throw new man_exception('Could not successfully send out sms -- testing (Feighen)');
		}
	* end
*/
class BulkSms extends Sms implements Communications
{
	//: Variables
  protected $_body = '';
  protected $_errors = array();
  protected $_pass = 'kalumasms';
  protected $_test = false;
  protected $_to;
  protected $_user = 'kaluma';
	
	//: Public functions
	//: Magic functions
	/** BulkSms::__construct()
		* Class constructor
	*/
	public function __construct() {$this->_log = new Logs();}
	
	/** BulkSms::__destruct()
		* Class destructor
	*/
	public function __destruct() {unset($this);}
	//: End
	
	//: Getters
	public static function getBody() {return $this->_body;}
	public static function getAllErrors() {return $this->_errors;}
	public static function getLastError() {return $this->_errors[count($this->_errors)-1];}
	public static function getTo() {return $this->_to;}
	//: End
	
	public function send()
	{
		$logTypes = new logTypes();
		$logUrgency = new LogUrgency();
		$err = $logTypes->getRow(array('where'=>'`name`="error"'));
		$warn = $logTypes->getRow(array('where'=>'`name`="warning"'));
		$med = $logUrgency->getRow(array('where'=>'`name`="medium"'));
		
		$url = (string)'http://bulksms.2way.co.za/eapi/submission/';
		$url .= strlen($this->_body) > 160 ? 'send_batch/1/1.0' : 'send_sms/2/2.0';
		$port = 80;
		$fields = (string)'';
		$post_fields = (array)array('username'=>$this->_user, 'password'=>$this->_pass);
		if (strlen($this->_body) > 160) {
			$messagearray = str_split($this->_body, 155);
			$post_fields['batch_data'] = (string)'msisdn,message'.'~';
			if (is_array($this->_to)) {
				foreach ($this->_to as $value) {
					foreach ($messagearray as $line) {
						if (parent::formatCellNumber($value)) {
							$post_fields['batch_data'] .= '"'.parent::formatCellNumber($value).'","'.$line.'"~';
						}
					}
				}
			} else {
				foreach ($messagearray as $line) {
					if (parent::formatCellNumber($this->_to)) {
						$post_fields['batch_data'] .= '"'.parent::formatCellNumber($this->_to).'","'.$line.'"~';
					}
				}
			}
			$post_fields['batch_data'] = rtrim($post_fields['batch_data'], '~');
		} else {
			$post_fields['message'] = $this->_body;
			if (is_array($this->_to)) {
				$post_fields['msisdn'] = (string)'';
				foreach ($this->_to as $value) {
					if (parent::formatCellNumber($value)) {
						$post_fields['msisdn'] .= parent::formatCellNumber($value).',';
					}
				}
				$post_fields['msisdn'] = rtrim($post_fields['msisdn'], ',');
			} else {
				if (parent::formatCellNumber($this->_to)) {
					$post_fields['msisdn'] = parent::formatCellNumber($this->_to);
				}
			}
		}
		foreach($post_fields as $key=>$value) {
			if ($key == 'batch_data') {
				$split = preg_split('/~/', $value);
				$fields .= urlencode($key).'=';
				foreach ($split as $skey=>$sval) {$fields .= urlencode($sval).'%0A';}
				$fields = rtrim($fields, '%0A');
			} else {
				$fields .= urlencode($key).'='.urlencode($value).'&';
			}
		}
		$fields = rtrim($fields, '&');
		if ($this->_test) {
			echo '<pre>number:<br />';
			print_r($this->_to);
			echo '<br />message:<br />';
			print_r($this->_body);
			echo '<br />fields:<br />';
			print_r($fields);
			echo '<br />message array:<br />';
			print_r(isset($messagearray) ? $messagearray : '');
			echo '</pre>';
			return false;
		}
		$ch = curl_init(); //: open the curl connection/
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120); # Added by Feighen 2010-11-03 11h16
		curl_setopt($ch, CURLOPT_PORT, $port);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		$response_string = curl_exec($ch);
		$curl_info = curl_getinfo($ch);
		if ($response_string === false) {
			// do some logging
    	$data = (array)array();
			$data["log_typeid"] = $err['id'];
			$data["log_urgencyid"] = $med['id'];
			$data["title"] = "Could not successfully execute cURL statement";
			$data["userid"] = $_SESSION['userid'];
			$data["message"] = preg_replace("/'/", "\'", "cURL returned the following error: ".curl_error($ch));
			$data["file"] = __FILE__;
			$data["update_at"] = $_SERVER['REQUEST_TIME'];
			$data["create_at"] = $_SERVER['REQUEST_TIME'];
			$data["url"] = $_SERVER['REQUEST_URI'];
			if (($this->_log->create($data)) === false) {
				throw new man_exception("Could not successfully query the database");
			}
			return false;
		} elseif ($curl_info['http_code'] != 200) {
			$data = (array)array();
			$data["log_typeid"] = $err['id'];
			$data["log_urgencyid"] = $med['id'];
			$data["title"] = "cURL statement produced an invalid return statement";
			$data["userid"] = $_SESSION['userid'];
			$data["message"] = "cURL returned the following return code: ".$curl_info['http_code'].". It needs to be a 200 for a successful transfer";
			$data["file"] = __FILE__;
			$data["update_at"] = $_SERVER['REQUEST_TIME'];
			$data["create_at"] = $_SERVER['REQUEST_TIME'];
			$data["url"] = $_SERVER['REQUEST_URI'];
			if (($this->_log->create($data)) === false) {
				throw new man_exception("Could not successfully query the database");
			}
			return false;
		} else {
			$result = preg_split('/\|/', $response_string);
			if (count($result) != 3) {
				$data = (array)array();
				$data["log_typeid"] = $warn['id'];
				$data["log_urgencyid"] = $med['id'];
				$data["title"] = "Incorrect parameter count";
				$data["userid"] = $_SESSION['userid'];
				$data["message"] = "A split of the cURL response string returned ".count($result).". A total of three is required";
				$data["file"] = __FILE__;
				$data["update_at"] = $_SERVER['REQUEST_TIME'];
				$data["create_at"] = $_SERVER['REQUEST_TIME'];
				$data["url"] = $_SERVER['REQUEST_URI'];
				if (($this->_log->create($data)) === false) {
					throw new man_exception("Could not successfully query the database");
				}
				return false;
			} else {
				if ($result[0] == '0') {
					return true;
				} else {
					$data = (array)array();
					
					$data["log_typeid"] = $warning['id'];
					$data["log_urgencyid"] = $med['id'];
					$data["title"] = "Parameter one of the return from cURL is incorrect";
					$data["userid"] = $_SESSION['userid'];
					$data["message"] = "Parameter one of the cURL return should be zero. It returned ".$result[0]." and ".$result[1].".";
					$data["file"] = __FILE__;
					$data["update_at"] = $_SERVER['REQUEST_TIME'];
					$data["create_at"] = $_SERVER['REQUEST_TIME'];
					$data["url"] = $_SERVER['REQUEST_URI'];
					if (($this->_log->create($data)) === false) {
						throw new man_exception("Could not successfully query the database");
					}
					return false;
				}
			}
		}
		curl_close($ch); //: close the curl connection
	}
	
	//: Setters
	/** BulkSms::setBody($body)
		* @param string text message to be sent
	*/
	public function setBody($body) {$this->_body = $body;}
	
	/** BulkSms::setBulkSMSPassword($password)
		* @param $password string valid bulkSMS user password
	*/
	public function setBulkSMSPassword($password) {$this->_pass = $password;}
	
	/** BulkSms::setBulkSmsUser($user)
		* @param $user string valid bulkSMS username
	*/
	public function setBulkSmsUser($user) {$this->_user = $user;}
	
	/** BulkSms::setTest($test)
		* @param $test bool whether or not this is a test run
	*/
	public function setTest($test) {$this->_test = $test;}
	
	/** BulkSms::setTo($to)
		* @param $to array or string recipients
	*/
	public function setTo($to) {$this->_to = $to;}
	//: End
}
