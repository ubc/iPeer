<?php
App::import('Lib', 'ExtendedAuthTestCase');
App::import('Vendor', 'IMSGlobal\\Caliper', array('file' => 'imsglobal'.DS.'caliper'.DS.'autoload.php'));
App::import('Model', 'User');

use caliper\CaliperSensor;
use IMSGlobal\Caliper\util\TimestampUtil;

/**
 * CaliperAuthTestCase based on ExtendedAuthTestCase, modified to better handle Caliper events
 *
 * @uses ExtendedAuthTestCase
 * @package   IPeer
 * @author    Andrew Gardener <andrew.gardener@ubc.ca>
 */
class CaliperAuthTestCase extends ExtendedAuthTestCase {
    function model_timestamp_to_iso8601($timestamp)
    {
        return TimestampUtil::formatTimeISO8601MillisUTC(new \DateTime($timestamp));
    }

    function setupSession($url = '')
    {
        $this->testAction($url);
    }

    /**
     * startTest prepare tests
     * @access public
     * @return void
     */
    public function startTest($method)
    {
        # setup default variables
        $this->User = ClassRegistry::init('User');
        $this->Course = ClassRegistry::init('Course');
        $this->Group = ClassRegistry::init('Group');

        # setup default expected_ed_app
        $this->expected_ed_app = array(
            'id' => 'http://test.ipeer.com',
            'type' => "SoftwareApplication",
            'name' => "iPeer",
            'version' => \IPEER_VERSION
        );

        $results = $this->Course->find('first', array(
            'conditions' => array('id' => 1)
        ));
        $this->course = $results['Course'];
        $this->expected_course = array (
            'id' => 'http://test.ipeer.com/courses/view/'.$this->course['id'],
            'type' => 'CourseOffering',
            'name' => 'Mechanical Engineering Design Project',
            'extensions' => array (
                'record_status' => 'A',
                'homepage' => 'http://www.mech.ubc.ca',
                'self_enroll' => 'off',
            ),
            'dateCreated' => $this->model_timestamp_to_iso8601($this->course['created']),
            'dateModified' => $this->model_timestamp_to_iso8601($this->course['modified']),
            'courseNumber' => 'MECH 328',
        );

        $this->_setup_super_admin();

        CaliperSensor::isTest(true);
        // CaliperSensor::sendTestEvents(true);
        CaliperSensor::getEnvelopes(); //clear envelopes if present
    }

    /**
     * @access public
     * @return void
     */
    public function endTest($method)
    {
        $this->_disable_caliper();
        if (isset($this->controller->Auth)) {
            $this->controller->Auth->logout();
        }
        unset($this->controller);
        ClassRegistry::flush();
        CaliperSensor::isTest(false);
        CaliperSensor::sendTestEvents(false);
    }

    public function getController()
    {
        return $this->controller;
    }

    public function _setup_super_admin() {
        # setup default user info
        $this->login = array(
            'User' => array(
                'username' => 'root',
                'password' => md5('ipeeripeer')
            )
        );
        $this->user = $this->User->getByUsername('root')['User'];
        $this->expected_actor = array(
            'id' => 'http://www.ubc.ca/root',
            'type' => 'Person',
            'name' => $this->user['full_name'],
            'dateCreated' => $this->model_timestamp_to_iso8601($this->user['created']),
            'dateModified' => $this->model_timestamp_to_iso8601($this->user['modified']),
        );

        $this->group = NULL;
        $this->group_members = array();
        $this->expected_group_members = array();

        $this->expected_group = $this->expected_course;
        $this->expected_membership = array(
            'id' => 'http://test.ipeer.com/courses/view/1/users/1',
            'type' => 'Membership',
            'member' =>  $this->expected_actor,
            'organization' => 'http://test.ipeer.com/courses/view/1',
            'roles' => array ('Administrator'),
            'status' => 'Active',
        );
    }

