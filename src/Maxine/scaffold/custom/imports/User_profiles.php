<?php
$base = substr(__DIR__, 0, strrpos(__DIR__, "M"));
defined("DS") || define("DS", DIRECTORY_SEPARATOR);
include_once($base."basefunctions".DS."baseapis".DS."TableManager.php");
/** CLASS::Maxine_Scaffold_Custom_Imports_User_profiles
 * @author feighen
 * @author feighen
 * @created 14 Jan 2011 1:54:32 PM
*/
class Maxine_Scaffold_Custom_Imports_User_profiles {
	//: Variables
	protected $_tableManager;
	
	//: Public functions
	//: Accessors
	public function getTableManager()
	{
		if (!$this->_tableManager) {
			$this->setTableManager();
		}
		return $this->_tableManager;
	}
	
	public function setTableManager(TableManager $manager = null)
	{
		if ($manager === null) {
			$manager = new TableManager("user_profiles");
		}
		$this->_tableManager = $manager;
	}
	//: End
	
	//: Magic
	/** Maxine_Scaffold_Custom_Imports_User_profiles::__constuct()
		* Class Constructor
	*/
	public function __construct()
	{
		## Prep
		$staffnumbers = (array)array(
			1=>"N Henderson", 23=>"D Willows", 50=>"R van Zyl", 116=>"S Jackson", 127=>"P Gunundu",
			178=>"B Ngcobo", 323=>"S Nugent", 463=>"L Joubert", 490=>"S Mhlongo", 610=>"V Maharaj",
			749=>"H Balzer", 768=>"T Vilakazi", 784=>"C Smith", 847=>"K Phillips", 1046=>"S Singh",
			1100=>"B Wegener", 1223=>"M Jali", 1323=>"R Ramchunder", 1356=>"L Caswell", 1373=>"S Steyn",
			1454=>"R Thupsie", 1469=>"N Thethwayo", 701=>"A Honsh-Raj", 980=>"S Bhoola", 70=>"C Naidoo",
			82=>"R Singh", 209=>"P Woodley", 1263=>"F Khan", 139=>"P Maharaj", 179=>"A Neizel", 237=>"R Barnard",
			277=>"T Xaba", 503=>"R Robinson", 731=>"M Zuma", 732=>"E Bhengu", 1121=>"D Greene", 1125=>"S Govender",
			1279=>"J Padayachee", 1292=>"W Biller", 1322=>"G Woolley", 1335=>"M Mpangase", 1371=>"G Nel", 1408=>"C Dettmer",
			1430=>"M Wilson", 1459=>"A Simpson", 1460=>"J Foxcroft", 1530=>"L Van Teeffelen", 1535=>"A Melton",
			41=>"B Van Rooyen", 53=>"Z Makasana", 1172=>"C Noble", 1336=>"L Van Rooyen", 67=>"S Govender",
			822=>"K Sewpersad", 1324=>"V Sewnarain", 1465=>"J Strydom", 1543=>"K Fourie", 238=>"R Lovell",
			260=>"K Holtman", 338=>"C Bhartu", 884=>"A Mathaba", 1311=>"O Kiss", 52=>"M Naude", 212=>"S Maseko",
			1168=>"A Mohan", 1483=>"R Pretorius", 4=>"J Shezi", 10=>"M Wilson", 107=>"C Warr", 157=>"D Bezuidenhout",
			166=>"G Kitching", 309=>"S Khumalo", 317=>"S Mandonda", 458=>"S Ndlovu", 530=>"M Dorlly", 723=>"D Reynders",
			752=>"M Dumakude", 758=>"D Mthonti", 806=>"N Shangase", 871=>"S du Preez", 925=>"V Ganesh", 1061=>"P Nolte",
			1096=>"E Rossouw", 1103=>"M Ntshapha", 1357=>"S Ndluli", 1414=>"F Magwaza", 1470=>"K Naude", 1542=>"B De Lange",
			124=>"V Madikane", 264=>"G Pelser", 1204=>"V Govender", 1205=>"N Kekezwa", 1294=>"R Cellier", 1295=>"G Groepe",
			1326=>"A Smit", 217=>"N Milne", 695=>"E Madlala", 1045=>"N Anthony", 1169=>"J Parsons", 1203=>"S Severn",
			1455=>"C Charles", 1484=>"C Watson", 1529=>"G Abrahams", 1540=>"J Lambert", 28=>"F Gilson", 895=>"L Kuhn",
			1404=>"C Walker", 86=>"S Mbhele", 122=>"J Spencer", 288=>"B Roberts", 501=>"J Ward", 583=>"P Masondo", 674=>"F Thabethe",
			696=>"S Mthalane", 766=>"A Dlamini", 807=>"S Khumalo", 831=>"J Govender", 918=>"X Buthelezi", 932=>"S Ntuli",
			938=>"P Sithole", 940=>"M Shangase", 971=>"T Dlamini", 991=>"N Buthelezi", 1034=>"J Zondi",
			1035=>"C Msani", 1036=>"B Ngubane", 1099=>"L Ally", 1175=>"F Oosterbroek", 1267=>"S Ndlovu",
			1268=>"I Manana", 1269=>"L Mnikathi", 1296=>"N Langa", 1304=>"T Shaik", 1405=>"f Pienaar", 1415=>"N Zondo",
			1426=>"T Sibiya", 1521=>"M Moodley", 1526=>"S Tshabalala", 1527=>"S Ndaba", 1528=>"N Mkize", 1544=>"M Sikhosana",
			1545=>"S Visagie", 1546=>"M Mhlongo", 497=>"L Nel", 546=>"J Malema", 614=>"C Pelser", 705=>"V Nel",
			708=>"C Chirwa", 710=>"D Mngomezulu", 711=>"V Banda", 740=>"M Devananden", 848=>"S Mkhwanazi",
			941=>"P Pasmen", 957=>"G Anderson", 1123=>"Z Mdluli", 1312=>"M Mnguni", 1325=>"S Swart", 1337=>"F Botha",
			1352=>"V Msengana", 1353=>"M Mbatha", 1354=>"N Johannes", 1372=>"A Van Der Riet", 1409=>"A Maharaj",
			1413=>"J Mwale", 1467=>"S Blumenthal", 1468=>"J Ramatutu", 1481=>"L Lourens", 1522=>"D Chuma", 94=>"L Naude",
			114=>"H Nieuwenhuis", 539=>"C van der Merwe", 619=>"N Mazibuko", 967=>"J Redgard", 1303=>"P de Jong",
			1482=>"J Saayman", 1509=>"D Breytenbach", 1536=>"B Polkinghorne", 61=>"S Nzimande", 409=>"A Mtolo",
			607=>"J Maartens", 1062=>"K Balakistan", 1063=>"R Ramgobind", 1064=>"S Naidoo", 1065=>"A Murugan",
			828=>"M Seluma", 1170=>"J Van Wyngaard", 1278=>"Q Jansen", 1461=>"B Temba", 1252=>"R Ramdhan",
			3=>"S Mkhize", 337=>"C van Vuuren", 356=>"S Ximba", 370=>"N Welkom", 376=>"B Mahlangeni",
			702=>"O Simelane", 736=>"S Ngcongo", 737=>"M Meyiwa", 738=>"N Bhengu", 739=>"B Zuma", 1216=>"T Zuma",
			1239=>"N Nene", 1464=>"N Msomi", 1435=>"P Snyman" 
		);
		echo("<link href=\"http://".$_SERVER["SERVER_NAME"]."/basefunctions/scripts/manline.css\" media=\"all\" rel=\"stylesheet\" type=\"text/css\" />".PHP_EOL);
		echo("<p class=\"standard\">".PHP_EOL);
		echo("Get the list of active users: <br />".PHP_EOL);
		$sql = (string)"SELECT * FROM `users` WHERE `canlogin`=1 AND `isplace`=0 AND `isgeneric`=0 AND (ISNULL(`deleted`) OR `deleted`=0) AND `username`!='admin';";
		$users = $this->getTableManager()->runSql($sql);
		foreach ($users as $key=>$val) {
			$data = (array)array(
				"firstname"=>$val["firstname"],
				"lastname"=>$val["lastname"],
				"department_id"=>$val["deptid"],
				"jobtitle"=>$val["position"],
				"theme_id"=>1
			);
			foreach ($staffnumbers as $staffno=>$name) {
				if ($name === substr($val["firstname"], 0, 1)." ".$val["lastname"]) {
					$data["staffno"] = $staffno;
					break;
				}
			}
			$id = $this->getTableManager()->insert($data);
			$sql = (string)"UPDATE `users` SET `user_profiles_id`=".$id." WHERE `personid`=".$val["personid"];
			$update = $this->getTableManager()->runSql($sql);
			echo("<span title=\"User updated and profile inserted\">|</span>".PHP_EOL);
		}
		echo("<br /><br />".PHP_EOL);
		echo("Get the list of departments: ".PHP_EOL);
		$sql = (string)"SELECT * FROM `m3_departments`";
		$depts = $this->getTablemanager()->runSql($sql);
		echo("<br /><br />".PHP_EOL);
		echo("Get the data from `profiles`.`staff_details` table: <br />".PHP_EOL);
		$sql = (string)"SELECT * FROM `profiles`.`staff_details`;";
		$profiles = $this->getTableManager()->runSql($sql);
		foreach ($profiles as $profile) {
			$this->getTableManager()->setWhere(
				$this->getTableManager()->quoteString("`user_profiles`.`staffno`=?", $profile["staffno"])
			);
			$record = $this->getTableManager()->selectSingle();
			$data = (array)array(
				"location"=>$profile["location"],
				"birthday"=>$profile["birthday"],
				"interests"=>$profile["interests"],
				"family"=>$profile["family"],
				"aspirations"=>$profile["aspirations"],
				"goals"=>$profile["goals"],
				"quote"=>$profile["quote"],
				"createDate"=>date("Y-m-d H:i:s", $profile["date"])
			);
			if ($record) {
				$func = (string)"update";
				$this->getTableManager()->setWhere(
					$this->getTableManager()->quoteString("`user_profiles`.`id`=?", $record["id"])
				);
			} else {
				$func = (string)"insert";
				$data["staffno"] = $profile["staffno"];
				$data["firstname"] = substr($profile["name"], 0, strpos($profile["name"], " "));
				$data["lastname"] = substr($profile["name"], strrpos($profile["name"], " ")+1);
				$data["jobtitle"] = $profile["jobtitle"];
				foreach ($depts as $dept) {
					if ($dept["name"] == $profile["department"]) {
						$data["department_id"] = $dept["id"];
						break;
					}
				}
			}
			$this->getTableManager()->$func($data);
			$this->getTableManager()->setWhere(""); ## Clear any where statements *** TocTou ***
			echo("<span title=\"Profile Data updated\">|</span>".PHP_EOL);
		}
	}
	
	/** Maxine_Scaffold_Custom_Imports_User_profiles::__destuct()
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
new Maxine_Scaffold_Custom_Imports_User_profiles();