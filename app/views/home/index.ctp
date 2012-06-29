<?php
// print active courses
if (isset($course_list['A']))
{
  echo "<h2>My Courses</h2>";
  foreach ($course_list['A'] as $course)
  {
    $courseid = $course['Course']['id'];
    $coursename = $course['Course']['course'];
    $image = $html->image("icons/home.gif", array('alt' => "$coursename home"));
    $link = "/courses/home/$courseid";
    echo "<div class='course'>";
    echo "<h3>";
    echo $html->link($coursename, $link, array('escape' => false));
    echo "</h3>";

    echo "<h4>Instructors: ";
    echo $this->element(
      'courses/list_instructors',
      array('instructors' => $course['Instructor'])
    );
    echo "</h4>";
    echo "<table>";
    echo $this->Html->tableHeaders(
      array('Events', 'Completion Ratio', 'Due Date'));
    foreach ($course['Event'] as $event)
    {
      $eventTitle = $this->Html->link(
        $event['title'],
        ($event['event_template_type_id'] == 3) ?
        '/surveygroups/viewresult/'.$event['id'] :
        '/evaluations/view/'.$event['id']);
      $eventReview = '';
      if ($event['to_review_count'] > 0)
      {
        $eventReview = $event['to_review_count'] .
           ' unreviewed group evaluations.';
      }
      $eventRatio = $event['completed_count'] .' of '.
        $event['student_count'] . ' Students';
      $eventDue = Toolkit::formatDate($event['due_date']);
      echo $this->Html->tableCells(
        array($eventTitle . $eventReview, $eventRatio, $eventDue)
      );
    }
    echo "</table>";
    echo "</div>";
  }
}

// print inactive courses
if (isset($course_list['I']))
{
  echo "<h2>Inactive Courses</h2>";
  foreach ($course_list['I'] as $course)
  {
    $courseid = $course['Course']['id'];
    $coursename = $course['Course']['course'];
    echo "<div class='course'>";
    echo "<h3>$coursename</h3>";
    echo "<h4>Instructors: ";
    echo $this->element(
      'courses/list_instructors',
      array('instructors' => $course['Instructor'])
    );
    echo "</h4>";
    echo "</div>";
  }
}

?>

<?php if (empty($course_list)):?>
    <?php echo __('You do not have any course.') ?>
<?php endif; ?>

<div class='toggle' style="text-align:right">
<a href="#" onclick="javascript:$('short_help').toggle();return false;">
(<?php __('Short Help')?>)
</a>
</div>

<div id="short_help" <?php echo ($course_list == 0) ? '':'style="display:none"'?>>
<h5><?php __('To use iPeer you have to add a course.')?></h5>
  <ol>
    <li><?php __('Please <i>add a course</i> from the yellow "Courses" tab above')?>
    </li>

    <li><?php __('Then <i>register students</i> into that course from that courses summary display. This display will available (once the course is created) by clicking on the courses name from most menus .')?>
    </li>

    <li><?php __('Put your students into <i>groups</i> manually, (or, if you have the students complete a survey, iPeer can do it for you, using TeamMaker).')?>
    </li>

    <li>
      <?php __('To create evaluations, check out the orange wizard link the top right hand corner "iPeer Tutorial Wizard".')?>
          <?php __('It has detailed movies on how to create evaluations.')?>
    </li>
  </ol>
</div>
