<?php
$items = array();
switch($submenu) {
  case "Student":
    if ($active) {
        array_push(
            $items,
            array('name' => 'Add Student', 'link' => "/users/add/$course_id"),
            array('name' => 'Import Students', 'link' => "/users/import/$course_id")
        );
    }
    array_push(
        $items,
        array('name' => 'List Students', 'link' => "/users/goToClassList/$course_id")
    );
    if ($active) {
        array_push(
            $items,
            array('name' => 'Email to All Students',  'link' => "/emailer/write/C/$course_id")
        );
    }
    break;
  case "Group":
    if ($active) {
        array_push(
            $items,
            array('name' => 'Create Groups (Manual)', 'link' => "/groups/add/$course_id"),
            array('name' => 'Create Groups (Import)', 'link' => "/groups/import/$course_id")
        );
    }
    array_push(
        $items,
        array('name' => 'List Groups', 'link' => "/groups/index/$course_id"),
        array('name' => 'Export Groups', 'link' => "/groups/export/$course_id")
    );
    break;
  case "EvalEvents":
    if ($active) {
        array_push(
            $items,
            array('name' => 'Add Event', 'link' => "/events/add/$course_id")
        );
    }
    array_push(
        $items,
        array('name' => 'List Evaluation Events', 'link' => "/events/index/$course_id"),
        array('name' => 'Export Evaluation Results', 'link' => "/evaluations/export/course/$course_id")
    );
    break;
  case "TeamMaker":
    if ($active) {
        array_push(
            $items,
            array('name' => 'Create Groups (Auto)', 'link' => "/surveygroups/makegroups/$course_id")
        );
    }
    array_push(
        $items,
        array('name' => 'List Survey Group Sets', 'link' => "/surveygroups/index/$course_id")
    );
    break;
}
?>
<div class="course_submenu">
<h3>
  <?php echo $submenuTitle?>
</h3>
<ul>
<?php
foreach($items as $item) {
  echo '<li>';
  echo $this->Html->link(
    $item['name'],
    $item['link'],
    array('escape' => false)
  );
  echo '</li>';
}
?>
</ul>
</div>
