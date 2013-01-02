<?php
$isReview = isset($isReview) && $isReview;
$gradeReleased = $isReview || (isset($scoreRecords[User::get('id')])? $scoreRecords[User::get('id')]['grade_released']: 1);
$commentReleased = $isReview || (isset($scoreRecords[User::get('id')])? $scoreRecords[User::get('id')]['comment_released']: 1);
$color = array("", "#FF3366","#ff66ff","#66ccff","#66ff66","#ff3333","#00ccff","#ffff33");
?>
<table class="standardtable" style="margin-top: 1em;">
    <tr>
        <th width="100"><?php __('Person Being Evaluated')?></th>
        <?php foreach ($rubricCriteria as $criteria): ?>
        <th><?php echo $criteria['criteria']?></th>
        <?php endforeach; ?>
    </tr>
    <?php if (!$gradeReleased && !$commentReleased):?>
        <?php $cols = $rubric['Rubric']["criteria"]+1; ?>
    <tr>
        <td colspan="<?php echo $cols ?>">
            <font color="red"><?php echo ($isReview ? '':__('Comments/Grades Not Released Yet.', true)) ?></font>
        </td>
    </tr>
    <?php else: ?>
        <?php if (isset($evalResult[$userId])) {
        //Retrieve the individual rubric detail
            $memberResult = $evalResult[$userId];
            if (isset($scoreRecords)) {
                shuffle($memberResult);
            }
        foreach ($memberResult AS $row):
            $memberRubric = $row['EvaluationRubric']; ?>
    <tr>
        <?php if (User::get('id')!=$row['EvaluationRubric']['evaluator']) { ?>
            <td width='15%'><?php echo User::get('full_name') ?></td>
        <?php } else {
            $member = $membersAry[$memberRubric['evaluatee']]; ?>
            <td width='15%' rowspan="2"><?php echo $member['User']['first_name'].' '.$member['User']['last_name'] ?></td>
        <?php }
        $resultDetails = $memberRubric['details'];
        foreach ($resultDetails as $detail) : $rubDet = $detail['EvaluationRubricDetail']; ?>
            <td valign="middle"><br />
                <!-- Points Detail -->
                <strong><?php echo __('Points', true) ?>: </strong>
                <?php if ($gradeReleased && isset($rubDet)) {
                    for ($v = 0; $v < $rubDet["selected_lom"]; $v++) {
                        echo $html->image('evaluations/circle.gif', array('align'=>'middle', 'vspace'=>'1', 'hspace'=>'1','alt'=>'circle'));
                    }
                    for ($t=0; $t < $rubric["Rubric"]["lom_max"] - $rubDet["selected_lom"]; $t++) {
                        echo $html->image('evaluations/circle_empty.gif', array('align'=>'middle', 'vspace'=>'1', 'hspace'=>'1','alt'=>'circle_empty'));
                    }
                } else {
                    echo __('n/a', true);
                }

                //Grade Detail
                echo "<br/><strong>Grade: </strong>";
                if ($gradeReleased && isset($rubDet)) {
                    //echo $rubDet["grade"] . " / " . $rubricCriteria['criteria_weight_'.$rubDet['criteria_number']];
                    echo $rubDet["grade"];
                } else {
                    echo __('n/a', true);
                }
                //Comments
                ?>
                <br/><strong><?php echo __('Comment', true) ?>: </strong>
                <?php echo $commentReleased && isset($rubDet)?$rubDet["criteria_comment"]: __('n/a', true)?>
            </td>
        <?php endforeach; ?>
     </tr>
     <!-- General Comment -->
     <tr>
        <?php $col = $rubric['Rubric']['criteria'] + 1; ?>
        <td colspan="<?php echo $col ?>">
            <strong><?php __('General Comment') ?>: </strong><br>
            <?php echo ($commentReleased ? $memberRubric['comment'] : __('n/a', true))?><br><br>
        </td>
     </tr>
    <?php endforeach;
        }
?>
<?php endif; ?>
</table>
