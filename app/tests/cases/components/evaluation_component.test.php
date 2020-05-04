<?php
App::import('Component', 'Evaluation');
App::import('Component', 'Auth');
class FakeEvaluationController extends Controller {
    public $name = 'FakeEvaluationController';
    public $components = array('Evaluation', 'Auth');
    public $uses = null;
    public $params = array('action' => 'test');
}

class EvaluationTestCase extends CakeTestCase
{
    public $fixtures = array('app.course', 'app.role', 'app.user', 'app.group', 'app.penalty', 'app.user_grade_penalty',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission','app.evaluation_mixeval',
        'app.survey_group_set', 'app.survey_group', 'app.groups_member',
        'app.survey_group_member', 'app.question', 'app.survey_input', 'app.rubrics_lom', 'app.evaluation_rubric', 'app.evaluation_rubric_detail',
        'app.response', 'app.survey_question', 'app.user_course', 'app.rubric', 'app.rubrics_criteria', 'app.rubrics_criteria_comment',
        'app.user_enrol', 'app.groups_member', 'app.survey', 'app.mixeval', 'app.mixevals_question', 'app.mixevals_question_desc',
        'app.evaluation_mixeval', 'app.evaluation_mixeval_detail',
        'app.evaluation_simple', 'app.faculty', 'app.user_faculty',
        'app.department', 'app.course_department', 'app.oauth_token', 'app.sys_parameter',
        'app.user_tutor', 'app.simple_evaluation'
    );

    function startCase()
    {
        $this->EvaluationComponentTest = new EvaluationComponent();
        $this->EvaluationSimple = ClassRegistry::init('EvaluationSimple');
        $this->SurveyInput = ClassRegistry::init('SurveyInput');
        $this->SurveyQuestion = ClassRegistry::init('SurveyQuestion');
        $this->EvaluationSubmission = ClassRegistry::init('EvaluationSubmission');
        $this->EvaluationMixeval = ClassRegistry::init('EvaluationMixeval');
        $this->EvaluationRubric = ClassRegistry::init('EvaluationRubric');
        $this->EvaluationRubricDetail   = ClassRegistry::init('EvaluationRubricDetail');
        $this->Event = ClassRegistry::init('Event');

        $this->EvaluationMixevalDetail = ClassRegistry::init('EvaluationMixevalDetail');
        $admin = array('User' => array('username' => 'root',
            'password' => 'ipeer'));
        $this->controller = new FakeEvaluationController();

        $this->controller->constructClasses();
        $this->controller->startupProcess();
        $this->controller->Component->startup($this->controller);
        $this->controller->Auth->startup($this->controller);
        $newView = new View($this->Controller);
        ClassRegistry::addObject('view', $newView);
        $newAuth = $this->controller->Auth;
        ClassRegistry::addObject('Auth', $newAuth);
        $admin = array('User' => array('username' => 'Admin',
            'password' => 'passwordA'));
        $this->controller->Auth->login($admin);
    }

    /**
     * testDaysLate
     *
     * Tests the due date of the first event in the database.
     */
    function testDaysLate()
    {
        $year = date("Y") + 1;
        // Before due date (early)
        $result = $this->EvaluationComponentTest->daysLate(1, $year.'-07-01 16:34:43');
        $this->assertEqual($result, 0);
        // On due date (on time)
        $result = $this->EvaluationComponentTest->daysLate(1, $year.'-07-02 16:34:43');
        $this->assertEqual($result, 0);
        //After due date (late)
        $result = $this->EvaluationComponentTest->daysLate(1, $year.'-07-03 16:34:43');
        $this->assertEqual($result, 1);
    }

    function testFormatGradeReleaseStatus()
    {
        // Set up test data
        $groupEventNone = array();
        $groupEventNone['GroupEvent']['grade_release_status'] = 'None';
        $groupEventSome = array();
        $groupEventSome['GroupEvent']['grade_release_status'] = 'Some';
        $groupEventAll = array();
        $groupEventAll['GroupEvent']['grade_release_status'] = 'All';

        // Case one: "grade_release_status" changed from None => Some
        $result = $this->EvaluationComponentTest->formatGradeReleaseStatus($groupEventNone, true, 3);
        $gradeReleaseStatus = $result['GroupEvent']['grade_release_status'];
        $this->assertEqual($gradeReleaseStatus, 'Some');

        // Case two: "grade_release_status" changed from Some => All
        $result = $this->EvaluationComponentTest->formatGradeReleaseStatus($groupEventSome, true, 0);
        $gradeReleaseStatus = $result['GroupEvent']['grade_release_status'];
        $this->assertEqual($gradeReleaseStatus, 'All');

        // Case three: "grade_release_status" changed from Some => None
        $result = $this->EvaluationComponentTest->formatGradeReleaseStatus($groupEventSome, false, 0);
        $gradeReleaseStatus = $result['GroupEvent']['grade_release_status'];
        $this->assertEqual($gradeReleaseStatus, 'None');

        // Case four: "grade_release_status" changed from All => Some
        $result = $this->EvaluationComponentTest->formatGradeReleaseStatus($groupEventAll, false, 0);
        $gradeReleaseStatus = $result['GroupEvent']['grade_release_status'];
        $this->assertEqual($gradeReleaseStatus, 'Some');

        // Case five: "grade_release_status" stays the same
        $result = $this->EvaluationComponentTest->formatGradeReleaseStatus($groupEventAll, true, 0);
        $gradeReleaseStatus = $result['GroupEvent']['grade_release_status'];
        $this->assertEqual($gradeReleaseStatus, 'All');

        $result = $this->EvaluationComponentTest->formatGradeReleaseStatus($groupEventNone, false, 0);
        $gradeReleaseStatus = $result['GroupEvent']['grade_release_status'];
        $this->assertEqual($gradeReleaseStatus, 'None');
    }

