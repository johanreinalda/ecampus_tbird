This is a simple Moodle block that creates a dynamic link to single-sign-on to an eCampus eBookstore account
(c) 2013, Thunderbird School of Global Management
Written by Johan Reinalda,  johan dot reinalda at thunderbird dot edu

DESCRIPTION:
This block creates dynamic links to an eCampus eBookstore instance.
This block implements the eCampus Automatic Student Login API (See document "eCampus Automatic Student Login.doc")
This block is only useful if you have a contract with eCampus to provide ebooks or other online tools, and
your school has chosen to use the same access mechanism.
Note: you need to contact your eCampus rep for your school ID and shared secret, needed during the install.
The course id passed to eCampus is the Moodle course 'idnumber' field, which is set by your external SA/SIS systems.

PREREQUISITES:
- from v1.1 on, Moodle 2.4 or later, due to the use of the Moodle Unified Cache to limit network calls to eCampus
	(v1.0 required Moodle 2.3 or later)
-your PHP install needs to have the 'curl' extension loaded!
 edit your php.ini file, and remove the semi-colon to enable a line like this:
    ;extension=php_curl.dll
    OR
    ;extension=php_curl.so
 You will probably have to restart your web server service for this change to go into effect.
 For more on cURL with php, see http://www.php.net/curl

NOTE:
This block is tested in Moodle v2.4 only!, but should work in later versions as well.

INSTALLATION:
Unzip these files to the appropriate directories under your Moodle install <blocks> folder
Then as Moodle admin, go to the Notifications entry of your Admin block.
The block should be found and added to the list of available block.

USAGE:
* if needed enable the block in Site Admin -> Modules -> Blocks -> Manage Block; click on the closed eye.

* next, configure it. Click on the Settings link behind the block.go to Site Admin -> Modules -> Blocks
Contact your eCampus rep, and add you eCampus School ID, and your Secret Key.
Configure other settings as desired.

VERSIONS:
1.1 - added option to list in the block the individual books in a course, and link directly into them on the eCampus site.
      We use the MUC cache to keep network calls low (once per course), and therefor we now require Moodle v2.4 or later.
      You can configure caching per your needs in the Administration -> Plugins ->Caching area.
      eCampus block uses a generic 'Application' cache. If data becomes corrupt, or you have changed books for courses.
      you can purge the cache from the bottom of the settings page.
      (In your moodle instance, the cache data will most likely be stored in the directory
      <moodle_data_dir>/cache/cachestore_file/default_application/adhoc_block_ecampus_tbird_books/
      When the block in uninstalled from Moodle, this cache will be deleted.
      
1.0 - initial version, simple single sign on into the eCampus site, onto users bookshelf or course book list.


BUGS/TODO:
currently, the custom content of this block is NOT saved when a course is exported.

HOW TO CHANGE LOGO:
If you want your own logo, either set the config settings for image urls,
or update the files "ecampus_tbird/pix/button.png" and "mybutton.png"

COPYRIGHT LICENSE:
This module is Copyright(c) 2013 onward, Thunderbird School of Global Management, with portions
contributed/copyrighted by many others (see the Moodle Developer credits and the Moodle source code itself)
and all of it is provided under the terms of the GPL.

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation, version 3, dated 29 June 2007.

See the LICENSE.txt included for specific terms.

WARRANTY:
This module is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License included in LICENSE.txt for
more details.

VERSION CHANGES:
2013080801 - Initial version

