<?php
App::import('Model', 'Penalty');

class PenaltyTestCase extends CakeTestCase
{
    public $name = 'Penalty';
    public $fixtures = array('app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey',
        'app.user_faculty', 'app.faculty', 'app.department',
        'app.course_department', 'app.sys_parameter', 'app.user_tutor', 'app.penalty',
        'app.evaluation_simple', 'app.survey_input',
    );
    public $Penalty = null;

    function startCase()
    {
        echo "Start Penalty model test.\n";
        $this->Penalty = ClassRegistry::init('Penalty');
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

    function testGetPenaltyById()
    {
        // valid id
        $ret = $this->Penalty->getPenaltyById(3);
        $this->assertEqual($ret['Penalty']['percent_penalty'], 60);

        // id = null
        $ret = $this->Penalty->getPenaltyById(null);
        $this->assertEqual($ret['Penalty']['percent_penalty'], null);

        // invalid id
        $ret = $this->Penalty->getPenaltyById(999);
        $this->assertEqual($ret['Penalty']['percent_penalty'], null);
    }

    function testGetPenaltyByEventId()
    {
        // valid event
        $ret = $this->Penalty->getPenaltyByEventId(2);
        $this->assertEqual($ret['0']['Penalty']['percent_penalty'], 15);
        $this->assertEqual($ret['1']['Penalty']['percent_penalty'], 30);
        $this->assertEqual($ret['2']['Penalty']['percent_penalty'], 45);

        // valid event but no penalty
        $ret = $this->Penalty->getPenaltyByEventId(3);
        $this->assertEqual($ret, array());

        // id = null
        $ret = $this->Penalty->getPenaltyByEventId(null);
        $this->assertEqual($ret, array());

        // invalid id
        $ret = $this->Penalty->getPenaltyByEventId(999);
        $this->assertEqual($ret, array());
    }

    function testGetPenaltyFinal()
    {
        // valid event
        $ret = $this->Penalty->getPenaltyFinal(1);
        $this->assertEqual($ret['Penalty']['percent_penalty'], 100);

        // valid event but no penalty
        $ret = $this->Penalty->getPenaltyFinal(3);
        $this->assertEqual($ret, array());

        // id = null
        $ret = $this->Penalty->getPenaltyFinal(null);
        $this->assertEqual($ret, array());

        // invalid id
        $ret = $this->Penalty->getPenaltyFinal(999);
        $this->assertEqual($ret, array());
    }

    function testGetPenaltyDays()
    {
        // valid event
        $ret = $this->Penalty->getPenaltyDays(1);
        $this->assertEqual($ret, 3);

        // valid event but no penalty
        $ret = $this->Penalty->getPenaltyDays(3);
        $this->assertEqual($ret, null);

        // id = null
        $ret = $this->Penalty->getPenaltyDays(null);
        $this->assertEqual($ret, null);

        // invalid id
        $ret = $this->Penalty->getPenaltyDays(999);
        $this->assertEqual($ret, null);
    }

    function testGetPenaltyByEventAndDaysLate()
    {
        // valid event - right on time
        $ret = $this->Penalty->getPenaltyByEventAndDaysLate(2, 0);
        $this->assertEqual($ret, null);

        // valid event - not late
        $ret = $this->Penalty->getPenaltyByEventAndDaysLate(2, -1.5);
        $this->assertEqual($ret, null);

        // valid event - late
        $ret = $this->Penalty->getPenaltyByEventAndDaysLate(2, 1.5);
        $this->assertEqual($ret['Penalty']['percent_penalty'], 30);

        // valid event - final deduction
        $ret = $this->Penalty ->getPenaltyByEventAndDaysLate(2, 5);
        $this->assertEqual($ret['Penalty']['percent_penalty'], 60);

        // valid event but no penalty - not late
        $ret = $this->Penalty->getPenaltyByEventAndDaysLate(3, -1.5);
        $this->assertEqual($ret, null);

        // valid event but no penalty - late
        $ret = $this->Penalty->getPenaltyByEventAndDaysLate(3, 1.5);
        $this->assertEqual($ret, null);

        // valid event but no penalty - final deduction
        $ret = $this->Penalty->getPenaltyByEventAndDaysLate(3, 5);
        $this->assertEqual($ret, null);

        // id = null - not late
        $ret = $this->Penalty->getPenaltyByEventAndDaysLate(null, -1.5);
        $this->assertEqual($ret, null);

        // id = null - late
        $ret = $this->Penalty->getPenaltyByEventAndDaysLate(null, 1.5);
        $this->assertEqual($ret, null);

        // id = null - final deduction
        $ret = $this->Penalty->getPenaltyByEventAndDaysLate(null, 5);
        $this->assertEqual($ret, null);

        // invalid id - not late
        $ret = $this->Penalty->getPenaltyByEventAndDaysLate(999, -1.5);
        $this->assertEqual($ret, null);

        // invalid id - late
        $ret = $this->Penalty->getPenaltyByEventAndDaysLate(999, 1.5);
        $this->assertEqual($ret, null);

        // invalid id - final deduction
        $ret = $this->Penalty->getPenaltyByEventAndDaysLate(999, 5);
        $this->assertEqual($ret, null);
    }
}
