<div id='CourseHome'>
<?php $studentCount = $data['Course']['student_count'];
$groupCount = count($data['Group']);
$eventCount = count($data['Event']);
if ($data['Course']['record_status'] == 'I') {
    echo "<div class='invalid'>Inactive Course (Limited Access)</div><br>";
} ?>
<table class='standardtable'>
  <tr>
    <th>Web</th>
    <th>Instructors</th>
    <th>Tutors</th>
    <th>Class Size</th>
    <th>Groups Count</th>
    <th>Evaluation Events</th>
  </tr>
  <tr>
    <td>
        <?php
        echo (!empty($data['Course']['homepage'])) ? "<a href=".$data['Course']['homepage']." target='_blank'>
            <img src='/img/icons/home.gif' border='0'></a>" : "None";
        ?>
    </td>
    <td>
      <?php
      echo $this->element(
        'list/unordered_list_users',
        array('instructors'=>$data['Instructor'])
      );
      ?>
    </td>
    <td>
      <?php
      echo $this->element(
        'list/unordered_list_users',
        array('instructors'=>$data['Tutor'])
      );
      ?>
    </td>
    <td><?php echo "$studentCount students"; ?></td>
    <td><?php echo "$groupCount groups"; ?></td>
    <td><?php echo "$eventCount events"; ?></td>
  </tr>
</table>

<div class='course_actions'>
<?php
$submenu = 'Student';
$submenuTitle = __('Students', true);
$params = array('submenu'=>$submenu, 'submenuTitle'=>$submenuTitle, 'course_id'=>$data['Course']['id']);
echo $this->element('courses/submenu', $params);


$submenu = 'Group';
$submenuTitle = __('Groups', true);
$params = array('controller'=>'courses', 'submenu'=>$submenu, 'submenuTitle'=>$submenuTitle, 'course_id'=>$data['Course']['id']);
echo $this->element('courses/submenu', $params);

$submenu = 'EvalEvents';
$submenuTitle = __('Evaluation Events', true);
$params = array('controller'=>'courses', 'submenu'=>$submenu, 'submenuTitle'=>$submenuTitle, 'course_id'=>$data['Course']['id']);
echo $this->element('courses/submenu', $params);

if (User::hasPermission('controllers/Surveys')) {
  $submenu = 'TeamMaker';
  $submenuTitle = __('Team Maker', true);
  $params = array('controller'=>'courses', 'submenu'=>$submenu, 'submenuTitle'=>$submenuTitle, 'course_id'=>$data['Course']['id']);
  echo $this->element('courses/submenu', $params);
}

if ($canvasEnabled) {
  $submenu = 'Canvas';
  $submenuTitle = __('Canvas', true);
  $params = array('controller'=>'courses', 'submenu'=>$submenu, 'submenuTitle'=>$submenuTitle, 'course_id'=>$data['Course']['id']);
  echo $this->element('courses/submenu', $params);
}

?>
</div>
</div>
