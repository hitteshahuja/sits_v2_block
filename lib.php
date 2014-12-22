<?php
require_once('../../config.php');
//Additional Stuff goes here
function course_containers($courses){
	$output = '';
	echo "Yooo!";
	global $OUTPUT;
	$bab_logo = $OUTPUT->pix_url('plus-white','block_sits_v2');
	$output .= sits_network_status();
	$output .= "<div id=\"course_containers\">";
	foreach ($courses as $objCourse) {
		$output .= "<div id=\"course_box_$objCourse->id\" class=\"yui3-toggle sits_course_box\">
		<div class=\"sits_course_title\"><img src=\"$bab_logo\" class='toggle_plus_minus'>$objCourse->fullname</div>
		<div class= \"sits_course_mappings_container\">
		<div class= \"sits_course_mappings_controls\">".mapping_controls(1,1)."</div>
		</div></div>";
	}
	$output .= "</div>";
	return $output;
}

function mapping_controls($course_id,$mapping){
		
	global $CFG,$OUTPUT;
	$output = "";
	$output .= "<div class=\"sits_mapping_control\"><img src=\"$CFG->wwwroot\blocks\sits_v2\pix\disk-black.png\"/>Save </div>";
	$output .="<button type=\"button\" class=\"btn btn-default btn-lg\"><span class=\"glyphicon glyphicon-star\"></span> Star</button>";
	$output .= "<div class=\"sits_mapping_control\" id=\"addcohort_{$course_id}\" onClick=\"M.sits_v2.addCohort();\"><img src=\"$CFG->wwwroot\blocks\sits_v2\pix\plus-circle.png\"/>Add Cohort</div>";
	$output .= "<div class=\"sits_mapping_control\"><img src=\"$CFG->wwwroot\blocks\sits_v2\pix\arrow_refresh.png\"/>Sync Course</div>";
	$output .= "<div class=\"sits_mapping_control\"><img src=\"$CFG->wwwroot\blocks\sits_v2\pix\users.png\"/>View Enrolled Users</div>";
	
	return $output;
	
}
function academic_years($back = 0,$forward = 0){
	$academic_years = array();
	//Current year
	$current_ac_year = date_format(new DateTime(),'Y'); //eg 2013
	
	if($back != 0){
		for($i=0;$i < $back;$i++ ){
		$previous_year = $current_ac_year - $i;
		$next_year = substr($previous_year + 1,2 ); //eg 14
		$academic_years[$previous_year.'/'.$next_year] = $previous_year.'/'.$next_year;
		}
	}
	if($forward != 0){
		for($i=1;$i < $forward;$i++ ){
			$next_year = $current_ac_year + $i;
			$next_year_short = substr($next_year + 1, 2);
			$academic_years["$next_year .'/'.$next_year_short"] = $next_year .'/'.$next_year_short;
		}
	}
	sort($academic_years); //Sort the academic years
	return $academic_years;
}
function add_cohort_form($courseid){
	global $CFG,$OUTPUT;
	require_once($CFG->libdir . '/formslib.php');
	$output = "";
	$output .= "<div class=\"sits_add_cohort_form\" id=\"sits_add_cohort_form_$courseid\"> ";
	$output .= "<label>".get_string('sits_code_label','block_sits_v2')."</label><input type=\"text\" name=\"sits_code\"></input><br/>";
	$academic_years_options = academic_years(5,3);
	$period_codes = simplexml_load_file("period_codes.xml");
	$period_codes = (array) $period_codes;
	$unenrolment_methods = array('Sync','Specified','Manual');
	$output .= "<label>".get_string('sits_ac_year_label','block_sits_v2')."</label>".html_writer::select($academic_years_options,'academic_year','3',false)."<br/>";
	$output .= "<label>".get_string('sits_ac_year_label','block_sits_v2')."</label>".html_writer::select($period_codes['code'],'period_code',null,false)."<br/>";
	$output .= "<label>".get_string('year_of_study_label','block_sits_v2')."</label><input type=\"text\" name=\"sits_code\"></input><br/>";
	$output .= "<label>".get_string('unenrol_method_label','block_sits_v2')."</label>".html_writer::select($unenrolment_methods,'unenrol_method','3',false)."<br/>";
	//$output .="<button type=\"button\" class=\"btn btn-danger btn-lg\"><i class=\"icon-remove\"></i> Remove Mapping</button>";
	//$output .="<button type=\"button\" class=\"btn btn-default btn-lg\"><i class=\"icon-ok-circle\"></i> Create Mapping</button>";
	$output .="<button type=\"button\" class=\"btn btn-primary btn-lg\"><i class=\"icon-ok-sign\"></i> Save Changes</button>";
	$output .="<button type=\"button\" class=\"btn btn-default btn-lg\"><i class=\"icon-plus-sign\"></i> Add Cohort</button>";
	$output .="<button type=\"button\" class=\"btn btn-default btn-lg\"><i class=\"icon-repeat\"></i> Sync Course</button>";
	$output .="<button type=\"button\" class=\"btn btn-default btn-lg\"><i class=\"icon-list\"></i> View enrolled users</button>";
	$output .= "</div>";
	return $output;
	
}
function sits_network_status(){
	global $CFG,$OUTPUT;
	require_once($CFG->dirroot.'/local/sits2/lib/sits.final.class.php');
	$network_online_icon = $OUTPUT->pix_url('network-status','block_sits_v2');
	$network_offline_icon = $OUTPUT->pix_url('network-status-offline','block_sits_v2');
	$report = "";
	$sits = new sits($report);
	$html = "<div id=\"sits_v2_network_status\">";
	$html .= " <span class=\"network_text\"></span>";
	$html .= "<button type=\"button\" onClick=\"M.sits_v2.SITSNetworkStatus();\" class=\"btn btn-default btn-lg\"><i class=\"icon-refresh\"></i></button>";
	//$html .= "<span id=\"sits_network_refresh\"><img src=\"$refresh_icon\" onClick=\"M.sits_v2.SITSNetworkStatus();\"/></span>";
	$html  .="</div>";
	return $html;
}
