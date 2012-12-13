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
?>

<h3>Peer Evaluations Due</h3>
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

<h3>Surveys Due</h3>
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
    <tr><td colspan="5" align="center"><b> No survey due at this time </b></td></tr>
    <?php endif; ?>
</table>

<?php
function getSubmittedTableArray($html, $events) {
    $ret = array();
    foreach ($events as $event) {
        if (!$event['Event']['is_released'] &&
            !$event['Event']['is_result_released']
        ){ // an event that has expired and is no longer relevant
            continue;
        }
        $tmp = array();
        if (isset($event['Event']['is_result_released'])) {
            $tmp[] = $html->link($event['Event']['title'],
                '/evaluations/studentViewEvaluationResult/' .
                $event['Event']['id'] . '/' . $event['Group']['id']);
            $tmp[] = $event['Event']['result_release_date_end'];
        }
        elseif ($event['Event']['event_template_type_id'] == 3) {
            $tmp[] = $html->link($event['Event']['title'],
                '/evaluations/studentViewEvaluationResult/' .
                $event['Event']['id']);
        }
        else {
            $tmp[] = $event['Event']['title'];
            $tmp[] = "<span class='orangered'>" .
                $event['Event']['result_release_date_begin'] . "</span>";
        }
        if (isset($event['Group']['group_name'])) {
            $tmp[] = $event['Group']['group_name'];
        }
        $tmp[] = $event['Course']['course'];
        $tmp[] = Toolkit::formatDate($event['Event']['due_date']);
        $tmp[] = $event['EvaluationSubmission'][0]['date_submitted'];
        $ret[] = $tmp;
    }
    return $ret;
}

$evalSubmitted = getSubmittedTableArray($html, $evals['submitted']);
$surveySubmitted = getSubmittedTableArray($html, $surveys['submitted']);
?>

<h3>Peer Evaluations Submitted</h3>
<table class='standardtable'>
    <tr>
        <th>Event</th>
        <th>Viewable <span class='orangered'>Start</span>/End</th>
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

<h3>Surveys Submitted</h3>
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
        <td colspan="5" align="center">No submitted surveys available.</td>
    </tr>
<?php endif; ?>
</table>

