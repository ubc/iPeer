<?php
/* CourseDepartment Test cases generated on: 2012-05-22 12:24:57 : 1337714697*/
App::import('Model', 'CourseDepartment');

class CourseDepartmentTestCase extends CakeTestCase {
	var $fixtures = array('app.course_department', 'app.course', 'app.group', 'app.user', 'app.evaluation_submission', 'app.event', 'app.event_template_type', 'app.group_event', 'app.user_faculty', 'app.faculty', 'app.department', 'app.user_course', 'app.user_enrol', 'app.groups_member', 'app.role', 'app.roles_user', 'app.survey', 'app.survey_group_set', 'app.survey_group', 'app.survey_group_member', 'app.question', 'app.response', 'app.survey_question');

	function startTest($method) {
        echo "Start CourseDepartment model test.\n";
	}

	function endTest($method) {
	}
	
	function testinsertCourses() {
	    //TODO
	}
}
