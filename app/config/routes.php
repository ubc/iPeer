<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/////// CWL LOGIN //////////

/**
 * Configure routing so that if iPeer has not been installed, the user
 * is directed to the installation page. Setup normal router otherwise.
 *
 * iPeer is considered to be installed if the text file installed.txt
 * is located in the TMP directory.
 * */

if (IS_INSTALLED) {
  // Disable access to the installer by redirecting all attempts to access
  // the installer to the index page. Except for install5, which is needed
  // to tell the user that an install was successful.
  Router::connect('/install/install5', array(
    'controller' => 'install', 'action' => 'install5'));
  Router::connect('/install/*', array('controller' => 'install'));
  // Connect default index page to the home controller
  Router::connect('/', array('controller' => 'home', 'action' => 'index'));
  // Connect static pages to the pages controller
  Router::connect('/pages/*', array('controller' => 'pages',
    'action' => 'display'));
  // Connect url '/test' to our test controller. For dev use
  Router::connect('/tests', array('controller' => 'tests',
    'action' => 'index'));

  // SAML Service Provider endpoints (auth initiation, logout, metadata)
  if (getenv('SAML_SETTINGS')) {
    Router::connect('/public/saml/auth.php', array('controller' => 'saml', 'action' => 'auth'));
    Router::connect('/public/saml/logout.php', array('controller' => 'saml', 'action' => 'logout'));
    Router::connect('/public/saml/metadata.php', array('controller' => 'saml', 'action' => 'metadata'));

    Router::connect('/__samlwrapper/api/saml/auth', array('controller' => 'saml', 'action' => 'acs'));
    Router::connect('/__samlwrapper/api/saml/single_logout', array('controller' => 'saml', 'action' => 'logout'));
  }

  // Authentication routes
  Router::connect('/login', array('plugin' => 'guard', 'controller' => 'guard',
    'action' => 'login'));
  if (getenv('SAML_SETTINGS')) {
    Router::connect('/logout', array('controller' => 'saml', 'action' => 'logout'));
  } else {
    Router::connect('/logout', array('plugin' => 'guard', 'controller' => 'guard', 'action' => 'logout'));
  }

  Router::connect('/loginout/loginByCWL', array('plugin' => 'guard', 'controller' => 'guard',
    'action' => 'logout'));
} else {
  // Note, order of routes specified matters. If install didn't come first
  // the /* directive would just redirect every page to the index page
  // of install, including the install/install2/, etc. steps of install
  Router::connect('/install/:action/*', array('controller' => 'install'));
  Router::connect('/upgrade', array('controller' => 'upgrade', 'action' => 'index'));
  Router::connect('/upgrade/:action', array('controller' => 'upgrade', 'action' => 'index'));
  Router::connect('/*', array('controller' => 'install'));
}
