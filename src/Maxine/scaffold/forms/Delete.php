<?php
/** CLASS::Scaffold_Forms_Delete
 * @author Feighen Oosterbroek
 * @author feighen@manlinegroup.com
 * @copyright 2010 onwards Manline Group (Pty) Ltd
 */
class Scaffold_Forms_Delete
{
	//: Variables
	protected $_html = null;
	protected $_records = null;

	//: Public functions
	//: Getters and Setters
	/** Scaffold_Forms_Delete::getHtml()
	 * @return string $this->_html html form
	 */
	public function getHtml()
	{
		if ($this->_html === null) {
			self::setHtml();
		}
		return $this->_html;
	}

	/** Scaffold_Forms_Delete::getRecords()
	 * @return array $this->_records list of records that can be deleted
	 */
	public function getRecords()
	{
		return $this->_records;
	}

	public function setHtml($html = null)
	{
		if ($html === null) {
			$records = self::getRecords();
			$html = (string)"<form method=\"post\" id=\"deleteForm\">".PHP_EOL;
			$html .= "<div style=\"text-align:center;\">".PHP_EOL;
			$html .= "<input type=\"submit\" value=\"Save\" style=\"background-color:transparent;background-image:url(".BASE."images/new/button.png);border:none;height:34px;width:114px;\" />".PHP_EOL;
			$html .= "</div>".PHP_EOL;
			$html .= "<table class=\"standard\" style=\"background-color:transparent;\">".PHP_EOL;
			$html .= "<thead>".PHP_EOL;
			$html .= "<tr><td class=\"standard\" colspan=\"100%\" class=\"toprow\">".PHP_EOL;
			$html .= "<input type=\"checkbox\" name=\"deleteAll\" id=\"deleteAll\" value=\"1\" onclick=\"formManipulators.checkCheckBoxes('deleteForm', this);\" />".PHP_EOL;
			$html .= "<label for=\"deleteAll\">Delete all records</label>".PHP_EOL;
			$html .= "</td></tr>".PHP_EOL;
			$html .= "<tr>".PHP_EOL;
			$html .= "<td rowspan=\"2\" style=\"color:#FFF;vertical-align:top;\">Delete?</td>".PHP_EOL;
			$html .= "<td colspan=\"".count($records[0])."\" style=\"color:#FFF;\">Table Data</td>".PHP_EOL;
			$html .= "</tr>".PHP_EOL;
			$html .= "<tr class=\"heading\">".PHP_EOL;
			foreach ($records[0] as $col=>$data) {
				if ($col == "id" || $col == "personid" || $col == "deleted") {continue;}
				$html .= "<td class=\"standard\">".($col == "id" ? "" : shortenWord(ucwords(str_replace("_", " ", $col)), 5))."</td>".PHP_EOL;
			}
			$html .= "</tr>".PHP_EOL;
			$html .= "</thead>".PHP_EOL;
			$html .= "<tbody>".PHP_EOL;
			foreach ($records as $key=>$row) {
				$class = (string)($key % 2 == 1 ? "content1" : "content2");
				$html .= "<tr class=\"".$class."\">".PHP_EOL;
				$html .= "<td class=\"standard\"><input type=\"checkbox\" name=\"record[]\" value=\"".$row["id"]."\" /></td>".PHP_EOL;
				foreach ($row as $col=>$val) {
					if ($col == "id" || $col == "personid" || $col == "deleted") {continue;}
					$html .= "<td class=\"standard\">".shortenWord(strip_tags(urldecode($val)), 9)."</td>".PHP_EOL;
				}
				$html .= "</tr>".PHP_EOL;
			}
			$html .= "</tbody>".PHP_EOL;
			$html .= "</table>".PHP_EOL;
			$html .= "<div style=\"text-align:center;\">".PHP_EOL;
			$html .= "<input type=\"submit\" value=\"Save\" style=\"background-color:transparent;background-image:url(".BASE."images/new/button.png);border:none;height:34px;width:114px;\" />".PHP_EOL;
			$html .= "</div>".PHP_EOL;
			$html .= "</form>".PHP_EOL;

			//$html .= "".PHP_EOL;
		}
		$this->_html = $html;
	}

	/** Scaffold_Forms_Delete::setRecords(array $records)
	 * @param array $records List of all currently active records
	 */
	public function setRecords(array $records)
	{
		$this->_records = $records;
	}
	//: End

	//: Magic
	/** Scaffold_Forms_Delete::__construct(array $records)
	* Class constructor
	* @param array $records list of all undeleted records for us to parse
	*/
	public function __construct(array $records)
	{
		self::setRecords($records);
	}

	/** Scaffold_Forms_Delete::__destruct()
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
