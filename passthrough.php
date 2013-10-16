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
 * Single Sign On hand-off routines for the eCampus interface
 *
 * @package   block_ecampus_tbird
 * @copyright  2013 Thunderbird School of Global Management
 * @author     2013 Johan Reinalda
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

//pass through to eCampus bookstore

require_once('../../config.php');
require_once('lib.php');

//during development
//error_reporting(E_ALL);
//ini_set('display_errors','stdout');
//ini_set('display_startup_errors', TRUE);

$mcourseid = optional_param('courseid', 0, PARAM_INT);		// this is optional, the Moodle $course->id

require_login();

$PAGE->set_url('/blocks/ecampus_tbird/passthrough.php', array('courseid' => $mcourseid));
$PAGE->set_pagelayout('base');

//figure out what user attribute we pass as studentid to eCampus
$useridtype = get_config('block_ecampus_tbird','configuseridtype');
switch($useridtype) {
	case 'idnumber':
		if($USER->idnumber === '') {
			// unrecoverable errors have occured
			$PAGE->set_context(get_context_instance(CONTEXT_SYSTEM));
			$PAGE->set_title(get_string('errorpagetitle','block_ecampus_tbird'));
			echo $OUTPUT->header();
			//the textual error explanations
			$errorheader = get_string('erroroccured','block_ecampus_tbird');
			$error = get_string('erroruseridnumbernotset','block_ecampus_tbird');
			//call generic error rendering
			echo render_eCampus_error($errorheader,$error);
			//and log this
			add_to_log($mcourseid, 'ecampus_tbird','error','blocks/ecampus_tbird/README.TXT',$error);
			echo $OUTPUT->footer();
			exit;
		}
		$studentid = $USER->idnumber;
		break;
	case 'email':
		//mandatory fields, not error checking needed!
		$studentid = $USER->email;
		break;
	case 'username':
		$studentid = $USER->username;
		break;
}

//get the course and check that user has access
$courseid = 0;	//eCampus courseid parameter, see API doc
if($mcourseid <> 0) {	//did we pass in a Moodle courseid ?
	$course = $DB->get_record('course', array('id'=>$mcourseid), '*', MUST_EXIST);
	require_login($course);	//make sure user has access to this course

	if ($course->id == SITEID) {	//should not happen because block() applicable_formats.
		error('eCampus access only works in courses');
	}
	//figure out what we Moodle course attribute we pass as courseid to eCampus
	$courseidtype = get_config('block_ecampus_tbird','configcourseidtype');
	if($courseidtype === 'idnumber') {
		//idnumber means we pass in the external SA system $course->idnumber, NOT $course->id
		if(!empty($course->idnumber))
			$courseid = $course->idnumber;
	} else {
		//shortname is only other option at this time
		$courseid = $course->shortname;
	}
} else {
	//coming from the My Moodle page! (most likely)
	//we need to set context manually (above is set by require_login($course))
	$PAGE->set_context(get_context_instance(CONTEXT_SYSTEM));
}

//get the eCampus pass-through temporary access code
$error;
$accesscode = get_eCampus_accesscode($studentid,&$error);

//and now render the page with the login form
if($accesscode) {
	$PAGE->set_title(get_string('ecampuslogin','block_ecampus_tbird'));
	echo $OUTPUT->header();
	echo '<p>' . get_string('redirectfollowsshortly', 'block_ecampus_tbird') . '</p>';
	echo render_eCampus_login($studentid,$accesscode,$courseid);
	add_to_log($mcourseid, 'ecampus_tbird','login','blocks/ecampus_tbird/README.TXT','eCampus Login');
} else {
	// unrecoverable errors have occured
	$PAGE->set_title(get_string('errorpagetitle','block_ecampus_tbird'));
	echo $OUTPUT->header();
	echo render_eCampus_error(get_string('erroroccured','block_ecampus_tbird'),$error);
	add_to_log($mcourseid, 'ecampus_tbird','error','blocks/ecampus_tbird/README.TXT',substr($error,0,200));
}
echo $OUTPUT->footer();
exit;