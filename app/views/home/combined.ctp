<div class="instructorView">
    <h1 class="title"><img src="/img/layout/icon_ipeer_logo.gif" alt="icon_ipeer_logo"> Instructor View</h1>
<?php

/*
=================================================
Modified version of instructor view (index.ctp)
=================================================
*/

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
    echo $html->link("<h3>$coursename</h3>", $link, array('escape' => false));

    echo "<h4>Instructors: ";
    echo $this->element(
      'list/unordered_list_users',
      array('instructors' => $course['Instructor'])
    );
    echo "</h4>";
    echo "<table>";
    echo $this->Html->tableHeaders(
      array('Events', 'Completion Ratio', 'Due Date'));
    foreach ($course['Event'] as $event)
    {
      $eventTitle = $this->Html->link(
          $event['title'], '/evaluations/view/'.$event['id']);
      $eventReview = '';
      if ($event['to_review_count'] > 0)
      {
          $eventReview = '<br />('.sprintf(__('%d unreviewed group evaluations', true), $event['to_review_count']).')';
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
    $image = $html->image("icons/home.gif", array('alt' => "$coursename home"));
    $link = "/courses/home/$courseid";
    echo $html->link("<h3>$coursename</h3>", $link, array('escape' => false));
    echo "<h4>Instructors: ";
    echo $this->element(
      'list/unordered_list_users',
      array('instructors' => $course['Instructor'])
    );
    echo "</h4>";
    echo "</div>";
  }
}

?>

<div class='toggle' style="text-align:right">
<a href="#" onclick="javascript:$('short_help').toggle();return false;">
(<?php __('Short Help')?>)
</a>
</div>

<div id="short_help" <?php echo ($course_list == 0) ? '':'style="display:none"'?>>
<h5><?php __('To use iPeer you have to add a course.')?></h5>
  <ol>
    <li><?php __('Please <i>add a course</i> from the "Courses" tab above')?>
    </li>

    <li><?php __('Then <i>register students</i> into that course from that course\'s home page. This display will be available (once the course is created) by clicking on the course\'s name from most menus.')?>
    </li>

    <li><?php __('Put your students into <i>groups</i> manually, (or, if you have the students complete a survey, iPeer can do it for you, using TeamMaker).')?>
    </li>

    <li>
      <?php __('To create evaluations, check out the <a href="http://ipeer.ctlt.ubc.ca/wiki/UserDocV3.1" target="_blank">User Documentations</a>')?>.
    </li>
  </ol>
</div>
</div>
<?php
/*
=================================================
Modified version of student view (student_index.ctp)
=================================================
*/
?>
<h1 class="title"><img src="/img/layout/icon_ipeer_logo.gif" alt="icon_ipeer_logo"> Student View</h1>

<div id='StudentHome'>
<?php
function getUpcomingTableArray($html, $events) {
    $ret = array();
    foreach ($events as $event) {
        $tmp = array();
        if (isset($event['Group']['group_name'])) {
            $tmp[] = $html->link($event['Event']['title'],
                '/evaluations/makeEvaluation/'.$event['Event']['id'].'/'.
                $event['Group']['id']);
            $tmp[] = $event['Group']['group_name'];
        }
        else {
            $tmp[] = $html->link($event['Event']['title'],
                '/evaluations/makeEvaluation/'.$event['Event']['id']);
        }
        $tmp[] = $event['Course']['course'];
        $tmp[] = Toolkit::formatDate($event['Event']['due_date']);

        $due = $event['Event']['due_in'];
        if ($event['late']) {
            $penalty = isset($event['percent_penalty']) ?
                ', ' . $event['percent_penalty'] . '&#37; penalty' : '';
            $tmp[] = "<span class='red'>$due</span>$penalty";
        }
        else {
            $tmp[] = $due;
        }

        $ret[] = $tmp;
    }
    return $ret;
}

$evalUpcoming = getUpcomingTableArray($html, $evals['upcoming']);
$surveyUpcoming = getUpcomingTableArray($html, $surveys['upcoming']);

