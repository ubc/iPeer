<?php

class CourseResourceComponent extends CakeObject
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
     * @param $courseId
     * @return array
     */
    public function courseById($courseId): array
    {
        if(!isset($courseId)) return [];
        $course = $this->controller->Course->getCourseById($courseId);
        if (isset($course)) {
            return [
                'id'        => $course['Course']['id'],
                'course'    => $course['Course']['course'],
                'title'     => $course['Course']['title'],
                'term'      => $course['Course']['term'],
            ];
        }
        return [];
    }
}