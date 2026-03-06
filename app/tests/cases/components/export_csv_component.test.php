<?php
App::import('Component', 'ExportBaseNew');
App::import('Component', 'ExportCsv');

class ExportCsvComponentTestCase extends CakeTestCase
{
    var $fixtures = array();

    function startCase()
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

    // -------------------------------------------------------------------------
    // calcDimensionX – peer eval (peerEval=1)
    // -------------------------------------------------------------------------

    function testCalcDimensionXBaseCase()
    {
        $event  = $this->_mixedEvent();
        $params = $this->_baseParams();
        $header = $this->ExportCsv->generateHeader($params, $event);
        $dim    = $this->ExportCsv->calcDimensionX($params, $event);
        $this->assertEqual($dim, count($header),
            "calcDimensionX should equal header count without behavioral flags");
    }

    function testCalcDimensionXIgnoresExportAll()
    {
        $event  = $this->_mixedEvent();
        $params = $this->_baseParams();
        $header = $this->ExportCsv->generateHeader($params, $event);

        $params['include']['export_all'] = true;
        $dim = $this->ExportCsv->calcDimensionX($params, $event);

        $this->assertEqual($dim, count($header),
            "export_all flag must not inflate calcDimensionX");
    }

    function testCalcDimensionXIgnoresQuestionTitle()
    {
        $event  = $this->_mixedEvent();
        $params = $this->_baseParams();
        $dimWithout = $this->ExportCsv->calcDimensionX($params, $event);

        $params['include']['question_title'] = true;
        $dimWith = $this->ExportCsv->calcDimensionX($params, $event);

        $this->assertEqual($dimWith, $dimWithout,
            "question_title flag must not inflate calcDimensionX");
    }

    function testCalcDimensionXIgnoresBothBehavioralFlags()
    {
        $event  = $this->_mixedEvent();
        $params = $this->_baseParams();
        $header = $this->ExportCsv->generateHeader($params, $event);

        $params['include']['export_all']     = true;
        $params['include']['question_title'] = true;
        $dim = $this->ExportCsv->calcDimensionX($params, $event);

        $this->assertEqual($dim, count($header),
            "export_all + question_title must not inflate calcDimensionX (was +2 before fix)");
    }

    // -------------------------------------------------------------------------
    // calcDimensionX – self eval (peerEval=0)
    // -------------------------------------------------------------------------

    function testCalcDimensionXSelfEvalMatchesHeaderCount()
    {
        $event  = $this->_mixedEvent();
        $params = $this->_baseParams();

        $header = $this->ExportCsv->generateHeader($params, $event, 0);
        $dim    = $this->ExportCsv->calcDimensionX($params, $event, 0);

        $this->assertEqual($dim, count($header),
            "calcDimensionX(peerEval=0) must equal generateHeader(peerEval=0) column count");
    }

    function testCalcDimensionXPeerVsSelfEvalDifference()
    {
        $event  = $this->_mixedEvent();
        $params = $this->_baseParams();

        $peerHeader = $this->ExportCsv->generateHeader($params, $event, 1);
        $selfHeader = $this->ExportCsv->generateHeader($params, $event, 0);
        $peerDim    = $this->ExportCsv->calcDimensionX($params, $event, 1);
        $selfDim    = $this->ExportCsv->calcDimensionX($params, $event, 0);

        $this->assertEqual($peerDim - $selfDim, count($peerHeader) - count($selfHeader),
            "Difference between peer and self-eval dimensions must match header difference");
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

        $expectedWidth = $this->ExportCsv->calcDimensionX($params, $event);
        $headerWidth   = count($this->ExportCsv->generateHeader($params, $event));

        $this->assertEqual($expectedWidth, $headerWidth,
            "calcDimensionX must equal header column count");
        $this->assertFalse(empty($grid),
            "Grid must contain the incomplete row when a partial response exists");
        $this->assertEqual(count($grid[0]), $expectedWidth,
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
}
