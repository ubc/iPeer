<?php
App::import('Model', 'SimpleEvaluation');

class SimpleEvaluationTestCase extends CakeTestCase
{
    public $name = 'SimpleEvaluation';
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey', 
        'app.simple_evaluation', 'app.user_faculty'
    );

    function startCase()
    {
        echo "Start SimpleEvaluation model test.\n";
    }

    function endCase()
    {
    }

    function startTest($method)
    {
    }
}