    function testGetGroupReleaseStatus()
    {
        // Set up test data
        $groupEvent = array();
        $groupEvent['GroupEvent']['grade_release_status'] = 'Some';
        $groupEvent['GroupEvent']['comment_release_status'] = 'Some';
        $expect = array('grade_release_status' => 'Some',
            'comment_release_status' => 'Some');
        // Run tests
        $result = $this->EvaluationComponentTest->GetGroupReleaseStatus($groupEvent);
        $this->assertEqual($result, $expect);
    }

    function testFilterString()
    {
        $testString = "HELLO THIS IS A TEST";
        $result = $this->EvaluationComponentTest->filterString($testString);
        $this->assertEqual($testString, $result);

        $testString2 = "HELLO232_32";
        $result = $this->EvaluationComponentTest->filterString($testString2);
        $expect = "HELLO";
        $this->assertEqual($result, $expect);
    }

    function testSaveSimpleEvaluation()
    {
        // Assert data was not saved prior to running function
        $search1 = $this->EvaluationSimple->find('first', array('conditions' => array('comment' => 'Kevin Luk was smart')));
        $search2 = $this->EvaluationSimple->find('first', array('conditions' => array('comment' => 'Zion Au was also smart')));
        $searchEvalSubmission = $this->EvaluationSubmission->find('all', array('conditions' => array('grp_event_id' => 999)));
        $this->assertFalse($search1);
        $this->assertFalse($search2);
        $this->assertFalse($searchEvalSubmission);

        // Set up test data
        $input = $this->setUpSimpleEvaluationTestData();
        $params = $input[0];
        $groupEvent = $input[1];
        $result1 = $this->EvaluationComponentTest->saveSimpleEvaluation($params, $groupEvent, null);
        $search1 = $this->EvaluationSimple->find('first', array('conditions' => array('comment' => 'Kevin Luk was smart')));
        $search2 = $this->EvaluationSimple->find('first', array('conditions' => array('comment' => 'Zion Au was also smart')));
        $searchEvalSubmission = $this->EvaluationSubmission->find('all', array('conditions' => array('grp_event_id' => 999)));

        // Run tests
        $this->assertTrue($result1);
        $this->assertTrue($search1);
        $this->assertTrue($search2);
        $this->assertTrue($searchEvalSubmission);
        $this->assertEqual($search1['EvaluationSimple']['comment'], 'Kevin Luk was smart');
        $this->assertEqual($search1['EvaluationSimple']['score'], 80);
        $this->assertEqual($search1['EvaluationSimple']['grp_event_id'], 999);
        $this->assertEqual($search2['EvaluationSimple']['comment'], 'Zion Au was also smart');
        $this->assertEqual($search2['EvaluationSimple']['score'], 120);
        $this->assertEqual($search2['EvaluationSimple']['grp_event_id'], 999);
        $this->assertEqual($searchEvalSubmission[0]['EvaluationSubmission']['event_id'], 1);
        $this->assertEqual($searchEvalSubmission[0]['EvaluationSubmission']['grp_event_id'], 999);
    }

    function testSaveRubricEvaluation()
    {

    }


    function saveNGetEvalutionRubricDetail()
    {

    }
    
    /**
     * testGetStudentViewRubricResultDetailReview
     *
     * Tests to see if method returns anything useful
     */
    function testGetStudentViewRubricResultDetailReview()
    {
        // Testing the first event in the group_events table
        $userId = 31;
        $id = array('id' => 4);
        $event = array('GroupEvent' => $id);
        $result = $this->EvaluationComponentTest->getStudentViewRubricResultDetailReview($event, $userId);

        // Test the first result returned - not every case is tested
        $this->assertEqual($result[$userId][0]['EvaluationRubric']['id'], 1);
        $this->assertEqual($result[$userId][0]['EvaluationRubric']['evaluator'], 31);
        $this->assertEqual($result[$userId][0]['EvaluationRubric']['evaluatee'], 32);
        $this->assertEqual($result[$userId][0]['EvaluationRubric']['comment'], 'We work well together.');
        $this->assertEqual($result[$userId][0]['EvaluationRubric']['score'], 15.00);
        $this->assertEqual($result[$userId][0]['EvaluationRubric']['comment_release'], 0);
        $this->assertEqual($result[$userId][0]['EvaluationRubric']['grade_release'], 0);
        $this->assertEqual($result[$userId][0]['EvaluationRubric']['grp_event_id'], 4);
        $this->assertEqual($result[$userId][0]['EvaluationRubric']['event_id'], 2);
        $this->assertEqual($result[$userId][0]['EvaluationRubric']['record_status'], 'A');
        $this->assertEqual($result[$userId][0]['EvaluationRubric']['creator_id'], 31);
        $this->assertEqual($result[$userId][0]['EvaluationRubric']['created'], "2012-07-13 10:26:47");
        $this->assertEqual($result[$userId][0]['EvaluationRubric']['updater_id'], 31);
        $this->assertEqual($result[$userId][0]['EvaluationRubric']['modified'], "2012-07-13 10:26:47");
        $this->assertEqual($result[$userId][0]['EvaluationRubric']['rubric_id'], 1);
        $this->assertEqual($result[$userId][0]['EvaluationRubricDetail'][0]['id'], 1);
        $this->assertEqual($result[$userId][0]['EvaluationRubricDetail'][0]['evaluation_rubric_id'], 1);
        $this->assertEqual($result[$userId][0]['EvaluationRubricDetail'][0]['grade'], 5.00);

        // Tests for non-existent events and users - arbitrary value of 1000 used
        // Method should return a false if either are empty
        $result = $this->EvaluationComponentTest->getStudentViewRubricResultDetailReview(1000, $userId);
        $this->assertFalse($result);
        $result = $this->EvaluationComponentTest->getStudentViewRubricResultDetailReview($event, 1000);
        $this->assertFalse($result);
        $result = $this->EvaluationComponentTest->getStudentViewRubricResultDetailReview(1000, 1000);
        $this->assertFalse($result);
    }

