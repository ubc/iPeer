<?php
App::import('Model', 'Mixeval');

class MixevalTestCase extends CakeTestCase
{
    public $name = 'Mixeval';
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
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
    public $Mixeval = null;

    function startCase()
    {
        echo "Start Mixeval model test.\n";
        $this->Mixeval = ClassRegistry::init('Mixeval');
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

    function testMixevalInstance()
    {
        $this->assertTrue(is_a($this->Mixeval, 'Mixeval'));
    }

    function testCompileViewData()
    {
        // Set up test input
        $input = $this->setUpTestInput();

        // Set up test data
        $result = $this->Mixeval->compileViewData($input);
    //    var_dump($result);

        // Assert that old input still exists in the result
        $this->assertEqual($input['Mixeval'], $result['Mixeval']);
        $this->assertEqual($input['Event'], $result['Event']);
        $this->assertEqual($input['Question'], $result['Question']);
        // Assert that a new array is merged with the old input
        $this->assertFalse(isset($input['questions']));
        $this->assertNotNull($result['questions']);
        $this->assertEqual(count($result['questions']), 6);
    }

    function setUpTestInput()
    {
        $tmp = array(
            'Mixeval' => array(
                'id' => 1,
                'name' => 'mixed2',
                'zero_mark' => 0,
                'scale_max' => 0,
                'availability' => 0,
                'creator_id' => 1,
                'created' => '2011-06-06 10:53:03',
                'updater_id' => 1,
                'modified' => '2011-06-06 10:53:03',
                'creator' => null,
                'updater' => null,
                'event_count' => 1,
                'lickert_question_max' => 1,
                'prefill_question_max' => 2,
                'total_question' => 3,
                'total_marks' => 1
            ),

            'Event' => array(
                '0' => array(
                    'id' => 56,
                    'title' => 'mixed2',
                    'course_id' => 1,
                    'description' => null,
                    'event_template_type_id' => 4,
                    'template_id' => 5,
                    'self_eval' => 0,
                    'com_req' => 0,
                    'due_date' => '2011-06-10 10:53:26',
                    'release_date_begin' => '2011-06-01 10:53:29',
                    'release_date_end' => '2011-06-19 10:53:33',
                    'record_status' => 'A',
                    'creator_id' => 1,
                    'created' => '2011-06-06 10:53:39',
                    'updater_id' => null,
                    'modified' => '2011-06-06 10:53:39',
                    'creator' => null,
                    'updater' => null,
                    'response_count' => 1,
                    'to_review_count' => 0,
                    'student_count' => 0,
                    'completed_count' => 1
                )
            ),

            'Question' => array(
                '0' => array(
                    'id' => 21,
                    'mixeval_id' => 5,
                    'question_num' => 0,
                    'title' => 'l1',
                    'instructions' => null,
                    'mixeval_question_type_id' => '1',
                    'required' => 1,
                    'self_eval' => 0,
                    'multiplier' => 1,
                    'scale_level' => 0,
                ),

                '1' => array(
                    'id' => 22,
                    'mixeval_id' => 5,
                    'question_num' => 1,
                    'title' => 'c1',
                    'instructions' => 'c1i1',
                    'mixeval_question_type_id' => '2',
                    'required' => 1,
                    'self_eval' => 0,
                    'multiplier' => 0,
                    'scale_level' => 0,
                ),

                '2' => array(
                    'id' => 23,
                    'mixeval_id' => 5,
                    'question_num' => 2,
                    'title' => 'c2',
                    'instructions' => 'c2i1',
                    'mixeval_question_type_id' => '2',
                    'required' => 1,
                    'self_eval' => 0,
                    'multiplier' => 0,
                    'scale_level' => 0,
                )
            )
        );
        return $tmp;
    }
}
