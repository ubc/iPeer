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

  // UBC CWL Authentication 
  Router::connect('/__samlwrapper/api/saml/auth', array('controller' => 'homeubcsaml', 'action' => 'index'));
  Router::connect('/__samlwrapper/api/saml/metadata', array('controller' => 'samlmetadata', 'action' => 'index3'));
  Router::connect('/__samlwrapper/api/saml/single_logout', array('plugin' => 'guard', 'controller' => 'guard',
  'action' => 'logout'));

    // Authentication routes
  Router::connect('/login', array('plugin' => 'guard', 'controller' => 'guard',
    'action' => 'login'));
  Router::connect('/loginUBCCWL', array('plugin' => 'guard', 'controller' => 'guard',
    'action' => 'loginUBCCWL'));

  Router::connect('/logout', array('controller' => 'homeubcsamllogout', 'action' => 'index'));

  Router::connect('/loginout/loginByCWL', array('plugin' => 'guard', 'controller' => 'guard',
    'action' => 'logout'));
  // Connect url to groups api
  Router::connect('/:controller/courses/:course_id/groups/:group_id',
    array('action' => 'groups', 'group_id' => null),
    array('course_id' => '[0-9]+', 'group_id' => '[0-9]+'));
  // Connect url to events api
  Router::connect('/:controller/courses/:course_id/events/:event_id',
    array('action' => 'events', 'event_id' => null),
    array('course_id' => '[0-9]+', 'event_id' => '[0-9]+'));
  // Connect to course enrolment
  Router::connect('/:controller/courses/:course_id/users',
    array('action' => 'enrolment'),
    array('course_id' => '[0-9]+'));
  // Connect url to grades api
  Router::connect('/:controller/events/:event_id/grades/:username',
    array('action' => 'grades', 'username' => null),
    array('event_id' => '[0-9]+', 'username' => '.+'));
  // Connect url to departments api
  Router::connect('/:controller/courses/:course_id/departments/:department_id',
    array('action'=> 'courseDepartments'),
    array('course_id' => '[0-9]+', 'department_id' => '[0-9]+'));
  // Connect url to users/events api with filters
  Router::connect('/:controller/users/:username/events/sub/:sub/results/:results',
    array('action' => 'userEvents'),
    array('sub' => '[0-1]', 'results' => '[0-1]'));
  // connect url to users/events/course api with filters
  Router::connect('/:controller/courses/:course_id/users/:username/events/sub/:sub/results/:results',
    array('action' => 'userEvents'),
    array('course_id' => '[0-9]+', 'sub' => '[0-1]', 'results' => '[0-1]'));
  // Connect url to users/events api
  Router::connect('/:controller/users/:username/events',
    array('action' => 'userEvents'));
  // connect url to users/events/course api
  Router::connect('/:controller/courses/:course_id/users/:username/events',
    array('action' => 'userEvents'),
    array('course_id' => '[0-9]+'));
  // connect url to groups/1/users api, for manipulating groupmembers
  Router::connect('/:controller/groups/:group_id/users/:username',
    array('action' => 'groupMembers', 'username' => null),
    array('group_id' => '[0-9]+', 'username' => '.+'));
} else {
  // Note, order of routes specified matters. If install didn't come first
  // the /* directive would just redirect every page to the index page
  // of install, including the install/install2/, etc. steps of install
  Router::connect('/install/:action/*', array('controller' => 'install'));
  Router::connect('/upgrade', array('controller' => 'upgrade', 'action' => 'index'));
  Router::connect('/upgrade/:action', array('controller' => 'upgrade', 'action' => 'index'));
  Router::connect('/*', array('controller' => 'install'));
}
