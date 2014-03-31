<?php
$gradeReleased = $status['autoRelease'] || (isset($release)? $release['grade']: 1);
$commentReleased = $status['autoRelease'] || (isset($release)? $release['comment']: 1);
?>
<table class="standardtable" style="margin-top: 1em;">
    <tr>
        <th width="100"><?php __('Person Being Evaluated')?></th>
        <?php foreach ($rubric['RubricsCriteria'] as $criteria) { ?>
            <th><?php echo $criteria['criteria']?></th>
        <?php } ?>
    </tr>
    <?php if (!$gradeReleased && !$commentReleased) {
        $cols = $rubric['Rubric']["criteria"]+1; ?>
        <tr><td colspan="<?php echo $cols ?>">
            <font color="red"><?php echo __(' Comments/Grades Not Released Yet.', true) ?></font>
        </td></tr>
    <?php } else { 
        if (!empty($details)) {
            shuffle($details);
            foreach ($details AS $row) {
                $memberRubric = $row['EvaluationRubric']; ?>
                <tr><td width='15%'><?php echo $membersList[$memberRubric['evaluatee']] ?></td>
                <?php foreach ($row['EvaluationRubricDetail'] as $detail) { ?>
                    <td valign="middle">
                    <!-- Points Detail -->
                    <?php if ($gradeReleased) {?>
                        <br /><strong><?php echo __('Points', true) ?>: </strong>
                        <?php if ($gradeReleased && isset($detail)) {
                            for ($v = 0; $v < $detail["selected_lom"]; $v++) {
                                echo $html->image('evaluations/circle.gif', array('align'=>'middle', 'vspace'=>'1', 'hspace'=>'1','alt'=>'circle'));
                            }
                            for ($t=0; $t < $rubric["Rubric"]["lom_max"] - $detail["selected_lom"]; $t++) {
                                echo $html->image('evaluations/circle_empty.gif', array('align'=>'middle', 'vspace'=>'1', 'hspace'=>'1','alt'=>'circle_empty'));
                            }
                        } else {
                            echo __('n/a', true);
                        }
                        //Grade Detail
                        echo "<br/><strong>Grade: </strong>";
                        if ($gradeReleased && isset($detail)) {
                            echo $detail["grade"];
                        } else {
                            echo __('n/a', true);
                        }
                    }
                    //Comments
                    ?>
                    <br/><strong><?php echo __('Comment', true) ?>: </strong>
                    <?php echo isset($detail) && ($detail['comment_release'] || isset($status['review'])) ? $detail["criteria_comment"]: __('n/a', true)?>
                    </td>
                <?php } ?>
            </tr>
            <!-- General Comment -->
            <tr>
            <?php $col = $rubric['Rubric']['criteria'] ?>
            <td> </td>
            <td colspan="<?php echo $col ?>">
                <strong><?php __('General Comment') ?>: </strong><br>
                <?php echo ($memberRubric['comment_release'] || isset($status['review']) ? $memberRubric['comment'] : __('n/a', true))?><br><br>
            </td>
            </tr>
    <?php }}}?>
</table>