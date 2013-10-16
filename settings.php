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
 * block settings for the eCampus interface
 *
 * @package   block_ecampus_tbird
 * @copyright  2013 Thunderbird School of Global Management
 * @author     2013 Johan Reinalda
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

//see more at http://docs.moodle.org/en/Development:Admin_settings#Individual_settings
//various functions are defined in lib/adminlib.php


if ($ADMIN->fulltree) {

	$settings->add(new admin_setting_configtext('block_ecampus_tbird/configtitle', get_string('configtitle', 'block_ecampus_tbird'),
				get_string('configtitledescr', 'block_ecampus_tbird'),
				'', PARAM_RAW, 30 ));

	$settings->add(new admin_setting_configtext('block_ecampus_tbird/configlinktext', get_string('configlinktext', 'block_ecampus_tbird'),
				get_string('configlinktextdescr', 'block_ecampus_tbird'),
				get_string('configlinktextdefault', 'block_ecampus_tbird'), PARAM_RAW, 30 ));

	$settings->add(new admin_setting_configtext('block_ecampus_tbird/configlinktitle', get_string('configlinktitle', 'block_ecampus_tbird'),
				get_string('configlinktitledescr', 'block_ecampus_tbird'),
				get_string('configlinktitledefault', 'block_ecampus_tbird'), PARAM_RAW, 60 ));

	$settings->add(new admin_setting_configselect('block_ecampus_tbird/configlinktype',get_string('configlinktype', 'block_ecampus_tbird'),
			get_string('configlinktypedescr', 'block_ecampus_tbird'),
			'text',
			array(	'text' => get_string('linktypetext', 'block_ecampus_tbird'),
					'image' => get_string('linktypeimage', 'block_ecampus_tbird'))
			));

	$settings->add(new admin_setting_configtext('block_ecampus_tbird/configimageurl', get_string('configimageurl', 'block_ecampus_tbird'),
				get_string('configimageurldescr', 'block_ecampus_tbird'),'', PARAM_RAW, 60 ));

	$settings->add(new admin_setting_configtext('block_ecampus_tbird/configmyimageurl', get_string('configmyimageurl', 'block_ecampus_tbird'),
				get_string('configmyimageurldescr', 'block_ecampus_tbird'),'', PARAM_RAW, 60 ));

	$settings->add(new admin_setting_confightmleditor('block_ecampus_tbird/configfooter', get_string('configfooter', 'block_ecampus_tbird'),
				get_string('configfooterdescr', 'block_ecampus_tbird'),
				'', PARAM_RAW, 60 ));

	$settings->add(new admin_setting_confightmleditor('block_ecampus_tbird/configcustomerrormsg', get_string('configcustomerrormsg', 'block_ecampus_tbird'),
				get_string('configcustomerrormsgdescr', 'block_ecampus_tbird'),
				get_string('configdefaulterrormsg','block_ecampus_tbird'), PARAM_RAW, 60, 16 ));

	$settings->add(new admin_setting_configcheckbox('block_ecampus_tbird/configallownewtitle', get_string('configallownewtitle', 'block_ecampus_tbird'),
			get_string('configallownewtitledescr', 'block_ecampus_tbird'), 0));

	$settings->add(new admin_setting_configcheckbox('block_ecampus_tbird/configallowcustom', get_string('configallowcustom', 'block_ecampus_tbird'),
			get_string('configallowcustomdescr', 'block_ecampus_tbird'), 0));

	$settings->add(new admin_setting_configtext('block_ecampus_tbird/schoolid', get_string('configschoolid', 'block_ecampus_tbird'),
				get_string('configschooliddescr', 'block_ecampus_tbird'), '', PARAM_RAW, 10 ));

	$settings->add(new admin_setting_configtext('block_ecampus_tbird/sharedsecret', get_string('configsharedsecret', 'block_ecampus_tbird'),
				get_string('configsharedsecretdescr', 'block_ecampus_tbird'), '', PARAM_RAW, 60 ));

	$settings->add(new admin_setting_configselect('block_ecampus_tbird/configuseridtype',get_string('configuseridtype', 'block_ecampus_tbird'),
			get_string('configuseridtypedescr', 'block_ecampus_tbird'),
			'idnumber',
			array( 	'idnumber' => get_string('useridtypeidnumber', 'block_ecampus_tbird'),
					'email' => get_string('useridtypeemail', 'block_ecampus_tbird'),
					'username' => get_string('useridtypeusername', 'block_ecampus_tbird'))
	));

	$settings->add(new admin_setting_configselect('block_ecampus_tbird/configcourseidtype',get_string('configcourseidtype', 'block_ecampus_tbird'),
			get_string('configcourseidtypedescr', 'block_ecampus_tbird'),
			'idnumber',
			array(	'idnumber' => get_string('courseidtypeidnumber', 'block_ecampus_tbird'),
					'shortname' => get_string('courseidtypeshortname', 'block_ecampus_tbird'))
			));

	$settings->add(new admin_setting_configtext('block_ecampus_tbird/connectiontimeout', get_string('configconnecttimeout', 'block_ecampus_tbird'),
				get_string('configconnecttimeoutdescr', 'block_ecampus_tbird'), 5, PARAM_INT, 5 ));

	$settings->add(new admin_setting_configcheckbox('block_ecampus_tbird/enablelog', get_string('configenablelog', 'block_ecampus_tbird'),
				get_string('configenablelogdescr', 'block_ecampus_tbird'), 0));

	$settings->add(new admin_setting_configtext('block_ecampus_tbird/logfile', get_string('configlogfile', 'block_ecampus_tbird'),
			get_string('configlogfiledescr', 'block_ecampus_tbird'), '', PARAM_RAW, 60 ));

	$settings->add(new admin_setting_configcheckbox('block_ecampus_tbird/enabledebug', get_string('configenabledebug', 'block_ecampus_tbird'),
			get_string('configenabledebugdescr', 'block_ecampus_tbird'), 0));

	$settings->add(new admin_setting_configtext('block_ecampus_tbird/debugfile', get_string('configdebugfile', 'block_ecampus_tbird'),
			get_string('configdebugfiledescr', 'block_ecampus_tbird'), '', PARAM_RAW, 60 ));

}