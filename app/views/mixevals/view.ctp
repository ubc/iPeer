<div class='MixevalView'>
<h2>Info</h2>
<dl>
    <dt><?php __('Name'); ?></dt>
    <dd><?php echo $mixeval['name']; ?></dd>
    <dt><?php __('Availability'); ?></dt>
    <dd><?php echo ucfirst($mixeval['availability']); ?></dd>
    <dd>
        <span class='help'>
        <?php __('Public Allows Mixed Evaluation Sharing Amongst Instructors')?>        </span>
    </dd>
    <dt>Zero Mark</dt>
    <dd><?php echo $mixeval['zero_mark'] ? 'On' : 'Off';?></dd>
    <dd>
        <span class='help'>
        <?php __('If Enabled, No Marks Given for Level of Scale of 1')?>
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
        $scale = count($q['Description']);
        $options = array();
        $descs = array();
        $marks = array();
        $markLbl = "Mark: ";
        foreach ($q['Description'] as $desc) {
            $options[] = $desc['id'];
            $descs[] = $desc['descriptor'];
            $marks[] = $markLbl. $highestMark * ($desc['scale_level'] / $scale);
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

// reconstruct the marker since the one used in $qtitle had class floatright
$required = $html->tag('span', '*', array('class' => 'required orangered'));
echo $html->para('note', $required . ' ' . _('Indicates response required.'));
echo $html->para('marks', _('Total Marks') . ": $totalMarks");

?>
</div>
