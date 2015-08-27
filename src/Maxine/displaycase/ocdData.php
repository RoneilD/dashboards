<?php
/** ocdData.php
 * @package ocdData
 * @author Feighen Oosterbroek <foosterbroek@bwtrans.co.za>
 * @copyright 2013 onwards Barloworld Transport Solutions
 * @license GNU GPL
 * @link http://www.gnu.org/licenses/gpl.html
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
function ocdData()
{
	//: Preparation
	$times = substr_count($_SERVER['PHP_SELF'],"/");
	$rootaccess	= "";
	$i = 1;
	
	while ($i < $times)
	{
		$rootaccess .= "../";
		$i++;
	}
	
	defined('BASE') || define("BASE", $rootaccess);
	
	include_once(BASE."/basefunctions/localdefines.php");
	include_once(BASE."/basefunctions/dbcontrols.php");
	include_once(BASE."/basefunctions/baseapis/manapi.php");
	include_once(BASE."Maxine/api/maxineapi.php");
	
	require_once(BASE."basefunctions/baseapis/fleetDayHandler.php");
	
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_SCHEMA) or die(mysqli_error($link));
	
	$fleetdayobj = new fleetDayHandler();
	$fleetlist = (array)array();
	$tmp = $fleetdayobj->getIncomeFleets();
	foreach ($tmp as $key=>$val) {
		$sorted[$val['maxid']] = $val["name"];
	}
	asort($sorted, SORT_STRING);
	foreach ($sorted as $key=>$val) {
		$fleetlist[$key] = $sorted[$key];
	}
	unset($sorted);
	unset($tmp);
	
	$sql = (string)'SELECT `r`.`fleet_id`, `missing_count`, `open_count`, `total_open_count`, `count`, `litres` FROM `refuels` AS `r` INNER JOIN `unauthorized_refuels` AS `u` ON `r`.`fleet_id`=`u`.`fleet_id`';
	if (isset($_POST) && isset($_POST['conf']) && $_POST['conf'])
	{
		if (isset($_POST['conf']['fleetid']) && $_POST['conf']['fleetid'])
		{
			$data = __sanitizeData(array(0=>$_POST['conf']['fleetid']));
			$sql .= ' WHERE `r`.`fleet_id`='.$data[0];
		}
	}
	$fleetid = (int)(((isset($_POST['conf']) && $_POST['conf']) && (isset($_POST['conf']['fleetid']) && $_POST['conf']['fleetid'])) ? $_POST['conf']['fleetid'] : 0);
	$data = sqlQuery($sql);
	//: End
	print('<!DOCTYPE html>');
	print('<head>');
	print('<meta charset="utf-8">');
	print('<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">');
	print('<title>Dashboards - Barloworld Transport</title>');
	print('<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />');
	print('<link href="favicon.ico" rel="shortcut icon" />');
	print('<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">');
	print('<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">');
	print('<link rel="stylesheet" href="'.BASE.'Maxine/displaycase/content/site/css/fonts.css">');
	print('<link rel="stylesheet" href="'.BASE.'Maxine/displaycase/content/site/css/main.css">');
	print('<script src="'.BASE.'Maxine/displaycase/content/site/js/vendor/modernizr-2.6.2.min.js"></script>');
	print('<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>');
	print('<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>');
	print('<!--[if lt IE 9]>');
	print('<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7/html5shiv.min.js"></script>');
	print('<script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.js"></script>');
	print('<![endif]-->');
	print('<script src="'.BASE.'Maxine/displaycase/content/site/js/vendor/jcircle.js"></script>');
	print('<script src="'.BASE.'Maxine/displaycase/content/site/js/vendor/jquery.flot/jquery.flot.js"></script>');
	print('<script src="'.BASE.'Maxine/displaycase/content/site/js/vendor/jquery.flot/jquery.flot.time.js"></script>');
	print('<script src="'.BASE.'Maxine/displaycase/content/site/js/vendor/jquery.flot/jquery.flot.tooltip.js"></script>');
	print('</head>'.PHP_EOL);
	print('<body>');
	print('<div id="root"></div>');
	print('<div id="page" style="overflow-y:auto;">');
	print('<header>');
	print('<div class="controlsWrapper">');
	print('<a href="#" class="menu"></a>');
	print('</div><!-- controlsWrapper -->');
	print('</header>');
	print('<nav>');
	print('<ul>');
	print('<li><a href="/?personal">Dashboard</a></li>');
	print('<li><a href="/?mydashdetails">Dashboard Builder</a></li>');
	print('<li><a href="/?importfleetday">Import Day</a></li>');
	print('<li><a href="/?checkfleetscoreupdates">Fleet Scores</a></li>');
	print('<li><a href="/?ocddata">OCD Data</a></li>');
	print('<li><a href="/?logout">Logout</a></li>');
	print('</ul>');
	print('</nav>');
	//: Page Content
	print('<div id="blackouts">');
	//: Form
	print('<div class="fleetWrapper" style="height:10%;">');
	print('<form method="POST">');
	print('<table><tbody><tr>');
	print('<td>'); //: Col 1
	print('<label for="conf[fleetid]">Fleet:</label>');
	print('<select id="conf[fleetid]" name="conf[fleetid]" value="'.$fleetid.'">');
	print('<option value="0" '.($fleetid==0?"selected":"").'>All</option>');
	foreach ($fleetlist as $fleetkey=>$fleetval) {
		if (!$fleetval)
		{
			continue;
		}
		print('<option value="'.$fleetkey.'"'.($fleetid==$fleetkey ? ' selected="selected"' : '').'>'.$fleetval.'</option>');
	}
	print('</select>');
	print('</td>');
	print('<td>'); //: Col 2
	print('<input type="Submit" value="Search" />');
	print('</td>');
	print('</tr></tbody></table>');
	print('</form>');
	print('</div>');
	print('<div class="fleetWrapper" style="height:90%;">');
	print('<table style="margin-bottom:20px;">');
	if ($data)
	{
		$cols = (array)array(
			'fleet_id'=>'Fleet',
			'missing_count'=>'Missing count',
			'total_open_count'=>'Total open',
			'open_count'=>'Open above fleet limit',
			'count'=>'Unauthorized count',
			'litres'=>'Litres'
		);
		print('<thead><tr>');
		foreach ($cols as $key=>$val)
		{
			print('<td>'.$val.'</td>');
		}
		print('</tr></thead>');
		print('<tbody>');
		foreach ($data as $row)
		{
			//: Skip rows that do not have an active fleet
			if (array_key_exists($row['fleet_id'], $fleetlist) === FALSE)
			{
				continue;
			}
			print('<tr>');
			foreach ($cols as $key=>$val)
			{
				print('<td>');
				print(($key == 'fleet_id' ? $fleetlist[$row[$key]] : $row[$key]));
				print('</td>');
			}
			print('</tr>');
		}
		print('</tbody>');
	}
	else
	{
		print('<tbody><tr><td>No results to show</td></tr></tbody>');
	}
	print('</table>');
	print('</div>');
	//: End
	print('</div>');
	//: End
	//: End Page
	print('<script>window.jQuery || document.write("<script src=\"'.BASE.'Maxine/displaycase/content/site/js/vendor/jquery-1.9.1.min.js\"><\/script>")</script>');
	print('<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-color/2.1.2/jquery.color.min.js"></script>');
	print('<script src="'.BASE.'Maxine/displaycase/content/site/js/plugins.js"></script>');
	print('<script src="'.BASE.'Maxine/displaycase/content/site/js/styling.js"></script>');     
	print('<script src="'.BASE.'Maxine/displaycase/content/site/js/main.js"></script>');
	print('<script type="text/javascript" language="javascript" src="'.BASE.'/basefunctions/scripts/manline.js"></script>');
	print('</body>'.PHP_EOL);
	print('</html>');
}