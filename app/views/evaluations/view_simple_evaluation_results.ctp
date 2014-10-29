<h2><?php __('Evaluation Event Detail')?></h2>
<!-- Event Details Table -->
<table class="standardtable">
<tr>
    <th><?php __('Event Name')?></th>
    <th><?php __('Group')?></th>
    <th><?php __('Due Date')?></th>
    <th><?php __('Self-Evaluation')?></th>
</tr>
<tr>
    <td><?php echo $event['Event']['title'] ?></td>
    <td><?php echo $event['Group']['group_name'] ?></td>
    <td><?php echo Toolkit::formatDate(date("Y-m-d H:i:s", strtotime($event['Event']['due_date']))) ?></td>
    <td><?php echo ($event['Event']['self_eval']) ? 'Yes' : 'No'?></td>
</tr>
</table>

<table class="standardtable">
<tr>
    <th><?php __('Description')?></th>
</tr>
<tr>
    <td><?php echo $event['Event']['description'] ?></td>
</tr>
</table>

<h2><?php __('Summary')?></h2>
<!-- Users who haven't done the evaluation yet table -->
<table class="standardtable">
<?php
if (!empty($results['Incomplete'])) {
    echo $html->tableHeaders(
        array(__('Have not submitted their evaluations', true)),
        null,
        array('class' => 'red')
    );
    $users = array();
    foreach($results['Incomplete'] as $userid) {
        $user = $results['evaluators'][$userid];
        $users[] = array($user['User']['full_name'] .
            ($user['Role'][0]['id']==4 ? ' (TA)' : ' (student)'));
    }
    echo $html->tableCells($users);
}
?>
</table>

<!-- Users who have left the group -->
<table class="standardtable">
<?php
if (!empty($results['Dropped'])) {
    echo $html->tableHeaders(
        array(__('Left the group, but had submitted or were evaluated', true)),
        null,
        array('class' => 'blue')
    );
    $users = array();
    foreach($results['Dropped'] as $userid) {
        $user = $results['evaluators'][$userid];
        $users[] = array($user['User']['full_name'] .
            ($user['Role'][0]['id']==4 ? ' (TA)' : ' (student)'));
    }
    echo $html->tableCells($users);
}
?>
</table>

<h3><?php __('Evaluation Results')?></h3>
<!-- Point Distribution Table -->
<table class='standardtable'>
<tr>
    <th rowspan="2"><?php __('Evaluator')?></th>
    <th colspan='<?php echo count($results['evaluatees']);?>'>
    <?php __('Members Evaluated')?>
    </th>
</tr>
<tr>
    <?php
    foreach ($results['evaluatees'] as $member) {
        $class = "";
        if (in_array($member['User']['id'], $results['Dropped'])) {
            $class = "class='blue'";
        }
        else if (in_array($member['User']['id'], $results['Incomplete'])) {
            $class = "class='red'";
        }
        echo "<th $class>" . $member['User']['full_name'] . '</th>';
    }
    ?>
</tr>
<?php
// data processing for scores

// first the individual scores
foreach ($results['evaluators'] as $evaluatorId => $evaluator) {
    $class = "";
    if (in_array($evaluatorId, $results['Dropped'])) {
        $class = "class='blue'";
    }
    else if (in_array($evaluatorId, $results['Incomplete'])) {
        $class = "class='red'";
    }
    echo '<tr>';
    echo "<th $class>" . $evaluator['User']['full_name'] . '</th>';
    foreach ($results['evaluatees'] as $evaluatee) {
        $evaluateeId = $evaluatee['User']['id'];
        $score = '-';
        // get the score that the evaluator gave to the evaluatee
        if (isset($results['Submissions'][$evaluatorId][$evaluateeId])) {
            $score = $results['Submissions'][$evaluatorId]
                [$evaluateeId]['score'];
            $score = is_numeric($score) ? number_format($score, 2) : $score;
        }
        echo "<td>$score</td>";
    }
    echo '</tr>';
}
?>

<tr>
    <td colspan='<?php echo count($results['evaluatees']) + 1;?>'></td>
</tr>

<tr>
    <th><?php __('Total'); ?></th>
