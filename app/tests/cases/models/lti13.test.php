<?php
App::import('Lib', 'Lti13Bootstrap');

/**
 * Usage:
 * `docker exec -it ipeer_app_unittest bash`
 * `vendor/bin/phing init-test-db`
 * `cake/console/cake -app app testsuite app case models/lti13`
 *
 * @link https://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html#testing-models
 * @package   CTLT.iPeer
 * @since     3.4.5
 * @author    Steven Marshall <steven.marshall@ubc.ca>
 * @copyright 2019 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class Lti13TestCase extends CakeTestCase
{

    public $fixtures = array(
        'app.user',
        'app.course',
        'app.role',
        'app.evaluation_submission',
        'app.sys_parameter',
        'app.event',
        'app.event_template_type',
        'app.group',
        'app.group_event',
        'app.evaluation_simple',
        'app.survey_input',
        'app.survey_group_member',
        'app.survey_group_set',
        'app.survey',
        'app.question',
        'app.response',
        'app.survey_question',
        'app.survey_group',
        'app.faculty',
        'app.user_faculty',
        'app.roles_user',
        'app.user_course',
        'app.user_tutor',
        'app.user_enrol',
        'app.groups_member',
        'app.department',
        'app.course_department',
        'app.penalty',
        'app.evaluation_rubric',
        'app.evaluation_rubric_detail',
        'app.evaluation_mixeval',
        'app.evaluation_mixeval_detail',
    );
    public $Lti13, $User, $Course, $Role;

    function startCase()
    {
        $this->Lti13 = ClassRegistry::init('Lti13');
        $this->User = ClassRegistry::init('User');
        $this->Course = ClassRegistry::init('Course');
        $this->Role = ClassRegistry::init('Role');
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
        $this->Lti13->jwtBody = json_decode($json, true);
        $expected = array(
            'id' => "4287",
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
        $this->Lti13->jwtBody = json_decode($json, true);
        $this->expectException('IMSGlobal\LTI\LTI_Exception');
        $this->Lti13->getLtiCourseData();
    }

    function test_getLtiCourseDataMissingId()
    {
        $json = <<<JSON
{
  "https://purl.imsglobal.org/spec/lti/claim/context": {
    "label": "Test iPeer course label",
    "title": "Test iPeer course title",
    "type": [
      "Test iPeer course type"
    ]
  }
}
JSON;
        $this->Lti13->jwtBody = json_decode($json, true);
        $this->expectException('IMSGlobal\LTI\LTI_Exception');
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
        $this->Lti13->jwtBody = json_decode($json, true);
        $this->expectException('IMSGlobal\LTI\LTI_Exception');
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
        $this->Lti13->jwtBody = json_decode($json, true);
        $this->expectException('IMSGlobal\LTI\LTI_Exception');
        $this->Lti13->getLtiCourseData();
    }

    function test_getLtiCourseDataEmptyJwtBody()
    {
        $this->Lti13->jwtBody = array();
        $this->expectException('IMSGlobal\LTI\LTI_Exception');
        $this->Lti13->getLtiCourseData();
    }

    function test_updateCourseRoster()
    {
        $this->assertFalse($this->Lti13->updateCourseRoster(array()));
    }

    function test_createCourseRoster()
    {
        $this->assertFalse($this->Lti13->createCourseRoster(array()));

        $data = array(
            'course' => "CPSC 405",
            'title' => "Advanced Software Engineering 405",
            'record_status' => Course::STATUS_ACTIVE,
        );
        $this->assertFalse($this->Lti13->createCourseRoster($data));
    }

    function test_findCourseByLabel()
    {
        $this->assertFalse($this->Lti13->findCourseByLabel(null));
    }

    function test_saveExistingUserToCourse()
    {
        $this->assertFalse($this->Lti13->saveExistingUserToCourse(array(), $courseId=0, $isInstructor=false, $ltiId=""));
        $this->assertFalse($this->Lti13->saveExistingUserToCourse(array(), $courseId=0, $isInstructor=true, $ltiId=""));
    }

    function test_saveNewUserToCourse()
    {
        $this->assertFalse($this->Lti13->saveNewUserToCourse(array(), $courseId=0, $isInstructor=true));
        $this->assertFalse($this->Lti13->saveNewUserToCourse(array(), $courseId=0, $isInstructor=false));
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

    function test_findUserByLtiUserId()
    {
        $this->Lti13->jwtBody['sub'] = null;
        $this->assertTrue($this->Lti13->findUserByLtiUserId());

        $this->Lti13->jwtBody['sub'] = 'aaaaa';
        $this->assertFalse($this->Lti13->findUserByLtiUserId());
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

        $this->expectException('IMSGlobal\LTI\LTI_Exception');
        $data = array(
            'given_name' => "John",
        );
        $this->Lti13->getUsername($data);

        $this->expectException('IMSGlobal\LTI\LTI_Exception');
        $data = array(
            'family_name' => "Smith",
        );
        $this->Lti13->getUsername($data);

        $this->expectException('IMSGlobal\LTI\LTI_Exception');
        $this->Lti13->getUsername(array());
    }

    function test_getUserType()
    {
        $this->assertEqual(3, $this->Lti13->getUserType($isInstructor=true));
        $this->assertEqual(5, $this->Lti13->getUserType($isInstructor=false));
    }
}
