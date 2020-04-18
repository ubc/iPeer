<?php

/**
 * LtiToolRegistrationFixture
 *
 * @uses      CakeTestFixture
 * @package   CTLT.iPeer
 * @since     3.4.5
 * @author    Steven Marshall <steven.marshall@ubc.ca>
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class LtiToolRegistrationFixture extends CakeTestFixture
{
    public $name = 'LtiToolRegistration';

    public $fields = array(
        'iss' => array('type' => 'string', 'length' => 255, 'null' => false, 'key' => 'primary'),
        'client_id' => array('type' => 'string', 'length' => 255, 'null' => false),
        'auth_login_url' => array('type' => 'string', 'length' => 255, 'null' => false),
        'auth_token_url' => array('type' => 'string', 'length' => 255, 'null' => false),
        'key_set_url' => array('type' => 'string', 'length' => 255, 'null' => false),
        'private_key_file' => array('type' => 'string', 'length' => 255, 'null' => false),
    );

    public $import = array('table' => 'lti_tool_registrations', 'records' => true);
}
