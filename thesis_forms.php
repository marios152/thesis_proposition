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
 *  teleconference noticeboard block
 *
 * @package    block_teleconference_noticeboard
 * @copyright  2016 Marios Theodoulou mariostheodoulou.com <marios152 at gmail.com> 
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->libdir.'/datalib.php');
require_once ('thesis_functions.php');

/*
	The forms that are used by this plugin
*/
	
class thesis_add_first_info extends moodleform{
	function definition(){
		global $COURSE;
		$mform=$this->_form;
		$mform->addElement('header', 'general', get_string('general'));
		
		$textareaattributessmall='wrap="virtual" rows="5" cols="70"';
		$textareaattributes= 'wrap="virtual" rows="20" cols="70"';
		
		$mform->addElement('textarea', 'firstproposition_a', get_string('firstproposition_a','newmodule'),$textareaattributessmall);
		$mform->setType('firstproposition_a',PARAM_NOTAGS);
		$mform->addRule('firstproposition_a',get_string('fieldrequired','newmodule'),'required');
		
		$mform->addElement('textarea', 'firstproposition_b', get_string('firstproposition_b','newmodule'),$textareaattributes);
		$mform->setType('firstproposition_b',PARAM_NOTAGS);	
		$mform->addRule('firstproposition_b',get_string('fieldrequired','newmodule'),'required'); 
		
		$mform->addElement('textarea', 'firstproposition_c', get_string('firstproposition_c','newmodule'),$textareaattributes);
		$mform->setType('firstproposition_c',PARAM_NOTAGS);	
		$mform->addRule('firstproposition_c',get_string('fieldrequired','newmodule'),'required'); 
		
		$mform->addElement('textarea', 'firstproposition_d', get_string('firstproposition_d','newmodule'),$textareaattributes);
		$mform->setType('firstproposition_d',PARAM_NOTAGS);	
		$mform->addRule('firstproposition_d',get_string('fieldrequired','newmodule'),'required'); 
				
		$mform->addElement('textarea', 'firstproposition_e', get_string('firstproposition_e','newmodule'),$textareaattributes);
		$mform->setType('firstproposition_e',PARAM_NOTAGS);	
		$mform->addRule('firstproposition_e',get_string('fieldrequired','newmodule'),'required'); 
		
		$mform->addElement('textarea', 'firstproposition_f', get_string('firstproposition_f','newmodule'),$textareaattributes);
		$mform->setType('firstproposition_f',PARAM_NOTAGS);	
		$mform->addRule('firstproposition_f',get_string('fieldrequired','newmodule'),'required'); 
		
		$mform->addElement('textarea', 'firstproposition_g', get_string('firstproposition_g','newmodule'),$textareaattributes);
		$mform->setType('firstproposition_g',PARAM_NOTAGS);	
		$mform->addRule('firstproposition_g',get_string('fieldrequired','newmodule'),'required'); 
		
		/*
			approved or not
		*/
		$mform->addElement('hidden', 'firstproposition_approved');
		$mform->setType('firstproposition_approved',PARAM_NOTAGS);	
        $mform->setDefault('firstproposition_approved', "-1");
		/*
			lecturer id assigned to the student
		*/
		$mform->addElement('hidden', 'firstproposition_lecturer_id');
		$mform->setType('firstproposition_lecturer_id',PARAM_NOTAGS);	
        $mform->setDefault('firstproposition_lecturer_id', "-1");
		/*
			date added 
		*/
		$mform->addElement('hidden', 'firstproposition_date_added');
		$mform->setType('firstproposition_date_added',PARAM_NOTAGS);	
        $mform->setDefault('firstproposition_date_added', strtotime(date("y-m-d")));	
		/*
			course id
		*/
		$mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);
        $mform->setDefault('courseid', $COURSE->id);
	
