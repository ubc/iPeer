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