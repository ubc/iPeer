<?php
/* UserFaculty Test cases generated on: 2012-05-18 16:59:28 : 1337385568*/
App::import('Model', 'UserFaculty');

class UserFacultyTestCase extends CakeTestCase {
	var $fixtures = array('app.user_faculty', 'app.user', 'app.evaluation_submission', 'app.event', 'app.event_template_type', 'app.course', 'app.group', 'app.groups_member', 'app.group_event', 'app.survey', 'app.survey_group_set', 'app.survey_group', 'app.survey_group_member', 'app.question', 'app.response', 'app.survey_question', 'app.course_department', 'app.department', 'app.faculty', 'app.user_course', 'app.user_enrol', 'app.role', 'app.roles_user');

	function startTest() {
		$this->UserFaculty =& ClassRegistry::init('UserFaculty');
	}

	function endTest() {
		unset($this->UserFaculty);
		ClassRegistry::flush();
	}

	function testGetFaculty() {

	}

}
