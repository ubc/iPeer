<?php
/* OauthClient Test cases generated on: 2012-08-09 10:37:16 : 1344533836*/
App::import('Model', 'OauthClient');

class OauthClientTestCase extends CakeTestCase {
	var $fixtures = array('app.oauth_client', 'app.user', 'app.evaluation_submission', 'app.event', 'app.event_template_type', 'app.course', 'app.group', 'app.group_event', 'app.groups_member', 'app.survey', 'app.survey_group_set', 'app.survey_group', 'app.survey_group_member', 'app.question', 'app.response', 'app.survey_question', 'app.user_course', 'app.user_tutor', 'app.user_enrol', 'app.department', 'app.faculty', 'app.course_department', 'app.penalty', 'app.user_faculty', 'app.role', 'app.roles_user');

	function startTest($method) {
		$this->OauthClient =& ClassRegistry::init('OauthClient');
	}

	function endTest($method) {
		unset($this->OauthClient);
		ClassRegistry::flush();
	}

}
