<?php
/* Faculty Test cases generated on: 2012-05-17 12:27:41 : 1337282861*/
App::import('Model', 'Faculty');

class FacultyTestCase extends CakeTestCase {
	var $fixtures = array('app.faculty', 'app.department', 'app.course_department', 'app.course', 'app.group', 'app.user', 'app.evaluation_submission', 'app.event', 'app.event_template_type', 'app.group_event', 'app.user_faculty', 'app.user_course', 'app.user_enrol', 'app.groups_member', 'app.role', 'app.roles_user', 'app.survey', 'app.survey_group_set', 'app.survey_group', 'app.survey_group_member', 'app.question', 'app.response', 'app.survey_question');

	function startTest($method) {
        echo "Start Faculty model test.\n";
	}

	function endTest($method) {
	}

}
