<?php
App::import('Model', 'LtiToolRegistration');

/**
 * Usage:
 * `docker exec -it ipeer_app_unittest bash`
 * `vendor/bin/phing init-test-db`
 * `cake/console/cake -app app testsuite app case models/lti_tool_registration`
 *
 * @link https://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html#testing-models
 * @package   CTLT.iPeer
 * @since     3.4.5
 * @author    Steven Marshall <steven.marshall@ubc.ca>
 * @copyright 2019 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class LtiToolRegistrationTestCase extends CakeTestCase
{
    public $fixtures = array(
        'app.lti_platform_deployment',
        'app.lti_tool_registration',
    );
    public $LtiToolRegistration;

    function startCase()
    {
        $this->LtiToolRegistration = ClassRegistry::init('LtiToolRegistration');
    }

    function test_findIssuers()
    {
        $json = <<<JSON
{
    "https://canvas.instructure.com": {
        "client_id": "10000000000001",
        "auth_login_url": "http://canvas.docker/api/lti/authorize_redirect",
        "auth_token_url": "http://canvas.docker/login/oauth2/token",
        "key_set_url": "http://canvas.docker/api/lti/security/jwks",
        "tool_private_key_file": "app/config/lti13/tool.private.key",
        "deployment": [
            "1:4dde05e8ca1973bcca9bffc13e1548820eee93a3",
            "2:f97330a96452fc363a34e0ef6d8d0d3e9e1007d2",
            "3:d3a2504bba5184799a38f141e8df2335cfa8206d"
        ]
    },
    "https://lti-ri.imsglobal.org": {
        "client_id": "ipeer-lti13-001",
        "auth_login_url": "https://lti-ri.imsglobal.org/platforms/652/authorizations/new",
        "auth_token_url": "https://lti-ri.imsglobal.org/platforms/652/access_tokens",
        "key_set_url": "https://lti-ri.imsglobal.org/platforms/652/platform_keys/654.json",
        "tool_private_key_file": "app/config/lti13/tool.private.key",
        "deployment": [
            "1"
        ]
    }
}
JSON;
        $issuers = $this->LtiToolRegistration->findIssuers();
        $this->assertEqual(json_decode($json, true), $issuers);
    }

    function test_findAll()
    {
        $expected = array(
            '1' => array(
                'Issuer' => 'https://canvas.instructure.com',
                'Settings' => '
"client_id": "10000000000001",
"auth_login_url": "http://canvas.docker/api/lti/authorize_redirect",
"auth_token_url": "http://canvas.docker/login/oauth2/token",
"key_set_url": "http://canvas.docker/api/lti/security/jwks",
"tool_private_key_file": "app/config/lti13/tool.private.key",
"deployment": [
    "1:4dde05e8ca1973bcca9bffc13e1548820eee93a3",
    "2:f97330a96452fc363a34e0ef6d8d0d3e9e1007d2",
    "3:d3a2504bba5184799a38f141e8df2335cfa8206d"
]
',
            ),
            '2' => array(
                'Issuer' => 'https://lti-ri.imsglobal.org',
                'Settings' => '
"client_id": "ipeer-lti13-001",
"auth_login_url": "https://lti-ri.imsglobal.org/platforms/652/authorizations/new",
"auth_token_url": "https://lti-ri.imsglobal.org/platforms/652/access_tokens",
"key_set_url": "https://lti-ri.imsglobal.org/platforms/652/platform_keys/654.json",
"tool_private_key_file": "app/config/lti13/tool.private.key",
"deployment": [
    "1"
]
',
            ),
        );
        $actual = $this->LtiToolRegistration->findAll();
        $this->assertEqual($expected, $actual);
    }
}
