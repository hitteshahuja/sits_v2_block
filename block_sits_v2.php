<?php
class block_sits_v2 extends block_base{
	
	public function init() {
        $this->title = get_string('blocktitle', 'block_sits_v2');
    }
	public function get_content(){
		if ($this->content !== null) {
            return $this->content;
        }
        if (!isloggedin()) {
            return $this->content;
        }
		$this->content = new stdClass();
		
		//Show Academic Area
		$this->content->text = $this->academic_area();
		
		/*if(has_capability('moodle/site:config', $context)){
			//Show Admin Area
		}*/
		
		return $this->content;
	}
	public function has_config()
    {
        return true;
    }
	protected function admin_area(){
		
	}
	protected function academic_area(){
		global $CFG,$COURSE;
		$html = "";
		$html .= "<div id=\"academic_container\">";
		$mappings_count = $this->mappings_count();
		$html .= "<ul><li><a href=\"#\" onClick=\"window.open('$CFG->wwwroot/blocks/sits_v2/mappings_interface.php?courseid=$COURSE->id','samis_add_user_interface','height=700,width=587,status=yes,toolbar=no,menubar=no,scrollbars=1,location=no');\">".get_string('manage_mappings', 'block_sits_v2')." (".$mappings_count.") </a></li><li><a href=\"#\">".get_string('add_user', 'block_sits_v2')."</a></li></ul>";
		$html .= "<ul><li><a href=\"#\" onClick=\"window.open('$CFG->wwwroot/blocks/sits_v2/interfaces/admin.php?courseid=$COURSE->id','samis_add_user_interface','height=700,width=587,status=yes,toolbar=no,menubar=no,scrollbars=1,location=no');\">".get_string('manage_mappings', 'block_sits_v2')." (".$mappings_count.") </a></li><li><a href=\"#\">".get_string('add_user', 'block_sits_v2')."</a></li></ul>";
		$html .= "</div>";
		return $html;
	}
/**
 * Show the number of Mappings for the cohort
 */	
 private function mappings_count(){
 	return false;
	global $COURSE,$CFG,$DB;
	require_once($CFG->dirroot . '/enrol/sits/lib.php');
	$mappings = array();
	$sits_sync = new enrol_sits_plugin();
	$mappings_count = 0;
	//Get all the courses for the user
	$user_courses = enrol_get_my_courses(null, "fullname ASC");
    if(!empty($user_courses)){
    	foreach ($user_courses as $course) {
			//Get mappings for the course
			$mappings_count = $mappings_count + $sits_sync->count_mappings_for_course($course->id);
			
			//$mappings[] = $sits_sync->read_mappings_for_course($course->id);
			}
    	}
	if($mappings_count !== 0)
	{
		return $mappings_count;
	}
	}
}
