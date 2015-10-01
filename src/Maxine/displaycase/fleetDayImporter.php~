<?php
$starttimer = date("U");
//: Test to see if it is already running
/* 
* ps -> list the processes
* grep -> filter
* cut -> get the process_id field
* wc -> get a count 
*/
//$pid = trim(shell_exec("ps aux | grep fleetDayImporter | cut -d\" \" -f 3 | wc -l"));
//if(($pid > 8)) {
//	syslog(LOG_INFO, "FleetDayImporter.php is already running.");
//	return FALSE;
//}
//: End
//: Preparation
$realPath = realpath(dirname(__FILE__));
$maxine = substr($realPath, 0, strrpos($realPath, DIRECTORY_SEPARATOR));
$rootaccess = substr($maxine, 0, strrpos($maxine, DIRECTORY_SEPARATOR)+1);
defined('BASE') || define("BASE", $rootaccess);

include_once(BASE."basefunctions/localdefines.php");
include_once(BASE."basefunctions/dbcontrols.php");
include_once(BASE."basefunctions/baseapis/manapi.php");
include_once(BASE."Maxine/api/maxineapi.php");
include_once(BASE."basefunctions/baseapis/fleetDayHandler.php");

$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_SCHEMA) or die(mysqli_error($link));
//: End
//: Content
$fleetdayobj = new fleetDayHandler();
$today	= date("d");

$day = (int)0;
if (array_key_exists(1, $argv))
{
	$day = (int)$argv[1];
}

$customday = (int)0;
if ($day)
{
	$customday = $day;
}

if($customday > 0) {
	$fleetscore = $fleetdayobj->pullFleetDay($customday);
	$fleetdayobj->saveFleetDay($fleetscore);
	
	print("<pre style='font-family:verdana;font-size:13'>");
	print_r($fleetscore);
	print("</pre>");
} else {
	print("Pulling today.<br>");
	$fleetscore = $fleetdayobj->pullFleetDay($today);
	$fleetdayobj->saveFleetDay($fleetscore);
	
	if($today > 1) {
		print("Pulling yesterday.<br>");
		$fleetscore = $fleetdayobj->pullFleetDay(($today - 1));
		$fleetdayobj->saveFleetDay($fleetscore);
	}
	
	/* $backday = $fleetdayobj->findBackDay((int)$today);
	print("Backday: ".$backday.", Today: ".$today."<br>");
	if($backday > 0) {
	print("Pulling from further back.<br>");
	$fleetscore = $fleetdayobj->pullFleetDay($backday);
	$fleetdayobj->saveFleetDay($fleetscore);
	} */
}

$endtimer = date("U");
$totaltimer = $endtimer - $starttimer;
print("That took ".$totaltimer." seconds.");
//: End