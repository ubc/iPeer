<?php

class EvaluationSubmissionComponent extends CakeObject
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
     * @param string $eventId
     * @param string $groupId
     * @return mixed|null
     */
    public function isSubmitted(string $eventId, string $groupId)
    {
        $userId = User::get('id');
        // $submission = $this->controller->EvaluationSubmission->getEvalSubmissionByEventIdSubmitter($eventId, $userId);
        $submission = $this->controller->EvaluationSubmission->getEvalSubmissionByEventIdGroupIdSubmitter($eventId, $groupId, $userId);
        return $submission['EvaluationSubmission']['submitted'] ?? null;
    }
    
    public function submission(string $eventId, string $userId)
    {
        $userId = $userId ?? User::get('id');
        return $this->controller->EvaluationSubmission->getEvalSubmissionByEventIdSubmitter($eventId, $userId);
    }
}