		$objs = array();
        $objs[] =& $mform->createElement('submit', '', get_string('submitBtn', 'newmodule'));
        $objs[] =& $mform->createElement('cancel', '', get_string('cancelBtn', 'newmodule'));
        $grp =& $mform->addElement('group', 'buttonsgrp', "Options", $objs, array(' ', '<br />'), false);
	}
	// check that the form is not empty
	/*function validation($data,$files){ 
		$errors=parent::validation($data,$files);
		if (empty($data['link'])){
			$errors['link']='Link is required';
		}
		if (empty($data['time'])){
			$errors['time']='Time is required';
		}
		if (empty($data['date'])){
			$errors['date']='Date is required';
		}		
		return $errors;
	}*/
}	
/*
	Only one thesis should be returned.
	A student is only allowed to have one thesis proposition
*/
class thesis_show_first_info_to_student extends moodleform{ 
	function definition(){
		global $USER, $COURSE;
		
		$first_proposition_info = retrieve_first_proposition_info_student($USER->id, $COURSE->id);
		
		$mform=$this->_form;
		$mform->addElement('header', 'general', get_string('general'));
		
		$textareaattributessmall='wrap="virtual" rows="5" cols="70" disabled';
		$textareaattributes= 'wrap="virtual" rows="20" cols="70" disabled';
		
		var_dump($first_proposition_info);
		
		foreach($first_proposition_info as $fpi){
				
			$mform->addElement('textarea', 'firstproposition_a', get_string('firstproposition_a','newmodule'),$textareaattributessmall);
			$mform->setDefault('firstproposition_a', $fpi->first_proposition_a);
			
			$mform->addElement('textarea', 'firstproposition_b', get_string('firstproposition_b','newmodule'),$textareaattributes);
			$mform->setDefault('firstproposition_b', $fpi->first_proposition_b);
						
			$mform->addElement('textarea', 'firstproposition_c', get_string('firstproposition_c','newmodule'),$textareaattributes);
			$mform->setDefault('firstproposition_c', $fpi->first_proposition_c);		
			$mform->addElement('textarea', 'firstproposition_d', get_string('firstproposition_d','newmodule'),$textareaattributes);
			$mform->setDefault('firstproposition_d', $fpi->first_proposition_d);
					
			$mform->addElement('textarea', 'firstproposition_e', get_string('firstproposition_e','newmodule'),$textareaattributes);
			$mform->setDefault('firstproposition_e', $fpi->first_proposition_e);
			
			$mform->addElement('textarea', 'firstproposition_f', get_string('firstproposition_f','newmodule'),$textareaattributes);
			$mform->setDefault('firstproposition_f', $fpi->first_proposition_f);
			
			$mform->addElement('textarea', 'firstproposition_g', get_string('firstproposition_g','newmodule'),$textareaattributes);
			$mform->setDefault('firstproposition_g', $fpi->first_proposition_g);
			
		}
		
		/*
			approved or not
		*/
		$mform->addElement('hidden', 'firstproposition_approved');
		$mform->setType('firstproposition_approved',PARAM_NOTAGS);	
        $mform->setDefault('firstproposition_approved', "-1");
		/*
			lecturer id assigned to the student
		*/
		$mform->addElement('hidden', 'firstproposition_lecturer_id');
		$mform->setType('firstproposition_lecturer_id',PARAM_NOTAGS);	
        $mform->setDefault('firstproposition_lecturer_id', "-1");
		/*
			date added 
		*/
		$mform->addElement('hidden', 'firstproposition_date_added');
		$mform->setType('firstproposition_date_added',PARAM_NOTAGS);	
        $mform->setDefault('firstproposition_date_added', strtotime(date("y-m-d")));	
		/*
			course id
		*/
		$mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);
        $mform->setDefault('courseid', $COURSE->id);
		
	
		$objs = array();
        $objs[] =& $mform->createElement('submit', '', get_string('submitBtn', 'newmodule'));
        $objs[] =& $mform->createElement('cancel', '', get_string('cancelBtn', 'newmodule'));
        $grp =& $mform->addElement('group', 'buttonsgrp', "Options", $objs, array(' ', '<br />'), false);
	}
	// check that the form is not empty
	/*function validation($data,$files){ 
		$errors=parent::validation($data,$files);
		if (empty($data['link'])){
			$errors['link']='Link is required';
		}
		if (empty($data['time'])){
			$errors['time']='Time is required';
		}
		if (empty($data['date'])){
			$errors['date']='Date is required';
		}		
		return $errors;
	}*/
}

