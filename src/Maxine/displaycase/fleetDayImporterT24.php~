<?PHP
$starttimer = date("U");
//: Preparation
$realPath = realpath(dirname(__FILE__));
$maxine = substr($realPath, 0, strrpos($realPath, DIRECTORY_SEPARATOR));
$rootaccess = substr($maxine, 0, strrpos($maxine, DIRECTORY_SEPARATOR)+1);
define("BASE", $rootaccess);

include_once(BASE."basefunctions/localdefines.php");
include_once(BASE."basefunctions/dbcontrols.php");
include_once(BASE."basefunctions/baseapis/manapi.php");
include_once(BASE."Maxine/api/maxineapi.php");

require_once(BASE."basefunctions/baseapis/fleetDayHandler.php");

$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
$db_selected = mysql_select_db(DB_SCHEMA, $link);
//: End

$fleetdayobj = new fleetDayHandler();
$today = date("d");

$customday = 0;

if($customday > 0)
{
	$fleetscore	= $fleetdayobj->pullFleetDayT24($customday);
	$fleetdayobj->saveFleetDay($fleetscore);
	
	print("<pre style='font-family:verdana;font-size:13'>");
	print_r($fleetscore);
	print("</pre>");
} //: anglo-subbies : Anglo - Sub Contractor
else
{
	print("Pulling today.".PHP_EOL);
	$fleetscore = $fleetdayobj->pullFleetDayT24($today);
	if ($fleetscore === FALSE)
	{
		//: broken
		print("No data recieved".PHP_EOL);
		exit;
	}
	$fleetdayobj->saveFleetDay($fleetscore);
	
	if($today > 1)
	{
		print("Pulling yesterday.".PHP_EOL);
		$fleetscore = $fleetdayobj->pullFleetDayT24(($today - 1));
		if ($fleetscore === FALSE)
		{
			//: broken
			print("No data recieved".PHP_EOL);
			exit;
		}
		$fleetdayobj->saveFleetDay($fleetscore);
	}
	
	$backday = $fleetdayobj->findT24BackDay($today);
	print("Backday: ".$backday.", Today: ".$today."<br>");
	if($backday > 0)
	{
		print("Pulling from further back.".PHP_EOL);
		$fleetscore = $fleetdayobj->pullFleetDayT24($backday);
		if ($fleetscore === FALSE)
		{
			//: broken
			print("No data recieved".PHP_EOL);
			exit;
		}
		$fleetdayobj->saveFleetDay($fleetscore);
	}
}

$endtimer = date("U");
$totaltimer = $endtimer - $starttimer;
print("That took ".$totaltimer." seconds.".PHP_EOL);
