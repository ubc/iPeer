<?php
App::import('Model', 'Event');

class EventTestCase extends CakeTestCase
{
    public $name = 'Event';
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey',
        'app.faculty', 'app.user_faculty', 'app.department',
        'app.course_department', 'app.sys_parameter', 'app.user_tutor',
        'app.penalty', 'app.evaluation_simple', 'app.survey_input',
    );
    public $Course = null;

    function startCase()
    {
        echo "Start Event model test.\n";
        $this->Event = ClassRegistry::init('Event');
    }

    function endCase()
    {
    }

    function startTest($method)
    {
    }

    function endTest($method)
    {
    }

    function testGetCourseEvent()
    {

        $empty = null;

        $this->Event = & ClassRegistry::init('Event');

        //Test a valid course number
        $course = $this->Event->getCourseEvent(1);
        $this->assertEqual($course[0]['Event']['title'], 'Term 1 Evaluation');
        $this->assertEqual($course[1]['Event']['title'], 'Term Report Evaluation');
        $this->assertEqual($course[2]['Event']['title'], 'Project Evaluation');
        $this->assertEqual($course[3]['Event']['title'], 'Team Creation Survey');
        $this->assertEqual($course[4]['Event']['title'], 'Survey, all Q types');
        $this->assertEqual($course[5]['Event']['title'], 'simple evaluation 2');
        $this->assertEqual($course[6]['Event']['title'], 'simple evaluation 3');
        $this->assertEqual($course[7]['Event']['title'], 'simple evaluation 4');
        $this->assertEqual($course[8]['Event']['title'], 'simple evaluation 5');

        //Test an invalid course number
        $course = $this->Event->getCourseEvent(999);
        $this->assertEqual($course, $empty);
    }


    function testGetCourseEvalEvent()
    {

        $empty = null;
        $this->Event = & ClassRegistry::init('Event');

        //Test a valid course number

        $course = $this->Event->GetCourseEvalEvent(1);
        $events = $this->toEventNameArray($course);
        $this->assertEqual($events['0'], 'Term 1 Evaluation');
        $this->assertEqual($events['1'], 'Term Report Evaluation');
        $this->assertEqual($events['2'], 'Project Evaluation');

        //Test an invalid course number
        $course = $this->Event->GetCourseEvalEvent(999);
        $this->assertEqual($course, $empty);

    }

    function testGetCourseEventCount()
    {

        $empty = null;
        $this->Event = & ClassRegistry::init('Event');

        //Test a valid course number
        $course = $this->Event->getCourseEventCount(1);
        $this->assertEqual($course, 9);

        //Test an invalid course number
        $course = $this->Event->getCourseEventCount(999);
        $this->assertEqual($course, 0);
    }

    function testGetCourseByEventId()
    {

        $empty = null;
        $this->Event = & ClassRegistry::init('Event');

        //Test a valid event number
        $course = $this->Event->getCourseByEventId(1);
        $this->assertEqual($course, 1);

        //Test an invalid event number
        $course = $this->Event->getCourseEventCount(999);
        $this->assertEqual($course, 0);

    }

    function testGetActiveSurveyEvents()
    {
        $empty = null;
        $this->Event = & ClassRegistry::init('Event');

        //Test a valid course number
        $event = $this->Event->getActiveSurveyEvents(1);
        $events = $this->toEventNameArray($event);
        $this->assertEqual($events, array('Team Creation Survey','Survey, all Q types'));

        //Test a valid course with one inactive survey
        $event = $this->Event->getActiveSurveyEvents(2);
        $events = $this->toEventNameArray($event);
        $this->assertEqual($events, array());

        //Test invalid course
        $event = $this->Event->getActiveSurveyEvents(4);
        $this->assertEqual($event, $empty);
    }


    function testCheckIfNowLate()
    {
        $late = $this->Event->checkIfNowLate(1);
        $this->assertFalse($late);
        $late = $this->Event->checkIfNowLate(2);
        $this->assertFalse($late);

        $late = $this->Event->checkIfNowLate(999);
        $this->assertFalse($late);
        $late = $this->Event->checkIfNowLate(null);
        $this->assertFalse($late);
    }


    function testGetUnassignedGroups()
    {
        $empty = null;
        $this->Event = & ClassRegistry::init('Event');

        //Test valid event without group assigned

        $event= $this->Event->getCourseEvent(1);
        $groups = $this->Event->getUnassignedGroups($event[0]);

        $groups= $this->toGroupArray($groups);
        // in this case, no unassigned groups
        $this->assertEqual($groups, array());

        //Test valid event with a group assigned

        $event= $this->Event->getCourseEvent(1);
        $groups = $this->Event->getUnassignedGroups($event[0], array(1));

        $groups= $this->toGroupArray($groups);
        $this->assertEqual($groups, array('Lazy Engineers'));

        //Test invalid event id
        $event= $this->Event->getCourseEvent(999);
        $this->assertEqual($event, $empty);

        //Test valid event id with invalid groups
        $event= $this->Event->getCourseEvent(1);
        $groups = $this->Event->getUnassignedGroups($event[0], 999);

        $groups= $this->toGroupArray($groups);
        $this->assertEqual($groups, array('Reapers', 'Lazy Engineers'));
    }

    function testGetEventById()
    {

        $empty = null;
        $this->Event = & ClassRegistry::init('Event');

        //Test valid event
        $event = $this->Event->getEventById(1);
        $this->assertEqual($event['Event']['title'], 'Term 1 Evaluation');

        //Test invalid event
        $event = $this->Event->getEventById(999);
        $this->assertEqual($event, $empty);

    }


    function testGetEventTemplateTypeId()
    {
        $empty = null;
        $this->Event = & ClassRegistry::init('Event');

        //Test simple eval events
        $id = $this->Event->getEventTemplateTypeId(1);
        $this->assertEqual($id, 1);

        //Test rubric events
        $id = $this->Event->getEventTemplateTypeId(2);
        $this->assertEqual($id, 2);

        //Test survey eval events
        $id = $this->Event->getEventTemplateTypeId(4);
        $this->assertEqual($id, 3);

        //Test mixed eval events
        $id = $this->Event->getEventTemplateTypeId(3);
        $this->assertEqual($id, 4);

        //Test invalid events
        $id = $this->Event->getEventTemplateTypeId(999);
        $this->assertEqual($id, $empty);

    }


    function testGetEventTitleById()
    {

        $empty = null;
        $this->Event = & ClassRegistry::init('Event');

        //Test valid event
        $title = $this->Event->getEventTitleById(1);
        $this->assertEqual($title, 'Term 1 Evaluation');

        //Test invalid event
        $title = $this->Event->getEventTitleById(999);
        $this->assertEqual($title, $empty);

    }

    function testGetAccessibleEventById()
    {
        // superadmin
        $event = $this->Event->getAccessibleEventById(1, 1, Course::FILTER_PERMISSION_SUPERADMIN);
        $this->assertEqual(count($event), 1);
        $event = $event['Event'];
        $this->assertEqual($event['id'], 1);
        $this->assertEqual($event['title'], 'Term 1 Evaluation');

        // admins can access their faculty's event
        $event = $this->Event->getAccessibleEventById(1, 34, Course::FILTER_PERMISSION_FACULTY);
        $this->assertEqual(count($event), 1);
        $event = $event['Event'];
        $this->assertEqual($event['id'], 1);
        $this->assertEqual($event['title'], 'Term 1 Evaluation');

        // admins cannot access other faculty's event
        $event = $this->Event->getAccessibleEventById(1, 38, Course::FILTER_PERMISSION_FACULTY);
        $this->assertFalse($event);

        // instructor can access their course's event
        $event = $this->Event->getAccessibleEventById(1, 2, Course::FILTER_PERMISSION_OWNER);
        $this->assertEqual(count($event), 1);
        $event = $event['Event'];
        $this->assertEqual($event['id'], 1);
        $this->assertEqual($event['title'], 'Term 1 Evaluation');

        // instructor cannot access other course's event
        $event = $this->Event->getAccessibleEventById(1, 3, Course::FILTER_PERMISSION_OWNER);
        $this->assertFalse($event);
    }

    function testGetEventsByUserId()
    {
        $events = $this->Event->getEventsByUserId(5);
        $evaluations = $events['Evaluations'];
        $this->assertEqual(count($evaluations), 7);
        $surveys = $events['Surveys'];
        $this->assertEqual(count($surveys), 2);

        $events = $this->Event->getEventsByUserId(27);
        $evaluations = $events['Evaluations'];
        $this->assertEqual(count($evaluations), 0);
        $surveys = $events['Surveys'];
        $this->assertEqual(count($surveys), 0);
    }

    #####################################################################################################################################################
    ###############################################     HELPER FUNCTIONS     ############################################################################
    #####################################################################################################################################################


    function toEventNameArray($events)
    {
        $courseNameArray = array();
        foreach ($events as $event) {
            array_push($courseNameArray, $event['Event']['title']);
        }
        return $courseNameArray;
    }

    function toGroupArray($events)
    {
        $groups = array();
        foreach ($events as $event) {
            array_push($groups, $event);
        }
        return $groups;
    }
}
