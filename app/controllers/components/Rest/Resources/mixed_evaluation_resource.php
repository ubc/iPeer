<?php

class MixedEvaluationResourceComponent extends CakeObject
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
     * @param $mixeval
     * @param $questions
     * @param $groupMembers
     * @param $submission
     * @return array
     */
    public function getMixedEvaluationQuestionsAndSubmission($mixeval, $questions, $groupMembers, $submission): array
    {
        return [
            'id' => $mixeval['Mixeval']['id'],
            'availability' => $mixeval['Mixeval']['availability'],
            'name' => $mixeval['Mixeval']['name'],
            'peer_question' => $mixeval['Mixeval']['peer_question'],
            'self_question' => $mixeval['Mixeval']['self_eval'],
            'total_marks' => $mixeval['Mixeval']['total_marks'],
            'total_question' => $mixeval['Mixeval']['total_question'],
            'zero_mark' => $mixeval['Mixeval']['zero_mark'],
            'questions' => $this->getMixedEvaluationQuestionsDetail($questions),
            'submission' => isset($submission) && $submission !== false ? [
                'id' => $submission['EvaluationSubmission']['id'],
                'submitted' => $submission['EvaluationSubmission']['submitted'],
                'date_submitted' => $submission['EvaluationSubmission']['date_submitted'],
                'submitter_id' => $submission['EvaluationSubmission']['submitter_id'],
                'event_id' => $submission['EvaluationSubmission']['event_id'],
                'grp_event_id' => $submission['EvaluationSubmission']['grp_event_id'],
                'record_status' => $submission['EvaluationSubmission']['record_status'],
                'data' => $this->getMixedEvaluationSubmissionDetail($groupMembers),
            ] : [
                'data' => $this->getMixedEvaluationGroupMembersDetail($groupMembers),
            ],
        ];
    }
    
    
    private function getMixedEvaluationQuestionsDetail($questions): array
    {
        if (!isset($questions)) return [];
        $data = [];
        foreach ($questions as $question) {
            $tmp = $question['MixevalQuestion'];
            $tmp['type'] = $question['MixevalQuestionType']['type'];
            $tmp['loms'] = $question['MixevalQuestionDesc'];
            
            $data[] = $tmp;
        }
        return $data;
    }
    
    
    private function getMixedEvaluationSubmissionDetail(array $groupMembers): array
    {
        if (!isset($groupMembers)) return [];
        $data = [];
        foreach ($groupMembers as $member) {
            $tmp = [];
            $tmp['id'] = $member['User']['id'];
            $tmp['first_name'] = $member['User']['first_name'];
            $tmp['last_name'] = $member['User']['last_name'];
            
            isset($member['User']['Evaluation']) && !empty($member['User']['Evaluation']) ? $tmp['detail'] = [
                'id' => $member['User']['Evaluation']['EvaluationMixeval']['id'],
                'event_id' => $member['User']['Evaluation']['EvaluationMixeval']['event_id'],
                'grp_event_id' => $member['User']['Evaluation']['EvaluationMixeval']['grp_event_id'],
                'evaluator' => $member['User']['Evaluation']['EvaluationMixeval']['evaluator'],
                'evaluatee' => $member['User']['Evaluation']['EvaluationMixeval']['evaluatee'],
                'comment_release' => $member['User']['Evaluation']['EvaluationMixeval']['comment_release'],
                'grade_release' => $member['User']['Evaluation']['EvaluationMixeval']['grade_release'],
                'record_status' => $member['User']['Evaluation']['EvaluationMixeval']['record_status'],
                'score' => $member['User']['Evaluation']['EvaluationMixeval']['score'],
                'response' => $this->getMixedEvaluationResponseDetail($member['User']['Evaluation']['EvaluationMixevalDetail']),
            ] : NULL;
            
            $data[] = $tmp;
        }
        
        return $data;
    }
    
    
    private function getMixedEvaluationGroupMembersDetail(array $groupMembers): array
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
    
    private function getMixedEvaluationResponseDetail(array $response): array
    {
        if (!isset($response)) return [];
        $data = [];
        foreach ($response as $r) {
            $tmp = [];
            $tmp['id'] = $r['id'];
            $tmp['evaluation_mixeval_id'] = $r['evaluation_mixeval_id'];
            $tmp['question_number'] = $r['question_number'];
            $tmp['question_comment'] = $r['question_comment'];
            $tmp['selected_lom'] = $r['selected_lom'];
            $tmp['grade'] = $r['grade'];
            $tmp['comment_release'] = $r['comment_release'];
            $tmp['record_status'] = $r['record_status'];
            $data[] = $tmp;
        }
        return $data;
    }
    
}