    /**
     * testFormatRubricEvaluationResultsMatrix
     *
     * Tests whether or not the corresponding method returns the expected values.
     */
    function testFormatRubricEvaluationResultsMatrix()
    {
        // Set up test cases
        $evalResult = array(
            1 => array(
                'EvaluationRubric' => array(
                    'evaluator' => 2,
                    'evaluatee' => 1,
                    'comment' => 'Gen comment 1',
                    'score' => 15,
                    'comment_release' => 1,
                    'grade_release' => 1,
                ),
                'EvaluationRubricDetail' => array(
                    0 => array(
                        'id' => 999,
                        'criteria_number' => 1,
                        'criteria_comment' => 'Student 1 Criteria 1',
                        'grade' => 5.00,
                        'comment_release' => 1,
                    ),
                    1 => array(
                        'id' => 1000,
                        'criteria_number' => 2,
                        'criteria_comment' => 'Student 1 Criteria 2',
                        'grade' => 10.00,
                        'comment_release' => 1,
                    )
                )
            ),
            2 => array(
                'EvaluationRubric' => array(
                    'evaluator' => 1,
                    'evaluatee' => 2,
                    'comment' => 'Gen comment 2',
                    'score' => 15,
                    'comment_release' => 1,
                    'grade_release' => 1,
                ),
                'EvaluationRubricDetail' => array(
                    0 => array(
                        'id' => 1001,
                        'criteria_number' => 1,
                        'criteria_comment' => 'Student 2 Criteria 1',
                        'grade' => 10.00,
                        'comment_release' => 1,
                    ),
                    1 => array(
                        'id' => 1002,
                        'criteria_number' => 2,
                        'criteria_comment' => 'Student 2 Criteria 2',
                        'grade' => 5.00,
                        'comment_release' => 1,
                    )
                )
            )
        );
        // Gets the result
        $result = Toolkit::formatRubricEvaluationResultsMatrix($evalResult);
        // Sets up expected return value
        $expected = array(
            1 => array(
                "release_status" => array(
                    'gradeRelease' => 1,
                    'commentRelease' => 1,
                ),
                "total" => 15,
                "evaluator_count" => 1,
                "grades" => array(
                    1 => 5,
                    2 => 10,
                ),
                "individual" => array(
                    2 => array(
                        "general_comment" => array(
                            'comment' => "Gen comment 1",
                            'comment_release' => 1,
                        ),
                        1 => array(
                            'grade' => 5.00,
                            'comment' => 'Student 1 Criteria 1',
                            'id' => 999,
                            'comment_release' => 1,
                        ),
                        2 => array(
                            'grade' => 10.00,
                            'comment' => 'Student 1 Criteria 2',
                            'id' => 1000,
                            'comment_release' => 1,
                        ),
                    ),
                )
            ),
            2 => array(
                "release_status" => array(
                    'gradeRelease' => 1,
                    'commentRelease' => 1,
                ),
                "total" => 15,
                "evaluator_count" => 1,
                "grades" => array(
                    1 => 10,
                    2 => 5,
                ),
                "individual" => array(
                    1 => array(
                        "general_comment" => array(
                            'comment' => "Gen comment 2",
                            'comment_release' => 1,
                        ),
                        1 => array(
                            'grade' => 10.00,
                            'comment' => 'Student 2 Criteria 1',
                            'id' => 1001,
                            'comment_release' => 1,
                        ),
                        2 => array(
                            'grade' => 5.00,
                            'comment' => 'Student 2 Criteria 2',
                            'id' => 1002,
                            'comment_release' => 1,
                        ),
                    ),
                )
            ),
            'grades' => array(
                1 => 7.5,
                2 => 7.5,
            ),
        );
        $this->assertEqual($expected, $result);
        
        // Tests null instance
        $result = Toolkit::formatRubricEvaluationResultsMatrix(null);
        $this->assertFalse($result);

    }

    //TODO
    //Skip, uses Auth
    function testFormatRubricEvaluationResult()
    {

        $event= array('Event' => array('template_id' => 1, 'self_eval' => 1), 'group_id' => 1, 'group_event_id' => 1);
        $displayFormat = 'Detail';
        $studentView = 0;
        $currentUser = array ('id' => 1);
        //   $result = $this->EvaluationComponentTest->formatRubricEvaluationResult($event, $displayFormat, $studentView, $currentUser);
    }

    //TODO
    //Uses Auth
    function testLoadMixEvaluationDetail()
    {

    }
/*
    //TODO
    function testSaveMixevalEvaluation()
    {

        $params = array('form'=> array('memberIDs' => array (3,16), 'group_event_id' => 1, 'event_id' => 1, '3'=>'Save This Section'),
            'data' => array('Evaluation' => array ('evaluator_id' => 16)));
        $this->EvaluationComponentTest->saveMixevalEvaluation($params);
        $now  ='hi';
        $search = $this->EvaluationMixeval->find('all', array('conditions' => array('evaluator' => 16)));
        $this->assertEqual($search[0]['EvaluationMixeval']['evaluator'], 16);
        $this->assertEqual($search[0]['EvaluationMixeval']['evaluatee'], 3);
        $this->assertEqual($search[0]['EvaluationMixeval']['comment_release'], 0);
        $this->assertEqual($search[0]['EvaluationMixeval']['grp_event_id'], 1);
        $this->assertEqual($search[0]['EvaluationMixeval']['event_id'], 1);
        $this->assertEqual($search[0]['EvaluationMixeval']['record_status'], 'A');

    }
*/