function getNonUpcomingTableArray($html, $events) {
    $ret = array();
    foreach ($events as $event) {
        $tmp = array();
        if (isset($event['Event']['is_result_released']) &&
            $event['Event']['is_result_released']
        ) { // we're in the result release period, so link to the results
            $tmp[] = $html->link($event['Event']['title'],
                '/evaluations/studentViewEvaluationResult/' .
                $event['Event']['id'] . '/' . $event['Group']['id']);
            $tmp[] = $event['Event']['result_release_date_end'];
        }
        else if ($event['Event']['event_template_type_id'] == 3) {
            // this is a survey, no release period, so link to the results
            $tmp[] = $html->link($event['Event']['title'],
                '/evaluations/studentViewEvaluationResult/' .
                $event['Event']['id']);
        }
        else {
            // we're not in the result release period, notify user when they can
            // view the results
            if ($event['Event']['is_released']) {
                // can let students edit their submissions
                if (isset($event['Group']['group_name'])) {
                    $tmp[] = $html->link($event['Event']['title'],
                        '/evaluations/makeEvaluation/'.$event['Event']['id'].
                        '/'. $event['Group']['id']);
                }
                else {
                    $tmp[] = $html->link($event['Event']['title'],
                        '/evaluations/makeEvaluation/'.$event['Event']['id']);
                }
            }
            else {
                $tmp[] = $event['Event']['title'];
            }
            $tmp[] = "<span class='orangered'>" .
                $event['Event']['result_release_date_begin'] . "</span>";
        }
        if (isset($event['Group']['group_name'])) {
            // NOTE: surveys don't have group names
            $tmp[] = $event['Group']['group_name'];
        }
        $tmp[] = $event['Course']['course'];
        $tmp[] = Toolkit::formatDate($event['Event']['due_date']);
        if (!empty($event['EvaluationSubmission'])) {
            // expired events have no submissions
            $tmp[] = $event['EvaluationSubmission']['date_submitted'];
        }
        $ret[] = $tmp;
    }
    return $ret;
}

$evalSubmitted = getNonUpcomingTableArray($html, $evals['submitted']);
$surveySubmitted = getNonUpcomingTableArray($html, $surveys['submitted']);
$evalExpired = getNonUpcomingTableArray($html, $evals['expired']);
// note that we have this section for completeness, but currently,
// surveys are removed once past the due date, so unless the student
// made a submission, it won't show up
$surveyExpired = getNonUpcomingTableArray($html, $surveys['expired']);

if ($numOverdue) {
    echo "<div class='eventSummary overdue'>$numOverdue Overdue Event(s)</div>";
}
if ($numDue) {
    echo "<div class='eventSummary pending'>$numDue Pending Event(s) Total</div>";
}
else {
    echo "<div class='eventSummary alldone'>No Event(s) Pending</div>";
}
?>


<h2>Peer Evaluations</h2>
<h3>Due</h3>
<table class='standardtable'>
    <tr>
       <th>Event</th>
       <th>Group</th>
       <th>Course</th>
       <th>Due Date</th>
       <th>Due In/<span class='red'>Late By</span></th>
    </tr>
    <?php
    echo $html->tableCells($evalUpcoming);
    ?>
    <?php if (empty($evalUpcoming)):?>
    <tr><td colspan="5" align="center"><b> No peer evaluations due at this time </b></td></tr>
    <?php endif; ?>
</table>

<h3>Submitted</h3>
<table class='standardtable'>
    <tr>
        <th>Event</th>
        <th>Result <span class='orangered'>Available</span>/End</th>
        <th>Group</th>
        <th>Course</th>
        <th>Due Date</th>
        <th>Date Submitted</th>
    </tr>
<?php echo $html->tableCells($evalSubmitted); ?>
<?php if (empty($evalSubmitted)):?>
    <tr>
        <td colspan="6" align="center">No submitted evaluations available.</td>
    </tr>
<?php endif; ?>
</table>

<?php if (!empty($evalExpired)):?>
<h3>Expired With No Submission</h3>
<table class='standardtable'>
    <tr>
        <th>Event</th>
        <th>Result <span class='orangered'>Available</span>/End</th>
        <th>Group</th>
        <th>Course</th>
        <th>Due Date</th>
    </tr>
<?php echo $html->tableCells($evalExpired); ?>
</table>
<?php endif; ?>

<h2>Surveys</h2>
<h3>Due</h3>
<table class='standardtable'>
    <tr>
       <th>Event</th>
       <th>Course</th>
       <th>Due Date</th>
       <th>Due In/<span class='red'>Late By</span></th>
    </tr>
    <?php
    echo $html->tableCells($surveyUpcoming);
    ?>
    <?php if (empty($surveyUpcoming)):?>
    <tr><td colspan="4" align="center"><b> No survey due at this time </b></td></tr>
    <?php endif; ?>
</table>

<h3>Submitted</h3>
<table class='standardtable'>
    <tr>
        <th>Event</th>
        <th>Course</th>
        <th>Due Date</th>
        <th>Date Submitted</th>
    </tr>
<?php echo $html->tableCells($surveySubmitted); ?>
<?php if (empty($surveySubmitted)):?>
    <tr>
        <td colspan="4" align="center">No submitted surveys available.</td>
    </tr>
<?php endif; ?>
</table>

<?php if (!empty($surveyExpired)):?>
<h3>Expired With No Submission</h3>
<table class='standardtable'>
    <tr>
        <th>Event</th>
        <th>Course</th>
        <th>Due Date</th>
    </tr>
<?php echo $html->tableCells($surveyExpired); ?>
</table>
<?php endif; ?>

</div>
