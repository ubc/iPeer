<table class='standardtable'>
  <tr>
    <th>Instructors</th>
    <th>Tutors</th>
    <th>Class Size</th>
    <th>Groups Count</th>
    <th>Evaluation Events</th>
  </tr>
  <tr>
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
$params = array('submenu'=>$submenu, 'submenuTitle'=>$submenuTitle);
echo $this->element('courses/submenu', $params);


$submenu = 'Group';
$submenuTitle = __('Groups', true);
$params = array('controller'=>'courses', 'submenu'=>$submenu, 'submenuTitle'=>$submenuTitle, 'courseId'=>$course_id);
echo $this->element('courses/submenu', $params);

$submenu = 'EvalEvents';
$submenuTitle = __('Evaluation Events', true);
$params = array('controller'=>'courses', 'submenu'=>$submenu, 'submenuTitle'=>$submenuTitle, 'courseId'=>$course_id);
echo $this->element('courses/submenu', $params);

if (User::hasPermission('controllers/Surveys')) {
  $submenu = 'TeamMaker';
  $submenuTitle = __('Surveys (Team Maker)', true);
  $params = array('controller'=>'courses', 'submenu'=>$submenu, 'submenuTitle'=>$submenuTitle, 'course_id'=>$course_id);
  echo $this->element('courses/submenu', $params);
}

?>
</div>
