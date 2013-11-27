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
define('ECAMPUS_BOOKLIST_URL','http://www.ecampus.com/autocourselist.asp');
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
	$log;

	//new curl resource
	$c = curl_init();

	// set URL, etc.
	$schoolid =  get_config('block_ecampus_tbird','schoolid');
	$schoolsecret = get_config('block_ecampus_tbird','sharedsecret');
	$url = ECAMPUS_ACCESS_URL . '?s=' . $schoolid. '&k=' . $schoolsecret . '&studentid=' . $studentid;
	$log = 'ACCESS URL: ' . $url;

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
		$log .= "\n" . get_string('curlerror','block_ecampus_tbird') . ' '. $c_errno . ' - ' . curl_error_to_text($c_errno);
	} else {
		//we got something:
		$info = curl_getinfo($c);
		$log .= "\n   Returned in " . $info['total_time'] . " seconds:\n" . substr($data,0,500); //make sure it is not too long, in case of error
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

	//output log
	eCampus_Log($log);

	return $accesscode;
}

/**
 * get_eCampus_userid() - return the user id from the current context
 * 
 * @return string - the user id as needed by eCampus, ie Moodle username, student id, etc.
 */
function get_eCampus_studentid() {
global $USER;
	//figure out what user attribute we pass as studentid to eCampus
	$useridtype = get_config('block_ecampus_tbird','configuseridtype');
	$studentid = '';
	switch($useridtype) {
		case 'idnumber':
			if($USER->idnumber !== '') {
				$studentid = $USER->idnumber;
			}
			break;
		case 'email':
			//mandatory fields, not error checking needed!
			$studentid = $USER->email;
			break;
		case 'username':
			$studentid = $USER->username;
			break;
	}
	return $studentid;
}

/**
 * render_eCampus_login - function to create the eCampus login form
 *
 * @param string $studentid - eCampus username
 * @param string $accesscode - temporary access code for auto-login
 * @param number $courseid - course ID number for bookshelf with specific course only
 * @param number $isbn - ISBN number for the specific book to open
 * @param number $page - page number inside the specific book to open
 * @param boolean $newwindow - if set, form submit will render in new target window
 * @parapm boolean $autosubmit - if set, javascript to automatically submit form will be included
 * @param boolean $gotomyaccount - if true, go to top level bookshelf
 * @return string - contains form for login to eCampus, with javascript for auto-submit.
 */
function render_eCampus_login($studentid,$accesscode, $courseid=0, $isbn=0, $page=0, $submittext='', $submitimage='', $newwindow=false, $autosubmit=true, $gotomyaccount=false)
{
	$schoolid =  get_config('block_ecampus_tbird','schoolid');

	$s =  '<form name="ecampusform" method="post" action="' . ECAMPUS_SSO_POST_URL . '"';
	if($newwindow) $s .= ' target="_blank"';
	$s .= '><input type="hidden" name="s" value="' . $schoolid . '" />';	//<!-- Required -->
	$s .= '<input type="hidden" name="accesscode" value="' . $accesscode . '" />';	//<!-- Required Access Code obtained from hidden call above -->
	$s .= '<input type="hidden" name="studentid" value="' . $studentid . '" />';	//<!-- Required -->
	if($isbn) {
		$s .= '<input type="hidden" name="isbn" value="' . $isbn . '" />';
		if($page) {
			$s .= '<input type="hidden" name="page" value="' . $page . '" />';
		}
	}
	if(!$gotomyaccount and !$isbn) {
		// land on bookshelf, instead of 'My Account' page
		$s .= '<input type="hidden" name="defaultpage" value="ebookshelf" />';	//<!-- Specify ebookshelf for default page if you want them to land on their ebooks page after the auto login occurs.  If this value is not provided, student will land on default my account landing page.  -->
	}
	if($courseid) {
		//if course id given, we will go to the books for that specific course, instead of all books in bookshelf
		$s .= '<input type="hidden" name="courseid" value="' . $courseid . '" />';	//<!-- Optional -->
	}
	if($submittext === '') {
		$submittext = get_string('clicktoaccessecampus', 'block_ecampus_tbird');
	}
	if($submitimage <> '') {
		//use image as submit button
		$s .= '<input type="image" src="' . $submitimage . '" alt="' . $submittext . '" />';
	} else {
		// textual button
		$s .= '<input type="submit" value="' . $submittext . '" />';
	}
	$s .= '</form>';
	if($autosubmit) {
		//if javascript enabled (most browsers), submit immediately to simulate SSO
		$s .= '<script language="JavaScript">document.ecampusform.submit();</script>';
		$s .= '<noscript><p>' . get_string('javascriptdisabled', 'block_ecampus_tbird') . '</p></noscript>';
	}
		
	return $s;
}


