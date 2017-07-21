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
/*
	show proposition to student
*/
function retrieve_first_proposition_info_student($courseID, $studentID){
	global $DB,$COURSE;
	$sessions=$DB->get_records('first_thesis_proposition',array('courseid'=>$COURSE->id));
	$sessionArr=[];
	foreach($sessions as $session){
		array_push($sessionArr, $session);
	}
	return $sessionArr;	
	
}
/*
	show all propositions to course leader
*/
function retrieve_all_first_proposition_info($studentID){
	global $DB,$COURSE;
	$sessions=$DB->get_records('first_thesis_proposition',array('courseid'=>$COURSE->id));
	$sessionArr=[];
	foreach($sessions as $session){
		array_push($sessionArr, $session);
	}
	return $sessionArr;	
	
}

function studentView(){
	global $COURSE, $USER;
	$first_student_proposition= retrieve_first_proposition_info_student($COURSE->id, $USER->id);
	if($first_student_proposition!=NULL){
		echo '<table cellspacing="0" border="1" >
		<colgroup>
			<col style="width: 10%" />
			<col style="width: 10%" />
			<col style="width: 10%" />
			<col style="width: 10%" />
			<col style="width: 10%" />
		</colgroup>
			<thead>
				<tr>
					<th>Title</th>
					<th>Date added</th>
					<th>Approved</th>
					<th>Lecturer assigned</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<tr>';
				// $this->content->footer .= '<h5>'.get_string('howToConnectInstr', 'block_teleconference_noticeboard').'</h5>';	
				// var_dump($first_student_proposition);
				foreach($first_student_proposition as $fsp){
					echo '<tr>';
						$show_thesis_first_info_url = new moodle_url("/mod/newmodule/showstudent_first_thesis_info.php", array('courseid'=>$COURSE->id));				
						echo '<td><a href="'.$show_thesis_first_info_url.'">'.$fsp->first_proposition_a.'</a></td>';
						echo '<td>'.$fsp->date_added.'</td>';
							if ($fsp->first_proposition_approved == '1'){
								echo '<td>Continue to the second form</td>';	
							}elseif($fsp->first_proposition_approved == '0'){
								echo '<td>Edit your submision. The proposition you submitted was not approved</td>';
							}elseif($fsp->first_proposition_approved == '-1'){
								echo '<td>You are not approved yet</td>';
							}
						echo '<td>Something</td>';
						echo '<td>Something</td>';			
					echo '</tr>';
				}
				echo'</tr>
			</tbody>
		</table>';
		}else{
			// $this->content->footer .= '<h5>'.get_string('noSessionsYet', 'block_teleconference_noticeboard').'</h5>';	
			echo "You didn't submit anything. Please submit.";
		}
	
	
}
