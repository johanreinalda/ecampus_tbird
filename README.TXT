This is a simple HTML Block that creates dynamic links to an eCampus eBookstore instance
(c) 2013, Thunderbird School of Global Management
Written by Johan Reinalda,  johan dot reinalda at thunderbird dot edu

DESCRIPTION:
This block creates dynamic links to an eCampus eBookstore instance.
This block implements the eCampus Automatic Student Login API (See document 'eCampus_API.docx')
This block is only useful if you have a contract with eCampus to provide ebooks or other online tools.
Please contact your eCampus rep for your school ID and shared secret, needed during the install.

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
* enable the block in Site Admin -> Modules -> Blocks -> Manage Block; click on the closed eye.

* next, configure it. Click on the Settings link behind the block.go to Site Admin -> Modules -> Blocks
Contact your eCampus rep, and add you eCampus School ID, and your Secret Key.
	
BUGS / TODO:
currently, the custom content of this block is NOT saved when a course is exported.

HOW TO CHANGE LOGO:
If you want your own logo, update the file "button.png" in the pix directory.

VERSION CHANGES:

2012062700 - Initial version

