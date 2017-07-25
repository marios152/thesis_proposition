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
	global $DB, $COURSE;	
	$module=$DB->get_record('modules',array('name'=>'newmodule'));
	$coursemodule=$DB->get_record('course_modules',array('course'=>$COURSE->id , 'instance'=>'1', 'module'=>$module->id));
	redirect (new moodle_url('/mod/newmodule/view.php', array('id'=>$coursemodule->id)));	
}
/*
	show proposition to student
*/
function retrieve_first_proposition_info_student($courseID, $studentID){
	global $DB;
	$sessions=$DB->get_records('first_thesis_proposition',array('courseid'=>$courseID, 'userid'=>$studentID));
	$sessionArr=[];
	foreach($sessions as $session){
		array_push($sessionArr, $session);
	}
	return $sessionArr;	
}
/*
	show all propositions to course leader
*/
function retrieve_all_first_proposition_info($courseid){
	global $DB,$COURSE;
	$sessions=$DB->get_records('first_thesis_proposition',array('courseid'=>$courseid));
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

function teacherView(){
	global $DB,$COURSE,$USER;
	
	$all_first_student_proposition= retrieve_all_first_proposition_info($COURSE->id);
	if($all_first_student_proposition!=NULL){
		echo '<table cellspacing="0" border="1" >
		<colgroup>
			<col style="width: 10%" />
			<col style="width: 10%" />
			<col style="width: 10%" />
			<col style="width: 10%" />
			<col style="width: 10%" />
			<col style="width: 10%" />
		</colgroup>
			<thead>
				<tr>
					<th>Student</th>
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
				foreach($all_first_student_proposition as $fsp){
					echo '<tr>';
						$show_thesis_first_info_url = new moodle_url("/mod/newmodule/showstudent_first_thesis_info_teacher.php", array('courseid'=>$COURSE->id, 'studentid'=>$fsp->userid));
						$useridarray= array('id'=>$fsp->userid);
						$userdetails=$DB->get_record('user', $useridarray);	 //get info of the user from DB 
						echo '<td>'.$userdetails->firstname." ".$userdetails->lastname.'</td>';				
						echo '<td><a href="'.$show_thesis_first_info_url.'">'.$fsp->first_proposition_a.'</a></td>';
						echo '<td>'.$fsp->date_added.'</td>';
							if ($fsp->first_proposition_approved == '1'){
								echo '<td style="color:green; font-weight:bold;">Approved</td>';	
							}elseif($fsp->first_proposition_approved == '0'){
								echo '<td style="color:red; font-weight:bold;">Not approved</td>';
							}elseif($fsp->first_proposition_approved == '-1'){
								echo '<td style="color:orange; font-weight:bold;">Not approved yet</td>';
							}
							if($fsp->assigned_lecturer_id == '-1'){
								echo '<td style="color:red;">No lecturer assigned yet</td>';
							}
							// print lecturers name and surname
							// elseif(){ 								
							// }
							
						echo '<td>Something</td>';			
					echo '</tr>';
				}
				echo'</tr>
			</tbody>
		</table>';
		}else{
			// $this->content->footer .= '<h5>'.get_string('noSessionsYet', 'block_teleconference_noticeboard').'</h5>';	
			echo "There is nothing submitted";
		}
	
	
}



