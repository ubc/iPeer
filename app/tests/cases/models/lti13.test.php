<?php
App::import('Lib', 'Lti13Bootstrap');
App::import('Model', 'Lti13');

/**
 * `cake/console/cake testsuite app case models/lti13`
 */
class Lti13TestCase extends CakeTestCase {

    function startCase()
    {
        $this->Lti13 = ClassRegistry::init('Lti13');
    }

    function test_get_course_info()
    {
        $jwt_payload = <<<JSON
{
  "https://purl.imsglobal.org/spec/lti/claim/context": {
    "id": "4287",
    "label": "Test iPeer course label",
    "title": "Test iPeer course title",
    "type": [
      "Test iPeer course type"
    ]
  }
}
JSON;
        $jwt_payload = json_decode($jwt_payload, true);
        $expected = array(
            'course' => "Test iPeer course label",
            'title'  => "Test iPeer course title",
        );
        $actual = $this->Lti13->get_course_info($jwt_payload);
        $this->assertEqual($expected, $actual);
    }

    function test_get_course_info_missing_context()
    {
        $jwt_payload = <<<JSON
{
  "http://purl.imsglobal.org/spec/lti/claim/context": {
    "id": "4287",
    "label": "Test iPeer course label",
    "title": "Test iPeer course title",
    "type": [
      "Test iPeer course type"
    ]
  }
}
JSON;
        $jwt_payload = json_decode($jwt_payload, true);
        $this->expectException('IMSGlobal\LTI\LTI_Exception', "Missing 'https://purl.imsglobal.org/spec/lti/claim/context'");
        $this->Lti13->get_course_info($jwt_payload);
    }

    function test_get_course_info_missing_label()
    {
        $jwt_payload = <<<JSON
{
  "https://purl.imsglobal.org/spec/lti/claim/context": {
    "id": "4287",
    "title": "Test iPeer course title",
    "type": [
      "Test iPeer course type"
    ]
  }
}
JSON;
        $jwt_payload = json_decode($jwt_payload, true);
        $this->expectException('IMSGlobal\LTI\LTI_Exception', "Missing 'context label'");
        $this->Lti13->get_course_info($jwt_payload);
    }

    function test_get_course_info_missing_title()
    {
        $jwt_payload = <<<JSON
{
  "https://purl.imsglobal.org/spec/lti/claim/context": {
    "id": "4287",
    "label": "Test iPeer course label",
    "type": [
      "Test iPeer course type"
    ]
  }
}
JSON;
        $jwt_payload = json_decode($jwt_payload, true);
        $this->expectException('IMSGlobal\LTI\LTI_Exception', "Missing 'context title'");
        $this->Lti13->get_course_info($jwt_payload);
    }
}
