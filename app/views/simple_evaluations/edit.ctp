<?php
    $readonly = isset($readonly) ? $readonly : false;
    if($this->action == 'copy') $this->action = 'add';
?>
    <?php echo $this->Form->create('SimpleEvaluation', array(
                'id' => 'frm',
                'url' => array(
                    'controller' => 'simpleevaluations',
                    'action' => $this->action
                ),
            )
        )
    ?>
    <input type="hidden" name="required" id="required" value="SimpleEvaluationName SimpleEvaluationPointPerMember" />
        <!-- Evaluation Name -->
            <?php echo $this->Form->input('name', array(
                        'size'=>'80',
                        'class'=>'validate required TEXT_FORMAT name_msg Invalid_name.',
                        'error' => array('unique' => __('Duplicate name found. Please change the name.', true)),
                        'readonly' => $readonly
                    )
                )
            ?>
            <?php echo $readonly ? '' : $ajax->observeField('name', array('update'=>'nameErr', 'url'=>'checkDuplicateTitle/', 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');stripe();")); ?>
            <div id='usernameErr' class="error"></div>

        <!-- Description -->
            <?php echo $this->Form->input('description', array(
                        'cols' => 60,
                        'rows' => 10,
                        'class' => 'validate none none',
                        'readonly' => $readonly
                    )
                )
            ?>
            <div id="description_msg" class="error"></div>

        <!-- Base Point Per Member -->
            <?php echo $this->Form->input('point_per_member', array(
                        'size'=>'5',
                        'class'=>'validate required NUMERIC_FORMAT point_per_member_msg Invalid_Number_Value.',
                        'readonly' => $readonly,
                        'onChange' => 'return ensureEntriesValid();'
                    )
                )
            ?>
            <div id="point_per_member_msg" class="error"></div>

        <!-- Template Availability -->
            <?php echo $this->Form->input('availability', array(
                        'id' => 'availability',
                        'type' => 'radio',
                        'options' => array('public' => __('Public', true), 'private' => __('Private', true)),
                        'label' => __('Availability', true),
                        'separator' => '&nbsp;',
                        'disabled' => $readonly
                    )
                )
            ?>

            <div style="text-align: center">
                <input type="button" name="Back" value="<?php __('Back')?>" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
                <?php echo $this->Form->submit(__('Save', true),  array('div' => false, 'onclick' => 'return validate();')); ?>
            </div>
    <?php echo $this->Form->end();?>


<script>
// Ensure that the entries are valid
function ensureEntriesValid() {
    var bppm = $("SimpleEvaluationPointPerMember");
    if (bppm.value > 0) {
        return true;
    }

    alert (__("Base points per member *must be* at least 1 point.\nHowever, at least 10 is recommended.", true));
    bppm.value = 10;
    bppm.focus();
    bppm.select();
    return false;
}
</script>
