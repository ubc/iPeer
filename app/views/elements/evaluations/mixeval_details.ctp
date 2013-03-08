<?php
$addOn = '';
if (!$gradeReleased && !$commentReleased && $details) {
    $addOn = ' - <font color="red">'._t(' Comments/Grades Not Released Yet').'</font>';
} else if (!$gradeReleased) {
    $addOn = ' - <font color="red">'._t(' Grades Not Released Yet').'</font>';
} else if (!$commentReleased && $details) {
    $addOn = ' - <font color="red">'._t(' Comments Not Released Yet').'</font>';
}
$header = _t('Questions').$addOn;

echo $html->tag('h2', $header);
if ($details) {
    foreach ($questions as $qnum => $ques) {
        echo $html->tag('h3', "$qnum. $ques[title]");
        $type = $ques['mixeval_question_type_id'];
        $multiplier = $ques['multiplier'];
        $scale = $ques['scale_level'];
        $descriptors = Set::combine($ques['MixevalQuestionDesc'], '{n}.scale_level', '{n}.descriptor');
        if (isset($ques['Submissions'])) {
            echo '<ul>';
            foreach ($ques['Submissions'] as $num => $sub) {
                if ($type == '1' && !$gradeReleased) {
                    echo '<li>'._t('Grades Not Released Yet').'</li>';
                    break;
                } else if (in_array($type, array(2, 3)) && !$commentReleased) {
                    echo '<li>'._t('Comments Not Released Yet').'</li>';
                    break;
                }
                $name = '';
                if (isset($names)) {
                    $class = in_array($sub['evaluator'], $notInGroup) ? 'blue' : 'name';
                    $name = '<label class='.$class.'>'.$names[$sub['evaluator']].':</label>';
                }
                if ($type == '1') {
                    $step = $multiplier / ($scale - $zero_mark);
                    $start = $zero_mark ? 0 : $step;
                    $options = range($start, $multiplier, $step);
                    $grade = '<label class="grade">'._t('Grade: ');
                    $grade .= $sub['grade'].' / '.$multiplier.'</label>';
                    $grade .= (empty($descriptors[$sub['selected_lom']])) ? '' :
                        '<label class="desc">('.$descriptors[$sub['selected_lom']].')</label>';
                    echo $form->input('ques_'.$qnum.'_'.$num.'_'.$evaluatee, array(
                        'type' => 'radio',
                        'options' => $options,
                        'disabled' => true,
                        'default' => $sub['selected_lom'] - 1,
                        'before' => '<li>'.$name,
                        'after' => $grade.'</li>'
                    ));
                } else {
                    echo '<li>'.$name.$sub['question_comment'].'</li>';
                }
            }
            echo '</ul>';
        } else {
            echo '<ul><li>N/A</li></ul>';
        }
    }
    echo '<br>';
} else {
    foreach ($questions as $qnum => $ques) {
        if ($ques['mixeval_question_type_id'] == '1') {
            echo $html->tag('h3', "$qnum. $ques[title]");
            if (isset($ques['Submissions'])) {
                echo '<ul>';
                if (!$gradeReleased) {
                    echo '<li>'._t('Grades Not Released Yet').'</li></ul>';
                    continue;
                }
                $grades = Set::extract('/grade', $ques['Submissions']);
                $average = array_sum($grades) / count($grades);
                echo '<li>Average: '.$average.' / '.$ques['multiplier'].'</li></ul>';
            } else {
                echo '<ul><li>N/A</li></ul>';
            }
        }
    }
    echo '<br>';
}
?>