/**
 * get_eCampus_courseid() - return the course id from the current context
 * @param int $mcourseid - the Moodle course id $course->id
 * @return string - the course id as needed by eCampus, ie Moodle course id number, or course shortname
 */
function get_eCampus_courseid($mcourseid) {
	global $COURSE;

	//figure out what Moodle course attribute we pass as courseid to eCampus
	$courseidtype = get_config('block_ecampus_tbird','configcourseidtype');
	$courseid = '';
	if(!empty($COURSE) and $COURSE->id != 1) {
		if($courseidtype === 'idnumber') {
			//idnumber means we pass in the external SA system $course->idnumber, NOT $course->id
			$courseid = $COURSE->idnumber;
		} else {
			//shortname is only other option at this time
			$courseid = $COURSE->shortname;
		}
	}
	return $courseid;
}

/**
 * function get_eCampus_booklist - find all books for a specific course
 *    this will check the Moodle cache first. If not found, it will query eCampus
 *    and store data in the Moodle cache for later use.
 * @param int $mcourseid - Moodle course->id field, if set
 * @return Array - an array of one or more eBook objects with the book data for this course
 */
function get_eCampus_booklist($mcourseid=0)
{
	global $USER;
	$booklist = Array();
	
	$courseid = get_eCampus_courseid($mcourseid);
	if($courseid <> '') {
		// access the cache store that created during install in db/caches.php
		$cache = cache::make_from_params(cache_store::MODE_APPLICATION, 'block_ecampus_tbird', 'books');
		
		// is the book list already cached ?
		if($booklist = $cache->get($courseid)) {
			eCampus_log("Book list for course $courseid (user id " . $USER->id . ") FOUND in cache!");
			eCampus_debug("Book list found:\n" . print_r($booklist,true));
		} else {
			// go get the book list from eCampus API with cURL library		
			$c = curl_init();
			// set URL, etc.
			$schoolid =  get_config('block_ecampus_tbird','schoolid');
			$url = ECAMPUS_BOOKLIST_URL . '?sintschoolid=' . $schoolid . '&courses2=' . $courseid . '&xml=1';
			eCampus_log("Book list for course $courseid (user id " . $USER->id . ") NOT FOUND in cache!\nGetting: " . $url);
				
			//bind url to connection
			curl_setopt($c, CURLOPT_URL, $url);
			//set timeout (in seconds)
			curl_setopt($c, CURLOPT_CONNECTTIMEOUT, get_config('block_ecampus_tbird','connectiontimeout'));
			//force data transfer instead of to stdout
			curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
			//do no recurse redirection, but stop!
			curl_setopt($c, CURLOPT_FOLLOWLOCATION, false);
			//disable SSL cert checking, if needed
			//curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
	
			//grab it, log error as needed.
			if(!$data = curl_exec($c)) {
				//error
				$c_errno = curl_errno($c);
				eCampus_log(get_string('curlerror','block_ecampus_tbird') . ' '. $c_errno . ' - ' . curl_error_to_text($c_errno));
					
			} else {
				//we got something:
				$info = curl_getinfo($c);
				eCampus_log("\n   Returned in " . $info['total_time'] . " seconds:\n" . substr($data,0,500)); //make sure it is not too long, in case of error
				//need to parse XML return data here!
				libxml_use_internal_errors(true); //disable warnings (E_WARNING) if bad data received.
				// this will return 'false' if bad data!
				$xmlobject = simplexml_load_string($data);
				eCampus_debug("XML object:\n" . print_r($xmlobject,true));
				// the SimpleXMLElement object returned from simple_xml_load() cannot be serialized,
				// and therefor cannot be cached in Moodle cache
				// parse this into an object that can be cached:
				$booklist = XMLtoEbookArray($xmlobject);
				eCampus_debug("eBook array:\n" . print_r($booklist,true));
				// if something found, so cache it.
				if(sizeof($booklist)) {
					$cache->set($courseid,$booklist);
					eCampus_log("Book list for course $courseid STORED in cache!");
				} else {
					eCampus_log("NO Book list found for course $courseid!");
				}

			}
			//close it
			curl_close($c);

		}
	} else {
		eCampus_debug('courseid NOT SET');
	}
	return $booklist;
}


