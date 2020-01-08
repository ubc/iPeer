<?php
App::import('Lib', 'Lti13Bootstrap');
App::import('Model', 'Lti13');

/**
 * Usage `cake/console/cake testsuite app case models/lti13`
 *
 * @link https://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html#testing-models
 * @package   CTLT.iPeer
 * @since     3.4.5
 * @author    Steven Marshall <steven.marshall@ubc.ca>
 * @copyright 2019 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class Lti13TestCase extends CakeTestCase {

    public $fixtures = array('app.user', 'app.course', 'app.role');
    public $UserTest, $CourseTest, $RoleTest;

    function startCase()
    {
        $this->Lti13 = ClassRegistry::init('Lti13');
        $this->UserTest = ClassRegistry::init('User');
        $this->CourseTest = ClassRegistry::init('Course');
        $this->RoleTest = ClassRegistry::init('Role');
    }

    function test_getLtiCourseData()
    {
        $json = <<<JSON
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
        $this->Lti13->jwtPayload = json_decode($json, true);
        $expected = array(
            'label' => "Test iPeer course label",
            'title' => "Test iPeer course title",
        );
        $actual = $this->Lti13->getLtiCourseData();
        $this->assertEqual($expected, $actual);
    }

    function test_getLtiCourseDataMissingContext()
    {
        $json = <<<JSON
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
        $this->Lti13->jwtPayload = json_decode($json, true);
        $this->expectException('IMSGlobal\LTI\LTI_Exception', "Missing 'https://purl.imsglobal.org/spec/lti/claim/context'");
        $this->Lti13->getLtiCourseData();
    }

    function test_getLtiCourseDataMissingLabel()
    {
        $json = <<<JSON
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
        $this->Lti13->jwtPayload = json_decode($json, true);
        $this->expectException('IMSGlobal\LTI\LTI_Exception', "Missing 'context label'");
        $this->Lti13->getLtiCourseData();
    }

    function test_getLtiCourseDataMissingTitle()
    {
        $json = <<<JSON
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
        $this->Lti13->jwtPayload = json_decode($json, true);
        $this->expectException('IMSGlobal\LTI\LTI_Exception', "Missing 'context title'");
        $this->Lti13->getLtiCourseData();
    }

    function test_getLtiCourseDataEmptyJwtPayload()
    {
        $this->Lti13->jwtPayload = array();
        $this->expectException('IMSGlobal\LTI\LTI_Exception', "Missing 'https://purl.imsglobal.org/spec/lti/claim/context'");
        $this->Lti13->getLtiCourseData();
    }

    function test_removeUsersFoundInBothRosters()
    {
        $this->Lti13->ltiRoster = array(
            '1' => array(
                'user_id' => '0123',
            ),
            '2' => array(
                'user_id' => '9870',
            ),
        );
        $this->Lti13->ipeerRoster = array(
            '8' => array(
                'User' => array(
                    'lti_id' => '7777',
                ),
            ),
            '9' => array(
                'User' => array(
                    'lti_id' => '0123',
                ),
            ),
        );
        $expectedLtiRoster = array(
            '2' => array(
                'user_id' => '9870',
            ),
        );
        $expectedIpeerRoster = array(
            '8' => array(
                'User' => array(
                    'lti_id' => '7777',
                ),
            ),
        );
        $this->Lti13->removeUsersFoundInBothRosters();
        $this->assertEqual($expectedLtiRoster, $this->Lti13->ltiRoster);
        $this->assertEqual($expectedIpeerRoster, $this->Lti13->ipeerRoster);
    }

    function test_isInstructor()
    {
        $this->assertTrue($this->Lti13->isInstructor("Instructor"));
        $this->assertTrue($this->Lti13->isInstructor("Winstructor"));
        $this->assertFalse($this->Lti13->isInstructor("Instructo"));
        $this->assertTrue($this->Lti13->isInstructor(array("#Mentor", "#Instructor")));
        $this->assertFalse($this->Lti13->isInstructor(array("Student", "Mentor")));
    }

    function test_getUsername()
    {
        $data = array(
            'given_name' => "John",
            'family_name' => "Smith",
        );
        $this->assertEqual("JohnSmith", $this->Lti13->getUsername($data));

        $this->expectException('IMSGlobal\LTI\LTI_Exception', "Missing 'family_name'");
        $data = array(
            'given_name' => "John",
        );
        $this->Lti13->getUsername($data);

        $this->expectException('IMSGlobal\LTI\LTI_Exception', "Missing 'given_name'");
        $data = array(
            'family_name' => "Smith",
        );
        $this->Lti13->getUsername($data);

        $this->expectException('IMSGlobal\LTI\LTI_Exception');
        $this->Lti13->getUsername($data);
    }

    function test_getUserType()
    {
        $this->Lti13->User = $this->UserTest;
        $this->assertEqual(3, $this->Lti13->getUserType($isInstructor=true));
        $this->assertEqual(5, $this->Lti13->getUserType($isInstructor=false));
    }
}
