<?php
/**
 * ExtendedTestCase test classes.
 *
 * @copyright     Copyright 2010, Jeremy Harris
 * @link          http://42pixels.com Jeremy Harris
 * @package			extended_test_case
 * @subpackage    extended_test_case.tests.cases.libs
 */

/**
 * Includes
 */
App::import('Lib', 'ExtendedTestCase.ExtendedTestCase');
App::import('Component', 'RequestHandler');
App::import('Model', 'App');

/**
 * Dummy app model
 *
 * @package			extended_test_case
 * @subpackage    app.tests.cases.libs
 */
class Dummy extends AppModel {
	var $useTable = false;

	var $actsAs = array('Dumber');
}
/**
 * Dummy component
 *
 * @package			extended_test_case
 * @subpackage    extended_test_case.tests.cases.libs
 */
class DumbComponent extends Object {
	var $enabled = false;

	function initialize() {
		$this->enabled = true;
	}

	function beforeRender(&$Controller) {
		$Controller->set('component', 'dumb!');
	}
}
/**
 * Dummy behavior
 *
 * @package			extended_test_case
 * @subpackage    extended_test_case.tests.cases.libs
 */
class DumberBehavior extends ModelBehavior {
	function beforeSave(&$Model) {
		$Model->invalidate('no_db', 'There\'s no database!');
		return false;
	}
}
/**
 * Dummy controller
 *
 * @package			extended_test_case
 * @subpackage    extended_test_case.tests.cases.libs
 */
class DummiesController extends Controller {
	var $name = 'Dummies';

	var $components = array(
		 'Session',
		 'Dumb'
	);

	function dummy_action($var) {
		$this->set('var', $var);
		return true;
	}

	function set_passed_var() {
		if (isset($this->passedArgs['foo'])) {
			$this->set('foo', $this->passedArgs['foo']);
		}
	}

	function test_save() {
		if (!empty($this->data)) {
			$success = $this->Dummy->save($this->data);
			$this->set('saveSuccess', $success);
		}
	}

	function get_me() {
		$this->set('query', $this->params['url']['query']);
	}

	function admin_index() {
		$this->set('admin', true);
	}

	function prefix_action() {
		$this->set('prefix', true);
	}
}
/**
 * Dummy test case
 *
 * @package		extended_test_case
 * @subpackage	extended_test_case.tests.cases.libs
 */
class DummyTestCase extends ExtendedTestCase {
	
	function testMethod() {		
	}
	
	function testAnotherMethod() {		
	}	
}
/**
 * Dummy reporter to capture messages
 *
 * @package		extended_test_case
 * @subpackage	extended_test_case.tests.cases.libs
 */
class MockReporter {
	
	var $messages = array();
	
	function paintSkip($msg) {
		$this->messages[] = $msg;
	}
}

Mock::generatePartial('DummiesController', 'MockDummiesController', array('header', 'render', 'redirect'));
Mock::generatePartial('RequestHandlerComponent', 'MockRequestHandlerComponent', array('_header'));

/**
 * ExtendedTestCase test case
 *
 * @package			extended_test_case
 * @subpackage    extended_test_case.tests.cases.libs
 */
class ExtendedTestCaseTestCase extends CakeTestCase {

	function startTest() {
		$this->ExtendedTestCase =& new ExtendedTestCase();
		$this->Dummies = new DummiesController();
		$this->Dummies->constructClasses();
		$this->Dummies->RequestHandler = new MockRequestHandlerComponent();
		$this->Dummies->Component->initialize($this->Dummies);
		$this->ExtendedTestCase->testController = $this->Dummies;
	}

	function endTest() {
		unset($this->ExtendedTestCase);
		unset($this->Dummies);
		ClassRegistry::flush();
	}
	