    /**
     * testSaveNGetEvaluationMixevalDetail
     *
     * Tests the return total grade value
     * Currently does not test whether values are actually saved
     */
    function testSaveNGetEvaluationMixevalDetail()
    {
        // Set up default values
        $evalMixevalId = 1;
        $auto_release = 0;
        $mixeval = array(
            'Mixeval' => array (
                'zero_mark' => 0,
            ),
            'MixevalQuestion' => array(
                0 => array(
                    'question_num' => 1,
                    'mixeval_question_type_id' => 1,
                    'required' => 1,
                    'self_eval' => 0,
                    'multiplier' => 1,
                    'scale_level' => 5,
                ),
                1 => array(
                    'question_num' => 2,
                    'mixeval_question_type_id' => 2,
                    'required' => 1,
                    'self_eval' => 0,
                    'multiplier' => 0,
                    'scale_level' => 5,
                ),
                2 => array(
                    'question_num' => 3,
                    'mixeval_question_type_id' => 3,
                    'required' => 1,
                    'self_eval' => 0,
                    'multiplier' => 0,
                    'scale_level' => 5,
                ),
            )
        );
        $form = array(
            'EvaluationMixeval' => array(
                1 => array(
                    'grade' => 0.8,
                    'selected_lom' => 4,
                ),
                2 => array(
                    'question_comment' => 'Yes',
                ),
                3 => array(
                    'question_comment' => 'Yes',
                ),
            ),
        );
        // Get the grade with real values
        $grade = $this->EvaluationComponentTest->saveNGetEvaluationMixevalDetail ($evalMixevalId, $mixeval, $form, $auto_release);
        $this->assertEqual($grade, 0.8);
        
        // Test the method with null values
        $grade = $this->EvaluationComponentTest->saveNGetEvaluationMixevalDetail (null, null, null, null);
        $this->assertFalse($grade);
    }

    /**
     * testGetMixevalResultDetail
     *
     * Tests whether or not the return values of the method are equal
     * to the actual values.
     */
    function testGetMixevalResultDetail()
    {

        $groupEventId = 5;
        $groupMembers = array(
            0 => array (
                'User' => array(
                    'id' => 6,
                ),
                'Role' => array(
                    0 => array(
                        'name' => 'student',
                    ),
                ),
            ),
        );
        $include = array(5);
        $required = array(1, 2, 3, 4, 5);
        $eval = $this->EvaluationComponentTest->getMixevalResultDetail($groupEventId, $groupMembers, $include, $required);
        // Set up expected results
        $expected = $this->setUpMixevalResultDetail();
        $this->assertEqual($eval, $expected);

        // Null test cases
        $eval = $this->EvaluationComponentTest->getMixevalResultDetail(null, $groupMembers, null, null);
        $expected = array(
            "scoreRecords"=>array(),
            "evalResult"=> array(),
        );
        $this->assertEqual($eval, $expected);
        $eval = $this->EvaluationComponentTest->getMixevalResultDetail(null, null, null, null);
        $this->assertEqual($eval, $expected);

    }

    function testGetStudentViewMixevalResultDetailReview()
    {
        $userId = 5;
        $event = array (
            'GroupEvent' => array(
                'id' => 5,
            )
        );
        $eval = $this->EvaluationComponentTest->getStudentViewMixevalResultDetailReview($event, $userId);

        // Check due_in time with assertWithinMargin
        $year = date("Y") + 1;
        $currentTime = strtotime($year."-07-02 09:00:28") - time();
        $this->assertWithinMargin($currentTime, $eval[5][0]['Event']['due_in'], 5);
        // Null due_in time since we are doing assertEqual
        $eval[5][0]['Event']['due_in'] = null;
        
        // Set up expected return value
        $expected = $this->setUpMixevalResultDetailReview();
        $this->assertEqual($eval, $expected);

        // Null test cases
        $eval = $this->EvaluationComponentTest->getStudentViewMixevalResultDetailReview(null, 1);
        $this->assertFalse(!empty($eval));
        $eval = $this->EvaluationComponentTest->getStudentViewMixevalResultDetailReview(1, null);
        $this->assertFalse(!empty($eval));
    }
    
    function testFormatMixevalEvaluationResultsMatrix()
    {
    
    }


    function testChangeMixevalEvaluationGradeRelease()
    {



    }

    //function is not used anywhere

    function testFormatStudentViewOfSurveyEvaluationResult()
    {
        //   $survey = $this->EvaluationComponentTest->formatStudentViewOfSurveyEvaluationResult(1);
    }

