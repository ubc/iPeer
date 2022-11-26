<?php

class PenaltyCollectionComponent extends CakeObject
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
     * @param $penalties
     * @return array
     */
    public function toArray(): array
    {
        return $this->controller->Penalty->all();
    }
    
    /**
     * @param $eventId
     * @return array list of penalties object[]
     */
    public function penaltiesByEventId($eventId): array
    {
        $penalties = $this->controller->Penalty->getPenaltyByEventId($eventId);
        if (empty($penalties)) return $penalties;
        $data = [];
        foreach ($penalties as $penalty) {
            $data[] = $penalty['Penalty'];
        }
        return $data;
    }
    
    
}