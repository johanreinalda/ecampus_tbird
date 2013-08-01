<?php
//pass through to eCampus bookstore

require_once('../../config.php');
require_once('lib.php');

//during development
error_reporting(E_ALL);
ini_set('display_errors','stdout');
ini_set('display_startup_errors', TRUE);

$courseid = optional_param('courseid', 0, PARAM_INT);		// this is optional, the course->idnumber

require_login();
$username = $USER->username;

$PAGE->set_url('/blocks/ecampus_tbird/passthrough.php', array('courseid' => $courseid));
$PAGE->set_pagelayout('base');

//get the course and check that user has access
if($courseid <> 0) {
	$course = $DB->get_record('course', array('id'=>$courseid), '*', MUST_EXIST);
	require_login($course);

	if ($course->id == SITEID) {	//should not happen because block() applicable_formats.
		error('eCampus access only works in courses');
	}
}

//get the eCampus pass-through temporary access code
$error;
$accesscode = get_eCampus_accesscode($username,&$error);

//and now render the page with the login form
if($accesscode) {
	$PAGE->set_title(get_string('ecampuslogin','block_ecampus_tbird'));
	echo $OUTPUT->header();
	echo '<p>' . get_string('redirectfollowsshortly', 'block_ecampus_tbird') . '</p>';
	echo render_eCampus_login($username,$accesscode,$courseid);
} else {
	// unrecoverable errors have occured
	$PAGE->set_title(get_string('errorpagetitle','block_ecampus_tbird'));
	echo $OUTPUT->header();
	echo '<p>' . get_string('erroroccured','block_ecampus_tbird') . '<p>';
	echo '<p>' . get_string('errorcontactadmin','block_ecampus_tbird') . '<p>';
	echo '<p><font color="red">' . $error . '</font></p>';
}
echo $OUTPUT->footer();
exit;