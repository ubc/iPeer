<div class="content-container" align=center>
    <table width="95%"  border="0" cellspacing="2" cellpadding="8">
        <tr class="tableheader">
            <td><?php __('Instructions')?></td>
        </tr>
        <tr class="tablecell2">
            <td><?php echo $this->element('users/user_import_info');?></td>
        </tr>
        <tr class="tableheader">
            <td><?php __('Import')?></td>
        </tr>
        <tr class="tablecell2">
            <td>
            <?php echo $this->Form->create(null, array('action' => 'importFile',
                                                       'type' => 'file',
                                                       'onSubmit' => 'return import_validate();',
                                                       'id' => 'importfrm',
                                                       'name' => 'importfrm'));?>

            <input type="hidden" name="required" value="course_id"/>
            <?php echo $this->Form->hidden('User/role'); ?>
            <ol>
                <li><h3><?php __('Please select a CSV file to import:')?></h3><?php echo $this->Form->file('file', array('name' => 'file', 'class' => 'required')); ?></li>
                <li><h3><?php __('Select the course to import into:')?></h3><?php echo $this->element('courses/course_selection_box', $courseParams); ?></li>
                <li><h3><?php __('Click the button below to import the students:')?></h3><?php echo $this->Form->submit(__('Import', true))?></li>
            </ol>
            <?php echo $this->Form->end();?>
            </td>
        </tr>
    </table>
</div>
