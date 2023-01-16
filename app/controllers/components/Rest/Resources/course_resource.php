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
    
    
    public function format(array $course): array
    {
        return [
            'id' => $course['id'],
            'course' => $course['course'],
            'title' => $course['title'],
            'term' => $course['term'],
        ];
    }
    
    /**
     * @param $courseId
     * @return array
     */
    public function courseById($courseId): array
    {
        if (!isset($courseId)) return [];
        $course = $this->controller->Course->getCourseById($courseId);
        if (isset($course)) {
            return $this->format($course['Course']);
        }
        return [];
    }
}