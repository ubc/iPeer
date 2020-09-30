<?php
// hack to get jwt login and membership mocking working
namespace IMSGlobal\LTI {
    function file_get_contents($string) {
        if ($string == 'http://mock_lti.com/api/lti/security/jwks') {
            return '{
                "keys": [{
                    "alg": "RS256",
                    "e": "AQAB",
                    "kid": "ymMKtYy2-TPvxW1EWO6DKxoDO_SW8QGyNTjBo2WG2EM",
                    "kty": "RSA",
                    "n": "yDQ0JyGesS6gWeO3W0QqlzN0-6ODsbQ86wVvqAsloeSuKYWwlvqjuCqrSJMMpRjaT7wDri8Xq2SGLMq9Yi1k9tXExIKqyIgZ8NROybHRhvEXL0rMWR--03e7tOK_Kv-ok9crLiKeuaB-7NQWlgXqWpgMPKmk2Spa_qj4I1Xidq2MVxzVub9B_FtDyYulHxg-ifNoSkmIKVP-p7a91bYSvXnF1PJQ1QxrFpr0rn7VGQ4_JvykYqRwGkJE_uXw5gAMP8JgXdlak-ntg2tljdvpp6kG9Dh3Vra9NmiehB8vPXBYtUo0QC_zE31BK4vLCUNEBQN5dGYDTwyjYYRkRMdWEK3S2vaoYi5FTZTpXCH0FgZr_8bZygq7CaF9o11QHvUA3vjoAUpMf3KAyeDXIhmnpGbg4BcIHeFsgcua64NNXHPCsmXGDgjG6Up3RT6Z0nDACTO03TXvcjkjA1WPGJpShzG_0eUN4T6u96pxKlPSQ76j083lQ60LOTN9d0wKVw79Xg5OuUx0uW3nQRM157TXjZVad5QZ5Okzcb3kpFhwUqGh0UfsYVt3Io9-jGXN-JFhDYl-VqlTd42eOmno7Kcs9QIUPccqLQGq-tqDAZQWIGUWDdykRC98tJdOOKhVHXrpz7TS2dmEAq21X-NJ62vhzZdGTRbtYG8vS_15wplk6Ns",
                    "use": "sig"
                }]
            }';
        }
        return \file_get_contents($string);
    }
    $CURL_URL_TRACKER = null;
    $CURL_HEADER_SIZE = 0;
    function curl_setopt ($ch, $option, $value) {
        global $CURL_URL_TRACKER;
        if ($option == CURLOPT_URL) {
            $CURL_URL_TRACKER = $value;
        }
        return \curl_setopt($ch, $option, $value);
    }
    function curl_getinfo ($ch, $option) {
        global $CURL_HEADER_SIZE;
        if ($option == CURLINFO_HEADER_SIZE) {
            return $CURL_HEADER_SIZE;
        }
        return \curl_getinfo($ch, $option);
    }
    function curl_exec($ch) {
        global $CURL_URL_TRACKER;
        global $CURL_HEADER_SIZE;

        if ($CURL_URL_TRACKER == 'http://mock_lti.com/login/oauth2/token') {
            $CURL_HEADER_SIZE = 0;
            return '{
                "access_token" : "mock_access_token",
                "stenotype" : "bearer",
                "expires_in" : 3600,
                "scope" : "mock_scope"
            }';
        } elseif ($CURL_URL_TRACKER == 'http://mock_lti.com/api/lti/courses/13/names_and_roles?rlid=mock_lti_context_id') {
            $links = array(
                '<http://mock_lti.com/api/lti/courses/13/names_and_roles?rlid=mock_lti_context_id>; rel="current"',
                '<http://mock_lti.com/api/lti/courses/13/names_and_roles?rlid=mock_lti_context_id&page=2>; rel="next"',
                '<http://mock_lti.com/api/lti/courses/13/names_and_roles?rlid=mock_lti_context_id>; rel="first"',
                '<http://mock_lti.com/api/lti/courses/13/names_and_roles?rlid=mock_lti_context_id&page=2>; rel="last"',
            );
            $headers = array(
                'HTTP/1.1 200 OK',
                'Content-Type: application/vnd.ims.lti-nrps.v2.membershipcontainer+json; charset=utf-8',
                'Status: 200 OK',
                'Link: ' . implode(',', $links),
            );
            $header = implode("\r\n", $headers)."\r\n";
            $CURL_HEADER_SIZE = strlen($header);
            return $header . '{
                "id": "<http://mock_lti.com/api/lti/courses/13/names_and_roles?rlid=mock_lti_context_id",
                "context": {
                    "id": "8796c3851d6e0caa06d5615f656433c2da8c407a",
                    "label": "MECH 328",
                    "title": "Mechanical Engineering Design Project"
                },
                "members": [
                    {
                        "status": "Active",
                        "user_id": "mocked_lti_user_id_instructor1",
                        "given_name": "Instructor.changed",
                        "family_name": "1.changed",
                        "email": "instructor1.changed@email",
                        "roles": [
                            "http://purl.imsglobal.org/vocab/lis/v2/membership#Instructor"
                        ],
                        "message": [
                            {
                                "https://purl.imsglobal.org/spec/lti/claim/message_type": "LtiResourceLinkRequest",
                                "https://purl.imsglobal.org/spec/lti/claim/custom": {
                                    "username": "instructor1",
                                    "student_number": null
                                }
                            }
                        ]
                    },
                    {
                        "status": "Active",
                        "user_id": "mocked_lti_user_id_new_instructor",
                        "given_name": "Instructor",
                        "family_name": "New",
                        "email": "instructor.new@email",
                        "roles": [
                            "http://purl.imsglobal.org/vocab/lis/v2/membership#Instructor"
                        ],
                        "message": [
                            {
                                "https://purl.imsglobal.org/spec/lti/claim/message_type": "LtiResourceLinkRequest",
                                "https://purl.imsglobal.org/spec/lti/claim/custom": {
                                    "username": "new_instructor",
                                    "student_number": null
                                }
                            }
                        ]
                    },
                    {
                        "status": "Active",
                        "user_id": "mocked_lti_user_id_tutor1",
                        "given_name": "Tutor",
                        "family_name": "1",
                        "email": null,
                        "roles": [
                            "http://purl.imsglobal.org/vocab/lis/v2/membership#Instructor",
                            "http://purl.imsglobal.org/vocab/lis/v2/membership/Instructor#TeachingAssistant"
                        ],
                        "message": [
                            {
                                "https://purl.imsglobal.org/spec/lti/claim/message_type": "LtiResourceLinkRequest",
                                "https://purl.imsglobal.org/spec/lti/claim/custom": {
                                    "username": "tutor1",
                                    "student_number": null
                                }
                            }
                        ]
                    },
                    {
                        "status": "Inactive",
                        "user_id": "mocked_lti_user_id_tutor2",
                        "given_name": "Tutor",
                        "family_name": "1",
                        "email": null,
                        "roles": [
                            "http://purl.imsglobal.org/vocab/lis/v2/membership#Instructor",
                            "http://purl.imsglobal.org/vocab/lis/v2/membership/Instructor#TeachingAssistant"
                        ],
                        "message": [
                            {
                                "https://purl.imsglobal.org/spec/lti/claim/message_type": "LtiResourceLinkRequest",
                                "https://purl.imsglobal.org/spec/lti/claim/custom": {
                                    "username": "tutor2",
                                    "student_number": null
                                }
                            }
                        ]
                    },
                    {
                        "status": "Active",
                        "user_id": "mocked_lti_user_id_new_tutor",
                        "given_name": "Tutor",
                        "family_name": "New",
                        "email": "tutor.new@email",
                        "roles": [
                            "http://purl.imsglobal.org/vocab/lis/v2/membership#Instructor",
                            "http://purl.imsglobal.org/vocab/lis/v2/membership/Instructor#TeachingAssistant"
                        ],
                        "message": [
                            {
                                "https://purl.imsglobal.org/spec/lti/claim/message_type": "LtiResourceLinkRequest",
                                "https://purl.imsglobal.org/spec/lti/claim/custom": {
                                    "username": "new_tutor",
                                    "student_number": null
                                }
                            }
                        ]
                    }
                ]
            }';
        } elseif ($CURL_URL_TRACKER == 'http://mock_lti.com/api/lti/courses/13/names_and_roles?rlid=mock_lti_context_id&page=2') {
            $links = array(
                '<http://mock_lti.com/api/lti/courses/13/names_and_roles?rlid=mock_lti_context_id&page=2>; rel="current"',
                '<http://mock_lti.com/api/lti/courses/13/names_and_roles?rlid=mock_lti_context_id>; rel="previous"',
                '<http://mock_lti.com/api/lti/courses/13/names_and_roles?rlid=mock_lti_context_id>; rel="first"',
                '<http://mock_lti.com/api/lti/courses/13/names_and_roles?rlid=mock_lti_context_id&page=2>; rel="last"',
            );
            $headers = array(
                'HTTP/1.1 200 OK',
                'Content-Type: application/vnd.ims.lti-nrps.v2.membershipcontainer+json; charset=utf-8',
                'Status: 200 OK',
                'Link: ' . implode(',', $links),
            );
            $header = implode("\r\n", $headers)."\r\n";
            $CURL_HEADER_SIZE = strlen($header);
            return $header . '{
                "id": "<http://mock_lti.com/api/lti/courses/13/names_and_roles?rlid=mock_lti_context_id",
                "context": {
                    "id": "8796c3851d6e0caa06d5615f656433c2da8c407a",
                    "label": "MECH 328",
                    "title": "Mechanical Engineering Design Project"
                },
                "members": [
                    {
                        "status": "Active",
                        "user_id": "mocked_lti_user_id_redshirt0001",
                        "given_name": "Ed",
                        "family_name": "Student (now instructor)",
                        "email": null,
                        "roles": [
                            "http://purl.imsglobal.org/vocab/lis/v2/membership#Instructor"
                        ],
                        "message": [
                            {
                                "https://purl.imsglobal.org/spec/lti/claim/message_type": "LtiResourceLinkRequest",
                                "https://purl.imsglobal.org/spec/lti/claim/custom": {
                                    "username": "redshirt0001",
                                    "student_number": "65498451"
                                }
                            }
                        ]
                    },
                    {
                        "status": "Active",
                        "user_id": "mocked_lti_user_id_redshirt0002",
                        "given_name": "Alex",
                        "family_name": "Student (now tutor)",
                        "email": null,
                        "roles": [
                            "http://purl.imsglobal.org/vocab/lis/v2/membership#Instructor",
                            "http://purl.imsglobal.org/vocab/lis/v2/membership/Instructor#TeachingAssistant"
                        ],
                        "message": [
                            {
                                "https://purl.imsglobal.org/spec/lti/claim/message_type": "LtiResourceLinkRequest",
                                "https://purl.imsglobal.org/spec/lti/claim/custom": {
                                    "username": "redshirt0002",
                                    "student_number": "65468188"
                                }
                            }
                        ]
                    },
                    {
                        "status": "Active",
                        "user_id": "mocked_lti_user_id_redshirt0003",
                        "given_name": "Matt",
                        "family_name": "Student",
                        "email": null,
                        "roles": [
                            "http://purl.imsglobal.org/vocab/lis/v2/membership#Learner"
                        ],
                        "message": [
                            {
                                "https://purl.imsglobal.org/spec/lti/claim/message_type": "LtiResourceLinkRequest",
                                "https://purl.imsglobal.org/spec/lti/claim/custom": {
                                    "username": "redshirt0003",
                                    "student_number": "98985481"
                                }
                            }
                        ]
                    },
                    {
                        "status": "Inactive",
                        "user_id": "mocked_lti_user_id_redshirt0009",
                        "given_name": "Damien",
                        "family_name": "Student",
                        "email": null,
                        "roles": [
                            "http://purl.imsglobal.org/vocab/lis/v2/membership#Learner"
                        ],
                        "message": [
                            {
                                "https://purl.imsglobal.org/spec/lti/claim/message_type": "LtiResourceLinkRequest",
                                "https://purl.imsglobal.org/spec/lti/claim/custom": {
                                    "username": "redshirt0009",
                                    "student_number": "84188465"
                                }
                            }
                        ]
                    },
                    {
                        "status": "Active",
                        "user_id": "mocked_lti_user_id_new_student",
                        "given_name": "New",
                        "family_name": "Student",
                        "email": "student.new@email",
                        "roles": [
                            "http://purl.imsglobal.org/vocab/lis/v2/membership#Learner"
                        ],
                        "message": [
                            {
                                "https://purl.imsglobal.org/spec/lti/claim/message_type": "LtiResourceLinkRequest",
                                "https://purl.imsglobal.org/spec/lti/claim/custom": {
                                    "username": "new_student",
                                    "student_number": "999999999999"
                                }
                            }
                        ]
                    }
                ]
            }';
        }
        return \curl_exec($ch);
    }
}

namespace {

/* Lti Test cases */
App::import('Lib', 'ExtendedAuthTestCase');
App::import('Controller', 'Lti');

use Firebase\JWT\JWT;

JWT::$leeway = 5;

// mock instead of needing to create a new controller for every test
Mock::generatePartial(
    'LtiController',
    'MockLtiController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header')
);