<?php
// then the total for each user
foreach ($results['evaluatees'] as $evaluatee) {
    $evaluateeId = $evaluatee['User']['id'];
    $totalGrade = '-';
    if (isset($results['TotalGrades'][$evaluateeId])) {
        $totalGrade = number_format($results['TotalGrades'][$evaluateeId], 2);
    }
    echo "<td>$totalGrade</td>";
}
?>
</tr>

<tr>
    <th><?php __('Penalty'); ?> </th>
<?php
// the penalty for each user
foreach ($results['evaluatees'] as $evaluatee) {
    $evaluateeId = $evaluatee['User']['id'];
    $disp = '-';
    if (isset($results['TotalGrades'][$evaluateeId])) {
        $totalGrade = $results['TotalGrades'][$evaluateeId];
        $penaltyPct = $results['Penalties'][$evaluateeId];
        $gradePenalty = ($penaltyPct / 100) * $totalGrade;
        if ($gradePenalty > 0) {
            $gradePenalty = number_format($gradePenalty, 2);
            $disp = "<span class='red'>$gradePenalty</span> ($penaltyPct%)";
        }
    }
    echo "<td>$disp</td>";
}
?>
</tr>

<tr>
    <th><?php __('Final Mark'); ?></th>
<?php
// the final mark for each user
foreach ($results['evaluatees'] as $evaluatee) {
    $evaluateeId = $evaluatee['User']['id'];
    $finalGrade = '-';
    if (isset($results['FinalGrades'][$evaluateeId])) {
        $finalGrade = number_format($results['FinalGrades'][$evaluateeId], 2);
    }
    echo "<td>$finalGrade</td>";
}
?>
</tr>

<tr>
    <th><?php __('# of Evaluator(s)')?></th>
<?php
// the number of evaluators for each user
foreach ($results['evaluatees'] as $evaluatee) {
    $evaluateeId = $evaluatee['User']['id'];
    $numEvaluators = '-';
    if (isset($results['NumEvaluators'][$evaluateeId])) {
        $numEvaluators = $results['NumEvaluators'][$evaluateeId];
    }
    echo "<td>$numEvaluators</td>";
}
?>
</tr>

<tr>
    <th><?php __('Average Received')?></th>
<?php
// the average for each user
foreach ($results['evaluatees'] as $evaluatee) {
    $evaluateeId = $evaluatee['User']['id'];
    $avgScore = '-';
    if (isset($results['NumEvaluators'][$evaluateeId])) {
        $totalGrade = (float) $results['TotalGrades'][$evaluateeId];
        $numEvaluators = $results['NumEvaluators'][$evaluateeId];
        $penalty = (1 - $results['Penalties'][$evaluateeId] / 100);
        $avgScore = $totalGrade * $penalty / $numEvaluators;
        $avgScore = number_format($avgScore, 2);
    }
    echo "<td>$avgScore</td>";
}
?>
</tr>

<?php if ($viewReleaseBtns) { ?>
<tr class="green">
    <td><?php __('Grade Release')?></td>
<?php
// controls to initiate grade release
foreach ($results['evaluatees'] as $evaluatee) {
    $evaluateeId = $evaluatee['User']['id'];
    $button = $form->button(__('N/A', true), array('disabled' => 'disabled'));
    if (array_key_exists($evaluateeId, $results['ReleaseStatus'])) {
        $status = $results['ReleaseStatus'][$evaluateeId];
        $buttonName = "Release";
        $releaseAction = '1';
        if (isset($status['grade_release']) && $status['grade_release']) {
            $buttonName = "Unrelease";
            $releaseAction = '0';
        }
        $eventId = $event['Event']['id'];
        $groupId = $event['Group']['id'];
        $grpEventId = $event['GroupEvent']['id'];
        $button = $form->button(
            $buttonName,
            array(
                'onclick' => "location.href='".$this->Html->url('/evaluations/markGradeRelease')."/$grpEventId/$releaseAction/$evaluateeId'"
            )
        );
    }
    echo "<td>$button</td>";
}
?>
</tr>
<?php } ?>

