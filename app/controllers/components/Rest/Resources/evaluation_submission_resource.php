<?php

class EvaluationSubmissionResourceComponent extends CakeObject
{
    public $components = [
        'EventResource', 'CourseResource', 'GroupResource', 'PenaltyResource',
        'EvaluationSubmissionResource', 'EvaluationReviewsCollection', 'UserCollection',
        'JsonResponse'];
    public $controller;
    public $settings;
    
    public function initialize($controller, $settings)
    {
        $this->controller = $controller;
        $this->settings = $settings;
    }
    
    public function get($data): array
    {
        // return ['Resource' => 'EvaluationSubmission'];
        if (empty($data)) return [];
        return [
            'id' => $data['questions']['id'],
            'name' => $data['questions']['name'],
            'description' => $data['questions']['description'],
            'point_per_member' => $data['questions']['point_per_member'],
            'record_status' => $data['questions']['record_status'],
            'availability' => $data['questions']['availability'],
            // 'event_count'       => $data['questions']['event_count'],
            'remaining' => $data['remaining'],
            /** NOTE:: need to handle error when evaluation not submitted! */
            // 'submitted'         => $data['submission']['EvaluationSubmission']['submitted'],
            // 'submitter_id'      => $data['submission']['EvaluationSubmission']['submitter_id'],
            // 'date_submitted'    => $data['submission']['EvaluationSubmission']['date_submitted'],
            //
            'submitted' => $this->evaluationSubmission($data['event']['Event']['id'], $data['event']['Group']['id'])['submitted'],
            'submitter_id' => $this->evaluationSubmission($data['event']['Event']['id'], $data['event']['Group']['id'])['submitter_id'],
            'date_submitted' => $this->evaluationSubmission($data['event']['Event']['id'], $data['event']['Group']['id'])['date_submitted'],
            //
            // 'data'              => $this->getSimpleEvaluationData($data['submission'], $data['evaluation'], $data['event']['Group']['id'], $data['event']['Event']['self_eval']),
            // get submission
            'data' => $this->getSimpleEvaluationSubmission(
                $data['submission'],
                $data['evaluation'],
                $data['event']['Event']['id'],
                $data['event']['Group']['id'],
                $data['event']['Event']['self_eval']
            ),
        ];
    }
    
    /**
     * @param string $eventId
     * @param string $groupId
     * @return mixed|null
     */
    private function evaluationSubmission(string $eventId, string $groupId)
    {
        $userId = User::get('id');
        $submission = $this->controller->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, $userId);
        return $submission['EvaluationSubmission'] ?? null;
    }
    
    /**
     * Private
     */
    private function getSimpleEvaluationSubmission($submission, $evaluation, $eventId, $groupId, $selfEval): array
    {
        $isSubmitted = $this->isSubmitted($eventId, $groupId);
        $members = $this->UserCollection->eventGroupMembersNoTutors($groupId, $selfEval);
        
        $output = [];
        foreach ($members as $key => $member) {
            $tmp = [];
            $tmp['id'] = $member['id'];
            $tmp['first_name'] = $member['first_name'];
            $tmp['last_name'] = $member['last_name'];
            //$tmp['position'] = '';
            $tmp['point'] = isset($isSubmitted) ? $evaluation[$key]['EvaluationSimple']['score'] : NULL;
            $tmp['comment'] = isset($isSubmitted) ? $evaluation[$key]['EvaluationSimple']['comment'] : NULL;
            $output[] = $tmp;
        }
        
        return $output;
    }
    
    
    /***/
    
    private function isSubmitted(string $eventId, string $groupId)
    {
        $userId = User::get('id');
        $submission = $this->controller->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, $userId);
        return $submission['EvaluationSubmission']['submitted'] ?? null;
    }
}
