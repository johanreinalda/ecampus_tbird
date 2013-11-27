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
 * purgecache.php page allows for purging/deletion of the book list cache used in this block
 *
 * @package    block_ecampus_tbird
 * @copyright  2013 Thunderbird School of Global Management
 * @author     2013 Johan Reinalda
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

require_once('../../config.php');
require_once('lib.php');

$mcourseid = optional_param('courseid', 0, PARAM_INT);		// this is optional, the Moodle $course->id

require_login();

$PAGE->set_url('/blocks/ecampus_tbird/purgecache.php', array('courseid' => $mcourseid));
//$PAGE->set_pagelayout('base');
$PAGE->set_pagelayout('admin');
$context = get_context_instance(CONTEXT_SYSTEM);
$PAGE->set_context($context);
$PAGE->set_title(get_string('purgecachetitle','block_ecampus_tbird'));

//are we alllow to purge the cache ?
if (has_capability('block/ecampus_tbird:purgecache', $context)) {
	// access the cache store that created during install in db/caches.php
	$cache = cache::make_from_params(cache_store::MODE_APPLICATION, 'block_ecampus_tbird', 'books');
	$cache->purge();
	unset($cache);
	$msg = get_string('cachepurged','block_ecampus_tbird');
	$PAGE->navbar->add(get_string('blocks'));
	$settingsurl = new moodle_url('/admin/settings.php?section=blocksettingecampus_tbird');
	$PAGE->navbar->add(get_string('pluginname', 'block_ecampus_tbird'), $settingsurl);
	add_to_log(0, 'ecampus_tbird','purgecache','blocks/ecampus_tbird/README.TXT','eCampus Cache Purged');
	eCampus_log($USER->username . ': ' . $msg);
	eCampus_debug($USER->username . ': ' . $msg);
} else {
	$msg = get_string('accessdenied','block_ecampus_tbird');
}

echo $OUTPUT->header();
echo '<p>' . $msg . '</p>';
echo $OUTPUT->footer();
exit;