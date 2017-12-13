<div id='CourseEditForm'>
<?php
if (isset($instructions)) {
    echo $instructions;
}
echo $this->Form->create('Course');
echo $this->Form->input('id');
// canvas courses
$cCourse = $form->input(
    'Course.canvas_course',
    array(
        'type' => 'select',
        'id' => 'CanvasCourses',
        'label' => __('Select a Canvas course', true),
        'options' => $canvasCourses,
        'after' => $html->link(__('Next', true), '#', array('onclick' => 'javascript: if (CanvasCourses.value == "") { return false; } document.forms[0].submit(); return false;'))
    )
);
echo $html->div('input text', $cCourse, array('id' => 'canvas_courses'));
if (empty($canvasCourses)) {
    echo $html->div('help-text', __('No accessible Canvas course', true));
}
?>
</div>