class LtiControllerTestCase extends ExtendedAuthTestCase {
    public $controller = null;

    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.lti_user', 'app.lti_nonce', 'app.lti_tool_registration',
        'app.lti_resource_link', 'app.lti_context',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey',
        'app.personalize', 'app.penalty', 'app.evaluation_simple',
        'app.faculty', 'app.user_tutor', 'app.course_department',
        'app.evaluation_rubric', 'app.evaluation_rubric_detail',
        'app.evaluation_mixeval', 'app.evaluation_mixeval_detail',
        'app.user_faculty', 'app.department', 'app.sys_parameter',
        'app.oauth_token', 'app.rubric', 'app.rubrics_criteria',
        'app.rubrics_criteria_comment', 'app.rubrics_lom',
        'app.simple_evaluation', 'app.survey_input', 'app.mixeval_question',
        'app.mixeval_question_desc', 'app.mixeval'
    );

    function startCase() {
        echo "Start Lti controller test.\n";
        $this->defaultLogin = array(
            'User' => array(
                'username' => 'root',
                'password' => md5('ipeeripeer')
            )
        );
    }

    function endCase() {
    }

    function startTest($method) {
        echo $method.TEST_LB;
        $this->controller = new MockLtiController();

        $this->login_data = array(
            "iss" => "https://docker-canvas.instructure.com",
            "login_hint" => "135745",
            "target_link_uri" => "https://localhost/lti/launch/",
            "lti_message_hint" => "19481",
            "lti_deployment_id" => "10000:10000000000013",
            "client_id" => "10000000000013",
        );
        $this->base_launch_data = array(
            "https://purl.imsglobal.org/spec/lti/claim/message_type" => "LtiResourceLinkRequest",
            "https://purl.imsglobal.org/spec/lti/claim/resource_link" => [
                "id" => "19481",
                "title" => "Test Link",
                "description" => "N/A"
            ],
            "https://purl.imsglobal.org/spec/lti/claim/context" => [
                "id" => "10045",
                "label" => "Course 123",
                "title" => "Test Course",
                "type" => [
                    "course_offering"
                ]
            ],
            "https://purl.imsglobal.org/spec/lti/claim/tool_platform" => [
                "name" => "Mock Canvas",
                "contact_email" => "",
                "description" => "",
                "url" => "",
                "product_family_code" => "",
                "version" => "1.0",
                "guid" => 1176
            ],
            "https://purl.imsglobal.org/spec/lti-ags/claim/endpoint" => [
                "scope" => [
                    "https://purl.imsglobal.org/spec/lti-ags/scope/lineitem",
                    "https://purl.imsglobal.org/spec/lti-ags/scope/result.readonly",
                    "https://purl.imsglobal.org/spec/lti-ags/scope/score"
                ],
                "lineitems" => "https://lti-ri.imsglobal.org/platforms/1176/contexts/10045/line_items"
            ],
            "https://purl.imsglobal.org/spec/lti-nrps/claim/namesroleservice" => [
                "context_memberships_url" => "https://lti-ri.imsglobal.org/platforms/1176/contexts/10045/memberships",
                "service_versions" => [
                    "2.0"
                ]
            ],
            "iss" => "https://docker-canvas.instructure.com",
            "aud" => "10000000000013",
            "iat" => time(),
            "exp" => time() + (60 * 5), //expires in 5 minutes
            "nonce" => "04aee25a09294ce3b94f532f90c755d21caf6c5e131e11eba5cd0242ac120005",
            "https://purl.imsglobal.org/spec/lti/claim/version" => "1.3.0",
            "locale" => "en-US",
            "https://purl.imsglobal.org/spec/lti/claim/custom" => [
                "canvas_course_id" => "1000",
                "term_name" => "Default Term",
                "account_name" => "Some Account"
            ],
            "https://purl.imsglobal.org/spec/lti/claim/deployment_id" => "10000:10000000000013",
            "https://purl.imsglobal.org/spec/lti/claim/target_link_uri" => "https://localhost/lti/launch/"
        );


        $this->base_instructor_data = array(
            "given_name" => "Millie",
            "family_name" => "Robel",
            "middle_name" => "Cummerata",
            "email" => "Millie.Robel@example.org",
            "name" => "Millie Cummerata Robel",
            "sub" => "instructor_lti_user_id",
            "https://purl.imsglobal.org/spec/lti/claim/roles" => [
                "http://purl.imsglobal.org/vocab/lis/v2/institution/person#Instructor",
                "http://purl.imsglobal.org/vocab/lis/v2/membership#Instructor"
            ],
            "https://purl.imsglobal.org/spec/lti/claim/custom" => [
                "username" => "lti_instructor",
                "student_number" => null
            ],
        );
        $this->instructor_launch_data = array_merge_recursive($this->base_launch_data, $this->base_instructor_data);


        $this->base_ta_data = array(
            "given_name" => "Stevie",
            "family_name" => "PhD",
            "middle_name" => "Kuhn",
            "email" => "Stevie.PhD@example.org",
            "name" => "Stevie Kuhn PhD",
            "sub" => "ta_lti_user_id",
            "https://purl.imsglobal.org/spec/lti/claim/roles" => [
                "http://purl.imsglobal.org/vocab/lis/v2/institution/person#Learner",
                "http://purl.imsglobal.org/vocab/lis/v2/membership#Instructor",
                "http://purl.imsglobal.org/vocab/lis/v2/membership/Instructor#TeachingAssistant"
            ],
            "https://purl.imsglobal.org/spec/lti/claim/custom" => [
                "username" => "lti_ta",
                "student_number" => '1234567890'
            ],
        );
        $this->ta_launch_data = array_merge_recursive($this->base_launch_data, $this->base_ta_data);


        $this->base_student_data = array(
            "given_name" => "Maricela",
            "family_name" => "Nikolaus",
            "middle_name" => "Windler",
            "email" => "Maricela.Nikolaus@example.org",
            "name" => "Maricela Windler Nikolaus",
            "sub" => "student_lti_user_id",
            "https://purl.imsglobal.org/spec/lti/claim/roles" => [
                "http://purl.imsglobal.org/vocab/lis/v2/institution/person#Learner",
                "http://purl.imsglobal.org/vocab/lis/v2/membership#Learner"
            ],
            "https://purl.imsglobal.org/spec/lti/claim/custom" => [
                "username" => "lti_student",
                "student_number" => '0987654321'
            ],
        );
        $this->student_launch_data = array_merge_recursive($this->base_launch_data, $this->base_student_data);

        $this->platform_private_key = '-----BEGIN RSA PRIVATE KEY-----
MIIJKAIBAAKCAgEAyDQ0JyGesS6gWeO3W0QqlzN0+6ODsbQ86wVvqAsloeSuKYWw
lvqjuCqrSJMMpRjaT7wDri8Xq2SGLMq9Yi1k9tXExIKqyIgZ8NROybHRhvEXL0rM
WR++03e7tOK/Kv+ok9crLiKeuaB+7NQWlgXqWpgMPKmk2Spa/qj4I1Xidq2MVxzV
ub9B/FtDyYulHxg+ifNoSkmIKVP+p7a91bYSvXnF1PJQ1QxrFpr0rn7VGQ4/Jvyk
YqRwGkJE/uXw5gAMP8JgXdlak+ntg2tljdvpp6kG9Dh3Vra9NmiehB8vPXBYtUo0
QC/zE31BK4vLCUNEBQN5dGYDTwyjYYRkRMdWEK3S2vaoYi5FTZTpXCH0FgZr/8bZ
ygq7CaF9o11QHvUA3vjoAUpMf3KAyeDXIhmnpGbg4BcIHeFsgcua64NNXHPCsmXG
DgjG6Up3RT6Z0nDACTO03TXvcjkjA1WPGJpShzG/0eUN4T6u96pxKlPSQ76j083l
Q60LOTN9d0wKVw79Xg5OuUx0uW3nQRM157TXjZVad5QZ5Okzcb3kpFhwUqGh0Ufs
YVt3Io9+jGXN+JFhDYl+VqlTd42eOmno7Kcs9QIUPccqLQGq+tqDAZQWIGUWDdyk
RC98tJdOOKhVHXrpz7TS2dmEAq21X+NJ62vhzZdGTRbtYG8vS/15wplk6NsCAwEA
AQKCAgAHKoXdYAuH8w/Ooo3bHfRaUioIodXibqhUOe8WYu5svjyVBDyJwvHznc9g
uya+OBwEXkn8Gk0XbPM34jqSK5F2xmMTLPbY/l99/9N2zmnIvJN5tWHdeiXrTYNc
r6sWam1J0W9caOUpPCX0QIkzGIYcH7TRwmQXWTfwRUhl12VSSIGtUpJnN4HbUGxQ
6Ac34YxyL/zBKqL4O7ycmzgYpg+YqaWxACAa6CVjJevGh91agWiK8uByyXOxH5h2
+UW8rdltD35B4w2mHWmg2nTESvqw7iYaEsquwhQWwPrqPBra8Pez5t06Q50d9aNM
tZBwcJscL398RknYXVmccXqMqF1GY69RKYiIdHfKLwnhSBdJ1w7BiswnllBSDVZL
STZA8dyKozSTnxsBq4PoO3cXGEYKdO+1GZy97h6ByTkaCFXZOdclBvPAyi/A4Ee/
KSsdkUvKryyKfTgzIPXzLsDxhs0hpkI3v7PjKz9uzCvQpq9X3Cd88Gh8Z3MaW4YS
Q4OHTTEUthCR8205uuHm0DXYCTgWv4NrLRJexG/uy7h/enLKFmAfVCT1NUgKxeIS
3uq5yo/0McKekBN/JSYLooAomfKKFwFlc5QY9RSX/MmHxxK1X9BEXA3/c6RA37s8
ymfCq8pKDy4/q3hyux1Tm+kiOikTm1lkPMM0y/qRTab+f3etZQKCAQEA3gxjcKzz
hpyVUkf+sKU1a6qU3IAxmJ2a5mZO+2IOK1ihZYfPwxnjP5DyIhyoG83Fo0GULRiB
RyaMn42hFYPM59ovKqSBPXU4rWGhWqXK80Yl+uS92z4/lfY05R7k8Sz2QcuYwf4d
xyLpjJKEQerrXyHH5MZj6fkJv4WZ2bko/C1H07ac7zcyPx+I/MN2mY1caDVz/2Pw
B+8m21HyVjY3HK10N5QyWdA+l36dmzfFmzagi1WkGFe/WVJ3BUhqqc5SXbKg9A8B
L+MXekU2S7jZ1J8esRxYLUzkeH2fk9OquVubdSVQgOnyHHOTvkkHAowXSdWMKGpC
eHYLAnlkfC55rQKCAQEA5tDCjFOljAptx6Jd5oYfaEEf9KjRajaQsHfAHG2e/UJC
S77BSVyc+ot4lGfN5AuXCDXkOVOt0useG2KxdEHVRycKSVwuRr1y17EjYZ9M+qB3
kbqke3JkXWVt1lVcmBGjHpEdTKSvWqvTOtl8BMW6dnXCXlorj0B6NhAmwROrT+bj
KJNFYQ8O36o7k6NSvlWG55xc7DpLOPeMhym+1liIVwrps+9YI/IlzN+2OhIAjHJn
tokiBV0TT8tEvjoPbaq7IwccyCttdbAge713/L0wLBFsvkDOF3/Npf8DFYAcuFH2
d2jWZ3LiJOKOsmQO0Adv3g48imRRXKERqr2QWrTNpwKCAQEAvXwo56BPeJHqwvp5
F1kES0qYGcqziB8GbpLj15WHrenGYRQScdWHnVkdp4p40rE4dOajghAlUghNfGKq
EegVVc1U7rjPKRj9Msfbn7VXiV5VTtMgSRXHwTsHTHaevEi4JNGPHAy0cJkUYEcv
4eiMzvPO1yWNYb6JWQyzi558oSYq4zo0ldauZDuO9NQAQ2zkbHEg+dHYpYypxgMa
IAPH6AsE3+DxTr9sim8cI7bmRFvLiNueWr+WpKzAsJtpmlpc42RqAZtEUg8im86w
VNH74Xuf/1fGz3GMjl31bXr1d5P7B26+UiRR3YGrlHhRKRVPUkyPfHWhH5bsMkJR
Q7+NSQKCAQBy42SDDruvOh2sqeANd6M4dHoggMtEEAbzH5grTlE+BHYVV8zD5Gpq
t3N8gzLTmQVDW/fOpR03iDqDLRvhH0e20/Ll0xFhurjoLc7Lr8xUT/1UN0/Z9nWI
m40Ri4m8U8Ma2uZ3mN2Dx1UrzMdTZMxMXI80AbP+6Pwr3tw7bLvv2KAnOS7mgeVI
ZWakNT5haRbuQEFsgBOjNmzndlr8PDMZCGCNZMw9kDFKiewdeYp2XhfLnvSlMNAE
/sun2CSH1NyzMb4c0Kj6VIHGted8kPriZIX5KS6sObw2LPnvAMbK5FlG1JMsCN4R
uAeJOg65c4o2QGXYCNkKv02Y7CRnUemvAoIBADWUCHbCfTJf5duKsSnYBLu2RUFV
KVtTYOPbS2kqmVcE+dyCFBcBNKgnA28BJf6tbhSe1+M0+6aQQLj6gG//GTNVV093
B1BVWdrmCX1hf0JmYGfxIkwkgf0e8IcKBKnLOniLtbnWh4O6Cw0PwjF9Wl/W0EL6
r3uNVCiJIY40Exk7duBvJcuxFARVQHflNPfgI2rvAgF7Fb5GVF65DdydXOz7R4rD
g82scjMwgLlmPlM17DIbyJEx0bLkqGHue+HUdEEuGkjy/js0jZts3a2ihOXA+mwq
O+Izt6vChLopGp2MMeJALA12JdUtQa8hmF7GlSWHun5fOAVhcJiSkUNjqM0=
-----END RSA PRIVATE KEY-----';

        $this->platform_public_key = '-----BEGIN PUBLIC KEY-----
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAyDQ0JyGesS6gWeO3W0Qq
lzN0+6ODsbQ86wVvqAsloeSuKYWwlvqjuCqrSJMMpRjaT7wDri8Xq2SGLMq9Yi1k
9tXExIKqyIgZ8NROybHRhvEXL0rMWR++03e7tOK/Kv+ok9crLiKeuaB+7NQWlgXq
WpgMPKmk2Spa/qj4I1Xidq2MVxzVub9B/FtDyYulHxg+ifNoSkmIKVP+p7a91bYS
vXnF1PJQ1QxrFpr0rn7VGQ4/JvykYqRwGkJE/uXw5gAMP8JgXdlak+ntg2tljdvp
p6kG9Dh3Vra9NmiehB8vPXBYtUo0QC/zE31BK4vLCUNEBQN5dGYDTwyjYYRkRMdW
EK3S2vaoYi5FTZTpXCH0FgZr/8bZygq7CaF9o11QHvUA3vjoAUpMf3KAyeDXIhmn
pGbg4BcIHeFsgcua64NNXHPCsmXGDgjG6Up3RT6Z0nDACTO03TXvcjkjA1WPGJpS
hzG/0eUN4T6u96pxKlPSQ76j083lQ60LOTN9d0wKVw79Xg5OuUx0uW3nQRM157TX
jZVad5QZ5Okzcb3kpFhwUqGh0UfsYVt3Io9+jGXN+JFhDYl+VqlTd42eOmno7Kcs
9QIUPccqLQGq+tqDAZQWIGUWDdykRC98tJdOOKhVHXrpz7TS2dmEAq21X+NJ62vh
zZdGTRbtYG8vS/15wplk6NsCAwEAAQ==
-----END PUBLIC KEY-----';

        $this->platform_kid = 'ymMKtYy2-TPvxW1EWO6DKxoDO_SW8QGyNTjBo2WG2EM';

        $this->LtiToolRegistration = ClassRegistry::init('LtiToolRegistration');
        $this->LtiNonce = ClassRegistry::init('LtiNonce');
        $this->LtiContext = ClassRegistry::init('LtiContext');
        $this->LtiResourceLink = ClassRegistry::init('LtiResourceLink');
        $this->LtiUser = ClassRegistry::init('LtiUser');
        $this->Role = ClassRegistry::init('Role');
        $this->User = ClassRegistry::init('User');
        $this->Course = ClassRegistry::init('Course');
        $this->Faculty = ClassRegistry::init('Faculty');

        $this->registration_count = $this->LtiToolRegistration->find('count');
        $this->lti_context_count = $this->LtiContext->find('count');
        $this->lti_resource_link_count = $this->LtiResourceLink->find('count');
        $this->lti_user_count = $this->LtiUser->find('count');
        $this->user_count = $this->User->find('count');
        $this->course_count = $this->Course->find('count');
        $this->faculty_count = $this->Faculty->find('count');
    }

    public function endTest($method)
    {
        // defer logout to end of the test as some of the test need check flash
        // message. After logging out, message is destoryed.
        if (isset($this->controller->Auth)) {
            $this->controller->Auth->logout();
        }
        unset($this->controller);

        unset($_COOKIE['lti1p3_mocked_state']);
        unset($_POST['state']);
        unset($_POST['id_token']);

        unset($_REQUEST['iss']);
        unset($_REQUEST['login_hint']);
        unset($_REQUEST['target_link_uri']);
        unset($_REQUEST['lti_message_hint']);
        unset($_REQUEST['lti_deployment_id']);
        unset($_REQUEST['client_id']);
        ClassRegistry::flush();
    }

    public function getController()
    {
        return $this->controller;
    }

    function resetLaunchNonce() {
        $this->LtiNonce->save(array(
            'LtiNonce' => array(
                'nonce' => $this->base_launch_data['nonce'],
            )
        ));
    }

    function gerenateJWK($payload) {
        return JWT::encode($payload, $this->platform_private_key, 'RS256', $this->platform_kid);
    }

    function gerenateInvalidSignatureJWK($payload) {
        $invalid_private_key = str_replace('a', 'b', $this->platform_private_key);
        return JWT::encode($payload, $invalid_private_key, 'RS256', $this->platform_kid);
    }

    function gerenateInvalidKidJWK($payload) {
        return JWT::encode($payload, $this->platform_private_key, 'RS256', $this->platform_kid."invalid");
    }

    function setupLoginData($data) {
        unset($_REQUEST['iss']);
        unset($_REQUEST['login_hint']);
        unset($_REQUEST['target_link_uri']);
        unset($_REQUEST['lti_message_hint']);
        unset($_REQUEST['lti_deployment_id']);
        unset($_REQUEST['client_id']);
        if (!empty($data['iss'])) {
            $_REQUEST['iss'] = $data['iss'];
        }
        if (!empty($data['login_hint'])) {
            $_REQUEST['login_hint'] = $data['login_hint'];
        }
        if (!empty($data['target_link_uri'])) {
            $_REQUEST['target_link_uri'] = $data['target_link_uri'];
        }
        if (!empty($data['lti_message_hint'])) {
            $_REQUEST['lti_message_hint'] = $data['lti_message_hint'];
        }
        if (!empty($data['lti_deployment_id'])) {
            $_REQUEST['lti_deployment_id'] = $data['lti_deployment_id'];
        }
        if (!empty($data['client_id'])) {
            $_REQUEST['client_id'] = $data['client_id'];
        }
    }

    function setupLaunchData($data) {
        unset($_POST['state']);
        unset($_POST['id_token']);
        if (!empty($data['state'])) {
            $_POST['state'] = $data['state'];
        }
        if (!empty($data['id_token'])) {
            $_POST['id_token'] = $data['id_token'];
        }
    }


    function testLogin() {
        // NOTE: its to hard to extract the redirect url and since there are random elements, just going to check *
        $this->controller->expectOnce('redirect', array('*'));
        //http://mock_lti.com/api/lti/authorize_redirect?scope=openid&response_type=id_token&response_mode=form_post&prompt=none&client_id=10000000000013&redirect_uri=%2Flti%2Flaunch&state=state-*&nonce=nonce-*&login_hint=135745&lti_message_hint=19481

        $this->setupLoginData($this->login_data);
        $this->testAction(
            '/lti/login',
            array('fixturize' => true, 'method' => 'post')
        );
        $message = $this->controller->Session->read('Message.flash');
        $this->assertNull($message);
    }

    function testLoginFailedMissingRequiredParams() {
        $requiredParams = array(
            'iss' => 'Error doing OIDC login: Could not find issuer',
            'login_hint' => 'Error doing OIDC login: Could not find login hint',
        );

        $count = 0;
        foreach($requiredParams as $requiredParam => $expectedMessage) {
            $data_copy = $this->login_data;
            unset($data_copy[$requiredParam]);

            $this->controller->expectCallCount('redirect', ++$count);
            $this->controller->expectArgumentsAt($count-1, 'redirect', array('/home/index'));
            $this->setupLoginData($data_copy);
            $this->testAction(
                '/lti/login',
                array('fixturize' => true, 'method' => 'post')
            );
            $message = $this->controller->Session->read('Message.flash');
            $this->assertEqual($message['message'], $expectedMessage);
        }
    }

    function testLoginSuccessMissingOptionalParams() {
        $optionalParams = array(
            'lti_deployment_id',
            'client_id',
            'lti_message_hint',
            'target_link_uri'
        );

        $count = 0;
        foreach($optionalParams as $optionalParam) {
            $data_copy = $this->login_data;
            unset($data_copy[$optionalParam]);

            $this->controller->expectCallCount('redirect', ++$count);
            $this->controller->expectArgumentsAt($count-1, 'redirect', array('*'));
            $this->setupLoginData($data_copy);
            $this->testAction(
                '/lti/login',
                array('fixturize' => true, 'method' => 'post')
            );
            $message = $this->controller->Session->read('Message.flash');
            $this->assertNull($message);
        }
    }

    function testInstructorLaunch() {
        $_COOKIE['lti1p3_mocked_state'] = 'mocked_state';
        $data = array(
            'state' => 'mocked_state',
            'id_token' => $this->gerenateJWK($this->instructor_launch_data),
        );

        //-- TEST 1: Completely new launch
        $this->resetLaunchNonce();
        $this->controller->expectOnce('redirect', array('/courses/home/5'));
        $this->setupLaunchData($data);
        $this->testAction(
            '/lti/launch',
            array('fixturize' => true, 'method' => 'post')
        );
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'LTI launch success');

        $this->assertEqual($this->registration_count, $this->LtiToolRegistration->find('count'));
        $this->assertEqual(++$this->lti_context_count, $this->LtiContext->find('count'));
        $this->assertEqual(++$this->lti_resource_link_count, $this->LtiResourceLink->find('count'));
        $this->assertEqual(++$this->lti_user_count, $this->LtiUser->find('count'));
        $this->assertEqual(++$this->user_count, $this->User->find('count'));
        $this->assertEqual(++$this->course_count, $this->Course->find('count'));
        $this->assertEqual(++$this->faculty_count, $this->Faculty->find('count'));

        // check faculty
        $faculties = $this->Faculty->find('all');
        $faculty = end($faculties)['Faculty'];
        $this->assertEqual($faculty['name'], 'Some Account');

        // check course
        $courses = $this->Course->find('all');
        $course = end($courses)['Course'];
        $this->assertEqual($course['course'], 'Course 123');
        $this->assertEqual($course['title'], 'Test Course');
        $this->assertNull($course['homepage']);
        $this->assertEqual($course['self_enroll'], 'off');
        $this->assertNull($course['password']);
        $this->assertEqual($course['record_status'], 'A');
        $this->assertEqual($course['canvas_id'], '1000');
        $this->assertEqual($course['term'], 'Default Term');

        // check user
        $users = $this->User->find('all');
        $user = end($users)['User'];
        $this->assertEqual($user['username'], 'lti_instructor');
        $this->assertNotNull($user['password']);
        $this->assertEqual($user['first_name'], 'Millie');
        $this->assertEqual($user['last_name'], 'Robel');
        $this->assertNull($user['student_no']);
        $this->assertEqual($user['title'], '');
        $this->assertEqual($user['email'], 'Millie.Robel@example.org');
        $this->assertEqual($user['record_status'], 'A');

        // check user role
        $roles = $this->Role->find('all', array(
            'conditions' => array('User.id' => $user['id']),
            'recursive' => 0
        ));
        $role_ids = Set::extract('/Role/id', $roles);
        $this->assertEqual($role_ids, array($this->User->USER_TYPE_INSTRUCTOR));

        // check user enrollment
        $student_enrollment_course_ids = $this->User->getEnrolledCourses($user['id']);
        $ta_enrollment_course_ids = $this->User->getTutorCourses($user['id']);
        $instructor_enrollment_course_ids = $this->User->getInstructorCourses($user['id']);
        $this->assertEqual($student_enrollment_course_ids, array());
        $this->assertEqual($ta_enrollment_course_ids, array());
        $this->assertEqual($instructor_enrollment_course_ids, array($course['id']));

        // check lti context
        $lti_contexts = $this->LtiContext->find('all');
        $lti_context = end($lti_contexts)['LtiContext'];
        $this->assertEqual($lti_context['lti_tool_registration_id'], 1);
        $this->assertEqual($lti_context['context_id'], '10045');
        $this->assertEqual($lti_context['course_id'], $course['id']);
        $this->assertEqual($lti_context['nrps_context_memberships_url'], 'https://lti-ri.imsglobal.org/platforms/1176/contexts/10045/memberships');

        // check lti resource link
        $lti_resource_links = $this->LtiResourceLink->find('all');
        $lti_resource_link = end($lti_resource_links)['LtiResourceLink'];
        $this->assertEqual($lti_resource_link['lti_context_id'], $lti_context['id']);
        $this->assertEqual($lti_resource_link['resource_link_id'], '19481');
        $this->assertNull($lti_resource_link['event_id']);
        $this->assertEqual($lti_resource_link['lineitems_url'], 'https://lti-ri.imsglobal.org/platforms/1176/contexts/10045/line_items');
        $this->assertNull($lti_resource_link['lineitem_url']);
        $this->assertEqual($lti_resource_link['scope_lineitem'], 1);
        $this->assertEqual($lti_resource_link['scope_lineitem_read_only'], 0);
        $this->assertEqual($lti_resource_link['scope_result_readonly'], 1);
        $this->assertEqual($lti_resource_link['scope_result_score'], 1);

        // check lti user
        $lti_users = $this->LtiUser->find('all');
        $lti_user = end($lti_users)['LtiUser'];
        $this->assertEqual($lti_user['lti_tool_registration_id'], 1);
        $this->assertEqual($lti_user['lti_user_id'], 'instructor_lti_user_id');
        $this->assertEqual($lti_user['ipeer_user_id'], $user['id']);

        //-- TEST 2: Second launch with zero changes
        $this->resetLaunchNonce();
        $this->controller->expectCallCount('redirect', 2);
        $this->controller->expectArgumentsAt(1, 'redirect', array('/courses/home/5'));
        $this->setupLaunchData($data);
        $this->testAction(
            '/lti/launch',
            array('fixturize' => true, 'method' => 'post')
        );
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'LTI launch success');

        $this->assertEqual($this->registration_count, $this->LtiToolRegistration->find('count'));
        $this->assertEqual($this->lti_context_count, $this->LtiContext->find('count'));
        $this->assertEqual($this->lti_resource_link_count, $this->LtiResourceLink->find('count'));
        $this->assertEqual($this->lti_user_count, $this->LtiUser->find('count'));
        $this->assertEqual($this->user_count, $this->User->find('count'));
        $this->assertEqual($this->course_count, $this->Course->find('count'));
        $this->assertEqual($this->faculty_count, $this->Faculty->find('count'));


        // check faculty
        $faculties = $this->Faculty->find('all');
        $faculty = end($faculties)['Faculty'];
        $this->assertEqual($faculty['name'], 'Some Account');

        // check course
        $courses = $this->Course->find('all');
        $course = end($courses)['Course'];
        $this->assertEqual($course['course'], 'Course 123');
        $this->assertEqual($course['title'], 'Test Course');
        $this->assertNull($course['homepage']);
        $this->assertEqual($course['self_enroll'], 'off');
        $this->assertNull($course['password']);
        $this->assertEqual($course['record_status'], 'A');
        $this->assertEqual($course['canvas_id'], '1000');
        $this->assertEqual($course['term'], 'Default Term');

        // check user
        $users = $this->User->find('all');
        $user = end($users)['User'];
        $this->assertEqual($user['username'], 'lti_instructor');
        $this->assertNotNull($user['password']);
        $this->assertEqual($user['first_name'], 'Millie');
        $this->assertEqual($user['last_name'], 'Robel');
        $this->assertNull($user['student_no']);
        $this->assertEqual($user['title'], '');
        $this->assertEqual($user['email'], 'Millie.Robel@example.org');
        $this->assertEqual($user['record_status'], 'A');

        // check user role
        $roles = $this->Role->find('all', array(
            'conditions' => array('User.id' => $user['id']),
            'recursive' => 0
        ));
        $role_ids = Set::extract('/Role/id', $roles);
        $this->assertEqual($role_ids, array($this->User->USER_TYPE_INSTRUCTOR));

        // check user enrollment
        $student_enrollment_course_ids = $this->User->getEnrolledCourses($user['id']);
        $ta_enrollment_course_ids = $this->User->getTutorCourses($user['id']);
        $instructor_enrollment_course_ids = $this->User->getInstructorCourses($user['id']);
        $this->assertEqual($student_enrollment_course_ids, array());
        $this->assertEqual($ta_enrollment_course_ids, array());
        $this->assertEqual($instructor_enrollment_course_ids, array($course['id']));

        // check lti context
        $lti_contexts = $this->LtiContext->find('all');
        $lti_context = end($lti_contexts)['LtiContext'];
        $this->assertEqual($lti_context['lti_tool_registration_id'], 1);
        $this->assertEqual($lti_context['context_id'], '10045');
        $this->assertEqual($lti_context['course_id'], $course['id']);
        $this->assertEqual($lti_context['nrps_context_memberships_url'], 'https://lti-ri.imsglobal.org/platforms/1176/contexts/10045/memberships');

        // check lti resource link
        $lti_resource_links = $this->LtiResourceLink->find('all');
        $lti_resource_link = end($lti_resource_links)['LtiResourceLink'];
        $this->assertEqual($lti_resource_link['lti_context_id'], $lti_context['id']);
        $this->assertEqual($lti_resource_link['resource_link_id'], '19481');
        $this->assertNull($lti_resource_link['event_id']);
        $this->assertEqual($lti_resource_link['lineitems_url'], 'https://lti-ri.imsglobal.org/platforms/1176/contexts/10045/line_items');
        $this->assertNull($lti_resource_link['lineitem_url']);
        $this->assertEqual($lti_resource_link['scope_lineitem'], 1);
        $this->assertEqual($lti_resource_link['scope_lineitem_read_only'], 0);
        $this->assertEqual($lti_resource_link['scope_result_readonly'], 1);
        $this->assertEqual($lti_resource_link['scope_result_score'], 1);

        // check lti user
        $lti_users = $this->LtiUser->find('all');
        $lti_user = end($lti_users)['LtiUser'];
        $this->assertEqual($lti_user['lti_tool_registration_id'], 1);
        $this->assertEqual($lti_user['lti_user_id'], 'instructor_lti_user_id');
        $this->assertEqual($lti_user['ipeer_user_id'], $user['id']);


        //-- TEST 3: Third launch with many changes (course and user)
        $this->instructor_launch_data = array_replace($this->instructor_launch_data, array(
            // user data
            "given_name" => "MillieAAA",
            "family_name" => "RobelBBB",
            "middle_name" => "CummerataCCC",
            "email" => "MillieAAA.RobelBBB@example.org",
            "name" => "MillieAAA CummerataCCC RobelBBB",
            "https://purl.imsglobal.org/spec/lti/claim/roles" => [
                "http://purl.imsglobal.org/vocab/lis/v2/institution/person#Learner",
                "http://purl.imsglobal.org/vocab/lis/v2/membership#Instructor",
                "http://purl.imsglobal.org/vocab/lis/v2/membership/Instructor#TeachingAssistant"
            ],
            "https://purl.imsglobal.org/spec/lti/claim/custom" => [
                "username" => "lti_instructor",
                "student_number" => '123454321',
                //course
                "canvas_course_id" => "1010",
                "term_name" => "Default Term 2",
                "account_name" => "Some Other Account"
            ],
            // course
            "https://purl.imsglobal.org/spec/lti/claim/context" => [
                "id" => "10045",
                "label" => "Course 456", //doesn't change in ipeer if changes in LMS
                "title" => "Test Course 2",
                "type" => [
                    "course_offering"
                ]
            ],
            "https://purl.imsglobal.org/spec/lti-ags/claim/endpoint" => [
                "scope" => [
                    "https://purl.imsglobal.org/spec/lti-ags/scope/lineitem.readonly"
                ],
                "lineitems" => "https://some_other_url.com",
                "lineitem" => "https://some_other_url2.com",
            ],
            "https://purl.imsglobal.org/spec/lti-nrps/claim/namesroleservice" => [
                "context_memberships_url" => "https://some_other_url3.com",
                "service_versions" => [
                    "2.0"
                ]
            ],
        ));
        $data['id_token'] = $this->gerenateJWK($this->instructor_launch_data);
        $this->resetLaunchNonce();
        $this->controller->expectCallCount('redirect', 3);
        $this->controller->expectArgumentsAt(2, 'redirect', array('/courses/home/5'));
        $this->setupLaunchData($data);
        $this->testAction(
            '/lti/launch',
            array('fixturize' => true, 'method' => 'post')
        );
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'LTI launch success');

        $this->assertEqual($this->registration_count, $this->LtiToolRegistration->find('count'));
        $this->assertEqual($this->lti_context_count, $this->LtiContext->find('count'));
        $this->assertEqual($this->lti_resource_link_count, $this->LtiResourceLink->find('count'));
        $this->assertEqual($this->lti_user_count, $this->LtiUser->find('count'));
        $this->assertEqual($this->user_count, $this->User->find('count'));
        $this->assertEqual($this->course_count, $this->Course->find('count'));
        $this->assertEqual(++$this->faculty_count, $this->Faculty->find('count'));


        // check faculty
        $faculties = $this->Faculty->find('all');
        $faculty = end($faculties)['Faculty'];
        $this->assertEqual($faculty['name'], 'Some Other Account');

        // check course
        $courses = $this->Course->find('all');
        $course = end($courses)['Course'];
        $this->assertEqual($course['course'], 'Course 123');
        $this->assertEqual($course['title'], 'Test Course 2');
        $this->assertNull($course['homepage']);
        $this->assertEqual($course['self_enroll'], 'off');
        $this->assertNull($course['password']);
        $this->assertEqual($course['record_status'], 'A');
        $this->assertEqual($course['canvas_id'], '1010');
        $this->assertEqual($course['term'], 'Default Term 2');

        // check user
        $users = $this->User->find('all');
        $user = end($users)['User'];
        $this->assertEqual($user['username'], 'lti_instructor');
        $this->assertNotNull($user['password']);
        $this->assertEqual($user['first_name'], 'MillieAAA');
        $this->assertEqual($user['last_name'], 'RobelBBB');
        $this->assertEqual($user['student_no'], '123454321');
        $this->assertEqual($user['title'], '');
        $this->assertEqual($user['email'], 'MillieAAA.RobelBBB@example.org');
        $this->assertEqual($user['record_status'], 'A');

        // check user role
        $roles = $this->Role->find('all', array(
            'conditions' => array('User.id' => $user['id']),
            'recursive' => 0
        ));
        $role_ids = Set::extract('/Role/id', $roles);
        $this->assertEqual($role_ids, array($this->User->USER_TYPE_INSTRUCTOR, $this->User->USER_TYPE_TA));

        // check user enrollment
        $student_enrollment_course_ids = $this->User->getEnrolledCourses($user['id']);
        $ta_enrollment_course_ids = $this->User->getTutorCourses($user['id']);
        $instructor_enrollment_course_ids = $this->User->getInstructorCourses($user['id']);
        $this->assertEqual($student_enrollment_course_ids, array());
        $this->assertEqual($ta_enrollment_course_ids, array($course['id']));
        $this->assertEqual($instructor_enrollment_course_ids, array());

        // check lti context
        $lti_contexts = $this->LtiContext->find('all');
        $lti_context = end($lti_contexts)['LtiContext'];
        $this->assertEqual($lti_context['lti_tool_registration_id'], 1);
        $this->assertEqual($lti_context['context_id'], '10045');
        $this->assertEqual($lti_context['course_id'], $course['id']);
        $this->assertEqual($lti_context['nrps_context_memberships_url'], 'https://some_other_url3.com');

        // check lti resource link
        $lti_resource_links = $this->LtiResourceLink->find('all');
        $lti_resource_link = end($lti_resource_links)['LtiResourceLink'];
        $this->assertEqual($lti_resource_link['lti_context_id'], $lti_context['id']);
        $this->assertEqual($lti_resource_link['resource_link_id'], '19481');
        $this->assertNull($lti_resource_link['event_id']);
        $this->assertEqual($lti_resource_link['lineitems_url'], 'https://some_other_url.com');
        $this->assertEqual($lti_resource_link['lineitem_url'], 'https://some_other_url2.com');
        $this->assertEqual($lti_resource_link['scope_lineitem'], 0);
        $this->assertEqual($lti_resource_link['scope_lineitem_read_only'], 1);
        $this->assertEqual($lti_resource_link['scope_result_readonly'], 0);
        $this->assertEqual($lti_resource_link['scope_result_score'], 0);

        // check lti user
        $lti_users = $this->LtiUser->find('all');
        $lti_user = end($lti_users)['LtiUser'];
        $this->assertEqual($lti_user['lti_tool_registration_id'], 1);
        $this->assertEqual($lti_user['lti_user_id'], 'instructor_lti_user_id');
        $this->assertEqual($lti_user['ipeer_user_id'], $user['id']);
    }


    function testTaLaunch() {
        $_COOKIE['lti1p3_mocked_state'] = 'mocked_state';
        $data = array(
            'state' => 'mocked_state',
            'id_token' => $this->gerenateJWK($this->ta_launch_data),
        );

        //-- TEST 1: Completely new launch
        $this->resetLaunchNonce();
        $this->controller->expectOnce('redirect', array('/courses/home/5'));
        $this->setupLaunchData($data);
        $this->testAction(
            '/lti/launch',
            array('fixturize' => true, 'method' => 'post')
        );
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'LTI launch success');

        $this->assertEqual($this->registration_count, $this->LtiToolRegistration->find('count'));
        $this->assertEqual(++$this->lti_context_count, $this->LtiContext->find('count'));
        $this->assertEqual(++$this->lti_resource_link_count, $this->LtiResourceLink->find('count'));
        $this->assertEqual(++$this->lti_user_count, $this->LtiUser->find('count'));
        $this->assertEqual(++$this->user_count, $this->User->find('count'));
        $this->assertEqual(++$this->course_count, $this->Course->find('count'));
        $this->assertEqual(++$this->faculty_count, $this->Faculty->find('count'));

        // check faculty
        $faculties = $this->Faculty->find('all');
        $faculty = end($faculties)['Faculty'];
        $this->assertEqual($faculty['name'], 'Some Account');

        // check course
        $courses = $this->Course->find('all');
        $course = end($courses)['Course'];
        $this->assertEqual($course['course'], 'Course 123');
        $this->assertEqual($course['title'], 'Test Course');
        $this->assertNull($course['homepage']);
        $this->assertEqual($course['self_enroll'], 'off');
        $this->assertNull($course['password']);
        $this->assertEqual($course['record_status'], 'A');
        $this->assertEqual($course['canvas_id'], '1000');
        $this->assertEqual($course['term'], 'Default Term');

        // check user
        $users = $this->User->find('all');
        $user = end($users)['User'];
        $this->assertEqual($user['username'], 'lti_ta');
        $this->assertNotNull($user['password']);
        $this->assertEqual($user['first_name'], 'Stevie');
        $this->assertEqual($user['last_name'], 'PhD');
        $this->assertEqual($user['student_no'], '1234567890');
        $this->assertEqual($user['title'], '');
        $this->assertEqual($user['email'], 'Stevie.PhD@example.org');
        $this->assertEqual($user['record_status'], 'A');

        // check user role
        $roles = $this->Role->find('all', array(
            'conditions' => array('User.id' => $user['id']),
            'recursive' => 0
        ));
        $role_ids = Set::extract('/Role/id', $roles);
        $this->assertEqual($role_ids, array($this->User->USER_TYPE_TA));

        // check user enrollment
        $student_enrollment_course_ids = $this->User->getEnrolledCourses($user['id']);
        $ta_enrollment_course_ids = $this->User->getTutorCourses($user['id']);
        $instructor_enrollment_course_ids = $this->User->getInstructorCourses($user['id']);
        $this->assertEqual($student_enrollment_course_ids, array());
        $this->assertEqual($ta_enrollment_course_ids, array($course['id']));
        $this->assertEqual($instructor_enrollment_course_ids, array());

        // check lti context
        $lti_contexts = $this->LtiContext->find('all');
        $lti_context = end($lti_contexts)['LtiContext'];
        $this->assertEqual($lti_context['lti_tool_registration_id'], 1);
        $this->assertEqual($lti_context['context_id'], '10045');
        $this->assertEqual($lti_context['course_id'], $course['id']);
        $this->assertEqual($lti_context['nrps_context_memberships_url'], 'https://lti-ri.imsglobal.org/platforms/1176/contexts/10045/memberships');

        // check lti resource link
        $lti_resource_links = $this->LtiResourceLink->find('all');
        $lti_resource_link = end($lti_resource_links)['LtiResourceLink'];
        $this->assertEqual($lti_resource_link['lti_context_id'], $lti_context['id']);
        $this->assertEqual($lti_resource_link['resource_link_id'], '19481');
        $this->assertNull($lti_resource_link['event_id']);
        $this->assertEqual($lti_resource_link['lineitems_url'], 'https://lti-ri.imsglobal.org/platforms/1176/contexts/10045/line_items');
        $this->assertNull($lti_resource_link['lineitem_url']);
        $this->assertEqual($lti_resource_link['scope_lineitem'], 1);
        $this->assertEqual($lti_resource_link['scope_lineitem_read_only'], 0);
        $this->assertEqual($lti_resource_link['scope_result_readonly'], 1);
        $this->assertEqual($lti_resource_link['scope_result_score'], 1);

        // check lti user
        $lti_users = $this->LtiUser->find('all');
        $lti_user = end($lti_users)['LtiUser'];
        $this->assertEqual($lti_user['lti_tool_registration_id'], 1);
        $this->assertEqual($lti_user['lti_user_id'], 'ta_lti_user_id');
        $this->assertEqual($lti_user['ipeer_user_id'], $user['id']);

        //-- TEST 2: Second launch with zero changes
        $this->resetLaunchNonce();
        $this->controller->expectCallCount('redirect', 2);
        $this->controller->expectArgumentsAt(1, 'redirect', array('/courses/home/5'));
        $this->setupLaunchData($data);
        $this->testAction(
            '/lti/launch',
            array('fixturize' => true, 'method' => 'post')
        );
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'LTI launch success');

        $this->assertEqual($this->registration_count, $this->LtiToolRegistration->find('count'));
        $this->assertEqual($this->lti_context_count, $this->LtiContext->find('count'));
        $this->assertEqual($this->lti_resource_link_count, $this->LtiResourceLink->find('count'));
        $this->assertEqual($this->lti_user_count, $this->LtiUser->find('count'));
        $this->assertEqual($this->user_count, $this->User->find('count'));
        $this->assertEqual($this->course_count, $this->Course->find('count'));
        $this->assertEqual($this->faculty_count, $this->Faculty->find('count'));


        // check faculty
        $faculties = $this->Faculty->find('all');
        $faculty = end($faculties)['Faculty'];
        $this->assertEqual($faculty['name'], 'Some Account');

        // check course
        $courses = $this->Course->find('all');
        $course = end($courses)['Course'];
        $this->assertEqual($course['course'], 'Course 123');
        $this->assertEqual($course['title'], 'Test Course');
        $this->assertNull($course['homepage']);
        $this->assertEqual($course['self_enroll'], 'off');
        $this->assertNull($course['password']);
        $this->assertEqual($course['record_status'], 'A');
        $this->assertEqual($course['canvas_id'], '1000');
        $this->assertEqual($course['term'], 'Default Term');

        // check user
        $users = $this->User->find('all');
        $user = end($users)['User'];
        $this->assertEqual($user['username'], 'lti_ta');
        $this->assertNotNull($user['password']);
        $this->assertEqual($user['first_name'], 'Stevie');
        $this->assertEqual($user['last_name'], 'PhD');
        $this->assertEqual($user['student_no'], '1234567890');
        $this->assertEqual($user['title'], '');
        $this->assertEqual($user['email'], 'Stevie.PhD@example.org');
        $this->assertEqual($user['record_status'], 'A');

        // check user role
        $roles = $this->Role->find('all', array(
            'conditions' => array('User.id' => $user['id']),
            'recursive' => 0
        ));
        $role_ids = Set::extract('/Role/id', $roles);
        $this->assertEqual($role_ids, array($this->User->USER_TYPE_TA));

        // check user enrollment
        $student_enrollment_course_ids = $this->User->getEnrolledCourses($user['id']);
        $ta_enrollment_course_ids = $this->User->getTutorCourses($user['id']);
        $instructor_enrollment_course_ids = $this->User->getInstructorCourses($user['id']);
        $this->assertEqual($student_enrollment_course_ids, array());
        $this->assertEqual($ta_enrollment_course_ids, array($course['id']));
        $this->assertEqual($instructor_enrollment_course_ids, array());

        // check lti context
        $lti_contexts = $this->LtiContext->find('all');
        $lti_context = end($lti_contexts)['LtiContext'];
        $this->assertEqual($lti_context['lti_tool_registration_id'], 1);
        $this->assertEqual($lti_context['context_id'], '10045');
        $this->assertEqual($lti_context['course_id'], $course['id']);
        $this->assertEqual($lti_context['nrps_context_memberships_url'], 'https://lti-ri.imsglobal.org/platforms/1176/contexts/10045/memberships');

        // check lti resource link
        $lti_resource_links = $this->LtiResourceLink->find('all');
        $lti_resource_link = end($lti_resource_links)['LtiResourceLink'];
        $this->assertEqual($lti_resource_link['lti_context_id'], $lti_context['id']);
        $this->assertEqual($lti_resource_link['resource_link_id'], '19481');
        $this->assertNull($lti_resource_link['event_id']);
        $this->assertEqual($lti_resource_link['lineitems_url'], 'https://lti-ri.imsglobal.org/platforms/1176/contexts/10045/line_items');
        $this->assertNull($lti_resource_link['lineitem_url']);
        $this->assertEqual($lti_resource_link['scope_lineitem'], 1);
        $this->assertEqual($lti_resource_link['scope_lineitem_read_only'], 0);
        $this->assertEqual($lti_resource_link['scope_result_readonly'], 1);
        $this->assertEqual($lti_resource_link['scope_result_score'], 1);

        // check lti user
        $lti_users = $this->LtiUser->find('all');
        $lti_user = end($lti_users)['LtiUser'];
        $this->assertEqual($lti_user['lti_tool_registration_id'], 1);
        $this->assertEqual($lti_user['lti_user_id'], 'ta_lti_user_id');
        $this->assertEqual($lti_user['ipeer_user_id'], $user['id']);


        //-- TEST 3: Third launch with many changes (course and user)
        $this->ta_launch_data = array_replace($this->ta_launch_data, array(
            // user data
            "given_name" => "StevieAAA",
            "family_name" => "PhDBBB",
            "middle_name" => "KuhnCCC",
            "email" => "StevieAAA.PhDBBB@example.org",
            "name" => "StevieAAA KuhnCCC PhDBBB",
            "https://purl.imsglobal.org/spec/lti/claim/roles" => [
                "http://purl.imsglobal.org/vocab/lis/v2/institution/person#Learner",
                "http://purl.imsglobal.org/vocab/lis/v2/membership#Learner"
            ],
            "https://purl.imsglobal.org/spec/lti/claim/custom" => [
                "username" => "lti_ta",
                "student_number" => '543212345',
                //course
                "canvas_course_id" => "1010",
                "term_name" => "Default Term 2",
                "account_name" => "Some Other Account"
            ],
            // course
            "https://purl.imsglobal.org/spec/lti/claim/context" => [
                "id" => "10045",
                "label" => "Course 456", //doesn't change in ipeer if changes in LMS
                "title" => "Test Course 2",
                "type" => [
                    "course_offering"
                ]
            ],
            "https://purl.imsglobal.org/spec/lti-ags/claim/endpoint" => [
                "scope" => [
                    "https://purl.imsglobal.org/spec/lti-ags/scope/lineitem.readonly"
                ],
                "lineitems" => "https://some_other_url.com",
                "lineitem" => "https://some_other_url2.com",
            ],
            "https://purl.imsglobal.org/spec/lti-nrps/claim/namesroleservice" => [
                "context_memberships_url" => "https://some_other_url3.com",
                "service_versions" => [
                    "2.0"
                ]
            ],
        ));
        $data['id_token'] = $this->gerenateJWK($this->ta_launch_data);
        $this->resetLaunchNonce();
        $this->controller->expectCallCount('redirect', 3);
        $this->controller->expectArgumentsAt(2, 'redirect', array('/home/index'));
        $this->setupLaunchData($data);
        $this->testAction(
            '/lti/launch',
            array('fixturize' => true, 'method' => 'post')
        );
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'LTI launch success');

        $this->assertEqual($this->registration_count, $this->LtiToolRegistration->find('count'));
        $this->assertEqual($this->lti_context_count, $this->LtiContext->find('count'));
        $this->assertEqual($this->lti_resource_link_count, $this->LtiResourceLink->find('count'));
        $this->assertEqual($this->lti_user_count, $this->LtiUser->find('count'));
        $this->assertEqual($this->user_count, $this->User->find('count'));
        $this->assertEqual($this->course_count, $this->Course->find('count'));
        $this->assertEqual(++$this->faculty_count, $this->Faculty->find('count'));


        // check faculty
        $faculties = $this->Faculty->find('all');
        $faculty = end($faculties)['Faculty'];
        $this->assertEqual($faculty['name'], 'Some Other Account');

        // check course
        $courses = $this->Course->find('all');
        $course = end($courses)['Course'];
        $this->assertEqual($course['course'], 'Course 123');
        $this->assertEqual($course['title'], 'Test Course 2');
        $this->assertNull($course['homepage']);
        $this->assertEqual($course['self_enroll'], 'off');
        $this->assertNull($course['password']);
        $this->assertEqual($course['record_status'], 'A');
        $this->assertEqual($course['canvas_id'], '1010');
        $this->assertEqual($course['term'], 'Default Term 2');

        // check user
        $users = $this->User->find('all');
        $user = end($users)['User'];
        $this->assertEqual($user['username'], 'lti_ta');
        $this->assertNotNull($user['password']);
        $this->assertEqual($user['first_name'], 'StevieAAA');
        $this->assertEqual($user['last_name'], 'PhDBBB');
        $this->assertEqual($user['student_no'], '543212345');
        $this->assertEqual($user['title'], '');
        $this->assertEqual($user['email'], 'StevieAAA.PhDBBB@example.org');
        $this->assertEqual($user['record_status'], 'A');

        // check user role
        $roles = $this->Role->find('all', array(
            'conditions' => array('User.id' => $user['id']),
            'recursive' => 0
        ));
        $role_ids = Set::extract('/Role/id', $roles);
        $this->assertEqual($role_ids, array($this->User->USER_TYPE_TA, $this->User->USER_TYPE_STUDENT));

        // check user enrollment
        $student_enrollment_course_ids = $this->User->getEnrolledCourses($user['id']);
        $ta_enrollment_course_ids = $this->User->getTutorCourses($user['id']);
        $instructor_enrollment_course_ids = $this->User->getInstructorCourses($user['id']);
        $this->assertEqual($student_enrollment_course_ids, array($course['id']));
        $this->assertEqual($ta_enrollment_course_ids, array());
        $this->assertEqual($instructor_enrollment_course_ids, array());

        // check lti context
        $lti_contexts = $this->LtiContext->find('all');
        $lti_context = end($lti_contexts)['LtiContext'];
        $this->assertEqual($lti_context['lti_tool_registration_id'], 1);
        $this->assertEqual($lti_context['context_id'], '10045');
        $this->assertEqual($lti_context['course_id'], $course['id']);
        $this->assertEqual($lti_context['nrps_context_memberships_url'], 'https://some_other_url3.com');

        // check lti resource link
        $lti_resource_links = $this->LtiResourceLink->find('all');
        $lti_resource_link = end($lti_resource_links)['LtiResourceLink'];
        $this->assertEqual($lti_resource_link['lti_context_id'], $lti_context['id']);
        $this->assertEqual($lti_resource_link['resource_link_id'], '19481');
        $this->assertNull($lti_resource_link['event_id']);
        $this->assertEqual($lti_resource_link['lineitems_url'], 'https://some_other_url.com');
        $this->assertEqual($lti_resource_link['lineitem_url'], 'https://some_other_url2.com');
        $this->assertEqual($lti_resource_link['scope_lineitem'], 0);
        $this->assertEqual($lti_resource_link['scope_lineitem_read_only'], 1);
        $this->assertEqual($lti_resource_link['scope_result_readonly'], 0);
        $this->assertEqual($lti_resource_link['scope_result_score'], 0);

        // check lti user
        $lti_users = $this->LtiUser->find('all');
        $lti_user = end($lti_users)['LtiUser'];
        $this->assertEqual($lti_user['lti_tool_registration_id'], 1);
        $this->assertEqual($lti_user['lti_user_id'], 'ta_lti_user_id');
        $this->assertEqual($lti_user['ipeer_user_id'], $user['id']);
    }

    function testStudentLaunch() {
        $_COOKIE['lti1p3_mocked_state'] = 'mocked_state';
        $data = array(
            'state' => 'mocked_state',
            'id_token' => $this->gerenateJWK($this->student_launch_data),
        );

        //-- TEST 1: Completely new launch
        $this->resetLaunchNonce();
        $this->controller->expectOnce('redirect', array('/home/index'));
        $this->setupLaunchData($data);
        $this->testAction(
            '/lti/launch',
            array('fixturize' => true, 'method' => 'post')
        );
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'LTI launch success');

        $this->assertEqual($this->registration_count, $this->LtiToolRegistration->find('count'));
        $this->assertEqual(++$this->lti_context_count, $this->LtiContext->find('count'));
        $this->assertEqual(++$this->lti_resource_link_count, $this->LtiResourceLink->find('count'));
        $this->assertEqual(++$this->lti_user_count, $this->LtiUser->find('count'));
        $this->assertEqual(++$this->user_count, $this->User->find('count'));
        $this->assertEqual(++$this->course_count, $this->Course->find('count'));
        $this->assertEqual(++$this->faculty_count, $this->Faculty->find('count'));

        // check faculty
        $faculties = $this->Faculty->find('all');
        $faculty = end($faculties)['Faculty'];
        $this->assertEqual($faculty['name'], 'Some Account');

        // check course
        $courses = $this->Course->find('all');
        $course = end($courses)['Course'];
        $this->assertEqual($course['course'], 'Course 123');
        $this->assertEqual($course['title'], 'Test Course');
        $this->assertNull($course['homepage']);
        $this->assertEqual($course['self_enroll'], 'off');
        $this->assertNull($course['password']);
        $this->assertEqual($course['record_status'], 'A');
        $this->assertEqual($course['canvas_id'], '1000');
        $this->assertEqual($course['term'], 'Default Term');

        // check user
        $users = $this->User->find('all');
        $user = end($users)['User'];
        $this->assertEqual($user['username'], 'lti_student');
        $this->assertNotNull($user['password']);
        $this->assertEqual($user['first_name'], 'Maricela');
        $this->assertEqual($user['last_name'], 'Nikolaus');
        $this->assertEqual($user['student_no'], '0987654321');
        $this->assertEqual($user['title'], '');
        $this->assertEqual($user['email'], 'Maricela.Nikolaus@example.org');
        $this->assertEqual($user['record_status'], 'A');

        // check user role
        $roles = $this->Role->find('all', array(
            'conditions' => array('User.id' => $user['id']),
            'recursive' => 0
        ));
        $role_ids = Set::extract('/Role/id', $roles);
        $this->assertEqual($role_ids, array($this->User->USER_TYPE_STUDENT));

        // check user enrollment
        $student_enrollment_course_ids = $this->User->getEnrolledCourses($user['id']);
        $ta_enrollment_course_ids = $this->User->getTutorCourses($user['id']);
        $instructor_enrollment_course_ids = $this->User->getInstructorCourses($user['id']);
        $this->assertEqual($student_enrollment_course_ids, array($course['id']));
        $this->assertEqual($ta_enrollment_course_ids, array());
        $this->assertEqual($instructor_enrollment_course_ids, array());

        // check lti context
        $lti_contexts = $this->LtiContext->find('all');
        $lti_context = end($lti_contexts)['LtiContext'];
        $this->assertEqual($lti_context['lti_tool_registration_id'], 1);
        $this->assertEqual($lti_context['context_id'], '10045');
        $this->assertEqual($lti_context['course_id'], $course['id']);
        $this->assertEqual($lti_context['nrps_context_memberships_url'], 'https://lti-ri.imsglobal.org/platforms/1176/contexts/10045/memberships');

        // check lti resource link
        $lti_resource_links = $this->LtiResourceLink->find('all');
        $lti_resource_link = end($lti_resource_links)['LtiResourceLink'];
        $this->assertEqual($lti_resource_link['lti_context_id'], $lti_context['id']);
        $this->assertEqual($lti_resource_link['resource_link_id'], '19481');
        $this->assertNull($lti_resource_link['event_id']);
        $this->assertEqual($lti_resource_link['lineitems_url'], 'https://lti-ri.imsglobal.org/platforms/1176/contexts/10045/line_items');
        $this->assertNull($lti_resource_link['lineitem_url']);
        $this->assertEqual($lti_resource_link['scope_lineitem'], 1);
        $this->assertEqual($lti_resource_link['scope_lineitem_read_only'], 0);
        $this->assertEqual($lti_resource_link['scope_result_readonly'], 1);
        $this->assertEqual($lti_resource_link['scope_result_score'], 1);

        // check lti user
        $lti_users = $this->LtiUser->find('all');
        $lti_user = end($lti_users)['LtiUser'];
        $this->assertEqual($lti_user['lti_tool_registration_id'], 1);
        $this->assertEqual($lti_user['lti_user_id'], 'student_lti_user_id');
        $this->assertEqual($lti_user['ipeer_user_id'], $user['id']);

        //-- TEST 2: Second launch with zero changes
        $this->resetLaunchNonce();
        $this->controller->expectCallCount('redirect', 2);
        $this->controller->expectArgumentsAt(1, 'redirect', array('/home/index'));
        $this->setupLaunchData($data);
        $this->testAction(
            '/lti/launch',
            array('fixturize' => true, 'method' => 'post')
        );
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'LTI launch success');

        $this->assertEqual($this->registration_count, $this->LtiToolRegistration->find('count'));
        $this->assertEqual($this->lti_context_count, $this->LtiContext->find('count'));
        $this->assertEqual($this->lti_resource_link_count, $this->LtiResourceLink->find('count'));
        $this->assertEqual($this->lti_user_count, $this->LtiUser->find('count'));
        $this->assertEqual($this->user_count, $this->User->find('count'));
        $this->assertEqual($this->course_count, $this->Course->find('count'));
        $this->assertEqual($this->faculty_count, $this->Faculty->find('count'));


        // check faculty
        $faculties = $this->Faculty->find('all');
        $faculty = end($faculties)['Faculty'];
        $this->assertEqual($faculty['name'], 'Some Account');

        // check course
        $courses = $this->Course->find('all');
        $course = end($courses)['Course'];
        $this->assertEqual($course['course'], 'Course 123');
        $this->assertEqual($course['title'], 'Test Course');
        $this->assertNull($course['homepage']);
        $this->assertEqual($course['self_enroll'], 'off');
        $this->assertNull($course['password']);
        $this->assertEqual($course['record_status'], 'A');
        $this->assertEqual($course['canvas_id'], '1000');
        $this->assertEqual($course['term'], 'Default Term');

        // check user
        $users = $this->User->find('all');
        $user = end($users)['User'];
        $this->assertEqual($user['username'], 'lti_student');
        $this->assertNotNull($user['password']);
        $this->assertEqual($user['first_name'], 'Maricela');
        $this->assertEqual($user['last_name'], 'Nikolaus');
        $this->assertEqual($user['student_no'], '0987654321');
        $this->assertEqual($user['title'], '');
        $this->assertEqual($user['email'], 'Maricela.Nikolaus@example.org');
        $this->assertEqual($user['record_status'], 'A');

        // check user role
        $roles = $this->Role->find('all', array(
            'conditions' => array('User.id' => $user['id']),
            'recursive' => 0
        ));
        $role_ids = Set::extract('/Role/id', $roles);
        $this->assertEqual($role_ids, array($this->User->USER_TYPE_STUDENT));

        // check user enrollment
        $student_enrollment_course_ids = $this->User->getEnrolledCourses($user['id']);
        $ta_enrollment_course_ids = $this->User->getTutorCourses($user['id']);
        $instructor_enrollment_course_ids = $this->User->getInstructorCourses($user['id']);
        $this->assertEqual($student_enrollment_course_ids, array($course['id']));
        $this->assertEqual($ta_enrollment_course_ids, array());
        $this->assertEqual($instructor_enrollment_course_ids, array());

        // check lti context
        $lti_contexts = $this->LtiContext->find('all');
        $lti_context = end($lti_contexts)['LtiContext'];
        $this->assertEqual($lti_context['lti_tool_registration_id'], 1);
        $this->assertEqual($lti_context['context_id'], '10045');
        $this->assertEqual($lti_context['course_id'], $course['id']);
        $this->assertEqual($lti_context['nrps_context_memberships_url'], 'https://lti-ri.imsglobal.org/platforms/1176/contexts/10045/memberships');

        // check lti resource link
        $lti_resource_links = $this->LtiResourceLink->find('all');
        $lti_resource_link = end($lti_resource_links)['LtiResourceLink'];
        $this->assertEqual($lti_resource_link['lti_context_id'], $lti_context['id']);
        $this->assertEqual($lti_resource_link['resource_link_id'], '19481');
        $this->assertNull($lti_resource_link['event_id']);
        $this->assertEqual($lti_resource_link['lineitems_url'], 'https://lti-ri.imsglobal.org/platforms/1176/contexts/10045/line_items');
        $this->assertNull($lti_resource_link['lineitem_url']);
        $this->assertEqual($lti_resource_link['scope_lineitem'], 1);
        $this->assertEqual($lti_resource_link['scope_lineitem_read_only'], 0);
        $this->assertEqual($lti_resource_link['scope_result_readonly'], 1);
        $this->assertEqual($lti_resource_link['scope_result_score'], 1);

        // check lti user
        $lti_users = $this->LtiUser->find('all');
        $lti_user = end($lti_users)['LtiUser'];
        $this->assertEqual($lti_user['lti_tool_registration_id'], 1);
        $this->assertEqual($lti_user['lti_user_id'], 'student_lti_user_id');
        $this->assertEqual($lti_user['ipeer_user_id'], $user['id']);


        //-- TEST 3: Third launch with many changes (course and user)
        $this->student_launch_data = array_replace($this->student_launch_data, array(
            // user data
            "given_name" => "MaricelaAAA",
            "family_name" => "NikolausBBB",
            "middle_name" => "WindlerCCC",
            "email" => "MaricelaAAA.NikolausBBB@example.org",
            "name" => "MaricelaAAA WindlerCCC NikolausBBB",
            "https://purl.imsglobal.org/spec/lti/claim/roles" => [
                "http://purl.imsglobal.org/vocab/lis/v2/institution/person#Instructor",
                "http://purl.imsglobal.org/vocab/lis/v2/membership#Instructor"
            ],
            "https://purl.imsglobal.org/spec/lti/claim/custom" => [
                "username" => "lti_student",
                "student_number" => null,
                //course
                "canvas_course_id" => "1010",
                "term_name" => "Default Term 2",
                "account_name" => "Some Other Account"
            ],
            // course
            "https://purl.imsglobal.org/spec/lti/claim/context" => [
                "id" => "10045",
                "label" => "Course 456", //doesn't change in ipeer if changes in LMS
                "title" => "Test Course 2",
                "type" => [
                    "course_offering"
                ]
            ],
            "https://purl.imsglobal.org/spec/lti-ags/claim/endpoint" => [
                "scope" => [
                    "https://purl.imsglobal.org/spec/lti-ags/scope/lineitem.readonly"
                ],
                "lineitems" => "https://some_other_url.com",
                "lineitem" => "https://some_other_url2.com",
            ],
            "https://purl.imsglobal.org/spec/lti-nrps/claim/namesroleservice" => [
                "context_memberships_url" => "https://some_other_url3.com",
                "service_versions" => [
                    "2.0"
                ]
            ],
        ));
        $data['id_token'] = $this->gerenateJWK($this->student_launch_data);
        $this->resetLaunchNonce();
        $this->controller->expectCallCount('redirect', 3);
        $this->controller->expectArgumentsAt(2, 'redirect', array('/courses/home/5'));
        $this->setupLaunchData($data);
        $this->testAction(
            '/lti/launch',
            array('fixturize' => true, 'method' => 'post')
        );
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'LTI launch success');

        $this->assertEqual($this->registration_count, $this->LtiToolRegistration->find('count'));
        $this->assertEqual($this->lti_context_count, $this->LtiContext->find('count'));
        $this->assertEqual($this->lti_resource_link_count, $this->LtiResourceLink->find('count'));
        $this->assertEqual($this->lti_user_count, $this->LtiUser->find('count'));
        $this->assertEqual($this->user_count, $this->User->find('count'));
        $this->assertEqual($this->course_count, $this->Course->find('count'));
        $this->assertEqual(++$this->faculty_count, $this->Faculty->find('count'));


        // check faculty
        $faculties = $this->Faculty->find('all');
        $faculty = end($faculties)['Faculty'];
        $this->assertEqual($faculty['name'], 'Some Other Account');

        // check course
        $courses = $this->Course->find('all');
        $course = end($courses)['Course'];
        $this->assertEqual($course['course'], 'Course 123');
        $this->assertEqual($course['title'], 'Test Course 2');
        $this->assertNull($course['homepage']);
        $this->assertEqual($course['self_enroll'], 'off');
        $this->assertNull($course['password']);
        $this->assertEqual($course['record_status'], 'A');
        $this->assertEqual($course['canvas_id'], '1010');
        $this->assertEqual($course['term'], 'Default Term 2');

        // check user
        $users = $this->User->find('all');
        $user = end($users)['User'];
        $this->assertEqual($user['username'], 'lti_student');
        $this->assertNotNull($user['password']);
        $this->assertEqual($user['first_name'], 'MaricelaAAA');
        $this->assertEqual($user['last_name'], 'NikolausBBB');
        $this->assertEqual($user['student_no'], '0987654321'); //values aren't changed if they are missing from launch
        $this->assertEqual($user['title'], '');
        $this->assertEqual($user['email'], 'MaricelaAAA.NikolausBBB@example.org');
        $this->assertEqual($user['record_status'], 'A');

        // check user role
        $roles = $this->Role->find('all', array(
            'conditions' => array('User.id' => $user['id']),
            'recursive' => 0
        ));
        $role_ids = Set::extract('/Role/id', $roles);
        $this->assertEqual($role_ids, array($this->User->USER_TYPE_STUDENT, $this->User->USER_TYPE_INSTRUCTOR));

        // check user enrollment
        $student_enrollment_course_ids = $this->User->getEnrolledCourses($user['id']);
        $ta_enrollment_course_ids = $this->User->getTutorCourses($user['id']);
        $instructor_enrollment_course_ids = $this->User->getInstructorCourses($user['id']);
        $this->assertEqual($student_enrollment_course_ids, array());
        $this->assertEqual($ta_enrollment_course_ids, array());
        $this->assertEqual($instructor_enrollment_course_ids, array($course['id']));

        // check lti context
        $lti_contexts = $this->LtiContext->find('all');
        $lti_context = end($lti_contexts)['LtiContext'];
        $this->assertEqual($lti_context['lti_tool_registration_id'], 1);
        $this->assertEqual($lti_context['context_id'], '10045');
        $this->assertEqual($lti_context['course_id'], $course['id']);
        $this->assertEqual($lti_context['nrps_context_memberships_url'], 'https://some_other_url3.com');

        // check lti resource link
        $lti_resource_links = $this->LtiResourceLink->find('all');
        $lti_resource_link = end($lti_resource_links)['LtiResourceLink'];
        $this->assertEqual($lti_resource_link['lti_context_id'], $lti_context['id']);
        $this->assertEqual($lti_resource_link['resource_link_id'], '19481');
        $this->assertNull($lti_resource_link['event_id']);
        $this->assertEqual($lti_resource_link['lineitems_url'], 'https://some_other_url.com');
        $this->assertEqual($lti_resource_link['lineitem_url'], 'https://some_other_url2.com');
        $this->assertEqual($lti_resource_link['scope_lineitem'], 0);
        $this->assertEqual($lti_resource_link['scope_lineitem_read_only'], 1);
        $this->assertEqual($lti_resource_link['scope_result_readonly'], 0);
        $this->assertEqual($lti_resource_link['scope_result_score'], 0);

        // check lti user
        $lti_users = $this->LtiUser->find('all');
        $lti_user = end($lti_users)['LtiUser'];
        $this->assertEqual($lti_user['lti_tool_registration_id'], 1);
        $this->assertEqual($lti_user['lti_user_id'], 'student_lti_user_id');
        $this->assertEqual($lti_user['ipeer_user_id'], $user['id']);
    }


    function testInvalidLaunch() {
        $data = array(
            'state' => 'mocked_state',
            'id_token' => $this->gerenateJWK($this->student_launch_data),
        );
        $count = 0;

        //-- TEST 1: Missing state Cookie
        $this->resetLaunchNonce();
        $this->controller->expectCallCount('redirect', ++$count);
        $this->controller->expectArgumentsAt($count-1, 'redirect', array('/logout'));
        $this->setupLaunchData($data);
        $this->testAction(
            '/lti/launch',
            array('fixturize' => true, 'method' => 'post')
        );
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'State not found');


        //-- TEST 2: miss matching states
        $_COOKIE['lti1p3_mocked_state'] = 'incorrect_state';
        $invalid_data = $data;
        unset($invalid_data['state']);
        $this->resetLaunchNonce();
        $this->controller->expectCallCount('redirect', ++$count);
        $this->controller->expectArgumentsAt($count-1, 'redirect', array('/logout'));
        $this->setupLaunchData($invalid_data);
        $this->testAction(
            '/lti/launch',
            array('fixturize' => true, 'method' => 'post')
        );
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'State not found');


        // set valid cookie for future tests
        $_COOKIE['lti1p3_mocked_state'] = 'mocked_state';


        //-- TEST 3: missing state post param
        $invalid_data = $data;
        unset($invalid_data['state']);
        $this->resetLaunchNonce();
        $this->controller->expectCallCount('redirect', ++$count);
        $this->controller->expectArgumentsAt($count-1, 'redirect', array('/logout'));
        $this->setupLaunchData($invalid_data);
        $this->testAction(
            '/lti/launch',
            array('fixturize' => true, 'method' => 'post')
        );
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'State not found');


        //-- TEST 4: missing id_token post param
        $invalid_data = $data;
        unset($invalid_data['id_token']);
        $this->resetLaunchNonce();
        $this->controller->expectCallCount('redirect', ++$count);
        $this->controller->expectArgumentsAt($count-1, 'redirect', array('/logout'));
        $this->setupLaunchData($invalid_data);
        $this->testAction(
            '/lti/launch',
            array('fixturize' => true, 'method' => 'post')
        );
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Missing id_token');

        // NOTE Can't test at the moment since library error is thrown
        // Fatal error: Uncaught Error: Class 'Firebase\JWT\SignatureInvalidException' not found in /var/www/html/vendor/fproject/php-jwt/src/JWT.php on line 113

        // //-- TEST 5: invalid signature
        // $invalid_data = $data;
        // $invalid_data['id_token'] = $this->gerenateInvalidSignatureJWK($this->student_launch_data);
        // $this->controller->expectCallCount('redirect', ++$count);
        // $this->controller->expectArgumentsAt($count-1, 'redirect', array('/logout'));
        // $this->setupLaunchData($invalid_data);
        // $this->testAction(
        //     '/lti/launch',
        //     array('fixturize' => true, 'method' => 'post')
        // );
        // $message = $this->controller->Session->read('Message.flash');
        // $this->assertEqual($message['message'], 'LTI launch success');

        //-- TEST 6: invalid Kid
        $invalid_data = $data;
        $invalid_data['id_token'] = $this->gerenateInvalidKidJWK($this->student_launch_data);
        $this->resetLaunchNonce();
        $this->controller->expectCallCount('redirect', ++$count);
        $this->controller->expectArgumentsAt($count-1, 'redirect', array('/logout'));
        $this->setupLaunchData($invalid_data);
        $this->testAction(
            '/lti/launch',
            array('fixturize' => true, 'method' => 'post')
        );
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Unable to find public key');


        //-- TEST 6: invalid nonce
        $invalid_student_data = $this->student_launch_data;
        $invalid_student_data['nonce'] = 'some_invalid_nonce';
        $invalid_data = array(
            'state' => 'mocked_state',
            'id_token' => $this->gerenateJWK($invalid_student_data),
        );
        $this->resetLaunchNonce();
        $this->controller->expectCallCount('redirect', ++$count);
        $this->controller->expectArgumentsAt($count-1, 'redirect', array('/logout'));
        $this->setupLaunchData($invalid_data);
        $this->testAction(
            '/lti/launch',
            array('fixturize' => true, 'method' => 'post')
        );
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Invalid Nonce');
    }


    function testAdminRoster() {
        $course = $this->Course->findById(1);

        $initial_instructor_ids = [2];
        $current_instructor_ids = Set::extract('/Instructor/id', $course);
        $initial_tutor_ids = [35,36];
        $current_tutor_ids = Set::extract('/Tutor/id', $course);
        $initial_student_ids = [5, 6, 7, 13, 15, 17, 19, 21, 26, 28, 31, 32, 33];
        $current_student_ids = Set::extract('/Enrol/id', $course);

        $this->assertEqual($initial_instructor_ids, $current_instructor_ids);
        $this->assertEqual($initial_tutor_ids, $current_tutor_ids);
        $this->assertEqual($initial_student_ids, $current_student_ids);

        $this->controller->expectOnce('redirect', array('/courses/home/1'));
        $this->testAction('/lti/roster/1');
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Imported Users from LMS');

        $users = $this->User->find('all');
        $new_users = array_slice($users, sizeof($users) - 3);
        $new_instructor = $new_users[0];
        $new_tutor = $new_users[1];
        $new_student = $new_users[2];

        $course = $this->Course->findById(1);

        # verify expected enrollments
        $expected_instructor_ids = [2, $new_instructor['User']['id'], 5];
        $current_instructor_ids = Set::extract('/Instructor/id', $course);
        $expected_tutor_ids = [35, $new_tutor['User']['id'], 6];
        $current_tutor_ids = Set::extract('/Tutor/id', $course);
        $expected_student_ids = [7, $new_student['User']['id']];
        $current_student_ids = Set::extract('/Enrol/id', $course);

        $this->assertEqual($expected_instructor_ids, $current_instructor_ids);
        $this->assertEqual($expected_tutor_ids, $current_tutor_ids);
        $this->assertEqual($current_student_ids, $current_student_ids);

        # verify expected user data

        # instructors
        $user = $this->User->findById(2);
        $this->assertEqual($user['User']['username'], 'instructor1');
        $this->assertNotNull($user['User']['password']);
        $this->assertEqual($user['User']['first_name'], 'Instructor.changed');
        $this->assertEqual($user['User']['last_name'], '1.changed');
        $this->assertNull($user['User']['student_no']);
        $this->assertEqual($user['User']['email'], 'instructor1.changed@email');

        $this->assertEqual($user['LtiUser'][0]['lti_tool_registration_id'], 1);
        $this->assertEqual($user['LtiUser'][0]['lti_user_id'], 'mock_lti_user_id_instructor');

        $user = $new_instructor;
        $this->assertEqual($user['User']['username'], 'new_instructor');
        $this->assertNotNull($user['User']['password']);
        $this->assertEqual($user['User']['first_name'], 'Instructor');
        $this->assertEqual($user['User']['last_name'], 'New');
        $this->assertNull($user['User']['student_no']);
        $this->assertEqual($user['User']['email'], 'instructor.new@email');

        $this->assertEqual($user['LtiUser'][0]['lti_tool_registration_id'], 1);
        $this->assertEqual($user['LtiUser'][0]['lti_user_id'], 'mocked_lti_user_id_new_instructor');

        $user = $this->User->findById(5);
        $this->assertEqual($user['User']['username'], 'redshirt0001');
        $this->assertNotNull($user['User']['password']);
        $this->assertEqual($user['User']['first_name'], 'Ed');
        $this->assertEqual($user['User']['last_name'], 'Student (now instructor)');
        $this->assertEqual($user['User']['student_no'], '65498451');
        $this->assertEqual($user['User']['email'], '');

        $this->assertEqual($user['LtiUser'][0]['lti_tool_registration_id'], 1);
        $this->assertEqual($user['LtiUser'][0]['lti_user_id'], 'mocked_lti_user_id_redshirt0001');

        # tutors
        $user = $this->User->findById(35);
        $this->assertEqual($user['User']['username'], 'tutor1');
        $this->assertNotNull($user['User']['password']);
        $this->assertEqual($user['User']['first_name'], 'Tutor');
        $this->assertEqual($user['User']['last_name'], '1');
        $this->assertEqual($user['User']['student_no'], '');
        $this->assertEqual($user['User']['email'], '');

        $this->assertEqual($user['LtiUser'][0]['lti_tool_registration_id'], 1);
        $this->assertEqual($user['LtiUser'][0]['lti_user_id'], 'mocked_lti_user_id_tutor1');

        $user = $new_tutor;
        $this->assertEqual($user['User']['username'], 'new_tutor');
        $this->assertNotNull($user['User']['password']);
        $this->assertEqual($user['User']['first_name'], 'Tutor');
        $this->assertEqual($user['User']['last_name'], 'New');
        $this->assertNull($user['User']['student_no']);
        $this->assertEqual($user['User']['email'], 'tutor.new@email');

        $this->assertEqual($user['LtiUser'][0]['lti_tool_registration_id'], 1);
        $this->assertEqual($user['LtiUser'][0]['lti_user_id'], 'mocked_lti_user_id_new_tutor');

        $user = $this->User->findById(6);
        $this->assertEqual($user['User']['username'], 'redshirt0002');
        $this->assertNotNull($user['User']['password']);
        $this->assertEqual($user['User']['first_name'], 'Alex');
        $this->assertEqual($user['User']['last_name'], 'Student (now tutor)');
        $this->assertEqual($user['User']['student_no'], '65468188');
        $this->assertEqual($user['User']['email'], '');

        $this->assertEqual($user['LtiUser'][0]['lti_tool_registration_id'], 1);
        $this->assertEqual($user['LtiUser'][0]['lti_user_id'], 'mocked_lti_user_id_redshirt0002');


        # students
        $user = $this->User->findById(7);
        $this->assertEqual($user['User']['username'], 'redshirt0003');
        $this->assertNotNull($user['User']['password']);
        $this->assertEqual($user['User']['first_name'], 'Matt');
        $this->assertEqual($user['User']['last_name'], 'Student');
        $this->assertEqual($user['User']['student_no'], '98985481');
        $this->assertEqual($user['User']['email'], '');

        $this->assertEqual($user['LtiUser'][0]['lti_tool_registration_id'], 1);
        $this->assertEqual($user['LtiUser'][0]['lti_user_id'], 'mocked_lti_user_id_redshirt0003');

        $user = $new_student;
        $this->assertEqual($user['User']['username'], 'new_student');
        $this->assertNotNull($user['User']['password']);
        $this->assertEqual($user['User']['first_name'], 'New');
        $this->assertEqual($user['User']['last_name'], 'Student');
        $this->assertEqual($user['User']['student_no'], '999999999999');
        $this->assertEqual($user['User']['email'], 'student.new@email');

        $this->assertEqual($user['LtiUser'][0]['lti_tool_registration_id'], 1);
        $this->assertEqual($user['LtiUser'][0]['lti_user_id'], 'mocked_lti_user_id_new_student');
    }


    function testInstructorRoster() {
        $this->login = array(
            'User' => array(
                'username' => 'instructor1',
                'password' => md5('ipeeripeer')
            )
        );
        $course = $this->Course->findById(1);

        $initial_instructor_ids = [2];
        $current_instructor_ids = Set::extract('/Instructor/id', $course);
        $initial_tutor_ids = [35,36];
        $current_tutor_ids = Set::extract('/Tutor/id', $course);
        $initial_student_ids = [5, 6, 7, 13, 15, 17, 19, 21, 26, 28, 31, 32, 33];
        $current_student_ids = Set::extract('/Enrol/id', $course);

        $this->assertEqual($initial_instructor_ids, $current_instructor_ids);
        $this->assertEqual($initial_tutor_ids, $current_tutor_ids);
        $this->assertEqual($initial_student_ids, $current_student_ids);

        $this->controller->expectOnce('redirect', array('/courses/home/1'));
        $this->testAction('/lti/roster/1');
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Imported Users from LMS');

        $users = $this->User->find('all');
        $new_users = array_slice($users, sizeof($users) - 3);
        $new_instructor = $new_users[0];
        $new_tutor = $new_users[1];
        $new_student = $new_users[2];

        $course = $this->Course->findById(1);

        # verify expected enrollments
        $expected_instructor_ids = [2, $new_instructor['User']['id'], 5];
        $current_instructor_ids = Set::extract('/Instructor/id', $course);
        $expected_tutor_ids = [35, $new_tutor['User']['id'], 6];
        $current_tutor_ids = Set::extract('/Tutor/id', $course);
        $expected_student_ids = [7, $new_student['User']['id']];
        $current_student_ids = Set::extract('/Enrol/id', $course);

        $this->assertEqual($expected_instructor_ids, $current_instructor_ids);
        $this->assertEqual($expected_tutor_ids, $current_tutor_ids);
        $this->assertEqual($current_student_ids, $current_student_ids);

        # verify expected user data

        # instructors
        $user = $this->User->findById(2);
        $this->assertEqual($user['User']['username'], 'instructor1');
        $this->assertNotNull($user['User']['password']);
        $this->assertEqual($user['User']['first_name'], 'Instructor.changed');
        $this->assertEqual($user['User']['last_name'], '1.changed');
        $this->assertNull($user['User']['student_no']);
        $this->assertEqual($user['User']['email'], 'instructor1.changed@email');

        $this->assertEqual($user['LtiUser'][0]['lti_tool_registration_id'], 1);
        $this->assertEqual($user['LtiUser'][0]['lti_user_id'], 'mock_lti_user_id_instructor');

        $user = $new_instructor;
        $this->assertEqual($user['User']['username'], 'new_instructor');
        $this->assertNotNull($user['User']['password']);
        $this->assertEqual($user['User']['first_name'], 'Instructor');
        $this->assertEqual($user['User']['last_name'], 'New');
        $this->assertNull($user['User']['student_no']);
        $this->assertEqual($user['User']['email'], 'instructor.new@email');

        $this->assertEqual($user['LtiUser'][0]['lti_tool_registration_id'], 1);
        $this->assertEqual($user['LtiUser'][0]['lti_user_id'], 'mocked_lti_user_id_new_instructor');

        $user = $this->User->findById(5);
        $this->assertEqual($user['User']['username'], 'redshirt0001');
        $this->assertNotNull($user['User']['password']);
        $this->assertEqual($user['User']['first_name'], 'Ed');
        $this->assertEqual($user['User']['last_name'], 'Student (now instructor)');
        $this->assertEqual($user['User']['student_no'], '65498451');
        $this->assertEqual($user['User']['email'], '');

        $this->assertEqual($user['LtiUser'][0]['lti_tool_registration_id'], 1);
        $this->assertEqual($user['LtiUser'][0]['lti_user_id'], 'mocked_lti_user_id_redshirt0001');

        # tutors
        $user = $this->User->findById(35);
        $this->assertEqual($user['User']['username'], 'tutor1');
        $this->assertNotNull($user['User']['password']);
        $this->assertEqual($user['User']['first_name'], 'Tutor');
        $this->assertEqual($user['User']['last_name'], '1');
        $this->assertEqual($user['User']['student_no'], '');
        $this->assertEqual($user['User']['email'], '');

        $this->assertEqual($user['LtiUser'][0]['lti_tool_registration_id'], 1);
        $this->assertEqual($user['LtiUser'][0]['lti_user_id'], 'mocked_lti_user_id_tutor1');

        $user = $new_tutor;
        $this->assertEqual($user['User']['username'], 'new_tutor');
        $this->assertNotNull($user['User']['password']);
        $this->assertEqual($user['User']['first_name'], 'Tutor');
        $this->assertEqual($user['User']['last_name'], 'New');
        $this->assertNull($user['User']['student_no']);
        $this->assertEqual($user['User']['email'], 'tutor.new@email');

        $this->assertEqual($user['LtiUser'][0]['lti_tool_registration_id'], 1);
        $this->assertEqual($user['LtiUser'][0]['lti_user_id'], 'mocked_lti_user_id_new_tutor');

        $user = $this->User->findById(6);
        $this->assertEqual($user['User']['username'], 'redshirt0002');
        $this->assertNotNull($user['User']['password']);
        $this->assertEqual($user['User']['first_name'], 'Alex');
        $this->assertEqual($user['User']['last_name'], 'Student (now tutor)');
        $this->assertEqual($user['User']['student_no'], '65468188');
        $this->assertEqual($user['User']['email'], '');

        $this->assertEqual($user['LtiUser'][0]['lti_tool_registration_id'], 1);
        $this->assertEqual($user['LtiUser'][0]['lti_user_id'], 'mocked_lti_user_id_redshirt0002');


        # students
        $user = $this->User->findById(7);
        $this->assertEqual($user['User']['username'], 'redshirt0003');
        $this->assertNotNull($user['User']['password']);
        $this->assertEqual($user['User']['first_name'], 'Matt');
        $this->assertEqual($user['User']['last_name'], 'Student');
        $this->assertEqual($user['User']['student_no'], '98985481');
        $this->assertEqual($user['User']['email'], '');

        $this->assertEqual($user['LtiUser'][0]['lti_tool_registration_id'], 1);
        $this->assertEqual($user['LtiUser'][0]['lti_user_id'], 'mocked_lti_user_id_redshirt0003');

        $user = $new_student;
        $this->assertEqual($user['User']['username'], 'new_student');
        $this->assertNotNull($user['User']['password']);
        $this->assertEqual($user['User']['first_name'], 'New');
        $this->assertEqual($user['User']['last_name'], 'Student');
        $this->assertEqual($user['User']['student_no'], '999999999999');
        $this->assertEqual($user['User']['email'], 'student.new@email');

        $this->assertEqual($user['LtiUser'][0]['lti_tool_registration_id'], 1);
        $this->assertEqual($user['LtiUser'][0]['lti_user_id'], 'mocked_lti_user_id_new_student');
    }


    function testTutorRoster() {
        $this->login = array(
            'User' => array(
                'username' => 'tutor1',
                'password' => md5('ipeeripeer')
            )
        );

        $course = $this->Course->findById(1);

        $initial_instructor_ids = [2];
        $current_instructor_ids = Set::extract('/Instructor/id', $course);
        $initial_tutor_ids = [35,36];
        $current_tutor_ids = Set::extract('/Tutor/id', $course);
        $initial_student_ids = [5, 6, 7, 13, 15, 17, 19, 21, 26, 28, 31, 32, 33];
        $current_student_ids = Set::extract('/Enrol/id', $course);

        $this->assertEqual($initial_instructor_ids, $current_instructor_ids);
        $this->assertEqual($initial_tutor_ids, $current_tutor_ids);
        $this->assertEqual($initial_student_ids, $current_student_ids);

        $this->controller->expectOnce('redirect', array('/home'));
        $this->testAction('/lti/roster/1');
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: You do not have permission to access the page.');

        // roster should be unchanged
        $course = $this->Course->findById(1);
        $current_instructor_ids = Set::extract('/Instructor/id', $course);
        $current_tutor_ids = Set::extract('/Tutor/id', $course);
        $current_student_ids = Set::extract('/Enrol/id', $course);

        $this->assertEqual($initial_instructor_ids, $current_instructor_ids);
        $this->assertEqual($initial_tutor_ids, $current_tutor_ids);
        $this->assertEqual($initial_student_ids, $current_student_ids);
    }


    function testStudentRoster() {
        $this->login = array(
            'User' => array(
                'username' => 'redshirt0001',
                'password' => md5('ipeeripeer')
            )
        );

        $course = $this->Course->findById(1);

        $initial_instructor_ids = [2];
        $current_instructor_ids = Set::extract('/Instructor/id', $course);
        $initial_tutor_ids = [35,36];
        $current_tutor_ids = Set::extract('/Tutor/id', $course);
        $initial_student_ids = [5, 6, 7, 13, 15, 17, 19, 21, 26, 28, 31, 32, 33];
        $current_student_ids = Set::extract('/Enrol/id', $course);

        $this->assertEqual($initial_instructor_ids, $current_instructor_ids);
        $this->assertEqual($initial_tutor_ids, $current_tutor_ids);
        $this->assertEqual($initial_student_ids, $current_student_ids);

        $this->controller->expectOnce('redirect', array('/home'));
        $this->testAction('/lti/roster/1');
        $message = $this->controller->Session->read('Message.flash');
        $this->assertEqual($message['message'], 'Error: You do not have permission to access the page.');

        // roster should be unchanged
        $course = $this->Course->findById(1);
        $current_instructor_ids = Set::extract('/Instructor/id', $course);
        $current_tutor_ids = Set::extract('/Tutor/id', $course);
        $current_student_ids = Set::extract('/Enrol/id', $course);

        $this->assertEqual($initial_instructor_ids, $current_instructor_ids);
        $this->assertEqual($initial_tutor_ids, $current_tutor_ids);
        $this->assertEqual($initial_student_ids, $current_student_ids);
    }
}

}
