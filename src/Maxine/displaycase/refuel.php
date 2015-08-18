<?php
//: Checks
if (isset($_POST['dashid']) === FALSE)
{
	syslog(LOG_INFO, 'Parameter dashid missing from posted variables in file refuel.php. Exiting.');
	return FALSE;
}

//: End
//: Get the Data
$times = substr_count($_SERVER['PHP_SELF'],"/");
$rootaccess	= "";
$i = 1;

while ($i < $times)
{
	$rootaccess .= "../";
	$i++;
}

define("BASE", $rootaccess);

include_once(BASE."/basefunctions/localdefines.php");
include_once(BASE."/basefunctions/dbcontrols.php");
include_once(BASE."/basefunctions/baseapis/manapi.php");
include_once(BASE."Maxine/api/maxineapi.php");

require_once(BASE."basefunctions/baseapis/fleetDayHandler.php");

$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_SCHEMA) or die(mysql_error());

$fleetdayobj = new fleetDayHandler();

$fleet = $fleetdayobj->getFleetById(substr($_POST['dashid'], 1));
//: Missing and open refuels
$sql = (string)'SELECT * FROM `refuels` WHERE `fleet_id`='.(int)$fleet['maxid'];
$refuels = sqlQuery($sql);
if ($refuels === FALSE)
{
	syslog(LOG_INFO, 'Query for unauthorized refuels returned FALSE.');
}
//: End
//: Unauthorized Refuels
$sql = (string)'SELECT * FROM `unauthorized_refuels` WHERE `fleet_id`='.(int)$fleet['maxid'];
$unauth = sqlQuery($sql);
if ($unauth === FALSE)
{
	syslog(LOG_INFO, 'Query for unauthorized refuels returned FALSE.');
}
//: End
//: End
//: Output to Screen
print('<br /><br /><br />');
//: End
print('<div id="blackouts">');
//: Page
print('<div class="upper" style="height:15%;">');
print('<div class="title">');
print('<h2>OCD Dashboard</h2>');
print('<p class="datetime">'.date("d F H:i").'</p>');
print('<p class="datetime">Reports run from '.date("d F", strtotime('-30 days')).'</p>');
print('</div>');
print('<div class="large">');
print('<p class="caption" style="font-size:70px;">'.$fleet['name'].'</p>');
print('</div>');
print('</div>');
//: End
//: Title
$style = (string)'margin-bottom:50px;width:25%;';
print('<div class="fleetWrapper" style="height:85%;">');
print('<ul>');
//: Column 1
print('<li style="'.$style.'">');
print('<span class="value">'.(array_key_exists('total_open_count', $refuels[0]) ? $refuels[0]['total_open_count'] : '').'</span>');
print('<span class="label">Total No. Open Refuels</span>');
print('</li>');
//: End
//: Column 2
print('<li style="'.$style.($refuels[0]['open_count'] > 5 ? 'background-color:#F00;' : '').'">');
print('<span class="value">'.(array_key_exists('open_count', $refuels[0]) ? $refuels[0]['open_count'] : '').'</span>');
print('<span class="label">Open Refuels Exceeding Time Limit</span>');
print('</li>');
//: End
//: Column 3
print('<li style="'.$style.($refuels[0]['missing_count'] > 5 ? 'background-color:#F00;' : '').'">');
print('<span class="value">'.(array_key_exists('missing_count', $refuels[0]) ? $refuels[0]['missing_count'] : '').'</span>');
print('<span class="label">Missing Refuels</span>');
print('</li>');
//: End
//: Column 4
print('<li style="'.$style.'">');
print('<span class="value">'.(array_key_exists('count', $unauth[0]) ? $unauth[0]['count'] : '').'</span>');
print('<span class="label">Refuels at Unauthorised Locations</span>');
print('</li>');
//: End
//: Column 1
print('<li style="'.$style.'">');
print('<span class="value" style="border:none;"></span>');
print('<span class="label"></span>');
print('</li>');
//: End
//: Column 2
print('<li style="'.$style.'">');
print('<span class="value">'.(array_key_exists('open_time', $fleet) ? (($fleet['open_time']/60)/60).'h' : '').'</span>');
print('<span class="label">Open Refuel Time Limit</span>');
print('</li>');
//: End
//: Column 3
print('<li style="'.$style.'">');
print('<span class="value">'.(array_key_exists('kms_limit', $fleet) ? $fleet['kms_limit'] : '').'</span>');
print('<span class="label">Missing Refuels KM Limit</span>');
print('</li>');
//: End
//: Column 4
print('<li style="'.$style.'">');
print('<span class="value">'.(array_key_exists('litres', $unauth[0]) ? 'R '.number_format($unauth[0]['litres']*0.29, 2) : '').'</span>');
print('<span class="label">Rebate Savings Lost</span>');
print('</li>');
//: End
print('</ul>');
print('</div>');
//: End
print('</div>');