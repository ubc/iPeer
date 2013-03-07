<div class='MixevalForm'>
<?php
$evaluation = isset($user['Evaluation']) ? $user['Evaluation'] : null;
$details = Set::combine($evaluation['EvaluationMixevalDetail'], '{n}.question_number', '{n}');
echo "<input type='hidden' name=data[$user[id]][Evaluation][evaluatee_id] value='$user[id]'/>";
echo "<input type='hidden' name=data[$user[id]][Evaluation][evaluator_id] value='".User::get('id')."'/>";
echo "<input type='hidden' name=data[$user[id]][Evaluation][event_id] value='".$event['Event']['id']."'/>";
echo "<input type='hidden' name=data[$user[id]][Evaluation][group_event_id] value='".$event['GroupEvent']['id']."'/>";
echo "<input type='hidden' name=data[$user[id]][Evaluation][group_id] value='".$event['Group']['id']."'/>";
foreach ($questions as $ques) {
    $type = $ques['MixevalQuestionType']['type'];
    $num = $ques['MixevalQuestion']['question_num'];
    $instruct = $ques['MixevalQuestion']['instructions'];
    $instruct = $instruct ? $html->para('help green', $instruct) : '';
    $required = (!$ques['MixevalQuestion']['required']) ? '' :
        $html->tag('span', '*', array('class' => 'required orangered floatright'));
    $title = $ques['MixevalQuestion']['title'];
    $title = $html->tag('h3', "$num. $title $required");
    $class = $ques['MixevalQuestion']['required'] ? 'must' : '';
    
    if ($type == 'Paragraph') {
        $value = (isset($details[$num])) ? $details[$num]['question_comment'] : '';
        $output = $html->div('MixevalQuestion',
            $title .
            $instruct .
            $form->textarea('EvaluationMixeval.'.$num.'.question_comment',
                array('default' => $value, 'class' => $class,
                    'name' => 'data['.$user['id'].'][EvaluationMixeval]['.$num.'][question_comment]'))
        );
    } else if ($type == 'Sentence') {
        $value = (isset($details[$num])) ? $details[$num]['question_comment'] : '';
        $output = $html->div('MixevalQuestion',
            $title .
            $instruct .
            $form->input($user['id'].'.EvaluationMixeval.'.$num.'.question_comment',
                array('label' => false, 'default' => $value, 'type' => 'text', 'class' => $class,
                    'name' => 'data['.$user['id'].'][EvaluationMixeval]['.$num.'][question_comment]'))
        );
    } else if ($type == 'Likert') {
        $highestMark = $ques['MixevalQuestion']['multiplier'];
        $scale = count($ques['MixevalQuestionDesc']);
        $options = array();
        $descs = array();
        $marks = array();
        $markLabel = "Mark: ";
        $subIf0 = 0;
        if ($zero_mark) {
            $subIf0 = 1;
            $scale -= $subIf0;
        }
        foreach ($ques['MixevalQuestionDesc'] as $desc) {
            $descs[] = $desc['descriptor'];
            $desc['scale_level'] -= $subIf0;
            $mark = $highestMark * ($desc['scale_level'] / $scale);
            $checked = '';
            if (isset($details[$num])) {
                $checked = ($details[$num]['selected_lom'] == $desc['scale_level']) ? 'checked' : '';
            }
            $option = "<input type='radio' name=data[$user[id]][EvaluationMixeval][$num][grade] value='$mark' $checked ";
            $option .= "onclick=document.getElementById('selected_lom".$num."_".$user['id']."').value=$desc[scale_level] ";
            $option .= "class='".$class."' />";
            $options[] = $option;
            $marks[] = $markLabel. round($mark, 2);
            $markLabel = '';
            $class = '';
        }
        $lom = (isset($details[$num])) ? $details[$num]['selected_lom'] : '';
        $selected = "<input type='hidden' id='selected_lom".$num."_".$user['id']."' name=data[$user[id]][EvaluationMixeval][$num][selected_lom] value='".$lom."' />";
        $output = $html->div('MixevalQuestion',
            $title .
            $instruct .
            $html->tag('table',
                $html->tableCells($descs) .
                $html->tableCells($options) .
                $html->tableCells($marks)
            )
        );
        $output = $output.$selected;
    }
    echo $output;
}
//$required = $html->tag('span', '*', array('class' => 'required orangered'));
//$save = isset($user['Evaluation']) ? 'Edit This Section' : 'Save This Section';
//echo $html->para('note', $required . ' ' . _t('Indicates response required.'));
//echo $html->div('center', $form->submit(__($save, true), array('div' => 'editSection')));
//echo '<br><center>'._t('Make sure you save this section before moving on to the other ones!').'</center><br><br>';
//echo $form->end();
?>
</div>