    function testFormatSurveyEvaluationResult()
    {

        // $survey = $this->EvaluationComponentTest->formatSurveyEvaluationResult(1,1);
        // var_dump($survey);

    }

/*
    // Have yet to implement method
    function testFormatSurveyGroupEvaluationResult()
    {

        $survey = $this->EvaluationComponentTest->formatSurveyGroupEvaluationResult(1, 1);
        $expected = array(
            1 => array(
                'Question' => array(
                    'prompt' => 'Did you learn a lot from this course ?',
                    'type' => 'M',
                    'id' => 1,
                    'number' => 1,
                    'sq_id' => 1,
                    'Responses' => array(
                        'response_0' => array(
                            'response' => 'YES FOR Q1',
                            'id' => 1,
                            'count' =>0
                        ),
                        'response_1' => array(
                            'response' => 'NO FOR Q1',
                            'id' => 5,
                            'count' => 0
                        )
                    ),
                    'total_response' => 0
                )
            ),
            2 => array(
                'Question' => array(
                    'prompt' => 'What was the hardest part ?',
                    'type' => 'M',
                    'id' => 2,
                    'number' => 2,
                    'sq_id' => 2,
                    'Responses' => array(
                        'response_0' => array(
                            'response' => 'NO FOR Q2',
                            'id' => 2,
                            'count' =>0
                        )
                    ),
                    'total_response' => 0)),

            3 => array(
                'Question' => array(
                    'prompt' => 'Did u like the prof ?',
                    'type' => 'A',
                    'id' => 6,
                    'number' => 3,
                    'sq_id' => 6,
                    'Responses' => array(),
                )));
        $this->assertEqual($survey, $expected);

        $survey = $this->EvaluationComponentTest->formatSurveyGroupEvaluationResult(null, null);
        //     $this->assertFalse($survey);
        $survey = $this->EvaluationComponentTest->formatSurveyGroupEvaluationResult(999, 999);
        //     $this->assertFalse($survey);

    }
*/
    function testFormatSurveyEvaluationSummary()
    {
        // Choose the "Survey, all Q types"
        $surveyId = 2;
        $eventId = 5;
        // $userIds is the 'user_id' column in the 'survey_group_members' table
        $userIds = array(5, 6, 7, 13, 15, 17, 19, 21, 26, 28, 32, 33);
        $survey = $this->EvaluationComponentTest->formatSurveyEvaluationSummary($surveyId, $eventId, $userIds);
        $expected = $this->setUpSurveyTestData();
        $this->assertEqual($expected, $survey);
        
        // Testing null and 'random' values
        $survey = $this->EvaluationComponentTest->formatSurveyEvaluationSummary(999, $eventId, $userIds);
        $this->assertFalse($survey);
        // Set up survey results with no responses
        $expected = $this->setUpSurveyBlankData();
        
        $survey = $this->EvaluationComponentTest->formatSurveyEvaluationSummary($surveyId, 999, $userIds);
        $this->assertEqual($expected, $survey);
        
        $survey = $this->EvaluationComponentTest->formatSurveyEvaluationSummary($surveyId, $eventId, array(84, 180, 230));
        $this->assertEqual($expected, $survey);
        
        $survey = $this->EvaluationComponentTest->formatSurveyEvaluationSummary(null, null, null);
        $this->assertFalse($survey);
    }

    /*
    function testFormatStudentViewOfSimpleEvaluationResult()
    {
      $eventInput = $this->Event->find('first', array('conditions' => array('Event.id' => 1)));
      $result = $this->EvaluationComponentTest->formatStudentViewOfSimpleEvaluationResult($eventInput);
      var_dump($return);
    }
    */

    function setUpSimpleEvaluationTestData()
    {
        $params = array();
        $params['form']['memberIDs'][0] = 1;
        $params['form']['memberIDs'][1] = 2;
        $params['form']['points'][0] = 80;
        $params['form']['points'][1] = 120;
        $params['form']['comments'][0] = "Kevin Luk was smart";
        $params['form']['comments'][1] = "Zion Au was also smart";
        $params['data']['Evaluation']['evaluator_id'] = 1;
        $params['data']['Evaluation']['evaluator_id'] = 2;
        $params['form']['evaluateeCount'] = 2;
        $params['form']['event_id'] = 1;

        $groupEvent = array();
        $groupEvent['GroupEvent']['id'] = 999;
        $groupEvent['GroupEvent']['event_id'] = 1;
        $groupEvent['GroupEvent']['group_id'] = 999;

        $return = array($params, $groupEvent);
        return $return;
    }

