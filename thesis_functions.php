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

function navigateToCourse($courseID){
	redirect (new moodle_url('/course/view.php', array('id'=>$courseID)));	
}

function navigatetomodule($courseID){
	redirect (new moodle_url('/mod/newmodule/view.php', array('id'=>$courseID)));	
}
function retrieve_first_proposition_info_student($courseID, $studentID){
	global $DB,$COURSE;
	$sessions=$DB->get_records('first_thesis_proposition',array('courseid'=>$COURSE->id));
	$sessionArr=[];
	foreach($sessions as $session){
		array_push($sessionArr, $session);
	}
	return $sessionArr;	
	
}
