<?php
class Sms
{
	//: Public functions
	/** formatCellNumber($number)
		* format a cellphone number
		* @param $number string cellphone number to format
		* @return string number if valid else false
	*/
	function formatCellNumber($number) {
		$number = preg_replace('/\s/', '', $number);
		switch (strlen($number)) {
		case 10: $number = '27'.substr($number, 1); break;
		case 11: $number = $number; break;
		case 15: $number = '27'.substr(preg_replace('/[\(|\)|\+]/', '', $number), 3); break;
			default: return false; break;
		}
		return $number;
	}
}
