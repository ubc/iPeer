<?php
App::import('Model', 'LtiToolRegistration');
App::import('Model', 'LtiPlatformDeployment');

/**
 * Usage:
 * `docker exec -it ipeer_app_unittest bash`
 * `vendor/bin/phing init-test-db`
 * `cake/console/cake -app app testsuite app case models/lti_platform_deployment`
 *
 * @link https://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html#testing-models
 * @package   CTLT.iPeer
 * @since     3.4.5
 * @author    Steven Marshall <steven.marshall@ubc.ca>
 * @copyright 2019 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class LtiPlatformDeploymentTestCase extends CakeTestCase
{
    public $fixtures = array(
        'app.lti_platform_deployment',
        'app.lti_tool_registration',
    );
    public $LtiToolRegistration, $LtiPlatformDeployment;

    function startCase()
    {
        $this->LtiToolRegistration = ClassRegistry::init('LtiToolRegistration');
        $this->LtiPlatformDeployment = ClassRegistry::init('LtiPlatformDeployment');
    }

    function test_filterDeploymentRows_remove_empty_deployment_values()
    {
        $expected = array(
            'LtiToolRegistration' => array(
                'id' => '2',
                'iss' => 'https://lti-ri.imsglobal.org',
                'client_id' => 'ipeer-lti13-001',
                'auth_login_url' => 'https://lti-ri.imsglobal.org/platforms/652/authorizations/new',
                'auth_token_url' => 'https://lti-ri.imsglobal.org/platforms/652/access_tokens',
                'key_set_url' => 'https://lti-ri.imsglobal.org/platforms/652/platform_keys/654.json',
                'tool_private_key_file' => 'app/config/lti13/tool.private.key',
            ),
        );
        $data = array(
            'LtiToolRegistration' => array(
                'id' => '2',
                'iss' => 'https://lti-ri.imsglobal.org',
                'client_id' => 'ipeer-lti13-001',
                'auth_login_url' => 'https://lti-ri.imsglobal.org/platforms/652/authorizations/new',
                'auth_token_url' => 'https://lti-ri.imsglobal.org/platforms/652/access_tokens',
                'key_set_url' => 'https://lti-ri.imsglobal.org/platforms/652/platform_keys/654.json',
                'tool_private_key_file' => 'app/config/lti13/tool.private.key',
            ),
            'LtiPlatformDeployment' => array(
                array(
                    'id' => '1',
                    'lti_tool_registration_id' => '2',
                    'deployment' => '',
                ),
            ),
        );
        $actual = $this->LtiPlatformDeployment->filterDeploymentRows($data);
        $this->assertEqual($expected, $actual);
    }

    function test_filterDeploymentRows_delete_LtiPlatformDeployment_key()
    {
        $expected = array(
            'LtiToolRegistration' => array(
                'id' => '1',
                'iss' => 'https://canvas.instructure.com',
                'client_id' => '10000000000001',
                'auth_login_url' => 'http://canvas.docker/api/lti/authorize_redirect',
                'auth_token_url' => 'http://canvas.docker/login/oauth2/token',
                'key_set_url' => 'http://canvas.docker/api/lti/security/jwks',
                'tool_private_key_file' => 'app/config/lti13/tool.private.key',
            ),
        );
        $data = array(
            'LtiToolRegistration' => array(
                'id' => '1',
                'iss' => 'https://canvas.instructure.com',
                'client_id' => '10000000000001',
                'auth_login_url' => 'http://canvas.docker/api/lti/authorize_redirect',
                'auth_token_url' => 'http://canvas.docker/login/oauth2/token',
                'key_set_url' => 'http://canvas.docker/api/lti/security/jwks',
                'tool_private_key_file' => 'app/config/lti13/tool.private.key',
            ),
            'LtiPlatformDeployment' => array(),
        );
        $actual = $this->LtiPlatformDeployment->filterDeploymentRows($data);
        $this->assertEqual($expected, $actual);
    }

    function test_fillDeploymentRows()
    {
        $expected = array(
            'LtiToolRegistration' => array(
                'id' => '1',
                'iss' => 'https://canvas.instructure.com',
                'client_id' => '10000000000001',
                'auth_login_url' => 'http://canvas.docker/api/lti/authorize_redirect',
                'auth_token_url' => 'http://canvas.docker/login/oauth2/token',
                'key_set_url' => 'http://canvas.docker/api/lti/security/jwks',
                'tool_private_key_file' => 'app/config/lti13/tool.private.key',
            ),
            'LtiPlatformDeployment' => array(
                array(
                    'id' => '2',
                    'lti_tool_registration_id' => '1',
                    'deployment' => '1:4dde05e8ca1973bcca9bffc13e1548820eee93a3',
                ),
                array(
                    'id' => '3',
                    'lti_tool_registration_id' => '1',
                    'deployment' => '2:f97330a96452fc363a34e0ef6d8d0d3e9e1007d2',
                ),
                array(
                    'id' => '4',
                    'lti_tool_registration_id' => '1',
                    'deployment' => '3:d3a2504bba5184799a38f141e8df2335cfa8206d',
                ),
            ),
        );
        $data = array(
            'LtiToolRegistration' => array(
                'id' => '1',
                'iss' => 'https://canvas.instructure.com',
                'client_id' => '10000000000001',
                'auth_login_url' => 'http://canvas.docker/api/lti/authorize_redirect',
                'auth_token_url' => 'http://canvas.docker/login/oauth2/token',
                'key_set_url' => 'http://canvas.docker/api/lti/security/jwks',
                'tool_private_key_file' => 'app/config/lti13/tool.private.key',
            ),
            'LtiPlatformDeployment' => array(
                array(
                    'id' => '2',
                    'deployment' => '1:4dde05e8ca1973bcca9bffc13e1548820eee93a3',
                ),
                array(
                    'id' => '3',
                    'deployment' => '2:f97330a96452fc363a34e0ef6d8d0d3e9e1007d2',
                ),
                array(
                    'id' => '4',
                    'deployment' => '3:d3a2504bba5184799a38f141e8df2335cfa8206d',
                ),
            ),
        );
        $lti_tool_registration_id = $data['LtiToolRegistration']['id'];
        $actual = $this->LtiPlatformDeployment->fillDeploymentRows($data, $lti_tool_registration_id);
        $this->assertEqual($expected, $actual);
    }
}
