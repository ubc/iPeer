<?php

class CoursesViewTestCase extends CakeTestCase {
  function startTest($method) {
    $controller = new Controller();
    $this->view = new View($controller);
    $this->view->layout = null;
    $this->view->viewPath = 'courses';
  }

  function testCourseInstance() {
    $this->assertTrue(is_a($this->view, 'View'));
  }

  function testCourseIndex() {
  }
}
