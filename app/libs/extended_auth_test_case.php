<?php
App::import('Lib', 'ExtendedTestCase.ExtendedTestCase');

// define a line break according to run environment
if (PHP_SAPI == 'cli') {
    define('TEST_LB', "\n");
} else {
    define('TEST_LB', "<br />");
}

/**
 * ExtendedAuthTestCase based on ExtendedTestCase, added login, afterLogin, logout
 * callback so that we can do authentication within test case
 *
 * @uses ExtendedTestCase
 * @package   IPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 * @version   Release: 3.0
 */
class ExtendedAuthTestCase extends ExtendedTestCase
{
    protected $defaultLogin = null;
    protected $login = null;

    /**
     * testAction
     *
     * @param string $url
     * @param array  $params
     *
     * @access public
     * @return void
     */
    function testAction($url = '', $params = array())
    {
        $Controller = $this->getController();
        if (is_null($Controller)) {
            return parent::testAction($url, $params);
        }

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

        $Controller->__construct();
        $Controller->constructClasses();

        $Controller->Session->delete('Message');
        $Controller->activeUser = null;

        $default = array(
            'return' => 'result',
            'fixturize' => false,
            'data' => array(),
            'method' => 'post',
            'connection' => 'default'
        );

        if (is_string($params)) {
            $params = array('return' => $params);
        }
        $params = array_merge($default, $params);


        $default = array(
            'data' => array(),
            'method' => 'post'
        );
        $params = array_merge($default, $params);

        // set up the controller based on the url
        $urlParams = Router::parse($url);
        $extra = array_diff_key($params, array('data' => null, 'method' => null, 'return' => null));
        $urlParams = array_merge($urlParams, $extra);
        $action = $urlParams['action'];
        $prefix = null;
        $urlParams['url']['url'] = $url;
        if (strtolower($params['method']) == 'get') {
            $urlParams['url'] = array_merge($params['data'], $urlParams['url']);
        } else {
            $Controller->data = $params['data'];
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

        $Controller->Component->initialize($Controller);

        $this->login($Controller);
        $this->afterLogin($Controller);

        $Controller->beforeFilter();
        $Controller->Component->startup($Controller);

        call_user_func_array(array(&$Controller, $action), $urlParams['pass']);

        $Controller->beforeRender();
        $Controller->Component->triggerCallback('beforeRender', $Controller);

        return $Controller->viewVars;
    }

    /**
     * login callback function
     *
     * @param mixed $controller
     *
     * @access protected
     * @return boolean if login is successful
     */
    function login($controller)
    {
        $login = array();
        if (null == $this->login && null == $this->defaultLogin) {
            trigger_error('You have to define at least one login credentials (login or defaultLogin variable)!', E_USER_ERROR);
        }

        $login = (null == $this->login) ? $this->defaultLogin : $this->login;
        // reset login so that next test will not be affected
        $this->login = null;

        return $controller->Auth->login($login);
    }

    /**
     * afterLogin callback function
     *
     * @param mixed $controller
     *
     * @access protected
     * @return void
     */
    function afterLogin($controller)
    {
        User::getInstance($controller->Auth->user());
        $controller->AccessControl->getPermissions();
        $controller->User->loadRoles(User::get('id'));
    }

    /**
     * logout callback function
     *
     * @param mixed $controller
     *
     * @access protected
     * @return boolean if login is successful
     */
    function logout($controller)
    {
    }

    /**
     * getController abstract function to be override by subclass.
     *
     * Subclass can provide a real controller or a partially mocked
     * controller for testing
     *
     * @access protected
     * @return Controller $controller
     */
    function getController() {
        trigger_error('No getController() method has been defined in the test case. You may use Mock controller in the method. Falling back to default testAction.');
        return null;
    }

}

