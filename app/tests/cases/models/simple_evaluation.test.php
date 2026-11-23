<?php
App::import('Model', 'SimpleEvaluation');

class SimpleEvaluationTestCase extends CakeTestCase
{
    public $name = 'SimpleEvaluation';
    // the same fixtures as the Mixeval case: the Event association loads
    // these models too
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
        'app.evaluation_mixeval', 'app.evaluation_mixeval_detail',
        'app.simple_evaluation'
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

    // getBelongingOrPublic() is shared by the rubric and survey models
    // (EvaluationBase). The seed has one public template, "Module 1 Project
    // Evaluation" (1), created by user 1.
    function testGetBelongingOrPublicListsOwnAndPublicByName()
    {
        $model = ClassRegistry::init('SimpleEvaluation');
        $zeta = $this->addTemplate($model, 'Zeta own', 'private', 2);
        $alpha = $this->addTemplate($model, 'Alpha own', 'public', 2);
        $hidden = $this->addTemplate($model, 'Hidden other', 'private', 3);

        $row = fn ($id, $name, $creator) => ['id' => $id, 'name' => $name, 'creator_id' => $creator];
        $module = $row(1, 'Module 1 Project Evaluation', 1);

        // own templates (private too) and others' public ones, sorted by name
        $this->assertIdentical($model->getBelongingOrPublic(2), [
            $row($alpha, 'Alpha own', 2),
            $module,
            $row($zeta, 'Zeta own', 2),
        ]);
        $this->assertIdentical($model->getBelongingOrPublic(1), [
            $row($alpha, 'Alpha own', 2),
            $module,
        ]);

        // the included template is listed even when it is another user's
        // private one
        $this->assertIdentical($model->getBelongingOrPublic(99, $hidden), [
            $row($alpha, 'Alpha own', 2),
            $row($hidden, 'Hidden other', 3),
            $module,
        ]);

        $this->assertIdentical($model->getBelongingOrPublic('x'), []);
        $this->assertIdentical($model->getBelongingOrPublic(2, 'x'), []);

        $model->deleteAll(['SimpleEvaluation.id' => [$zeta, $alpha, $hidden]], false, false);
    }

    private function addTemplate($model, $name, $availability, $creatorId)
    {
        $model->create();
        $model->save([
            'name' => $name,
            'description' => '',
            'point_per_member' => 100,
            'availability' => $availability,
        ], false);
        $id = (int) $model->id;
        // Traceable sets the creator to the logged-in user, so set it here
        $model->updateAll(['creator_id' => $creatorId], ['SimpleEvaluation.id' => $id]);
        return $id;
    }
}
