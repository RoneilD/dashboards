<?php
//: Log Level defines
define('NORMAL', 1); // Errors only
define('HIGH', 5); // Errors and warnings
define('VERBOSE', 9); // Errors, warnings and notices
define('VERBOSE_HIGH', 13); // Everything
//: End

/** Interface Communications
	* @file /basefunctions/baseapis/communications/Communications.interface.php
	* @author Feighen Oosterbroek
	* @author feighen@manlinegroup.com
	* @copyright 2009 onwards Manline Group (Pty) Ltd
*/
interface Communications
{
	//: Public functions
	//: Getters
	public static function getAllErrors();
	public static function getLastError();
	//: End
	
	public function send();
}
