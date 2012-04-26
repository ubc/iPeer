<?php
/**
 * ExtendedTestCase class.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright		Copyright 2010, Jeremy Harris
 * @link			http://42pixels.com Jeremy Harris
 * @package			extended_test_case
 * @subpackage		extended_test_case.libs
 * @license			MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Includes
 */
require_once APP.'config'.DS.'routes.php';

/**
 * Ensure SimpleTest doesn't think this is a test case and that it starts from
 * scratch
 */
SimpleTest::ignore('ExtendedTestCase');
ClassRegistry::flush();

/**
 * ExtendedTestCase class
 *
 * Extends the functionality of CakeTestCase, namely, `testAction()`
 *
 * @package			extended_test_case
 * @subpackage		extended_test_case.libs
 */
class ExtendedTestCase extends CakeTestCase {

/**
 * Whether or not the components have initialized yet
 *
 * @var boolean
 * @access protected
 */
	var $_componentsInitialized = false;

/**
 * The controller we're testing. Set to null to use the original
 * `CakeTestCase::testAction()` function.
 * 
 * @var object
 */
	var $testController = null;
	
/**
 * Methods to test
 * 
 * @param Array of methods to test
 */
	var $testMethods = null;
	
/**
 * Skip *all* database setup entirely - only use if you're not using fixtures!
 * 
 * @var boolean
 */
	var $skipSetup = false;

/**
 * Tests an action using the controller itself and skipping the dispatcher, and
 * returning the view vars.
 *
 * Since `CakeTestCase::testAction` was causing so many problems and is
 * incredibly slow, it is overwritten here to go about it a bit differently.
 * Import `ExtendedTestCase` from 'Lib' and extend test cases using `ExtendedTestCase`
 * instead to gain this functionality.
 *
 * For backwards compatibility with the original `CakeTestCase::testAction`, set
 * `testController` to `null`.
 *
 * ### Options:
 * - `data` Data to pass to the controller
 *
 * ### Limitations:
 * - only reinstantiates the default model
 * - not 100% complete, i.e., some callbacks may not be fired like they would
 *	  if regularly called through the dispatcher
 *
 * @param string $url The url to test
 * @param array $options A list of options
 * @return array The view vars
 * @link http://mark-story.com/posts/view/testing-cakephp-controllers-the-hard-way
 * @link http://mark-story.com/posts/view/testing-cakephp-controllers-mock-objects-edition
 * @link http://www.42pixels.com/blog/testing-controllers-the-slightly-less-hard-way
 */
	function testAction($url = '', $options = array()) {		
		if (is_null($this->testController)) {
			return parent::testAction($url, $options);
		}

		$Controller = $this->testController;

		// reset parameters
		ClassRegistry::flush();		
		$Controller->passedArgs = array();
		$Controller->params = array();
		$Controller->url = null;
		$Controller->action = null;
		$Controller->viewVars = array();
		$keys = ClassRegistry::keys();
		foreach ($keys as $key) {
			if (is_a(ClassRegistry::getObject(Inflector::camelize($key)), 'Model')) {
				ClassRegistry::getObject(Inflector::camelize($key))->create(false);
			}
		}
		$Controller->Session->delete('Message');
		$Controller->activeUser = null;

		$default = array(
			'data' => array(),
			'method' => 'post'
		);
		$options = array_merge($default, $options);

		// set up the controller based on the url
		$urlParams = Router::parse($url);
		$extra = array_diff_key($options, array('data' => null, 'method' => null, 'return' => null));
		$urlParams = array_merge($urlParams, $extra);
		$action = $urlParams['action'];
		$prefix = null;
		$urlParams['url']['url'] = $url;
		if (strtolower($options['method']) == 'get') {
			$urlParams['url'] = array_merge($options['data'], $urlParams['url']);
		} else {
			$Controller->data = $options['data'];
		}
		if (isset($urlParams['prefix'])) {
			$action = $urlParams['prefix'].'_'.$action;
			$prefix = $urlParams['prefix'].'/';
		}
		$Controller->passedArgs = $urlParams['named'];
		$Controller->params = $urlParams;
		$Controller->url = $urlParams;
		$Controller->action = $prefix.$urlParams['plugin'].'/'.$urlParams['controller'].'/'.$urlParams['action'];

		// only initialize the components once
		if ($this->_componentsInitialized === false) {
			$this->_componentsInitialized = true;
			$Controller->Component->initialize($Controller);
		}
		
		$Controller->beforeFilter();
		$Controller->Component->startup($Controller);

		call_user_func_array(array(&$Controller, $action), $urlParams['pass']);

		$Controller->beforeRender();
		$Controller->Component->triggerCallback('beforeRender', $Controller);

		return $Controller->viewVars;
	}
	
/**
 * Overrides `CakeTestCase::getTests()` to allow running a subset of tests within
 * the test case
 * 
 * To run only certain methods, define a `$testMethods` var as an array of test
 * methods you would like to test. All others will be ignored
 * 
 * {{{
 * class MyTestCase extends ExtendedTestCase {
 *   var $testMethods = array('testThis');
 * 
 *   function testThis() {} // will run
 *   function testThat() {} // will not run
 * } 
 * }}}
 * 
 * Additionally, if you are extended this test case class with a class that contains
 * all of your fixtures, you can skip the database setup by setting a `$skipSetup`
 * var to `false` on your test case. This is useful for skipping setting up the
 * database for things such as your helper's test cases.
 * 
 * @return array Array of tests to run
 */
	function getTests() {
		$tests = parent::getTests();
		$testMethods = array_udiff($tests, $this->methods, 'strcasecmp');
		if (!isset($this->testMethods) || empty($this->testMethods)) {
			$this->testMethods = $testMethods;
		}
		if (!is_array($this->testMethods)) {
			$this->testMethods = array($this->testMethods);
		}
		if (isset($this->skipSetup) && $this->skipSetup) {
			$tests = array_udiff($tests, array('start', 'end'), 'strcasecmp');
		}
		if (empty($this->testMethods)) {
			return $tests;
		}
		$removeMethods = array_udiff($testMethods, $this->testMethods, 'strcasecmp');
		$tests = array_udiff($tests, $removeMethods, 'strcasecmp');
		$skipped = array_udiff($testMethods, $this->testMethods, 'strcasecmp');
		foreach ($skipped as $skip) {
			$this->_reporter->paintSkip(sprintf(__('Skipped entire test method: %s', true), $skip));
		}
		return $tests;
	}

}

?>