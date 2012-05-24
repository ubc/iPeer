<?php
/* Faculties Test cases generated on: 2012-05-17 14:49:40 : 1337291380*/
App::import('Controller', 'Faculties');

class TestFacultiesController extends FacultiesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class FacultiesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.faculty', 'app.department', 'app.course_department', 'app.course', 'app.group', 'app.user', 'app.evaluation_submission', 'app.event', 'app.event_template_type', 'app.group_event', 'app.user_faculty', 'app.user_course', 'app.user_enrol', 'app.groups_member', 'app.role', 'app.roles_user', 'app.survey', 'app.survey_group_set', 'app.survey_group', 'app.survey_group_member', 'app.question', 'app.response', 'app.survey_question', 'app.sys_parameter');

	function startTest() {
		$this->Faculties =& new TestFacultiesController();
		$this->Faculties->constructClasses();
	}

	function endTest() {
		unset($this->Faculties);
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
