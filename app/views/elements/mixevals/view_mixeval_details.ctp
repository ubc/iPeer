<div class='MixevalForm'>
<?php
$evaluation = isset($user['Evaluation']) ? $user['Evaluation'] : null;
$details = Set::combine($evaluation['EvaluationMixevalDetail'], '{n}.question_number', '{n}');
echo $this->Form->create('EvaluationMixeval', array(
    'url' => $html->url('makeEvaluation') . '/'.$event['Event']['id'].'/'.$event['Group']['id']));
echo "<input type='hidden' name=data[Evaluation][evaluatee_id] value='$user[id]'/>";
echo "<input type='hidden' name=data[Evaluation][evaluator_id] value='".User::get('id')."'/>";
echo "<input type='hidden' name=data[Evaluation][event_id] value='".$event['Event']['id']."'/>";
echo "<input type='hidden' name=data[Evaluation][group_event_id] value='".$event['GroupEvent']['id']."'/>";
echo "<input type='hidden' name=data[Evaluation][group_id] value='".$event['Group']['id']."'/>";
foreach ($questions as $ques) {
    $type = $ques['MixevalQuestionType']['type'];
    $num = $ques['MixevalQuestion']['question_num'];
    $instruct = $ques['MixevalQuestion']['instructions'];
    $instruct = $instruct ? $html->para('help green', $instruct) : '';
    $required = (!$ques['MixevalQuestion']['required']) ? '' :
        $html->tag('span', '*', array('class' => 'required orangered floatright'));
    $title = $ques['MixevalQuestion']['title'];
    $title = $html->tag('h3', "$num. $title $required");
    
    if ($type == 'Paragraph') {
        $value = (isset($details[$num])) ? $details[$num]['question_comment'] : '';
        $output = $html->div('MixevalQuestion',
            $title .
            $instruct .
            $form->textarea('EvaluationMixeval.'.$num.'.question_comment',
                array('default' => $value))
        );
    } else if ($type == 'Sentence') {
        $value = (isset($details[$num])) ? $details[$num]['question_comment'] : '';
        $output = $html->div('MixevalQuestion',
            $title .
            $instruct .
            $form->input('EvaluationMixeval.'.$num.'.question_comment',
                array('label' => false, 'default' => $value))
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
            $option = "<input type='radio' name=data[EvaluationMixeval][$num][grade] value='$mark' $checked ";
            $option .= "onclick=document.getElementById('selected_lom".$num."').value=$desc[scale_level] />";
            $options[] = $option;
            $marks[] = $markLabel. round($mark, 2);
            $markLabel = '';
        }
        $selected = "<input type='hidden' id='selected_lom".$num."' name=data[EvaluationMixeval][$num][selected_lom] value=''/>";
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
$required  = $html->tag('span', '*', array('class' => 'required orangered'));
echo $html->para('note', $required . ' ' . _t('Indicates response required.'));
echo $html->div('center', $form->submit(__('Save', true), array('div' => false)));
echo $form->end();
?>
</div>