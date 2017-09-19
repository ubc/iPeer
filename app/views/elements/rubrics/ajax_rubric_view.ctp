<!-- elements::ajax_rubric_view start -->
<?php
$LOM_num = $data['Rubric']['lom_max'];
$criteria_num = $data['Rubric']['criteria'];
$rubric_type = $data['Rubric']['template'];
$zero_mark = $data['Rubric']['zero_mark'];
isset($user)? $userId = $user['id'] : $userId = '';
isset($user['Evaluation'])? $evaluation = $user['Evaluation'] : $evaluation = null;
$reqCom = isset($event) && $event['Event']['com_req'] ? '<br><font color="red">('.__('required', true).')</font>' : '';
if(!isset($viewMode)){
    $viewMode = 0;
}
?>

<!-- Sort by Student -->
<?php if($viewMode == 0): ?>
<input type="hidden" name="member_<?php echo $userId; ?>_updated" value="">
<table class="standardtable">
    <tr>
        <th width=150 valign="top"></th>
        <!-- // horizontal template type -->
        <?php if ( $rubric_type == "horizontal" ):?>
            <?php foreach($data['RubricsLom'] as $lom): ?>
                <th><?php echo $lom['lom_comment']?></th>
            <?php endforeach ?>
            <!-- //Comment for Evaluation Form -->
            <?php if ($evaluate):?>
                <th><?php echo __('Comments', true).$reqCom ?></th>
            <?php endif ?>
    </tr>

    <?php foreach ($data['RubricsCriteria'] as $criteria): $i = $criteria['criteria_num']; ?>
        <?php if (isset($evaluation['EvaluationDetail'][$i-1]['EvaluationRubricDetail']['selected_lom'])) :?>
            <input type="hidden" name="selected_lom_<?php echo $userId.'_'.$i?>" value="<?php echo $evaluation['EvaluationDetail'][$i-1]['EvaluationRubricDetail']['selected_lom']?>">
        <?php else: ?>
            <input type="hidden" name="selected_lom_<?php echo $userId.'_'.$i?>" value="" size="4" >
        <?php endif ?>
    <tr>
        <th style="text-align: left; padding: 0.5em;">
            <?php echo '<font id="'.$userId.'criteria'.$i.'">'.$criteria['criteria']?></font><br><br>
            <i><?php echo $criteria['multiplier']?><?php __(' mark(s)')?></i>
        </th>

        <?php foreach($data['RubricsLom'] as $lom): ?>
        <?php $mark_value = round( ($criteria['multiplier']/(count($data['RubricsLom']) - ('1' == $zero_mark ? 1 : 0))*($lom['lom_num'] - ('1' == $zero_mark ? 1 : 0))) , 2);?>
        <td>
            <div class="green"><?php echo (!empty($criteria['RubricsCriteriaComment'][$lom['lom_num']-1]['criteria_comment']) ? $criteria['RubricsCriteriaComment'][$lom['lom_num']-1]['criteria_comment'] : '')?></div>
            <?php
            $check = '';
            if (isset($evaluation['EvaluationDetail'][$i-1]['EvaluationRubricDetail']['selected_lom'])) {
                if ($evaluation['EvaluationDetail'][$i-1]['EvaluationRubricDetail']['selected_lom'] == $lom['lom_num']) $check = "checked";
            }?>
            <div>
                <input name="<?php echo $userId.'criteria_points_'.$i?>" type="radio" value="<?php echo $mark_value?>"
                    onClick="document.evalForm.selected_lom_<?php echo $userId."_".$i?>.value=<?php echo $lom['lom_num']?>;document.evalForm.member_<?php echo $userId; ?>_updated.value=1;"
                    <?php echo $check?>/>
            </div>

            <?php if ($criteria['show_marks'] == 1): ?>
                <?php if (!$evaluate): ?>
                    <div><?php __('Mark')?>: <?php echo $mark_value?></div>
                <?php endif; ?>
            <?php endif; ?>
        </td>
        <?php endforeach ?>

        <?php if ($evaluate): ?>
        <td>
            <textarea cols="20" rows="2" name="<?php echo $userId?>comments[]"
                onChange="document.evalForm.member_<?php echo $userId; ?>_updated.value=1;"
                ><?php echo (isset($evaluation['EvaluationDetail'][$i-1]['EvaluationRubricDetail']['criteria_comment']) ?
                    $evaluation['EvaluationDetail'][$i-1]['EvaluationRubricDetail']['criteria_comment'] : ''); ?></textarea>
        </td>
        <?php endif;?>
    </tr>
    <?php endforeach;?>
    <?php endif; ?>
    <tr>
        <?php if (!$evaluate): ?>
        <td colspan="<?php echo $LOM_num+1?>" align="right"><?php __('Total Marks')?>: <?php echo $data['Rubric']['total_marks']?></td>
        <?php else: ?>
        <td colspan="<?php echo $LOM_num+2?>" align="center" class="tableheader2"><?php echo __('General Comments').$reqCom ?><br>
            <textarea cols="80" rows="2" name="<?php echo $userId?>gen_comment"
                onchange="document.evalForm.member_<?php echo $userId; ?>_updated.value=1;"
                ><?php echo (isset($evaluation) ? $evaluation['EvaluationRubric']['comment'] : '')?></textarea>
        </td>
        <?php endif;?>
    </tr>
