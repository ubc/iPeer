<?php
$gradeReleased = array_product(Set::extract($evalResult[User::get('id')], '/EvaluationMixeval/grade_release')) ||
    $event['Event']['auto_release'];
$commentReleased = array_sum(Set::extract($evalResult[User::get('id')], '/EvaluationMixevalDetail/comment_release')) ||
    $event['Event']['auto_release'];
?>
<!-- Render Event Info table -->
<?php echo $this->element('evaluations/view_event_info', array('controller'=>'evaluations', 'event'=>$event));?>

<h2><?php __('Summary')?></h2>
<table class="standardtable">
<tr>
    <th><?php __('Rating')?></th>
</tr>
<tr>
    <td>
    <?php
    if ($gradeReleased) {
        if (isset($memberScoreSummary[User::get('id')])) {
            $receivedAvePercent = number_format($memberScoreSummary[User::get('id')]['received_ave_score']/$mixeval['Mixeval']['total_marks'] * 100);
            $receivedAvePercent = $receivedAvePercent * (100 - $penalty)/100;
        } else {
            $receivedAvePercent = 0;
        }
        $finalAvg = $memberScoreSummary[User::get('id')]['received_ave_score'] - number_format($avePenalty, 2);
        (number_format($avePenalty, 2) > 0) ? ($stringAddOn = ' - '.'('.'<font color=\'red\'>'.number_format($avePenalty, 2).'</font>'.
            ')'.'<font color=\'red\'>*</font>'.' = '.number_format($finalAvg, 2)) : $stringAddOn = '';

        echo number_format($memberScoreSummary[User::get('id')]['received_ave_score'], 2).$stringAddOn;
        number_format($avePenalty, 2) > 0 ? $penaltyNote = '&nbsp &nbsp &nbsp &nbsp &nbsp ( )'.'<font color=\'red\'>*</font>'.' : '.$penalty.
            '% late penalty. ' : $penaltyNote = '';
        echo $penaltyNote;
        echo ' ('.number_format($receivedAvePercent).'%)';
    } else {
        echo __('Not Released', true);
    }
    ?>
    </td>
</tr>
</table>

<div id='mixeval_result'>
<?php
$questions = Set::combine($mixeval['MixevalQuestion'], '{n}.question_num', '{n}');
shuffle($evalResult[User::get('id')]);
$zero_mark = $mixeval['Mixeval']['zero_mark'];
foreach ($evalResult[User::get('id')] as $eval) {
    foreach ($eval['EvaluationMixevalDetail'] as $detail) {
        $questions[$detail['question_number']]['Submissions'][] = $detail;
    }
}
if ($mixeval['Mixeval']['peer_question'] > 0) {
    $params = array('controller'=>'evaluations', 'questions'=>$questions, 'zero_mark'=>$zero_mark, 'evaluatee' => User::get('id'),
        'gradeReleased'=>$gradeReleased, 'commentReleased'=>$commentReleased, 'details'=>$event['Event']['enable_details'],
        'peer_eval' => 1, 'title' => __('Questions', true), 'instructorMode' => 0);
    echo $this->element('evaluations/mixeval_details', $params);
}

if ($mixeval['Mixeval']['self_eval'] > 0 && $event['Event']['self_eval']) {
    $params = array('controller'=>'evaluations', 'questions'=>$questions, 'zero_mark'=>$zero_mark, 'evaluatee' => User::get('id'),
        'gradeReleased'=> 1, 'commentReleased'=> 1, 'details'=>$event['Event']['enable_details'],
        'peer_eval' => 0, 'title' => __('Self-Evaluation', true), 'instructorMode' => 0);
    echo $this->element('evaluations/mixeval_details', $params);
}
?>
</div>