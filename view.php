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
 * Prints a particular instance of newmodule
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_newmodule
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Replace newmodule with the name of your module and remove this line.

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once('./thesis_forms.php');
require_once('./thesis_functions.php');
require_once(dirname(__FILE__).'/lib.php');
global $PAGE,$OUTPUT,$COURSE,$CFG, $USER, $DB;
$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... newmodule instance ID - it should be named as the first character of the module.
$courseid = optional_param('courseid', SITEID, PARAM_INT);

$context = context_course::instance($courseid);

if ($id) {
    $cm         = get_coursemodule_from_id('newmodule', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $newmodule  = $DB->get_record('newmodule', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $newmodule  = $DB->get_record('newmodule', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $newmodule->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('newmodule', $newmodule->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);


$event = \mod_newmodule\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $newmodule);
$event->trigger();

// Print the page header.

$PAGE->set_url('/mod/newmodule/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($newmodule->name));
$PAGE->set_heading(format_string($course->fullname));

/*
 * Other things you may want to set - remove if not needed.
 * $PAGE->set_cacheable(false);
 * $PAGE->set_focuscontrol('some-html-id');
 * $PAGE->add_body_class('newmodule-'.$somevar);
 */

// Output starts here.
echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($newmodule->intro) {
    echo $OUTPUT->box(format_module_intro('newmodule', $newmodule, $cm->id), 'generalbox mod_introbox', 'newmoduleintro');
}

// Replace the following lines with you own code.

echo $OUTPUT->heading('Please select options below');


$add_thesis_first_info_url = new moodle_url("/mod/newmodule/addstudent_first_thesis_info.php", array('courseid'=>$COURSE->id));
echo "<img style='width:15px; height:15px;' src='http://widgets.future-hawk-content.co.uk/img/misc/i.png'></img><a href='".$add_thesis_first_info_url."'>Add thesis proposition</a>";
$show_thesis_first_info_url = new moodle_url("/mod/newmodule/showstudent_first_thesis_info.php", array('courseid'=>$COURSE->id));
echo "<img style='width:15px; height:15px;' src='http://widgets.future-hawk-content.co.uk/img/misc/i.png'></img><a href='".$show_thesis_first_info_url."'>Show thesis proposition</a>";

/*
	Show submitted info else make the student submit. 
	Lecturer and Course Leader will be able to Thesis only when a student submits something.
*/
echo "</br></br>";
echo '
<style>
table { 
color: #333;
width: 100%; 
border-collapse: 
collapse; border-spacing: 0; 
}


td, th { 
border: 1px solid transparent; /* No more visible border */
height: 30px; 
transition: all 0.3s;  /* Simple transition for hover effect */
text-align: center;
}

th {
background: #DFDFDF;  /* Darken header a bit */
font-weight: bold;
color:#0070a8;
}

td {
background: #FAFAFA;
text-align: center;

}
tr:hover td{ background: #d0dafd; color: #339; } /* Hover cell effect! */
</style>';

$courseContext = context_course::instance($COURSE->id);
// $students = get_role_users(5, $courseContext);
// $lecturers = get_role_users(3, $coursecontext);
// $non_editing_teach = get_role_users(4, $coursecontext);
// $course_leader = get_role_users(9, $coursecontext);
// var_dump($students);

$rolestr = array();
$roles = get_user_roles($courseContext, $USER->id);
foreach($roles as $role){
	// var_dump($role);

	if ($role->shortname == 'student'){
		studentView();

	}elseif($role->shortname == 'teacher'){ // non-editingteacher
		// noneditingteacherView();

	}elseif($role->shortname == 'editingteacher'){ //teacher
		teacherView();

	}
}


// Finish the page.
echo $OUTPUT->footer();






















