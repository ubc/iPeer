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
        'app.evaluation_simple', 'app.faculty', 'app.user_faculty', 'app.department',
        'app.course_department', 'app.sys_parameter', 'app.user_tutor',
        'app.penalty', 'app.simple_evaluation', 'app.rubric', 'app.rubrics_lom',
        'app.rubrics_criteria', 'app.rubrics_criteria_comment', 'app.mixeval',
        'app.mixeval_question', 'app.mixeval_question_type', 'mixeval_question_desc',
        'app.survey_input', 'app.oauth_token', 'app.evaluation_rubric',
        'app.evaluation_rubric_detail', 'app.evaluation_mixeval_detail',
        'app.evaluation_mixeval', 'app.sys_parameter'
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
        $course = Set::sort($this->Event->getCourseEvent(1), '{n}.Event.id', 'asc');
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

        $course = Set::sort($this->Event->GetCourseEvalEvent(1), '{n}.Event.id', 'asc');
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
        $this->assertEqual($course, 17);

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
        //TODO - test for second parameter
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
        // normal student
        $events = $this->Event->getEventsByUserId(5);
        $evaluations = $events['Evaluations'];
        $this->assertEqual(count($evaluations), 8);
        $surveys = $events['Surveys'];
        $this->assertEqual(count($surveys), 2);

        // studnet with no events
        $events = $this->Event->getEventsByUserId(27);
        $evaluations = $events['Evaluations'];
        $this->assertEqual(count($evaluations), 0);
        $surveys = $events['Surveys'];
        $this->assertEqual(count($surveys), 0);

        // normal student with fields
        $events = $this->Event->getEventsByUserId(5, array('id', 'title'));
        $evaluations = $events['Evaluations'];
        $this->assertEqual(count($evaluations), 8);
        $surveys = $events['Surveys'];
        $this->assertEqual(count($surveys), 2);

        // student within two groups in the same event
        $events = $this->Event->getEventsByUserId(7);
        $evaluations = $events['Evaluations'];
        $this->assertEqual(count($evaluations), 12);
        $surveys = $events['Surveys'];
        $this->assertEqual(count($surveys), 2);
    }

    function testGetPendingEventsByUserId()
    {
        $options = array('submission' => 1, 'results' => 0);
        $events = $this->Event->getPendingEventsByUserId(5, $options);
        $ids = Set::extract($events, '/id');
        sort($ids);
        $this->assertEqual(count($events), 5);
        $this->assertEqual($ids, array(1,2,3,4,5));

        $events = $this->Event->getPendingEventsByUserId(27, $options);
        $this->assertEqual(count($events), 0);

        // student within two groups in the same event
        $events = $this->Event->getPendingEventsByUserId(7, $options);
        $ids = Set::extract($events, '/id');
        sort($ids);
        $this->assertEqual(count($events), 6);
        // event 6 has two occurrences as user in two groups
        $this->assertEqual($ids, array(1,2,3,5,6,6));
    }

    function testGetEventSubmission()
    {
        //TODO
    }

    function testTimezone()
    {
        $now = time(); // current php time (eg. not sql time)
        $event = $this->Event->findById(1);
        $dueDate = strtotime($event['Event']['due_date']);
        $dueIn = $dueDate - $now;
        $serverTZ = date_default_timezone_get(); // saves the server's timezone
        // the difference between the our calculuation and the model's calcualtion
        // should be within 10 seconds
        $this->assertWithinMargin($event['Event']['due_in'], $dueIn, 10);
        // switch to timezone without daylight savings
        date_default_timezone_set('America/Regina');
        $dueIn = $dueDate - time();
        $this->assertWithinMargin($event['Event']['due_in'], $dueIn, 10);
        // switch to timezone with daylight savings
        date_default_timezone_set('America/Vancouver');
        $dueIn = $dueDate - time();
        $this->assertWithinMargin($event['Event']['due_in'], $dueIn, 10);
        // switch timezone back to original
        date_default_timezone_set($serverTZ);
    }

    function testCsvExport() {
        $year = date("Y") + 1;
        $this->Event->recursive = 1; // in-production, the groups get returned; in tests, we need to enable this
        $mech_events = array (
            0 => array (
                0 => 'Title', 1 => 'Description', 2 => 'Type', 3 => 'Template', 4 => 'Opens', 5 => 'Due', 6 => 'Closes', 7 => 'Results Open', 8 => 'Results Close', 9 => 'Self-Evaluation?', 10 => 'Comments required?', 11 => 'Auto-release results?', 12 => 'Student result mode', 13 => 'Groups', ),
                1 => array ( 'title' => 'Term 1 Evaluation', 'description' => '', 'event_template_type_id' => '1', 'template_id' => '1', 'release_date_begin' => '2011-06-16 16:34:49', 'due_date' => $year.'-07-02 16:34:43', 'release_date_end' => '2023-07-22 16:34:53', 'result_release_date_begin' => '2024-07-04 16:34:43', 'result_release_date_end' => '2024-07-30 16:34:43', 'self_eval' => '0', 'com_req' => '0', 'auto_release' => '0', 'enable_details' => '1', 0 => '*', ),
                2 => array ( 'title' => 'Term Report Evaluation', 'description' => '', 'event_template_type_id' => '2', 'template_id' => '1', 'release_date_begin' => '2011-06-06 08:59:35', 'due_date' => $year.'-06-08 08:59:29', 'release_date_end' => '2023-07-02 08:59:41', 'result_release_date_begin' => '2024-06-09 08:59:29', 'result_release_date_end' => '2024-07-08 08:59:29', 'self_eval' => '0', 'com_req' => '0', 'auto_release' => '0', 'enable_details' => '1', 0 => '*', ),
                3 => array ( 'title' => 'Project Evaluation', 'description' => '', 'event_template_type_id' => '4', 'template_id' => '1', 'release_date_begin' => '2011-06-07 09:00:35', 'due_date' => $year.'-07-02 09:00:28', 'release_date_end' => '2023-07-09 09:00:39', 'result_release_date_begin' => '2023-07-04 09:00:28', 'result_release_date_end' => '2024-07-12 09:00:28', 'self_eval' => '0', 'com_req' => '0', 'auto_release' => '0', 'enable_details' => '1', 0 => '*', ),
                4 => array ( 'title' => 'Team Creation Survey', 'description' => NULL, 'event_template_type_id' => '3', 'template_id' => '1', 'release_date_begin' => '2012-07-01 11:20:00', 'due_date' => $year.'-07-31 11:20:00', 'release_date_end' => $year.'-12-31 11:20:00', 'result_release_date_begin' => '', 'result_release_date_end' => '', 'self_eval' => '', 'com_req' => '', 'auto_release' => '', 'enable_details' => '', 0 => '', ),
                5 => array ( 'title' => 'Survey, all Q types', 'description' => NULL, 'event_template_type_id' => '3', 'template_id' => '2', 'release_date_begin' => '2012-07-01 11:20:00', 'due_date' => $year.'-07-31 11:20:00', 'release_date_end' => $year.'-12-31 11:20:00', 'result_release_date_begin' => '', 'result_release_date_end' => '', 'self_eval' => '', 'com_req' => '', 'auto_release' => '', 'enable_details' => '', 0 => '', ),
                6 => array ( 'title' => 'simple evaluation 2', 'description' => '2nd simple evaluation', 'event_template_type_id' => '1', 'template_id' => '1', 'release_date_begin' => '2012-11-20 00:00:00', 'due_date' => '2012-11-28 00:00:00', 'release_date_end' => ($year+1).'-11-29 00:00:00', 'result_release_date_begin' => ($year+1).'-11-30 00:00:00', 'result_release_date_end' => ($year+1).'-12-12 00:00:00', 'self_eval' => '0', 'com_req' => '0', 'auto_release' => '0', 'enable_details' => '1', 0 => '*', ),
                7 => array ( 'title' => 'simple evaluation 3', 'description' => '3rd simple evaluation for testing overdue event', 'event_template_type_id' => '1', 'template_id' => '1', 'release_date_begin' => '2012-11-20 00:00:00', 'due_date' => '2012-11-28 00:00:00', 'release_date_end' => '2012-11-29 00:00:00', 'result_release_date_begin' => ($year+1).'-11-30 00:00:00', 'result_release_date_end' => ($year+1).'-12-12 00:00:00', 'self_eval' => '0', 'com_req' => '0', 'auto_release' => '0', 'enable_details' => '1', 0 => 'Reapers', ),
                8 => array ( 'title' => 'simple evaluation 4', 'description' => 'result released with submission', 'event_template_type_id' => '1', 'template_id' => '1', 'release_date_begin' => '2012-11-20 00:00:00', 'due_date' => '2012-11-28 00:00:00', 'release_date_end' => '2012-11-29 00:00:00', 'result_release_date_begin' => '2012-11-30 00:00:00', 'result_release_date_end' => ($year+1).'-12-12 00:00:00', 'self_eval' => '0', 'com_req' => '0', 'auto_release' => '0', 'enable_details' => '1', 0 => 'Reapers', ),
                9 => array ( 'title' => 'simple evaluation 5', 'description' => 'result released with no submission', 'event_template_type_id' => '1', 'template_id' => '1', 'release_date_begin' => '2012-11-20 00:00:00', 'due_date' => '2012-11-28 00:00:00', 'release_date_end' => '2012-11-29 00:00:00', 'result_release_date_begin' => '2012-11-30 00:00:00', 'result_release_date_end' => ($year+1).'-12-12 00:00:00', 'self_eval' => '0', 'com_req' => '0', 'auto_release' => '0', 'enable_details' => '1', 0 => 'Reapers', ),
                10 => array ( 'title' => 'simple evaluation 6', 'description' => 'result released with no submission', 'event_template_type_id' => '1', 'template_id' => '1', 'release_date_begin' => $year.'-07-31 11:20:00', 'due_date' => ($year+1).'-07-31 11:20:00', 'release_date_end' => ($year+1).'-07-31 11:20:00', 'result_release_date_begin' => ($year+1).'-07-31 11:20:00', 'result_release_date_end' => ($year+2).'-07-31 11:20:00', 'self_eval' => '0', 'com_req' => '0', 'auto_release' => '0', 'enable_details' => '1', 0 => 'Reapers', ),
                11 => array ( 'title' => 'timezone test A', 'description' => 'before DST', 'event_template_type_id' => '1', 'template_id' => '1', 'release_date_begin' => '2012-11-20 00:00:00', 'due_date' => '2013-02-14 00:00:00', 'release_date_end' => ($year+1).'-11-29 00:00:00', 'result_release_date_begin' => ($year+1).'-11-30 00:00:00', 'result_release_date_end' => ($year+1).'-12-12 00:00:00', 'self_eval' => '0', 'com_req' => '0', 'auto_release' => '0', 'enable_details' => '1', 0 => '', ),
                12 => array ( 'title' => 'timezone test B', 'description' => 'transition to DST', 'event_template_type_id' => '1', 'template_id' => '1', 'release_date_begin' => '2012-11-20 00:00:00', 'due_date' => '2013-03-10 02:00:00', 'release_date_end' => ($year+1).'-11-29 00:00:00', 'result_release_date_begin' => ($year+1).'-11-30 00:00:00', 'result_release_date_end' => ($year+1).'-12-12 00:00:00', 'self_eval' => '0', 'com_req' => '0', 'auto_release' => '0', 'enable_details' => '1', 0 => '', ),
                13 => array ( 'title' => 'timezone test C', 'description' => 'during DST', 'event_template_type_id' => '1', 'template_id' => '1', 'release_date_begin' => '2012-11-20 00:00:00', 'due_date' => '2013-06-12 00:00:00', 'release_date_end' => ($year+1).'-11-29 00:00:00', 'result_release_date_begin' => ($year+1).'-11-30 00:00:00', 'result_release_date_end' => ($year+1).'-12-12 00:00:00', 'self_eval' => '0', 'com_req' => '0', 'auto_release' => '0', 'enable_details' => '1', 0 => '', ),
                14 => array ( 'title' => 'timezone test D', 'description' => 'transition from DST', 'event_template_type_id' => '1', 'template_id' => '1', 'release_date_begin' => '2012-11-20 00:00:00', 'due_date' => '2013-11-03 02:00:00', 'release_date_end' => ($year+1).'-11-29 00:00:00', 'result_release_date_begin' => ($year+1).'-11-30 00:00:00', 'result_release_date_end' => ($year+1).'-12-12 00:00:00', 'self_eval' => '0', 'com_req' => '0', 'auto_release' => '0', 'enable_details' => '1', 0 => '', ),
                15 => array ( 'title' => 'timezone test E', 'description' => 'after DST', 'event_template_type_id' => '1', 'template_id' => '1', 'release_date_begin' => '2012-11-20 00:00:00', 'due_date' => '2013-11-04 00:00:00', 'release_date_end' => ($year+1).'-11-29 00:00:00', 'result_release_date_begin' => ($year+1).'-11-30 00:00:00', 'result_release_date_end' => ($year+1).'-12-12 00:00:00', 'self_eval' => '0', 'com_req' => '0', 'auto_release' => '0', 'enable_details' => '1', 0 => '', ),
                16 => array ( 'title' => 'timezone test B1', 'description' => 'missed time from transition to DST', 'event_template_type_id' => '1', 'template_id' => '1', 'release_date_begin' => '2012-11-20 00:00:00', 'due_date' => '2013-03-10 02:30:00', 'release_date_end' => ($year+1).'-11-29 00:00:00', 'result_release_date_begin' => ($year+1).'-11-30 00:00:00', 'result_release_date_end' => ($year+1).'-12-12 00:00:00', 'self_eval' => '0', 'com_req' => '0', 'auto_release' => '0', 'enable_details' => '1', 0 => '', ),
                17 => array ( 'title' => 'timezone test D1', 'description' => 'overlapped time from transition from DST', 'event_template_type_id' => '1', 'template_id' => '1', 'release_date_begin' => '2012-11-20 00:00:00', 'due_date' => '2013-11-03 01:30:00', 'release_date_end' => ($year+1).'-11-29 00:00:00', 'result_release_date_begin' => ($year+1).'-11-30 00:00:00', 'result_release_date_end' => ($year+1).'-12-12 00:00:00', 'self_eval' => '0', 'com_req' => '0', 'auto_release' => '0', 'enable_details' => '1', 0 => '', ), );
        $cpsc_events = array ( 0 => array ( 0 => 'Title', 1 => 'Description', 2 => 'Type', 3 => 'Template', 4 => 'Opens', 5 => 'Due', 6 => 'Closes', 7 => 'Results Open', 8 => 'Results Close', 9 => 'Self-Evaluation?', 10 => 'Comments required?', 11 => 'Auto-release results?', 12 => 'Student result mode', 13 => 'Groups', ), );

        $this->assertEqual($this->Event->csvExportEventsByCourseId(1),$mech_events); //mech
        $this->assertEqual($this->Event->csvExportEventsByCourseId(3),$cpsc_events); //cpsc
    }

    function testCsvImportFailing() {
        // tests is an array of tests
        // each test is array("expected error message",courseId,userId,events)
        // function call: importEventsByCsv($courseId,$events,$userId)
        $tests = array();

        // empty file
        $tests[] = array(
            'Event on row 1 does not have an entry for column: Title',1,1,
            array ( 0 => array ( 0 => NULL, ), )
        );

        // only header row
        $tests[] = array(
            'Error: No events to import.',1,1,
            array ( 0 => array ( 0 => 'Title', 1 => 'Description', 2 => 'Type', 3 => 'Template', 4 => 'Opens', 5 => 'Due', 6 => 'Closes', 7 => 'Results Open', 8 => 'Results Close', 9 => 'Self-Evaluation?', 10 => 'Comments required?', 11 => 'Auto-release results?', 12 => 'Student result mode', 13 => 'Groups', ), )
        );

        // empty row between filled rows
        $tests[] = array(
            'Event "" (on row 2), has an invalid field: "Title": Title is required.',1,1,
            array ( 0 => array ( 0 => 'Title', 1 => 'Description', 2 => 'Type', 3 => 'Template', 4 => 'Opens', 5 => 'Due', 6 => 'Closes', 7 => 'Results Open', 8 => 'Results Close', 9 => 'Self-Evaluation?', 10 => 'Comments required?', 11 => 'Auto-release results?', 12 => 'Student result mode', 13 => 'Groups', ), 1 => array ( 0 => '', 1 => '', 2 => '', 3 => '', 4 => '', 5 => '', 6 => '', 7 => '', 8 => '', 9 => '', 10 => '', 11 => '', 12 => '', 13 => '', ), 2 => array ( 0 => 'test-event', 1 => '', 2 => '1', 3 => '1', 4 => '2011-06-16 16:34:49', 5 => '2049-07-02 16:34:43', 6 => '2050-07-22 16:34:53', 7 => '2051-07-04 16:34:43', 8 => '2051-07-30 16:34:43', 9 => '0', 10 => '0', 11 => '0', 12 => '1', 13 => '*', ), )
        );

        // too many columns
        $tests[] = array(
            'Event on row 1 has too many columns',1,1,
            array ( 0 => array ( 0 => 'test-event', 1 => '', 2 => '1', 3 => '1', 4 => '2011-06-16 16:34:49', 5 => '2049-07-02 16:34:43', 6 => '2050-07-22 16:34:53', 7 => '2051-07-04 16:34:43', 8 => '2051-07-30 16:34:43', 9 => '0', 10 => '0', 11 => '0', 12 => '1', 13 => '*', 14 => 'a', ), )
        );

        // missing type
        $tests[] = array(
            'Event "test-event" (on row 1), has an invalid field: "Type": Please select a template type.',1,1,
            array ( 0 => array ( 0 => 'test-event', 1 => '', 2 => '', 3 => '1', 4 => '2011-06-16 16:34:49', 5 => '2049-07-02 16:34:43', 6 => '2050-07-22 16:34:53', 7 => '2051-07-04 16:34:43', 8 => '2051-07-30 16:34:43', 9 => '0', 10 => '0', 11 => '0', 12 => '1', 13 => '*', ), )
        );

        // bad type
        $tests[] = array(
            'Event "test-event" (on row 1), has an invalid template type "z"',1,1,
            array ( 0 => array ( 0 => 'test-event', 1 => '', 2 => 'z', 3 => '1', 4 => '2011-06-16 16:34:49', 5 => '2049-07-02 16:34:43', 6 => '2050-07-22 16:34:53', 7 => '2051-07-04 16:34:43', 8 => '2051-07-30 16:34:43', 9 => '0', 10 => '0', 11 => '0', 12 => '1', 13 => '*', ), )
        );

        // missing template
        $tests[] = array(
            'Event "test-event" (on row 1), has an invalid field: "Template": Please select a template.',1,1,
            array ( 0 => array ( 0 => 'test-event', 1 => '', 2 => '1', 3 => '', 4 => '2011-06-16 16:34:49', 5 => '2049-07-02 16:34:43', 6 => '2050-07-22 16:34:53', 7 => '2051-07-04 16:34:43', 8 => '2051-07-30 16:34:43', 9 => '0', 10 => '0', 11 => '0', 12 => '1', 13 => '*', ), )
        );

        // non-existent template
        $tests[] = array(
            'Event "test-event" (on row 1), has a non-existent simple evaluation of id: 9999',1,1,
            array ( 0 => array ( 0 => 'test-event', 1 => '', 2 => '1', 3 => '9999', 4 => '2011-06-16 16:34:49', 5 => '2049-07-02 16:34:43', 6 => '2050-07-22 16:34:53', 7 => '2051-07-04 16:34:43', 8 => '2051-07-30 16:34:43', 9 => '0', 10 => '0', 11 => '0', 12 => '1', 13 => '*', ), )
        );

        // private template
        // we set an existing one to private (we revert this after the tests run)
        // note that we switch to course=3 and user=2
        $this->Rubric = ClassRegistry::init('Rubric');
        $privateRubric = $this->Rubric->getEvaluation(1);
        $privateRubric['Rubric']['availability'] = 'private';
        $this->Rubric->save($privateRubric);
        $tests[] = array(
            'Event "test-event" (on row 1), has a non-accessible template: 1',3,2,
            array ( 0 => array ( 0 => 'test-event', 1 => '', 2 => '2', 3 => '1', 4 => '2011-06-16 16:34:49', 5 => '2049-07-02 16:34:43', 6 => '2050-07-22 16:34:53', 7 => '2051-07-04 16:34:43', 8 => '2051-07-30 16:34:43', 9 => '0', 10 => '0', 11 => '0', 12 => '1', ), )
        );

        // bad date format
        $tests[] = array(
            'Event "test-event" (on row 1), has an invalid field: "Closes": Must be in Year-Month-Day Hour:Minute:Second format.',1,1,
            array ( 0 => array ( 0 => 'test-event', 1 => '', 2 => '1', 3 => '1', 4 => '2011-06-16 16:34:49', 5 => '2049-07-02 16:34:43', 6 => '2023/07/22 abcd 16:34:53', 7 => '2051-07-04 16:34:43', 8 => '2051-07-30 16:34:43', 9 => '0', 10 => '0', 11 => '0', 12 => '1', 13 => '*', ), )
        );

        // closing date is in past
        $tests[] = array(
            'Event "test-event" (on row 1), has a closing date in the past. Please check that you have updated the dates for the new session/term',1,1,
            array ( 0 => array ( 0 => 'test-event', 1 => '', 2 => '1', 3 => '1', 4 => '2011-06-16 16:34:49', 5 => '2012-07-02 16:34:43', 6 => '2012-07-22 16:34:53', 7 => '2051-07-04 16:34:43', 8 => '2051-07-30 16:34:43', 9 => '0', 10 => '0', 11 => '0', 12 => '1', 13 => '*', ), )
        );

        // survey with groups
        $tests[] = array(
            'Event "test-event" (on row 1), has groups, which are not allowed for a survey.',1,1,
            array ( 0 => array ( 0 => 'test-event', 1 => '', 2 => '3', 3 => '1', 4 => '2011-06-16 16:34:49', 5 => '2049-07-02 16:34:43', 6 => '2050-07-22 16:34:53', 7 => '2051-07-04 16:34:43', 8 => '2051-07-30 16:34:43', 9 => '0', 10 => '0', 11 => '0', 12 => '1', 13 => '*', ), )
        );

        // badly formatted groups
        $tests[] = array(
            'Event "test-event" (on row 1), has an invalid group of id: "1,2,3"',1,1,
            array ( 0 => array ( 0 => 'test-event', 1 => '', 2 => '1', 3 => '1', 4 => '2011-06-16 16:34:49', 5 => '2049-07-02 16:34:43', 6 => '2050-07-22 16:34:53', 7 => '2051-07-04 16:34:43', 8 => '2051-07-30 16:34:43', 9 => '0', 10 => '0', 11 => '0', 12 => '1', 13 => '1,2,3', ), )
        );

        // group not in course
        $tests[] = array(
            'Event "test-event" (on row 1), has an invalid group of id: "1"',3,1,
            array ( 0 => array ( 0 => 'test-event', 1 => '', 2 => '1', 3 => '1', 4 => '2011-06-16 16:34:49', 5 => '2049-07-02 16:34:43', 6 => '2050-07-22 16:34:53', 7 => '2051-07-04 16:34:43', 8 => '2051-07-30 16:34:43', 9 => '0', 10 => '0', 11 => '0', 12 => '1', 13 => '1', ), )
        );

        // missing title
        $tests[] = array(
            'Event "" (on row 2), has an invalid field: "Title": Title is required.',1,1,
            array ( 0 => array ( 0 => 'Title', 1 => 'Description', 2 => 'Type', 3 => 'Template', 4 => 'Opens', 5 => 'Due', 6 => 'Closes', 7 => 'Results Open', 8 => 'Results Close', 9 => 'Self-Evaluation?', 10 => 'Comments required?', 11 => 'Auto-release results?', 12 => 'Student result mode', 13 => 'Groups', ), 1 => array ( 0 => '', 1 => '', 2 => '1', 3 => '1', 4 => '2011-06-16 16:34:49', 5 => '2049-07-02 16:34:43', 6 => '2050-07-22 16:34:53', 7 => '2051-07-04 16:34:43', 8 => '2051-07-30 16:34:43', 9 => '0', 10 => '0', 11 => '0', 12 => '1', 13 => '*', ), )
        );

        // Run all above tests
        foreach ($tests as $test) {
            $this->assertEqual($this->Event->importEventsByCsv($test[1],$test[3],$test[2]),$test[0]);
        }

        // we revert the template back to public
        $privateRubric['Rubric']['availability'] = 'public';
        $this->Rubric->save($privateRubric);
    }

    function testCsvImportPassing() {
        // function call: importEventsByCsv($courseId,$events,$userId)

        $testEvents = array ( 0 => array ( 0 => 'test-event-1', 1 => 'desc1', 2 => '1', 3 => '1', 4 => '2011-06-16 16:34:49', 5 => '2049-07-02 16:34:43', 6 => '2050-07-22 16:34:53', 7 => '2051-07-04 16:34:43', 8 => '2051-07-30 16:34:43', 9 => '0', 10 => '0', 11 => '0', 12 => '1', 13 => '1;2', ), 1 => array ( 0 => 'test-event-2', 1 => 'desc2', 2 => '2', 3 => '1', 4 => '2011-06-17 16:34:49', 5 => '2049-07-03 16:34:43', 6 => '2050-07-23 16:34:53', 7 => '2051-07-05 16:34:43', 8 => '2051-07-30 16:34:43', 9 => '1', 10 => '1', 11 => '1', 12 => '1', 13 => '', ), 2 => array ( 0 => 'test-event-3', 1 => 'desc3', 2 => '3', 3 => '1', 4 => '2011-06-18 16:34:49', 5 => '2049-07-04 16:34:43', 6 => '2050-07-24 16:34:53', 7 => '', 8 => '', 9 => '', 10 => '', 11 => '', 12 => '', 13 => '', ), 3 => array ( 0 => 'test-event-4', 1 => 'desc4', 2 => '4', 3 => '1', 4 => '2011-06-19 16:34:49', 5 => '2049-07-05 16:34:43', 6 => '2050-07-25 16:34:53', 7 => '2051-07-07 16:34:43', 8 => '2051-07-30 16:34:43', 9 => '1', 10 => '0', 11 => '1', 12 => '0', 13 => '*', ), );

        // execute action and basic test
        $this->assertEqual($this->Event->importEventsByCsv(1,$testEvents,1),4);

        // check first event properties
        $testEvent1 = $this->Event->find('first', array(
            'conditions' => array('Event.title' => 'test-event-1')));
        $this->assertFalse(empty($testEvent1));
        $this->assertEqual($testEvent1['Event']['description'],'desc1');
        $this->assertEqual($testEvent1['Event']['event_template_type_id'],'1');
        $this->assertEqual($testEvent1['Event']['release_date_begin'],'2011-06-16 16:34:49');

        $this->assertEqual($testEvent1['Event']['self_eval'],'0');
        $this->assertEqual($testEvent1['Event']['com_req'],'0');
        $this->assertEqual($testEvent1['Event']['auto_release'],'0');
        $this->assertEqual($testEvent1['Event']['enable_details'],'1');

        $this->assertEqual($testEvent1['Group'][0]['id'],'1');
        $this->assertEqual($testEvent1['Group'][1]['id'],'2');
        $this->assertTrue(count($testEvent1['Group']),2);
        $this->assertEqual($testEvent1['GroupEvent'][0]['grade_release_status'],'None');
        $this->assertEqual($testEvent1['GroupEvent'][1]['comment_release_status'],'None');

        $this->assertEqual($testEvent1['Course']['id'],'1');

        // check second event properties
        $testEvent2 = $this->Event->find('first', array(
            'conditions' => array('Event.title' => 'test-event-2')));
        $this->assertFalse(empty($testEvent2));
        $this->assertEqual($testEvent2['Event']['description'],'desc2');
        $this->assertEqual($testEvent2['Event']['event_template_type_id'],'2');
        $this->assertEqual($testEvent2['Event']['release_date_begin'],'2011-06-17 16:34:49');

        $this->assertEqual($testEvent2['Event']['self_eval'],'1');
        $this->assertEqual($testEvent2['Event']['com_req'],'1');
        $this->assertEqual($testEvent2['Event']['auto_release'],'1');
        $this->assertEqual($testEvent2['Event']['enable_details'],'1');

        $this->assertEqual(count($testEvent2['Group']),0);

        $this->assertEqual($testEvent2['Course']['id'],'1');

        // check third event properties (note: this is a survey)
        $testEvent3 = $this->Event->find('first', array(
            'conditions' => array('Event.title' => 'test-event-3')));
        $this->assertFalse(empty($testEvent3));
        $this->assertEqual($testEvent3['Event']['description'],'desc3');
        $this->assertEqual($testEvent3['Event']['event_template_type_id'],'3');
        $this->assertEqual($testEvent3['Event']['release_date_begin'],'2011-06-18 16:34:49');

        $this->assertEqual(count($testEvent3['Group']),0);

        $this->assertEqual($testEvent3['Course']['id'],'1');

        // check fourth event properties
        $testEvent4 = $this->Event->find('first', array(
            'conditions' => array('Event.title' => 'test-event-4')));
        $this->assertFalse(empty($testEvent4));
        $this->assertEqual($testEvent4['Event']['description'],'desc4');
        $this->assertEqual($testEvent4['Event']['event_template_type_id'],'4');
        $this->assertEqual($testEvent4['Event']['release_date_begin'],'2011-06-19 16:34:49');

        $this->assertEqual($testEvent4['Event']['self_eval'],'1');
        $this->assertEqual($testEvent4['Event']['com_req'],'0');
        $this->assertEqual($testEvent4['Event']['auto_release'],'1');
        $this->assertEqual($testEvent4['Event']['enable_details'],'0');

        $this->assertEqual($testEvent4['Group'][0]['id'],'1');
        $this->assertEqual($testEvent4['Group'][1]['id'],'2');
        $this->assertTrue(count($testEvent4['Group']),2);
        $this->assertEqual($testEvent4['GroupEvent'][0]['grade_release_status'],'Auto');
        $this->assertEqual($testEvent4['GroupEvent'][1]['comment_release_status'],'Auto');

        // delete events
        $this->Event->delete($testEvent1['Event']['id']);
        $this->Event->delete($testEvent2['Event']['id']);
        $this->Event->delete($testEvent3['Event']['id']);
        $this->Event->delete($testEvent4['Event']['id']);
        $deletedEvent = $this->Event->find('first', array(
            'conditions' => array('Event.title' => 'test-event-1')));
        // ensure got deleted
        $this->assertFalse($deletedEvent);

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
