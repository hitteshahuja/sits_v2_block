<?php
//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class sits_periodslotcode_form extends moodleform{
	
	public function definition(){
		global $CFG;
		$mform = $this->_form;
		$mform->addElement('button', 'intro', 'Add New Period Slot Code');
		$mform->addElement('button', 'intro', 'Save All');
	}
	//Custom validation should be added here
	function validation($data, $files) {
		return array();
	}
}