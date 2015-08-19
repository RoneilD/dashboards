<?php
/** unauthRefuels.php
 * @package unauthRefuels
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
// Defines and includes {
	$realPath = realpath(dirname(__FILE__));
	$maxine = substr($realPath, 0, strrpos($realPath, DIRECTORY_SEPARATOR));
	$rootaccess = substr($maxine, 0, strrpos($maxine, DIRECTORY_SEPARATOR)+1);
	define("BASE", $rootaccess);
	
	include_once(BASE."/basefunctions/localdefines.php");
	include_once(BASE."/basefunctions/dbcontrols.php");
	include_once(BASE."/basefunctions/baseapis/manapi.php");
	include_once(BASE."Maxine/api/maxineapi.php");
	
	require_once(BASE."basefunctions/baseapis/fleetDayHandler.php");
	
	$link			= mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_SCHEMA) or die(mysqli_error($link));
	
	$fleetdayobj = new fleetDayHandler;
// }
$fleetdayobj->getUnauthorizedRefuels();

# B@r!0W0r!d

# /var/www/htdocs/mobilize/vendor/Kaluma/Max/scripts/ortit.php -t 'BWTS Group' -r 'Feighen Oosterbroek' -f '2.T24 - Timber24' -f '2.Freight SA (Own)' -f '2.Freight Africa' -f '2.Freight Subcontractors Consolidated' -f '2.Manline Mega' -f '2.Energy' -f '2.Construction' -f '2.Environmental' -f '2.Commercial' -f '2.Agriculture' -f '2.Mining'