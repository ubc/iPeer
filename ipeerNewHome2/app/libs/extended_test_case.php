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

App::import('Component', 'Acl');

Mock::generatePartial('AclComponent', 'MockAclComponent', array('check'));

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

    // configure auth
    if (isset($Controller->Auth)) {
      $Controller->Auth->initialize($Controller);
      if (!$Controller->Session->check('Auth.User') && !$Controller->Session->check('User')) {
        $Controller->Session->write('Auth.User', array('id' => 1, 'username' => 'testadmin'));
        $Controller->Session->write('User', array('Group' => array('id' => 1, 'lft' => 1)));
      }
    }
    // configure acl
    if (isset($Controller->Acl)) {
      $Controller->Acl = new MockAclComponent();
      $Controller->Acl->enabled = true;
      $Controller->Acl->setReturnValue('check', true);
    }		

		$Controller->beforeFilter();
		$Controller->Component->startup($Controller);

		call_user_func_array(array(&$Controller, $action), $urlParams['pass']);

		$Controller->beforeRender();
		$Controller->Component->triggerCallback('beforeRender', $Controller);

		return $Controller->viewVars;
	}

}

?>
