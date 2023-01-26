<?php

class EvaluationCollectionComponent extends CakeObject
{
    public $components = ['EventResource', 'CourseResource', 'GroupResource', 'PenaltyResource', 'EvaluationSubmission'];
    public $controller;
    public $settings;
    
    public function initialize($controller, $settings)
    {
        $this->controller = $controller;
        $this->settings = $settings;
    }
    
    /**
     * @param $evaluations
     * @return array
     */
    public function toArray($evaluations): array
    {
        $data = [];
        foreach ($evaluations as $row) {
            $tmp['event'] = isset($row['Event']) ? $this->EventResource->format($row['Event'], $row['Group']['id']) : null;
            $tmp['course'] = isset($row['Course']) ? $this->CourseResource->courseById($row['Course']['id']) ?? NULL : NULL;
            $tmp['group'] = isset($row['Group']) ? $this->GroupResource->groupByIdEventId($row['Group']['id'], $row['Event']['id']) ?? NULL : NULL;
            $tmp['penalties'] = $row['Penalty'] ?? NULL;
            $tmp['penalty_final'] = isset($row['Event']) ? $this->PenaltyResource->getPenaltyFinalByEventId($row['Event']['id']) : NULL;
            $tmp['penalty_days'] = isset($row['Event']) ? $this->PenaltyResource->getPenaltyDaysByEventId($row['Event']['id']) : NULL;
            $tmp['is_submitted'] = $this->EvaluationSubmission->isSubmitted($row['Event']['id'], $row['Group']['id']);
            $tmp['penalty_percent'] = $row['percent_penalty'] ?? NULL;
            $tmp['expiry_date'] = $row['expiry_date'] ?? NULL; // JK::A
            $tmp['late'] = $row['late'] ?? NULL;
            $tmp['status'] = $row['status'];
            
            $data[] = $tmp;
        }
        return $data;
    }
    
    
    /**
     * Private
     */
    
    /**
     * @param string $eventId
     * @param string $groupId
     * @return mixed|null
     */
//    private function isSubmitted(string $eventId, string $groupId)
//    {
//        $userId = User::get('id');
//        $submission = $this->controller->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, $userId);
//        return $submission['EvaluationSubmission']['submitted'] ?? null;
//    }
    
}