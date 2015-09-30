<?php
/** ImportFleetDay.php
 * @package ImportFleetDay
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
/** ImportFleetDay
 * This function imports a single day of Income into the maxinedb.fleet_scores table
 */
function ImportFleetDay()
{
	$start = microtime(TRUE);
	//: Preparation
	$times = substr_count($_SERVER['PHP_SELF'],"/");
	$rootaccess	= "";
	$i = 1;
	
	while ($i < $times)
	{
		$rootaccess .= "../";
		$i++;
	}
	set_time_limit(0);
	defined('BASE') || define("BASE", $rootaccess);
	
	include_once(BASE."/basefunctions/localdefines.php");
	include_once(BASE."/basefunctions/dbcontrols.php");
	include_once(BASE."/basefunctions/baseapis/manapi.php");
	include_once(BASE."Maxine/api/maxineapi.php");
	
	require_once(BASE."basefunctions/baseapis/fleetDayHandler.php");
	
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_SCHEMA) or die(mysqli_error($link));
	
	$fleetdayobj = new fleetDayHandler();
	$startdate = (int)(((isset($_POST['conf']) && $_POST['conf']) && (isset($_POST['conf']['date']) && $_POST['conf']['date'])) ? strtotime(preg_replace('/\//', '-', $_POST['conf']['date'])) : strtotime(date('Y-m-01')));
	//: End
	print('<!DOCTYPE html>');
	print('<head>');
	print('<meta charset="utf-8">');
	print('<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">');
	print('<title>Dashboards - Barloworld Transport</title>');
	print('<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />');
	print('<link href="favicon.ico" rel="shortcut icon" />');
	print('<link rel="stylesheet" href="'.BASE.'basefunctions/scripts/bootstrap.min.css">');
	print('<link href="'.BASE.'basefunctions/scripts/font-awesome.min.css" rel="stylesheet">');
	print('<link rel="stylesheet" href="'.BASE.'Maxine/displaycase/content/site/css/fonts.css">');
	print('<link rel="stylesheet" href="'.BASE.'Maxine/displaycase/content/site/css/main.css">');
	print('<script src="'.BASE.'Maxine/displaycase/content/site/js/vendor/modernizr-2.6.2.min.js"></script>');
	print('<script src="'.BASE.'basefunctions/scripts/jquery.min.js"></script>');
	print('<script src="'.BASE.'basefunctions/scripts/jquery.ui.touch-punch.min.js"></script>');
	print('<!--[if lt IE 9]>');
	print('<script src="'.BASE.'basefunctions/scripts/html5shiv.min.js"></script>');
	print('<script src="'.BASE.'basefunctions/scripts/respond.js"></script>');
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
	print('<label for="conf[date]">Date:</label>');
	print('<input id="conf[date]" name="conf[date]" value="'.date("d/m/Y", $startdate).'" readonly style="width: 160px; text-align: center;">');
	print('<img src="'.BASE.'/images/calendar.png" style="cursor:pointer" onClick="displayDatePicker(\'conf[date]\', this, \'dmy\', \'\');">');
	print('</td>');
	print('<td>'); //: Col 2
	print('<input type="Submit" value="Import" />');
	print('</td>');
	print('</tr></tbody></table>');
	print('</form>');
	print('</div>');
	print('<div class="fleetWrapper" style="height:90%;">');
	print('<table><tbody><tr><td>');
	if (isset($_POST) && $_POST)
	{
		print('Importing for date: '.date('Y-m-d', $startdate).'<br />');
		$day = date('d', $startdate);
		$fleetscore = $fleetdayobj->pullFleetDay($day);
		$fleetdayobj->saveFleetDay($fleetscore);
		$end = microtime(TRUE);
		$time = $end - $start;
		print('Import completed in :'.($time/60));
	}
	print('</td></tr></tbody></table>');
	print('</div>');
	//: End
	print('</div>');
	//: End
	//: End Page
	print('<script>window.jQuery || document.write("<script src=\"'.BASE.'Maxine/displaycase/content/site/js/vendor/jquery-1.9.1.min.js\"><\/script>")</script>');
	print('<script src="'.BASE.'basefunctions/scripts/jquery.color.min.js"></script>');
	print('<script src="'.BASE.'Maxine/displaycase/content/site/js/plugins.js"></script>');
	print('<script src="'.BASE.'Maxine/displaycase/content/site/js/styling.js"></script>');     
	print('<script src="'.BASE.'Maxine/displaycase/content/site/js/main.js"></script>');
	print('<script type="text/javascript" language="javascript" src="'.BASE.'/basefunctions/scripts/manline.js"></script>');
	print('</body>'.PHP_EOL);
	print('</html>');
}