<tr>
	<td colspan="<?php echo count($results['evaluatees']) + 1; ?>">
    <form name="evalForm" id="evalForm" method="POST" action="<?php echo $html->url('markEventReviewed') ?>">
    <input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>" />
    <input type="hidden" name="group_id" value="<?php echo $event['Group']['id']?>" />
    <input type="hidden" name="course_id" value="<?php echo $event['Event']['course_id']?>" />
    <input type="hidden" name="group_event_id" value="<?php echo $event['GroupEvent']['id']?>" />
    <?php
    if ($event['GroupEvent']['marked'] == "reviewed") {
        echo "<input type='submit' name='mark_not_reviewed' value=' ".__('Mark Peer Evaluations as Not Reviewed', true)."' />";
    }
    else {
        echo "<input type='submit' name='mark_reviewed' value=' ".__('Mark Peer Evaluations as Reviewed', true)."' />";
    }
    ?>
    </form>
    </td>
</tr>

</table>

<!-- Comment Section -->

<h2><?php __('Comment Sections')?></h2>
<?php if ($viewReleaseBtns) {
    echo '<h3>'.__('Instructions', true).'</h3>';
    echo '<ul class="instructions">';
    echo '<li>'.__('Check the "Released" checkbox and click "Save Changes" to release individual comments , or', true).'</li>';
    echo '<li>'.__('Click "Release All" or "Unrelease All" buttons to release or unrelease all comments.', true).'</li>';
    echo '</ul>';
} ?>

<form name="evalForm2" id="evalForm2" method="POST" action="<?php echo $html->url('markCommentRelease') ?>">
<?php
// controls to release comments
if (isset($results['Submissions'])) {
    foreach ($results['Submissions'] as $evaluatorId => $evaluator) {
        $class = "";
        if (in_array($evaluatorId, $results['Dropped'])) {
            $class = "class='blue'";
        }
        $evaluatorInfo = $results['evaluators'][$evaluatorId]['User'];
        echo "<h3 $class>Evaluator: ".$evaluatorInfo['full_name'].'</h3>';
        $headers = array(__('Evaluatee', true), __('Comment', true));
        if ($viewReleaseBtns) {
            $headers[] = __('Released', true);
        }
        echo "<table class='standardtable'>";
        echo $html->tableHeaders($headers);
        $cells = array();
        foreach ($evaluator as $evaluateeId => $evalMark) {
            $class = "";
            if (in_array($evaluateeId, $results['Dropped'])) {
                $class = "blue";
            }
            else if (in_array($evaluateeId, $results['Incomplete'])) {
                $class = "red";
            }
            $evaluateeInfo = $results['evaluators'][$evaluateeId]['User'];
            $tmp = array();
            $tmp[] = array($evaluateeInfo['full_name'],
                array('width' => '15%', 'class' => $class));
            $tmp[] = isset($evalMark['comment']) ? $evalMark['comment'] : '-';
            
            if ($viewReleaseBtns) {
                $releaseChk = "";
                $releaseChkParams = array(
                    'value' => $evalMark['evaluatee'],
                    'hiddenField' => false,
                    'name' => 'release' . $evalMark['evaluator'] . '[]'
                );
                if ($evalMark['release_status'] == 1) {
                    $releaseChkParams['checked'] = 'checked';
                }
                $releaseChk = $form->checkbox($releaseChkParams['name'],
                    $releaseChkParams);
                $releaseChk .= $form->hidden("evaluator_ids[]", array(
                    'value' => $evalMark['evaluator'], 'name' => 'evaluator_ids[]'));
                $tmp[] = array($releaseChk, array('width' => '5%'));
            }
            $cells[] = $tmp;
        }
        echo $html->tableCells($cells);
        echo "</table>";
    }
}
?>
<p style="text-align: center;">
<?php if ($viewReleaseBtns) { ?>
<input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>" />
<input type="hidden" name="group_id" value="<?php echo $event['Group']['id']?>" />
<input type="hidden" name="group_event_id" value="<?php echo $event['GroupEvent']['id']?>" />
<input type="submit" name="submit" value="<?php __('Save Changes')?>" />
<input type="submit" name="submit" value="<?php __('Release All')?>" />
<input type="submit" name="submit" value="<?php __('Unrelease All')?>" />
<?php } ?>
</p>
</form>
