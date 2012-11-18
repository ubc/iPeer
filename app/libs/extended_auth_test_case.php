<?php
App::import('Lib', 'ExtendedTestCase.ExtendedTestCase');

/**
 * ExtendedAuthTestCase based on ExtendedTestCase, added _startController
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
    /**
     * testAction
     *
     * @param string $url
     * @param bool   $options
     *
     * @access public
     * @return void
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


        $Controller->Component->initialize($Controller);
        $this->_startController($Controller);
        $Controller->beforeFilter();
        $Controller->Component->startup($Controller);

        call_user_func_array(array(&$Controller, $action), $urlParams['pass']);

        $Controller->beforeRender();
        $Controller->Component->triggerCallback('beforeRender', $Controller);

        return $Controller->viewVars;
    }

    /**
     * _startController callback function
     *
     * @param mixed $controller
     *
     * @access protected
     * @return void
     */
    function _startController($controller) {
    }
}
