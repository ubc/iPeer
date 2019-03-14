<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php
 *
 * This is an application wide file to load any function that is not used within a class
 * define. You can also use this to include or require any files in your application.
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
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * App::build(array(
 *     'plugins' => array('/full/path/to/plugins/', '/next/full/path/to/plugins/'),
 *     'models' =>  array('/full/path/to/models/', '/next/full/path/to/models/'),
 *     'views' => array('/full/path/to/views/', '/next/full/path/to/views/'),
 *     'controllers' => array('/full/path/to/controllers/', '/next/full/path/to/controllers/'),
 *     'datasources' => array('/full/path/to/datasources/', '/next/full/path/to/datasources/'),
 *     'behaviors' => array('/full/path/to/behaviors/', '/next/full/path/to/behaviors/'),
 *     'components' => array('/full/path/to/components/', '/next/full/path/to/components/'),
 *     'helpers' => array('/full/path/to/helpers/', '/next/full/path/to/helpers/'),
 *     'vendors' => array('/full/path/to/vendors/', '/next/full/path/to/vendors/'),
 *     'shells' => array('/full/path/to/shells/', '/next/full/path/to/shells/'),
 *     'locales' => array('/full/path/to/locale/', '/next/full/path/to/locale/')
 * ));
 *
 */

/**
 * As of 1.3, additional rules for the inflector are added below
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */

#include_once('fix_mysql.inc.php')

;if (file_exists(dirname(__FILE__).'/bootstrap.local.php')) {
  include('bootstrap.local.php');
}

// because the installed.txt file has been moved from CONFIG to TMP
// we need the following to move the file if the install.txt is still in the old
// place
/*
if (!file_exists(dirname(__FILE__).'/../tmp/installed.txt') && file_exists(dirname(__FILE__).'/installed.txt')) {
    copy(dirname(__FILE__).'/installed.txt', dirname(__FILE__).'/../tmp/installed.txt');
    // in case we can't remove the old file, we will leave it there
    @unlink(dirname(__FILE__).'/installed.txt');
    define('IS_INSTALLED', 1);
} else if (!file_exists(dirname(__FILE__).'/../tmp/installed.txt') && !file_exists(dirname(__FILE__).'/installed.txt')) {
    define('IS_INSTALLED', 0);
} else {
    define('IS_INSTALLED', 1);
}*/

/**
 * Create an empty database.php file if one isn't found.
 *
 * This lets us remove database.php from version control. This was a bit of
 * a pain to version control since each dev install can have different
 * credentials. It was initially added to version control since CakePHP1.3
 * complains loudly if it couldn't find database.php.
 **/
if (!file_exists(CONFIGS.'database.php')) {
  $fd = fopen(CONFIGS.'database.php', 'x');
  fclose($fd);
}

require_once(CONFIGS.'database.php');
if (!defined('DB_PREDEFINED')) {
    define('DB_PREDEFINED', false);
}

function table_exists($conn, $table) {
    $val = mysqli_query($conn, "SELECT 1 FROM $table LIMIT 1");
    return $val !== false;
}

// test if the system has been installed
$db_config = new DATABASE_CONFIG();
if ($db_config->default['driver'] === 'mysqli') {
    $conn = mysqli_connect($db_config->default['host'], $db_config->default['login'], $db_config->default['password'], $db_config->default['database']);
    if (mysqli_errno($conn)) {
        throw new RuntimeException('Please setup database config first in app/config/database.php.');
    }
    $table_name = $db_config->default['prefix'] . 'users';
    if (table_exists($conn, $table_name)) {
        define('IS_INSTALLED', 1);
    } else {
        define('IS_INSTALLED', 0);
    }

    // create session table if needed
    if (Configure::read('Session.save') === 'database' && !table_exists($conn, 'cake_session')) {
        mysqli_query($conn, '
            CREATE TABLE `cake_sessions` (
            `id` varchar(255) NOT NULL,
            `data` text DEFAULT NULL,
            `expires` int(11) DEFAULT NULL, PRIMARY KEY  (`id`)
            );
        ');
    }
    mysqli_close($conn);
} else {
    throw new RuntimeException('Database driver '.$db_config->default['driver'].' is not supported!');
}
