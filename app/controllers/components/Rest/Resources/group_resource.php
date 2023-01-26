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
     * @param array $group
     * @return array
     */
    public function format(array $group): array
    {
        if (!isset($group)) return [];
        return [
            'id' => $group['id'],
            'num' => $group['group_num'],
            'name' => $group['group_name'],
            'member_count' => $group['member_count'],
        ];
    }
    
    /**
     * @param $groupId
     * @param $eventId
     * @return array
     */
    public function groupByIdEventId($groupId, $eventId): array
    {
        if (!isset($groupId)) return [];
        $group = $this->controller->Group->getGroupByGroupIdEventId($groupId, $eventId);
        if (isset($group)) {
            return $this->format($group['Group']);
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