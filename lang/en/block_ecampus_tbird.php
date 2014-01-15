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
 * trings for component 'block_ecampus_tbird', language 'en'
 *
 * @package    block_ecampus_tbird
 * @copyright  Thunderbird School of Global Management
 * @author	   2013 Johan Reinalda
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'eCampus';
$string['configtitle'] = 'Title';
$string['configtitledescr'] = 'If set, show as title in block header. Default is to not show header.';
$string['configlinktext'] = 'Link text';
$string['configlinktextdefault'] = 'eCampus Bookstore';
$string['configlinktextdescr'] = 'The text for the link to the eCampus Bookstore';
$string['configlinktitle'] = 'Link title';
$string['configlinktitledefault'] = 'Click here to access the eCampus Bookstore';
$string['configlinktitledescr'] = 'The hover-over text for the link to the eCampus Bookstore';
$string['configlinktype'] = 'Link type';
$string['configlinktypedescr'] = 'Select either a textual or graphical link to the eCampus Bookstore';
$string['configimageurl'] = 'Image URL';
$string['configmyimageurldescr'] = 'If using image, external URL to override built-in image outside courses (eg. My Moodle).';
$string['configmyimageurl'] = 'My Image URL';
$string['configimageurldescr'] = 'If using image, external URL to override built-in image on My Moodle page.';
$string['linktypetext'] = 'Text link';
$string['linktypeimage'] = 'Image link';
$string['configshowbooklink'] = 'Show book links';
$string['configshowbooklinkdescr'] = 'Choose if you want to show links to the individual books for a course, either a textual (book title) or graphical (book cover) link';
$string['booklinktypeno'] = 'Do not show';
$string['booklinktypetext'] = 'Book title';
$string['booklinktypeimage'] = 'Book cover image';
$string['configusesecureimage'] = 'Use secure image';
$string['configusesecureimagedescr'] = 'If available, use secure (https) url to show the book image from eCampus';
$string['configbookimagesize'] = 'Book image size';
$string['configbookimagesizedescr'] = 'Set the max height and width (in pixels) for the eCampus provided book image. 0 means do not limit.';
$string['configbooktitlelength'] = 'Book title length';
$string['configbooktitlelengthdescr'] = 'Set the max number of characters of the book title. 0 means do not limit. If limit set, title will be cut of at that length, followed by "..."';
$string['confignologoifbook'] = 'No logo if books';
$string['confignologoifbookdescr'] = 'If course has eBooks associated with it, hide the eCampus logo button, and show only book links or images';
$string['configallowcustomconfig'] = 'Allow custom settings';
$string['configallowcustomconfigdescr'] = 'Allow per-block settings of book title vs. image, image size, and title length';
$string['configfooter'] = 'Footer';
$string['configfooterdescr'] = 'If set, show as centered footer text in block. You can use HTML tags to format.';
$string['configallownewtitle'] = 'Allow custom title';
$string['configallownewtitledescr'] = 'If set, in each block instance allow a custom title.';
$string['configallowcustom'] = 'Allow custom config';
$string['configallowcustomdescr'] = 'If set, in each block instance allow additional custom content to be added below eCampus link and before footer.';
$string['configchangetitle'] = 'Change block title';
$string['configadditionalcontent'] = 'Additional content between link and footer';
$string['ecampus_tbird_settings'] = 'eCampus block custom settings';
$string['ecampuscustomersettings'] = 'eCampus customer specific settings';
$string['configecampuscustomersettings'] = 'The settings below are customer/school specific, and can be obtained from your eCampus representative.';
$string['configschoolid'] = 'eCampus School ID';
$string['configschooliddescr'] = 'School specific, see your eCampus rep for details.';
$string['configsharedsecret'] = 'eCampus Shared Secret';
$string['configsharedsecretdescr'] = 'School specific, see your eCampus rep for details.';
$string['configuseridtype'] = 'eCampus user id';
$string['configuseridtypedescr'] = 'Which Moodle user attribute will be sent to eCampus as the student id';
$string['useridtypeidnumber'] = 'idnumber';
$string['useridtypeemail'] = 'email';
$string['useridtypeusername'] = 'username';
$string['configcourseidtype'] = 'eCampus course id';
$string['configcourseidtypedescr'] = 'Which Moodle course attribute will be sent to eCampus as the course id';
$string['courseidtypeidnumber'] = 'idnumber';
$string['courseidtypeshortname'] = 'shortname';
$string['ecampuscoursebookshelf'] = 'eCampus Course Bookshelf';
$string['ecampusbookshelf'] = 'eCampus Bookshelf';
$string['instancesettings'] = 'Per Instance settings';
$string['configinstancesettings'] = 'The following settings allow per-instance customizations. In most cases this may not be needed.';
$string['debugsettings'] = 'Connection and Debug settings';
$string['configdebugsettings'] = 'The following settings do not normally require changing. Only set if you know what you are doing, or as requested by support.';
$string['configenablelog'] = 'Enable extra logging';
$string['configenablelogdescr'] = 'Enable activity logging into an extra log file. Primarily for development. (Moodle system logging always occurs).';
$string['configlogfile'] = 'Log file';
$string['configlogfiledescr'] = 'Log file path, must be writable by web server';
$string['configenabledebug'] = 'Enable debugging';
$string['configenabledebugdescr'] = 'If checked, enable debugging into the debug file. Primarily for development.';
$string['configdebugfile'] = 'Debug file';
$string['configdebugfiledescr'] = 'Debug file path, must be writable by web server';
$string['clicktoaccessecampus'] = 'eCampus Login';
$string['configconnecttimeout'] = 'Connection timeout';
$string['configconnecttimeoutdescr'] = 'Connection timeout in second for getting the temporary eCampus access code for single-sign-in';
$string['ecampuslogin'] = 'eCampus Login';
$string['redirectfollowsshortly'] = 'You will shortly be redirected to the eCampus site';
$string['javascriptdisabled'] = 'Since your browser has Javascript disabled, you will need to click the button above to login to eCampus';
$string['errorpagetitle'] = 'An eCampus login error has occured!';
$string['configcustomerrormsg'] = 'Custom error message';
$string['configcustomerrormsgdescr'] = 'Custom error message that will be shown below the eCampus actual error text.';
$string['configdefaulterrormsg'] = 'Please contact the site administrator for assistance.';
$string['erroroccured'] = 'An error occured trying to sign in to eCampus!';
$string['erroruseridnumbernotset'] = 'User idnumber needed for eCampus login, but idnumber not set for this user.';
$string['curlerror'] = 'ERROR: cannot get eCampus access code, curl error';
$string['curlerror_timeout'] = 'Connection Timeout';
$string['curlerror_ssl'] = 'SSL related error';
$string['curlerror_unknown'] = 'Unknown';
$string['curlerror_invalidpage'] = 'ERROR: eCampus returned invalid page';
$string['clickhereforbook'] = 'Click here to access your purchased eCampus eBook';
$string['purgecachetitle'] = 'eCampus Book List Cache Purging';
$string['cachepurged'] = 'The eCampus book list cache has been purged!';
$string['accessdenied'] = 'Access denied: you are not allow to purge the book list cache!';
$string['ecampus_tbird:addinstance'] = 'Add a new eCampus block';
$string['ecampus_tbird:myaddinstance'] = 'Add a new eCampus block to My home';
$string['ecampus_tbird:purgecache'] = 'Purge the course book list cache';