/*
	All thesis propositions should be returned.
	Course leader must be able to see all thesis propositions
*/
class thesis_show_all_first_info_to_student extends moodleform{ 
	function definition(){
		global $USER, $COURSE;
		
		$first_proposition_info = retrieve_all_first_proposition_info($COURSE->id);
		
		$mform=$this->_form;
		$mform->addElement('header', 'general', get_string('general'));
		
		$textareaattributessmall='wrap="virtual" rows="5" cols="70" disabled';
		$textareaattributes= 'wrap="virtual" rows="20" cols="70" disabled';
		
		var_dump($first_proposition_info);
		
		foreach($first_proposition_info as $fpi){
				
			$mform->addElement('textarea', 'firstproposition_a', get_string('firstproposition_a','newmodule'),$textareaattributessmall);
			$mform->setDefault('firstproposition_a', $fpi->first_proposition_a);
			
			$mform->addElement('textarea', 'firstproposition_b', get_string('firstproposition_b','newmodule'),$textareaattributes);
			$mform->setDefault('firstproposition_b', $fpi->first_proposition_b);
						
			$mform->addElement('textarea', 'firstproposition_c', get_string('firstproposition_c','newmodule'),$textareaattributes);
			$mform->setDefault('firstproposition_c', $fpi->first_proposition_c);		
			$mform->addElement('textarea', 'firstproposition_d', get_string('firstproposition_d','newmodule'),$textareaattributes);
			$mform->setDefault('firstproposition_d', $fpi->first_proposition_d);
					
			$mform->addElement('textarea', 'firstproposition_e', get_string('firstproposition_e','newmodule'),$textareaattributes);
			$mform->setDefault('firstproposition_e', $fpi->first_proposition_e);
			
			$mform->addElement('textarea', 'firstproposition_f', get_string('firstproposition_f','newmodule'),$textareaattributes);
			$mform->setDefault('firstproposition_f', $fpi->first_proposition_f);
			
			$mform->addElement('textarea', 'firstproposition_g', get_string('firstproposition_g','newmodule'),$textareaattributes);
			$mform->setDefault('firstproposition_g', $fpi->first_proposition_g);
			
		}
		
		/*
			approved or not
		*/
		$mform->addElement('hidden', 'firstproposition_approved');
		$mform->setType('firstproposition_approved',PARAM_NOTAGS);	
        $mform->setDefault('firstproposition_approved', "-1");
		/*
			lecturer id assigned to the student
		*/
		$mform->addElement('hidden', 'firstproposition_lecturer_id');
		$mform->setType('firstproposition_lecturer_id',PARAM_NOTAGS);	
        $mform->setDefault('firstproposition_lecturer_id', "-1");
		/*
			date added 
		*/
		$mform->addElement('hidden', 'firstproposition_date_added');
		$mform->setType('firstproposition_date_added',PARAM_NOTAGS);	
        $mform->setDefault('firstproposition_date_added', strtotime(date("y-m-d")));	
		/*
			course id
		*/
		$mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);
        $mform->setDefault('courseid', $COURSE->id);
		
	
		$objs = array();
        $objs[] =& $mform->createElement('submit', '', get_string('submitBtn', 'newmodule'));
        $objs[] =& $mform->createElement('cancel', '', get_string('cancelBtn', 'newmodule'));
        $grp =& $mform->addElement('group', 'buttonsgrp', "Options", $objs, array(' ', '<br />'), false);
	}
	// check that the form is not empty
	/*function validation($data,$files){ 
		$errors=parent::validation($data,$files);
		if (empty($data['link'])){
			$errors['link']='Link is required';
		}
		if (empty($data['time'])){
			$errors['time']='Time is required';
		}
		if (empty($data['date'])){
			$errors['date']='Date is required';
		}		
		return $errors;
	}*/
}
