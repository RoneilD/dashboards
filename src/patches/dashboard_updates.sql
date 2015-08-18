/** dashboard_updates.sql
 * @package dashboard_updates
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

/* Sliders */
DROP TABLE IF EXISTS `sliders`;
CREATE TABLE IF NOT EXISTS `sliders` (
	`id` INT(150) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
	`name` VARCHAR(255) NOT NULL DEFAULT "DEFAULT",
	`users_id` INT(150) NOT NULL DEFAULT 1,
	INDEX (`users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

ALTER TABLE `sliders` AUTO_INCREMENT=25000;

/* Slider Fleets */
DROP TABLE IF EXISTS `sliders_fleets`;
CREATE TABLE IF NOT EXISTS `sliders_fleets` (
	`id` INT(150) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
	`slider_id` INT(150) NOT NULL DEFAULT 1,
	`fleet_id` INT(150) NOT NULL DEFAULT 1,
	INDEX (`slider_id`, `fleet_id`),
	INDEX (`slider_id`),
	INDEX (`fleet_id`),
	foreign key (`slider_id`) references `sliders` (`id`) on delete cascade
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

/* Unauthorized Refuels */
DROP TABLE IF EXISTS `unauthorized_refuels`;
CREATE TABLE IF NOT EXISTS `unauthorized_refuels` (
	`id` INT(150) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
	`fleet_id` INT(150) NOT NULL DEFAULT 1,
	`count` INT(150) NOT NULL DEFAULT 0,
	`litres` FLOAT(150,4) NOT NULL DEFAULT 0,
	INDEX (`count`, `fleet_id`),
	INDEX (`count`),
	INDEX (`fleet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

/* Open Refuels */
DROP TABLE IF EXISTS `refuels`;
CREATE TABLE IF NOT EXISTS `refuels` (
	`id` INT(150) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
	`fleet_id` INT(150) NOT NULL DEFAULT 1,
	`fleet_count` INT(150) NOT NULL DEFAULT 0,
	`missing_count` INT(150) NOT NULL DEFAULT 0,
	`open_count` INT(150) NOT NULL DEFAULT 0,
	INDEX (`open_count`, `missing_count`, `fleet_id`),
	INDEX (`missing_count`),
	INDEX (`open_count`),
	INDEX (`fleet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;