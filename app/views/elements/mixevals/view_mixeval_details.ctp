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
    $title = $html->tag('h3', "$peerNum. $title $required", array('class' => 'question-title', 'id' => 'q_'.$user['id'].'_'.$num));
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
        $markLabel = __("Mark", true).": ";

        foreach ($ques['MixevalQuestionDesc'] as $key => $desc) {
            $descs[] = $desc['descriptor'];
            if ($desc['scale_level'] == 0) {
                // upgraded from pre 3.1, scale_levels are set to 0. So use $key as level, scale_level starts from 1
                $desc['scale_level'] = $key + 1;
            }
            $mark = $highestMark * (($desc['scale_level'] - $zero_mark) / ($scale - $zero_mark));
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
        
        // Enhancement #520 - "hide" numerical designations of categories
        // Depending on setting shows/hides marks
        if($ques['MixevalQuestion']['show_marks'] == 0){
            $showMarks = false;
        }
        else{
            $showMarks = true;
        }
        $questionContent = $html->tableCells($descs) . $html->tableCells($options);
        if($showMarks){
            $questionContent .= $html->tableCells($marks);
        }
        
        $output = $html->div('MixevalQuestion',
            $title .
            $instruct .
            $html->tag('table',
                $questionContent
                /*
                $html->tableCells($descs) .
                $html->tableCells($options) .
                $html->tableCells($marks)
                */
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
