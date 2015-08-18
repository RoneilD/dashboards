<?php
$base = substr(__DIR__, 0, strrpos(__DIR__, "M"));
defined("DS") || define("DS", DIRECTORY_SEPARATOR);
include_once($base."basefunctions".DS."baseapis".DS."TableManager.php");
/** CLASS::Maxine_Scaffold_Custom_Imports_Pages
 * @author feighen
 * @author feighen
 * @created 06 Jan 2011 1:09:26 PM
*/
class Maxine_Scaffold_Custom_Imports_Pages {
	//: Variables
	protected $_tableManager;
	
	//: Public functions
	//: Accessors
	public function getTableManager()
	{
		return $this->_tableManager;
	}
	
	public function setTableManager(TableManager $manager = null)
	{
		if ($manager === null) {
			$manager = new TableManager("pages");
		}
		$this->_tableManager = $manager;
	}
	//: End
	
	//: Magic
	/** Maxine_Scaffold_Custom_Imports_Pages::__constuct()
		* Class Constructor
	*/
	public function __construct()
	{
		$this->setTableManager();
		echo("<link href=\"http://".$_SERVER["SERVER_NAME"]."/basefunctions/scripts/manline.css\" media=\"all\" rel=\"stylesheet\" type=\"text/css\" />".PHP_EOL);
		echo("<p class=\"standard\">".PHP_EOL);
		## the departments
		$sql = (string)"SELECT * FROM `m3_departments`";
		$departmentsI = $this->getTableManager()->runSql($sql);
		$departments = (array)array();
		foreach ($departmentsI as $dept) {
		if (in_array($dept["name"], array("Human Resources", "Information Technology"))) {
				$dept["abbr"] = ($dept["name"] == "Human Resources" ? "H.R" : "I.T");
			}
			$departments[$dept["id"]] = $dept;
		}
		unset($departmentsI);
		
		## Get the nodes from the drupaldb nodes tables
		$sql = (string)"SELECT `n`.`nid` AS `nodeID`, `r`.`title` AS `name`, `r`.`body` AS `content`, `t`.`id` as `type_id` ";
		$sql .= "FROM `drupaldb`.`node` AS `n` LEFT JOIN `drupaldb`.`node_revisions` AS `r` ON `n`.`nid`=`r`.`nid` LEFT JOIN `drupaldb`.`node_type` AS `nt` ON `nt`.`type`=`n`.`type` LEFT JOIN `maxinedb`.`type` AS `t` ON `nt`.`name`=`t`.`name` ";
		$sql .= "WHERE `nt`.`type` IN ('page', 'story') ";
		$sql .= "ORDER BY `nodeID`";
		$nodesI = $this->getTableManager()->runSql($sql);
		$nodes = (array)array();
		foreach ($nodesI as $node) {
			## Add Department details in
			foreach ($departments as $dept) {
				if (strstr($node["name"], $dept["name"])) {
					$node["departments_id"] = $dept["id"];
				} elseif (isset($dept["abbr"]) && strstr($node["name"], $dept["abbr"])) {
					$node["departments_id"] = $dept["id"];
				}
			}
			$nodes[$node["nodeID"]] = $node;
		}
		unset($nodesI);
		## insert the list of nodes
		foreach ($nodes as $node) {
			$data = (array)array(
				"name"=>$node["name"],
				"content"=>$node["content"],
				"type_id"=>$node["type_id"]
			);
			if ($node["departments_id"]) {
				$data["departments_id"] = $node["departments_id"];
			}
			$this->getTableManager()->insert($data);
			echo("<span title=\"Page Inserted\">|</span>".PHP_EOL);
		}
		
		## Get the list of inserted pages
		$pagesI = $this->getTableManager()->selectMultiple();
		$pages = (array)array();
		foreach ($pagesI as $page) {
			$pages[$page["name"]] = $page;
		}
		unset($pagesI);
		
		## Get the list of node, parentNode from the `drupaldb`.`menu` table
		## Yes I know the query is a bit horrifying. Sorry :(
		$sql = (string)"SELECT REVERSE(SUBSTRING_INDEX(REVERSE(`c`.`path`), \"/\", 1)) AS `child`, REVERSE(SUBSTRING_INDEX(REVERSE(`p`.`path`), \"/\", 1)) AS `parent`, `n`.`title` AS `childName`, `np`.`title` AS `parentName` ";
		$sql .= "FROM `drupaldb`.`menu` AS `c` LEFT JOIN `drupaldb`.`menu` AS `p` ON `p`.`mid`=`c`.`pid` LEFT JOIN `drupaldb`.`node` AS `np` ON `np`.`nid`=REVERSE(SUBSTRING_INDEX(REVERSE(`p`.`path`), \"/\", 1)) LEFT JOIN `drupaldb`.`node` AS `n` ON `n`.`nid`=REVERSE(SUBSTRING_INDEX(REVERSE(`c`.`path`), \"/\", 1)) ";
		$sql .= "WHERE `c`.`type`='118' ";
		$sql .= "HAVING `child` REGEXP '[0-9]{1,}'";
		$data = $this->getTableManager()->runSql($sql);
		foreach ($data as $row) {
			if (!$row["parent"]) {continue;} ## we don't need to update records if there is no parent record set
			$par = $pages[$row["parentName"]];
			$child = $pages[$row["childName"]];
			$this->getTableManager()->setWhere(
				$this->getTableManager()->quoteString("`pages`.`id`=?", $child["id"])
			);
			$data = (array)array(
				"parent_id"=>$par["id"]
			);
			$this->getTableManager()->update($data);
			echo("<span title=\"Page Updated\">|</span>".PHP_EOL);
			$this->getTableManager()->setWhere("");
		}
		
		## Green Promise Insert
		$data = array();
		$data["name"] = "Green Promise";
		$data["content"] = '<div style="margin-bottom:15px;text-align:center;">
<img alt="Green Promise" src="../images/green-promise-logo.png" />
</div>
<ul class="manline" style="list-style-image:url(http://maxine.za.net/images/new/list-bullet.png);text-align:center;">
<li class="greenPromiseLi">
<a href="/Maxine/index.php?action=viewpage&name=Advanced Driving Course" style="color:#FFF;" title="Click to view details"><strike>Pro-driving course</strike></a>
</li>
<li class="greenPromiseLi">
<a href="/Maxine/index.php?action=viewpage&name=Tandem Sky Diving Course"  style="color:#FFF;" title="Click to view details"><strike>Tandem sky diving course</strike></a>
</li>
<li class="greenPromiseLi">
<a href="/Maxine/index.php?action=viewpage&name=2 minute trolley dash – SPAR"  style="color:#FFF;" title="Click to view details"><strike>2-minute trolley dash - SPAR</strike></a>
</li>
<li class="greenPromiseLi">Code 14 license</li>
<li class="greenPromiseLi">Image consulting (make-over)</li>
<li class="greenPromiseLi">5-day weekend</li>
<li class="greenPromiseLi">MGS consult and plan, with garden clean-up</li>
<li class="greenPromiseLi"><strike>Romantic Midlands getaway (one night)</strike></li>
<li class="greenPromiseLi">2 x box tickets for the Currie Cup season</li>
<li class="greenPromiseLi">Family portrait session</li>
<li class="greenPromiseLi">5 x nutritionalist consults</li>
<li class="greenPromiseLi"><strike>Suit or dress design and manufacture</strike></li>
<li class="greenPromiseLi">
<a href="http://www.maxine.za.net/Maxine/index.php?action=viewpage&name=Scuba Diving Course" style="color:#FFF;" title="Click to view details"><strike>Scuba diving course</strike></a>
</li>
<li class="greenPromiseLi"><strike>4 x new tyres</strike></li>
<li class="greenPromiseLi">Fuel for a month</li>
<li class="greenPromiseLi">
<a href="/Maxine/index.php?action=viewpage&name=Day at uShaka for two couples and kids" style="color:#FFF;" title="Click to view details"><strike>Day at uShaka for two couples and kids</strike></a>
</li>
<li class="greenPromiseLi">3-hour fishing charter for four people off Durban</li>
<li class="greenPromiseLi">
<a href="/Maxine/index.php?action=viewpage&name=Hot Air Balloon Ride" style="color:#FFF;" title="Click to view details"><strike>Hot air ballooning for two</strike></a>
</li>
<li class="greenPromiseLi">
<a href="/Maxine/index.php?action=viewpage&name=Aerobatic Bi-plane Flight" style="color:#FFF;" title="Click to view details">Aerobatic bi-plane flight</a>
</li>
<li class="greenPromiseLi">
<a href="/Maxine/index.php?action=viewpage&name=Nguni Hide" style="color:#FFF;" title="Click to view details">Nguni hide</a>
</li>
<li class="greenPromiseLi">
<a href="/Maxine/index.php?action=viewpage&name=Full hair makeover and products" style="color:#FFF;" title="Click to view details">Full hair makeover and products</a>
</li>
<li class="greenPromiseLi">
<a href="/Maxine/index.php?action=viewpage&name=Static Line Jump Course" style="color:#FFF;" title="Click to view details">Static-line jump course</a>
</li>
<li class="greenPromiseLi">
<a href="/Maxine/index.php?action=viewpage&name=Helicopter flip at Cathedral Peak" style="color:#FFF;" title="Click to view details"><strike>Helicopter flip at Cathedral Peak</strike></a>
</li>
</ul>';
		$data["type_id"] = 17;
		$this->getTableManager()->insert($data);
	}
	
	/** Maxine_Scaffold_Custom_Imports_Pages::__destuct()
		* Allow for Garbage Collection
	*/
	public function __destruct()
	{
		echo("</p>".PHP_EOL);
		unset($this);
	}
	//: End
	
	//: Private functions
	
}
new Maxine_Scaffold_Custom_Imports_Pages();