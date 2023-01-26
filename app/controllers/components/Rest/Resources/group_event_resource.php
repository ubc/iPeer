<?php

class GroupEventResourceComponent extends CakeObject
{
    public $components = [];
    public $controller;
    public $settings;
    
    public function initialize($controller, $settings)
    {
        $this->controller = $controller;
        $this->settings = $settings;
    }
    
    public function getGroupEventByEventIdGroupId(string $eventId, string $groupId): array
    {
        if (!isset($eventId) || !isset($groupId)) return [];
        
        $group_events = $this->controller->GroupEvent->getGroupEventByEventId($eventId);
        
        return array_filter($group_events, function ($grp_event) use ($groupId) {
            return $grp_event['id'] === $groupId;
        }, []);
    }
}
