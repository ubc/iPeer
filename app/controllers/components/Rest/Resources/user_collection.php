<?php

class UserCollectionComponent extends CakeObject
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
     * @param $group
     * @return array
     */
    public function toArray($group): array
    {
        return [];
    }
    
    
    public function users(): array
    {
    return [];
    }
    
    
    public function members(): array
    {
    return [];
    }
    
    /**
     * @param $groupId
     * @param $selfEval
     * @return array
     */
    public function eventGroupMembersWithTutor($groupId, $selfEval): array
    {
        $userId = User::get('id');
        $groupMembers = $this->controller->GroupsMembers->getEventGroupMembers($groupId, $selfEval, $userId);
        $members = [];
        foreach ($groupMembers as $member) {
            $tmp = [];
            $tmp['id'] = $member['User']['id'];
            $tmp['first_name'] = $member['User']['first_name'];
            $tmp['last_name'] = $member['User']['last_name'];
            $members[] = $tmp;
        }
        return $members;
    }
    
    /**
     * @param $groupId
     * @param $selfEval
     * @return array
     */
    public function eventGroupMembersNoTutors($groupId, $selfEval): array
    {
        $userId = User::get('id');
        $groupMembers = $this->controller->User->getEventGroupMembersNoTutors($groupId, $selfEval, $userId);
        $members = [];
        foreach ($groupMembers as $member) {
            $tmp = [];
            $tmp['id']          = $member['User']['id'];
            $tmp['first_name']  = $member['User']['first_name'];
            $tmp['last_name']   = $member['User']['last_name'];
            $tmp['role_name']   = $member['Role'][0]['name'];
            $members[] = $tmp;
        }
        return $members;
    }
    
    
    
    public function groupEventByEventIdGroupId($eventId, $groupId): array
    {
        $groupMembers = $this->controller->GroupEvent->getGroupEventByEventIdGroupId($eventId, $groupId);
        
        return [];
    }
    
    
    
    
    
    /**
     * @param $groupId
     * @param $eventId
     * @return array TBD
     */
    public function membersByGroupIdEventId($groupId, $eventId): array
    {
    return [];
    }
    
    
}