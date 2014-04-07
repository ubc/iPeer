<form name="evalForm<?php echo $evaluatee ?>" id="evalForm<?php echo $evaluatee ?>" method="POST" action="<?php echo $html->url('markCommentRelease') ?>">
<input type="hidden" name="event_id" value="<?php echo $event['Event']['id']?>">
<input type="hidden" name="group_id" value="<?php echo $event['Group']['id'] ?>" />
<input type="hidden" name="evaluatee" value="<?php echo $evaluatee?>">
<input type="hidden" name="group_event_id" value="<?php echo $event['GroupEvent']['id']?>">

<table class="standardtable">
<?php
echo $html->tableHeaders($headers);
$empty = $rubric['lom_max'];
$col = $rubric['criteria'] + 1;
foreach ($result AS $evaluator => $row) {
    $class = in_array($evaluator, $notInGroup) ? ' class="blue" ' : ' ';
    echo "<tr><td".$class."width='15%'>".$memberList[$evaluator]."</td>";
    $comment = array_pop($row);
    foreach ($row as $num => $grade) {
        echo '<td valign="middle"><br />';
        //Points Detail
        echo "<strong>".__('Points', true).": </strong>";
        $lom = $grade["grade"];
        
        for ($v = 0; $v < $lom; $v++) {
            echo $html->image('evaluations/circle.gif', array('align'=>'middle', 'vspace'=>'1', 'hspace'=>'1','alt'=>'circle'));
            $empty--;
        }
        for ($t=0; $t < $empty; $t++) {
            echo $html->image('evaluations/circle_empty.gif', array('align'=>'middle', 'vspace'=>'1', 'hspace'=>'1','alt'=>'circle_empty'));
        }
        echo "<br />";
        //Grade Detail
        echo "<strong>".__('Grade:', true)." </strong>";
        echo $grade["grade"] . " / " . $multiplier[$num] . "<br />";
        //Comments
        $chkParam = array(
            'value' => $grade['id'],
            'hiddenField' => false,
            'name' => 'releaseComments[]',
            'checked' => $grade['comment_release'], 
        );
        echo "<br/>";
        echo $form->checkbox($chkParam['name'], $chkParam);
        echo "<strong>".__('Comment:', true)." </strong>";
        echo $grade["comment"];
        echo "</td>";
    }
    echo "</tr>";
    //General Comment
    echo "<tr><td></td>";
    echo "<td colspan=".$col."><strong>".__('General Comment:', true)." </strong><br>";
    $checkParam = array(
        'value' => $evaluator,
        'hiddenField' => false,
        'name' => 'releaseGeneralCom[]', 
        'checked' => $comment['comment_release'],
    );
    echo $form->checkbox($checkParam['name'], $checkParam);
    echo $comment['comment'];
    echo "<br><br></td></tr>";
}
?>
</table>
<?php if ($viewReleaseBtns) { ?>
<input name="submit" type="submit" value="<?php echo __('Release All Comments', true); ?>">
<input name="submit" type="submit" value="<?php echo __('Save Changes', true); ?>">
<input name="submit" type="submit" value="<?php echo __('Unrelease All Comments', true); ?>"><br>
<input type="button" name="ReleaseGrades" value="<?php __('Release Grades')?>" onClick="location.href='<?php echo $this->webroot.$this->theme.'evaluations/markGradeRelease/'.$event['GroupEvent']['id'].'/1/'.$evaluatee; ?>'">
<input type="button" name="UnreleaseGrades" value="<?php __('Unrelease Grades')?>" onClick="location.href='<?php echo $this->webroot.$this->theme.'evaluations/markGradeRelease/'.$event['GroupEvent']['id'].'/0/'.$evaluatee; ?>'">
<?php } ?>
</form>