<?php
//: Preparation
$realPath = realpath(dirname(__FILE__));
$maxine	= substr($realPath, 0, strrpos($realPath, DIRECTORY_SEPARATOR));
$rootaccess = substr($maxine, 0, strrpos($maxine, DIRECTORY_SEPARATOR)+1);
defined("BASE") || define("BASE", $rootaccess);

include_once(BASE."basefunctions/localdefines.php");
include_once(BASE."basefunctions/dbcontrols.php");
include_once(BASE."basefunctions/baseapis/manapi.php");
include_once(BASE."Maxine/api/maxineapi.php");

require_once(BASE."basefunctions/baseapis/fleetDayHandler.php");

$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
$db_selected = mysql_select_db(DB_SCHEMA, $link);
//: End
//: Content
$fleetdayobj = new fleetDayHandler();
function dates_month($month, $year)
{
	$num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
	$dates_month=array();
	for($i=1;$i<=$num;$i++)
	{
                $mktime=mktime(0,0,0,$month,$i,$year);
                $date=date("d-M-Y",$mktime);
                $dates_month[$i]=$date;
        }
        return $dates_month;
}

$days = dates_month(date("m"), date("Y"));
foreach ($days as $day)
{
	if (date("d", strtotime($day)) > 31)
	{
		continue;
	}
	$fleetData = $fleetdayobj->pullT24FleetData(date("d", strtotime($day)));
	$fleet_trucks = (array)array();
	foreach ($fleetData as $row)
	{
		$fleet_trucks[$row["fleet_id"]][] = $row;
	}
	unset($fleetData);
	// print_r($fleet_trucks);
	$t24Budget = (array)array();
	foreach ($fleet_trucks as $fleetId => $truck)
	{
		$trucks = (string)"";
		foreach ($truck as $data)
		{
			$trucks .= $data["truck_id"].",";
		}
		$budgets = $fleetdayobj->getBudgetsInDateRange(date("Y-m-d", strtotime($day)), date("Y-m-d", strtotime($day)+86400), substr($trucks, 0, -1));
		// print_r($budgets);
		$t24Budget[$fleetId] = (array)array();
		$dateday = date("d", strtotime($day));
		$t24Budget[$fleetId][$dateday] = (array)array();
		$t24Budget[$fleetId][$dateday]["fleetid"] = $fleetId;
		if (!isset($t24Budget[$fleetId][$dateday]["budget"]))
		{
			$t24Budget[$fleetId][$dateday]["budget"] = (float)0;
		}
		if (!isset($t24Budget[$fleetId][$dateday]["budgetcontrib"]))
		{
			$t24Budget[$fleetId][$dateday]["budgetcontrib"] = (float)0;
		}
		if (!isset($t24Budget[$fleetId][$dateday]["budkms"]))
		{
			$t24Budget[$fleetId][$dateday]["budkms"] = (float)0;
		}
		if (!isset($blackoutcount))
		{
			$blackoutcount = (float)0;
		}
		$cdate = date("Y-m-d", strtotime($day));
		if (isset($budgets[$cdate][0]))
		{
			//print("OOK");
			if (isset($budgets[$cdate][0]["SUM(`income`)/100"]))
			{
				$t24Budget[$fleetId][$dateday]["budget"] = $budgets[$cdate][0]["SUM(`income`)/100"];
			}
			if (isset($budgets[$cdate][0]["SUM(`contribution`)/100"]))
			{
				$t24Budget[$fleetId][$dateday]["budgetcontrib"] = $budgets[$cdate][0]["SUM(`contribution`)/100"];
			}
			if (isset($budgets[$cdate][0]["SUM(`kms`)"]))
			{
				$t24Budget[$fleetId][$dateday]["budkms"] = $budgets[$cdate][0]["SUM(`kms`)"];
			}
		}
		$t24Budget[$fleetId][$dateday]["t24"] = 1;
		$t24Budget[$fleetId][$dateday]["day"] = $dateday;
		$t24Budget[$fleetId][$dateday]["date"] = strtotime($day);
		$t24Budget[$fleetId][$dateday]["blackouts"] = $blackoutcount;
	}
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_SCHEMA);
	foreach ($t24Budget as $fleet=>$data)
	{
		foreach ($data as $day)
		{
			// print_r($day);
			$sql = (string)"SELECT * FROM `fleet_scores` WHERE `fleetid`=".$day["fleetid"]." AND `date`=".$day["date"];
			//print($sql);exit;
			if ($result = $mysqli->query($sql))
			{
				$record = (array)array();
				while($obj = $result->fetch_array()){ 
					foreach ($obj as $key=>$val) {
						if (is_int($key) === TRUE)
						{
							unset($obj[$key]);
						}
					}
					$record = $obj;
				}
				
				/* free result set */
				$result->close();
			}
			// print_r($record);
			if (isset($record) && $record) { //: Update
				sqlCommit(array(
					"table"=>"fleet_scores",
					"where"=>"id=".$record["id"],
					"fields"=>$day
					));
			}
			else
			{
				sqlCreate(array(
					"table"=>"fleet_scores",
					"fields"=>$day
					));
			}
		}
	}
}
//: End
