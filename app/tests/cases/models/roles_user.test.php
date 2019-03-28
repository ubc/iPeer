<?php
/* RolesUser Test cases generated on: 2012-07-12 16:37:54 : 1342136274*/
App::import('Model', 'RolesUser');

class RolesUserTestCase extends CakeTestCase {
	var $fixtures = array('app.roles_user', 'app.role', 'app.user', 'app.evaluation_submission', 'app.event', 'app.event_template_type', 'app.course', 'app.group', 'app.group_event', 'app.groups_member', 'app.survey', 'app.survey_group_set', 'app.survey_group', 'app.survey_group_member', 'app.question', 'app.response', 'app.survey_question', 'app.user_course', 'app.user_tutor', 'app.user_enrol', 'app.department', 'app.faculty', 'app.course_department', 'app.user_faculty');

	function startTest($method) {
        echo "Start RolesUser model test.\n";
		$this->RolesUser =& ClassRegistry::init('RolesUser');
	}

	function endTest($method) {
		unset($this->RolesUser);
		ClassRegistry::flush();
	}

}
