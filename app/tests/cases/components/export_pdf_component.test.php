<?php
App::import('Component', 'ExportBaseNew');
App::import('Component', 'ExportPdf');

/**
 * Stand-in for the models that ExportPdfComponent gets from
 * ClassRegistry::init(). ClassRegistry returns a registered object when
 * its alias matches, so these tests need no database or fixtures.
 */
class ExportPdfTestModelStub
{
    var $alias;
    var $findByIdResult = array();
    var $fullNames = array();

    function __construct($alias)
    {
        $this->alias = $alias;
    }

    function formatPenaltyArray($eventId, $groupId, $names)
    {
        return array_fill_keys(array_keys($names), 0);
    }

    function findById($id)
    {
        return $this->findByIdResult;
    }

    function getFullNames($userIds)
    {
        return $this->fullNames;
    }
}

/**
 * Regression tests for the HTML escaping in ExportPdfComponent.
 *
 * TCPDF writeHTML() reads <img src> from local paths and remote URLs, so
 * user text must reach it escaped. The report types build their HTML in
 * different methods, so each method has its own test. Each test also
 * checks that the report's own markup and numbers are not changed.
 */
class ExportPdfComponentTestCase extends CakeTestCase
{
    var $fixtures = array();
    var $Pdf;

    // markup that must never reach writeHTML() as markup
    var $payload = '<img src="https://example.com/x.png">';
    var $payloadEscaped = '&lt;img src=&quot;https://example.com/x.png&quot;&gt;';

    var $stubAliases = array('Mixeval', 'Rubric', 'SimpleEvaluation', 'Group');
    var $stubs = array();

    function startTest($method)
    {
        parent::startTest($method);
        $this->Pdf = new ExportPdfComponent();

        $this->stubs = array();
        foreach ($this->stubAliases as $alias) {
            // removeObject first: addObject() does not replace an object
            // that an earlier test case registered
            ClassRegistry::removeObject($alias);
            $this->stubs[$alias] = new ExportPdfTestModelStub($alias);
            ClassRegistry::addObject($alias, $this->stubs[$alias]);
        }

        $user = new ExportPdfTestModelStub('User');
        $user->fullNames = $this->_rawNames();
        $this->Pdf->User = $user;
    }