    function setUpMixevalResultDetail()
    {
        $expected = array(
            "scoreRecords"=> array(
                6=> array (
                    1=> 0.80,
                    2=> 0.80,
                    3=> 0.80,
                ),
            ),
            "evalResult"=> array (
                6=> array(
                    0=> array(
                        "EvaluationMixeval"=> array(
                            "id"=> 5,
                            "evaluator"=> 5,
                            "evaluatee"=> 6,
                            "score"=> "2.40",
                            "comment_release"=> 0,
                            "grade_release"=> 0,
                            "grp_event_id"=> 5,
                            "event_id"=> 3,
                            "record_status"=> "A",
                            "creator_id"=> 5,
                            "created"=> "2012-07-13 15:19:26",
                            "updater_id"=> 5,
                            "modified"=> "2012-07-13 15:19:26",
                            "creator"=> "Ed Student",
                            "updater"=> "Ed Student",
                        ),
                        "EvaluationMixevalDetail"=> array (
                            0 => Array(
                                "id" => 25,
                                "evaluation_mixeval_id" => 5,
                                "question_number" => 1,
                                "question_comment" => null,
                                "selected_lom" => 4,
                                "grade" => 0.80,
                                "comment_release" => 0,
                                "record_status" => "A",
                                "creator_id" => 5,
                                "created" => "2012-07-13 15:19:26",
                                "updater_id" => 5,
                                "modified" => "2012-07-13 15:19:26",
                                "creator" => "Ed Student",
                                "updater" => "Ed Student",
                            ),
                            1 => Array(
                                "id" => 26,
                                "evaluation_mixeval_id" => 5,
                                "question_number" => 2,
                                "question_comment" => null,
                                "selected_lom" => 4,
                                "grade" => 0.80,
                                "comment_release" => 0,
                                "record_status" => "A",
                                "creator_id" => 5,
                                "created" => "2012-07-13 15:19:26",
                                "updater_id" => 5,
                                "modified" => "2012-07-13 15:19:26",
                                "creator" => "Ed Student",
                                "updater" => "Ed Student",
                            ),
                            2 => Array(
                                "id" => 27,
                                "evaluation_mixeval_id" => 5,
                                "question_number" => 3,
                                "question_comment" => null,
                                "selected_lom" => 4,
                                "grade" => 0.80,
                                "comment_release" => 0,
                                "record_status" => "A",
                                "creator_id" => 5,
                                "created" => "2012-07-13 15:19:26",
                                "updater_id" => 5,
                                "modified" => "2012-07-13 15:19:26",
                                "creator" => "Ed Student",
                                "updater" => "Ed Student",
                            ),
                            3 => Array(
                                "id" => 28,
                                "evaluation_mixeval_id" => 5,
                                "question_number" => 4,
                                "question_comment" => "Yes",
                                "selected_lom" => 0,
                                "grade" => 0.00,
                                "comment_release" => 0,
                                "record_status" => "A",
                                "creator_id" => 5,
                                "created" => "2012-07-13 15:19:26",
                                "updater_id" => 5,
                                "modified" => "2012-07-13 15:19:26",
                                "creator" => "Ed Student",
                                "updater" => "Ed Student",
                            ),
                            4 => Array(
                                "id" => 29,
                                "evaluation_mixeval_id" => 5,
                                "question_number" => 5,
                                "question_comment" => "Yes",
                                "selected_lom" => 0,
                                "grade" => 0.00,
                                "comment_release" => 0,
                                "record_status" => "A",
                                "creator_id" => 5,
                                "created" => "2012-07-13 15:19:26",
                                "updater_id" => 5,
                                "modified" => "2012-07-13 15:19:26",
                                "creator" => "Ed Student",
                                "updater" => "Ed Student",
                            ),
                            5 => Array(
                                "id" => 30,
                                "evaluation_mixeval_id" => 5,
                                "question_number" => 6,
                                "question_comment" => "Yes",
                                "selected_lom" => 0,
                                "grade" => 0.00,
                                "comment_release" => 0,
                                "record_status" => "A",
                                "creator_id" => 5,
                                "created" => "2012-07-13 15:19:26",
                                "updater_id" => 5,
                                "modified" => "2012-07-13 15:19:26",
                                "creator" => "Ed Student",
                                "updater" => "Ed Student",
                            )
                        ),
                    )
                ),
            )
        );
        return $expected;
    }

