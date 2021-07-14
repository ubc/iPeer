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
 * @author    Steven Marshall <steven.marshall@ubc.ca>
 * @copyright 2019 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class LtiToolRegistrationTestCase extends CakeTestCase
{
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.lti_user', 'app.lti_nonce', 'app.lti_tool_registration',
        'app.lti_resource_link', 'app.lti_context',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey', 'app.oauth_token',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.mixeval',
        'app.mixeval_question', 'app.mixeval_question_desc', 'app.faculty',
        'app.user_faculty', 'app.department', 'app.course_department',
        'app.sys_parameter', 'app.user_tutor', 'app.penalty',
        'app.evaluation_simple', 'app.survey_input',
        'app.mixeval_question_type', 'app.evaluation_rubric', 'app.evaluation_rubric_detail',
        'app.evaluation_mixeval', 'app.evaluation_mixeval_detail'
    );
    public $LtiToolRegistration;

    function startCase()
    {
        $this->LtiToolRegistration = ClassRegistry::init('LtiToolRegistration');
    }

    function test_findByIss()
    {

        $ret = $this->LtiToolRegistration->findByIss('https://docker-canvas.instructure.com');
        $this->assertEqual($ret['LtiToolRegistration'], array(
            'id' => 1,
            'iss' => 'https://docker-canvas.instructure.com',
            'client_id' => '10000000000013',
            'auth_login_url' => 'http://mock_lti.com/api/lti/authorize_redirect',
            'auth_token_url' => 'http://mock_lti.com/login/oauth2/token',
            'key_set_url' => 'http://mock_lti.com/api/lti/security/jwks',
            'tool_private_key' => "-----BEGIN RSA PRIVATE KEY-----\nMIIEogIBAAKCAQEApBK2zJCDg9s8QDeci6E4QWlTSGav3qh5edjbXULo5Mv0KxN7\nqLyC05QfRrs/I+P5a6D18S/ecAGYPH5xQKuPvqOEotPrRhHCkJB5PtLDsF4ZZbr/\nwWLWG5OYhCkY/H2Wip9rsx1GjKG73EMMTqT2p14K+GW4dg8/HbxQLA4yGeNnGr4D\nd87A+n9wUvMZYAxoCiHiSFD7x0hVIg/q4VXoWHBGEnnqCMC9Gtd7g7HZtQzbYMm4\nm2uY5JHhs+MXS8YKf6Ftc58sJHK5fMtjs9vMVOCkAlrEiEEn+tEHOjSNlzMg+P03\nUU79Lt/MDjXv3mtEPVmPjpJevT4Kjf1HxCSUNQIDAQABAoIBAF5Jmt9IFSwLKz7E\nNqRPS+LbQk8TI/JS4yxQoQ+hSfFh+7ldguzfGFe6gZbGOGzJsCZX475tAelgITpy\nd2bwsLSfh7ODEWu8/RDS1bpyqJ6MFRBPPHbH8775POaGL6O6EG8tWlkec9KRh0H3\nDfWL+2sHMkq5Oh4ueNj/xRrsNYKGLsD0bJMS9eFswDCpvL3fscu/JrrQT+CltBTJ\nj8lTHmGRIF9UOg0Ef1kEgOxcR+AZ2djP3d+zkKZxMATLKWnA1HRFPb7XpJQfjA+k\nsismB4FuhvTSN6IRaci1U5qASHUnIjbTMfFsqJ3h17RivQmSEz1r28OE1HyD/tdE\nIIy3WikCgYEAz6r27/MfEWXgOAwloKJAQi0sqa/0VkWQcNPN3jSgO+AN0iqPLSuj\nAlrLqlLcRlyYAfe7t6B/8SAklJtdy0x9uBaJXrmHhby4jeBksyycQULUzTUhGdbW\nGDfR+XbvNoGUaH1q+vIpglz2jw3N1/M6B/i16wd6Q81UO5WYxQj1j7sCgYEAykJW\nIq4A4UQmtM6gdsXXranV80NOlcH0p521Ec6wpU0dxfI+qVVbT4FxqxfB9Pq5N4V0\nreEYN1ALbjLi3fvChbx8P+lg6k/Tuhn7oiH3kado8iUUR00KyRwWeaMbVwUzU9sQ\nUhB/XfR3J7l3inN/dAlfdSsYbnQJN2U88CKEVM8CgYBfM2UZAz+O3kE38HmfdkI3\nFDaRY9SDaEibML4Dy+RZDpHHczNH5eVIww7y+iF5MCGPZV5tA+sjQzUB22fYNyy7\nI7m97xetu6JviBsh+KV5VYXwvRZ7nf1wBMcBsgBf4G+Ep1pPyIw28x8k3ZMsGJjV\n5rKfGEJ4qryexCnQyhao2QKBgCel71qm/3cpM+k3pA8EY24gn9cq94m11q7Q5IDU\nIp6UymRWQ2BQYjDosA6Y/qV2TL6Mg73eJTAamdMFWKGpS42J0FV6+0uTUG7nzwMO\nY4iC57in+hysBpQ71FAN4DsjwtcKV12u7DjPxlfcLInQcEif2b2PMB/e0Tuxtcth\nCM3TAoGAM+z4u7mi5jxyW9teAYtx3Yb6RGeuly7XvlknV0Lwf2438P2HNZiOa4SE\nSXHZir6LWNv8HOdGapYxUlDfmeNneo4D9B8lBpVs/FsuQF1aOI6B299SlVLPmF+a\nl88qKzXKv7M1pcOv74GK1AIVDF8XJvt1PyaQX92M14q2Ga8Jdjk=\n-----END RSA PRIVATE KEY-----",
            'tool_public_key' => "-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApBK2zJCDg9s8QDeci6E4\nQWlTSGav3qh5edjbXULo5Mv0KxN7qLyC05QfRrs/I+P5a6D18S/ecAGYPH5xQKuP\nvqOEotPrRhHCkJB5PtLDsF4ZZbr/wWLWG5OYhCkY/H2Wip9rsx1GjKG73EMMTqT2\np14K+GW4dg8/HbxQLA4yGeNnGr4Dd87A+n9wUvMZYAxoCiHiSFD7x0hVIg/q4VXo\nWHBGEnnqCMC9Gtd7g7HZtQzbYMm4m2uY5JHhs+MXS8YKf6Ftc58sJHK5fMtjs9vM\nVOCkAlrEiEEn+tEHOjSNlzMg+P03UU79Lt/MDjXv3mtEPVmPjpJevT4Kjf1HxCSU\nNQIDAQAB\n-----END PUBLIC KEY-----",
            'user_identifier_field' => 'https://purl.imsglobal.org/spec/lti/claim/custom|username',
            'student_number_field' => 'https://purl.imsglobal.org/spec/lti/claim/custom|student_number',
            'term_field' => 'https://purl.imsglobal.org/spec/lti/claim/custom|term_name',
            'canvas_id_field' => 'https://purl.imsglobal.org/spec/lti/claim/custom|canvas_course_id',
            'faculty_name_field' => 'https://purl.imsglobal.org/spec/lti/claim/custom|account_name',
        ));

        $ret = $this->LtiToolRegistration->findByIss('');
        $this->assertEqual($ret, array());
    }
}
