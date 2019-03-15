<?php
define("DB_HOST", $_SERVER['DB1_HOST']);
define("DB_NAME", $_SERVER['DB1_NAME']);
define("DB_USER", $_SERVER['DB1_USER']);
define("DB_PASS", $_SERVER['DB1_PASS']);
define("DB_PREDEFINED", true);

class DATABASE_CONFIG
{
public $default = array(
    'driver' => 'mysqli',
    'connect' => 'mysql_pconnect',
    'host'   => DB_HOST,
    'login'   => DB_USER,
    'password'   => DB_PASS,
    'database'   => DB_NAME,
    'prefix'   => ''
  );
}