    function setUpMixevalResultDetailReview()
    {
        $year = date("Y") + 1;
        $expected = Array (
            5 => Array (
                0 => Array (
                    "EvaluationMixeval" => Array (
                        "id" => 5,
                        "evaluator" => 5,
                        "evaluatee" => 6,
                        "score" => 2.40,
                        "comment_release" => 0,
                        "grade_release" => 0,
                        "grp_event_id" => 5,
                        "event_id" => 3,
                        "record_status" => "A",
                        "creator_id" => 5,
                        "created" => "2012-07-13 15:19:26",
                        "updater_id" => 5,
                        "modified" => "2012-07-13 15:19:26",
                        "creator" => "Ed Student",
                        "updater" => "Ed Student",
                        "details" => Array (
                            0 => Array (
                                "EvaluationMixevalDetail" => Array (
                                    "id" => 25,
                                    "evaluation_mixeval_id" => 5,
                                    "question_number" => 1,
                                    "question_comment" => null,
                                    "selected_lom" => 4,
                                    "grade" => 0.80,
                                    "comment_release" => 0,
                                    "record_status" => "A",
                                    "creator_id" => 5,
                                    "created" => "2012-07-13 15:19:26",
                                    "updater_id" => 5,
                                    "modified" => "2012-07-13 15:19:26",
                                    "creator" => "Ed Student",
                                    "updater" => "Ed Student",
                                )
                            ),
                            1 => Array (
                                "EvaluationMixevalDetail" => Array (
                                    "id" => 26,
                                    "evaluation_mixeval_id" => 5,
                                    "question_number" => 2,
                                    "question_comment" => null,
                                    "selected_lom" => 4,
                                    "grade" => 0.80,
                                    "comment_release" => 0,
                                    "record_status" => "A",
                                    "creator_id" => 5,
                                    "created" => "2012-07-13 15:19:26",
                                    "updater_id" => 5,
                                    "modified" => "2012-07-13 15:19:26",
                                    "creator" => "Ed Student",
                                    "updater" => "Ed Student",
                                )
                            ),
                            2 => Array (
                                "EvaluationMixevalDetail" => Array (
                                    "id" => 27,
                                    "evaluation_mixeval_id" => 5,
                                    "question_number" => 3,
                                    "question_comment" => null,
                                    "selected_lom" => 4,
                                    "grade" => 0.80,
                                    "comment_release" => 0,
                                    "record_status" => "A",
                                    "creator_id" => 5,
                                    "created" => "2012-07-13 15:19:26",
                                    "updater_id" => 5,
                                    "modified" => "2012-07-13 15:19:26",
                                    "creator" => "Ed Student",
                                    "updater" => "Ed Student",
                                )
                            ),
                            3 => Array (
                                "EvaluationMixevalDetail" => Array (
                                    "id" => 28,
                                    "evaluation_mixeval_id" => 5,
                                    "question_number" => 4,
                                    "question_comment" => "Yes",
                                    "selected_lom" => 0,
                                    "grade" => 0.00,
                                    "comment_release" => 0,
                                    "record_status" => "A",
                                    "creator_id" => 5,
                                    "created" => "2012-07-13 15:19:26",
                                    "updater_id" => 5,
                                    "modified" => "2012-07-13 15:19:26",
                                    "creator" => "Ed Student",
                                    "updater" => "Ed Student",
                                )
                            ),
                            4 => Array (
                                "EvaluationMixevalDetail" => Array (
                                    "id" => 29,
                                    "evaluation_mixeval_id" => 5,
                                    "question_number" => 5,
                                    "question_comment" => "Yes",
                                    "selected_lom" => 0,
                                    "grade" => 0.00,
                                    "comment_release" => 0,
                                    "record_status" => "A",
                                    "creator_id" => 5,
                                    "created" => "2012-07-13 15:19:26",
                                    "updater_id" => 5,
                                    "modified" => "2012-07-13 15:19:26",
                                    "creator" => "Ed Student",
                                    "updater" => "Ed Student",
                                )
                            ),
                            5 => Array (
                                "EvaluationMixevalDetail" => Array (
                                    "id" => 30,
                                    "evaluation_mixeval_id" => 5,
                                    "question_number" => 6,
                                    "question_comment" => "Yes",
                                    "selected_lom" => 0,
                                    "grade" => 0.00,
                                    "comment_release" => 0,
                                    "record_status" => "A",
                                    "creator_id" => 5,
                                    "created" => "2012-07-13 15:19:26",
                                    "updater_id" => 5,
                                    "modified" => "2012-07-13 15:19:26",
                                    "creator" => "Ed Student",
                                    "updater" => "Ed Student",
                                )
                            )
                        )
                    ),
                    "Event" => Array (
                        "id" => 3,
                        "title" => "Project Evaluation",
                        "course_id" => 1,
                        "description" => null,
                        "event_template_type_id" => 4,
                        "template_id" => 1,
                        "self_eval" => 0,
                        "com_req" => 0,
                        "auto_release" => 0,
                        "enable_details" => 1,
                        "due_date" => $year."-07-02 09:00:28",
                        "release_date_begin" => "2011-06-07 09:00:35",
                        "release_date_end" => "2023-07-09 09:00:39",
                        "result_release_date_begin" => "2023-07-04 09:00:28",
                        "result_release_date_end" => "2024-07-12 09:00:28",
                        "record_status" => "A",
                        "creator_id" => 1,
                        "created" => "2006-06-21 08:53:14",
                        "updater_id" => 1,
                        "modified" => "2006-06-21 09:07:26",
                        "canvas_assignment_id" => null,
                        "creator" => "Super Admin",
                        "updater" => "Super Admin",
                        "response_count" => 2,
                        "to_review_count" => 0,
                        "student_count" => 8,
                        "group_count" => 2,
                        "completed_count" => 2,
                        "due_in" => null,
                        "is_released" => 1,
                        "is_result_released" => null,
                        "is_ended" => null,
                    ),
                    "EvaluationMixevalDetail" => Array (
                        0 => Array (
                            "id" => 25,
                            "evaluation_mixeval_id" => 5,
                            "question_number" => 1,
                            "question_comment" => null,
                            "selected_lom" => 4,
                            "grade" => 0.80,
                            "comment_release" => 0,
                            "record_status" => "A",
                            "creator_id" => 5,
                            "created" => "2012-07-13 15:19:26",
                            "updater_id" => 5,
                            "modified" => "2012-07-13 15:19:26",
                            "creator" => "Ed Student",
                            "updater" => "Ed Student",
                        ),
                        1 => Array (
                            "id" => 26,
                            "evaluation_mixeval_id" => 5,
                            "question_number" => 2,
                            "question_comment" => null,
                            "selected_lom" => 4,
                            "grade" => 0.80,
                            "comment_release" => 0,
                            "record_status" => "A",
                            "creator_id" => 5,
                            "created" => "2012-07-13 15:19:26",
                            "updater_id" => 5,
                            "modified" => "2012-07-13 15:19:26",
                            "creator" => "Ed Student",
                            "updater" => "Ed Student",
                        ),
                        2 => Array (
                            "id" => 27,
                            "evaluation_mixeval_id" => 5,
                            "question_number" => 3,
                            "question_comment" => null,
                            "selected_lom" => 4,
                            "grade" => 0.80,
                            "comment_release" => 0,
                            "record_status" => "A",
                            "creator_id" => 5,
                            "created" => "2012-07-13 15:19:26",
                            "updater_id" => 5,
                            "modified" => "2012-07-13 15:19:26",
                            "creator" => "Ed Student",
                            "updater" => "Ed Student",
                        ),
                        3 => Array (
                            "id" => 28,
                            "evaluation_mixeval_id" => 5,
                            "question_number" => 4,
                            "question_comment" => "Yes",
                            "selected_lom" => 0,
                            "grade" => 0.00,
                            "comment_release" => 0,
                            "record_status" => "A",
                            "creator_id" => 5,
                            "created" => "2012-07-13 15:19:26",
                            "updater_id" => 5,
                            "modified" => "2012-07-13 15:19:26",
                            "creator" => "Ed Student",
                            "updater" => "Ed Student",
                        ),
                        4 => Array (
                            "id" => 29,
                            "evaluation_mixeval_id" => 5,
                            "question_number" => 5,
                            "question_comment" => "Yes",
                            "selected_lom" => 0,
                            "grade" => 0.00,
                            "comment_release" => 0,
                            "record_status" => "A",
                            "creator_id" => 5,
                            "created" => "2012-07-13 15:19:26",
                            "updater_id" => 5,
                            "modified" => "2012-07-13 15:19:26",
                            "creator" => "Ed Student",
                            "updater" => "Ed Student",
                        ),
                        5 => Array (
                            "id" => 30,
                            "evaluation_mixeval_id" => 5,
                            "question_number" => 6,
                            "question_comment" => "Yes",
                            "selected_lom" => 0,
                            "grade" => 0.00,
                            "comment_release" => 0,
                            "record_status" => "A",
                            "creator_id" => 5,
                            "created" => "2012-07-13 15:19:26",
                            "updater_id" => 5,
                            "modified" => "2012-07-13 15:19:26",
                            "creator" => "Ed Student",
                            "updater" => "Ed Student",
                        )
                    )
                )
            )
        );
        return $expected;
    }