	function testGetTests() {
		$Case = new DummyTestCase();
		$Reporter = new MockReporter();
		$Case->_reporter = $Reporter;
		
		$Reporter->messages = array();
		$result = array_values($Case->getTests());
		$expected = array('start', 'startCase', 'testMethod', 'testAnotherMethod', 'endCase', 'end');
		$this->assertEqual($result, $expected);
		$this->assertEqual(count($Reporter->messages), 0);
		
		$Reporter->messages = array();
		$Case->skipSetup = true;
		$result = array_values($Case->getTests());
		$expected = array('startCase', 'testMethod', 'testAnotherMethod', 'endCase');
		$this->assertEqual($result, $expected);
		$this->assertEqual(count($Reporter->messages), 0);
		
		$Reporter->messages = array();
		$Case->testMethods = 'testMethod';
		$Case->skipSetup = false;
		$result = array_values($Case->getTests());
		$expected = array('start', 'startCase', 'testMethod', 'endCase', 'end');
		$this->assertEqual($result, $expected);
		$this->assertEqual(count($Reporter->messages), 1);
		
		$Reporter->messages = array();
		$Case->testMethods = array('testMethod', 'testAnotherMethod');
		$Case->skipSetup = false;
		$result = array_values($Case->getTests());
		$expected = array('start', 'startCase', 'testMethod', 'testAnotherMethod', 'endCase', 'end');
		$this->assertEqual($result, $expected);
		$this->assertEqual(count($Reporter->messages), 0);
		
		$Reporter->messages = array();
		$Case->testMethods = array('testMethod', 'testAnotherMethod');
		$Case->skipSetup = true;
		$result = array_values($Case->getTests());
		$expected = array('startCase', 'testMethod', 'testAnotherMethod', 'endCase');
		$this->assertEqual($result, $expected);
		$this->assertEqual(count($Reporter->messages), 0);
		
		$Reporter->messages = array();
		$Case->testMethods = array('testRandomMissingMethod');
		$Case->skipSetup = true;
		$result = array_values($Case->getTests());
		$expected = array('startCase', 'endCase');
		$this->assertEqual($result, $expected);
		$this->assertEqual(count($Reporter->messages), 2);
	}
	
	function testNotRunningExtendedTestCase() {
		$this->assertTrue(SimpleTest::isIgnored('ExtendedTestCase'));
	}

	function testTestActionVars() {
		$vars = $this->ExtendedTestCase->testAction('/dummies/dummy_action/3');
		$this->assertEqual($vars['var'], 3);

		$vars = $this->ExtendedTestCase->testAction('/dummies/set_passed_var/foo:bar', array(
			'return' => 'vars'
		));
		$this->assertEqual($vars['foo'], 'bar');
	}

	function testGetParams() {
		$vars = $this->ExtendedTestCase->testAction('/dummies/get_me', array(
			'method' => 'get',
			'data' => array(
				'query' => 'This is my query'
			)
		));
		$this->assertEqual($vars['query'], 'This is my query');
	}

	function testExtension() {
		Router::parseExtensions('csv');
		$vars = $this->ExtendedTestCase->testAction('/dummies/dummy_action/test.csv');
		$this->assertEqual($this->Dummies->params['url']['ext'], 'csv');
	}

	function testComponent() {
		$vars = $this->ExtendedTestCase->testAction('/dummies/dummy_action/test');
		$this->assertEqual($vars['component'], 'dumb!');

		$this->assertTrue($this->Dummies->Dumb->enabled);
	}

	function testBehavior() {
		$vars = $this->ExtendedTestCase->testAction('/dummies/test_save/test', array(
			'data' => array(
				'somedata' => 'test'
			)
		));
		$result = array(
			'no_db' => 'There\'s no database!'
		);
		$this->assertEqual($this->Dummies->Dummy->validationErrors, $result);

		$result = array(
			'somedata' => 'test'
		);
		$this->assertEqual($this->Dummies->data, $result);

		$this->assertFalse($vars['saveSuccess']);
	}

	function testPrefixActions() {
		$oldPrefixes = Configure::read('Routing.prefixes');
		Configure::write('Routing.prefixes', array('admin', 'prefix'));
		Router::reload();

		$vars = $this->ExtendedTestCase->testAction('/admin/dummies/');
		$this->assertTrue($vars['admin']);

		$vars = $this->ExtendedTestCase->testAction('/prefix/dummies/action');
		$this->assertTrue($vars['prefix']);

		Configure::write('Routing.prefixes', $oldPrefixes);
	}
	
	function testExtraParams() {
		$this->ExtendedTestCase->testAction('/dummies/dummy_action/form', array(
			'form' => array(
				'test' => 'value'
			),
			'pass' => array(
				'overwrite'
			)
		));
		$this->assertEqual($this->Dummies->params['form'], array('test' => 'value'));
		$this->assertEqual($this->Dummies->params['pass'][0], 'overwrite');
	}

}
?>