<?php

//$config['Guard.AuthModule.Name'] = 'Ldap';    // Using LDAP module
//$config['Guard.AuthModule.Name'] = 'Shibboleth';    // Using Shibboleth module
$config['Guard.AuthModule.Name'] = getenv('IPEER_AUTH') ? getenv('IPEER_AUTH') : 'Default';     // Using default (build-in) module

$config['Guard.AuthModule.Default'] = array();
$config['Guard.AuthModule.Shibboleth'] = array(
    'sessionInitiatorURL' => 'https://%HOST%/Shibboleth.sso/Login',
    'logoutURL'           => 'https://%HOST%/Shibboleth.sso/Logout',
    'fieldMapping'        => array(
        'eppn'        => 'username',
        'affiliation' => 'role',
    ),
    'mappingRules'        => array(
        'eppn'        => array('/@ubc.ca/' => ''),
        'affiliation' => array('/staff@ubc.ca/' => 'admin'),
    ),
    'loginError'          => 'You have successfully logged in through Shibboleth. But you do not have access this appliction.',
    'loginImageButton'    => '',
    'loginTextButton'     => 'Login',
);

$config['Guard.AuthModule.Ldap'] = array(
    'host' => 'ldaps://ldap.school.ca/',
    'port' => 636,
    'serviceUsername' => 'uid=USERNAME, ou=Special Users, o=school.ca', // username to connect to LDAP
    'servicePassword' => 'PASSWORD', // password to connect to LDAP
    'baseDn' => 'ou=Campus Login, o=school.ca',
    'usernameField' => 'uid',
    'attributeSearchFilters' => array(
//        'uid',
    ),
    'attributeMap' => array(
//        'username' => 'uid',
    ),
    'fallbackInternal' => true,
);

$config['Guard.AuthModule.Cwl'] = array(
    'sessionInitiatorURL' => 'https://www.auth.cwl.ubc.ca/auth/login',
    'applicationID'       => 'ServiceName',
    'applicationPassword' => 'ServicePassword',
    'fieldMapping'        => array(
        'eppn'        => 'username',
        'affiliation' => 'role',
    ),
    'mappingRules'        => array(
        'eppn'        => array('/@ubc.ca/' => ''),
        'affiliation' => array('/staff@ubc.ca/' => 'admin'),
    ),
    'loginError'          => 'You have successfully logged in. But you do not have access this appliction.',
    'loginImageButton'    => '',
    'loginTextButton'     => 'Login',
    // CWL XML-RPC interface URLs: https://www.auth.verf.cwl.ubc.ca/auth/rpc (for verification)
    //                             https://www.auth.cwl.ubc.ca/auth/rpc
    'RPCURL'              => "https://www.auth.cwl.ubc.ca",
    'RPCPath'             => "/auth/rpc",

    /**
     * the name of the function being called through XML-RPC. this is
     * prepended with 'session.' later
     */
    //$CWLFunctionName    => 'getLoginName',
    'functionName'        => 'getIdentities',

    /**
     * the application's ID/name and password as given by the CWL team
     */
    'applicationID'       => '',
    'applicationPassword' => '',
);

function override_from_env(&$config) {
    $prefix = 'IPEER_AUTH_'.strtoupper($config['Guard.AuthModule.Name']).'_';
    $auth_config = &$config['Guard.AuthModule.' . $config['Guard.AuthModule.Name']];
    foreach($_ENV as $k => $v) {
        if (0 === strpos($k, $prefix)) {
            $key_str = substr($k, strlen($prefix)-strlen($k));
            $keys = explode('_', $key_str);
            $step = &$auth_config;
            foreach($keys as $i => $key) {
                if (!array_key_exists($key, $step)) {
                    $step[$key] = ($i == count($keys) - 1) ? $v : array();
                }elseif (array_key_exists($key, $step) && $i == count($keys) - 1) {
                    if (is_bool($step[$key])) {
                        $step[$key] = filter_var($v, FILTER_VALIDATE_BOOLEAN);
                    } elseif (is_int($step[$key])) {
                        $step[$key] = filter_var($v, FILTER_VALIDATE_INT);
                    } elseif (is_array($step[$key])) {
                        $step[$key] = json_decode($v, true);
                    } else {
                        $step[$key] = $v;
                    }
                }
                $step = &$step[$key];
            }
        }
    }
}

override_from_env($config);
