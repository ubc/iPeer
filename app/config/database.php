<?php
class DATABASE_CONFIG {

// =-=-= The used Database =-=-=-=-=-=-=
	
var $default  = array('driver'   => 'mysql',
                     'connect'  => 'mysql_pconnect',
                     'host'     => 'localhost',
                     'login'    => 'ipeer',
                     'password' => '99bobotw',
                     'database' => 'ipeer',
                     'prefix'   => '');  

// =-=-=-= Avaliable Databases =-=-=-=-=-

// The development server
var $burrito = array('driver'   => 'mysql',
                     'connect'  => 'mysql_pconnect',
                     'host'     => '137.82.12.110',
                     'login'    => 'ipeer',
                     'password' => '99bobotw',
                     'database' => 'ipeer',
                     'prefix'   => '');  

// Serge's laptop server
var $sergeLaptop = array('driver'   => 'mysql',
                     'connect'  => 'mysql_pconnect',
                     'host'     => 'localhost',
                     'login'    => 'ipeer',
                     'password' => 'pass.word',
                     'database' => 'ipeer',
                     'prefix'   => '');  

}
?>
