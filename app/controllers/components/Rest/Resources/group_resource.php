<?php

class GroupResourceComponent extends CakeObject
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
        return [];
    }
    
    /**
     * @param $groupIid
     * @param $eventId
     * @return array
     */
    public function groupByIdEventId($groupIid, $eventId): array
    {
        if(!isset($groupIid)) return [];
        $group = $this->controller->Group->getGroupByGroupIdEventId($groupIid, $eventId);
        if (isset($group)) {
            return [
                'id'            => $group['Group']['id'],
                'num'           => $group['Group']['group_num'],
                'name'          => $group['Group']['group_name'],
                'member_count'  => $group['Group']['member_count'],
            ];
        }
        return $group;
    }
    
    /**
     * @param $groupId
     * @param $eventId
     * @return array
     */
    public function groupMembersByGroupIdEventId($groupId, $eventId): array
    {
        return [];
    }
    
    
}