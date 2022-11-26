<?php

class UserResourceComponent extends CakeObject
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
    
    
    public function userById(): array
    {
    
        return [];
    }
}