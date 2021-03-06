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
 * Form for editing ecampus_tbird block instances.
 *
 * @package   block_ecampus_tbird
 * @copyright Thunderbird School of Global Management
 * @author    2013 Johan Reinalda
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

//for all items you can add, see /lib/form/*.php - JKR 20101124
//form->addElement (type, variable_name, label)
//variable starts with config_xxxxx and then can be addressed as block->config->xxxxx

class block_ecampus_tbird_edit_form extends block_edit_form {
	protected function specific_definition($mform) {
		// Fields for editing custom block title and additional contents.
		// only if allowed by global setting
		// copied from HTML block code
		$allownewtitle = get_config('block_ecampus_tbird','configallownewtitle');
		$allowcustom = get_config('block_ecampus_tbird','configallowcustom');
		if(!empty($allownewtitle) or !empty($allowcustom)) {

			$mform->addElement('header', 'configheader', get_string('ecampus_tbird_settings', 'block_ecampus_tbird'));

			// field for editing the block title
			if (!empty($allownewtitle)) {
				$mform->addElement('text', 'config_title', get_string('configchangetitle', 'block_ecampus_tbird'));
				$mform->setType('config_title', PARAM_MULTILANG);
			}

			// field for editing the block custom content
			if(!empty($allowcustom)) {
				$editoroptions = array('maxfiles' => EDITOR_UNLIMITED_FILES, 'noclean'=>true, 'context'=>$this->block->context);
				$mform->addElement('editor', 'config_text', get_string('configadditionalcontent', 'block_ecampus_tbird'), null, $editoroptions);
				$mform->setType('config_text', PARAM_RAW); // XSS is prevented when printing the block contents and serving files
			}
			
		}
	}

	function set_data($defaults) {
		if (!empty($this->block->config) && is_object($this->block->config)) {
			$text = $this->block->config->text;
			$draftid_editor = file_get_submitted_draft_itemid('config_text');
			if (empty($text)) {
				$currenttext = '';
			} else {
				$currenttext = $text;
			}
			$defaults->config_text['text'] = file_prepare_draft_area($draftid_editor, $this->block->context->id, 'block_ecampus_tbird', 'content', 0, array('subdirs'=>true), $currenttext);
			$defaults->config_text['itemid'] = $draftid_editor;
			//$defaults->config_text['format'] = $this->block->config->format;
		} else {
			$text = '';
		}

		if (!$this->block->user_can_edit() && !empty($this->block->config->title)) {
			// If a title has been set but the user cannot edit it format it nicely
			$title = $this->block->config->title;
			$defaults->config_title = format_string($title, true, $this->page->context);
			// Remove the title from the config so that parent::set_data doesn't set it.
			unset($this->block->config->title);
		}

		// have to delete text here, otherwise parent::set_data will empty content
		// of editor
		unset($this->block->config->text);
		parent::set_data($defaults);
		// restore $text
		if (!isset($this->block->config)) {
			$this->block->config = new stdClass();
		}

		$this->block->config->text = $text;
		if (isset($title)) {
			// Reset the preserved title
			$this->block->config->title = $title;
		}
	}
}