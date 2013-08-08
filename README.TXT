This is a simple block that creates a dynamic link to single-sign-on to an eCampus eBookstore account
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
-Moodle 2.3 or later
-your PHP install needs to have the 'curl' extension loaded!

NOTE:
This block is tested in Moodle v2.3 and v2.4 only!, but should work in later versions as well.

INSTALLATION:
Unzip these files to the appropriate directories under your Moodle install <blocks> folder
Then as Moodle admin, go to the Notifications entry of your Admin block.
The block should be found and added to the list of available block.

USAGE:
* if needed enable the block in Site Admin -> Modules -> Blocks -> Manage Block; click on the closed eye.

* next, configure it. Click on the Settings link behind the block.go to Site Admin -> Modules -> Blocks
Contact your eCampus rep, and add you eCampus School ID, and your Secret Key.
Configure other settings as desired.
	
BUGS / TODO:
currently, the custom content of this block is NOT saved when a course is exported.

HOW TO CHANGE LOGO:
If you want your own logo, either set the config setting "Image URL",
or update the file "ecampus_tbird/pix/button.png".

LICENSE:
This module is licensed under the GNU GENERAL PUBLIC LICENSE Version 3, dated 29 June 2007
See the LICENSE.txt included for specific terms.

VERSION CHANGES:

2012080201 - Initial version

