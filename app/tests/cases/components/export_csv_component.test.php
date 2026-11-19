<?php
App::import('Component', 'ExportBaseNew');
App::import('Component', 'ExportCsv');
App::import('Component', 'Penalty');

class ExportCsvComponentTestCase extends CakeTestCase
{
    var $fixtures = array();

    // A new component for each test, so no test depends on what another one
    // set on it.
    function startTest($method)
    {
        $this->ExportCsv = new ExportCsvComponent();
        // responseModelName is normally set by createCsv(); set it manually so
        // buildScoreTableByEvaluatee works in isolation.
        $this->ExportCsv->responseModelName = 'EvaluationMixeval';
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    function _mixedEvent()
    {
        return array(
            'Event' => array(
                'id'                      => 1,
                'event_template_type_id'  => 4,
                'self_eval'               => 0,
                'title'                   => 'Test Mixed Evaluation',
                'template_id'             => 1,
                'due_date'                => null,
                'release_date_end'        => null,
            ),
            'Course'  => array('course' => 'Test Course'),
            'Penalty' => array(),
            'Question' => array(
                array(
                    'mixeval_question_type_id' => 1,
                    'self_eval'    => 0,
                    'question_num' => 1,
                    'required'     => 1,
                    'multiplier'   => 5,
                    'title'        => 'Grade Question',
                ),
                array(
                    'mixeval_question_type_id' => 2,
                    'self_eval'    => 0,
                    'question_num' => 2,
                    'required'     => 0,
                    'multiplier'   => 0,
                    'title'        => 'Comment Question',
                ),
            ),
        );
    }

    function _baseParams()
    {
        return array('include' => array(
            'course'            => true,
            'eval_event_names'  => true,
            'eval_event_type'   => true,
            'group_names'       => true,
            'student_name'      => true,
            'student_id'        => true,
            'comments'          => true,
            'grade_tables'      => true,
            'final_marks'       => true,
            'question_title'    => false,
        ));
    }

    function _member($id, $name, $studentNo)
    {
        return [
            'id'           => $id,
            'full_name'    => $name,
            'student_no'   => $studentNo,
            'GroupsMember' => [],
            'Role'         => ['name' => 'student'],
        ];
    }

    function _rubricEvent()
    {
        return [
            'Event' => [
                'id'                      => 2,
                'event_template_type_id'  => 2,
                'self_eval'               => 0,
                'title'                   => 'Test Rubric',
                'template_id'             => 1,
                'due_date'                => '2000-01-01 00:00:00',
                'release_date_end'        => '2000-02-01 00:00:00',
            ],
            'Course'   => ['course' => 'Test Course'],
            'Penalty'  => [],
            'Rubric'   => ['id' => 1],
            'Question' => [
                ['criteria_num' => 1, 'multiplier' => 5],
                ['criteria_num' => 2, 'multiplier' => 5],
                ['criteria_num' => 3, 'multiplier' => 5],
            ],
        ];
    }

    // An evaluatee and an evaluator in one group.
    function _pair()
    {
        $evaluatee = $this->_member(10, 'Jean-Luc Picard', '55555555');
        $group = [
            'Group'  => ['id' => 1, 'group_name' => 'NextGen'],
            'Member' => [$evaluatee, $this->_member(11, 'Worf', '66666666')],
        ];
        return [$evaluatee, $group];
    }

    // Each row of the grid has as many cells as the header of the section.
    function _assertRowsMatchHeader($grid, $params, $event, $peerEval, $message)
    {
        $headerWidth = count($this->ExportCsv->generateHeader($params, $event, $peerEval));
        $this->assertFalse(empty($grid), "$message: the grid has rows");
        foreach ($grid as $i => $row) {
            $this->assertEqual(count($row), $headerWidth, "$message: row $i");
        }
    }

    // -------------------------------------------------------------------------
    // Row width: rows without answers are as wide as the header
    // -------------------------------------------------------------------------

    function testUnansweredRubricRowMatchesHeaderWidth()
    {
        $event  = $this->_rubricEvent();
        $params = $this->_baseParams();
        $params['include']['export_all'] = true;
        [$evaluatee, $group] = $this->_pair();

        $grid = $this->ExportCsv->buildScoreTableByEvaluatee(
            $params, $group, $evaluatee, $event, [], [], 1
        );

        // 8 lead columns, 3 criterion comments, the comment, 3 criteria and
        // the 3 totals.
        $this->assertEqual(count($this->ExportCsv->generateHeader($params, $event)), 18);
        $this->_assertRowsMatchHeader($grid, $params, $event, 1, 'unanswered rubric row');
    }

    function testUnansweredRowMatchesHeaderWidthWithColumnsUnchecked()
    {
        // The form posts '0' for an unchecked box.
        $event  = $this->_mixedEvent();
        $params = $this->_baseParams();
        $params['include']['export_all']   = '1';
        $params['include']['student_id']   = '0';
        $params['include']['comments']     = '0';
        $params['include']['grade_tables'] = '0';
        $params['include']['final_marks']  = '0';
        [$evaluatee, $group] = $this->_pair();

        $grid = $this->ExportCsv->buildScoreTableByEvaluatee(
            $params, $group, $evaluatee, $event, [], [], 1
        );

        // 6 lead columns, the 2 questions, the raw score and the penalty.
        $this->assertEqual(count($this->ExportCsv->generateHeader($params, $event)), 10);
        $this->_assertRowsMatchHeader($grid, $params, $event, 1, 'unanswered row, columns unchecked');
    }

    // -------------------------------------------------------------------------
    // Answered rubric rows: one cell for each criterion, in criterion order
    // -------------------------------------------------------------------------

    function _answeredRubricRow($params)
    {
        $event = $this->_rubricEvent();
        [$evaluatee, $group] = $this->_pair();
        // Criterion 2 has no answer, and the details are not in order.
        $responses = [10 => [11 => [
            'EvaluationRubric'       => ['id' => 1, 'score' => 8, 'comment' => 'general'],
            'EvaluationRubricDetail' => [
                ['criteria_number' => 3, 'criteria_comment' => 'third', 'grade' => 3],
                ['criteria_number' => 1, 'criteria_comment' => 'first', 'grade' => 5],
            ],
        ]]];
        $this->ExportCsv->responseModelName = 'EvaluationRubric';
        $this->ExportCsv->Penalty = new PenaltyComponent();
        return $this->ExportCsv->buildScoreTableByEvaluatee(
            $params, $group, $evaluatee, $event, $responses, [], 1
        );
    }

    function testAnsweredRubricRowPutsEachCriterionUnderItsColumn()
    {
        $params = $this->_baseParams();
        $grid = $this->_answeredRubricRow($params);

        $this->_assertRowsMatchHeader($grid, $params, $this->_rubricEvent(), 1, 'answered rubric row');
        // After the 8 lead columns: the criterion comments, the comment, the
        // grades, then the totals.
        $this->assertIdentical(array_slice($grid[0], 8, 7), ['first', '', 'third', 'general', 5, '', 3]);
    }

    function testRubricCriterionCommentsFollowIncludeComments()
    {
        $params = $this->_baseParams();
        $params['include']['comments'] = '0';
        $header = $this->ExportCsv->generateHeader($params, $this->_rubricEvent());
        $grid = $this->_answeredRubricRow($params);

        $this->assertFalse(in_array('Q1 Comment', $header));
        $this->_assertRowsMatchHeader($grid, $params, $this->_rubricEvent(), 1, 'rubric row without comments');
        $this->assertIdentical(array_slice($grid[0], 8, 3), [5, '', 3]);
    }

    // -------------------------------------------------------------------------
    // Self-evaluation section
    // -------------------------------------------------------------------------

    function testSelfEvaluationHeaderListsTheSelfQuestions()
    {
        $event = $this->_mixedEvent();
        $event['Event']['self_eval'] = 1;
        $event['Question'][] = [
            'mixeval_question_type_id' => 1,
            'self_eval'    => 1,
            'question_num' => 3,
            'required'     => 1,
            'multiplier'   => 8,
            'title'        => 'Self Grade',
        ];
        $params = $this->_baseParams();
        $params['include']['question_title'] = true;
        $evaluatee = $this->_member(10, 'Jean-Luc Picard', '55555555');
        $group = [
            'Group'  => ['id' => 1, 'group_name' => 'NextGen'],
            'Member' => [$evaluatee],
        ];
        $responses = [10 => [10 => [
            'EvaluationMixeval'       => ['id' => 1, 'score' => 0],
            'EvaluationMixevalDetail' => [
                ['question_number' => 3, 'grade' => 6, 'question_comment' => null],
            ],
        ]]];

        $header = $this->ExportCsv->generateHeader($params, $event, 0);
        $grid = $this->ExportCsv->buildScoreTableByEvaluatee(
            $params, $group, $evaluatee, $event, $responses, [], 0
        );

        $this->_assertRowsMatchHeader($grid, $params, $event, 0, 'self-evaluation row');
        // The self question with its title, not the peer questions.
        $this->assertIdentical(array_slice($header, -1), ['Q1 ( /8) Self Grade']);
        $this->assertIdentical(array_slice($grid[0], -1), [6]);
    }

    // -------------------------------------------------------------------------
    // buildScoreTableByEvaluatee – missing_required_question path
    // -------------------------------------------------------------------------

    function testMissingRequiredQuestionRowIsPaddedToHeaderWidth()
    {
        $event  = $this->_mixedEvent();
        $params = $this->_baseParams();

        $evaluateeId = 10;
        $evaluatorId = 11;

        $evaluatee = array(
            'id'           => $evaluateeId,
            'full_name'    => 'Jean-Luc Picard',
            'student_no'   => '55555555',
            'GroupsMember' => array(),
            'Role'         => array('name' => 'student'),
        );
        $evaluator = array(
            'id'           => $evaluatorId,
            'full_name'    => 'Worf',
            'student_no'   => '66666666',
            'GroupsMember' => array(),
            'Role'         => array('name' => 'student'),
        );

        $group = array(
            'Group'  => array('id' => 1, 'group_name' => 'NextGen'),
            'Member' => array($evaluatee, $evaluator),
        );

        $responses = array(
            $evaluateeId => array(
                $evaluatorId => array(
                    'EvaluationMixeval' => array(
                        'id'           => 1,
                        'evaluatee'    => $evaluateeId,
                        'evaluator'    => $evaluatorId,
                        'grp_event_id' => 1,
                        'score'        => 0,
                    ),
                    'EvaluationMixevalDetail' => array(
                        // Q1 is required but absent; Q2 present → missing_required_question
                        array('question_number' => 2, 'grade' => null, 'question_comment' => 'looks fine'),
                    ),
                ),
            ),
        );

        $grid = $this->ExportCsv->buildScoreTableByEvaluatee(
            $params, $group, $evaluatee, $event, $responses, array(), 1
        );

        $headerWidth = count($this->ExportCsv->generateHeader($params, $event));

        $this->assertFalse(empty($grid),
            "Grid must contain the incomplete row when a partial response exists");
        $this->assertEqual(count($grid[0]), $headerWidth,
            "Row from missing_required_question path must be padded to header width");
    }

    function testExportAllEmptyRowMatchesHeaderWidth()
    {
        $event  = $this->_mixedEvent();
        $params = $this->_baseParams();
        $params['include']['export_all']     = true;
        $params['include']['question_title'] = true;

        $evaluatee = array(
            'id'           => 10,
            'full_name'    => 'Jean-Luc Picard',
            'student_no'   => '55555555',
            'GroupsMember' => array(),
            'Role'         => array('name' => 'student'),
        );
        $evaluator = array(
            'id'           => 11,
            'full_name'    => 'Worf',
            'student_no'   => '66666666',
            'GroupsMember' => array(),
            'Role'         => array('name' => 'student'),
        );

        $group = array(
            'Group'  => array('id' => 1, 'group_name' => 'NextGen'),
            'Member' => array($evaluatee, $evaluator),
        );

        $grid = $this->ExportCsv->buildScoreTableByEvaluatee(
            $params, $group, $evaluatee, $event, array(), array(), 1
        );

        $headerWidth = count($this->ExportCsv->generateHeader($params, $event));

        $this->assertFalse(empty($grid),
            "Grid must contain rows when export_all is enabled");
        foreach ($grid as $i => $row) {
            $this->assertEqual(count($row), $headerWidth,
                "export_all empty row $i must have same column count as header");
        }
    }

    // -------------------------------------------------------------------------
    // CSV output
    // -------------------------------------------------------------------------

    function testRenderCsvOutput()
    {
        // fputcsv() quotes fields with spaces, delimiters, quotes or the
        // escape character; a quote after a backslash is not doubled
        ob_start();
        $this->ExportCsv->render(array(
            array('plain', 'with space', 'comma,inside', 'quote"inside', 'back\\"slash'),
            array('', '1'),
        ));
        $out = ob_get_clean();

        $this->assertIdentical(
            "plain,\"with space\",\"comma,inside\",\"quote\"\"inside\",\"back\\\"slash\"\n,1\n",
            $out
        );
    }

    function testExportCsvOutput()
    {
        ob_start();
        try {
            $ret = $this->ExportCsv->exportCSV(array(array('a b', 'c\\"d'), array('e')), 'name');
        } finally {
            $out = ob_get_clean();
            // exportCSV() sends download headers; in the browser test runner they would
            // make the browser download the buffered report as name.csv instead of
            // showing it. Both calls are no-ops on the CLI.
            header('Content-Type: text/html; charset=UTF-8');
            header_remove('Content-Disposition');
        }

        $this->assertTrue($ret);
        $this->assertIdentical("\"a b\",\"c\\\"d\"\ne\n", $out);
    }
}
