<div class='MixevalView'>
<h2>Info</h2>
<dl>
    <dt><?php __('Name'); ?></dt>
    <dd><?php echo $mixeval['name']; ?></dd>
    <dt><?php __('Availability'); ?></dt>
    <dd><?php echo ucfirst($mixeval['availability']); ?></dd>
    <dd>
        <span class='help'>
        <?php
        __('Public lets you share this mixed evaluation with other instructors.');
        ?>
        </span>
    </dd>
    <dt>Zero Mark</dt>
    <dd><?php echo $mixeval['zero_mark'] ? 'On' : 'Off';?></dd>
    <dd>
        <span class='help'>
        <?php __('Start marks from zero for all Likert questions.')?>
        </span>
    </dd>
    <dt>Creator</dt>
    <dd><?php echo $mixeval['creator']; ?></dd>
    <dt>Created</dt>
    <dd><?php echo $mixeval['created']; ?></dd>
    <dt>Modified</dt>
    <dd><?php echo $mixeval['modified']; ?></dd>
</dl>
<h2>Questions</h2> 
<?php

$totalMarks = 0;

foreach ($questions as $q) {
    $qtype = $q['MixevalQuestionType']['type'];
    $qnum = $q['MixevalQuestion']['question_num'];
    // construct the instructions paragraph if needed
    $qinstruct = $q['MixevalQuestion']['instructions'];
    $qinstruct = $qinstruct ? $html->para('help green', $qinstruct) : '';
    // construct the required marker if needed
    $qrequired = "";
    if ($q['MixevalQuestion']['required']) {
        $qrequired = $html->tag('span', '*', 
            array('class' => 'required orangered floatright'));
    }
    // construct the question prompt header
    $qtitle = $q['MixevalQuestion']['title'];
    $qtitle = $html->tag('h3', "$qnum. $qtitle $qrequired");

    // construct the question for display depending on the question type
    if ($qtype == 'Paragraph') {
        $out = $html->div('MixevalQuestion',
            $qtitle .
            $qinstruct . 
            $form->textarea($qnum)
        );
    }
    else if ($qtype == 'Sentence') {
        $out = $html->div('MixevalQuestion',
            $qtitle .
            $qinstruct . 
            $form->text($qnum)
        );
    }
    else if ($qtype == 'Likert') {
        $highestMark = $q['MixevalQuestion']['multiplier'];
        $totalMarks += $highestMark;
        $scale = count($q['MixevalQuestionDesc']);
        $options = array();
        $descs = array();
        $marks = array();
        $markLbl = "Mark: ";
        // for zero marks, we need to shift the scale so that marks start at 0
        $subIf0 = 0;
        if ($mixeval['zero_mark']) {
            $subIf0 = 1;
            $scale -= $subIf0;
        }
        foreach ($q['MixevalQuestionDesc'] as $desc) {
            $options[] = $desc['id'];
            $descs[] = $desc['descriptor'];
            $desc['scale_level'] -= $subIf0;
            $mark = $highestMark * ($desc['scale_level'] / $scale);
            $marks[] = $markLbl. round($mark, 2);// max 2 decimal places
            $markLbl = "";
        }
        foreach ($options as &$opt) {
            $opt = "<input type='radio' name='$qnum' />";
        }
        $out = $html->div('MixevalQuestion',
            $qtitle .
            $qinstruct . 
            $html->tag('table',
                $html->tableCells($descs) . 
                $html->tableCells($options) .
                $html->tableCells($marks)
            )
        );
    }
    echo $out;
}

// reconstruct the req marker since the one used in $qtitle had class floatright
$required = $html->tag('span', '*', array('class' => 'required orangered'));
echo $html->para('note', $required . ' ' . _t('Indicates response required.'));
echo $html->para('marks', _t('Total Marks') . ": $totalMarks");

?>
</div>
