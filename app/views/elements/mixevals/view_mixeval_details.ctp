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
      else if ($type == 'ScoreDropdown'){
            $basePoints = $ques['MixevalQuestion']['multiplier'];
            $increment = $basePoints/10;
            $total = $evaluatee_count;
            $value = (isset($details[$num])) ? $details[$num]['grade'] : 0;
            $incrementArray = array();
            for($i=0;$i<=$basePoints*$evaluatee_count;$i++){
                $incrementArray[$i] = $i;
            }    
            $output = $html->div('MixevalQuestion',
                 $title .
                 $instruct .
                $form->input('dropdown',
                     array('label' => false, 'default' => $value,'options' => $incrementArray, 'class' => $class,
                    'name' => 'data['.$user['id'].'][EvaluationMixeval]['.$num.'][grade]'))
        );   
        }
    
    echo $output;
}
?>