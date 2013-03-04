<div class='MixevalForm'>
<?php
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
        $output = $html->div('MixevalQuestion',
            $title .
            $instruct .
            $form->textarea($num)
        );
    } else if ($type == 'Sentence') {
        $output = $html->div('MixevalQuestion',
            $title .
            $instruct .
            $form->text($num)
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
            $options[] = $desc['id'];
            $descs[] = $desc['descriptor'];
            $desc['scale_level'] -= $subIf0;
            $mark = $highestMark * ($desc['scale_level'] / $scale);
            $marks[] = $markLabel. round($mark, 2);
            $markLabel = '';
        }
        foreach ($options as &$opt) {
            $opt = "<input type='radio' name='$num' />";
        }
        $output = $html->div('MixevalQuestion',
            $title .
            $instruct .
            $html->tag('table',
                $html->tableCells($descs) .
                $html->tableCells($options) .
                $html->tableCells($marks)
            )
        );
    }
    echo $output;
}
$required  = $html->tag('span', '*', array('class' => 'required orangered'));
echo $html->para('note', $required . ' ' . _t('Indicates response required.'));
?>
</div>