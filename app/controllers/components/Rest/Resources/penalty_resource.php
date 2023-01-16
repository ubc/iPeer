<?php

class PenaltyResourceComponent extends CakeObject
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
     * @return array
     */
    public function toArray(): array
    {
        return $this->controller->Penalty->all();
    }
    
    
    public function format($penalty): array
    {
        $data = [];
        foreach ($penalty as $key => $value) {
            $data['id'] = $value['id'];
            $data['event_id'] = $value['event_id'];
            $data['days_late'] = $value['days_late'];
            $data['percent_penalty'] = $value['percent_penalty'];
        }
        // returns the last object in the array [refactor]
        return $data;
    }
    
    
    /**
     * @param $eventId
     * @return array|null
     * union not allowed in the current php version 7.2
     */
    public function getPenaltyFinalByEventId($eventId)
    {
        $penalty = $this->controller->Penalty->getPenaltyFinal($eventId);
        if (empty($penalty)) return $penalty;
        return $penalty['Penalty'];
    }
    
    public function getPenaltyDaysByEventId($eventId): string
    {
        $penalty = $this->controller->Penalty->getPenaltyDays($eventId);
        if (empty($penalty)) return $penalty;
        return $penalty;
    }
}