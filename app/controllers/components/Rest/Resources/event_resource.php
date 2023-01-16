<?php

class EventResourceComponent extends CakeObject
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
     * @param array $event
     * @return array
     */
    function format(array $event): array
    {
        if (!empty($event)) {
            return [
                'id' => $event['id'],
                'title' => $event['title'],
                'course_id' => $event['course_id'],
                'description' => $event['description'],
                'event_template_type_id' => $event['event_template_type_id'],
                'template_id' => $event['template_id'],
                'self_eval' => $event['self_eval'],
                'com_req' => $event['com_req'],
                'auto_release' => $event['auto_release'],
                'enable_details' => $event['enable_details'],
                'due_date' => $event['due_date'],
                'release_date_begin' => $event['release_date_begin'],
                'release_date_end' => $event['release_date_end'],
                'result_release_date_begin' => $event['result_release_date_begin'],
                'result_release_date_end' => $event['result_release_date_end'],
                'record_status' => $event['record_status'],
                'is_released' => $event['is_released'],
                'is_result_released' => $event['is_result_released'],
                'is_ended' => $event['is_ended'],
                'due_in' => $event['due_in'],
            ];
        }
        
        return [];
    }
}