    function setUpSurveyTestData()
    {
        $expected = Array (
            0 => Array (
                "id" => 3,
                "prompt" => "Testing out MC",
                "type" => "M",
                "master" => "no",
                "SurveyQuestion" => Array (
                    "id" => 3,
                    "survey_id" => 2,
                    "number" => 1,
                    "question_id" => 3,
                ),
                "Response" => Array (
                    0 => Array (
                        "id" => 7,
                        "question_id" => 3,
                        "response" => "A",
                        "count" => 0,
                    ),
                    1 => Array (
                        "id" => 8,
                        "question_id" => 3,
                        "response" => "B",
                        "count" => 1,
                    ),
                    2 => Array (
                        "id" => 9,
                        "question_id" => 3,
                        "response" => "C",
                        "count" => 0,
                    ),
                    3 => Array (
                        "id" => 10,
                        "question_id" => 3,
                        "response" => "D",
                        "count" => 0,
                    )
                ),
                "total_response" => 1,
            ),
            1 => Array (
                "id" => 4,
                "prompt" => "Testing out checkboxes",
                "type" => "C",
                "master" => "no",
                "SurveyQuestion" => Array (
                    "id" => 4,
                    "survey_id" => 2,
                    "number" => 2,
                    "question_id" => 4,
                ),
                "Response" => Array (
                    0 => Array (
                        "id" => 11,
                        "question_id" => 4,
                        "response" => "choose me",
                        "count" => 1,
                    ),
                    1 => Array (
                        "id" => 12,
                        "question_id" => 4,
                        "response" => "no, me",
                        "count" => 1,
                    ),
                    2 => Array (
                        "id" => 13,
                        "question_id" => 4,
                        "response" => "me please",
                        "count" => 0,
                    )
                ),
                "total_response" => 2,
            ),
            2 => Array (
                "id" => 5,
                "prompt" => "Testing out single line answers",
                "type" => "S",
                "master" => "no",
                "SurveyQuestion" => Array (
                    "id" => 5,
                    "survey_id" => 2,
                    "number" => 3,
                    "question_id" => 5,
                ),
                "Response" => Array (
                    1 => Array (
                        "response_text" => "single line rah rah",
                        "user_name" => "Edna Student",
                    )
                ),
                "Responses" => Array (
                )
            ),
            3 => Array (
                "id" => 6,
                "prompt" => "Testing out multi-line long answers",
                "type" => "L",
                "master" => "no",
                "SurveyQuestion" => Array (
                    "id" => 6,
                    "survey_id" => 2,
                    "number" => 4,
                    "question_id" => 6,
                ),
                "Response" => Array (
                    1 => Array (
                        "response_text" => "long answer what what",
                        "user_name" => "Edna Student",
                    )
                ),
                "Responses" => Array (
                )
            )
        );
        return $expected;
    }

    function setUpSurveyBlankData()
    {
        $expected = Array (
            0 => Array (
                "id" => 3,
                "prompt" => "Testing out MC",
                "type" => "M",
                "master" => "no",
                "SurveyQuestion" => Array (
                    "id" => 3,
                    "survey_id" => 2,
                    "number" => 1,
                    "question_id" => 3,
                ),
                "Response" => Array (
                    0 => Array (
                        "id" => 7,
                        "question_id" => 3,
                        "response" => "A",
                        "count" => 0,
                    ),
                    1 => Array (
                        "id" => 8,
                        "question_id" => 3,
                        "response" => "B",
                        "count" => 0,
                    ),
                    2 => Array (
                        "id" => 9,
                        "question_id" => 3,
                        "response" => "C",
                        "count" => 0,
                    ),
                    3 => Array (
                        "id" => 10,
                        "question_id" => 3,
                        "response" => "D",
                        "count" => 0,
                    )
                ),
                "total_response" => 0,
            ),
            1 => Array (
                "id" => 4,
                "prompt" => "Testing out checkboxes",
                "type" => "C",
                "master" => "no",
                "SurveyQuestion" => Array (
                    "id" => 4,
                    "survey_id" => 2,
                    "number" => 2,
                    "question_id" => 4,
                ),
                "Response" => Array (
                    0 => Array (
                        "id" => 11,
                        "question_id" => 4,
                        "response" => "choose me",
                        "count" => 0,
                    ),
                    1 => Array (
                        "id" => 12,
                        "question_id" => 4,
                        "response" => "no, me",
                        "count" => 0,
                    ),
                    2 => Array (
                        "id" => 13,
                        "question_id" => 4,
                        "response" => "me please",
                        "count" => 0,
                    )
                ),
                "total_response" => 0,
            ),
            2 => Array (
                "id" => 5,
                "prompt" => "Testing out single line answers",
                "type" => "S",
                "master" => "no",
                "SurveyQuestion" => Array (
                    "id" => 5,
                    "survey_id" => 2,
                    "number" => 3,
                    "question_id" => 5,
                ),
                "Response" => Array (
                ),
                "Responses" => Array (
                )
            ),
            3 => Array (
                "id" => 6,
                "prompt" => "Testing out multi-line long answers",
                "type" => "L",
                "master" => "no",
                "SurveyQuestion" => Array (
                    "id" => 6,
                    "survey_id" => 2,
                    "number" => 4,
                    "question_id" => 6,
                ),
                "Response" => Array (
                ),
                "Responses" => Array (
                )
            )
        );
        return $expected;
    }
}
