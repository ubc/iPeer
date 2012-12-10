<?php
/* OauthClients Test cases generated on: 2012-08-09 10:57:49 : 1344535069*/
App::import('Controller', 'Oauthclients');

class TestOauthClientsController extends OauthclientsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class OauthClientsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.oauth_client', 'app.user', 'app.evaluation_submission', 'app.event', 'app.event_template_type', 'app.course', 'app.group', 'app.group_event', 'app.groups_member', 'app.survey', 'app.survey_group_set', 'app.survey_group', 'app.survey_group_member', 'app.question', 'app.response', 'app.survey_question', 'app.user_course', 'app.user_tutor', 'app.user_enrol', 'app.department', 'app.faculty', 'app.course_department', 'app.penalty', 'app.user_faculty', 'app.role', 'app.roles_user', 'app.sys_parameter');

	function startTest() {
		$this->OauthClients =& new TestOauthClientsController();
		$this->OauthClients->constructClasses();
	}

	function endTest() {
		unset($this->OauthClients);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testView() {

	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testDelete() {

	}

}
