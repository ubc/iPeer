<?php
$addOn = '';
if (!$gradeReleased && !$commentReleased) {
    $addOn = ' - <font color="red">'._t(' Comments/Grades Not Released Yet').'</font>';
} else if (!$gradeReleased) {
    $addOn = ' - <font color="red">'._t(' Grades Not Released Yet').'</font>';
} else if (!$commentReleased) {
    $addOn = ' - <font color="red">'._t(' Comments Not Released Yet').'</font>';
}
$header = _t('Questions').$addOn;

echo $html->tag('h2', $header);
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
        
            if ($type == '1') {
                $step = $multiplier / ($scale - $zero_mark);
                $start = $zero_mark ? 0 : $step;
                $options = range($start, $multiplier, $step);
                $grade = '<label class="grade">'._t('Grade: ');
                $grade .= $sub['grade'].' / '.$multiplier.'</label>';
                $grade .= (empty($descriptors[$sub['selected_lom']])) ? '' :
                    '<label class="desc">('.$descriptors[$sub['selected_lom']].')</label>';
                echo $form->input('ques_'.$qnum.'_'.$num, array(
                    'type' => 'radio',
                    'options' => $options,
                    'disabled' => true,
                    'default' => $sub['selected_lom'] - 1,
                    'before' => '<li>',
                    'after' => $grade.'</li>'
                ));
            } else {
                echo '<li>'.$sub['question_comment'].'</li>';
            }
        }
        echo '</ul>';
    } else {
        echo 'N/A';
    }
}
echo '<br>';
?>