    function endTest($method)
    {
        foreach ($this->stubAliases as $alias) {
            ClassRegistry::removeObject($alias);
        }
        parent::endTest($method);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    function _rawNames()
    {
        return array(
            1 => 'Tom & Jerry',
            2 => 'Ann "Q" O\'Neil',
            3 => 'Évé Zürich',
        );
    }

    /**
     * Names as createPDF() passes them to the _write* methods.
     */
    function _names()
    {
        return $this->Pdf->_names(array(1, 2, 3));
    }

    function _params()
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
        ));
    }

    function _event($typeId)
    {
        return array(
            'Event' => array(
                'id'                     => 1,
                'event_template_type_id' => $typeId,
                'template_id'            => 1,
                'self_eval'              => 0,
                'title'                  => 'Event <u>title</u>',
                'description'            => null,
                'due_date'               => '2026-01-15 10:00:00',
            ),
            'Course'            => array('course' => 'Course & <i>name</i>'),
            'EventTemplateType' => array('type_name' => 'MIXED'),
        );
    }

    function _mixevalQuestions()
    {
        return array(
            array('MixevalQuestion' => array(
                'question_num'             => 1,
                'mixeval_question_type_id' => 1,
                'self_eval'                => 0,
                'title'                    => 'Score <b>question</b>',
                'multiplier'               => 5,
            )),
            array('MixevalQuestion' => array(
                'question_num'             => 2,
                'mixeval_question_type_id' => 2,
                'self_eval'                => 0,
                'title'                    => 'Comment question',
                'multiplier'               => 0,
            )),
        );
    }

    function _mixevalEvaluations()
    {
        return array(
            array(
                'EvaluationMixeval' => array('evaluator' => 1, 'evaluatee' => 2, 'score' => 4),
                'EvaluationMixevalDetail' => array(
                    array('question_number' => 1, 'grade' => 4, 'question_comment' => null),
                    array('question_number' => 2, 'grade' => 0, 'question_comment' => $this->payload),
                ),
            ),
            array(
                'EvaluationMixeval' => array('evaluator' => 3, 'evaluatee' => 2, 'score' => 2),
                'EvaluationMixevalDetail' => array(
                    array('question_number' => 1, 'grade' => 2, 'question_comment' => null),
                    array('question_number' => 2, 'grade' => 0, 'question_comment' => null),
                ),
            ),
        );
    }

    function _rubricCriteria()
    {
        return array(
            array('RubricsCriteria' => array(
                'criteria_num' => 1,
                'multiplier'   => 5,
                'criteria'     => 'Team <i>work</i>',
            )),
        );
    }

    function _rubricEvaluations()
    {
        return array(
            array(
                'EvaluationRubric' => array('evaluator' => 1, 'evaluatee' => 2, 'comment' => $this->payload),
                'EvaluationRubricDetail' => array(
                    array('criteria_number' => 1, 'grade' => 4, 'criteria_comment' => $this->payload),
                ),
            ),
            array(
                'EvaluationRubric' => array('evaluator' => 3, 'evaluatee' => 2, 'comment' => null),
                'EvaluationRubricDetail' => array(
                    array('criteria_number' => 1, 'grade' => 3, 'criteria_comment' => null),
                ),
            ),
        );
    }

    function _simpleEvaluations()
    {
        return array(
            array('EvaluationSimple' => array(
                'evaluator' => 1, 'evaluatee' => 2, 'score' => 10, 'comment' => $this->payload,
            )),
            array('EvaluationSimple' => array(
                'evaluator' => 1, 'evaluatee' => 3, 'score' => 5, 'comment' => null,
            )),
        );
    }

    function _assertHas($html, $needle)
    {
        $this->assertTrue(strpos($html, $needle) !== false, 'Expected text not found: ' . $needle);
    }

    function _assertHasNot($html, $needle)
    {
        $this->assertTrue(strpos($html, $needle) === false, 'Unexpected text found: ' . $needle);
    }

    /**
     * Checks that apply to every report: no raw payload, no double escaping.
     */
    function _assertSafe($html)
    {
        $this->_assertHasNot($html, '<img');
        $this->_assertHasNot($html, '&amp;amp;');
        $this->_assertHasNot($html, '&amp;quot;');
        $this->_assertHasNot($html, '&amp;lt;');
    }

    // -------------------------------------------------------------------------
    // _h() and _names()
    // -------------------------------------------------------------------------

    function testHEscapesMarkupAndQuotes()
    {
        $this->assertIdentical($this->Pdf->_h($this->payload), $this->payloadEscaped);
        $this->assertIdentical($this->Pdf->_h('Tom & Jerry'), 'Tom &amp; Jerry');
        $this->assertIdentical($this->Pdf->_h('O\'Neil "Q"'), 'O&#039;Neil &quot;Q&quot;');
    }

    function testHAcceptsNullAndNumbers()
    {
        $this->assertIdentical($this->Pdf->_h(null), '');
        $this->assertIdentical($this->Pdf->_h(0), '0');
        $this->assertIdentical($this->Pdf->_h(4.5), '4.5');
    }

    function testHKeepsNonAsciiText()
    {
        $this->assertIdentical($this->Pdf->_h('Évé Zürich 中文'), 'Évé Zürich 中文');
    }

    function testHKeepsTextWithInvalidUtf8()
    {
        // a latin1 "é" byte must not make the whole value disappear
        $result = $this->Pdf->_h("Caf\xE9 <b>");
        $this->assertTrue(strpos($result, 'Caf') === 0, 'Text before the invalid byte is lost');
        $this->_assertHas($result, '&lt;b&gt;');
    }

    function testFileNameIsCleanedAsInTcpdf65()
    {
        $this->assertIdentical($this->Pdf->_fileName(array('file_name' => 'Week 3 results')), 'Week_3_results.pdf');
        $this->assertIdentical($this->Pdf->_fileName(array('file_name' => 'A/B "x"')), 'AB_x.pdf');
        $this->assertIdentical($this->Pdf->_fileName(array('file_name' => '')), date('m.d.y').'.pdf');
    }

    function testHShowsTypedEntitiesLiterally()
    {
        // a user who types "&amp;" sees "&amp;" in the PDF, not "&"
        $this->assertIdentical($this->Pdf->_h('&amp;'), '&amp;amp;');
    }

    function testNamesAreEscapedOnceAndKeepUserIdKeys()
    {
        $names = $this->_names();
        $this->assertIdentical(array_keys($names), array(1, 2, 3));
        $this->assertIdentical($names[1], 'Tom &amp; Jerry');
        $this->assertIdentical($names[2], 'Ann &quot;Q&quot; O&#039;Neil');
        $this->assertIdentical($names[3], 'Évé Zürich');
    }

    // -------------------------------------------------------------------------
    // Title and event details (all report types)
    // -------------------------------------------------------------------------

    function testTitleEscapesCourseAndEventNames()
    {
        $html = $this->Pdf->_writeTitle($this->_event(1), $this->_params());

        $this->_assertSafe($html);
        $this->assertIdentical($html,
            '<h2>Evaluation Event Detail: Course &amp; &lt;i&gt;name&lt;/i&gt;'
            . ' - Event &lt;u&gt;title&lt;/u&gt;</h2>');
    }

    function testEvalDetailsEscapesEventAndGroupText()
    {
        $this->stubs['Group']->findByIdResult = array(
            'Group' => array('group_name' => 'Group <script>x</script> & co'),
        );
        $event = $this->_event(4);
        $event['Event']['description'] = 'Read ' . $this->payload;

        $html = $this->Pdf->_writeEvalDetails($event, 1, $this->_params());

        $this->_assertSafe($html);
        $this->_assertHasNot($html, '<script>');
        $this->_assertHas($html, 'Group &lt;script&gt;x&lt;/script&gt; &amp; co');
        $this->_assertHas($html, 'Event &lt;u&gt;title&lt;/u&gt;');
        $this->_assertHas($html, 'Read ' . $this->payloadEscaped);
        $this->_assertHas($html, 'Mixed');
        // the report's own markup stays
        $this->_assertHas($html, '<p><b>Group: </b>');
        $this->_assertHas($html, '<b>Due Date: </b>');
    }

    function testEvalDetailsAcceptsNullDescription()
    {
        $this->stubs['Group']->findByIdResult = array('Group' => array('group_name' => 'G1'));

        $html = $this->Pdf->_writeEvalDetails($this->_event(1), 1, $this->_params());

        $this->_assertHas($html, '<b>Description: </b></p>');
    }

    // -------------------------------------------------------------------------
    // Simple evaluation
    // -------------------------------------------------------------------------

    function testCommentsEscapesCommentsAndNames()
    {
        $results = $this->Pdf->_writeComments($this->_simpleEvaluations(), $this->_names());
        $html = implode('', $results);

        $this->_assertSafe($html);
        $this->_assertHas($html, '<br><b>Evaluator: Tom &amp; Jerry</b><br>');
        $this->_assertHas($html, 'Ann &quot;Q&quot; O&#039;Neil: ' . $this->payloadEscaped . '<br>');
        // null comment gives an empty comment, not an error
        $this->_assertHas($html, 'Évé Zürich: <br>');
    }

    function testScoresTableEscapesNamesAndKeepsNumbers()
    {
        $html = $this->Pdf->_writeScoresTbl(
            $this->_event(1), 1, 1, $this->_params(),
            $this->_simpleEvaluations(), array(), $this->_names()
        );

        $this->_assertSafe($html);
        $this->_assertHas($html, '<table border="1" align="center">');
        $this->_assertHas($html, '<th>Ann &quot;Q&quot; O&#039;Neil</th>');
        $this->_assertHas($html, '<tr><td>Tom &amp; Jerry</td>');
        $this->_assertHas($html, '<td>10.00</td>');
        $this->_assertHas($html, '<td>5.00</td>');
    }

    // -------------------------------------------------------------------------
    // Rubric
    // -------------------------------------------------------------------------

    function testRubricResultsEscapesCommentsCriteriaAndNames()
    {
        $results = $this->Pdf->_writeRubricEvalResults(
            $this->_event(2), 1, 1, $this->_params(),
            $this->_rubricEvaluations(), array(), $this->_names(), $this->_rubricCriteria()
        );
        $html = implode('', $results);

        $this->_assertSafe($html);
        $this->_assertHas($html, '(1) Team &lt;i&gt;work&lt;/i&gt;</th>');
        $this->_assertHas($html, '<b>Evaluatee: Ann &quot;Q&quot; O&#039;Neil</b>');
        $this->_assertHas($html, '<tr><td rowspan="2">Tom &amp; Jerry</td>');
        // criteria comment and general comment
        $this->_assertHas($html, '<b>Comment</b>: ' . $this->payloadEscaped . '</td>');
        $this->_assertHas($html, '<b>General Comment:</b><br>' . $this->payloadEscaped . '</td>');
        // null comments give empty text
        $this->_assertHas($html, '<b>Comment</b>: </td>');
        $this->_assertHas($html, '<b>General Comment:</b><br></td>');
        // grades are not changed
        $this->_assertHas($html, '<b>Grade</b>: 4 / 5');
        $this->_assertHas($html, '<b>Grade</b>: 3 / 5');
    }

    function testRubricTableEscapesNamesAndKeepsNumbers()
    {
        $html = $this->Pdf->_writeRubricResultsTbl(
            $this->_event(2), 1, 1, $this->_params(),
            $this->_rubricEvaluations(), array(), $this->_names(), $this->_rubricCriteria()
        );

        $this->_assertSafe($html);
        $this->_assertHas($html, '<table border="1" align="center">');
        $this->_assertHas($html, '<th>1 (/5.0 )</th>');
        $this->_assertHas($html, '<tr><td>Ann &quot;Q&quot; O&#039;Neil</td>');
        $this->_assertHas($html, '<td>3.50</td>');
    }

    // -------------------------------------------------------------------------
    // Mixed evaluation
    // -------------------------------------------------------------------------

    function testMixedResultsEscapesAnswersTitlesAndNames()
    {
        $this->stubs['Mixeval']->findByIdResult = array('Mixeval' => array(
            'total_marks' => 5, 'peer_question' => 1, 'self_eval' => 0,
        ));

        $results = $this->Pdf->_writeMixEvalResults(
            $this->_event(4), 1, 1, $this->_params(),
            $this->_mixevalEvaluations(), array(), $this->_names(), $this->_mixevalQuestions()
        );
        $html = implode('', $results);

        $this->_assertSafe($html);
        $this->_assertHas($html, '<u><b>Evaluatee: </b>Ann &quot;Q&quot; O&#039;Neil</u>');
        $this->_assertHas($html, '<h4>1. Score &lt;b&gt;question&lt;/b&gt;</h4>');
        $this->_assertHas($html, '<li><b>Tom &amp; Jerry: </b>' . $this->payloadEscaped . '</li>');
        // null text answer gives empty text
        $this->_assertHas($html, '<li><b>Évé Zürich: </b></li>');
        // numeric answers are not changed
        $this->_assertHas($html, '<li><b>Tom &amp; Jerry: </b>4.00 / 5</li>');
        $this->_assertHas($html, '<li><b>Évé Zürich: </b>2.00 / 5</li>');
    }

    function testMixedTableEscapesNamesAndKeepsNumbers()
    {
        $html = $this->Pdf->_writeMixResultsTbl(
            $this->_event(4), 1, 1, $this->_params(),
            $this->_mixevalEvaluations(), array(), $this->_names(), $this->_mixevalQuestions()
        );

        $this->_assertSafe($html);
        $this->_assertHas($html, '<table border="1" align="center">');
        $this->_assertHas($html, '<th>1 (/5.0)</th>');
        $this->_assertHas($html, '<tr><td>Ann &quot;Q&quot; O&#039;Neil</td>');
        $this->_assertHas($html, '<td>3.00</td>');
    }
}
