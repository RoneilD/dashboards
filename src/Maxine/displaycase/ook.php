<?php
$fleetdayobj = new fleetDayHandler();
$budgets = $fleetdayobj->getAllBudgetsInDateRange(date("Y-m-01"), date("Y-m-01", strtotime("+1 month")));
// print_r($budgets);exit;
//: Get the days listed
$days = (array)array();
foreach ($budgets as $truck_id=>$budgetrow)
{
	//print_r($budgetrow);
	foreach ($budgetrow as $val) {
		$days[] = $val["date"];
	}
	break;
}
$t24Budget = (array)array();
foreach ($days as $day) {
	//: Get the trucks in a fleet on a specific day
	$date = intval(substr($day, strrpos($day, "-")+1));
	//echo $date;
	if ($date>1)
	{
		continue;
	}
	$trucksinafleet = $fleetdayobj->pullT24FleetData($date);
	print_r($trucksinafleet);
	//: End
	//: Sum the budget amounts per truck
	foreach ($trucksinafleet as $row)
	{
		//: Get the budget for this truck
		if (array_key_exists($row["truck_id"], $budgets))
		{
			$t24Budget[$row["fleet_id"]][$day] = array();
			$t24Budget[$row["fleet_id"]][$day]["fleetid"] = $row["fleet_id"];
			if (!isset($t24Budget[$row["fleet_id"]][$day]["budget"]))
			{
				$t24Budget[$row["fleet_id"]][$day]["budget"] = (float)0;
			}
			if (!isset($t24Budget[$row["fleet_id"]][$day]["budgetcontrib"]))
			{
				$t24Budget[$row["fleet_id"]][$day]["budgetcontrib"] = (float)0;
			}
			if (!isset($t24Budget[$row["fleet_id"]][$day]["budkms"]))
			{
				$t24Budget[$row["fleet_id"]][$day]["budkms"] = (float)0;
			}
			if (!isset($blackoutcount))
			{
				$blackoutcount = (float)0;
			}
			foreach ($budgets[$row["truck_id"]] as $bud)
			{
				$t24Budget[$row["fleet_id"]][$day]["budget"] += ($bud["income"]/100);
				$t24Budget[$row["fleet_id"]][$day]["budgetcontrib"] += ($bud["contribution"]/100);
				$t24Budget[$row["fleet_id"]][$day]["budkms"] += $bud["kms"];
			}
			$t24Budget[$row["fleet_id"]][$day]["day"] = substr($day, strrpos($day, "-")+1);
			$t24Budget[$row["fleet_id"]][$day]["date"] = strtotime($day);
			$t24Budget[$row["fleet_id"]][$day]["blackouts"] = $blackoutcount;
		}
		else
		{
			continue;
		}
		//: End
	}
	//: End
}
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_SCHEMA);
foreach ($t24Budget as $fleet=>$data)
{
	foreach ($data as $day)
	{
		print_r($day);
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
		//print_r($record);
		if (isset($record) && $record) { //: Update
			sqlCommit(array(
				"table"=>"fleet_scores",
				"where"=>"id=".$record["id"],
				"fields"=>$day
			));
		} else { //: Insert
			sqlCreate(array(
				"table"=>"fleet_scores",
				"fields"=>$day
			));
		}
	}
}
//: End
//: End
//: End
//: End
