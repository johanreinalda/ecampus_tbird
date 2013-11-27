<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    block
 * @subpackage ecampus_tbird
 * @copyright  2013 onward Johan Reinalda {@link http://www.thunderbird.edu}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;


function xmldb_block_ecampus_tbird_install() {
	// cache definition.
	// This defines an application wide cache, useable by every user
	// It becomes callable as
	// $cache = cache:make('block_ecampus_tbird','books');

	$definitions = array(
		//'persistent' => true,
		//'staticacceleration' => true	// same as persistent after 2.4.6
	);
	cache::make_from_params(cache_store::MODE_APPLICATION, 'block_ecampus_tbird', 'books', $definitions);
}
