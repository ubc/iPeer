<?php
/**
 * Constants and functions that PHPStan needs to load iPeer's code.
 *
 * CakePHP 1.3 defines these at run time (app/webroot/index.php and
 * cake/config/paths.php) and loads classes with App::import(), not with an
 * autoloader. PHPStan reads cake/ and app/vendors for the classes (see
 * scanDirectories in phpstan.neon); this file gives it the rest.
 */

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__));
define('APP_DIR', 'app');
define('WEBROOT_DIR', 'webroot');
define('WWW_ROOT', ROOT . DS . APP_DIR . DS . WEBROOT_DIR . DS);
define('CAKE_CORE_INCLUDE_PATH', ROOT);
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
define('APP_PATH', ROOT . DS . APP_DIR . DS);

// basics.php defines CakePHP's global functions, such as __(), h(), env() and
// debug(). It loads before paths.php, because paths.php calls env() to define
// some of its path constants.
require ROOT . '/cake/basics.php';
// paths.php defines the path constants, such as APP, TMP and VIEWS, from the
// constants above.
require ROOT . '/cake/config/paths.php';
