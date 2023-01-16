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
    
    public function get($data): array
    {
        
        return $data;
    }
    
    public function eventGroupMembersNoTutors($groupId, $selfEval): array
    {
        $userId = User::get('id');
        $groupMembers = $this->controller->User->getEventGroupMembersNoTutors($groupId, $selfEval, $userId);
        $members = [];
        foreach ($groupMembers as $member) {
            $tmp = [];
            $tmp['id'] = $member['User']['id'];
            $tmp['first_name'] = $member['User']['first_name'];
            $tmp['last_name'] = $member['User']['last_name'];
            $tmp['role_name'] = $member['Role'][0]['name'];
            $members[] = $tmp;
        }
        return $members;
    }
}
