<?php echo $html->script('groups')?>
<?php $readonly = isset($readonly) ? $readonly : false;?>
<?php $status = (isset($submissions) && $submissions > 0 && !User::hasPermission('functions/superadmin')) ? 'disabled' : '';?>
<?php $id = $this->action == 'add' ? $course_id : $group_id; ?>

<div class="content-container">
<?php
echo $this->Form->create('Group',
    array('id' => 'frm',
    'url' => array('action' => $this->action.'/'.$id),
));
echo isset($group_id) ? $this->Form->hidden('Group.id', array('value' => $group_id)):'';
echo $this->Form->hidden('Group.course_id', array('value' => $course_id));
echo $this->Form->input('group_num', array('size'=>'50', 'class'=>'input',
    'readonly' => true, 'value' => $group_num, 'label' => __('Group Number', true)));
echo $this->Form->input('group_name', array('size'=>'50', 'class'=>'input', 'label' => __('Group Name', true),
    'readonly' => $readonly));
?>
<div class="input select">
<?php echo $this->Form->label('', __('Members', true))?>
    <div style="margin-left: 14em; width: 28em; text-align:center">
    <?php echo $this->element("groups/group_list_chooser",
                array('all' => $user_data, 'status' => $status,
                      'allName' =>  __("Filtered Students", true), 'selectedName' => __('Students in Group', true)));
    ?>
     <font size="2em">
     <?php __('Note: Students already in one or more groups are marked with *')?><br>
     </font>
     </div>
</div>
<div class="red" style="text-align:center"><?php !empty($status) ? 
__('There has been submissions in this group. Changing group members may cause data integrity issue. 
In the evaluation results of events that have already ended, new members will be marked down as having no submissions, 
which means no participation grades.') : ''?></div>
    <?php echo $this->Form->submit(ucfirst($this->action).__(' Group', true), array(
        'onClick' => "processSubmit(document.getElementById('selected_groups'))")) ?>
</div>
<?php echo $this->Form->end();?>
