
<?php
require_once('../../config.php');

global $CFG,$USER;
require_once($CFG->dirroot . '/blocks/sits_v2/lib.php');
$courseid = required_param('courseid', PARAM_INT);
$course = $DB->get_record('course', array('id'=>$courseid), '*', MUST_EXIST);
$context = context_course::instance($course->id);
require_capability('enrol/manual:config', $context);
require_login($course);
$PAGE->set_pagelayout('popup');
$PAGE->set_title('Manage Mappings');
$PAGE->set_heading('Manage Mappings');
$PAGE->set_url($CFG->wwwroot);
echo $OUTPUT->header();
       $module              = array(
            'name' => 'sits_v2',
            'fullpath' => '/blocks/sits_v2/js/sits_v2.js'
        );
        $params   = array();
        $PAGE->requires->js_init_call('M.sits_v2.init', array(
            $params
        ), false, $module);
		

$user_courses = enrol_get_my_courses(null, "fullname ASC");
//SITS Network Status

echo course_containers($user_courses);
echo add_cohort_form(2);
/*Paging details */
$totalcount = 10;
$page =1;
$perpage = 1;
$pagingurl = $CFG->wwwroot;
echo $OUTPUT->paging_bar($totalcount, $page, $perpage, $pagingurl);
echo $OUTPUT->footer(); 
 ?>