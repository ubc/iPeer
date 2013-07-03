<?php
$evaluation = isset($user['Evaluation']) ? $user['Evaluation'] : null;
$self = isset($self) ? $self : null;
$evaluation = $self_eval ? $self : $evaluation;
$details = Set::combine($evaluation['EvaluationMixevalDetail'], '{n}.question_number', '{n}');
$peerNum = 1;
echo "<input type='hidden' name=data[$user[id]][".$eval."][evaluatee_id] value='$user[id]'/>";
echo "<input type='hidden' name=data[$user[id]][".$eval."][evaluator_id] value='".User::get('id')."'/>";
echo "<input type='hidden' name=data[$user[id]][".$eval."][event_id] value='".$event['Event']['id']."'/>";
echo "<input type='hidden' name=data[$user[id]][".$eval."][group_event_id] value='".$event['GroupEvent']['id']."'/>";
echo "<input type='hidden' name=data[$user[id]][".$eval."][group_id] value='".$event['Group']['id']."'/>";
foreach ($questions as $ques) {
    if ($ques['MixevalQuestion']['self_eval'] != $self_eval) {
        continue; // skip questions not in this section (eg. self eval, peer eval)
    }
    $type = $ques['MixevalQuestionType']['type'];
    $num = $ques['MixevalQuestion']['question_num'];
    $instruct = $ques['MixevalQuestion']['instructions'];
    $instruct = $instruct ? $html->para('help green', $instruct) : '';
    $required = (!$ques['MixevalQuestion']['required']) ? '' :
        $html->tag('span', '*', array('class' => 'required orangered floatright'));
    $title = $ques['MixevalQuestion']['title'];
    $title = $html->tag('h3', "$peerNum. $title $required");
    $class = $ques['MixevalQuestion']['required'] ? 'must' : '';
    $peerNum++;

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
    } else if ($type == 'ScoreDropdown') {
        $basePoints = $ques['MixevalQuestion']['multiplier']; //Base Points is set as a Constant to 10 for since it is supposed to be used for TBL-Default
        $increment = $basePoints/10;
        $total = $evaluatee_count;
        $value = (isset($details[$num])) ? $details[$num]['grade'] : 0;
        $max = $basePoints * $evaluatee_count;
        $output = $html->div('MixevalQuestion',
             $title .
             $instruct .
            $form->input('dropdown',
                 array('label' => false, 'default' => $value,'options' => range(0, $max), 'class' => $class,
                'name' => 'data['.$user['id'].'][EvaluationMixeval]['.$num.'][grade]'))
        );
    }

    echo $output;
}
