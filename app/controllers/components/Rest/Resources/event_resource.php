<?php

class EventResourceComponent extends CakeObject
{
    public $controller;
    public $settings;
    
    public function initialize($controller, $settings)
    {
        $this->controller = $controller;
        $this->settings = $settings;
    }
    
    /**
     * @param $event
     * @return array
     */
    public function toArray($event): array
    {
        if(!isset($event)) return [];
        return [
            'id' => $event['event']['Event']['id'],
            'title' => $event['event']['Event']['title'],
            'description' => $event['event']['Event']['description'],
            'event_template_type_id' => $event['event']['Event']['event_template_type_id'],
            'template_id' => $event['event']['Event']['template_id'],
            'self_eval' => $event['event']['Event']['self_eval'],
            'com_req' => $event['event']['Event']['com_req'],
            'auto_release' => $event['event']['Event']['auto_release'],
            'due_date' => $event['event']['Event']['due_date'],
            'release_date_begin' => $event['event']['Event']['release_date_begin'],
            'release_date_end' => $event['event']['Event']['release_date_end'],
            'result_release_date_begin' => $event['event']['Event']['result_release_date_begin'],
            'result_release_date_end' => $event['event']['Event']['result_release_date_end'],
            'group_event_id' => $event['event']['GroupEvent']['id'],
            'status'                => $this->isSubmitted($event['event']['Event']['id'], $event['event']['Group']['id']),
            'is_released'           => $event['event']['Event']['is_released'],
            'is_result_released'    => $event['event']['Event']['is_result_released'],
            'is_ended'              => $event['event']['Event']['is_ended']
        ];
    }
    
    /**
     * Private
     */
    
    /**
     * @param string $eventId
     * @param string $groupId
     * @return mixed|null
     */
    private function isSubmitted(string $eventId, string $groupId)
    {
        $userId = User::get('id');
        $submission = $this->controller->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, $userId);
        return $submission['EvaluationSubmission']['submitted'] ?? null;
    }
    
}