// simple class to hold book information so it can be serialized in a cache
class eBook {
	public $isbn;
	public $title;
	public $author;
	public $image;
	public $secureimage;

	function __construct($isbn = 0, $title = '', $author = '', $image = '', $secureimage = '') {
		$this->isbn = $isbn;
		$this->title = $title;
		$this->author = $author;
		$this->image = $image;
		$this->secureimage = $secureimage;
	}
}

/**
 * function XMLtoEbookArray - convert an SimpleXML object that cannot be serialized to
 *                            an array of objects that can be serialized and therefor cached.
 *
 * @param XMLSimple $xml - the XML returns from the eCampus booklist call
 * @return Array in format that can be serialized:
 */
function  XMLtoEbookArray($xml) {

	$booklist = Array();
	if(is_object($xml)) { // will be false if not proper xml data received
		if(isset($xml->Courses->Course->Books)) {
			$books = $xml->Courses->Course->Books;
			eCampus_debug("XML \$books:\n" . print_r($books,true));
			if(is_object($books)) {
				// one book only!
				eCampus_debug('XML Book Object count: ' . count($books->Book));
				foreach($books->Book as $b) {
					$booklist[] = get_ebook($b);
				}
			}
		}
		// else }
			// not proper format! }
	}
	return $booklist;
}

/**
 * function get_ebook() -  created an eBook object from the XML book data received from eCampus
 * We used XMLSimpleParse to get a quick object that cannot be serialized.
 * To store in the Moodle Cache, we need a serializable object format.
 * @param xml object $b - object as returned from XML from eCampus 
 * @return eBook
 */
function get_ebook($b) {
	eCampus_debug("get_ebook():\n" . print_r($b,true));
	$book = new eBook();
	$book->isbn = (string)$b->ISBN;
	$book->title = (string)$b->Title;
	$book->author = (string)$b->Author;
	if(strlen($b->Image)) {
		$book->image = (string)$b->Image;
	}
	if(strlen($b->SecureImage)) {
		$book->secureimage = (string)$b->SecureImage;
	}
	eCampus_debug("get_ebook() returned:\n" . print_r($book,true));
	return $book;
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
	}
}

/**
 * function eCampus_log - simple function to add to the block log file
 * @param string $string - string to print to log file
 */
function eCampus_log($string) {

	if(get_config('block_ecampus_tbird','enablelog') and $string <> '') {
		if($fp = fopen(get_config('block_ecampus_tbird','logfile'),'a')) {
			fwrite($fp,date(DATEFORMAT) . ' - ' . $string . "\n");
			fclose($fp);
		}
	}
	//send to debug as well
	eCampus_debug($string);
}

/**
 * function curl_error_to_string - translate an error number into a human readable string
 * @param int $c_errno - the curl error
 * @return string - the human readable explanation of the error
 */
function curl_error_to_text($c_errno) {
	$errorstring;
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
	return $errorstring;
}