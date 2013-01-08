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
if (!$allMembersCompleted) {
    echo $html->tableHeaders(
        array(__('These people have not yet submitted their evaluations',true)),
        null,
        array('class' => 'red')
    );
    $incompletedMembersArr = array();
    $users = array();
    foreach($inCompletedMembers as $row) {
        $user = $row['User'];
        array_push($incompletedMembersArr, $user['full_name']);
        $users[] = array($user['full_name'] .
            ($row['Role'][0]['id']==4 ? ' (TA)' : ' (student)'));
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
    <th colspan='<?php echo count($groupMembersNoTutors);?>'>
    <?php __('Members Evaluated')?>
    </th>
</tr>
<tr>
    <?php
    foreach ($groupMembersNoTutors as $member) {
        echo '<th>'.$member['User']['full_name'].'</th>';
    }
    ?>
</tr>
<?php
// data processing for scores

// first the individual scores
foreach ($groupMembers as $evaluator) {
    echo '<tr>';
    $evaluatorId = $evaluator['User']['id'];
    echo '<th>'. $evaluator['User']['full_name'].'</th>';
    foreach ($groupMembersNoTutors as $evaluatee) {
        $evaluateeId = $evaluatee['User']['id'];
        if (($evaluatorId == $evaluateeId) && !$event['Event']['self_eval']) {
            // if no self evaluation, no score for self
            echo '<td>-</td>';
        } else {
            // get the score that the evaluator gave to the evaluatee
            if (isset($scoreRecords[$evaluatorId][$evaluateeId])) {
                $score = $scoreRecords[$evaluatorId][$evaluateeId];
                echo '<td>'. (is_numeric($score) ? number_format($score, 2) :
                    $score) . '</td>';
            } else {
                echo '<td>0.00</td>';
            }
        }
    }
    echo '</tr>';
}
?>

<tr>
    <td colspan='<?php echo count($groupMembersNoTutors) + 1;?>'></td>
</tr>

<tr>
    <th><?php __('Total'); ?></th>
<?php
// then the total for each user
$memberEvaluatedCount = $event['Event']['self_eval'] ? count($scoreRecords) :
    count($scoreRecords) - 1;
foreach ($groupMembersNoTutors as $evaluatee) {
    $evaluateeId = $evaluatee['User']['id'];
    if (isset($memberScoreSummary[$evaluateeId])) {
        $totalGrade = number_format($memberScoreSummary[$evaluateeId]['received_total_score'],2);
        $gradePenalty = ($penalties[$evaluateeId] / 100) * $totalGrade;
        $finalGrade = $totalGrade - $gradePenalty;

        (!empty($gradePenalty) && $gradePenalty > 0) ? $stringAddOn = ' - ('."<span class=\"red\">".$gradePenalty."</span>".")".
            "<span class=\"red\">".'*'."</span>".' = '.$finalGrade :
            $stringAddOn = '';

        echo '<td>'.$totalGrade.'</td>';

    } else {
        echo '<td> - </td>';
    }
}
?>
</tr>

<tr>
    <th><?php __('Penalty'); ?> </th>
<?php
// the penalty for each user
$memberEvaluatedCount = ($event['Event']['self_eval'])? count($scoreRecords) :
    count($scoreRecords) - 1;
foreach ($groupMembersNoTutors as $evaluatee) {
    $evaluateeId = $evaluatee['User']['id'];
    if (isset($memberScoreSummary[$evaluateeId])) {
        $totalGrade = number_format($memberScoreSummary[$evaluateeId]['received_total_score'],2);
        $gradePenalty = number_format(($penalties[$evaluateeId] / 100) * $totalGrade, 2);

        (!empty($gradePenalty) && $gradePenalty > 0) ? $stringAddOn = "<span class=\"red\">".$gradePenalty." </span>".
            "(".$penalties[$evaluateeId]."%)":
            $stringAddOn = '-';

        echo '<td>'.$stringAddOn.'</td>'."\n\t\t";

    } else {
        echo '<td> - </td>';
    }
}
?>
</tr>

<tr>
    <th><?php __('Final Mark'); ?></th>
<?php
// the final mark for each user
$memberEvaluatedCount = ($event['Event']['self_eval']) ? count($scoreRecords) :
    count($scoreRecords) - 1;
foreach ($groupMembersNoTutors as $evaluatee) {
    $evaluateeId = $evaluatee['User']['id'];
    if (isset($memberScoreSummary[$evaluateeId])) {
        $totalGrade = number_format($memberScoreSummary[$evaluateeId]['received_total_score'],2);
        $gradePenalty = ($penalties[$evaluateeId] / 100) * $totalGrade;
        $finalGrade = number_format($totalGrade - $gradePenalty, 2);

        echo '<td>'.$finalGrade.'</td>';

    } else {
        echo '<td> - </td>';
    }
}?>
</tr>

<tr>
    <th><?php __('# of Evaluator(s)')?></th>
<?php
// the number of evaluators for each user
$memberEvaluatedCount = ($event['Event']['self_eval']) ? count($scoreRecords) :
    count($scoreRecords) - 1;
foreach ($groupMembersNoTutors as $evaluatee) {
    $evaluateeId = $evaluatee['User']['id'];
    if (isset($memberScoreSummary[$evaluateeId])) {
        if ($event['Event']['self_eval']) {
            // with self_eval on, calculation is simple
            echo '<td>'.($memberEvaluatedCount-(count($inCompletedMembers))).'</td>';
        } else {
            // with self_eval off, we need to handle the case that
            // the member hasn't completed the evaluation
            if (!empty($incompletedMembersArr) && in_array($evaluatee['User']['full_name'], $incompletedMembersArr))
                echo '<td>'.($memberEvaluatedCount-(count($inCompletedMembers))+1).'</td>';
            else
                echo '<td>'.($memberEvaluatedCount-(count($inCompletedMembers))).'</td>';
        }
    } else {
        echo '<td> - </td>';
    }
}
?>
</tr>

<tr>
    <th><?php __('Average Received')?></th>
<?php
// the average for each user
$memberEvaluatedCount = ($event['Event']['self_eval']) ? count($scoreRecords) :
    count($scoreRecords) - 1;
foreach ($groupMembersNoTutors as $evaluatee) {
    $evaluateeId = $evaluatee['User']['id'];
    $totalScore = $memberScoreSummary[$evaluateeId]['received_total_score'];
    $gradePenalty = ($penalties[$evaluateeId] / 100) * $totalScore;
    $finalGrade = $totalScore - $gradePenalty;
    if (isset($memberScoreSummary[$evaluateeId])) {
        if ($event['Event']['self_eval']) {
            // with self_eval on, calculation is simple
            echo '<td >'.number_format($memberScoreSummary[$evaluateeId]['received_total_score'] / ($memberEvaluatedCount-count($inCompletedMembers)), 2).'</td>';
        } else {
            // with self_eval off, we need to handle the case that
            // the member hasn't completed the evaluation
            if (!empty($incompletedMembersArr) && in_array($evaluatee['User']['full_name'], $incompletedMembersArr)) {
                if ($memberEvaluatedCount > count($inCompletedMembers)) {
                    echo '<td>'.number_format($finalGrade / ($memberEvaluatedCount-(count($inCompletedMembers))+1), 2).'</td>';
                } else {
                    echo '<td>'.number_format($finalGrade).'</td>';
                }
            } else {
                if ($memberEvaluatedCount > count($inCompletedMembers)) {
                    echo '<td>'.number_format($finalGrade / ($memberEvaluatedCount-count($inCompletedMembers)), 2).'</td>';
                } else {
                    echo '<td>'.number_format($finalGrade).'</td>';
                }
            }
        }
    } else {
        echo '<td> - </td>';
    }
}
?>
</tr>

<tr class="green">
    <td><?php __('Grade Release')?></td>
<?php
// controls to initiate grade release
$n=0;
for ($m=0; $m<count($groupMembersNoTutors); $m++) {
    if(array_key_exists($groupMembersNoTutors[$m]['User']['id'], $gradeReleaseStatus)){
        $gradeRelease = $gradeReleaseStatus[$groupMembersNoTutors[$m]['User']['id']];
        echo '<td>';
        if (isset($gradeRelease['grade_release']) && $gradeRelease['grade_release']) {?>
            <input type="button" name="Unrelease" value="Unrelease" onclick="location.href='<?php echo $this->webroot.$this->theme.'evaluations/markGradeRelease/'.$event['Event']['id'].';'.$event['Group']['id'].';'.$groupMembersNoTutors[$m]['User']['id'].';'.$event['GroupEvent']['id'].';0'; ?>'">
           <?php } else { ?>
            <input type="button" name="Release" value="Release" onclick="location.href='<?php echo $this->webroot.$this->theme.'evaluations/markGradeRelease/'.$event['Event']['id'].';'.$event['Group']['id'].';'.$groupMembersNoTutors[$m]['User']['id'].';'.$event['GroupEvent']['id'].';1'; ?>'">
<?php }
echo '</td>';
    } else
        echo '<td><input type="button" value="'.__('marks n/a', true).'" disabled /></td>';
}
?>
</tr>

<tr>
	<td colspan="<?php echo count($groupMembersNoTutors) + 1; ?>">
    <form name="evalForm" id="evalForm" method="POST" action="<?php echo $html->url('markEventReviewed') ?>">
    <input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>" />
    <input type="hidden" name="group_id" value="<?php echo $event['Group']['id']?>" />
    <input type="hidden" name="course_id" value="<?php echo $event['Event']['course_id']?>" />
    <input type="hidden" name="group_event_id" value="<?php echo $event['GroupEvent']['id']?>" />
    <?php
    if ($event['GroupEvent']['marked'] == "reviewed") {
        echo "<input type=\"submit\" name=\"mark_not_reviewed\" value=\" ".__('Mark Peer Evaluations as Not Reviewed', true)."\" />";
    }
    else {
        echo "<input type=\"submit\" name=\"mark_reviewed\" value=\" ".__('Mark Peer Evaluations as Reviewed', true)."\" />";
    }
    ?>
    </form>
    </td>
</tr>

</table>

<!-- Comment Section -->

<h2><?php __('Comment Sections')?></h2>
<h4><?php __('Instructions'); ?></h4>
<ul>
<li><?php __('Check the "Released" checkbox and click "Save Changes" to release individual comments , or')?></li>
<li><?php __('Click "Release All" or "Unrelease All" buttons to release or unrelease all comments.')?></li>
</ul>

<form name="evalForm2" id="evalForm2" method="POST" action="<?php echo $html->url('markCommentRelease') ?>">
<?php
// controls to release comments
foreach ($groupMembers as $row) {
    $user = $row['User'];
    echo '<h3>Evaluator: '.$user['full_name'].'</h3>';
    $headers = array(
        __('Evaluatee', true),
        __('Comment', true),
        __('Released', true)
    );
    echo "<table class='standardtable'>";
    echo $html->tableHeaders($headers);
    $comments = array();
    $i = 0;
    foreach ($evalResult[$user['id']] as $row) {
        // We need to skip self-evaluation results
        if (($groupMembersNoTutors[$i]['User']['id']==$user['id']) && (!$event['Event']['self_eval'])) {
            $i++;
        }

        $evaluatee = $groupMembersNoTutors[$i++]['User'];
        $evalMark = isset($row['EvaluationSimple'])? $row['EvaluationSimple']: null;
        echo '<tr>';
        if (isset($evalMark)) {
            echo '<td width="15%">'.$evaluatee['full_name'].'</td>';
            echo '<td>';
            echo (isset($evalMark['comment']))? $evalMark['comment'] : __('No Comments', true);
            echo '</td>' ;
            $checked = $evalMark['release_status'] == 1 ? 'checked' : '';
            // made explicit comparison with 1
            echo '<td width="5%">' . '<input type="checkbox" name="release' .  $evalMark['evaluator']  . '[]" value="' . $evalMark['evaluatee'] . '" '.$checked.'/>';
            echo '<input type="hidden" name="evaluator_ids[]" value="' .  $evalMark['evaluator']  . '" /></td>';
        } else {
            echo '<td colspan="4">'.__('n/a', true).'</td>';
        }
        echo '</tr>';
    }
    echo "</table>";
}
?>
<p style="text-align: center;">
<input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>" />
<input type="hidden" name="group_id" value="<?php echo $event['Group']['id']?>" />
<input type="hidden" name="group_event_id" value="<?php echo $event['GroupEvent']['id']?>" />
<input type="submit" name="submit" value="<?php __('Save Changes')?>" />
<input type="submit" name="submit" value="<?php __('Release All')?>" />
<input type="submit" name="submit" value="<?php __('Unrelease All')?>" />
</p>
</form>