    /*
     * Note redshirt0003 has actual evaluation submissions
     * Note redshirt0013 has actual survey submissions
     */
    public function _setup_student($username, $group_id=NULL) {
        # setup default user info
        $this->login = array(
            'User' => array(
                'username' => $username,
                'password' => md5('ipeeripeer')
            )
        );
        $this->user = $this->User->getByUsername($username)['User'];
        $this->expected_actor = array(
            'id' => 'http://www.ubc.ca/'.$this->user['username'],
            'type' => 'Person',
            'name' => $this->user['full_name'],
            'dateCreated' => $this->model_timestamp_to_iso8601($this->user['created']),
            'dateModified' => $this->model_timestamp_to_iso8601($this->user['modified']),
            'extensions' => array(
                'student_no' => $this->user['student_no'],
            ),
        );

        if ($group_id) {
            $this->group = $this->Group->findGroupByid($group_id)['Group'];
            $this->group_members = $this->User->getMembersByGroupId($this->group['id']);
            $this->expected_group_members = array();
            foreach($this->group_members as $group_member) {
                $user = $group_member['User'];

                $expected_group_member = array(
                    'id' => 'http://www.ubc.ca/'.$user['username'],
                    'type' => 'Person',
                    'name' => $user['full_name'],
                    'dateCreated' => $this->model_timestamp_to_iso8601($user['created']),
                    'dateModified' => $this->model_timestamp_to_iso8601($user['modified'])
                );
                if ($user['student_no']) {
                    $expected_group_member['extensions'] = array(
                        'student_no' => $user['student_no'],
                    );
                }
                $this->expected_group_members[] = $expected_group_member;
            }

            $this->expected_group = array(
                'id' => 'http://test.ipeer.com/groups/view/'.$this->group['id'],
                'type' => 'Group',
                'name' => $this->group['group_name'],
                'extensions' => array(
                    'group_num' => $this->group['group_num'],
                    'record_status' => $this->group['record_status'],
                ),
                'dateCreated' => $this->model_timestamp_to_iso8601($this->group['created']),
                'dateModified' => $this->model_timestamp_to_iso8601($this->group['modified']),
                'members' => $this->expected_group_members,
                'subOrganizationOf' => $this->expected_course,
            );
        } else {
            $this->group = NULL;
            $this->group_members = array();
            $this->expected_group_members = array();

            $this->expected_group = $this->expected_course;
        }

        $this->expected_membership = array(
            'id' => 'http://test.ipeer.com/courses/view/1/users/'.$this->user['id'],
            'type' => 'Membership',
            'member' => $this->expected_actor,
            'organization' => 'http://test.ipeer.com/courses/view/1',
            'roles' => array('Learner'),
            'status' => 'Active',
        );
    }

    function _get_caliper_events($session_start=FALSE, $session_end=False) {
        $envelopes = CaliperSensor::getEnvelopes();
        $events = array();
        foreach($envelopes as $envelopeJson) {
            $envelope = json_decode($envelopeJson, true);

            $this->assertEqual($envelope['sensor'], 'http://test.ipeer.com');
            $this->assertNotNull($envelope['sendTime']);
            $this->assertEqual($envelope['dataVersion'], "http://purl.imsglobal.org/ctx/caliper/v1p2");

            foreach($envelope['data'] as $event) {
                $this->assertEqual($event['@context'], "http://purl.imsglobal.org/ctx/caliper/v1p2");
                unset($event['@context']);

                $this->assertNotNull($event['id']);
                unset($event['id']);

                $this->assertNotNull($event['eventTime']);
                unset($event['eventTime']);

                $this->assertEqual($event['edApp'], $this->expected_ed_app);
                unset($event['edApp']);

                $this->assertEqual($event['actor'], $this->expected_actor);
                unset($event['actor']);

                $this->assertNotNull($event['session']['id']);
                unset($event['session']['id']);

                if ($session_start) {
                    $this->assertNotNull($event['session']['startedAtTime']);
                    unset($event['session']['startedAtTime']);
                }
                if ($session_end) {
                    $this->assertNotNull($event['session']['endedAtTime']);
                    unset($event['session']['endedAtTime']);
                }
                if ($session_start && $session_end) {
                    $this->assertNotNull($event['session']['duration']);
                    unset($event['session']['duration']);
                }

                $this->assertNotNull($event['session']['client']['id']);
                unset($event['session']['client']['id']);

                $this->assertEqual($event['session'], array(
                    'type' => "Session",
                    'user' => $this->expected_actor,
                    'client' => array(
                        'type' => 'SoftwareApplication'
                    )
                ));
                unset($event['session']);

                // remove Feedback Rating and Comment ids since they are randomly generated
                if ($event['type'] == 'FeedbackEvent' && $event['generated']) {
                    if ($event['generated']['type'] == 'Comment') {
                        $this->assertNotNull($event['generated']['id']);
                        unset($event['generated']['id']);
                    }
                    if ($event['generated']['type'] == 'Rating') {
                        $this->assertNotNull($event['generated']['id']);
                        unset($event['generated']['id']);
                        if (array_key_exists('ratingComment', $event['generated'])) {
                            $this->assertNotNull($event['generated']['ratingComment']['id']);
                            unset($event['generated']['ratingComment']['id']);
                        }
                    }
                }

                $events[]= $event;
            }
        }
        return $events;
    }

    function _enable_caliper() {
        putenv('CALIPER_API_KEY=test_caliper_key');
        putenv('CALIPER_HOST=http://test.caliper.host');
        putenv('CALIPER_BASE_URL=http://test.ipeer.com');
        putenv('CALIPER_ACTOR_BASE_URL=http://www.ubc.ca/%s');
        putenv('CALIPER_ACTOR_UNIQUE_IDENTIFIER_PARAM=username');

        // to automatically send tests to suite, add info here and
        // uncomment `CaliperSensor::sendTestEvents(true);` above
        // putenv('CALIPER_API_KEY=be4947b8-3b6b-4fe4-99c1-86faff4c4aba');
        // putenv('CALIPER_HOST=https://caliper-dev.imsglobal.org/caliper/be4947b8-3b6b-4fe4-99c1-86faff4c4aba/message');
    }

    function _disable_caliper() {
        putenv('CALIPER_API_KEY');
        putenv('CALIPER_HOST');
        putenv('CALIPER_BASE_URL');
        putenv('CALIPER_BASE_URL');
        putenv('CALIPER_ACTOR_BASE_URL');
    }
}

