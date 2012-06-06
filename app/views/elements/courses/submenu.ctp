<div class="course_submenu">
<h3>
  <?php echo $submenuTitle?>
</h3>

<?php 
$params = array(
  'submenu'=>$submenu, 
  'submenuTitle'=>$submenuTitle, 
);

$items = array();
switch($submenu) {
  case "Student":
    array_push(
      $items,
      array('name' => 'Add Student', 'link' => "/users/add/$course_id"),
      array('name' => 'List Students',
        'link' => "/users/goToClassList/$course_id"),
      array('name' => 'Email to All Students',
        'link' => "/emailer/write/C/$course_id")
    );
    break;
  case "Group":
    array_push(
      $items,
      array('name' => 'Create Groups (Manual)', 
        'link' => "/groups/add/$course_id"),
      array('name' => 'List Groups', 
        'link' => "/groups/goToClassList/$course_id"),
      array('name' => 'Export Groups Information', 
        'link' => "/groups/export/$course_id")
    );
    break;
  case "EvalEvents":
    array_push(
      $items,
      array('name' => 'Add Event', 'link' => "/events/add"),
      array('name' => 'List Evaluation Events', 
        'link' => "/events/index/$course_id"),
      array('name' => 'Export Evaluation Results', 
        'link' => "/evaluations/export/courseId=$course_id")
    );
    break;
  case "TeamMaker":
    array_push(
      $items,
      array('name' => 'Edit Survey', 'link' => "/surveys/index/$course_id"),
      array('name' => 'View Survey Results', 
        'link' => "/surveygroups/viewresult/"),
      array('name' => 'Create Groups (Auto)', 
        'link' => "/surveygroups/makegroups/$course_id"),
      array('name' => 'List Survey Group Sets', 
        'link' => "/surveygroups/index/$course_id")
    );
    break;
}
echo $this->element('courses/submenu_items',
  array('items' => $items));

?>
</div>
