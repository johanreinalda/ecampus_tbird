<?php
/**
 * eCampus Block page.
 *
 * @package    block
 * @subpackage ecampus_tbird
 * @copyright  2013 Thunderbird School of Global Management
 * @author     2013 Johan Reinalda
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

//see block development at: http://docs.moodle.org/dev/Blocks
//database interface is defined in /lib/dml/moodle_database.php

class block_ecampus_tbird extends block_base {

    function init() {
    	//this is the initial title for the block
    	//this will be used on the settings page for this block
    	//instances in courses will have the settings value of $CFG->block_ecampus_tbird_configtitle
    	//as this will be overwritten on specialization() method.
		$this->title = get_string('pluginname', 'block_ecampus_tbird');
    }

    //make sure header is shown, with global admin title, set in language file
    function hide_header(){
    	// if no title set, don't show header
    	// custom title allowed and set ?
    	if(get_config('block_ecampus_tbird','configallownewtitle') && !empty($this->config->title)) {
    		return false;
    	}
    	// system title set ?
    	$newtitle = get_config('block_ecampus_tbird','configtitle');
    	if(empty($newtitle)) {
        	return true;
    	}
    	return false;
    }
    
    //we have global config/settings data
	function has_config() {
		return true;
	}

	//only show in courses, front page, and My Moodle page
    function applicable_formats() {
        return array(
        		'course-view' => true,
				'my' => true,
        		'site-index' => true);
    }

    //this class is called immediately after object is instantiated.
    //here we can override the title for this instance
	function specialization() {
		//override title to come from global configuration if set
		if(get_config('block_ecampus_tbird','configallownewtitle') && !empty($this->config->title)) {
			$this->title = $this->config->title;
		} else {
			$newtitle = get_config('block_ecampus_tbird','configtitle');
			if(!empty($newtitle))
				$this->title = $newtitle;
		}
	}

    //we do NOT allow multiple instances of this block!
    function instance_allow_multiple() {
        return false;
    }

    function get_content() {
    	global $CFG;
        if ($this->content !== NULL) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';
        //make sure PHP has curl extension loaded
        if (!extension_loaded('curl')) {
        	//Hmm, should not have gotten here.
        	$this->content->text = 'Error: PHP curl extention not loaded!</br>Please notify your Moodle admin.';
        	 
        } else {
        	//the eCampus pass-through page link
        	$passthrough = $CFG->wwwroot.'/blocks/ecampus_tbird/passthrough.php';
        	//add "courseid" for courses
        	global $COURSE;
        	if (empty($COURSE) or $COURSE->id == 1) {
        		//not in course context, but Front Page or My Moodle.
        		$external = get_config('block_ecampus_tbird','configmyimageurl');
        		if(!empty($external)) {	//use external configured image url for 'Front Page' or 'My' block view
        			$image = $external;
        		} else {
	        		$image = $CFG->wwwroot.'/blocks/ecampus_tbird/pix/mybutton.png'; //built-in image
    	    	}
        	} else {
        		//in course context
        		$passthrough .= '?courseid=' . $COURSE->id;
        		$external = get_config('block_ecampus_tbird','configimageurl');
        		if(!empty($external)) {	//use external configured url for course block
        			$image = $external;
        		} else {
        			$image = $CFG->wwwroot.'/blocks/ecampus_tbird/pix/button.png'; //built-in image
        		}
        	}
        	     
        	//show text or image link for eCampus click-through?
        	$linktype = get_config('block_ecampus_tbird','configlinktype');
        	$linktitle = get_config('block_ecampus_tbird','configlinktitle');
       		$text = '<center><a target="_blank" ';
       		if(!empty($linktitle))
       			$text .= 'title="' . $linktitle . '" ';
       		$text .= 'href="' . $passthrough . '">';
        	if($linktype === 'text') {
        		$text .= get_config('block_ecampus_tbird','configlinktext');
        	} else {
        		//image
        		$text .= '<img src="' . $image . '">'; 
        	}
        	$text .= '</a></center>';
        	
        	//add instance content, if allowed and set
        	if(get_config('block_ecampus_tbird','configallowcustom')) {
        		if(!empty($this->config->text['text'])) {
        			$text .= $this->config->text;
        		}
        	}

        	$this->content->text = $text;
        	$this->add_footer();
        }
        
        return $this->content;
    }
    
    /**
     * Serialize and store config data
     */
    function instance_config_save($data, $nolongerused = false) {
    	global $DB;
    
    	$config = clone($data);
    	// Move embedded files into a proper filearea and adjust HTML links to match
    	$config->text = file_save_draft_area_files($data->text['itemid'], $this->context->id, 'block_html', 'content', 0, array('subdirs'=>true), $data->text['text']);
    	$config->format = $data->text['format'];
    
    	parent::instance_config_save($config, $nolongerused);
    }
    
    function instance_delete() {
    	global $DB;
    	$fs = get_file_storage();
    	$fs->delete_area_files($this->context->id, 'block_html');
    	return true;
    }
    
	function add_footer() {
		//add footer if set
		$configfooter = get_config('block_ecampus_tbird','configfooter');
		if(!empty($configfooter)) {
			$this->content->footer = $configfooter;
		}
	}
	
    function clear_content() {
		$this->content->text = '';
		$this->content->footer = '';
    }

}
