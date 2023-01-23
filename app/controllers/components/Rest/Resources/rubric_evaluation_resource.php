<?php

class RubricEvaluationResourceComponent extends CakeObject
{
    public $components = [];
    public $controller;
    public $settings;
    
    public function initialize($controller, $settings)
    {
        $this->controller = $controller;
        $this->settings = $settings;
    }
    
    /**
     * @param $data
     * @param $groupMembers
     * @param $submission
     * @return array
     */
    public function getRubricEvaluationQuestionsAndSubmission($data, $groupMembers, $submission): array
    {
        return [
            'id' => $data['Rubric']['id'],
            'name' => $data['Rubric']['name'],
            'availability' => $data['Rubric']['availability'],
            'criteria' => $data['Rubric']['criteria'],
            'event_count' => $data['Rubric']['event_count'],
            'lom_max' => $data['Rubric']['lom_max'],
            'template' => $data['Rubric']['template'],
            'total_marks' => $data['Rubric']['total_marks'],
            'view_mode' => $data['Rubric']['view_mode'],
            'zero_mark' => $data['Rubric']['zero_mark'],
            'questions' => $this->getRubricEvaluationQuestionsDetail($data),
            'submission' => isset($submission) && $submission !== false ? [
                'id' => $submission['EvaluationSubmission']['id'],
                'submitted' => $submission['EvaluationSubmission']['submitted'],
                'date_submitted' => $submission['EvaluationSubmission']['date_submitted'],
                'submitter_id' => $submission['EvaluationSubmission']['submitter_id'],
                'event_id' => $submission['EvaluationSubmission']['event_id'],
                'grp_event_id' => $submission['EvaluationSubmission']['grp_event_id'],
                'record_status' => $submission['EvaluationSubmission']['record_status'],
                'data' => $this->getRubricEvaluationSubmissionDetail($groupMembers),
            ] : [
                'data' => $this->getRubricEvaluationGroupMembersDetail($groupMembers),
            ]
        ];
    }
    
    
    private function getRubricEvaluationQuestionsDetail($rubricEvaluation): array
    {
        if (!isset($rubricEvaluation)) return [];
        $data = [];
        foreach ($rubricEvaluation['RubricsCriteria'] as $criteria) {
            $tmp = [];
            $tmp['id'] = $criteria['id'];
            $tmp['criteria'] = $criteria['criteria'];
            $tmp['criteria_num'] = $criteria['criteria_num'];
            $tmp['multiplier'] = $criteria['multiplier'];
            $tmp['rubric_id'] = $criteria['rubric_id'];
            $tmp['show_marks'] = $criteria['show_marks'];
            $tmp['comments'] = $criteria['RubricsCriteriaComment'];
            $tmp['loms'] = $rubricEvaluation['RubricsLom'];
            
            $data[] = $tmp;
        }
        return $data;
    }
    
    
    public function getRubricEvaluationSubmissionDetail(array $groupMembers): array
    {
        $data = [];
        foreach ($groupMembers as $member) {
            $tmp = [];
            $tmp['id'] = $member['User']['id'];
            $tmp['first_name'] = $member['User']['first_name'];
            $tmp['last_name'] = $member['User']['last_name'];
            
            isset($member['User']['Evaluation']) ? $tmp['detail'] = [
                'id' => $member['User']['Evaluation']['EvaluationRubric']['id'],
                'comment' => $member['User']['Evaluation']['EvaluationRubric']['comment'],
                'comment_release' => $member['User']['Evaluation']['EvaluationRubric']['comment_release'],
                'evaluatee' => $member['User']['Evaluation']['EvaluationRubric']['evaluatee'],
                'evaluator' => $member['User']['Evaluation']['EvaluationRubric']['evaluator'],
                'grade_release' => $member['User']['Evaluation']['EvaluationRubric']['grade_release'],
                'score' => $member['User']['Evaluation']['EvaluationRubric']['score'],
                'response' => $member['User']['Evaluation']['EvaluationRubricDetail']
            ] : NULL;
            
            $data[] = $tmp;
        }
        
        return $data;
    }
    
    private function getRubricEvaluationGroupMembersDetail(array $groupMembers): array
    {
        if (!isset($groupMembers)) return [];
        $data = [];
        foreach ($groupMembers as $member) {
            $tmp = [];
            $tmp['id'] = $member['User']['id'];
            $tmp['first_name'] = $member['User']['first_name'];
            $tmp['last_name'] = $member['User']['last_name'];
            $tmp['detail'] = [];
            
            $data[] = $tmp;
        }
        return $data;
    }
    
}