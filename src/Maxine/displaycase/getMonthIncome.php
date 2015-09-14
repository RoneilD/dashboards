<?php
//: Preparation and includes
$realPath = realpath(dirname(__FILE__));
$maxine = substr($realPath, 0, strrpos($realPath, DIRECTORY_SEPARATOR));
$rootaccess = substr($maxine, 0, strrpos($maxine, DIRECTORY_SEPARATOR)+1);
defined('BASE') || define("BASE", $rootaccess);

include_once(BASE."basefunctions/localdefines.php");
include_once(BASE."basefunctions/dbcontrols.php");
include_once(BASE."basefunctions/baseapis/manapi.php");
include_once(BASE."Maxine/api/maxineapi.php");
include_once(BASE."basefunctions/baseapis/fleetDayHandler.php");
//: End
/** getMonthIncome.php
 * @package getMonthIncome
 * @author Feighen Oosterbroek <foosterbroek@bwtsgroup>
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
class getMonthIncome 
{
	//: Variables
	
	//: End
	//: Public functions
	//: Magic
	/** getMonthIncome::__construct()
	 * Class constructor
	 */
	public function __construct()
	{
		//: This doesn't need to happen at all if we aren't after the third of the month
		if (date('d') < 2)
		{
			syslog(LOG_INFO, "No need to run, as it is before the third of the month");
			exit;
		}
		$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_SCHEMA) or die(mysqli_error($link));
		$fleetdayobj = new fleetDayHandler();
		for ($i=1; $i<=date('d', strtotime('-2 days')); $i++) {
			print('Pulling for day: '.$i.PHP_EOL);
			$fleetscore = $fleetdayobj->pullFleetDay($i);
			$fleetdayobj->saveFleetDay($fleetscore);
		}
	}
	
	/** getMonthIncome::__destruct()
	 * Class destructor
	 * Allow for garbage collection
	 */
	public function __destruct()
	{
		unset($this);
	}
	//: End
	//: End
}
$getMonthIncome = new getMonthIncome();