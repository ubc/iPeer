<?php

class SimpleEvaluationResourceComponent extends CakeObject
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
     * getSimpleEvaluationQuestionsAndSubmission
     * @param $simpleEvaluation
     * @param $groupMembers
     * @param $submission
     * @param $evaluation
     * @return array
     */
    public function getSimpleEvaluationQuestionsAndSubmission($simpleEvaluation, $groupMembers, $submission, $evaluation): array
    {
        return [
            'id' => $simpleEvaluation['SimpleEvaluation']['id'],
            'name' => $simpleEvaluation['SimpleEvaluation']['name'],
            'description' => $simpleEvaluation['SimpleEvaluation']['description'],
            'availability' => $simpleEvaluation['SimpleEvaluation']['availability'],
            'event_count' => $simpleEvaluation['SimpleEvaluation']['event_count'],
            'point_per_member' => $simpleEvaluation['SimpleEvaluation']['point_per_member'],
            'record_status' => $simpleEvaluation['SimpleEvaluation']['record_status'],
            'questions' => [],
            'submission' => isset($submission) && $submission !== false ? [
                'id' => $submission['EvaluationSubmission']['id'],
                'submitted' => $submission['EvaluationSubmission']['submitted'],
                'date_submitted' => $submission['EvaluationSubmission']['date_submitted'],
                'submitter_id' => $submission['EvaluationSubmission']['submitter_id'],
                'event_id' => $submission['EvaluationSubmission']['event_id'],
                'grp_event_id' => $submission['EvaluationSubmission']['grp_event_id'],
                'record_status' => $submission['EvaluationSubmission']['record_status'],
                'data' => $this->getSimpleEvaluationSubmissionDetail($groupMembers, $evaluation),
            ] : [
                'data' => $this->getSimpleEvaluationGroupMembersDetail($groupMembers),
            ]
        ];
    }
    
    
    public function getSimpleEvaluationSubmissionDetail(array $groupMembers, array $evaluation): array
    {
        if (!isset($groupMembers) || !isset($evaluation)) return [];
        $data = [];
        foreach ($groupMembers as $key => $groupMember) {
            $tmp = [];
            $tmp['id'] = $groupMember['User']['id'];
            $tmp['first_name'] = $groupMember['User']['first_name'];
            $tmp['last_name'] = $groupMember['User']['last_name'];
            
            $tmp['detail'] = isset($evaluation) && !empty($evaluation) ? [
                'id' => $evaluation[$key]['EvaluationSimple']['id'],
                'event_id' => $evaluation[$key]['EvaluationSimple']['event_id'],
                'grp_event_id' => $evaluation[$key]['EvaluationSimple']['grp_event_id'],
                'evaluator' => $evaluation[$key]['EvaluationSimple']['evaluator'],
                'evaluatee' => $evaluation[$key]['EvaluationSimple']['evaluatee'],
                'date_submitted' => $evaluation[$key]['EvaluationSimple']['date_submitted'],
                'grade_release' => $evaluation[$key]['EvaluationSimple']['grade_release'],
                'record_status' => $evaluation[$key]['EvaluationSimple']['record_status'],
                'release_status' => $evaluation[$key]['EvaluationSimple']['release_status'],
                'response' => [
                    'comment' => $evaluation[$key]['EvaluationSimple']['comment'],
                    'score' => $evaluation[$key]['EvaluationSimple']['score'],
                ]
            ] : [];
            
            $data[] = $tmp;
        }
        return $data;
    }
    
    private function getSimpleEvaluationGroupMembersDetail(array $groupMembers): array
    {
        if (!isset($groupMembers)) return [];
        $data = [];
        foreach ($groupMembers as $groupMember) {
            $tmp = [];
            $tmp['id'] = $groupMember['User']['id'];
            $tmp['first_name'] = $groupMember['User']['first_name'];
            $tmp['last_name'] = $groupMember['User']['last_name'];
            
            $tmp['detail'] = [];
            
            $data[] = $tmp;
        }
        return $data;
    }
    
}
