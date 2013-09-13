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
 * library routines for the eCampus interface
 *
 * @package   block_ecampus_tbird
 * @copyright  2013 Thunderbird School of Global Management
 * @author     2013 Johan Reinalda
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('ECAMPUS_ACCESS_URL','https://www.ecampus.com/myaccount/generate-access-code.asp');
define('ECAMPUS_SSO_POST_URL','https://www.ecampus.com/myaccount/default.asp');
define('ECAMPUS_ACCESS_CODE_LEN',42);
define('DATEFORMAT','Y/m/d G:i:s');

/**
 * 
 * @param string $studentid - the student id as known to eCampus, could be moodle username, studentid or email
 * @param string $error - a string returning an error from eCampus call.
 * @return string - the 42 character access code as returned from eCampus.
 */
function get_eCampus_accesscode($studentid,&$error)
{
	$log = 'user ' . $studentid;
	$debug = $log;
		
	//new curl resource
	$c = curl_init();
	
	// set URL, etc.
	$schoolid =  get_config('block_ecampus_tbird','schoolid');
	$schoolsecret = get_config('block_ecampus_tbird','sharedsecret');
	$url = ECAMPUS_ACCESS_URL . '?s=' . $schoolid. '&k=' . $schoolsecret . '&studentid=' . $studentid;
	$debug .= "\n   URL: " . $url;
	
	//bind url to connection
	curl_setopt($c, CURLOPT_URL, $url);
	//set timeout (in seconds)
	curl_setopt($c, CURLOPT_CONNECTTIMEOUT, get_config('block_ecampus_tbird','connectiontimeout'));
	//force data transfer instead of to stdout
	curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
	//disable SSL cert checking, if needed
	//curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
	
	//grab it
	$accesscode = false;
	$error = '';
	if(!$data = curl_exec($c)) {
		//error
		$c_errno = curl_errno($c);
		$log .= ', curl error ' . $c_errno;
		$debug .= "\n   Curl error: " . $c_errno;
		//handle some of most common errors!
		switch ($c_errno) {
			case 28:
				$errorstring = get_string('curlerror_timeout','block_ecampus_tbird');
				break;
			case 25:
			case 53:
			case 54:
			case 58:
			case 59:
			case 60:
				$errorstring = get_string('curlerror_ssl','block_ecampus_tbird');
			default:
				$errorstring = get_string('curlerror_unknown','block_ecampus_tbird');
		}
		$error = get_string('curlerror','block_ecampus_tbird') . ' '. $c_errno . ' - ' . $errorstring;
	} else {
		//we got something:
		$log .= ", returned: " . substr($data,0,500); //make sure it is not too long, in case of error
		$info = curl_getinfo($c);
		$debug .= "\n   Returned: " . substr($data,0,1000);
		$debug .= "\n   Took " . $info['total_time'] . " seconds to get access code";
		//need to parse return data here!
		if(!strncmp($data,'Error:',6)) {
			//return string is "Error:xxxxxxxxx"
			$error = 'ERROR: eCampus returned &quot;' . $data . '&quot;';
			
		} elseif(strlen($data) === ECAMPUS_ACCESS_CODE_LEN) {
			//valid access code will always be 42 characters.
			$accesscode = $data;
		} else {
			//Should not happen, but handle as error!
			//show only first 1000 characters of page returned, just in case.
			$error = get_string('curlerror_invalidpage','block_ecampus_tbird') . '<p>' . htmlspecialchars(substr($data,0,1000));
		}
	}
	
	//close it
	curl_close($c);

	//output log and debug data
	eCampus_Debug($debug);
	eCampus_Log($log);
	
	return $accesscode;
}

/**
 * render_eCampus_login - function to create the eCampus login form
 * 
 * @param string $studentid - eCampus username
 * @param string $accesscode - temporary access code for auto-login
 * @param number $courseid - course ID number for bookshelf with specific course only 
 * @param boolean $gotomyaccount - if true, go to top level bookshelf
 * @return string - contains form for login to eCampus, with javascript for auto-submit.
 */
function render_eCampus_login($studentid,$accesscode,$courseid = 0,$gotomyaccount = false)
{
	$schoolid =  get_config('block_ecampus_tbird','schoolid');
	
	$s =  '<form name="ecampusform" method="post" action="' . ECAMPUS_SSO_POST_URL . '">';
	$s .= '<input type="hidden" name="s" value="' . $schoolid . '"></input>';	//<!-- Required -->
	$s .= '<input type="hidden" name="accesscode" value="' . $accesscode . '"></input>';	//<!-- Required Access Code obtained from hidden call above -->
	$s .= '<input type="hidden" name="studentid" value="' . $studentid . '"></input>';	//<!-- Required -->
	if(!$gotomyaccount) {
		// land on bookshelf, instead of 'My Account' page
		$s .= '<input type="hidden" name="defaultpage" value="ebookshelf"></input>';	//<!-- Specify ebookshelf for default page if you want them to land on their ebooks page after the auto login occurs.  If this value is not provided, student will land on default my account landing page.  -->
	}
	if($courseid) {
		//if course id given, we will go to the books for that specific course, instead of all books in bookshelf
		$s .= '<input type="hidden" name="courseid" value="' . $courseid . '"></input>';	//<!-- Optional -->
	}
	$s .= '<input type="submit" value="' . get_string('clicktoaccessecampus', 'block_ecampus_tbird') . '"/></form>';
	//if javascript enabled (most browsers), submit immediately to simulate SSO
	$s .= '<script language="JavaScript">document.ecampusform.submit();</script>';
	$s .= '<noscript><p>' . get_string('javascriptdisabled', 'block_ecampus_tbird') . '</p></noscript>';

	return $s;
}

/**
 * 
 * @param string $header - header to show in formatted error page
 * @param string $error - the actual error, most likely directly from eCampus API
 * @return string - the html error page
 */
function render_eCampus_error($header,$error) {
	
	$s = '<p>' . $header . '<p>';
	$s .= '<p><font color="red">' . $error . '</font></p>';
	$s .= '<p>' . get_config('block_ecampus_tbird','configcustomerrormsg') . '<p>';
	return $s;	
}


/**
 * eCampus_debug - simple function to add to the block debug file
 * @param string $string - string to print to debug file
 */
function eCampus_debug($string) {
	
	if(get_config('block_ecampus_tbird','enabledebug') and $string <> '') {
		if($fp = fopen(get_config('block_ecampus_tbird','debugfile'),'a')) {
			fwrite($fp,date(DATEFORMAT) . ' - ' . $string . "\n");
			fclose($fp);
		}
		//else {//silently ignored!}
	}
}

/**
 * eCampus_log - simple function to add to the block log file
 * @param string $string - string to print to log file
 */
function eCampus_log($string) {

	if(get_config('block_ecampus_tbird','enablelog') and $string <> '') {
		if($fp = fopen(get_config('block_ecampus_tbird','logfile'),'a')) {
			fwrite($fp,date(DATEFORMAT) . ' - ' . $string . "\n");
			fclose($fp);
		}
		//else {//silently ignored!}
	}
}