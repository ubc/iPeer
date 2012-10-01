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
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */


/**
 * Configure routing so that if iPeer has not been installed, the user
 * is directed to the installation page. Setup normal router otherwise.
 *
 * iPeer is considered to be installed if the text file installed.txt
 * is located in the CONFIGS directory.
 * */

if (file_exists(CONFIGS.'installed.txt')) {
  // Disable access to the installer by redirecting all attempts to access
  // the installer to the index page. Except for install5, which is needed
  // to tell the user that an install was successful.
  Router::connect('/install/install5', array(
    'controller' => 'install', 'action' => 'install5'));
  // Connect url to groups api
  Router::connect(
    '/:controller/courses/:course_id/groups/:group_id',
    array('action' => 'groups', 'group_id' => null),
    array(
      'course_id' => '[0-9]+',
      'group_id' => '[0-9]+'
    )
  );
  Router::connect('/install/*', array('controller' => 'install'));
  // Connect default index page to the home controller
  Router::connect('/', array('controller' => 'home', 'action' => 'index'));
  // Connect static pages to the pages controller
  Router::connect('/pages/*', array('controller' => 'pages',
    'action' => 'display'));
  // Connect url '/test' to our test controller. For dev use
  Router::connect('/tests', array('controller' => 'tests',
    'action' => 'index'));
  // Authentication routes
  Router::connect('/login', array('controller' => 'users',
    'action' => 'login'));
  Router::connect('/logout', array('controller' => 'users',
    'action' => 'logout'));
  // Connect url to groups api
  Router::connect('/:controller/courses/:course_id/groups/:group_id',
    array('action' => 'groups', 'group_id' => null),
    array('course_id' => '[0-9]+', 'group_id' => '[0-9]+'));
  // Connect url to events api
  Router::connect('/:controller/courses/:course_id/events/:event_id',
    array('action' => 'events', 'event_id' => null),
    array('course_id' => '[0-9]+', 'event_id' => '[0-9]+'));
  // Connect url to grades api
  Router::connect('/:controller/courses/:course_id/events/:event_id/grades/:user_id',
    array('action' => 'grades', 'user_id' => null),
    array('course_id' => '[0-9]+', 'event_id' => '[0-9]+', 'user_id' => '[0-9]+'));
  Router::connect('/:controller/courses/:course_id/departments/:department_id',
    array('action'=> 'courseDepartments'),
    array('course_id' => '[0-9]+', 'department_id' => '[0-9]+'));

} else {
  // Note, order of routes specified matters. If install didn't come first
  // the /* directive would just redirect every page to the index page
  // of install, including the install/install2/, etc. steps of install
  Router::connect('/install/:action/*', array('controller' => 'install'));
  Router::connect('/*', array('controller' => 'install'));
}


?>