</table>

<!-- Sort by Criteria -->
<?php elseif($viewMode == 1): ?>
<table class="standardtable">
    <tr>
        <th width=150 valign="top"></th>
        <!-- // horizontal template type -->
        <?php if ( $rubric_type == "horizontal" ):?>
            <?php foreach($data['RubricsLom'] as $lom): ?>
                <th><?php __('Level of Mastery')?> <?php echo $lom['lom_num']?>:<br><?php echo $lom['lom_comment']?></th>
            <?php endforeach ?>
            <!-- //Comment for Evaluation Form -->
            <?php if ($evaluate):?>
                <th><?php echo __('Comments', true).$reqCom ?></th>
            <?php endif ?>
    </tr>

    <?php foreach ($groupMembers as $row): $user = $row['User']?>
        <?php $i = $criteria['criteria_num']; ?>
        <?php $userId = $user['id'];?>
        <?php
            isset($user['Evaluation'])? $evaluation = $user['Evaluation'] : $evaluation = null;
         ?>

        <?php
            // Sets the selected lom for the current criteria
            $selectedLom = '';
            if (isset($evaluation['EvaluationDetail'])) {
                foreach ($evaluation['EvaluationDetail'] as $evalDetail) {
                    if ($evalDetail['EvaluationRubricDetail']['criteria_number'] == $i) {
                        $selectedLom = $evalDetail['EvaluationRubricDetail']['selected_lom'];
                        break;
                    }
                }
            }
        ?>

        <input type="hidden" name="selected_lom_<?php echo $userId.'_'.$i?>" value="<?php echo $selectedLom?>">
    <tr>
        <th style="text-align: left; padding: 0.5em;">
            <?php echo '<font id="'.$userId.'criteria'.$i.'">'.$user['first_name'].' '.$user['last_name']?></font><br><br>
            <i><?php echo $criteria['multiplier']?><?php __(' mark(s)')?></i>
        </th>

        <?php foreach($data['RubricsLom'] as $lom): ?>
        <?php $mark_value = round( ($criteria['multiplier']/(count($data['RubricsLom']) - ('1' == $zero_mark ? 1 : 0))*($lom['lom_num'] - ('1' == $zero_mark ? 1 : 0))) , 2);?>
        <td>
            <div class="green"><?php echo (!empty($criteria['RubricsCriteriaComment'][$lom['lom_num']-1]['criteria_comment']) ? $criteria['RubricsCriteriaComment'][$lom['lom_num']-1]['criteria_comment'] : '')?></div>
            <?php
                // Make sure the radio button is checked if the selected lom is set with same value
                $check = '';
                if (isset($evaluation['EvaluationDetail'])){
                    foreach ($evaluation['EvaluationDetail'] as $evalDetail) {
                        if ($evalDetail['EvaluationRubricDetail']['criteria_number'] == $i) {
                            if ($evalDetail['EvaluationRubricDetail']['selected_lom'] == $lom['lom_num']) {
                                $check = "checked";
                                break;
                            }
                        }
                    }
                }
            ?>
            <div>
                <input name="<?php echo $userId.'criteria_points_'.$i?>" type="radio" value="<?php echo $mark_value?>"
                    onClick="document.evalForm.selected_lom_<?php echo $userId."_".$i?>.value=<?php echo $lom['lom_num']?>;document.evalForm.member_<?php echo $userId; ?>_updated.value=1;"
                    <?php echo $check?>/>
            </div>

            <?php if ($criteria['show_marks'] == 1): ?>
                <?php if (!$evaluate): ?>
                    <div><?php __('Mark')?>: <?php echo $mark_value?></div>
                <?php endif; ?>
            <?php endif; ?>
        </td>
        <?php endforeach ?>

        <?php if ($evaluate): ?>
        <td>
            <?php
                // Find the corresponding comment
                $comment = '';
                if (isset($evaluation['EvaluationDetail'])) {
                    foreach ($evaluation['EvaluationDetail'] as $evalDetail) {
                        if ($evalDetail['EvaluationRubricDetail']['criteria_number'] == $i) {
                            $comment = $evalDetail['EvaluationRubricDetail']['criteria_comment'];
                        }
                    }
                }
            ?>
            <textarea cols="20" rows="2" name="<?php echo $userId?>comments[]"><?php echo $comment; ?></textarea>
        </td>
        <?php endif;?>

    </tr>
    <?php endforeach;?>
    <?php endif; ?>
</table>
<?php endif; ?>

<!-- elements::ajax_rubric_preview end -->
<script type="text/javascript">
jQuery().ready(function() {
    var viewMode = <?php echo $viewMode ?>;
    if(viewMode == 0){
        var userId = <?php echo $userId ?>;
    }
    else if(viewMode == 1){
        var userId = <?php echo $criteria['criteria_num'] ?>;
    }
    jQuery("input[type='submit'][name='"+userId+"']").click(function() {
        return saveButtonVal(userId, viewMode);
    });
});
</script>
